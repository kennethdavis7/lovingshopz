<script setup>
import Navbar from "./GuestLayout/GuestNavbar.vue";
import Footer from "./GuestLayout/GuestFooter.vue";
</script>

<template>
    <Navbar />
    <slot />
    <Footer />
</template>
