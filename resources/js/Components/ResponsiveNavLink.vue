<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    active: Boolean,
    href: String,
    as: String,
});

const classes = computed(() => {
    return props.active
        ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[#3198E8] text-start text-base font-medium text-[#3198E8] bg-[#F6FCFF] focus:outline-none focus:text-[#3198E8] focus:bg-[#F6FCFF] focus:border-[#3198E8] transition duration-150 ease-in-out'
        : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-[#3198E8] hover:bg-[#F6FCFF] hover:border-[#3198E8] focus:outline-none focus:text-[#3198E8] focus:bg-[#F6FCFF] focus:border-[#3198E8] transition duration-150 ease-in-out';
});
</script>

<template>
    <div>
        <button v-if="as == 'button'" :class="classes" class="w-full text-start">
            <slot />
        </button>

        <a v-else-if="as == 'a'" :class="classes" class="w-full text-start" :href="href">
            <slot />
        </a>

        <Link v-else :href="href" :class="classes">
            <slot />
        </Link>
    </div>
</template>
