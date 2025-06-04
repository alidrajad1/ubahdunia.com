<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);
const searchQuery = ref('');

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    router.post(route('logout'));
};


const performSearch = () => {
    console.log('Mencari:', searchQuery.value);
};
</script>

<template>
    <div>

        <Head :title="title" />

        <Banner />

        <div class="min-h-screen bg-gray-100">
            <nav class="sticky top-0 z-50 bg-white border-b border-gray-100">
                <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="flex justify-between h-auto">
                        <div class="flex">
                            <div class="flex items-center shrink-0">
                                <Link :href="route('dashboard')">
                                <ApplicationMark class="block w-auto h-12" />
                                </Link>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>
                                <NavLink :href="route('available-donation')"
                                    :active="route().current('available-donation')">
                                    Donasi Tersedia
                                </NavLink>
                                <NavLink :href="route('news')" :active="route().current('news')">
                                    Berita
                                </NavLink>
                                <NavLink :href="route('volunteer')" :active="route().current('volunteer')">
                                    Volunteer
                                </NavLink>
                            </div>
                        </div>

                        <!-- Bagian ini akan disembunyikan di layar kecil (mobile) -->
                        <!-- Tambahkan kelas 'hidden' dan 'sm:flex' -->
                        <div class="items-center hidden ms-auto sm:flex">
                            <form @submit.prevent="performSearch" class="relative hidden w-96 sm:block">
                                <input type="text" v-model="searchQuery" placeholder="Cari..."
                                    class="block w-full pr-10 border-gray-300 rounded-full shadow-sm focus:border-[#3198E8] focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <button type="submit"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-[#3198E8]">
                                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </form>

                            <div class="relative ms-3">
                                <Dropdown v-if="$page.props.jetstream.hasTeamFeatures && $page.props.auth.user"
                                    align="right" width="60">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50">
                                                {{ $page.props.auth.user.current_team.name }}
                                                <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div class="w-60">
                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                Manage Team
                                            </div>

                                            <DropdownLink
                                                :href="route('teams.show', $page.props.auth.user.current_team)">
                                                Team Settings
                                            </DropdownLink>

                                            <DropdownLink v-if="$page.props.jetstream.canCreateTeams"
                                                :href="route('teams.create')">
                                                Create New Team
                                            </DropdownLink>

                                            <template v-if="$page.props.auth.user.all_teams.length > 1">
                                                <div class="border-t border-gray-200" />

                                                <div class="block px-4 py-2 text-xs text-gray-400">
                                                    Switch Teams
                                                </div>

                                                <template v-for="team in $page.props.auth.user.all_teams"
                                                    :key="team.id">
                                                    <form @submit.prevent="switchToTeam(team)">
                                                        <DropdownLink as="button">
                                                            <div class="flex items-center">
                                                                <svg v-if="team.id == $page.props.auth.user.current_team_id"
                                                                    class="text-green-400 me-2 size-5"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="1.5"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>

                                                                <div>{{ team.name }}</div>
                                                            </div>
                                                        </DropdownLink>
                                                    </form>
                                                </template>
                                            </template>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>

                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            v-if="$page.props.jetstream.managesProfilePhotos && $page.props.auth.user"
                                            class="flex text-sm transition border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300">
                                            <img class="object-cover rounded-full size-8"
                                                :src="$page.props.auth.user.profile_photo_url"
                                                :alt="$page.props.auth.user.name">
                                        </button>

                                        <span v-else-if="$page.props.auth.user" class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50">
                                                <div class="flex items-center">
                                                    <!-- Perbaikan: Tambahkan text-right di sini -->
                                                    <div class="flex-col px-5 text-right">
                                                        <div class="text-base font-medium text-gray-800">
                                                            {{ $page.props.auth.user.name }}
                                                        </div>
                                                        <div class="text-sm font-light text-gray-400">
                                                            {{ $page.props.auth.user.email }}
                                                        </div>
                                                    </div>
                                                    <img v-if="$page.props.auth.user.profile_photo_url"
                                                        class="object-cover rounded-full size-10 me-3"
                                                        :src="$page.props.auth.user.profile_photo_url"
                                                        :alt="$page.props.auth.user.name">
                                                </div>
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="#3198E8" class="size-9">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                                </svg>


                                            </button>
                                        </span>
                                        <Link v-else :href="route('login')"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50">
                                        Login
                                        </Link>
                                    </template>

                                    <template #content>
                                        <div v-if="$page.props.auth.user" class="block px-4 py-2 text-xs text-gray-400">
                                            Manage Account
                                        </div>

                                        <DropdownLink v-if="$page.props.auth.user" :href="route('profile.show')">
                                            Profile
                                        </DropdownLink>

                                        <DropdownLink
                                            v-if="$page.props.jetstream.hasApiFeatures && $page.props.auth.user"
                                            :href="route('api-tokens.index')">
                                            API Tokens
                                        </DropdownLink>

                                        <div v-if="$page.props.auth.user" class="border-t border-gray-200" />

                                        <form v-if="$page.props.auth.user" @submit.prevent="logout">
                                            <DropdownLink as="button">
                                                Log Out
                                            </DropdownLink>
                                        </form>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Tombol toggle menu mobile -->
                        <div class="flex items-center -me-2 sm:hidden">
                            <button
                                class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500"
                                @click="showingNavigationDropdown = !showingNavigationDropdown">
                                <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{ 'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path
                                        :class="{ 'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu (Mobile) -->
                <div :class="{ 'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown }"
                    class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('available-donation')"
                            :active="route().current('available-donation')">
                            Donasi Tersedia
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('news')" :active="route().current('news')">
                            Berita
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('volunteer')" :active="route().current('volunteer')">
                            Volunteer
                        </ResponsiveNavLink>
                    </div>
                    <div class="px-4 py-2">
                        <form @submit.prevent="performSearch">
                            <input type="text" v-model="searchQuery" placeholder="Cari..."
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <button type="submit"
                                class="inline-flex items-center justify-center w-full px-4 py-2 mt-2 text-sm font-medium text-white bg-[#3198E8] border border-transparent rounded-md hover:bg-[#56c7ff] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3198E8]">
                                Cari
                            </button>
                        </form>
                    </div>
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div v-if="$page.props.auth.user" class="flex items-center px-4">
                            <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 me-3">
                                <img class="object-cover rounded-full size-10"
                                    :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                            </div>

                            <div class="flex items-center">
                                <img v-if="!$page.props.jetstream.managesProfilePhotos && $page.props.auth.user.profile_photo_url"
                                    class="object-cover rounded-full size-10 me-3"
                                    :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                                <div>
                                    <div class="text-base font-medium text-gray-800">
                                        {{ $page.props.auth.user.name }}
                                    </div>
                                    <div class="text-sm font-medium text-gray-500">
                                        {{ $page.props.auth.user.email }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="px-4">
                            <Link :href="route('login')"
                                class="block w-full px-4 py-2 text-base font-medium text-left text-gray-600 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-800 focus:outline-none focus:bg-gray-100 focus:text-gray-800">
                            Login
                            </Link>
                        </div>


                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink v-if="$page.props.auth.user" :href="route('profile.show')"
                                :active="route().current('profile.show')">
                                Profile
                            </ResponsiveNavLink>

                            <ResponsiveNavLink v-if="$page.props.jetstream.hasApiFeatures && $page.props.auth.user"
                                :href="route('api-tokens.index')" :active="route().current('api-tokens.index')">
                                API Tokens
                            </ResponsiveNavLink>

                            <div v-if="$page.props.auth.user" class="border-t border-gray-200" />

                            <form v-if="$page.props.auth.user" method="POST" @submit.prevent="logout">
                                <ResponsiveNavLink as="button">
                                    Log Out
                                </ResponsiveNavLink>
                            </form>

                            <template v-if="$page.props.jetstream.hasTeamFeatures && $page.props.auth.user">
                                <div class="border-t border-gray-200" />

                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    Manage Team
                                </div>

                                <ResponsiveNavLink :href="route('teams.show', $page.props.auth.user.current_team)"
                                    :active="route().current('teams.show')">
                                    Team Settings
                                </ResponsiveNavLink>

                                <ResponsiveNavLink v-if="$page.props.jetstream.canCreateTeams"
                                    :href="route('teams.create')" :active="route().current('teams.create')">
                                    Create New Team
                                </ResponsiveNavLink>

                                <template v-if="$page.props.auth.user.all_teams.length > 1">
                                    <div class="border-t border-gray-200" />

                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        Switch Teams
                                    </div>

                                    <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                        <form @submit.prevent="switchToTeam(team)">
                                            <ResponsiveNavLink as="button">
                                                <div class="flex items-center">
                                                    <svg v-if="team.id == $page.props.auth.user.current_team_id"
                                                        class="text-green-400 me-2 size-5"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <div>{{ team.name }}</div>
                                                </div>
                                            </ResponsiveNavLink>
                                        </form>
                                    </template>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
            </nav>

            <header v-if="$slots.header" class="bg-white shadow">
                <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main>
                <slot />
            </main>
            <footer class="py-8 text-white bg-gray-800">
                <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                        <div>
                            <h3 class="mb-4 text-lg font-semibold">UbahDunia.com</h3>
                            <p class="text-sm text-gray-400">Platform ini bertujuan untuk memfasilitasi donasi dan
                                kegiatan
                                sukarelawan, membantu menghubungkan orang-orang yang membutuhkan dengan mereka yang
                                ingin berbagi.
                            </p>
                        </div>
                        <div>
                            <h3 class="mb-4 text-lg font-semibold">Tautan Cepat</h3>
                            <ul class="space-y-2">
                                <li>
                                    <Link :href="route('dashboard')" class="text-sm text-gray-400 hover:text-white">
                                    Dashboard</Link>
                                </li>
                                <li>
                                    <Link :href="route('available-donation')"
                                        class="text-sm text-gray-400 hover:text-white">
                                    Donasi Tersedia
                                    </Link>
                                </li>
                                <li>
                                    <Link :href="route('news')" class="text-sm text-gray-400 hover:text-white">
                                    Berita Terbaru
                                    </Link>
                                </li>
                                <li>
                                    <Link :href="route('volunteer')" class="text-sm text-gray-400 hover:text-white">
                                    Bergabung
                                    Sebagai Volunteer</Link>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="mb-4 text-lg font-semibold">Hubungi Kami</h3>
                            <p class="text-sm text-gray-400">
                                Email: info@donasivolunteer.com<br>
                                Telepon: (123) 456-7890<br>
                                Alamat: Jl. Contoh No. 123, Malang, Indonesia
                            </p>
                        </div>
                    </div>
                    <div class="pt-8 mt-8 text-sm text-center text-gray-500 border-t border-gray-700">
                        &copy; 2025 Donasi Volunteer. All rights reserved.
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>
