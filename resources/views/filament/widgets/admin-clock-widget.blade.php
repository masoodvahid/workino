<x-filament-widgets::widget>
    <div
        x-data="{
            now: new Date(),
            formatTime() {
                return new Intl.DateTimeFormat('fa-IR', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                }).format(this.now)
            },
            formatDate() {
                return new Intl.DateTimeFormat('fa-IR-u-ca-persian', {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                }).format(this.now)
            },
            tick() {
                this.now = new Date()
            },
        }"
        x-init="tick(); setInterval(() => tick(), 1000)"
        class="admin-clock-widget"
        dir="rtl"
    >
        <div class="admin-clock-widget__meta">
            <span class="admin-clock-widget__eyebrow">امروز</span>
            <span class="admin-clock-widget__date" x-text="formatDate()">{{ $todayLabel }}</span>
        </div>

        <div class="admin-clock-widget__time" x-text="formatTime()">--:--:--</div>
    </div>
</x-filament-widgets::widget>
