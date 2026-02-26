<x-layouts.app>
    <section class="relative bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-20 lg:py-32 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=2069&auto=format&fit=crop')] bg-cover bg-center"></div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl lg:text-6xl font-extrabold mb-6 leading-tight">
                بهترین فضای کار اشتراکی را <br class="hidden md:inline"> برای خود پیدا کنید
            </h1>
            <p class="text-lg lg:text-xl text-blue-100 mb-10 max-w-2xl mx-auto">
                ورکینو، پلتفرم جامع رزرو فضاهای کار اشتراکی، اتاق جلسات و دفاتر کار در سراسر ایران.
            </p>

            <div class="bg-white p-4 rounded-2xl shadow-xl max-w-4xl mx-auto text-gray-800 relative">
                <form action="{{ route('spaces.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-3">
                    <div class="text-right md:col-span-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">جستجو در عنوان فضا</label>
                        <input
                            id="home-space-search"
                            name="q"
                            type="text"
                            placeholder="مثلا: مرکز نوآوری، ورکینو..."
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 py-3 px-4 text-sm"
                            data-search-endpoint="{{ route('spaces.live-search') }}"
                            autocomplete="off"
                        >
                    </div>

                    <div class="  md:flex md:items-end">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-md">
                            جستجو
                        </button>
                    </div>

                    <div class="text-right md:col-span-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">امکانات</label>
                        <div class="rounded-lg border border-gray-300 bg-white px-3 py-2 grid grid-cols-2 md:grid-cols-3 gap-x-3 gap-y-2">
                            @foreach ($subSpaceTypes as $typeKey => $typeLabel)
                                <label class="inline-flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                                    <input
                                        type="checkbox"
                                        name="subspace_types[]"
                                        value="{{ $typeKey }}"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                        data-home-subspace-type
                                    >
                                    <span>{{ $typeLabel }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </form>

                <div id="home-live-search-results" class="hidden absolute right-4 left-4 top-[calc(100%+0.5rem)] bg-white border border-gray-200 rounded-xl shadow-xl z-30 overflow-hidden">
                    <ul id="home-live-search-list" class="max-h-96 overflow-y-auto divide-y divide-gray-100"></ul>
                    <p id="home-live-search-empty" class="hidden p-4 text-sm text-gray-500">نتیجه‌ای پیدا نشد.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">جدیدترین فضاهای کار</h2>
                    <p class="text-gray-500">این بخش از جدول فضاها به صورت زنده نمایش داده می‌شود</p>
                </div>
                <a href="{{ route('spaces.index') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                    مشاهده همه
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 rotate-180">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($spaces as $space)
                    @php
                        $featuredImage = $space->metaValue('featured_image');
                        $image = blank($featuredImage)
                            ? 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?q=80&w=2069&auto=format&fit=crop'
                            : ((\Illuminate\Support\Str::startsWith((string) $featuredImage, ['http://', 'https://', '/']))
                                ? (string) $featuredImage
                                : \Illuminate\Support\Facades\Storage::disk('public')->url((string) $featuredImage));
                    @endphp

                    <a href="{{ route('spaces.show', $space->slug) }}" class="block bg-white rounded-xl shadow-sm hover:shadow-lg transition duration-300 border border-gray-100 overflow-hidden group">
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ $image }}" alt="{{ $space->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-md text-xs font-bold text-gray-700 shadow-sm">
                                {{ $space->status?->getLabel() }}
                            </div>
                        </div>

                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $space->title }}</h3>
                            <p class="text-sm text-gray-500 mb-4 line-clamp-3">{{ $space->metaValue('abstract') ?: ($space->note ?: 'بدون توضیح') }}</p>

                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                                <div>
                                    <span class="text-xs text-gray-400 block">تعداد زیرمجموعه</span>
                                    <div class="font-bold text-gray-900">{{ $space->subSpaces->count() }}</div>
                                </div>
                                <span class="px-4 py-2 bg-gray-900 text-white text-sm rounded-lg hover:bg-gray-800 transition">مشاهده</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="md:col-span-2 lg:col-span-3 bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-500">
                        هنوز فضای فعالی ثبت نشده است.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.app>
