<x-layouts.app>
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-20 lg:py-32 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=2069&auto=format&fit=crop')] bg-cover bg-center"></div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl lg:text-6xl font-extrabold mb-6 leading-tight">
                بهترین فضای کار اشتراکی را <br class="hidden md:inline"> برای خود پیدا کنید
            </h1>
            <p class="text-lg lg:text-xl text-blue-100 mb-10 max-w-2xl mx-auto">
                ورکینو، پلتفرم جامع رزرو فضاهای کار اشتراکی، اتاق جلسات و دفاتر کار در سراسر ایران.
                محیطی حرفه‌ای برای رشد کسب‌وکارهای نوپا.
            </p>
            
            <!-- Search Box with Filters -->
            <div class="bg-white p-4 rounded-2xl shadow-xl max-w-4xl mx-auto text-gray-800">
                <form action="#" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <!-- Search Input -->
                    <div class="md:col-span-2 text-right">
                        <label class="block text-sm font-medium text-gray-700 mb-1">جستجو</label>
                        <div class="relative">
                            <input type="text" placeholder="نام فضای کار یا منطقه..." class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 py-3 pr-10 pl-4 text-sm">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Options -->
                    <div class="text-right">
                        <label class="block text-sm font-medium text-gray-700 mb-1">امکانات</label>
                        <select class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 py-3 text-sm">
                            <option value="">همه امکانات</option>
                            <option value="parking">پارکینگ</option>
                            <option value="restroom">اتاق استراحت</option>
                            <option value="wifi">اینترنت پرسرعت</option>
                            <option value="cafe">کافه</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="button" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-md">
                            جستجو کن
                        </button>
                    </div>
                </form>
                
                <!-- Quick Tags -->
                <div class="mt-4 flex flex-wrap gap-2 text-xs justify-center md:justify-start">
                    <span class="px-3 py-1 bg-gray-100 rounded-full text-gray-600 cursor-pointer hover:bg-gray-200">اتاق جلسه</span>
                    <span class="px-3 py-1 bg-gray-100 rounded-full text-gray-600 cursor-pointer hover:bg-gray-200">میز اختصاصی</span>
                    <span class="px-3 py-1 bg-gray-100 rounded-full text-gray-600 cursor-pointer hover:bg-gray-200">دفتر خصوصی</span>
                    <span class="px-3 py-1 bg-gray-100 rounded-full text-gray-600 cursor-pointer hover:bg-gray-200">اینترنت فیبر نوری</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Cowork Spaces -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">جدیدترین فضاهای کار</h2>
                    <p class="text-gray-500">محبوب‌ترین فضاهای کار اشتراکی اضافه شده به ورکینو</p>
                </div>
                <a href="#" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                    مشاهده همه
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 rotate-180">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $coworks = [
                        [
                            'image' => 'https://images.unsplash.com/photo-1527192491265-7e15c55b1ed2?q=80&w=2070&auto=format&fit=crop',
                            'title' => 'فضای کار اشتراکی آبی',
                            'location' => 'تهران، ونک',
                            'price' => '۱۵۰,۰۰۰',
                            'rating' => 4.8,
                            'tags' => ['پارکینگ', 'کافه', 'اینترنت']
                        ],
                        [
                            'image' => 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?q=80&w=2069&auto=format&fit=crop',
                            'title' => 'استارتاپ هاب مرکزی',
                            'location' => 'اصفهان، چهارباغ',
                            'price' => '۱۲۰,۰۰۰',
                            'rating' => 4.5,
                            'tags' => ['اتاق جلسه', 'چای رایگان']
                        ],
                        [
                            'image' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?q=80&w=2832&auto=format&fit=crop',
                            'title' => 'مرکز نوآوری امید',
                            'location' => 'مشهد، وکیل‌آباد',
                            'price' => '۱۰۰,۰۰۰',
                            'rating' => 4.2,
                            'tags' => ['فضای باز', 'کمد اختصاصی']
                        ],
                        [
                            'image' => 'https://images.unsplash.com/photo-1504384308090-c54be3852f33?q=80&w=2070&auto=format&fit=crop',
                            'title' => 'کافه ورکینو',
                            'location' => 'شیراز، معالی‌آباد',
                            'price' => '۹۰,۰۰۰',
                            'rating' => 4.7,
                            'tags' => ['سکوت مطلق', 'قهوه']
                        ],
                        [
                            'image' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=2070&auto=format&fit=crop',
                            'title' => 'خانه خلاقیت',
                            'location' => 'تهران، انقلاب',
                            'price' => '۱۳۰,۰۰۰',
                            'rating' => 4.9,
                            'tags' => ['دسترسی مترو', 'اینترنت']
                        ],
                        [
                            'image' => 'https://images.unsplash.com/photo-1604328698692-f76ea9498e76?q=80&w=2070&auto=format&fit=crop',
                            'title' => 'ورک‌اسپیس مدرن',
                            'location' => 'کرج، عظیمیه',
                            'price' => '۱۱۰,۰۰۰',
                            'rating' => 4.3,
                            'tags' => ['پارکینگ', 'اتاق بازی']
                        ],
                        [
                            'image' => 'https://images.unsplash.com/photo-1568992687947-868a62a9f521?q=80&w=2832&auto=format&fit=crop',
                            'title' => 'فضای کار اشتراکی پلاس',
                            'location' => 'تبریز، ولیعصر',
                            'price' => '۱۴۰,۰۰۰',
                            'rating' => 4.6,
                            'tags' => ['ناهارخوری', 'پرینتر']
                        ],
                        [
                            'image' => 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?q=80&w=2070&auto=format&fit=crop',
                            'title' => 'تک‌هاب',
                            'location' => 'تهران، جردن',
                            'price' => '۲۰۰,۰۰۰',
                            'rating' => 5.0,
                            'tags' => ['لوکس', 'پارکینگ اختصاصی']
                        ],
                        [
                            'image' => 'https://images.unsplash.com/photo-1504384764586-bb4cdc1707b0?q=80&w=2070&auto=format&fit=crop',
                            'title' => 'آفیس باکس',
                            'location' => 'رشت، گلسار',
                            'price' => '۹۵,۰۰۰',
                            'rating' => 4.4,
                            'tags' => ['منظره', 'اینترنت']
                        ],
                        [
                            'image' => 'https://images.unsplash.com/photo-1557804506-669a67965ba0?q=80&w=2574&auto=format&fit=crop',
                            'title' => 'کارخانه نوآوری',
                            'location' => 'تهران، آزادی',
                            'price' => '۱۱۰,۰۰۰',
                            'rating' => 4.8,
                            'tags' => ['رویدادها', 'شبکه سازی']
                        ],
                        [
                            'image' => 'https://images.unsplash.com/photo-1497215728101-856f4ea42174?q=80&w=2070&auto=format&fit=crop',
                            'title' => 'ورک‌استیشن پرو',
                            'location' => 'اهواز، کیانپارس',
                            'price' => '۱۰۰,۰۰۰',
                            'rating' => 4.1,
                            'tags' => ['خنک', 'آرام']
                        ],
                        [
                            'image' => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?q=80&w=2112&auto=format&fit=crop',
                            'title' => 'دیجیتال نومد',
                            'location' => 'کیش',
                            'price' => '۲۵۰,۰۰۰',
                            'rating' => 4.9,
                            'tags' => ['ساحلی', 'اینترنت']
                        ],
                    ];
                @endphp

                @foreach ($coworks as $cowork)
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition duration-300 border border-gray-100 overflow-hidden group">
                        <!-- Image -->
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ $cowork['image'] }}" alt="{{ $cowork['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-md text-xs font-bold text-gray-700 flex items-center gap-1 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 text-yellow-400">
                                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                                </svg>
                                {{ $cowork['rating'] }}
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $cowork['title'] }}</h3>
                            <div class="flex items-center text-gray-500 text-sm mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                                {{ $cowork['location'] }}
                            </div>
                            
                            <!-- Tags -->
                            <div class="flex flex-wrap gap-1 mb-4">
                                @foreach ($cowork['tags'] as $tag)
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded text-xs">{{ $tag }}</span>
                                @endforeach
                            </div>
                            
                            <!-- Price & Button -->
                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                                <div>
                                    <span class="text-xs text-gray-400 block">شروع قیمت از</span>
                                    <div class="font-bold text-gray-900">
                                        {{ $cowork['price'] }} <span class="text-xs font-normal text-gray-500">تومان</span>
                                    </div>
                                </div>
                                <a href="#" class="px-4 py-2 bg-gray-900 text-white text-sm rounded-lg hover:bg-gray-800 transition">رزرو کنید</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination (Mock) -->
            <div class="mt-12 flex justify-center">
                <nav class="flex items-center gap-2" aria-label="Pagination">
                    <a href="#" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">قبلی</a>
                    <a href="#" class="relative inline-flex items-center rounded-md border border-blue-500 bg-blue-50 px-4 py-2 text-sm font-medium text-blue-600 focus:z-20">۱</a>
                    <a href="#" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-20">۲</a>
                    <a href="#" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-20">۳</a>
                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700">...</span>
                    <a href="#" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">بعدی</a>
                </nav>
            </div>
        </div>
    </section>
</x-layouts.app>