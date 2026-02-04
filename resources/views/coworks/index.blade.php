<x-layouts.app title="لیست فضاهای کار اشتراکی | ورکینو">
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="container mx-auto px-4">
            
            <!-- Header & Sort -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <h1 class="text-2xl font-bold text-gray-800">فضاهای کار اشتراکی</h1>
                
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500">مرتب‌سازی بر اساس:</span>
                    <select class="form-select border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-4">
                        <option value="rank">امتیاز (پیش‌فرض)</option>
                        <option value="view">پربازدیدترین</option>
                        <option value="newest">جدیدترین</option>
                        <option value="title">حروف الفبا</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar Filters -->
                <aside class="lg:col-span-1 space-y-6">
                    <!-- City Filter -->
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            شهر
                        </h3>
                        <select class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">همه شهرها</option>
                            <option value="tehran">تهران</option>
                            <option value="isfahan">اصفهان</option>
                            <option value="mashhad">مشهد</option>
                            <option value="shiraz">شیراز</option>
                            <option value="tabriz">تبریز</option>
                            <option value="karaj">کرج</option>
                        </select>
                    </div>

                    <!-- Features Filter -->
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 13.5V3.75m0 9.75a1.5 1.5 0 0 1 0 3m0-3a1.5 1.5 0 0 0 0 3m0 3.75V16.5m12-3V3.75m0 9.75a1.5 1.5 0 0 1 0 3m0-3a1.5 1.5 0 0 0 0 3m0 3.75V16.5m-6-9V3.75m0 3.75a1.5 1.5 0 0 1 0 3m0-3a1.5 1.5 0 0 0 0 3m0 9.75V10.5" />
                            </svg>
                            امکانات
                        </h3>
                        <div class="space-y-3">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-600 group-hover:text-blue-600 transition">اینترنت پرسرعت</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-600 group-hover:text-blue-600 transition">پارکینگ</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-600 group-hover:text-blue-600 transition">اتاق جلسه</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-600 group-hover:text-blue-600 transition">کافه / رستوران</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-600 group-hover:text-blue-600 transition">فضای باز</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-600 group-hover:text-blue-600 transition">دسترسی ۲۴ ساعته</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-600 group-hover:text-blue-600 transition">لاکزر اختصاصی</span>
                            </label>
                        </div>
                    </div>
                    
                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition shadow-sm">
                        اعمال فیلترها
                    </button>
                </aside>

                <!-- List Content -->
                <main class="lg:col-span-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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

                    <!-- Pagination -->
                    <div class="mt-12 flex justify-center">
                        <nav class="flex items-center gap-2" aria-label="Pagination">
                            <a href="#" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">قبلی</a>
                            <a href="#" class="relative inline-flex items-center rounded-md border border-blue-500 bg-blue-50 px-4 py-2 text-sm font-medium text-blue-600 focus:z-20">۱</a>
                            <a href="#" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-20">۲</a>
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700">...</span>
                            <a href="#" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">بعدی</a>
                        </nav>
                    </div>
                </main>
            </div>
        </div>
    </div>
</x-layouts.app>