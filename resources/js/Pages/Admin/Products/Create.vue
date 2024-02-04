<script setup>
import Sidebar from "../../../Layouts/SidebarAdmin.vue";
import Navbar from "../../../Layouts/NavbarAdmin.vue";
import Card from "../../../Components/CardAdmin.vue";
import Content from "../../../Components/ContentAdmin.vue";
</script>
<template>
    <Head title="Create Product" />

    <Navbar />
    <Sidebar />
    <Content>
        <h1 class="font-bold text-4xl text-gray-800">Create Product</h1>
        <Card>
            <form class="max-w-sm">
                <div class="mb-5">
                    <label
                        for="product"
                        class="block mb-2 text-sm font-medium text-gray-900"
                        >Product Title</label
                    >
                    <input
                        type="text"
                        id="product"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                    />
                </div>
            </form>
        </Card>
    </Content>
</template>
