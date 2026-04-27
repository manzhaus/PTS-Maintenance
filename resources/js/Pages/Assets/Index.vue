<script setup>
import { ref } from 'vue';
import { useForm, router, Head } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    assets: Array,
    availableCategories: Array,
    currentCategory: String,
    filters: Object
});

const handleExport = () => {
    // Ambil parameter carian semasa dari URL
    const params = new URLSearchParams(window.location.search).toString();
    
    // Redirect ke route export bersama params
    window.location.href = route('assets.export', { category: props.currentCategory });
};

const showModal = ref(false);
const showEditModal = ref(false);
const activeTab = ref('record');

// 1. ADD MAINTENANCE FORM
const recordForm = useForm({
    asset_id: '', 
    jenis_kerja: '', 
    kos_rm: '',
    tarikh: new Date().toISOString().split('T')[0],
    status: 'Siap', 
    resit: null,
});

// 2. EDIT MAINTENANCE FORM
const editForm = useForm({
    id: '', 
    jenis_kerja: '', 
    kos_rm: '', 
    tarikh: '', 
    status: '',
    next_cal: '', // Added for Weighbridge metadata update
    asset_category: '', // Added to track category in edit modal
});

// 3. ASSET REGISTRATION FORM
const assetForm = useForm({
    name: '', 
    category: '', 
    next_cal: '', // This is for metadata
});

const submitRecord = () => {
    recordForm.post(route('assets.store_record'), {
        forceFormData: true,
        onSuccess: () => { showModal.value = false; recordForm.reset(); }
    });
};

const openEdit = (m, asset) => {
    editForm.id = m.id;
    editForm.jenis_kerja = m.jenis_kerja;
    editForm.kos_rm = m.kos_rm;
    editForm.tarikh = m.tarikh;
    editForm.status = m.status;
    
    // Set category and current metadata for the edit form
    editForm.asset_category = asset.category;
    editForm.next_cal = asset.metadata?.tarikh_kalibrasi_seterusnya || '';
    
    showEditModal.value = true;
};

const updateRecord = () => {
    editForm.put(route('assets.update_record', editForm.id), {
        onSuccess: () => { showEditModal.value = false; }
    });
};

const deleteRecord = (id) => {
    if (confirm('Adakah anda pasti mahu memadam rekod ini?')) {
        router.delete(route('assets.destroy_record', id));
    }
};

const submitAsset = () => {
    assetForm.post(route('assets.register'), {
        onSuccess: () => { showModal.value = false; assetForm.reset(); }
    });
};

const filterByCategory = (cat) => {
    router.get(route('assets.index', { category: cat }));
};
</script>

