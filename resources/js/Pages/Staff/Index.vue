<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

// Receive data from Controller (Added 'stats')
defineProps({ 
    staff: Array,
    stats: Array 
});

const isEditMode = ref(false);
const showModal = ref(false);

// Form state for adding/editing staff
const form = useForm({
    id: null,
    nama: '',
    jawatan: '',
    gaji_asas: '',
    pts_lokasi: '',
    tarikh_mula_kerja: '',
});

// Fungsi untuk format mata wang RM
const formatRM = (value) => {
    return parseFloat(value).toLocaleString('en-MY', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
};

// Fungsi untuk buka modal tambah baru
const openCreateModal = () => {
    isEditMode.value = false;
    form.reset();
    showModal.value = true;
};

// Fungsi untuk buka modal edit & isi data
const openEditModal = (person) => {
    isEditMode.value = true;
    form.id = person.id;
    form.nama = person.nama;
    form.jawatan = person.jawatan;
    form.gaji_asas = person.gaji_asas;
    form.pts_lokasi = person.pts_lokasi;
    form.tarikh_mula_kerja = person.tarikh_mula_kerja;
    showModal.value = true;
};

const submit = () => {
    if (isEditMode.value) {
        form.put(route('staff.update', form.id), {
            onSuccess: () => {
                form.reset();
                showModal.value = false;
            },
        });
    } else {
        form.post(route('staff.store'), {
            onSuccess: () => {
                form.reset();
                showModal.value = false;
            },
        });
    }
};

const deleteStaff = (id) => {
    if (confirm('Adakah anda pasti mahu memadam maklumat staff ini?')) {
        router.delete(route('staff.destroy', id));
    }
};
</script>

<template>
    <Head title="Staff Management" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Ringkasan & Pengurusan Staff PTS
                </h2>
                <button @click="openCreateModal" class="bg-blue-700 text-white px-4 py-2 rounded-md hover:bg-blue-800 transition font-bold text-sm">
                    + Tambah Staff
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                
                <div v-if="stats && stats.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="stat in stats" :key="stat.pts_lokasi" 
                         class="bg-white overflow-hidden shadow sm:rounded-lg border-l-4 border-blue-600 p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Lokasi PTS</p>
                                <h3 class="text-lg font-extrabold text-gray-800">{{ stat.pts_lokasi || 'Umum' }}</h3>
                            </div>
                            <div class="bg-blue-50 p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline justify-between">
                            <div>
                                <p class="text-xs text-gray-400 font-semibold">Total Gaji Bulanan</p>
                                <p class="text-2xl font-black text-gray-900">RM {{ formatRM(stat.total_gaji) }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ stat.jumlah_staff }} Orang
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white shadow sm:rounded-lg overflow-x-auto">
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-700">Senarai Keseluruhan Kakitangan</h3>
                    </div>
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 uppercase text-xs font-bold">
                                <th class="border-b p-4">Nama</th>
                                <th class="border-b p-4 text-center">Jawatan</th>
                                <th class="border-b p-4 text-center">Lokasi</th>
                                <th class="border-b p-4 text-right">Gaji (RM)</th>
                                <th class="border-b p-4 text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="person in staff" :key="person.id" class="hover:bg-blue-50/30 transition duration-150">
                                <td class="p-4 font-semibold text-gray-800">{{ person.nama }}</td>
                                <td class="p-4 text-center text-gray-600">{{ person.jawatan }}</td>
                                <td class="p-4 text-center">
                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-[10px] font-black uppercase tracking-tighter">
                                        {{ person.pts_lokasi }}
                                    </span>
                                </td>
                                <td class="p-4 text-right font-mono font-bold text-blue-700">
                                    {{ formatRM(person.gaji_asas) }}
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button @click="openEditModal(person)" class="text-blue-600 hover:text-blue-900 font-bold text-xs uppercase underline">
                                            Edit
                                        </button>
                                        <button @click="deleteStaff(person.id)" class="text-red-500 hover:text-red-700 font-bold text-xs uppercase underline">
                                            Padam
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="staff.length === 0">
                                <td colspan="5" class="p-12 text-center text-gray-400 italic">Tiada rekod kakitangan dijumpai dalam pangkalan data.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4 transition-all">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden border border-gray-200">
                <div class="p-5 border-b bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-black text-gray-800 uppercase tracking-tight">
                        {{ isEditMode ? 'Kemaskini Staff' : 'Daftar Staff' }}
                    </h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>
                
                <form @submit.prevent="submit" class="p-6 space-y-5">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase">Nama Penuh</label>
                        <input v-model="form.nama" type="text" class="w-full border-gray-300 rounded-lg p-2.5 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required />
                    </div>
                    
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase">Jawatan</label>
                        <input v-model="form.jawatan" type="text" class="w-full border-gray-300 rounded-lg p-2.5 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required />
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase">Gaji (RM)</label>
                            <input v-model="form.gaji_asas" type="number" step="0.01" class="w-full border-gray-300 rounded-lg p-2.5 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required />
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase">Lokasi PTS</label>
                            <input v-model="form.pts_lokasi" type="text" class="w-full border-gray-300 rounded-lg p-2.5 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required />
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase">Tarikh Mula Kerja</label>
                        <input v-model="form.tarikh_mula_kerja" type="date" class="w-full border-gray-300 rounded-lg p-2.5 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required />
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" @click="showModal = false" class="flex-1 px-4 py-3 bg-gray-100 text-gray-600 rounded-lg font-bold text-sm hover:bg-gray-200 transition">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-3 bg-blue-700 text-white rounded-lg font-bold text-sm hover:bg-blue-800 shadow-lg shadow-blue-200 transition" :disabled="form.processing">
                            {{ isEditMode ? 'Simpan Perubahan' : 'Daftar Sekarang' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>