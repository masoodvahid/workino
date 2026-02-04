<x-layouts.app title="پشتیبانی | ورکینو">
    <div class="bg-gray-50 min-h-screen py-10">
        <div class="container mx-auto px-4 max-w-3xl">
            <div class="bg-white rounded-2xl shadow-sm p-8 md:p-10">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">ثبت درخواست پشتیبانی</h1>
                    <p class="text-gray-500">تیم پشتیبانی ما در سریع‌ترین زمان ممکن پاسخگوی شما خواهد بود.</p>
                </div>

                <form action="#" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">موضوع درخواست</label>
                        <input type="text" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="خلاصه مشکل را بنویسید">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">بخش مربوطه</label>
                        <select class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">انتخاب کنید...</option>
                            <option value="technical">مشکل فنی سایت</option>
                            <option value="billing">امور مالی و پرداخت</option>
                            <option value="booking">مشکل در رزرو</option>
                            <option value="complaint">شکایت از فضای کار</option>
                            <option value="suggestion">پیشنهاد و انتقاد</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">شرح درخواست</label>
                        <textarea rows="6" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="توضیحات کامل مشکل خود را بنویسید..."></textarea>
                    </div>

                    <div class="flex items-center gap-2 text-sm text-gray-500 bg-blue-50 p-4 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>
                        پاسخ شما از طریق پیامک و ایمیل اطلاع‌رسانی خواهد شد.
                    </div>

                    <button type="button" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-md">
                        ثبت تیکت
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>