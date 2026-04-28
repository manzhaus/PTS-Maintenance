<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const user = usePage().props.auth.user;
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-gray-900 border-b border-gray-700 shadow-md">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')" class="text-white font-extrabold text-lg flex items-center tracking-wider">
                                    <i class="fas fa-truck-loading text-blue-500 mr-2"></i>
                                    ERP MAINTENANCE
                                </Link>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')" 
                                         class="text-white hover:text-blue-400 font-medium transition duration-150">
                                    <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                                </NavLink>

                                <div class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-white hover:text-blue-400 transition duration-150 ease-in-out cursor-pointer group relative">
                                    <span :class="{'text-blue-400 border-b-2 border-blue-500': route().current('lorry.*') || route().current('assets.*')}" class="flex items-center">
                                        <i class="fas fa-tools mr-1"></i> Assets <i class="fas fa-chevron-down ml-1 text-xs opacity-70"></i>
                                    </span>
                                    
                                    <div class="absolute top-12 left-0 w-52 bg-white shadow-2xl rounded-md py-2 hidden group-hover:block z-50 border border-gray-200">
                                        <Link :href="route('lorry.index')" class="block px-4 py-2 text-sm text-gray-800 hover:bg-blue-600 hover:text-white font-semibold transition">
                                            <i class="fas fa-truck mr-2"></i> Lorry Management
                                        </Link>
                                        <Link :href="route('assets.index')" class="block px-4 py-2 text-sm text-gray-800 hover:bg-blue-600 hover:text-white font-semibold transition">
                                            <i class="fas fa-boxes mr-2"></i> Asset/Other Logs
                                        </Link>
                                    </div>
                                </div>

                                <NavLink :href="route('staff.index')" :active="route().current('staff.*')" 
                                         class="text-white hover:text-blue-400 font-medium transition duration-150">
                                    <i class="fas fa-users mr-1"></i> Staff
                                </NavLink>

                                <NavLink v-if="user.role === 'admin'" :href="route('admin.budgets.index')" :active="route().current('admin.budgets.*')" 
                                         class="text-white hover:text-blue-400 font-medium transition duration-150">
                                    <i class="fas fa-coins mr-1"></i> Budget Management
                                </NavLink>
                                <NavLink v-else :href="route('budget_requests.index')" :active="route().current('budget_requests.index')" 
                                         class="text-white hover:text-blue-400 font-medium transition duration-150">
                                    <i class="fas fa-hand-holding-usd mr-1"></i> My Budget Requests
                                </NavLink>

                                <NavLink :href="route('reports.index')" :active="route().current('reports.*')" 
                                         class="text-white hover:text-blue-400 font-medium transition duration-150">
                                    <i class="fas fa-file-invoice mr-1"></i> Lorry Maintenance Reports
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-gray-900 hover:text-blue-400 focus:outline-none transition ease-in-out duration-150">
                                                <div class="text-right mr-3 hidden lg:block">
                                                    <div class="font-bold text-white uppercase tracking-tight">{{ user.name }}</div>
                                                    <div class="text-xs text-blue-400 font-semibold">{{ user.role }}</div>
                                                </div>
                                                <img :src="`https://ui-avatars.com/api/?name=${user.name}&background=3b82f6&color=fff&bold=true`" 
                                                     class="h-9 w-9 rounded-full border-2 border-blue-500 shadow-sm" />
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div class="block px-4 py-2 text-xs text-gray-500 font-bold uppercase tracking-widest border-b border-gray-100 mb-1">
                                            LOKASI: {{ user.pts_lokasi || 'HQ ADMIN' }}
                                        </div>
                                        <DropdownLink :href="route('profile.edit')"> <i class="fas fa-user-circle mr-2"></i> Profile </DropdownLink>
                                        <div class="border-t border-gray-100"></div>
                                        <DropdownLink :href="route('logout')" method="post" as="button" class="text-red-600 font-bold">
                                            <i class="fas fa-sign-out-alt mr-2"></i> Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-blue-400 focus:outline-none transition duration-150">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" class="sm:hidden bg-gray-800">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')" class="text-white"> Dashboard </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('staff.index')" :active="route().current('staff.*')" class="text-white"> Staff </ResponsiveNavLink>
                        </div>
                </div>
            </nav>

            <header class="bg-white shadow-sm border-b" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main class="py-6">
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Menambah kontras untuk teks yang sedang aktif */
:deep(.nav-link-active) {
    color: #60a5fa !important; /* Blue 400 */
    border-bottom: 2px solid #3b82f6;
}
</style>