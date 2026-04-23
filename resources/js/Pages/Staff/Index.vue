<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Head } from '@inertiajs/vue3';

// Receive data from Controller
defineProps({ staff: Array });

// Form state for adding new staff
const form = useForm({
    nama: '',
    jawatan: '',
    gaji_asas: '',
    pts_lokasi: '',
    tarikh_mula_kerja: '',
});

const submit = () => {
    form.post(route('staff.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Staff Management" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Staff PTS Management</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium mb-4">Daftar Staff Baru</h3>
                    <form @submit.prevent="submit" class="grid grid-cols-2 gap-4">
                        <input v-model="form.nama" type="text" placeholder="Nama Penuh" class="border p-2 rounded" />
                        <input v-model="form.jawatan" type="text" placeholder="Jawatan" class="border p-2 rounded" />
                        <input v-model="form.gaji_asas" type="number" placeholder="Gaji Asas" class="border p-2 rounded" />
                        <input v-model="form.pts_lokasi" type="text" placeholder="Lokasi PTS" class="border p-2 rounded" />
                        <input v-model="form.tarikh_mula_kerja" type="date" class="border p-2 rounded" />
                        <button type="submit" class="bg-blue-600 text-white p-2 rounded col-span-2">Simpan Staff</button>
                    </form>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <table class="w-full text-left">
                        <thead>
                            <tr>
                                <th class="border-b p-2">Nama</th>
                                <th class="border-b p-2">Jawatan</th>
                                <th class="border-b p-2">Lokasi</th>
                                <th class="border-b p-2">Gaji (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="person in staff" :key="person.id">
                                <td class="border-b p-2">{{ person.nama }}</td>
                                <td class="border-b p-2">{{ person.jawatan }}</td>
                                <td class="border-b p-2">{{ person.pts_lokasi }}</td>
                                <td class="border-b p-2">{{ person.gaji_asas }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>