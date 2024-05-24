<script setup>
import Navbar from "./AuthenticatedLayout/AuthenticatedNavbar.vue";
import Sidebar from "./AuthenticatedLayout/AuthenticatedSidebar.vue";
</script>

<template>
    <Navbar />
    <Sidebar />

    <div class="p-7 sm:ml-64 mt-20">
        <slot />
    </div>
</template>
