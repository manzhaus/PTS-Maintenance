<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Head } from '@inertiajs/vue3';

const props = defineProps({ 
    reports: Array,
    filters: Object,
    userRole: String
});

const exportData = () => {
    // Generate the query string from the form data
    const queryParams = new URLSearchParams({
        no_plat: filterForm.no_plat,
        pts_lokasi: filterForm.pts_lokasi,
        bulan: filterForm.bulan,
        tahun: filterForm.tahun
    }).toString();

    // Redirect to the export route
    window.location.href = `/reports/export?${queryParams}`;
};

const filterForm = useForm({
    no_plat: props.filters.no_plat || '',
    tahun: props.filters.tahun || '',
    bulan: props.filters.bulan|| '',
    pts_lokasi: props.filters.pts_lokasi || '',
});

const search = () => {
    filterForm.get(route('reports.index'), {
        preserveState: true,
        replace: true
    });
};

const reset = () => {
    filterForm.no_plat = '';
    filterForm.tahun = '';
    filterForm.pts_lokasi = '';
    search();
};
</script>

<template>
    <Head title="Maintenance Report" />
    <AuthenticatedLayout>
        <div class="py-12 max-w-7xl mx-auto px-4">
            
            <div style="background: #f3f4f6; padding: 20px; border-radius: 8px; margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end;">
                <div>
                    <label style="display:block; font-size: 12px;">No Plat</label>
                    <input v-model="filterForm.no_plat" type="text" placeholder="Cari Plat..." style="border: 1px solid #ccc; padding: 8px; border-radius: 4px;">
                </div>
                <div>
    <label style="display:block; font-size: 12px; font-weight: bold; margin-bottom: 4px;">PTS Lokasi</label>
    <input 
        v-model="filterForm.pts_lokasi" 
        type="text" 
        placeholder="Cari PTS..." 
        :readonly="userRole === 'supervisor'"
        :style="{
            border: '1px solid #ccc', 
            padding: '8px', 
            borderRadius: '4px',
            backgroundColor: userRole === 'supervisor' ? '#e5e7eb' : 'white',
            cursor: userRole === 'supervisor' ? 'not-allowed' : 'text'
        }"
    >
</div>
                <div>
                    <label style="display:block; font-size: 12px;">Tahun</label>
                    <input v-model="filterForm.tahun" type="number" placeholder="2026" style="border: 1px solid #ccc; padding: 8px; border-radius: 4px;">
                </div>
                <div>
    <label style="display:block; font-size: 12px; font-weight: bold; margin-bottom: 4px;">Bulan</label>
    <select v-model="filterForm.bulan" style="border: 1px solid #ccc; padding: 8px; border-radius: 4px; background: white;">
        <option value="">Semua Bulan</option>
        <option value="1">Januari</option>
        <option value="2">Februari</option>
        <option value="3">Mac</option>
        <option value="4">April</option>
        <option value="5">Mei</option>
        <option value="6">Jun</option>
        <option value="7">Julai</option>
        <option value="8">Ogos</option>
        <option value="9">September</option>
        <option value="10">Oktober</option>
        <option value="11">November</option>
        <option value="12">Disember</option>
    </select>
</div>
                <button @click="search" style="background: #2563eb; color: white; padding: 9px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Cari</button>
                <button @click="reset" style="background: #9ca3af; color: white; padding: 9px 20px; border: none; border-radius: 4px; cursor: pointer;">Reset</button>
                <button 
        @click="exportData" 
        style="background: #059669; color: white; padding: 9px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; margin-left: auto;"
    >
        📥 Export CSV
    </button>
            </div>

            <div style="background-color: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background-color: #374151; color: white;">
                        <tr>
                            <th style="padding: 15px;">No. Plat</th>
                            <th style="padding: 15px;">PTS</th>
                            <th style="padding: 15px;">Bulan/Tahun</th>
                            <th style="padding: 15px;">Total Kos</th>
                            <th style="padding: 15px;">Supervisor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(report, index) in reports" :key="index" style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px; font-weight: bold; text-align: center;">{{ report.no_plat }}</td>
                        <td style="padding: 12px; text-align: center;">{{ report.pts_lokasi }}</td>
                        <td style="padding: 12px; text-align: center;">{{ report.bulan }} {{ report.tahun }}</td>
                        <td style="padding: 12px; color: #b91c1c; font-weight: bold; text-align: center;">RM {{ report.total_kos }}</td>
                        <td style="padding: 12px; font-size: 12px; color: #666; text-align: center;">{{ report.admin_name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>