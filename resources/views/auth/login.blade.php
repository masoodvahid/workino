<x-layouts.app title="ورود / ثبت نام | ورکینو">
    @php
        $otpSent = session('otp_sent', false);
        $mobile = old('mobile', session('otp_mobile'));
    @endphp
    <div class="min-h-[80vh] flex items-center justify-center bg-gray-50 py-12 px-4">
        <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md" x-data="{ step: {{ $otpSent ? 2 : 1 }} }">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">به ورکینو خوش آمدید</h1>
                <p class="text-gray-500 text-sm">برای استفاده از خدمات، وارد شوید یا ثبت نام کنید</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0">
                <form method="POST" action="{{ route('auth.otp.send') }}">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">شماره موبایل</label>
                        <input name="mobile" value="{{ $mobile }}" type="tel" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 py-3 text-center text-lg tracking-widest" placeholder="09xxxxxxxxx" dir="ltr" required>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition duration-200">
                        دریافت کد تایید
                    </button>
                </form>
                
                <div class="mt-6 text-center text-xs text-gray-400">
                    با ورود به ورکینو، <a href="#" class="text-blue-600 hover:underline">قوانین و مقررات</a> را می‌پذیرید.
                </div>
            </div>

            <div x-show="step === 2" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-x-10" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="mb-6 text-center">
                    <p class="text-sm text-gray-600 mb-1">کد تایید به شماره <span dir="ltr" class="font-bold">{{ $mobile }}</span> ارسال شد.</p>
                    <button @click="step = 1" class="text-blue-600 text-xs hover:underline">ویرایش شماره</button>
                </div>

                <form method="POST" action="{{ route('auth.otp.verify') }}">
                    @csrf
                    <input type="hidden" name="mobile" value="{{ $mobile }}">
                    <div class="mb-6 flex justify-center" dir="ltr">
                        <input name="code" type="text" maxlength="6" class="w-full max-w-[220px] h-12 text-center text-xl font-bold border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 tracking-[0.5em]" inputmode="numeric" autocomplete="one-time-code" required>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition duration-200">
                        ورود به حساب
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <button class="text-sm text-gray-500 hover:text-blue-600">ارسال مجدد کد (۱:۵۹)</button>
                </div>
            </div>

        </div>
    </div>
</x-layouts.app>
