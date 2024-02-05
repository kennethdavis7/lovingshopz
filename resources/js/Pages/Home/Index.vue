<script setup>
import Jumbotron from "./Partials/Jumbotron.vue";
import PopularProducts from "./Partials/PopularProducts.vue";
import NewProducts from "./Partials/NewProducts.vue";
</script>
<template>
    <Head title="Home" />

    <div class="w-full max-w-screen-xl mx-auto p-4">
        <Jumbotron />
        <PopularProducts />
        <div class="space my-14"></div>
        <NewProducts />
    </div>
</template>
