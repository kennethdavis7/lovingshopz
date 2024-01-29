<script setup>
import Navbar from "../../Layouts/Navbar.vue";
import Footer from "../../Layouts/Footer.vue";
import Jumbotron from "./Components/Jumbotron.vue";
import PopularProducts from "./Partials/PopularProducts.vue";
import NewProducts from "./Partials/NewProducts.vue";
import { Link } from "@inertiajs/vue3";
</script>
<template>
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
