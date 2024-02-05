<script setup>
import SearchButton from "@/Components/SearchButton.vue";
import DropdownButton from "@/Components/DropdownButton.vue";
import PrintButton from "@/Components/PrintButton.vue";
import CreateButton from "@/Components/CreateButton.vue";
import Card from "@/Components/CardAdmin.vue";
import Content from "@/Components/ContentAdmin.vue";
import { Link, Head } from "@inertiajs/vue3";

const props = defineProps({
    products: Object,
});
</script>

<template>
    <Head title="Products" />
    <div class="flex justify-between">
        <h1 class="font-bold text-4xl text-gray-800">Products</h1>
        <CreateButton />
    </div>
    <Card>
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
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
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
                <tr class="bg-white border-b">
                    <td class="px-6 py-4">1</td>

                    <td class="px-6 py-4">
                        <template
                            v-for="image in product.images"
                            :key="image.id"
                        >
                            <img
                                :src="image.url"
                                :alt="image.alt"
                                class="w-40"
                            />
                        </template>
                    </td>

                    <th
                        scope="row"
                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                    >
                        {{ product.name }}
                    </th>
                    <td class="px-6 py-4">
                        <span
                            id="badge-dismiss-default"
                            class="inline-flex items-center px-2 py-1 me-2 text-sm font-medium text-green-800 bg-green-100 rounded"
                        >
                            {{ product.category.name }}
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
                            class="font-medium mr-4 text-blue-600 hover:underline"
                            >Edit</a
                        >
                        <a
                            href="#"
                            class="font-medium text-red-600 hover:underline"
                            >Delete</a
                        >
                    </td>
                </tr>
            </tbody>
        </table>
    </Card>
</template>
