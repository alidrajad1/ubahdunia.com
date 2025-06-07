<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="p-6 mt-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">
        <div class="flex flex-col items-center gap-6 p-6 md:flex-row sm:rounded-lg lg:p-8">
            <div class="flex-shrink-0 w-full md:w-auto">
                <Link :href="route('volunteer')"
                      class="relative block w-full overflow-hidden transition duration-300 ease-in-out rounded-lg shadow-md cursor-pointer bg-[#D38107] hover:bg-orange-700 group">
                    <div class="flex items-center justify-between p-6 pr-4">
                        <div class="text-white">
                            <p class="mb-1 text-sm font-semibold opacity-90">Jadi Volunteer di UbahDunia.com</p>
                            <h4 class="text-2xl font-bold">Menu Volunteer</h4>
                        </div>
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 ml-4 transition-transform duration-300 ease-in-out bg-white rounded-full group-hover:translate-x-1">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </Link>
            </div>

            <div class="flex-grow text-base text-gray-700 lg:text-lg">
                <p>
                    Bergabunglah sebagai sukarelawan di UbahDunia.com dan jadilah bagian dari perubahan positif!
                    Kami mengundang Anda untuk berkontribusi dalam berbagai kegiatan sosial, membantu menyalurkan
                    bantuan, atau memberikan keahlian Anda untuk dampak yang lebih besar. Setiap kontribusi Anda
                    sangat berarti. Mari bersama-sama menciptakan dunia yang lebih baik!
                </p>
            </div>
        </div>
    </div>
</template>
