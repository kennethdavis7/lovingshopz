<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    imageClass: String,
});
</script>
<template>
    <Link :href="route('home')" class="flex items-center ms-2 cursor-pointer">
        <img
            src="/static_images/logo.png"
            :class="props.imageClass"
            alt="Lovingshopz Logo"
        />
    </Link>
</template>
