<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { defineProps } from 'vue';

// Definisikan props yang akan diterima dari controller
const props = defineProps({
    title: String,
    description: String,
    imageUrl: String,
});
</script>

<template>
    <AppLayout :title="props.title">
        <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h1 class="mb-6 text-4xl font-extrabold text-center text-gray-900 dark:text-gray-800">{{ props.title }}</h1>

            <div v-if="props.imageUrl" class="flex justify-center mb-8">
                <img :src="'/storage/' + props.imageUrl" :alt="props.title" class="object-cover h-auto max-w-full md:max-h-96">
            </div>

            <div class="prose prose-lg text-gray-800 dark:prose-invert max-w-none dark:text-gray-500">
                <p v-html="props.description"></p>
                </div>

            <div class="mt-10 text-center text-gray-600 dark:text-gray-400">
                <p>Berita dipublikasikan pada: </p>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Anda bisa menambahkan gaya kustom di sini jika diperlukan */
img {
    display: block; /* Menghilangkan margin bawah default pada gambar */
    margin-left: auto;
    margin-right: auto;
}
</style>
