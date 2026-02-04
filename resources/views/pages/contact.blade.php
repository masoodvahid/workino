<x-layouts.app title="تماس با ما | ورکینو">
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="container mx-auto px-4 max-w-6xl">
            <h1 class="text-3xl font-bold text-center text-gray-900 mb-12">با ما در ارتباط باشید</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Left Column: Form -->
                <div class="p-8 md:p-12">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">ارسال پیام</h2>
                    <form action="#" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نام و نام خانوادگی</label>
                            <input type="text" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="مثال: علی رضایی">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">شماره تماس</label>
                            <input type="tel" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="۰۹۱۲۳۴۵۶۷۸۹" dir="ltr" style="text-align: right;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">پیام شما</label>
                            <textarea rows="4" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="پیام خود را بنویسید..."></textarea>
                        </div>
                        <button type="button" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-md">
                            ارسال پیام
                        </button>
                    </form>
                </div>

                <!-- Right Column: Info & Map -->
                <div class="bg-blue-600 text-white p-8 md:p-12 flex flex-col justify-between relative overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-white via-transparent to-transparent"></div>
                    
                    <div class="relative z-10">
                        <h2 class="text-2xl font-bold mb-8">اطلاعات تماس</h2>
                        
                        <ul class="space-y-6">
                            <li class="flex items-start gap-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mt-1 flex-shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                                <div>
                                    <span class="block font-bold mb-1">آدرس دفتر مرکزی</span>
                                    <p class="text-blue-100 text-sm leading-6">تهران، میدان آزادی، خیابان آزادی، پلاک ۱۱۰، ساختمان ورکینو</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mt-1 flex-shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                </svg>
                                <div>
                                    <span class="block font-bold mb-1">تلفن تماس</span>
                                    <p class="text-blue-100 text-sm">۰۲۱-۱۲۳۴۵۶۷۸</p>
                                    <p class="text-blue-100 text-sm">۰۲۱-۸۷۶۵۴۳۲۱</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mt-1 flex-shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                                <div>
                                    <span class="block font-bold mb-1">ایمیل</span>
                                    <p class="text-blue-100 text-sm">info@workino.com</p>
                                    <p class="text-blue-100 text-sm">support@workino.com</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Fake Map Image -->
                    <div class="mt-8 rounded-lg overflow-hidden h-48 relative z-10 border-2 border-blue-400">
                        <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=2074&auto=format&fit=crop" alt="Map Location" class="w-full h-full object-cover opacity-80 hover:opacity-100 transition">
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <span class="bg-black/50 text-white px-3 py-1 rounded text-sm">نقشه گوگل (نمایشی)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>