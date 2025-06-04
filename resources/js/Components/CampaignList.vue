<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

const campaigns = ref([]);
const isLoading = ref(true);
const error = ref(null);

// BARU: Tambahkan prop hideViewAllLink
const props = defineProps({
    hideViewAllLink: {
        type: Boolean,
        default: false, // Secara default, link akan terlihat
    },
});

const fetchCampaigns = async () => {
    isLoading.value = true;
    error.value = null;
    try {
        const response = await axios.get('/api/campaigns');
        campaigns.value = response.data;
        console.log('Data kampanye berhasil diambil:', campaigns.value);
    } catch (err) {
        console.error('Gagal mengambil kampanye:', err);
        error.value = 'Gagal memuat kampanye. Silakan coba lagi nanti.';
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchCampaigns();
});

const getCollectedPercentage = (campaign) => {
    if (campaign.target_amount <= 0) {
        return 0;
    }
    return Math.min(100, (campaign.collected_amount / campaign.target_amount) * 100).toFixed(2);
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0, // Tidak menampilkan desimal untuk rupiah
        maximumFractionDigits: 0,
    }).format(amount);
};
</script>

<template>
    <div class="p-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">
        <!-- Pindahkan link "Lihat Semua" ke atas, di sebelah judul -->
        <div class="flex items-center justify-between mb-6">
            <h3 class="relative inline-block text-2xl font-bold gradient-underline-title">
                Kampanye Donasi Aktif
            </h3>
            <!-- BARU: Gunakan v-if untuk menyembunyikan link berdasarkan prop -->
            <Link v-if="!props.hideViewAllLink" :href="route('available-donation')" class="text-lg font-semibold text-gray-700 hover:text-gray-900 gradient-underline-link">
                Lihat Semua Kampanye &rarr;
            </Link>
        </div>

        <div v-if="isLoading" class="py-8 text-center text-gray-600">
            Memuat kampanye...
        </div>
        <div v-else-if="error" class="py-8 text-center text-red-600">
            {{ error }}
        </div>
        <div v-else-if="campaigns.length > 0" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <Link v-for="campaign in campaigns" :key="campaign.id"
                :href="route('campaigns-detail', campaign.slug)"
                class="flex flex-col overflow-hidden border border-gray-200 rounded-lg shadow-md
                       hover:shadow-lg hover:border-[#3198E8] transition duration-200 ease-in-out cursor-pointer">
                <img :src="'/storage/' + campaign.image_url" :alt="campaign.title"
                    class="object-cover w-full h-48">
                <div class="flex flex-col flex-grow p-4">
                    <h4 class="mb-2 text-lg font-bold text-gray-900">{{ campaign.title }}</h4>
                    <p class="mb-3 text-sm text-gray-600 line-clamp-3" v-html="campaign.description"></p>

                    <div class="mt-auto">
                        <div class="mb-1 text-sm text-gray-700">
                            Terkumpul: <span class="font-semibold">{{ formatCurrency(campaign.collected_amount) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                            <div class="bg-[#3198E8] h-2.5 rounded-full"
                                :style="{ width: getCollectedPercentage(campaign) + '%' }"></div>
                        </div>
                        <div class="mb-3 text-xs text-right text-gray-500">
                            {{ getCollectedPercentage(campaign) }}% dari {{ formatCurrency(campaign.target_amount) }}
                        </div>
                    </div>
                </div>
            </Link>
        </div>
        <div v-else class="py-8 text-center text-gray-600">
            Tidak ada kampanye yang tersedia saat ini.
        </div>
    </div>
</template>

<style scoped>
/* CSS untuk underline gradient pada judul */
.gradient-underline-title {
    position: relative;
    padding-bottom: 5px; /* Jarak antara teks dan underline */
}

.gradient-underline-title::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 3px; /* Ketebalan underline */
    /* Rasio 1:9 untuk #90CFF1 dan #D38107 */
    background-image: linear-gradient(to right, #90CFF1 10%, #D38107 10%);
    border-radius: 9999px; /* Membuat ujung underline membulat */
}

/* CSS untuk underline gradient pada link "Lihat Semua" */
.gradient-underline-link {
    position: relative;
    padding-bottom: 3px; /* Jarak antara teks dan underline */
    text-decoration: none; /* Hapus underline default */
}

.gradient-underline-link::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 2px; /* Ketebalan underline */
    /* Rasio 1:9 untuk #90CFF1 dan #D38107 */
    background-image: linear-gradient(to right, #90CFF1 10%, #D38107 10%);
    border-radius: 9999px;
    transform: scaleX(0); /* Awalnya tidak terlihat */
    transform-origin: bottom left;
    transition: transform 0.3s ease-out; /* Animasi saat hover */
}

.gradient-underline-link:hover::after {
    transform: scaleX(1); /* Muncul saat hover */
}
</style>
