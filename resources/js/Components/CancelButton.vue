<script setup>
import { Link } from "@inertiajs/vue3";
</script>
<template>
    <Link :href="route('products.index')">
        <button
            class="px-6 py-3 inline-flex items-center border border-black rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
        >
            <slot />
        </button>
    </Link>
</template>
