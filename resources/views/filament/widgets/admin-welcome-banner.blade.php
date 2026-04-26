<x-filament-widgets::widget>
    <div class="admin-welcome-banner">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <div class="admin-welcome-greeting">
                    {{ $greeting }}، {{ $name }} 👋
                </div>
                <div class="admin-welcome-sub">
                    به پنل مدیریت کارینو خوش آمدید — اینجا خلاصه‌ای از وضعیت امروز را می‌بینید.
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <span class="admin-welcome-pill">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0V11.25h18v7.5"/>
                    </svg>
                    {{ $jalaliDate }}
                </span>
                <span class="admin-welcome-pill">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $gregorianTime }}
                </span>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
