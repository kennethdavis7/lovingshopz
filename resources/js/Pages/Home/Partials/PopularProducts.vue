<script setup>
import Card from "@/Components/CardProduct.vue";
</script>

<template>
    <div class="flex flex-col bg-white m-auto p-auto">
        <h4 class="flex font-bold text-4xl text-gray-800 mb-4">
            Popular Products
        </h4>
        <div class="flex overflow-x-scroll hide-scroll-bar">
            <div class="flex flex-nowrap">
                <Card />
                <Card />
                <Card />
                <Card />
                <Card />
                <Card />
                <Card />
                <Card />
            </div>
        </div>
    </div>
</template>

<style scoped>
.hide-scroll-bar {
    -ms-overflow-style: none;
    scrollbar-width: auto;
}
.hide-scroll-bar::-webkit-scrollbar {
    display: none;
}
</style>
