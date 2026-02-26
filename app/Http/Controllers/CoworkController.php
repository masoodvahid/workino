<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\Space;
use App\Models\SubSpace;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
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
                    ->with('subSpaceMetas'),
            ])
            ->first();

        if (! $space) {
            throw new ModelNotFoundException();
        }

        return view('coworks.show', compact('space'));
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
            ->with('subSpaceMetas')
            ->first();

        if (! $subSpace) {
            throw new ModelNotFoundException();
        }

        return view('coworks.subspace-show', compact('space', 'subSpace'));
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
}
