<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

// Receive data from Controller
defineProps({ staff: Array });

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
        // Proses Update
        form.put(route('staff.update', form.id), {
            onSuccess: () => {
                form.reset();
                showModal.value = false;
            },
        });
    } else {
        // Proses Store
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
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Staff PTS Management</h2>
                <button @click="openCreateModal" class="bg-blue-700 text-white px-4 py-2 rounded-md hover:bg-blue-800 transition font-bold text-sm">
                    <i class="fas fa-plus mr-2"></i> Tambah Staff
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <div class="p-6 bg-white shadow sm:rounded-lg overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="border-b p-3 text-sm font-bold uppercase text-gray-600">Nama</th>
                                <th class="border-b p-3 text-sm font-bold uppercase text-gray-600 text-center">Jawatan</th>
                                <th class="border-b p-3 text-sm font-bold uppercase text-gray-600 text-center">Lokasi</th>
                                <th class="border-b p-3 text-sm font-bold uppercase text-gray-600 text-right">Gaji (RM)</th>
                                <th class="border-b p-3 text-sm font-bold uppercase text-gray-600 text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="person in staff" :key="person.id" class="hover:bg-gray-50 transition">
                                <td class="border-b p-3 font-medium">{{ person.nama }}</td>
                                <td class="border-b p-3 text-center text-gray-600">{{ person.jawatan }}</td>
                                <td class="border-b p-3 text-center text-gray-600">{{ person.pts_lokasi }}</td>
                                <td class="border-b p-3 text-right font-mono font-bold">{{ parseFloat(person.gaji_asas).toFixed(2) }}</td>
                                <td class="border-b p-3 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button @click="openEditModal(person)" class="bg-amber-100 text-amber-700 px-3 py-1 rounded text-xs font-bold hover:bg-amber-200">
                                            Edit
                                        </button>
                                        <button @click="deleteStaff(person.id)" class="bg-red-100 text-red-700 px-3 py-1 rounded text-xs font-bold hover:bg-red-200">
                                            Padam
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="staff.length === 0">
                                <td colspan="5" class="p-10 text-center text-gray-400">Tiada data kakitangan direkodkan.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden">
                <div class="p-4 border-b bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-700">
                        {{ isEditMode ? 'Kemaskini Maklumat Staff' : 'Daftar Staff Baru' }}
                    </h3>
                </div>
                
                <form @submit.prevent="submit" class="p-6 space-y-4">
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1">Nama Penuh</label>
                        <input v-model="form.nama" type="text" class="border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none" required />
                    </div>
                    
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1">Jawatan</label>
                        <input v-model="form.jawatan" type="text" class="border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none" required />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col">
                            <label class="text-xs font-bold text-gray-500 uppercase mb-1">Gaji Asas (RM)</label>
                            <input v-model="form.gaji_asas" type="number" step="0.01" class="border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none" required />
                        </div>
                        <div class="flex flex-col">
                            <label class="text-xs font-bold text-gray-500 uppercase mb-1">Lokasi PTS</label>
                            <input v-model="form.pts_lokasi" type="text" class="border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none" required />
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1">Tarikh Mula Kerja</label>
                        <input v-model="form.tarikh_mula_kerja" type="date" class="border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none" required />
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="showModal = false" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded font-bold hover:bg-gray-200 transition">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-blue-700 text-white rounded font-bold hover:bg-blue-800 transition" :disabled="form.processing">
                            {{ isEditMode ? 'Kemaskini' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>