<div
    x-data="{
        now: new Date(),
        formatTime() {
            return new Intl.DateTimeFormat('fa-IR', {
                hour: '2-digit',
                minute: '2-digit',
            }).format(this.now)
        },
        formatDate() {
            return new Intl.DateTimeFormat('fa-IR-u-ca-persian', {
                weekday: 'short',
                day: 'numeric',
                month: 'short',
            }).format(this.now)
        },
        tick() {
            this.now = new Date()
        },
    }"
    x-init="tick(); setInterval(() => tick(), 30000)"
    class="topbar-clock"
    dir="rtl"
>
    <span class="topbar-clock__time" x-text="formatTime()">--:--</span>
    <span class="topbar-clock__sep">·</span>
    <span class="topbar-clock__date" x-text="formatDate()"></span>
</div>
