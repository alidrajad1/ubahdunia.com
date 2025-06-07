<script setup>
import { ref, watch } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    campaign: Object,
    paymentMethods: Array,
    user: Object,
});

const form = useForm({
    amount: null,
    payment_method: null,
    hide_name: false,

});

const instantAmounts = [10000, 20000, 100000, 500000, 1000000];

const customAmountInput = ref('');

const selectedAmount = ref(null);

watch(selectedAmount, (newVal) => {
    form.amount = newVal;
});

watch(customAmountInput, (newVal) => {

    const parsedValue = parseInt(newVal.replace(/\D/g, ''), 10);
    selectedAmount.value = isNaN(parsedValue) ? null : parsedValue;
});

const selectInstantAmount = (amount) => {
    selectedAmount.value = amount;
    customAmountInput.value = formatCurrencyNoSymbol(amount);
};

const selectPaymentMethod = (methodId) => {
    form.payment_method = methodId;
};

const formatCurrency = (amount) => {
    if (amount === null || typeof amount === 'undefined') return 'Rp 0';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
};

const formatCurrencyNoSymbol = (amount) => {
    if (amount === null || typeof amount === 'undefined') return '';
    return new Intl.NumberFormat('id-ID', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
};

const handleSubmit = () => {
    if (!form.amount || form.amount < 10000) {
        alert('Silakan masukkan nominal donasi minimal Rp 10.000.');
        return;
    }
    if (!form.payment_method) {
        alert('Silakan pilih metode pembayaran.');
        return;
    }

    form.post(route('donation.process', props.campaign.slug), {
        onFinish: () => {
            if (form.errors.error) {
                alert(form.errors.error);
            }
        },
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="'Donasi ' + campaign.title" />

    <AppLayout>
        <div class="py-12">
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <div class="grid grid-cols-1 gap-8 p-6 md:grid-cols-2 lg:p-8">
                        <div>
                            <img :src="'/storage/' + campaign.image_url" :alt="campaign.title"
                                 class="object-cover w-full h-auto mb-4 rounded-lg">
                            <h2 class="mb-2 text-3xl font-bold text-gray-900">{{ campaign.title }}</h2>
                            <p class="mb-4 text-gray-700" v-html="campaign.description"></p>

                            <div class="mb-4">
                                <p class="text-lg text-gray-700">Terkumpul:</p>
                                <p class="text-2xl font-bold text-[#3198E8]">{{ formatCurrency(campaign.collected_amount) }}</p>
                                <div class="w-full h-4 mt-2 bg-gray-200 rounded-full">
                                    <div class="h-4 bg-[#3198E8] rounded-full"
                                        :style="{ width: (campaign.collected_amount / campaign.target_amount) * 100 + '%' }"></div>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    dari {{ formatCurrency(campaign.target_amount) }}
                                </p>
                            </div>
                        </div>

                        <div class="p-6 rounded-lg shadow-inner bg-gray-50">
                            <h3 class="pb-3 mb-5 text-2xl font-bold text-gray-800 border-b">Nominal Donasi</h3>

                            <div class="mb-4">
                                <label for="nominal_input" class="block mb-2 text-sm font-medium text-gray-700">
                                    Rp <span class="text-gray-500">Masukkan Nominal Sedekah</span>
                                </label>
                                <input type="text" id="nominal_input"
                                       v-model="customAmountInput"
                                       @focus="selectedAmount = null"
                                       @input="($event) => {
                                           const value = $event.target.value.replace(/\D/g, ''); // Hapus non-digit
                                           customAmountInput = new Intl.NumberFormat('id-ID').format(parseInt(value || '0', 10));
                                           form.amount = parseInt(value || '0', 10);
                                        }"
                                       class="block w-full p-3 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-[#3198E8] focus:border-blue-500"
                                       placeholder="Contoh: 50.000">
                                <p v-if="form.errors.amount" class="mt-1 text-xs text-red-500">{{ form.errors.amount }}</p>
                            </div>

                            <p class="mb-3 text-center text-gray-500">Atau Pilih Instan</p>

                            <div class="grid grid-cols-1 gap-3 mb-6">
                                <button v-for="amount in instantAmounts" :key="amount"
                                        @click="selectInstantAmount(amount)"
                                        :class="{
                                            'bg-blue-100 border-[#3198E8] text-blue-500': selectedAmount === amount,
                                            'bg-white border-gray-300 text-gray-800 hover:bg-gray-50': selectedAmount !== amount
                                        }"
                                        class="flex items-center justify-between p-3 transition duration-200 ease-in-out border rounded-lg shadow-sm">
                                    <span class="font-semibold">{{ formatCurrency(amount) }}</span>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="flex items-center justify-end mb-6">
                                <span class="mr-3 text-gray-700">Sembunyikan nama sebagai "Hamba Allah"</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" v-model="form.hide_name" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-500 rounded-full peer dark:bg-gray-200 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-100 peer-checked:bg-[#3198E8]"></div>
                                </label>
                            </div>

                            <h3 class="pb-3 mb-4 text-xl font-bold text-gray-800 border-b">Metode Pembayaran</h3>
                            <p class="mb-3 text-sm text-gray-500">Pilih 1 dari {{ paymentMethods.length }}</p>

                            <div class="grid grid-cols-2 gap-4 mb-8 sm:grid-cols-3 lg:grid-cols-5">
                                <div v-for="method in paymentMethods" :key="method.id"
                                     @click="selectPaymentMethod(method.id)"
                                     :class="{
                                         'border-[#3198E8] ring-2 ring-[#3198E8]': form.payment_method === method.id,
                                         'border-gray-300 hover:border-blue-300': form.payment_method !== method.id
                                     }"
                                     class="flex flex-col items-center justify-center p-3 transition duration-200 ease-in-out border rounded-lg cursor-pointer">
                                    <img :src="method.logo" :alt="method.name" class="object-contain w-auto h-12 mb-1">
                                    <span class="text-xs font-medium text-gray-600">{{ method.name }}</span>
                                </div>
                                <p v-if="form.errors.payment_method" class="mt-1 text-xs text-red-500 col-span-full">{{ form.errors.payment_method }}</p>
                            </div>

                            <div class="flex justify-between mt-6">
                                <button @click="$inertia.visit(route('home'))"
                                        class="px-6 py-3 font-semibold text-white transition duration-200 bg-[#D38107] hover:bg-orange-700 rounded-lg">
                                    Kembali
                                </button>
                                <button @click="handleSubmit"
                                        :class="{'opacity-50 cursor-not-allowed': form.processing}"
                                        :disabled="form.processing"
                                        class="px-8 py-3 font-semibold text-white transition duration-200 rounded-lg bg-[#3198E8] hover:bg-blue-500">
                                    Lanjutkan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
