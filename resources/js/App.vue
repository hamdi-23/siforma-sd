<script setup>
const user = {
    name: 'Admin Sekolah',
    role: 'Siforma SD',
};

const stats = [
    { label: 'Guru Aktif', value: '42', detail: 'Data terbaru', tone: 'sky' },
    { label: 'Presensi Hari Ini', value: '91%', detail: '38 dari 42 hadir', tone: 'emerald' },
    { label: 'Laporan Masuk', value: '27', detail: 'Menunggu 5 laporan', tone: 'amber' },
    { label: 'Rekap Bulanan', value: '12', detail: 'Siap ditinjau', tone: 'rose' },
];

const modules = [
    {
        title: 'Presensi',
        description: 'Kelola kehadiran guru, keterlambatan, izin, dan riwayat presensi harian.',
        href: '/attendance',
        action: 'Buka presensi',
        accent: 'bg-sky-600',
    },
    {
        title: 'Laporan Harian',
        description: 'Pantau laporan pembelajaran, catatan kelas, dan proses review kepala sekolah.',
        href: '/daily-report',
        action: 'Lihat laporan',
        accent: 'bg-emerald-600',
    },
    {
        title: 'Rekap Bulanan',
        description: 'Ringkas presensi dan laporan guru dalam format rekap bulanan yang mudah ditinjau.',
        href: '/monthly-recap',
        action: 'Cek rekap',
        accent: 'bg-amber-500',
    },
];

const agenda = [
    { time: '07.00', title: 'Validasi presensi pagi', status: 'Berjalan' },
    { time: '10.30', title: 'Review laporan kelas 4', status: 'Prioritas' },
    { time: '14.00', title: 'Finalisasi rekap mingguan', status: 'Terjadwal' },
];

const activities = [
    { name: 'Bu Sari', activity: 'mengirim laporan harian', time: '12 menit lalu' },
    { name: 'Pak Andi', activity: 'memperbarui data presensi', time: '28 menit lalu' },
    { name: 'Kepala Sekolah', activity: 'meninjau rekap bulanan', time: '1 jam lalu' },
];
</script>

