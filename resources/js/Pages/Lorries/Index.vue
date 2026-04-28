<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Head } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({ lorries: Array });

// --- Logic for Adding a New Lorry ---
const lorryForm = useForm({
    no_plat: '',
    model: '',
    tahun: '',
    pts_lokasi: '',
    odometer_semasa: '',
});

const submitLorry = () => {
    lorryForm.post(route('lorry.store'), {
        onSuccess: () => lorryForm.reset(),
    });
};

// --- Logic for Maintenance Modal ---
const showModal = ref(false);
const selectedLorry = ref(null);

const maintenanceForm = useForm({
    lorry_id: '',
    tarikh: new Date().toISOString().split('T')[0],
    jenis_maintenance: 'Servis',
    kos_rm: '',
    vendor: '',
    odometer_masa_servis: '',
    resit_upload: null,
});

const openModal = (lorry) => {
    selectedLorry.value = lorry;
    maintenanceForm.lorry_id = lorry.id;
    showModal.value = true;
};

const submitMaintenance = () => {
    maintenanceForm.post(route('maintenance.store'), {
        onSuccess: () => {
            showModal.value = false;
            maintenanceForm.reset();
        },
    });
};
</script>

<template>
    <Head title="Lorry Management" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pengurusan Lori & Penyelenggaraan</h2>
        </template>

        <div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <div class="bg-white p-6 shadow rounded-lg">
    <h3 class="text-lg font-bold mb-4 text-blue-800 border-b pb-2">Pendaftaran Lori Baru</h3>
    <form @submit.prevent="submitLorry" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium">No. Plat</label>
            <input v-model="lorryForm.no_plat" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Contoh: VAB 1234" />
        </div>
        
        <div>
            <label class="block text-sm font-medium">Model</label>
            <input v-model="lorryForm.model" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Contoh: Hino 300" />
        </div>

        <div>
            <label class="block text-sm font-medium">Tahun</label>
            <input v-model="lorryForm.tahun" type="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Contoh: 2022" />
        </div>

        <div>
            <label class="block text-sm font-medium">Lokasi PTS</label>
            <input v-model="lorryForm.pts_lokasi" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Contoh: Shah Alam" />
        </div>

        <div>
            <label class="block text-sm font-medium">Odometer Semasa (KM)</label>
            <input v-model="lorryForm.odometer_semasa" type="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>

        <div class="flex items-end lg:col-span-1">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 font-bold w-full">
                + Simpan Lori
            </button>
        </div>
    </form>
</div>

            <div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-4 border-b">No Plat</th>
                <th class="p-4 border-b">Model</th>
                <th class="p-4 border-b">Lokasi PTS</th>
                <th class="p-4 border-b">Status / Isu</th>
                <th class="p-4 border-b">Odometer Terkini</th>
                <th class="p-4 border-b text-center">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="lorry in lorries" :key="lorry.id" class="hover:bg-gray-50">
                <td class="p-4 border-b font-bold">{{ lorry.no_plat }}</td>
                
                <td class="p-4 border-b text-gray-700">{{ lorry.model }}</td>
                
                <td class="p-4 border-b">
                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-semibold">
                        {{ lorry.pts_lokasi }}
                    </span>
                </td>

                <td class="p-4 border-b">
                    <span v-if="lorry.maintenance_logs_count >= 3" class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold animate-pulse">
                        ⚠️ Check recurring issue
                    </span>
                    <span v-else class="text-green-600 text-sm font-medium">✅ Normal</span>
                </td>
                <td class="p-4 border-b text-gray-600">{{ lorry.odometer_semasa.toLocaleString() }} KM</td>
                <td class="p-4 border-b text-center">
                    <button @click="openModal(lorry)" 
                        style="background-color: #4f46e5; color: white; padding: 5px 15px; border-radius: 4px; border: none; cursor: pointer; font-weight: bold;">
                    + Log Maintenance
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
        </div>

        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-2xl">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Log Penyelenggaraan</h3>
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm font-mono">{{ selectedLorry.no_plat }}</span>
                </div>
                
                <form @submit.prevent="submitMaintenance" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Maintenance</label>
                        <select v-model="maintenanceForm.jenis_maintenance" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option>Servis</option>
                            <option>Tayar</option>
                            <option>Bateri</option>
                            <option>Repair</option>
                            <option>Lain-lain</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kos (RM)</label>
                        <input v-model="maintenanceForm.kos_rm" type="number" step="0.01" class="w-full border-gray-300 rounded-md shadow-sm" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Vendor / Bengkel</label>
                        <input v-model="maintenanceForm.vendor" type="text" class="w-full border-gray-300 rounded-md shadow-sm" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Odometer Semasa Servis</label>
                        <input v-model="maintenanceForm.odometer_masa_servis" type="number" class="w-full border-gray-300 rounded-md shadow-sm" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Muat Naik Resit (Gambar/PDF)</label>
                        <input type="file" @input="maintenanceForm.resit_upload = $event.target.files[0]" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-6 border-t">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">Batal</button>
                        <button type="submit" 
        :disabled="maintenanceForm.processing"
        style="background-color: #16a34a; color: white; padding: 10px 20px; border-radius: 6px; border: none; cursor: pointer; font-weight: bold;">
        {{ maintenanceForm.processing ? 'Menyimpan...' : 'Simpan Log Penyelenggaraan' }}
</button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>