<template>
    <Head title="Asset Maintenance" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Urus Aset & Penyelenggaraan
            </h2>
        </template>

        <div class="dashboard-container">
            <header class="main-header">
                <div>
                    <h1>Penyelenggaraan Aset</h1>
                    <p>Urus pelbagai kategori aset dalam satu paparan berpusat.</p>
                </div>
                
                <button @click="showModal = true" class="btn-primary">+ Log Maintenance / Asset Baru</button>
                <button 
            @click="handleExport" 
            class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700 font-bold text-sm"
        >
            <i class="fas fa-file-export mr-2"></i> Export Excel/CSV
        </button>
            </header>

            <nav class="filter-nav">
                <span class="filter-label">Tapis:</span>
                <div class="filter-pills">
                    <button @click="filterByCategory('All')" :class="['pill', { active: currentCategory === 'All' }]">Semua</button>
                    <button v-for="cat in availableCategories" :key="cat" @click="filterByCategory(cat)" :class="['pill', { active: currentCategory === cat }]">{{ cat }}</button>
                </div>
            </nav>

            <div class="asset-grid">
                <div v-for="asset in assets" :key="asset.id" class="asset-card">
                    <div class="card-header">
                        <div class="asset-info">
                            <span class="category-tag">{{ asset.category }}</span>
                            <h3>{{ asset.name }}</h3>
                            <small v-if="asset.maintenances.length > 0" class="last-service">
                                Servis Terakhir: {{ asset.maintenances[0].tarikh }}
                            </small>
                        </div>
                        
                        <div v-if="asset.category === 'Weighbridge'" class="metadata-display">
                            <div class="meta-item">
                                <small>Kalibrasi Seterusnya:</small>
                                <span :class="{'text-danger': true}">{{ asset.metadata?.tarikh_kalibrasi_seterusnya || 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="table-wrapper">
                        <table class="grid-table">
                            <colgroup>
                                <col style="width: 12%;">
                                <col style="width: 33%;">
                                <col style="width: 15%;">
                                <col style="width: 15%;">
                                <col style="width: 10%;">
                                <col style="width: 15%;">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>Tarikh</th>
                                    <th>Jenis Kerja</th>
                                    <th>Kos (RM)</th>
                                    <th>Status</th>
                                    <th>Resit</th>
                                    <th>Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="m in asset.maintenances" :key="m.id">
                                    <td>{{ m.tarikh }}</td>
                                    <td class="truncate-text">{{ m.jenis_kerja }}</td>
                                    <td>{{ parseFloat(m.kos_rm).toFixed(2) }}</td>
                                    <td>
                                        <span :class="['status-badge', m.status === 'Siap' ? 'siap' : 'proses']">{{ m.status }}</span>
                                    </td>
                                    <td>
                                        <a v-if="m.resit_path" :href="'/storage/' + m.resit_path" target="_blank" class="link-text">Lihat</a>
                                        <span v-else class="text-muted">-</span>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <button @click="openEdit(m, asset)" class="btn-edit">Edit</button>
                                            <button @click="deleteRecord(m.id)" class="btn-delete">Padam</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="asset.maintenances.length === 0">
                                    <td colspan="6" style="text-align: center; padding: 20px; color: #94a3b8;">Tiada rekod penyelenggaraan.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div v-if="showModal" class="modal-overlay">
                <div class="modal-content">
                    <div class="modal-tabs">
                        <button @click="activeTab = 'record'" :class="{ active: activeTab === 'record' }">Log Kerja</button>
                        <button @click="activeTab = 'asset'" :class="{ active: activeTab === 'asset' }">Daftar Aset</button>
                    </div>
                    <div class="modal-body">
                        <form v-if="activeTab === 'record'" @submit.prevent="submitRecord">
                            <div class="form-group">
                                <label>Pilih Aset</label>
                                <select v-model="recordForm.asset_id" required class="form-input">
                                    <option v-for="a in assets" :key="a.id" :value="a.id">{{ a.name }} ({{ a.category }})</option>
                                </select>
                            </div>
                            <input v-model="recordForm.jenis_kerja" placeholder="Jenis Kerja" required class="form-input">
                            <div class="form-row">
                                <input v-model="recordForm.kos_rm" type="number" step="0.01" placeholder="Kos RM" required class="form-input">
                                <input v-model="recordForm.tarikh" type="date" required class="form-input">
                            </div>
                            <div class="form-group">
                                <label>Status Kerja</label>
                                <select v-model="recordForm.status" class="form-input">
                                    <option value="Siap">Siap</option>
                                    <option value="Dalam Proses">Dalam Proses</option>
                                </select>
                            </div>
                            <input type="file" @input="recordForm.resit = $event.target.files[0]" class="form-input">
                            <button type="submit" class="btn-submit">Simpan Rekod</button>
                        </form>

                        <form v-else @submit.prevent="submitAsset">
                            <label>Kategori (Pilih atau Taip Baru)</label>
                            <input v-model="assetForm.category" list="category-options" class="form-input" placeholder="E.g. CCTV, Weighbridge">
                            <datalist id="category-options">
                                <option v-for="c in availableCategories" :key="c" :value="c"></option>
                            </datalist>
                            
                            <label>Nama Aset</label>
                            <input v-model="assetForm.name" placeholder="Nama Aset" required class="form-input">

                            <div v-if="assetForm.category === 'Weighbridge'" class="highlight-box">
                                <label>📅 Tarikh Kalibrasi Seterusnya</label>
                                <input v-model="assetForm.next_cal" type="date" class="form-input">
                            </div>

                            <button type="submit" class="btn-submit">Daftar Aset Baru</button>
                        </form>
                        <button @click="showModal = false" class="btn-close">Tutup</button>
                    </div>
                </div>
            </div>

            <div v-if="showEditModal" class="modal-overlay">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3>Kemaskini Rekod</h3>
                        <form @submit.prevent="updateRecord">
                            <label>Jenis Kerja</label>
                            <input v-model="editForm.jenis_kerja" required class="form-input">
                            <div class="form-row">
                                <div>
                                    <label>Kos (RM)</label>
                                    <input v-model="editForm.kos_rm" type="number" step="0.01" required class="form-input">
                                </div>
                                <div>
                                    <label>Tarikh</label>
                                    <input v-model="editForm.tarikh" type="date" required class="form-input">
                                </div>
                            </div>
                            <label>Status</label>
                            <select v-model="editForm.status" class="form-input">
                                <option value="Siap">Siap</option>
                                <option value="Dalam Proses">Dalam Proses</option>
                            </select>

                            <div v-if="editForm.asset_category === 'Weighbridge'" class="highlight-box">
                                <label>📅 Kemaskini Tarikh Kalibrasi Seterusnya</label>
                                <input v-model="editForm.next_cal" type="date" class="form-input">
                            </div>

                            <button type="submit" class="btn-submit">Simpan Perubahan</button>
                            <button type="button" @click="showEditModal = false" class="btn-close">Batal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Style kekal sama seperti asal */
.dashboard-container { max-width: 1200px; margin: 0 auto; padding: 20px; }
.main-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.btn-primary { background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; }

/* Filter Pills */
.filter-nav { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
.filter-pills { display: flex; gap: 8px; }
.pill { padding: 6px 16px; border-radius: 20px; border: 1px solid #e2e8f0; background: white; cursor: pointer; font-size: 14px; }
.pill.active { background: #2563eb; color: white; border-color: #2563eb; }

/* Table & Card */
.asset-card { background: white; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 24px; overflow: hidden; }
.card-header { padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #f8fafc; }
.last-service { display: block; color: #64748b; font-size: 12px; margin-top: 4px; }

/* Metadata Display */
.metadata-display { text-align: right; }
.meta-item small { display: block; font-size: 10px; color: #64748b; font-weight: bold; text-transform: uppercase; }
.meta-item span { font-size: 14px; font-weight: 700; }
.text-danger { color: #dc2626; }

.grid-table { width: 100%; table-layout: fixed; border-collapse: collapse; }
.grid-table th, .grid-table td { border: 1px solid #e2e8f0; padding: 12px 15px; font-size: 13px; text-align: left; }
.grid-table th { background: #f8fafc; color: #64748b; text-transform: uppercase; font-size: 11px; }

.action-btns { display: flex; gap: 5px; }
.btn-edit { background: #f3f4f6; color: #2563eb; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; font-size: 11px; font-weight: bold; }
.btn-delete { background: #fee2e2; color: #dc2626; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; font-size: 11px; font-weight: bold; }

.status-badge { padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; }
.status-badge.siap { background: #dcfce7; color: #166534; }
.status-badge.proses { background: #fef9c3; color: #854d0e; }

/* Modal & Forms */
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 100; }
.modal-content { background: white; border-radius: 12px; width: 100%; max-width: 450px; overflow: hidden; }
.modal-tabs { display: flex; border-bottom: 1px solid #e2e8f0; }
.modal-tabs button { flex: 1; padding: 12px; border: none; background: #f8fafc; cursor: pointer; font-weight: 600; }
.modal-tabs button.active { background: white; color: #2563eb; border-bottom: 2px solid #2563eb; }
.modal-body { padding: 20px; }
.form-input { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; margin-bottom: 12px; box-sizing: border-box; }
.form-row { display: flex; gap: 10px; }
.btn-submit { width: 100%; background: #2563eb; color: white; padding: 10px; border: none; border-radius: 6px; font-weight: 700; cursor: pointer; }
.btn-close { width: 100%; background: transparent; border: none; color: #64748b; margin-top: 10px; cursor: pointer; text-decoration: underline; font-size: 12px; }
.highlight-box { background: #f0f9ff; padding: 12px; border-radius: 8px; border: 1px dashed #3b82f6; margin-bottom: 15px; }
</style>