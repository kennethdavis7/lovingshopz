<script setup>
import ProductCard from "@/Components/ProductCard.vue";
const props = defineProps({
    products: Object,
    title: String,
});
</script>

<template>
    <div class="flex flex-col bg-white m-auto p-auto">
        <h4 class="flex font-bold text-4xl text-gray-800 mb-4">
            {{ props.title }}
        </h4>
        <div class="flex overflow-x-scroll hide-scroll-bar">
            <div class="flex gap-4 flex-nowrap">
                <ProductCard
                    class="min-w-[300px]"
                    v-for="product in props.products"
                    :key="product.id"
                    :data="product"
                />
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