<template>
    <main class="min-h-screen bg-[#f6f8fb] text-slate-950">
        <aside class="fixed inset-y-0 left-0 hidden w-72 border-r border-slate-200 bg-white px-5 py-6 lg:block">
            <a class="flex items-center gap-3" href="/vue" aria-label="Siforma SD">
                <span class="grid size-11 place-items-center rounded-lg bg-sky-700 text-lg font-bold text-white">SD</span>
                <span>
                    <span class="block text-base font-bold">Siforma SD</span>
                    <span class="block text-sm text-slate-500">Manajemen sekolah</span>
                </span>
            </a>

            <nav class="mt-8 space-y-1">
                <a class="flex items-center justify-between rounded-lg bg-slate-950 px-4 py-3 text-sm font-semibold text-white" href="/vue">
                    <span>Dashboard</span>
                    <span class="text-xs text-slate-300">Aktif</span>
                </a>
                <a class="block rounded-lg px-4 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950" href="/attendance">Presensi</a>
                <a class="block rounded-lg px-4 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950" href="/daily-report">Laporan Harian</a>
                <a class="block rounded-lg px-4 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950" href="/monthly-recap">Rekap Bulanan</a>
            </nav>

            <div class="absolute inset-x-5 bottom-6 rounded-lg border border-slate-200 bg-slate-50 p-4">
                <p class="text-sm font-semibold text-slate-900">Mode Vue aktif</p>
                <p class="mt-1 text-sm leading-6 text-slate-500">Frontend ini siap dipakai untuk migrasi halaman bertahap.</p>
            </div>
        </aside>

        <section class="lg:pl-72">
            <header class="sticky top-0 z-10 border-b border-slate-200 bg-white/90 backdrop-blur">
                <div class="flex items-center justify-between px-5 py-4 sm:px-8">
                    <div>
                        <p class="text-sm font-semibold text-slate-500">{{ user.role }}</p>
                        <h1 class="text-xl font-bold sm:text-2xl">Dashboard Operasional</h1>
                    </div>

                    <div class="flex items-center gap-3">
                        <a class="hidden rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-sky-300 hover:text-sky-700 sm:block" href="/dashboard">
                            Blade dashboard
                        </a>
                        <div class="grid size-10 place-items-center rounded-lg bg-emerald-600 text-sm font-bold text-white">
                            {{ user.name.charAt(0) }}
                        </div>
                    </div>
                </div>
            </header>

            <div class="px-5 py-6 sm:px-8 sm:py-8">
                <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <article
                        v-for="stat in stats"
                        :key="stat.label"
                        class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm"
                    >
                        <p class="text-sm font-semibold text-slate-500">{{ stat.label }}</p>
                        <div class="mt-4 flex items-end justify-between gap-3">
                            <strong class="text-3xl font-bold tracking-normal">{{ stat.value }}</strong>
                            <span
                                class="h-2.5 w-16 rounded-full"
                                :class="{
                                    'bg-sky-500': stat.tone === 'sky',
                                    'bg-emerald-500': stat.tone === 'emerald',
                                    'bg-amber-400': stat.tone === 'amber',
                                    'bg-rose-500': stat.tone === 'rose',
                                }"
                            ></span>
                        </div>
                        <p class="mt-3 text-sm text-slate-500">{{ stat.detail }}</p>
                    </article>
                </section>

                <section class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
                    <div class="space-y-6">
                        <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-sky-700">Modul utama</p>
                                    <h2 class="mt-1 text-2xl font-bold">Kelola kegiatan sekolah dari satu tempat</h2>
                                </div>
                                <a class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-800" href="/dashboard">
                                    Masuk dashboard
                                </a>
                            </div>

                            <div class="mt-6 grid gap-4 md:grid-cols-3">
                                <a
                                    v-for="module in modules"
                                    :key="module.title"
                                    :href="module.href"
                                    class="group rounded-lg border border-slate-200 p-5 transition hover:-translate-y-1 hover:border-slate-300 hover:shadow-md"
                                >
                                    <span class="block h-1.5 w-12 rounded-full" :class="module.accent"></span>
                                    <h3 class="mt-5 text-lg font-bold">{{ module.title }}</h3>
                                    <p class="mt-3 min-h-24 text-sm leading-6 text-slate-600">{{ module.description }}</p>
                                    <span class="mt-5 inline-flex text-sm font-semibold text-sky-700 group-hover:text-sky-900">
                                        {{ module.action }}
                                    </span>
                                </a>
                            </div>
                        </section>

                        <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-slate-500">Aktivitas terbaru</p>
                                    <h2 class="mt-1 text-xl font-bold">Pergerakan data hari ini</h2>
                                </div>
                            </div>

                            <div class="mt-5 overflow-hidden rounded-lg border border-slate-200">
                                <div
                                    v-for="activity in activities"
                                    :key="activity.name + activity.time"
                                    class="grid gap-2 border-b border-slate-200 px-4 py-4 last:border-b-0 sm:grid-cols-[180px_minmax(0,1fr)_120px]"
                                >
                                    <p class="font-semibold text-slate-900">{{ activity.name }}</p>
                                    <p class="text-sm text-slate-600">{{ activity.activity }}</p>
                                    <p class="text-sm text-slate-500 sm:text-right">{{ activity.time }}</p>
                                </div>
                            </div>
                        </section>
                    </div>

                    <aside class="space-y-6">
                        <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                            <p class="text-sm font-semibold text-slate-500">Agenda</p>
                            <h2 class="mt-1 text-xl font-bold">Hari ini</h2>

                            <div class="mt-5 space-y-3">
                                <div
                                    v-for="item in agenda"
                                    :key="item.time"
                                    class="rounded-lg border border-slate-200 p-4"
                                >
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="text-sm font-bold text-slate-900">{{ item.time }}</span>
                                        <span class="rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ item.status }}</span>
                                    </div>
                                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ item.title }}</p>
                                </div>
                            </div>
                        </section>

                        <section class="rounded-lg bg-slate-950 p-5 text-white shadow-sm sm:p-6">
                            <p class="text-sm font-semibold text-sky-200">Prioritas</p>
                            <h2 class="mt-2 text-xl font-bold">Lengkapi laporan yang belum masuk</h2>
                            <p class="mt-3 text-sm leading-6 text-slate-300">
                                Ada 5 laporan yang belum dikirim. Gunakan modul laporan harian untuk memantau status dan mengirim pengingat.
                            </p>
                            <a class="mt-5 inline-flex rounded-lg bg-white px-4 py-2 text-sm font-semibold text-slate-950 transition hover:bg-sky-100" href="/daily-report">
                                Tinjau laporan
                            </a>
                        </section>
                    </aside>
                </section>
            </div>
        </section>
    </main>
</template>
