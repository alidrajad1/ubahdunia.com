<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { defineProps, computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    campaign: {
        type: Object,
        required: true,
    },
    donationHistory: { // Pastikan prop ini diterima
        type: Array,
        default: () => [],
    },
    totalDonationCount: { // Pastikan prop ini diterima
        type: Number,
        default: 0,
    },
    timelineEvents: { // Pastikan prop ini diterima
        type: Array,
        default: () => [],
    },
});

const showFullDescription = ref(false);
const showFullTimeline = ref(false);

const getCollectedPercentage = computed(() => {
    if (props.campaign.target_amount <= 0) {
        return 0;
    }
    return Math.min(100, (props.campaign.collected_amount / props.campaign.target_amount) * 100).toFixed(2);
});

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};

// Log data untuk debugging
console.log('Donation detail:', props.campaign);
console.log('Donation History:', props.donationHistory); // Pastikan ini menampilkan data sebenarnya
console.log('Total Donation Count:', props.totalDonationCount);
console.log('Timeline Events:', props.timelineEvents);
</script>

<template>
    <AppLayout :title="campaign.title">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="p-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                        <!-- Kolom Kiri (Hero Image, Progress, Donasi Button) -->
                        <div class="lg:col-span-2">
                            <img :src="'/storage/' + campaign.image_url" :alt="campaign.title"
                                class="object-cover w-full mb-6 rounded-lg shadow-md h-96">

                            <h1 class="mb-2 text-4xl font-extrabold text-gray-900">#{{ campaign.title.toUpperCase() }}</h1>
                            <p class="mb-4 text-xl text-gray-700">
                                {{ campaign.description.replace(/<\/?p>/g, '').split('.')[0] }}.
                            </p>

                            <div class="mb-6">
                                <div class="mb-2 text-xl font-bold text-gray-900">
                                    Terkumpul: <span class="text-green-600">{{ formatCurrency(campaign.collected_amount) }}</span>
                                </div>
                                <div class="w-full h-4 mb-2 bg-gray-200 rounded-full">
                                    <div class="bg-[#3198E8] h-4 rounded-full"
                                        :style="{ width: getCollectedPercentage + '%' }"></div>
                                </div>
                                <div class="text-sm text-right text-gray-600">
                                    {{ getCollectedPercentage }}% dari {{ formatCurrency(campaign.target_amount) }}
                                </div>
                            </div>

                            <button class="w-full py-4 text-xl font-bold text-white transition duration-150 ease-in-out bg-[#3198E8] rounded-lg hover:bg-[#56c7ff]">
                                Donasi
                            </button>
                        </div>

                        <!-- Kolom Kanan (Tentang Program, Report/Timeline) -->
                        <div class="lg:col-span-1">
                            <!-- Tentang Program -->
                            <div class="p-6 mb-6 rounded-lg shadow-inner bg-gray-50">
                                <h2 class="mb-4 text-2xl font-bold text-gray-900">Tentang Program</h2>
                                <p class="leading-relaxed text-justify text-gray-700" v-html="campaign.description"></p>
                                <button @click="showFullDescription = !showFullDescription"
                                    class="mt-3 text-sm text-[#3198E8] hover:underline">
                                    {{ showFullDescription ? 'Lihat Lebih Sedikit' : 'Lihat Selengkapnya' }}
                                </button>
                            </div>

                            <!-- Report/Timeline Program -->
                            <div class="p-6 rounded-lg shadow-inner bg-gray-50">
                                <h2 class="mb-4 text-2xl font-bold text-gray-900">Report/Timeline Program</h2>
                                <div class="space-y-4">
                                    <div v-for="(event, index) in timelineEvents" :key="index" class="relative pl-6">
                                        <div class="absolute left-0 top-0 w-3 h-3 bg-[#3198E8] rounded-full"></div>
                                        <div class="absolute left-1.5 top-0 bottom-0 w-0.5 bg-gray-300"></div>
                                        <p class="text-xs text-gray-500">{{ event.date }}</p>
                                        <h5 class="font-semibold text-gray-800">{{ event.title }}</h5>
                                        <p class="text-sm text-gray-600">{{ event.description }}</p>
                                    </div>
                                </div>
                                <button @click="showFullTimeline = !showFullTimeline"
                                    class="mt-4 text-sm text-[#3198E8] hover:underline">
                                    Lihat Selengkapnya
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr class="my-12 border-gray-200">

                    <!-- Riwayat Donasi -->
                    <div class="mb-8">
                        <h2 class="mb-6 text-2xl font-bold text-gray-900">Riwayat Donasi</h2>
                        <!-- Menggunakan totalDonationCount dari prop -->
                        <p class="mb-6 text-lg text-gray-700">{{ totalDonationCount }} Donasi Terkumpul</p>

                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                            <!-- Menggunakan donationHistory dari prop -->
                            <div v-for="donation in donationHistory" :key="donation.id"
                                class="flex flex-col items-center p-4 text-center border border-gray-200 rounded-lg shadow-sm">
                                <img :src="donation.imageUrl" :alt="donation.donor" class="object-cover w-16 h-16 mb-2 rounded-full">
                                <p class="font-semibold text-gray-800">{{ donation.donor }}</p>
                                <p class="text-sm text-gray-600">Rp. {{ formatCurrency(donation.amount) }}</p>
                                <p class="text-xs text-gray-500" v-html="donation.message"></p>
                            </div>
                        </div>
                        <div v-if="donationHistory.length === 0" class="mt-4 text-center text-gray-600">
                            Belum ada donasi untuk kampanye ini.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Styling untuk line-clamp pada deskripsi */
.line-clamp-6 {
    display: -webkit-box;
    -webkit-line-clamp: 6;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
