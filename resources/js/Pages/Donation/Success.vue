<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    donation: Object, 
});

const formatCurrency = (amount) => {
    if (amount === null || typeof amount === 'undefined') return 'Rp 0';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
};
</script>

<template>
    <Head title="Donasi Berhasil!" />

    <AppLayout>
        <div class="py-12">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 overflow-hidden text-center bg-white shadow-xl sm:rounded-lg lg:p-8">

                    <div v-if="flash?.success" class="relative px-4 py-3 mb-6 text-green-700 bg-green-100 border border-green-400 rounded" role="alert">
                        <strong class="font-bold">Berhasil!</strong>
                        <span class="block sm:inline">{{ flash.success }}</span>
                    </div>

                    <div class="flex justify-center mb-6">
                        <svg class="w-24 h-24 text-ubahdunia-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>

                    <h2 class="mb-4 text-3xl font-bold text-gray-800">Donasi Anda Berhasil Dicatat!</h2>
                    <p class="mb-8 text-gray-600">Terima kasih atas kepedulian Anda. Donasi Anda telah dicatat dengan sukses.</p>

                    <div class="py-6 mb-8 text-left border-t border-b border-gray-200">
                        <h3 class="mb-4 text-xl font-semibold text-gray-700">Detail Donasi</h3>
                        <div class="grid grid-cols-1 gap-3 text-gray-700">
                            <p><strong>Nominal Donasi:</strong> {{ formatCurrency(donation.amount) }}</p>
                            <p><strong>Status:</strong> <span class="font-semibold capitalize">{{ donation.status }}</span></p>
                            <p v-if="donation.campaign"><strong>Untuk Kampanye:</strong> {{ donation.campaign.title }}</p>
                            <p><strong>Tanggal Donasi:</strong> {{ new Date(donation.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}</p>
                        </div>
                    </div>

                    <div class="flex flex-col justify-center gap-4 sm:flex-row">
                        <Link :href="route('home')"
                            class="px-6 py-3 bg-ubahdunia-blue text-white rounded-lg font-semibold bg-[#3198E8] hover:bg-[#2a7bbf] transition duration-200 ease-in-out">
                            Kembali ke Beranda
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
