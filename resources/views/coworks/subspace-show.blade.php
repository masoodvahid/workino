<x-layouts.app :title="$subSpace->title . ' | ' . $space->title . ' | ورکینو'">
    @php
        $featureImage = $subSpace->metaValue('feature_image');
        $image = blank($featureImage)
            ? 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?q=80&w=2069&auto=format&fit=crop'
            : ((\Illuminate\Support\Str::startsWith((string) $featureImage, ['http://', 'https://', '/']))
                ? (string) $featureImage
                : \Illuminate\Support\Facades\Storage::url((string) $featureImage));

        $gallery = collect($subSpace->metaValue('images'))
            ->filter(fn ($item) => filled($item))
            ->map(function ($item) {
                return \Illuminate\Support\Str::startsWith((string) $item, ['http://', 'https://', '/'])
                    ? (string) $item
                    : \Illuminate\Support\Facades\Storage::url((string) $item);
            })
            ->values();

        $typeLabels = [
            'seat' => 'میز',
            'room' => 'اتاق',
            'meeting_room' => 'اتاق جلسه',
            'conference_room' => 'اتاق کنفرانس',
            'coffeeshop' => 'کافه',
        ];

        $workingTime = $subSpace->metaValue('working_time');
        $prices = $subSpace->prices
            ->filter(fn ($price) => $price->status?->value === 'active')
            ->sortBy([
                fn ($price) => $price->priority ?? PHP_INT_MAX,
                fn ($price) => $price->base_price ?? PHP_INT_MAX,
            ])
            ->values();

        $dayLabels = [
            'saturday' => 'شنبه',
            'sunday' => 'یکشنبه',
            'monday' => 'دوشنبه',
            'tuesday' => 'سه‌شنبه',
            'wednesday' => 'چهارشنبه',
            'thursday' => 'پنج‌شنبه',
            'friday' => 'جمعه',
        ];
    @endphp

    <section class="bg-gray-50 py-8 min-h-screen">
        <div class="container mx-auto px-4 space-y-8">
            <div class="flex items-center justify-between">
                <a href="{{ route('spaces.show', $space->slug) }}" class="text-sm text-blue-600 hover:text-blue-700">بازگشت به {{ $space->title }}</a>
                <span class="text-xs bg-white border border-gray-200 rounded-full px-3 py-1 text-gray-600">{{ $subSpace->status?->getLabel() }}</span>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                <div class="p-2 bg-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-[6fr_2fr_2fr] md:grid-rows-2 gap-2">
                        <div class="md:row-span-2 rounded-xl overflow-hidden">
                            <button type="button" class="block w-full h-full" data-lightbox-trigger data-lightbox-src="{{ $image }}">
                                <img src="{{ $image }}" alt="{{ $subSpace->title }}" class="w-full h-[260px] md:h-[420px] object-cover">
                            </button>
                        </div>

                        @php
                            $topThumbs = $gallery->take(4);
                            $remainingGallery = $gallery->slice(4)->values();
                        @endphp

                        @foreach (range(0, 3) as $index)
                            <div class="rounded-xl overflow-hidden">
                                <button type="button" class="block w-full h-full" data-lightbox-trigger data-lightbox-src="{{ $topThumbs->get($index) ?? $image }}">
                                    <img src="{{ $topThumbs->get($index) ?? $image }}" alt="{{ $subSpace->title }}" class="w-full h-[129px] md:h-[206px] object-cover">
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="p-6 md:p-8 space-y-6">
                    <div>
                        <p class="text-sm text-gray-500">{{ $space->title }}</p>
                        <div class="mt-1 flex items-center gap-3">
                            @auth
                                <form method="POST" action="{{ route('spaces.subspaces.likes.toggle', ['spaceSlug' => $space->slug, 'subSpaceSlug' => $subSpace->slug]) }}">
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
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $subSpace->title }}</h1>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="rounded-xl bg-gray-50 border border-gray-100 p-4">
                            <div class="text-xs text-gray-500">نوع فضا</div>
                            <div class="text-lg font-bold text-gray-900 mt-1">{{ $typeLabels[$subSpace->type] ?? $subSpace->type }}</div>
                        </div>
                        <div class="rounded-xl bg-gray-50 border border-gray-100 p-4">
                            <div class="text-xs text-gray-500">ظرفیت</div>
                            <div class="text-lg font-bold text-gray-900 mt-1">{{ $subSpace->capacity ?: 'نامشخص' }}</div>
                        </div>
                        <div class="rounded-xl bg-gray-50 border border-gray-100 p-4">
                            <div class="text-xs text-gray-500">وضعیت</div>
                            <div class="text-lg font-bold text-gray-900 mt-1">{{ $subSpace->status?->getLabel() }}</div>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-gray-900 mb-2">معرفی کوتاه</h2>
                        <p class="text-gray-600 leading-8">{{ $subSpace->metaValue('abstract') ?: 'توضیحی برای این فضا ثبت نشده است.' }}</p>
                    </div>
                </div>
            </div>

            @if ($prices->isNotEmpty())
                <div class="bg-white border border-gray-100 rounded-2xl p-6">
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <h2 class="text-lg font-bold text-gray-900">تعرفه ها</h2>
                        <span class="text-xs text-gray-500">قیمت ها به ریال نمایش داده می شوند</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                        @foreach ($prices as $price)
                            @php
                                $priceValue = $price->special_price ?: $price->base_price;
                                $priceUnitLabel = $price->unit?->getLabel() ?? $price->unit?->value ?? '-';
                            @endphp

                            <div class="rounded-2xl border border-gray-100 bg-gray-50 p-5 space-y-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="font-bold text-gray-900">{{ $price->title }}</h3>
                                        <div class="text-sm text-gray-500 mt-1">{{ $priceUnitLabel }}</div>
                                    </div>
                                    @if ($price->special_price)
                                        <span class="text-xs bg-emerald-100 text-emerald-700 rounded-full px-3 py-1">ویژه</span>
                                    @endif
                                </div>

                                <div>
                                    <div class="text-2xl font-extrabold text-gray-900">{{ number_format($priceValue) }}</div>
                                    <div class="text-xs text-gray-500 mt-1">برای هر {{ $priceUnitLabel }}</div>
                                </div>

                                @if ($price->special_price)
                                    <div class="text-sm text-gray-500">
                                        <span class="line-through">{{ number_format($price->base_price) }}</span>
                                        <span class="mx-1">/</span>
                                        <span class="text-emerald-700 font-medium">{{ number_format($price->special_price) }}</span>
                                    </div>
                                @endif

                                @if (filled($price->description))
                                    <p class="text-sm leading-7 text-gray-600">{{ $price->description }}</p>
                                @endif

                                @if ($price->start || $price->end)
                                    <div class="text-xs text-gray-500 border-t border-gray-200 pt-3">
                                        اعتبار:
                                        {{ $price->start ? verta($price->start)->format('Y/m/d') : 'اکنون' }}
                                        تا
                                        {{ $price->end ? verta($price->end)->format('Y/m/d') : 'نامحدود' }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (is_array($workingTime) && count($workingTime))
                <div class="bg-white border border-gray-100 rounded-2xl p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">ساعات کاری</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach ($workingTime as $dayKey => $dayData)
                            @php
                                $enabled = data_get($dayData, 'enabled', false);
                                $start = data_get($dayData, 'start');
                                $end = data_get($dayData, 'end');
                            @endphp

                            <div class="rounded-lg border border-gray-100 bg-gray-50 px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $dayLabels[$dayKey] ?? $dayKey }}</div>
                                <div class="text-sm text-gray-600 mt-1">
                                    @if ($enabled && $start && $end)
                                        {{ $start }} تا {{ $end }}
                                    @else
                                        تعطیل
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($remainingGallery->isNotEmpty())
                <div class="bg-white border border-gray-100 rounded-2xl p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">گالری تصاویر بیشتر</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach ($remainingGallery as $galleryImage)
                            <button type="button" class="block w-full" data-lightbox-trigger data-lightbox-src="{{ $galleryImage }}">
                                <img src="{{ $galleryImage }}" alt="{{ $subSpace->title }}" class="w-full h-32 object-cover rounded-lg border border-gray-100">
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (filled($subSpace->metaValue('content')))
                <div class="bg-white border border-gray-100 rounded-2xl p-6 prose max-w-none prose-sm">
                    {!! $subSpace->metaValue('content') !!}
                </div>
            @endif

            <div class="bg-white border border-gray-100 rounded-2xl p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">ثبت نظر</h2>
                    <p class="text-sm text-gray-500 mt-1">اگر تجربه‌ای از این زیرمجموعه دارید، اینجا ثبت کنید.</p>
                </div>

                @if (session('comment_status'))
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('comment_status') }}
                    </div>
                @endif

                @auth
                    <form method="POST" action="{{ route('spaces.subspaces.comments.store', ['spaceSlug' => $space->slug, 'subSpaceSlug' => $subSpace->slug]) }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="subspace-rating" class="block text-sm font-medium text-gray-700 mb-2">امتیاز</label>
                                <select id="subspace-rating" name="rating" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                    @foreach (range(5, 1) as $rate)
                                        <option value="{{ $rate }}" @selected((int) old('rating', 5) === $rate)>{{ $rate }} از 5</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-3">
                                <label for="subspace-comment" class="block text-sm font-medium text-gray-700 mb-2">متن نظر</label>
                                <textarea id="subspace-comment" name="content" rows="4" class="w-full rounded-2xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="نظر شما درباره این زیرمجموعه...">{{ old('content') }}</textarea>
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

    <div id="subspace-lightbox" class="hidden fixed inset-0 z-50 bg-black/80 p-4 md:p-10">
        <button type="button" id="subspace-lightbox-close" class="absolute top-4 left-4 text-white text-3xl leading-none">&times;</button>
        <div class="w-full h-full flex items-center justify-center">
            <img id="subspace-lightbox-image" src="" alt="lightbox" class="max-w-full max-h-full object-contain rounded-xl">
        </div>
    </div>

    <script>
        (() => {
            const lightbox = document.getElementById('subspace-lightbox');
            const lightboxImage = document.getElementById('subspace-lightbox-image');
            const closeBtn = document.getElementById('subspace-lightbox-close');
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
