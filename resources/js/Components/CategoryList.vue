<script setup>
import { ref } from 'vue';

 
const categories = ref([
    { id: 1, name: 'Anak Yatim', icon: '/storage/icon/category_anak_yatim.png', href: '/kategori/anak-yatim' },
    { id: 2, name: 'Wakaf', icon: '/storage/icon/category_wakaf.png', href: '/kategori/wakaf' },
    { id: 3, name: 'Kegiatan Sosial', icon: '/storage/icon/category_kegiatan_sosial.png', href: '/kategori/kegiatan-sosial' },
    { id: 4, name: 'Bencana Alam', icon: '/storage/icon/category_bencana_alam.png', href: '/kategori/bencana-alam' },
    { id: 5, name: 'Lingkungan', icon: '/storage/icon/category_lingkungan.png', href: '/kategori/lingkungan' },
    { id: 6, name: 'Lihat Semua', icon: '/storage/icon/category_lihat_semua.png', route : 'available-donation' },
]);
</script>

<template>
    <div class="p-6 mt-10 overflow-hidden bg-white shadow-xl sm:rounded-lg">
        <div class="">
            <h3 class="relative inline-block mb-6 text-2xl font-bold gradient-underline-title">
                Kategori Donasi
            </h3>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
                <a v-for="category in categories" :key="category.id"
                    :href="category.href"
                    class="flex flex-col items-center justify-center p-4 transition duration-200 ease-in-out bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-[#3198E8] cursor-pointer">
                    <img :src="category.icon" :alt="category.name" class="object-contain w-16 h-16 mb-2">
                    <span class="text-sm font-semibold text-center text-gray-800">{{ category.name }}</span>
                </a>
            </div>
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
</style>
