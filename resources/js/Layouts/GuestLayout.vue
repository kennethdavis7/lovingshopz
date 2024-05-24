<script setup>
import Navbar from "./GuestLayout/GuestNavbar.vue";
import Footer from "./GuestLayout/GuestFooter.vue";
import { usePage } from "@inertiajs/vue3";

defineProps({
    footerSpaceY: {
        type: Function,
        required: false,
    },
});
</script>

<template>
    <Navbar />
    <slot />
    <Footer :style="{ marginBottom: footerSpaceY?.($page.props.auth.user) }" />
</template>
