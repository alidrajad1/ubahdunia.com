<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

const news = ref([]);
const isLoading = ref(true);
const error = ref(null);

const props = defineProps({
    hideViewAllLink: {
        type: Boolean,
        default: false,
    },
});

const fetchCampaigns = async () => {
    isLoading.value = true;
    error.value = null;
    try {
        const response = await axios.get('/api/news');
        news.value = response.data;
        console.log('Data berita berhasil diambil:', news.value);
    } catch (err) {
        console.error('Gagal mengambil berita:', err);
        error.value = 'Gagal memuat berita. Silakan coba lagi nanti.';
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
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
};
</script>

<template>
    <div class="p-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">

        <div class="flex items-center justify-between mb-6">
            <h3 class="relative inline-block text-2xl font-bold gradient-underline-title">
                Berita terbaru
            </h3>

            <Link v-if="!props.hideViewAllLink" :href="route('available-donation')" class="text-lg font-semibold text-gray-700 hover:text-gray-900 gradient-underline-link">
                Lihat Semua berita &rarr;
            </Link>
        </div>

        <div v-if="isLoading" class="py-8 text-center text-gray-600">
            Memuat berita...
        </div>
        <div v-else-if="error" class="py-8 text-center text-red-600">
            {{ error }}
        </div>
        <div v-else-if="news.length > 0" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <Link v-for="campaign in news" :key="campaign.id"
                :href="route('news-detail', campaign.slug)"
                class="flex flex-col overflow-hidden border border-gray-200 rounded-lg shadow-md
                       hover:shadow-lg hover:border-[#3198E8] transition duration-200 ease-in-out cursor-pointer">
                <img :src="'/storage/' + campaign.image_url" :alt="campaign.title"
                    class="object-cover w-full h-48">
                <div class="flex flex-col flex-grow p-4">
                    <h4 class="mb-2 text-lg font-bold text-gray-900">{{ campaign.title }}</h4>
                    <p class="mb-3 text-sm text-gray-600 line-clamp-3" v-html="campaign.description"></p>

                </div>
            </Link>
        </div>
        <div v-else class="py-8 text-center text-gray-600">
            Tidak ada berita yang tersedia saat ini.
        </div>
    </div>
</template>

<style scoped>

.gradient-underline-title {
    position: relative;
    padding-bottom: 5px;
}

.gradient-underline-title::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 3px;
    background-image: linear-gradient(to right, #90CFF1 10%, #D38107 10%);
    border-radius: 9999px;
}


.gradient-underline-link {
    position: relative;
    padding-bottom: 3px;
    text-decoration: none;
}

.gradient-underline-link::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 2px;
    background-image: linear-gradient(to right, #90CFF1 10%, #D38107 10%);
    border-radius: 9999px;
    transform: scaleX(0);
    transform-origin: bottom left;
    transition: transform 0.3s ease-out;
}

.gradient-underline-link:hover::after {
    transform: scaleX(1);
}
</style>
