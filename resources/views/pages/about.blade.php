<x-layouts.app title="درباره ما | ورکینو">
    <div class="bg-white overflow-hidden">
        <!-- Hero Section -->
        <div class="relative py-20 bg-blue-600 text-white overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center opacity-20"></div>
            <div class="container mx-auto px-4 relative z-10 text-center animate-[fadeInUp_1s_ease-out]">
                <h1 class="text-4xl lg:text-5xl font-extrabold mb-6">درباره ورکینو</h1>
                <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                    ما در تلاشیم تا تجربه پیدا کردن و رزرو فضای کار را برای فریلنسرها، استارتاپ‌ها و شرکت‌ها ساده و لذت‌بخش کنیم.
                </p>
            </div>
        </div>

        <!-- Story Section -->
        <div class="py-20 container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="order-2 md:order-1 animate-[fadeInLeft_1s_ease-out]">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">داستان ما</h2>
                    <p class="text-gray-600 leading-8 mb-4">
                        ورکینو در سال ۱۴۰۲ با هدف ایجاد شبکه یکپارچه از فضاهای کار اشتراکی در سراسر ایران متولد شد. ما متوجه شدیم که پیدا کردن یک محیط کار مناسب، ساکت و با امکانات کامل، یکی از دغدغه‌های اصلی نیروهای کار دورکار است.
                    </p>
                    <p class="text-gray-600 leading-8">
                        امروز، ورکینو با بیش از ۱۰۰ فضای کار اشتراکی در ۱۰ شهر بزرگ ایران همکاری می‌کند و به هزاران کاربر کمک کرده است تا دفتر کار ایده‌آل خود را پیدا کنند.
                    </p>
                </div>
                <div class="order-1 md:order-2 relative h-96 rounded-2xl overflow-hidden shadow-2xl animate-[fadeInRight_1s_ease-out]">
                    <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?q=80&w=2070&auto=format&fit=crop" alt="Team working" class="absolute inset-0 w-full h-full object-cover">
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="bg-gray-50 py-16">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div class="p-6">
                        <div class="text-4xl font-bold text-blue-600 mb-2">+۱۰۰</div>
                        <div class="text-gray-600">فضای کار اشتراکی</div>
                    </div>
                    <div class="p-6">
                        <div class="text-4xl font-bold text-blue-600 mb-2">+۵۰۰۰</div>
                        <div class="text-gray-600">کاربر فعال</div>
                    </div>
                    <div class="p-6">
                        <div class="text-4xl font-bold text-blue-600 mb-2">+۱۰</div>
                        <div class="text-gray-600">شهر تحت پوشش</div>
                    </div>
                    <div class="p-6">
                        <div class="text-4xl font-bold text-blue-600 mb-2">۲۴/۷</div>
                        <div class="text-gray-600">پشتیبانی آنلاین</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Section (Simple) -->
        <div class="py-20 container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-12">تیم ما</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                <!-- Member 1 -->
                <div class="group">
                    <div class="w-48 h-48 mx-auto rounded-full overflow-hidden mb-4 border-4 border-gray-100 group-hover:border-blue-100 transition">
                        <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=1974&auto=format&fit=crop" alt="Member" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">علی محمدی</h3>
                    <p class="text-blue-600">مدیر عامل</p>
                </div>
                <!-- Member 2 -->
                <div class="group">
                    <div class="w-48 h-48 mx-auto rounded-full overflow-hidden mb-4 border-4 border-gray-100 group-hover:border-blue-100 transition">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=1976&auto=format&fit=crop" alt="Member" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">سارا احمدی</h3>
                    <p class="text-blue-600">مدیر محصول</p>
                </div>
                <!-- Member 3 -->
                <div class="group">
                    <div class="w-48 h-48 mx-auto rounded-full overflow-hidden mb-4 border-4 border-gray-100 group-hover:border-blue-100 transition">
                        <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?q=80&w=1974&auto=format&fit=crop" alt="Member" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">رضا کریمی</h3>
                    <p class="text-blue-600">مدیر فنی</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Animations defined inline for simplicity or added to tailwind config later -->
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>
</x-layouts.app>