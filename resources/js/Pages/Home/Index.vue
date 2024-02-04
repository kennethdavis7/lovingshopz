<script setup>
import Navbar from "../../Layouts/Navbar.vue";
import Footer from "../../Layouts/Footer.vue";
import Jumbotron from "./Partials/Jumbotron.vue";
import PopularProducts from "./Partials/PopularProducts.vue";
import NewProducts from "./Partials/NewProducts.vue";
import { Link, Head } from "@inertiajs/vue3";
</script>
<template>
    <Head title="Home" />
    <div class="w-full max-w-screen-xl mx-auto p-4">
        <Navbar />
        <Jumbotron />
        <PopularProducts />
        <div class="space my-14"></div>
        <NewProducts />
        <Footer />
        <Link :href="route('home')" />
    </div>
</template>
