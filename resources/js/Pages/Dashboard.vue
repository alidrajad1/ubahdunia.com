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
import CategoryList from '@/Components/CategoryList.vue';
import CommentSection from '@/Components/CommentSection.vue';
import VolunteerSection from '@/Components/VolunteerSection.vue';
import NewsList from '@/Components/NewsList.vue';


// Terima prop 'carousels' yang dikirim dari controller
const props = defineProps({
    carousels: {
        type: Array,
        default: () => [],
    },
});

const modules = [Navigation, Pagination, Autoplay];

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

    slidesPerView: 1,
    spaceBetween: 0,

};
</script>

<template>
    <AppLayout title="Dashboard">

        <div class="py-10">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div>
                    <div v-if="props.carousels.length > 0" class="rounded-md">
                        <swiper :slides-per-view="swiperOptions.slidesPerView"
                            :space-between="swiperOptions.spaceBetween" :modules="modules" :loop="swiperOptions.loop"
                            :autoplay="swiperOptions.autoplay" :pagination="swiperOptions.pagination"
                            :navigation="swiperOptions.navigation" :breakpoints="swiperOptions.breakpoints"
                            class="mySwiper">
                            <swiper-slide v-for="carousel in props.carousels" :key="carousel.id">
                                <div class="overflow-hidden rounded-md">
                                    <a :href="carousel.link_url || '#'" target="_blank" rel="noopener noreferrer">
                                        <img :src="'/storage/' + carousel.image_url" :alt="carousel.caption"
                                            class="object-cover w-full rounded-md h-96">
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
                    <hr class="my-4">
                    <NewsList />
                    <CategoryList />
                    <CommentSection />
                    <VolunteerSection />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
.swiper-button-prev,
.swiper-button-next {
    color: #4F46E5;
}

.swiper-pagination-bullet-active {
    background: #D38107;
}

.mySwiper {
    width: 100%;
    height: auto;
    padding-bottom: 30px;

}


.mySwiper .swiper-slide img {
    border-radius: 0.375rem !important;
}


.mySwiper .swiper-slide {
    overflow: hidden; /* Penting! */
    border-radius: 0.375rem !important;
}

.swiper-button-prev,
.swiper-button-next {

    transform: translateY(-50%);
    width: 44px;

    height: 44px;
    background-color: rgba(255, 255, 255, 0.7);

    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.swiper-button-prev:after,
.swiper-button-next:after {
    font-size: 1.5rem;

}

.swiper-pagination {
    bottom: 10px;

}
</style>
