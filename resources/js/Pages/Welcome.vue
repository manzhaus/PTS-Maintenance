<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Selamat Datang" />

    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 selection:bg-blue-500 selection:text-white">
        <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-blue-500"
                >Dashboard</Link
            >

            <template v-else>
                <Link
                    :href="route('login')"
                    class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-blue-500"
                    >Log Masuk</Link
                >
            </template>
        </div>

        <div class="max-w-7xl mx-auto p-6 lg:p-8 text-center">
            <div class="flex justify-center">
                <img src="/images/logo_pts.png" alt="PTS Logo" class="h-32 w-auto drop-shadow-lg mb-8">
            </div>

            <div class="mt-4">
                <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl">
                    Sistem Pengurusan Penyelenggaraan PTS
                </h1>
                <p class="mt-6 text-lg leading-8 text-gray-600 max-w-2xl mx-auto">
                    Platform bersepadu untuk memantau status lori, log penyelenggaraan, 
                    dan pengurusan bajet secara masa nyata (real-time).
                </p>
            </div>

            <div class="mt-10 flex items-center justify-center gap-x-6">
                <Link
                    :href="route('login')"
                    class="rounded-md bg-blue-600 px-10 py-3 text-lg font-bold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                >
                    Mula Sekarang
                </Link>
            </div>

            <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-3">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="text-blue-600 font-bold mb-2">🚛 Log Lori</div>
                    <p class="text-sm text-gray-500">Pantau odometer dan maklumat aset lori dengan mudah.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="text-green-600 font-bold mb-2">🔧 Maintenance</div>
                    <p class="text-sm text-gray-500">Rekod setiap servis, tayar, dan pembaikan secara digital.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="text-purple-600 font-bold mb-2">📊 Laporan</div>
                    <p class="text-sm text-gray-500">Hasilkan laporan kos penyelenggaraan untuk pihak pengurusan.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
</style>