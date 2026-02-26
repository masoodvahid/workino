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
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mt-1">{{ $subSpace->title }}</h1>
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
