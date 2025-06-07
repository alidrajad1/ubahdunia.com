<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>

    <Head title="Register" />

    <div class="flex min-h-screen">
        <div class="flex items-center justify-center w-full p-6 bg-white lg:w-1/2">
            <div class="w-full max-w-md">





                <AuthenticationCard class="!bg-transparent !shadow-none !p-0">
                    <template #logo>
                        <AuthenticationCardLogo />
                    </template>
                    <h1 class="text-3xl font-bold text-center text-[#3198E8] mb-8">Register</h1>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <InputLabel for="name" value="Name" class="sr-only" />
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </span>
                                <TextInput id="name" v-model="form.name" type="text" class="block w-full pl-10 mt-1"
                                    required autofocus autocomplete="name" placeholder="Nama Lengkap" />
                            </div>
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="email" value="Email" class="sr-only" />
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 4v7a2 2 0 002 2h14a2 2 0 002-2v-7">
                                        </path>
                                    </svg>
                                </span>
                                <TextInput id="email" v-model="form.email" type="email" class="block w-full pl-10 mt-1"
                                    required autocomplete="username" placeholder="Email" />
                            </div>
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="password" value="Password" class="sr-only" />
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v3h8z">
                                        </path>
                                    </svg>
                                </span>
                                <TextInput id="password" v-model="form.password" type="password"
                                    class="block w-full pl-10 mt-1" required autocomplete="new-password"
                                    placeholder="Password" />
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer">
                                </span>
                            </div>
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="password_confirmation" value="Confirm Password" class="sr-only" />
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v3h8z">
                                        </path>
                                    </svg>
                                </span>
                                <TextInput id="password_confirmation" v-model="form.password_confirmation"
                                    type="password" class="block w-full pl-10 mt-1" required autocomplete="new-password"
                                    placeholder="Konfirmasi Password" />
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer">
                                </span>
                            </div>
                            <InputError class="mt-2" :message="form.errors.password_confirmation" />
                        </div>

                        <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                            <InputLabel for="terms">
                                <div class="flex items-center">
                                    <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />

                                    <div class="ms-2">
                                        Saya setuju dengan
                                        <a target="_blank" :href="route('terms.show')"
                                            class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3198E8]">Ketentuan
                                            Layanan</a>
                                        dan
                                        <a target="_blank" :href="route('policy.show')"
                                            class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3198E8]">Kebijakan
                                            Privasi</a>
                                    </div>
                                </div>
                                <InputError class="mt-2" :message="form.errors.terms" />
                            </InputLabel>
                        </div>

                        <PrimaryButton type="submit"
                            :class="{ 'opacity-25': form.processing, 'w-full !justify-center py-2.5 bg-[#3198E8] text-white rounded-md font-semibold hover:!bg-[#2a7bbf] focus:!ring-[#3198E8]': true }"
                            :disabled="form.processing">
                            Daftar
                        </PrimaryButton>
                    </form>

                    <div class="my-6 text-center text-gray-500">
                        Atau daftar lebih cepat dengan
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <a :href="route('oauth.redirect', 'google')"
                            class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 48 48">
                                <path fill="#ffc107"
                                    d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C12.955 4 4 12.955 4 24s8.955 20 20 20s20-8.955 20-20c0-1.341-.138-2.65-.389-3.917" />
                                <path fill="#ff3d00"
                                    d="m6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C16.318 4 9.656 8.337 6.306 14.691" />
                                <path fill="#4caf50"
                                    d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.9 11.9 0 0 1 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44" />
                                <path fill="#1976d2"
                                    d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002l6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917" />
                            </svg>
                            Masuk dengan Google
                        </a>
                        <a :href="route('oauth.redirect', 'facebook')"
                            class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 256 256">
                                <path fill="#1877f2"
                                    d="M256 128C256 57.308 198.692 0 128 0S0 57.308 0 128c0 63.888 46.808 116.843 108 126.445V165H75.5v-37H108V99.8c0-32.08 19.11-49.8 48.348-49.8C170.352 50 185 52.5 185 52.5V84h-16.14C152.959 84 148 93.867 148 103.99V128h35.5l-5.675 37H148v89.445c61.192-9.602 108-62.556 108-126.445" />
                                <path fill="#fff"
                                    d="m177.825 165l5.675-37H148v-24.01C148 93.866 152.959 84 168.86 84H185V52.5S170.352 50 156.347 50C127.11 50 108 67.72 108 99.8V128H75.5v37H108v89.445A129 129 0 0 0 128 256a129 129 0 0 0 20-1.555V165z" />
                            </svg>
                            Masuk dengan Facebook
                        </a>
                        <a :href="route('oauth.redirect', 'twitter')"
                            class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 128 128">
                                <path
                                    d="M75.916 54.2L122.542 0h-11.05L71.008 47.06L38.672 0H1.376l48.898 71.164L1.376 128h11.05L55.18 78.303L89.328 128h37.296L75.913 54.2ZM60.782 71.79l-4.955-7.086l-39.42-56.386h16.972L65.19 53.824l4.954 7.086l41.353 59.15h-16.97L60.782 71.793Z" />
                            </svg>
                            Masuk dengan X
                        </a>
                    </div>

                    <div class="mt-8 text-sm text-center text-gray-600">
                        Sudah punya akun?
                        <Link :href="route('login')"
                            class="text-[#3198E8] hover:text-[#2a7bbf] font-semibold underline">Masuk</Link>
                    </div>
                </AuthenticationCard>
            </div>
        </div>

        <div class="hidden w-1/2 bg-center bg-cover lg:block"
            style="background-image: url('/storage/images/background.png');">
        </div>
    </div>
</template>

 
