<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import FormSection from '@/Components/FormSection.vue'; // Sesuaikan path ini
import { reactive, ref } from 'vue';

// Data formulir
const form = reactive({
    name: '',
    email: '',
    phone: '',
    address: '',
    skills: '',
    availability: [], // Array untuk checkbox
    motivation: '',
});

// State untuk error dan loading
const errors = reactive({});
const isProcessing = ref(false);

const registerVolunteer = async () => {
    isProcessing.value = true;
    // Reset error sebelumnya
    for (const key in errors) {
        delete errors[key];
    }

    // Simulasi pengiriman data ke server
    try {
        await new Promise(resolve => setTimeout(resolve, 1500)); // Simulasi penundaan API

        // --- Simulasi Validasi ---
        if (!form.name.trim()) {
            errors.name = 'Nama lengkap wajib diisi.';
        }
        if (!form.email.trim() || !form.email.includes('@')) {
            errors.email = 'Email tidak valid.';
        }
        if (!form.phone.trim()) {
            errors.phone = 'Nomor telepon wajib diisi.';
        }
        if (!form.address.trim()) {
            errors.address = 'Alamat wajib diisi.';
        }
        if (!form.skills.trim()) {
            errors.skills = 'Setidaknya sebutkan satu keahlian atau minat.';
        }
        if (form.availability.length === 0) {
            errors.availability = 'Pilih setidaknya satu ketersediaan waktu.';
        }
        if (!form.motivation.trim()) {
            errors.motivation = 'Berikan sedikit motivasi Anda untuk bergabung.';
        }
        // --- Akhir Simulasi Validasi ---

        if (Object.keys(errors).length === 0) {
            console.log('Data pendaftaran sukarelawan:', form);
            alert('Pendaftaran sukarelawan berhasil! Terima kasih atas minat Anda.');
            // Anda bisa membersihkan formulir di sini jika perlu
            // form.name = ''; form.email = ''; ...
        } else {
            console.log('Ada error validasi:', errors);
            alert('Gagal mendaftar. Periksa kembali input Anda.');
        }

    } catch (error) {
        console.error('Terjadi kesalahan saat pendaftaran:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
    } finally {
        isProcessing.value = false;
    }
};
</script>

<template>
  <AppLayout title="Volunteer Registration">

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="p-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">
          <FormSection @submitted="registerVolunteer">
            <template #title>
              Daftar Sebagai Sukarelawan
            </template>

            <template #description>
              Isi formulir di bawah ini untuk menjadi bagian dari tim sukarelawan kami. Mari bersama-sama membuat dampak positif!
            </template>

            <template #form>
              <div class="col-span-6 sm:col-span-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input
                  type="text"
                  id="name"
                  v-model="form.name"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#3198E8] focus:border-[#3198E8] sm:text-sm"
                  autocomplete="name"
                />
                <p v-if="errors.name" class="mt-2 text-sm text-red-600">{{ errors.name }}</p>
              </div>

              <div class="col-span-6 sm:col-span-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                <input
                  type="email"
                  id="email"
                  v-model="form.email"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#3198E8] focus:border-[#3198E8] sm:text-sm"
                />
                <p v-if="errors.email" class="mt-2 text-sm text-red-600">{{ errors.email }}</p>
              </div>

              <div class="col-span-6 sm:col-span-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                <input
                  type="tel"
                  id="phone"
                  v-model="form.phone"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#3198E8] focus:border-[#3198E8] sm:text-sm"
                />
                <p v-if="errors.phone" class="mt-2 text-sm text-red-600">{{ errors.phone }}</p>
              </div>

              <div class="col-span-6">
                <label for="address" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                <textarea
                  id="address"
                  v-model="form.address"
                  rows="3"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#3198E8] focus:border-[#3198E8] sm:text-sm"
                ></textarea>
                <p v-if="errors.address" class="mt-2 text-sm text-red-600">{{ errors.address }}</p>
              </div>

              <div class="col-span-6">
                <label for="skills" class="block text-sm font-medium text-gray-700">Keahlian atau Minat (contoh: desain grafis, menulis, logistik, komunikasi)</label>
                <textarea
                  id="skills"
                  v-model="form.skills"
                  rows="3"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#3198E8] focus:border-[#3198E8] sm:text-sm"
                ></textarea>
                <p v-if="errors.skills" class="mt-2 text-sm text-red-600">{{ errors.skills }}</p>
              </div>

              <div class="col-span-6">
                <label class="block mb-2 text-sm font-medium text-gray-700">Ketersediaan Waktu</label>
                <div class="mt-1 space-y-2">
                  <div class="flex items-center">
                    <input id="availability-weekdays" type="checkbox" value="Weekdays" v-model="form.availability" class="focus:ring-[#3198E8] h-4 w-4 text-[#3198E8] border-gray-300 rounded">
                    <label for="availability-weekdays" class="block ml-2 text-sm text-gray-900">Hari Kerja (Senin-Jumat)</label>
                  </div>
                  <div class="flex items-center">
                    <input id="availability-weekends" type="checkbox" value="Weekends" v-model="form.availability" class="focus:ring-[#3198E8] h-4 w-4 text-[#3198E8] border-gray-300 rounded">
                    <label for="availability-weekends" class="block ml-2 text-sm text-gray-900">Akhir Pekan (Sabtu-Minggu)</label>
                  </div>
                  <div class="flex items-center">
                    <input id="availability-evenings" type="checkbox" value="Evenings" v-model="form.availability" class="focus:ring-[#3198E8] h-4 w-4 text-[#3198E8] border-gray-300 rounded">
                    <label for="availability-evenings" class="block ml-2 text-sm text-gray-900">Malam Hari</label>
                  </div>
                </div>
                <p v-if="errors.availability" class="mt-2 text-sm text-red-600">{{ errors.availability }}</p>
              </div>

              <div class="col-span-6">
                <label for="motivation" class="block text-sm font-medium text-gray-700">Mengapa Anda tertarik menjadi sukarelawan?</label>
                <textarea
                  id="motivation"
                  v-model="form.motivation"
                  rows="5"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#3198E8] focus:border-[#3198E8] sm:text-sm"
                ></textarea>
                <p v-if="errors.motivation" class="mt-2 text-sm text-red-600">{{ errors.motivation }}</p>
              </div>
            </template>

            <template #actions>
              <button
                type="submit"
                :disabled="isProcessing"
                class="inline-flex items-center px-4 py-2 bg-[#3198E8] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-500 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition"
              >
                <span v-if="isProcessing">Mengirim...</span>
                <span v-else>Daftar Sekarang</span>
              </button>
            </template>
          </FormSection>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

