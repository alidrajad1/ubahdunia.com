<script setup>
import CampaignList from '@/Components/CampaignList.vue';
import NewsList from '@/Components/NewsList.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="News">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <NewsList :hide-view-all-link="true"/>
            </div>
        </div>
    </AppLayout>
</template>
