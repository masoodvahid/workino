<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Workino - رزرو فضای کار اشتراکی' }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <div class="container mx-auto px-4 py-4 flex justify-between items-center">
                <!-- Logo & Menu -->
                <div class="flex items-center gap-8">
                    <a href="/" class="text-2xl font-bold text-blue-600">ورکینو</a>
                    <nav class="hidden md:flex gap-6 text-sm font-medium text-gray-600">
                        <a href="{{ route('home') }}" class="hover:text-blue-600 transition {{ request()->routeIs('home') ? 'text-blue-600 font-bold' : '' }}">خانه</a>
                        <a href="{{ route('coworks.index') }}" class="hover:text-blue-600 transition {{ request()->routeIs('coworks.*') ? 'text-blue-600 font-bold' : '' }}">فضاهای کار</a>
                        <a href="{{ route('about') }}" class="hover:text-blue-600 transition {{ request()->routeIs('about') ? 'text-blue-600 font-bold' : '' }}">درباره ما</a>
                        <a href="{{ route('contact') }}" class="hover:text-blue-600 transition {{ request()->routeIs('contact') ? 'text-blue-600 font-bold' : '' }}">تماس با ما</a>
                        <a href="{{ route('support.index') }}" class="hover:text-blue-600 transition {{ request()->routeIs('support.*') ? 'text-blue-600 font-bold' : '' }}">پشتیبانی</a>
                    </nav>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-3">
                    @auth
                        <a href="/modiriat" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                            پنل کاربری
                        </a>
                        <a href="{{ route('profile.index') }}" class="text-gray-600 hover:text-blue-600 font-medium text-sm">
                            پروفایل من
                        </a>
                    @else
                        <a href="{{ route('auth.login') }}" class="text-gray-600 hover:text-blue-600 font-medium text-sm">
                            ورود / ثبت نام
                        </a>
                    @endauth
                </div>
                
                <!-- Mobile Menu Button (Simple implementation) -->
                <button class="md:hidden text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-300 py-12 mt-12">
            <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Column 1: About -->
                <div>
                    <h3 class="text-white text-lg font-bold mb-4">ورکینو</h3>
                    <p class="text-sm leading-7 text-gray-400">
                        ورکینو پلتفرمی برای رزرو آنلاین فضاهای کار اشتراکی، اتاق جلسات و دفاتر کار است. ما به شما کمک می‌کنیم بهترین محیط را برای کار و فعالیت خود پیدا کنید.
                    </p>
                </div>

                <!-- Column 2: Quick Links -->
                <div>
                    <h3 class="text-white text-lg font-bold mb-4">دسترسی سریع</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">قوانین و مقررات</a></li>
                        <li><a href="#" class="hover:text-white transition">سوالات متداول</a></li>
                        <li><a href="#" class="hover:text-white transition">همکاری با ما</a></li>
                        <li><a href="#" class="hover:text-white transition">وبلاگ</a></li>
                    </ul>
                </div>

                <!-- Column 3: Contact -->
                <div>
                    <h3 class="text-white text-lg font-bold mb-4">تماس با ما</h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                            <span>۰۲۱-۱۲۳۴۵۶۷۸</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                            <span>info@workino.com</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            <span>تهران، میدان آزادی، خیابان آزادی</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-sm text-gray-500">
                &copy; ۲۰۲۴ ورکینو. تمامی حقوق محفوظ است.
            </div>
        </footer>
    </div>
</body>
</html>