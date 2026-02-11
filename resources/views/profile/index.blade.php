<x-layouts.app title="پروفایل کاربری | ورکینو">
    <div class="bg-gray-50 min-h-screen py-10" x-data="{ tab: 'info' }">
        <div class="container mx-auto px-4 max-w-5xl">
            
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Sidebar -->
                <aside class="w-full md:w-64 flex-shrink-0">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 text-center border-b border-gray-100">
                            <div class="w-20 h-20 mx-auto bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-2xl font-bold mb-3">
                                {{ $profile['initial'] }}
                            </div>
                            <h2 class="font-bold text-gray-900">{{ $profile['name'] }}</h2>
                            <p class="text-sm text-gray-500">{{ $profile['mobile'] }}</p>
                            <div class="mt-3 flex items-center justify-center gap-2 text-xs">
                                <span class="rounded-full bg-gray-100 px-3 py-1 text-gray-600">{{ $profile['type'] }}</span>
                                <span class="rounded-full bg-amber-100 px-3 py-1 text-amber-700">{{ $profile['status'] }}</span>
                            </div>
                        </div>
                        <nav class="flex flex-col p-2">
                            <button @click="tab = 'info'" :class="{ 'bg-blue-50 text-blue-600': tab === 'info', 'text-gray-600 hover:bg-gray-50': tab !== 'info' }" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition text-right">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                اطلاعات کاربر
                            </button>
                            <button @click="tab = 'kyc'" :class="{ 'bg-blue-50 text-blue-600': tab === 'kyc', 'text-gray-600 hover:bg-gray-50': tab !== 'kyc' }" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition text-right">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                                </svg>
                                احراز هویت
                            </button>
                            <button @click="tab = 'payments'" :class="{ 'bg-blue-50 text-blue-600': tab === 'payments', 'text-gray-600 hover:bg-gray-50': tab !== 'payments' }" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition text-right">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                                </svg>
                                پرداخت‌ها
                            </button>
                            <button @click="tab = 'bookings'" :class="{ 'bg-blue-50 text-blue-600': tab === 'bookings', 'text-gray-600 hover:bg-gray-50': tab !== 'bookings' }" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition text-right">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0h18M5.25 12h13.5m-13.5 3.75h13.5" />
                                </svg>
                                رزروها
                            </button>
                            <button @click="tab = 'logs'" :class="{ 'bg-blue-50 text-blue-600': tab === 'logs', 'text-gray-600 hover:bg-gray-50': tab !== 'logs' }" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition text-right">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                تاریخچه ورود
                            </button>
                            <button class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 transition text-right mt-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                </svg>
                                خروج
                            </button>
                        </nav>
                    </div>
                </aside>

                <!-- Content Area -->
                <main class="flex-grow bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
                    
                    <!-- Tab: Info -->
                    <div x-show="tab === 'info'" x-transition>
                        <div class="flex flex-col gap-8">
                            <div class="border-b border-gray-100 pb-6">
                                <h3 class="text-xl font-bold text-gray-800">ویرایش اطلاعات کاربر</h3>
                                <p class="text-sm text-gray-500 mt-2">اطلاعات خود را بروزرسانی کنید.</p>
                            </div>

                            @if (session('status'))
                                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                                    {{ session('status') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                    <ul class="list-disc pr-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('profile.update') }}" method="post" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                @csrf
                                @method('put')

                                <div class="rounded-xl border border-gray-100 p-5">
                                    <h4 class="font-semibold text-gray-800 mb-4">اطلاعات پایه</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <label class="text-gray-500 block mb-2">نام</label>
                                            <input name="name" value="{{ old('name', $form['name']) }}" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="text-gray-500 block mb-2">ماهیت</label>
                                            <input value="{{ $profile['type'] }}" readonly class="w-full rounded-lg border-gray-200 bg-gray-50 text-gray-600">
                                        </div>
                                        <div>
                                            <label class="text-gray-500 block mb-2">وضعیت</label>
                                            <input value="{{ $profile['status'] }}" readonly class="w-full rounded-lg border-gray-200 bg-gray-50 text-gray-600">
                                        </div>
                                        <div>
                                            <label class="text-gray-500 block mb-2">ایمیل</label>
                                            <input type="email" name="email" value="{{ old('email', $form['email']) }}" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-xl border border-gray-100 p-5">
                                    <h4 class="font-semibold text-gray-800 mb-4">اطلاعات تماس</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <label class="text-gray-500 block mb-2">شماره همراه</label>
                                            <input value="{{ $form['mobile'] }}" readonly class="w-full rounded-lg border-gray-200 bg-gray-50 text-gray-600">
                                        </div>
                                        <div>
                                            <label class="text-gray-500 block mb-2">شهر</label>
                                            <select name="city" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                                <option value="">انتخاب شهر</option>
                                                @foreach ($cityOptions as $option)
                                                    <option value="{{ $option->value }}" @selected(old('city', $form['city']) === $option->value)>{{ $option->getLabel() }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-gray-500 block mb-2">کد پستی</label>
                                            <input name="postal_code" value="{{ old('postal_code', $form['postal_code']) }}" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="text-gray-500 block mb-2">آدرس</label>
                                            <textarea name="address" rows="3" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">{{ old('address', $form['address']) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-xl border border-gray-100 p-5">
                                    <h4 class="font-semibold text-gray-800 mb-4">اطلاعات هویتی</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <label class="text-gray-500 block mb-2">{{ $profile['national_id_label'] }}</label>
                                            <input name="national_id" value="{{ old('national_id', $form['national_id']) }}" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        @if ($user?->type === 'company')
                                            <div>
                                                <label class="text-gray-500 block mb-2">شماره ثبت</label>
                                                <input name="reg_number" value="{{ old('reg_number', $form['reg_number']) }}" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                            </div>
                                        @endif
                                        @if (in_array($user?->type, ['man', 'woman'], true))
                                            <div>
                                                <label class="text-gray-500 block mb-2">تاریخ تولد</label>
                                                <input name="birth_day" value="{{ old('birth_day', $form['birth_day']) }}" placeholder="1402/01/01" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if (in_array($user?->type, ['man', 'woman'], true))
                                    <div class="rounded-xl border border-gray-100 p-5">
                                        <h4 class="font-semibold text-gray-800 mb-4">اطلاعات تحصیلی</h4>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <label class="text-gray-500 block mb-2">مقطع تحصیلی</label>
                                                <select name="education" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                                    <option value="">انتخاب مقطع</option>
                                                    @foreach ($educationOptions as $option)
                                                        <option value="{{ $option->value }}" @selected(old('education', $form['education']) === $option->value)>{{ $option->getLabel() }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="text-gray-500 block mb-2">رشته تحصیلی</label>
                                                <input name="major" value="{{ old('major', $form['major']) }}" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                            </div>
                                            <div class="sm:col-span-2">
                                                <label class="text-gray-500 block mb-2">آخرین محل تحصیل</label>
                                                <input name="university" value="{{ old('university', $form['university']) }}" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="rounded-xl border border-gray-100 p-5 lg:col-span-2">
                                    <h4 class="font-semibold text-gray-800 mb-4">یادداشت</h4>
                                    <textarea name="note" rows="4" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500">{{ old('note', $form['note']) }}</textarea>
                                </div>

                                <div class="lg:col-span-2 flex justify-end">
                                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">ذخیره تغییرات</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tab: KYC -->
                    <div x-show="tab === 'kyc'" style="display: none;" x-transition>
                        <h3 class="text-xl font-bold text-gray-800 mb-6 border-b border-gray-100 pb-4">احراز هویت</h3>
                        <div class="space-y-6">
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <p class="text-sm text-yellow-700">لطفا تصاویر مدارک خود را با کیفیت بالا و خوانا بارگذاری کنید.</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-gray-400 mx-auto mb-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                                    </svg>
                                    <span class="block font-medium text-gray-700 mb-1">تصویر کارت ملی</span>
                                    <span class="text-xs text-gray-500">برای آپلود کلیک کنید</span>
                                </div>
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-gray-400 mx-auto mb-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                                    </svg>
                                    <span class="block font-medium text-gray-700 mb-1">تصویر صفحه اول شناسنامه</span>
                                    <span class="text-xs text-gray-500">برای آپلود کلیک کنید</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Payments -->
                    <div x-show="tab === 'payments'" style="display: none;" x-transition>
                        <h3 class="text-xl font-bold text-gray-800 mb-6 border-b border-gray-100 pb-4">تاریخچه پرداخت‌ها</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-right">
                                <thead class="bg-gray-50 text-gray-700 font-bold">
                                    <tr>
                                        <th class="p-3 rounded-r-lg">شناسه</th>
                                        <th class="p-3">مبلغ (تومان)</th>
                                        <th class="p-3">تاریخ</th>
                                        <th class="p-3">وضعیت</th>
                                        <th class="p-3 rounded-l-lg">توضیحات</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr>
                                        <td class="p-3">#1023</td>
                                        <td class="p-3">۱۵۰,۰۰۰</td>
                                        <td class="p-3">۱۴۰۲/۱۰/۱۵</td>
                                        <td class="p-3"><span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">موفق</span></td>
                                        <td class="p-3">رزرو فضای کار ونک</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3">#1020</td>
                                        <td class="p-3">۵۰,۰۰۰</td>
                                        <td class="p-3">۱۴۰۲/۱۰/۱۰</td>
                                        <td class="p-3"><span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">ناموفق</span></td>
                                        <td class="p-3">افزایش اعتبار</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab: Bookings -->
                    <div x-show="tab === 'bookings'" style="display: none;" x-transition>
                        <h3 class="text-xl font-bold text-gray-800 mb-6 border-b border-gray-100 pb-4">رزروهای من</h3>
                         <div class="space-y-4">
                            <!-- Booking Item -->
                            <div class="border border-gray-200 rounded-lg p-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div>
                                    <h4 class="font-bold text-gray-900">فضای کار اشتراکی آبی</h4>
                                    <p class="text-sm text-gray-500 mt-1">۱۵ دی ۱۴۰۲ - ساعت ۱۰:۰۰ تا ۱۸:۰۰</p>
                                    <div class="flex gap-2 mt-2">
                                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">میز اختصاصی</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs">تایید شده</span>
                                    <button class="text-gray-400 hover:text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                         </div>
                    </div>

                    <!-- Tab: Logs -->
                    <div x-show="tab === 'logs'" style="display: none;" x-transition>
                        <h3 class="text-xl font-bold text-gray-800 mb-6 border-b border-gray-100 pb-4">تاریخچه ورود</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-right">
                                <thead class="bg-gray-50 text-gray-700 font-bold">
                                    <tr>
                                        <th class="p-3 rounded-r-lg">تاریخ</th>
                                        <th class="p-3">ساعت</th>
                                        <th class="p-3 rounded-l-lg">آی‌پی (IP)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr>
                                        <td class="p-3">۱۴۰۲/۱۱/۱۵</td>
                                        <td class="p-3">۱۴:۳۰</td>
                                        <td class="p-3">192.168.1.1</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3">۱۴۰۲/۱۱/۱۴</td>
                                        <td class="p-3">۰۹:۱۵</td>
                                        <td class="p-3">5.23.14.56</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </main>
            </div>
        </div>
    </div>
</x-layouts.app>
