<!-- <script>
export default {
    layout: (h, page) => h(GuestLayout, { isAdmin:  }, () => page),
};
</script> -->

<script setup>
import Cart from "@/Components/Cart.vue";
import CartItem from "@/Components/CartItem.vue";
import Jumbotron from "./Partials/Jumbotron.vue";
import ProductSection from "./Partials/ProductSection.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    newProducts: Object,
    popularProducts: Object,
});
</script>
<template>
    <Head title="Home" />

    <div class="w-full max-w-screen-xl mx-auto p-4">
        <Jumbotron />
        <ProductSection
            :products="props.popularProducts"
            title="Popular Products"
        />
        <div class="space my-14"></div>
        <ProductSection :products="props.newProducts" title="New Products" />
    </div>
    <Cart v-if="$page.props.auth.user?.role_id == 2" />
</template>
