<script setup>
import { Link } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import BoxShadow from "@/Components/BoxShadow.vue";
import Logo from "@/Components/Logo.vue";
</script>
<template>
    <div class="flex flex-col items-center my-28 mx-auto">
        <Logo imageClass="w-80" />
        <BoxShadow class="flex flex-col items-center">
            <h1>Pesanan Anda telah masuk!</h1>
            <Link class="underline" :href="route('orders.index')"
                >Lacak pesanan Anda di sini</Link
            >
        </BoxShadow>
    </div>
</template>
