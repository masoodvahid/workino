<x-layouts.app :title="$space->title . ' | ورکینو'">
    @php
        $featuredImage = $space->metaValue('featured_image');
        $image = blank($featuredImage)
            ? 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=2069&auto=format&fit=crop'
            : ((\Illuminate\Support\Str::startsWith((string) $featuredImage, ['http://', 'https://', '/']))
                ? (string) $featuredImage
                : \Illuminate\Support\Facades\Storage::disk('public')->url((string) $featuredImage));

        $gallery = collect($space->metaValue('images'))
            ->filter(fn ($item) => filled($item))
            ->map(function ($item) {
                return \Illuminate\Support\Str::startsWith((string) $item, ['http://', 'https://', '/'])
                    ? (string) $item
                    : \Illuminate\Support\Facades\Storage::disk('public')->url((string) $item);
            })
            ->values();
        $topThumbs = $gallery->take(4);
        $remainingGallery = $gallery->slice(4)->values();

        $logoValue = $space->metaValue('logo');
        $logo = blank($logoValue)
            ? null
            : (\Illuminate\Support\Str::startsWith((string) $logoValue, ['http://', 'https://', '/'])
                ? (string) $logoValue
                : \Illuminate\Support\Facades\Storage::disk('public')->url((string) $logoValue));

        $typeLabels = [
            'seat' => 'میز',
            'room' => 'اتاق',
            'meeting_room' => 'اتاق جلسه',
            'conference_room' => 'اتاق کنفرانس',
            'coffeeshop' => 'کافه',
        ];

        $city = \App\Enums\City::tryFrom((string) $space->metaValue('city'))?->getLabel() ?? 'نامشخص';
        $locationNeshan = $space->metaValue('location_neshan');
        $socials = collect($space->metaValue('social'))
            ->filter(fn ($item): bool => is_array($item) && filled($item['url'] ?? null))
            ->values();
        $phones = collect($space->metaValue('phones'))
            ->filter(fn ($item): bool => is_array($item) && filled($item['phone_number'] ?? null))
            ->values();
        $actionButtonClasses = 'inline-flex items-center gap-2 px-3 py-2 rounded-full border border-white/40 bg-white/70 text-sm font-medium text-gray-700 shadow-sm backdrop-blur-md hover:bg-white transition';
    @endphp

    <section class="bg-gray-50 py-8 min-h-screen">
        <div class="container mx-auto px-4 space-y-8">
            <div class="flex items-center justify-between">
                <a href="{{ route('spaces.index') }}" class="text-sm text-blue-600 hover:text-blue-700">بازگشت به لیست فضاها</a>
                <span class="text-xs bg-white border border-gray-200 rounded-full px-3 py-1 text-gray-600">{{ $space->status?->getLabel() }}</span>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                <div class="p-2 bg-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-[6fr_2fr_2fr] md:grid-rows-2 gap-2">
                        <div class="md:row-span-2 rounded-xl overflow-hidden">
                            <button type="button" class="block w-full h-full" data-lightbox-trigger data-lightbox-src="{{ $image }}">
                                <img src="{{ $image }}" alt="{{ $space->title }}" class="w-full h-[260px] md:h-[420px] object-cover">
                            </button>
                        </div>

                        @foreach (range(0, 3) as $index)
                            <div class="rounded-xl overflow-hidden">
                                <button type="button" class="block w-full h-full" data-lightbox-trigger data-lightbox-src="{{ $topThumbs->get($index) ?? $image }}">
                                    <img src="{{ $topThumbs->get($index) ?? $image }}" alt="{{ $space->title }}" class="w-full h-[129px] md:h-[206px] object-cover">
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="p-6 md:p-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-center gap-3 md:order-1">
                            @auth
                                <form method="POST" action="{{ route('spaces.likes.toggle', $space->slug) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-full border px-3 py-2 transition {{ $isLiked ? 'border-red-200 bg-red-50 text-red-600' : 'border-gray-200 bg-white text-gray-500 hover:text-red-600 hover:border-red-200' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5" fill="{{ $isLiked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6.716-4.35-9.193-8.157C.61 9.465 2.087 5.5 5.86 5.5c2.129 0 3.39 1.137 4.14 2.353C10.75 6.637 12.01 5.5 14.14 5.5c3.773 0 5.25 3.965 3.053 7.343C18.716 16.65 12 21 12 21Z"/>
                                        </svg>
                                        <span class="text-sm font-medium">{{ number_format($likesCount) }}</span>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('auth.login') }}" class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-3 py-2 text-gray-500 hover:text-red-600 hover:border-red-200 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6.716-4.35-9.193-8.157C.61 9.465 2.087 5.5 5.86 5.5c2.129 0 3.39 1.137 4.14 2.353C10.75 6.637 12.01 5.5 14.14 5.5c3.773 0 5.25 3.965 3.053 7.343C18.716 16.65 12 21 12 21Z"/>
                                    </svg>
                                    <span class="text-sm font-medium">{{ number_format($likesCount) }}</span>
                                </a>
                            @endauth
                            @if (filled($logo))
                                <img src="{{ $logo }}" alt="لوگوی {{ $space->title }}" class="w-14 h-14 md:w-16 md:h-16 rounded-full object-cover border border-gray-200 shadow-sm">
                            @endif
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $space->title }}</h1>
                           
                        </div>
                        <div class="flex flex-wrap gap-2 md:order-2">
                            @if (filled($locationNeshan))
                                <a href="{{ $locationNeshan }}" target="_blank" rel="noopener noreferrer" class="{{ $actionButtonClasses }}">
                                    <span>مسیریابی</span>
                                </a>
                            @endif

                            @foreach ($socials as $social)
                                @php
                                    $title = (string) ($social['title'] ?? 'لینک');
                                    $url = (string) ($social['url'] ?? '#');
                                    $socialIconClass = trim((string) ($social['icon'] ?? ''));
                                @endphp
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="{{ $actionButtonClasses }}">
                                    @if (filled($socialIconClass))
                                        <i class="{{ $socialIconClass }} text-sm" aria-hidden="true"></i>
                                    @endif
                                    <span>{{ $title }}</span>
                                </a>
                            @endforeach

                            @foreach ($phones as $phoneItem)
                                @php
                                    $phoneTitle = (string) ($phoneItem['title'] ?? 'تماس');
                                    $phone = preg_replace('/\s+/', '', (string) ($phoneItem['phone_number'] ?? ''));
                                @endphp
                                @if (filled($phone))
                                    <a href="tel:{{ $phone }}" class="{{ $actionButtonClasses }}">
                                        <span>{{ $phoneTitle }}</span>
                                        <span>{{ $phone }}</span>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <p class="mt-4 text-gray-600 leading-8">{{ $space->metaValue('abstract') ?: ($space->note ?: 'توضیحی برای این فضا ثبت نشده است.') }}</p>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="rounded-xl bg-gray-50 border border-gray-100 p-4">
                            <div class="text-xs text-gray-500">شهر</div>
                            <div class="text-2xl font-bold text-gray-900 mt-1">{{ $city }}</div>
                        </div>
                        <div class="rounded-xl bg-gray-50 border border-gray-100 p-4">
                            <div class="text-xs text-gray-500">تعداد زیرمجموعه</div>
                            <div class="text-2xl font-bold text-gray-900 mt-1">{{ $space->subSpaces->count() }}</div>
                        </div>
                        <div class="rounded-xl bg-gray-50 border border-gray-100 p-4 md:col-span-2">
                            <div class="text-xs text-gray-500 mb-2">انواع فضاهای زیرمجموعه</div>
                            <div class="flex flex-wrap gap-2">
                                @forelse ($space->subSpaces->pluck('type')->unique() as $type)
                                    <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded text-xs">{{ $typeLabels[$type] ?? $type }}</span>
                                @empty
                                    <span class="text-sm text-gray-500">زیرمجموعه‌ای ثبت نشده است.</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($remainingGallery->isNotEmpty())
                <div class="bg-white border border-gray-100 rounded-2xl p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">گالری تصاویر بیشتر</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach ($remainingGallery as $galleryImage)
                            <button type="button" class="block w-full" data-lightbox-trigger data-lightbox-src="{{ $galleryImage }}">
                                <img src="{{ $galleryImage }}" alt="{{ $space->title }}" class="w-full h-32 object-cover rounded-lg border border-gray-100">
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="bg-white border border-gray-100 rounded-2xl p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">فضاهای قابل رزرو</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse ($space->subSpaces as $subSpace)
                        @php
                            $subFeatureImage = $subSpace->metaValue('feature_image');
                            $subImage = blank($subFeatureImage)
                                ? 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?q=80&w=2069&auto=format&fit=crop'
                                : ((\Illuminate\Support\Str::startsWith((string) $subFeatureImage, ['http://', 'https://', '/']))
                                    ? (string) $subFeatureImage
                                    : \Illuminate\Support\Facades\Storage::url((string) $subFeatureImage));
                            $startingPrice = $subSpace->prices
                                ->filter(fn ($price) => $price->status?->value === 'active')
                                ->sortBy(fn ($price) => $price->special_price ?: $price->base_price)
                                ->first();
                            $startingPriceValue = $startingPrice ? ($startingPrice->special_price ?: $startingPrice->base_price) : null;
                            $startingPriceUnit = $startingPrice?->unit?->getLabel();
                        @endphp

                        <a href="{{ route('spaces.subspaces.show', ['spaceSlug' => $space->slug, 'subSpaceSlug' => $subSpace->slug]) }}" class="block rounded-xl border border-gray-100 overflow-hidden bg-gray-50 hover:shadow-md transition">
                            <img src="{{ $subImage }}" alt="{{ $subSpace->title }}" class="w-full h-36 object-cover">
                            <div class="p-4">
                                <div class="flex items-start justify-between gap-2">
                                    <h3 class="font-bold text-gray-900">{{ $subSpace->title }}</h3>
                                    <span class="text-xs px-2 py-1 rounded bg-blue-50 text-blue-600">
                                        {{ $typeLabels[$subSpace->type] ?? $subSpace->type }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 mt-2 line-clamp-2">
                                    {{ $subSpace->metaValue('abstract') ?: 'توضیحی برای این فضا ثبت نشده است.' }}
                                </p>
                                <div class="mt-3 text-xs text-gray-600">
                                    ظرفیت: {{ $subSpace->capacity ?: 'نامشخص' }}
                                </div>
                                <div class="mt-3">
                                    @if ($startingPriceValue)
                                        <div class="text-xs text-gray-500">شروع قیمت</div>
                                        <div class="text-base font-bold text-gray-900 mt-1">
                                            {{ number_format($startingPriceValue) }}
                                            <span class="text-xs font-medium text-gray-500">/ {{ $startingPriceUnit }}</span>
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-400">قیمت ثبت نشده</div>
                                    @endif
                                </div>
                                <div class="mt-3 text-sm text-blue-600 font-medium">مشاهده جزئیات</div>
                            </div>
                        </a>
                    @empty
                        <div class="md:col-span-2 lg:col-span-3 text-center text-sm text-gray-500 py-8">
                            هنوز فضای قابل رزروی برای این مرکز ثبت نشده است.
                        </div>
                    @endforelse
                </div>
            </div>

            @if (filled($space->metaValue('content')))
                <div class="bg-white border border-gray-100 rounded-2xl p-6 prose max-w-none prose-sm">
                    {!! $space->metaValue('content') !!}
                </div>
            @endif

            <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">ثبت نظر</h2>
                    <p class="text-sm text-gray-500 mt-1">تجربه خودتان از این مرکز را با دیگران به اشتراک بگذارید.</p>
                </div>

                @if (session('comment_status'))
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('comment_status') }}
                    </div>
                @endif

                @auth
                    <form method="POST" action="{{ route('spaces.comments.store', $space->slug) }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="space-rating" class="block text-sm font-medium text-gray-700 mb-2">امتیاز</label>
                                <select id="space-rating" name="rating" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                    @foreach (range(5, 1) as $rate)
                                        <option value="{{ $rate }}" @selected((int) old('rating', 5) === $rate)>{{ $rate }} از 5</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-3">
                                <label for="space-comment" class="block text-sm font-medium text-gray-700 mb-2">متن نظر</label>
                                <textarea id="space-comment" name="content" rows="4" class="w-full rounded-2xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="نظر شما درباره این مرکز...">{{ old('content') }}</textarea>
                            </div>
                        </div>

                        @error('rating')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('content')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition">
                            ثبت نظر
                        </button>
                    </form>
                @else
                    <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                        برای ثبت نظر ابتدا
                        <a href="{{ route('auth.login') }}" class="font-medium underline">وارد حساب کاربری</a>
                        شوید.
                    </div>
                @endauth

                @if ($comments->isNotEmpty())
                    <div class="border-t border-gray-100 pt-6">
                        <h3 class="text-base font-bold text-gray-900 mb-4">نظرات کاربران</h3>
                        <div class="space-y-4">
                            @foreach ($comments as $comment)
                                <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="font-medium text-gray-900">{{ $comment->user?->name ?: ($comment->user?->mobile ?? 'کاربر') }}</div>
                                        <div class="text-xs text-gray-500">{{ verta($comment->created_at)->format('Y/m/d H:i') }}</div>
                                    </div>
                                    <div class="mt-2 text-sm text-amber-500">{{ str_repeat('★', (int) $comment->rating) }}<span class="text-gray-300">{{ str_repeat('★', max(0, 5 - (int) $comment->rating)) }}</span></div>
                                    @if (filled($comment->content))
                                        <p class="mt-3 text-sm leading-7 text-gray-700">{{ $comment->content }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <div id="space-lightbox" class="hidden fixed inset-0 z-50 bg-black/80 p-4 md:p-10">
        <button type="button" id="space-lightbox-close" class="absolute top-4 left-4 text-white text-3xl leading-none">&times;</button>
        <div class="w-full h-full flex items-center justify-center">
            <img id="space-lightbox-image" src="" alt="lightbox" class="max-w-full max-h-full object-contain rounded-xl">
        </div>
    </div>

    <script>
        (() => {
            const lightbox = document.getElementById('space-lightbox');
            const lightboxImage = document.getElementById('space-lightbox-image');
            const closeBtn = document.getElementById('space-lightbox-close');
            const triggers = document.querySelectorAll('[data-lightbox-trigger]');

            if (!lightbox || !lightboxImage || !closeBtn || !triggers.length) return;

            const closeLightbox = () => {
                lightbox.classList.add('hidden');
                lightboxImage.src = '';
            };

            triggers.forEach((trigger) => {
                trigger.addEventListener('click', () => {
                    const src = trigger.getAttribute('data-lightbox-src');
                    if (!src) return;

                    lightboxImage.src = src;
                    lightbox.classList.remove('hidden');
                });
            });

            closeBtn.addEventListener('click', closeLightbox);
            lightbox.addEventListener('click', (event) => {
                if (event.target === lightbox) closeLightbox();
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') closeLightbox();
            });
        })();
    </script>
</x-layouts.app>
