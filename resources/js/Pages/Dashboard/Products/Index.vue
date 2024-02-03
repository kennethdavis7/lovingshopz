<script setup>
import Sidebar from "../Partials/Sidebar.vue";
import Navbar from "../Partials/Navbar.vue";
import SearchButton from "../Components/SearchButton.vue";
import DropdownButton from "../Components/DropdownButton.vue";
import PrintButton from "../Components/PrintButton.vue";
import CreateButton from "../Components/CreateButton.vue";

const props = defineProps({
    products: Object,
});
</script>
<template>
    <Navbar />
    <Sidebar />

    <div class="p-7 sm:ml-64">
        <CreateButton />
        <div
            class="p-4 border-2 border-gray-200 rounded-lg dark:border-gray-700 mt-3"
        >
            <div
                class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4"
            >
                <div class="flex align-middle">
                    <DropdownButton />
                    <div class="mx-2"></div>
                    <PrintButton />
                </div>
                <SearchButton />
            </div>
            <table
                class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
            >
                <thead
                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                >
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Image</th>
                        <th scope="col" class="px-6 py-3">Product Name</th>
                        <th scope="col" class="px-6 py-3">Category</th>
                        <th scope="col" class="px-6 py-3">Quantity</th>
                        <th scope="col" class="px-6 py-3">Price</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody v-for="product in props.products" :key="product.id">
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                    >
                        <td class="px-6 py-4">1</td>

                        <td class="px-6 py-4">
                            <!-- <img :src="'/storage/images/logo.png'" alt="" /> -->
                            <img :src="product.img" alt="" />
                        </td>

                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                        >
                            {{ product.name }}
                        </th>
                        <td class="px-6 py-4">
                            <span
                                v-for="category in product.category"
                                :key="category.id"
                                id="badge-dismiss-default"
                                class="inline-flex items-center px-2 py-1 me-2 text-sm font-medium text-green-800 bg-green-100 rounded dark:bg-green-900 dark:text-green-300"
                            >
                                {{ category.name }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ product.qty }}</td>
                        <td class="px-6 py-4">Rp{{ product.price }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <template v-if="product.status === 1">
                                    <div
                                        class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"
                                    ></div>
                                    Published
                                </template>
                                <template v-else>
                                    <div
                                        class="h-2.5 w-2.5 rounded-full bg-red-500 me-2"
                                    ></div>
                                    Unpublished
                                </template>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <a
                                href="#"
                                class="font-medium mr-4 text-green-600 dark:text-green-500 hover:underline"
                                >Edit</a
                            >
                            <a
                                href="#"
                                class="font-medium text-red-600 dark:text-red-500 hover:underline"
                                >Delete</a
                            >
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
