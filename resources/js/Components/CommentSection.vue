<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const comments = ref([]);
const isLoading = ref(true);
const error = ref(null);

const showFullDescription = ref({});


const getInitialAvatar = (name) => {
    if (!name) {
        return { initials: '?', bgColor: '#ccc' };
    }
    const parts = name.split(' ');
    let initials = '';
    if (parts.length > 1) {
        initials = (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
    } else {
        initials = parts[0][0].toUpperCase();
    }


    const colors = [
        '#FFD700',
        '#87CEEB',
        '#98FB98',
        '#FFB6C1',
        '#DA70D6',
        '#FFA07A',
        '#ADD8E6',
        '#F0E68C',
        '#DDA0DD',
        '#FFDAB9',
    ];
    let hash = 0;
    for (let i = 0; i < name.length; i++) {
        hash = name.charCodeAt(i) + ((hash << 5) - hash);
    }
    const colorIndex = Math.abs(hash % colors.length);
    const bgColor = colors[colorIndex];

    return { initials, bgColor };
};

const fetchComments = async () => {
    isLoading.value = true;
    error.value = null;
    try {
        const response = await axios.get('/api/comments');
        comments.value = response.data;
        comments.value.forEach(comment => {
            showFullDescription.value[comment.id] = false;
        });
        console.log('Data komentar berhasil diambil:', comments.value);
    } catch (err) {
        console.error('Gagal mengambil komentar:', err);
        error.value = 'Gagal memuat komentar. Silakan coba lagi nanti.';
    } finally {
        isLoading.value = false;
    }
};

const toggleDescription = (commentId) => {
    showFullDescription.value[commentId] = !showFullDescription.value[commentId];
};

const getStars = (rating = 5) => {
    const fullStars = '⭐'.repeat(Math.floor(rating));
    const emptyStars = '☆'.repeat(5 - Math.floor(rating));
    return fullStars + emptyStars;
};

onMounted(() => {
    fetchComments();
});
</script>

<template>
    <div class="p-6 mt-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">
        <div class="flex items-center justify-between mb-6">
            <h3 class="relative inline-block text-2xl font-bold gradient-underline-title">
                Komentar
            </h3>
        </div>

        <div v-if="isLoading" class="py-8 text-center text-gray-600">
            Memuat komentar...
        </div>
        <div v-else-if="error" class="py-8 text-center text-red-600">
            {{ error }}
        </div>
        <div v-else-if="comments.length > 0" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <div v-for="comment in comments" :key="comment.id"
                class="flex flex-col p-5 border border-gray-200 rounded-lg shadow-sm">
                <div class="flex items-start mb-3">
                    <template v-if="comment.user?.profile_photo_url">
                        <img :src="comment.user.profile_photo_url"
                             :alt="comment.user.name || 'Pengguna Anonim'"
                             class="object-cover w-12 h-12 mr-4 rounded-full">
                    </template>
                    <template v-else>
                        <div class="flex items-center justify-center w-12 h-12 mr-4 text-lg font-bold text-white rounded-full"
                             :style="{ backgroundColor: getInitialAvatar(comment.user?.name).bgColor }">
                            {{ getInitialAvatar(comment.user?.name).initials }}
                        </div>
                    </template>

                    <div>
                        <p class="font-semibold text-gray-900">{{ comment.user?.name || 'Pengguna Anonim' }}</p>
                        <div class="text-lg text-yellow-500">
                            {{ getStars(comment.rating) }}
                        </div>
                    </div>
                </div>
                <p class="flex-grow text-sm text-gray-700"
                   :class="{ 'line-clamp-3': !showFullDescription[comment.id] }"
                   v-html="comment.content"></p>
                <button v-if="comment.content.length > 100" @click="toggleDescription(comment.id)"
                        class="mt-2 text-sm font-semibold text-left text-blue-600 hover:text-blue-800">
                    {{ showFullDescription[comment.id] ? 'Baca Lebih Sedikit...' : 'Baca Lebih Lanjut...' }}
                </button>
            </div>
        </div>
        <div v-else class="py-8 text-center text-gray-600">
            Belum ada komentar.
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
