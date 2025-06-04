<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { defineProps } from 'vue';

// Impor Swiper dan SwiperSlide
import { Swiper, SwiperSlide } from 'swiper/vue';

// Impor modul Swiper yang Anda butuhkan (misalnya Navigation, Pagination, Autoplay)
import { Navigation, Pagination, Autoplay } from 'swiper'; // Sudah benar ini

// Impor Swiper styles
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import CampaignList from '@/Components/CampaignList.vue';


// Terima prop 'carousels' yang dikirim dari controller
const props = defineProps({
    carousels: {
        type: Array,
        default: () => [],
    },
});

// Definisikan modul Swiper yang akan digunakan
const modules = [Navigation, Pagination, Autoplay];

// Opsi Swiper
const swiperOptions = {
    loop: true,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    pagination: {
        clickable: true,
    },
    navigation: false,
    // ----------- PERUBAHAN UTAMA DI SINI -----------
    slidesPerView: 1, // Hanya satu slide yang terlihat penuh
    spaceBetween: 0,  // Tidak ada spasi antar slide
    // Hapus atau sesuaikan breakpoints jika tidak diperlukan 1 slide per view
    // Jika Anda ingin tetap responsif dan mungkin menunjukkan lebih dari 1 slide di desktop,
    // maka Anda bisa mendefinisikan breakpoints di sini.
    // Contoh:
    // breakpoints: {
    //     640: {
    //         slidesPerView: 1,
    //         spaceBetween: 0,
    //     },
    //     768: {
    //         slidesPerView: 1, // Tetap 1 untuk semua ukuran
    //         spaceBetween: 0,
    //     },
    //     1024: {
    //         slidesPerView: 1,
    //         spaceBetween: 0,
    //     },
    // },
    // ------------------------------------------------
};
</script>

<template>
    <AppLayout title="Dashboard">

        <div class="py-10">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div>
                    <div v-if="props.carousels.length > 0" class="rounded-md">
                        <swiper
                            :slides-per-view="swiperOptions.slidesPerView"
                            :space-between="swiperOptions.spaceBetween"
                            :modules="modules"
                            :loop="swiperOptions.loop"
                            :autoplay="swiperOptions.autoplay"
                            :pagination="swiperOptions.pagination"
                            :navigation="swiperOptions.navigation"
                            :breakpoints="swiperOptions.breakpoints"
                            class="mySwiper"
                        >
                            <swiper-slide v-for="carousel in props.carousels" :key="carousel.id">
                                <div class="overflow-hidden rounded-md">
                                    <a :href="carousel.link_url || '#'" target="_blank" rel="noopener noreferrer">
                                        <img :src="'/storage/' + carousel.image_url" :alt="carousel.caption" class="object-cover w-full rounded-md h-96">
                                    </a>
                                </div>
                            </swiper-slide>
                        </swiper>
                    </div>
                    <div v-else class="text-gray-600">
                        Tidak ada gambar carousel yang tersedia.
                    </div>

                    <hr class="my-4">
                    <CampaignList />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
/* CSS Kustom untuk Swiper (jika diperlukan) */
/* Pastikan ini tidak ter-scope jika ingin memengaruhi elemen Swiper global */
.swiper-button-prev,
.swiper-button-next {
    color: #4F46E5; /* Warna navigasi sesuai indigo-600 Tailwind */
}

.swiper-pagination-bullet-active {
    background: #D38107; /* Warna pagination aktif */
}

.mySwiper {
    width: 100%; /* Pastikan Swiper mengambil lebar penuh dari parentnya */
    height: auto; /* Biarkan tinggi menyesuaikan konten */
    /* Hapus padding-bottom dan padding-top jika tidak diperlukan atau jika navigasi/pagination diletakkan di luar area gambar */
    padding-bottom: 30px; /* Masih bisa berguna untuk memberi ruang pagination */
    /* padding-top: 30px; */ /* Mungkin tidak lagi diperlukan jika navigasi ada di dalam gambar */
}

/* Kustomisasi posisi navigasi dan pagination agar tidak menimpa gambar terlalu banyak */
/* Ini opsional, tergantung desain yang diinginkan */
.swiper-button-prev,
.swiper-button-next {
  top: 50%; /* Posisi vertikal di tengah */
  transform: translateY(-50%);
  width: 44px; /* Ukuran tombol navigasi */
  height: 44px;
  background-color: rgba(255, 255, 255, 0.7); /* Background transparan agar tombol terlihat */
  border-radius: 50%;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.swiper-button-prev:after,
.swiper-button-next:after {
    font-size: 1.5rem; /* Ukuran ikon panah */
}

.swiper-pagination {
    bottom: 10px; /* Posisi pagination di bawah */
}
</style>
