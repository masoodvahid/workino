<x-layouts.app title="لیست فضاهای کار اشتراکی | ورکینو">
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <h1 class="text-2xl font-bold text-gray-800">فضاهای کار اشتراکی</h1>

                <form method="GET" action="{{ route('spaces.index') }}" class="flex items-center gap-2">
                    <input type="hidden" name="q" value="{{ $keyword ?? '' }}">
                    @foreach (($selectedSubSpaceTypes ?? []) as $selectedType)
                        <input type="hidden" name="subspace_types[]" value="{{ $selectedType }}">
                    @endforeach
                    <span class="text-sm text-gray-500">مرتب‌سازی بر اساس:</span>
                    <select name="sort" onchange="this.form.submit()" class="form-select border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-4">
                        <option value="newest" @selected($sort === 'newest')>جدیدترین</option>
                        <option value="oldest" @selected($sort === 'oldest')>قدیمی‌ترین</option>
                        <option value="title" @selected($sort === 'title')>حروف الفبا</option>
                    </select>
                </form>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <aside class="lg:col-span-1 space-y-6">
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-2">اتصال به داده واقعی انجام شد</h3>
                        <p class="text-sm text-gray-500 leading-7">
                            این لیست از جدول <code>spaces</code> خوانده می‌شود و فقط فضاهای فعال نمایش داده می‌شوند.
                        </p>
                    </div>
                </aside>

                <main class="lg:col-span-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($spaces as $space)
                            @php
                                $featuredImage = $space->metaValue('featured_image');
                                $image = blank($featuredImage)
                                    ? 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=2069&auto=format&fit=crop'
                                    : ((\Illuminate\Support\Str::startsWith((string) $featuredImage, ['http://', 'https://', '/']))
                                        ? (string) $featuredImage
                                        : \Illuminate\Support\Facades\Storage::disk('public')->url((string) $featuredImage));
                                $logoValue = $space->metaValue('logo');
                                $logo = blank($logoValue)
                                    ? null
                                    : ((\Illuminate\Support\Str::startsWith((string) $logoValue, ['http://', 'https://', '/']))
                                        ? (string) $logoValue
                                        : \Illuminate\Support\Facades\Storage::disk('public')->url((string) $logoValue));

                                $typeLabels = [
                                    'seat' => 'میز',
                                    'room' => 'اتاق',
                                    'meeting_room' => 'اتاق جلسه',
                                    'conference_room' => 'اتاق کنفرانس',
                                    'coffeeshop' => 'کافه',
                                ];

                                $tags = $space->subSpaces
                                    ->pluck('type')
                                    ->unique()
                                    ->take(3)
                                    ->map(fn (string $type): string => $typeLabels[$type] ?? $type);
                            @endphp

                            <a href="{{ route('spaces.show', $space->slug) }}" class="block bg-white rounded-xl shadow-sm hover:shadow-lg transition duration-300 border border-gray-100 overflow-hidden group">
                                <div class="relative h-48 overflow-hidden">
                                    <img src="{{ $image }}" alt="{{ $space->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    <div class="absolute top-3 left-3 bg-white/25 backdrop-blur-md border border-white/40 px-3 py-1 rounded-lg text-xs font-bold text-white shadow-lg">
                                        {{ \App\Enums\City::tryFrom((string) $space->metaValue('city'))?->getLabel() ?? 'نامشخص' }}
                                    </div>
                                </div>

                                <div class="relative p-5 pt-8">
                                    @if (filled($logo))
                                        <img src="{{ $logo }}" alt="لوگوی {{ $space->title }}" class="w-15 h-15 -mt-20 mb-2 bg-white rounded-full object-cover ring-2 ring-white shadow-md">
                                    @endif
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $space->title }}</h3>
                                    <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $space->note ?: 'بدون توضیح' }}</p>

                                    <div class="flex flex-wrap gap-1 mb-4">
                                        @forelse ($tags as $tag)
                                            <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded text-xs">{{ $tag }}</span>
                                        @empty
                                            <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs">بدون زیرمجموعه</span>
                                        @endforelse
                                    </div>

                                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                                        <div>
                                            <span class="text-xs text-gray-400 block">تعداد زیرمجموعه</span>
                                            <div class="font-bold text-gray-900">{{ $space->subSpaces->count() }}</div>
                                        </div>
                                        <span class="px-4 py-2 bg-gray-900 text-white text-sm rounded-lg hover:bg-gray-800 transition">اطلاعات بیشتر</span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="md:col-span-2 lg:col-span-3 bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-500">
                                فضای فعالی برای نمایش وجود ندارد.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-10">
                        {{ $spaces->links() }}
                    </div>
                </main>
            </div>
        </div>
    </div>
</x-layouts.app>
