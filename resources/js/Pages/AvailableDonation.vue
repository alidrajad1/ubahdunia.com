<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CampaignList from '@/Components/CampaignList.vue';
</script>

<template>
    <AppLayout title="Available Donation">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <CampaignList :hide-view-all-link="true"/>
            </div>
        </div>
    </AppLayout>
</template>
