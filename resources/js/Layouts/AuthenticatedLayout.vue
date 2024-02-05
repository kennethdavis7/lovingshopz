<script setup>
import NavbarAdmin from "./AdminLayout/AdminNavbar.vue";
import SidebarAdmin from "./AdminLayout/AdminSidebar.vue";
import ContentAdmin from "@/Components/ContentAdmin.vue";
</script>

<template>
    <NavbarAdmin />
    <SidebarAdmin />

    <ContentAdmin>
        <slot />
    </ContentAdmin>
</template>
