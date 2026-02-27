<?php

namespace App\Http\Controllers;

use App\Enums\CommentStatus;
use App\Enums\InteractableType;
use App\Enums\Status;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Space;
use App\Models\SubSpace;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CoworkController extends Controller
{
    public function home(): View
    {
        $spaces = Space::query()
            ->where('status', Status::Active)
            ->with(['spaceMetas', 'subSpaces'])
            ->latest()
            ->limit(6)
            ->get();

        $subSpaceTypes = $this->getSubSpaceTypeOptions();

        return view('index', compact('spaces', 'subSpaceTypes'));
    }

    public function index(Request $request): View
    {
        $sort = $request->string('sort')->toString() ?: 'newest';
        $keyword = $request->string('q')->toString();
        $selectedSubSpaceTypes = collect($request->input('subspace_types', []))
            ->filter(fn (mixed $type): bool => is_string($type) && filled($type))
            ->values()
            ->all();

        if (blank($selectedSubSpaceTypes)) {
            $legacyType = $request->string('subspace_type')->toString();
            $selectedSubSpaceTypes = filled($legacyType) ? [$legacyType] : [];
        }

        $query = Space::query()
            ->where('status', Status::Active)
            ->with(['spaceMetas', 'subSpaces']);

        if (filled($keyword)) {
            $query->where('title', 'like', '%' . $keyword . '%');
        }

        if (filled($selectedSubSpaceTypes)) {
            $query->whereHas('subSpaces', function (Builder $subSpaceQuery) use ($selectedSubSpaceTypes): void {
                $subSpaceQuery
                    ->where('status', Status::Active)
                    ->whereIn('type', $selectedSubSpaceTypes);
            });
        }

        match ($sort) {
            'title' => $query->orderBy('title'),
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };

        $spaces = $query
            ->paginate(9)
            ->withQueryString();

        return view('coworks.index', compact('spaces', 'sort', 'keyword', 'selectedSubSpaceTypes'));
    }

    public function show(string $slug): View
    {
        $space = Space::query()
            ->where('status', Status::Active)
            ->where('slug', $slug)
            ->with([
                'spaceMetas',
                'subSpaces' => fn (Builder $query): Builder => $query
                    ->where('status', Status::Active)
                    ->with(['subSpaceMetas', 'prices']),
            ])
            ->first();

        if (! $space) {
            throw new ModelNotFoundException();
        }

        $comments = Comment::query()
            ->where('type', InteractableType::Space)
            ->where('parent_id', $space->id)
            ->where('status', CommentStatus::Approve)
            ->with('user')
            ->latest()
            ->get();

        $likesCount = $this->likesCount(InteractableType::Space, $space->id);
        $isLiked = $this->isLiked(InteractableType::Space, $space->id);

        return view('coworks.show', compact('space', 'comments', 'likesCount', 'isLiked'));
    }

    public function liveSearch(Request $request): JsonResponse
    {
        $keyword = $request->string('q')->toString();
        $subSpaceTypes = collect($request->input('subspace_types', []))
            ->filter(fn (mixed $type): bool => is_string($type) && filled($type))
            ->values()
            ->all();

        if (blank($subSpaceTypes)) {
            $legacyType = $request->string('subspace_type')->toString();
            $subSpaceTypes = filled($legacyType) ? [$legacyType] : [];
        }

        $query = Space::query()
            ->where('status', Status::Active)
            ->with(['spaceMetas', 'subSpaces']);

        if (filled($keyword)) {
            $query->where('title', 'like', '%' . $keyword . '%');
        }

        if (filled($subSpaceTypes)) {
            $query->whereHas('subSpaces', function (Builder $subSpaceQuery) use ($subSpaceTypes): void {
                $subSpaceQuery
                    ->where('status', Status::Active)
                    ->whereIn('type', $subSpaceTypes);
            });
        }

        $spaces = $query
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn (Space $space): array => [
                'title' => $space->title,
                'slug' => $space->slug,
                'url' => route('spaces.show', $space->slug),
                'sub_spaces_count' => $space->subSpaces->count(),
                'image' => $this->resolveSpaceImage($space),
            ])
            ->values();

        return response()->json(['data' => $spaces]);
    }

    public function showSubSpace(string $spaceSlug, string $subSpaceSlug): View
    {
        $space = Space::query()
            ->where('status', Status::Active)
            ->where('slug', $spaceSlug)
            ->with('spaceMetas')
            ->first();

        if (! $space) {
            throw new ModelNotFoundException();
        }

        $subSpace = SubSpace::query()
            ->where('space_id', $space->id)
            ->where('status', Status::Active)
            ->where('slug', $subSpaceSlug)
            ->with(['subSpaceMetas', 'prices'])
            ->first();

        if (! $subSpace) {
            throw new ModelNotFoundException();
        }

        $comments = Comment::query()
            ->where('type', InteractableType::Subspace)
            ->where('parent_id', $subSpace->id)
            ->where('status', CommentStatus::Approve)
            ->with('user')
            ->latest()
            ->get();

        $likesCount = $this->likesCount(InteractableType::Subspace, $subSpace->id);
        $isLiked = $this->isLiked(InteractableType::Subspace, $subSpace->id);

        return view('coworks.subspace-show', compact('space', 'subSpace', 'comments', 'likesCount', 'isLiked'));
    }

    public function storeSpaceComment(Request $request, string $slug): RedirectResponse
    {
        $space = Space::query()
            ->where('status', Status::Active)
            ->where('slug', $slug)
            ->first();

        if (! $space) {
            throw new ModelNotFoundException();
        }

        $this->storeComment($request, InteractableType::Space, $space->id);

        return redirect()
            ->route('spaces.show', $space->slug)
            ->with('comment_status', 'نظر شما ثبت شد و پس از بررسی نمایش داده می‌شود.');
    }

    public function storeSubSpaceComment(Request $request, string $spaceSlug, string $subSpaceSlug): RedirectResponse
    {
        $space = Space::query()
            ->where('status', Status::Active)
            ->where('slug', $spaceSlug)
            ->first();

        if (! $space) {
            throw new ModelNotFoundException();
        }

        $subSpace = SubSpace::query()
            ->where('space_id', $space->id)
            ->where('status', Status::Active)
            ->where('slug', $subSpaceSlug)
            ->first();

        if (! $subSpace) {
            throw new ModelNotFoundException();
        }

        $this->storeComment($request, InteractableType::Subspace, $subSpace->id);

        return redirect()
            ->route('spaces.subspaces.show', ['spaceSlug' => $space->slug, 'subSpaceSlug' => $subSpace->slug])
            ->with('comment_status', 'نظر شما ثبت شد و پس از بررسی نمایش داده می‌شود.');
    }

    public function toggleSpaceLike(Request $request, string $slug): RedirectResponse
    {
        $space = Space::query()
            ->where('status', Status::Active)
            ->where('slug', $slug)
            ->first();

        if (! $space) {
            throw new ModelNotFoundException();
        }

        $this->toggleLike($request, InteractableType::Space, $space->id);

        return redirect()->route('spaces.show', $space->slug);
    }

    public function toggleSubSpaceLike(Request $request, string $spaceSlug, string $subSpaceSlug): RedirectResponse
    {
        $space = Space::query()
            ->where('status', Status::Active)
            ->where('slug', $spaceSlug)
            ->first();

        if (! $space) {
            throw new ModelNotFoundException();
        }

        $subSpace = SubSpace::query()
            ->where('space_id', $space->id)
            ->where('status', Status::Active)
            ->where('slug', $subSpaceSlug)
            ->first();

        if (! $subSpace) {
            throw new ModelNotFoundException();
        }

        $this->toggleLike($request, InteractableType::Subspace, $subSpace->id);

        return redirect()->route('spaces.subspaces.show', ['spaceSlug' => $space->slug, 'subSpaceSlug' => $subSpace->slug]);
    }

    private function resolveSpaceImage(Space $space): string
    {
        $featuredImage = $space->metaValue('featured_image');

        if (blank($featuredImage)) {
            return 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?q=80&w=2069&auto=format&fit=crop';
        }

        if (Str::startsWith((string) $featuredImage, ['http://', 'https://', '/'])) {
            return (string) $featuredImage;
        }

        return Storage::disk('public')->url((string) $featuredImage);
    }

    /**
     * @return array<string, string>
     */
    private function getSubSpaceTypeOptions(): array
    {
        return [
            'seat' => 'میز',
            'room' => 'اتاق',
            'meeting_room' => 'اتاق جلسه',
            'conference_room' => 'اتاق کنفرانس',
            'coffeeshop' => 'کافه',
        ];
    }

    private function storeComment(Request $request, InteractableType $type, int $parentId): void
    {
        $data = $request->validate([
            'content' => ['nullable', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        Comment::query()->create([
            'user_id' => $request->user()->id,
            'type' => $type,
            'parent_id' => $parentId,
            'content' => $data['content'] ?? null,
            'rating' => (int) $data['rating'],
            'status' => CommentStatus::Pending,
        ]);
    }

    private function toggleLike(Request $request, InteractableType $type, int $parentId): void
    {
        $like = Like::query()
            ->where('user_id', $request->user()->id)
            ->where('type', $type)
            ->where('parent_id', $parentId)
            ->first();

        if ($like) {
            $like->delete();

            return;
        }

        Like::query()->create([
            'user_id' => $request->user()->id,
            'type' => $type,
            'parent_id' => $parentId,
            'created_at' => now(),
        ]);
    }

    private function likesCount(InteractableType $type, int $parentId): int
    {
        return Like::query()
            ->where('type', $type)
            ->where('parent_id', $parentId)
            ->count();
    }

    private function isLiked(InteractableType $type, int $parentId): bool
    {
        if (! auth()->check()) {
            return false;
        }

        return Like::query()
            ->where('user_id', auth()->id())
            ->where('type', $type)
            ->where('parent_id', $parentId)
            ->exists();
    }
}
