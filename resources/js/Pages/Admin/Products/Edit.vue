<script setup>
import Card from "@/Components/CardTemplate.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import SelectInput from "@/Components/SelectInput.vue";
import { ref, computed } from "vue";
import { Link, Head } from "@inertiajs/vue3";

const props = defineProps(["product"]);
</script>
<template>
    <Head title="Edit Product" />

    <h1 class="font-bold text-4xl text-gray-800">Edit Product</h1>
    <Card>
        <div class="description" v-html="props.product.description"></div
    ></Card>
</template>
