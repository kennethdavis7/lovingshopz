<script setup>
import Search from "@/Components/Search.vue";
import PrintButton from "@/Components/PrintButton.vue";
import CreateButton from "@/Components/CreateButton.vue";
import Card from "@/Components/CardTemplate.vue";
import Pagination from "@/Components/Pagination.vue";
import SuccessAlert from "@/Components/SuccessAlert.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { watch, ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    products: Object,
    sort_by: String,
    sort_direction: String,
    total_products: Number,
    categories: Object,
    category_id: Number,
});

const categories = computed(() => [
    {
        id: -1,
        name: "All",
    },
    ...props.categories,
]);

const moneyFormatter = new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
});

const formatCurrency = (num) => {
    return moneyFormatter.format(num);
};

const getSortIcon = (column) => {
    if (props.sort_by !== column) {
        return "sort_up_down.svg";
    }

    return props.sort_direction === "asc" ? "sort_down.svg" : "sort_up.svg";
};

const handleSort = (column) => {
    const url = new URL(window.location.href);

    if (props.sort_by === column) {
        url.searchParams.set(
            "sort_direction",
            props.sort_direction === "asc" ? "desc" : "asc"
        );
    } else {
        url.searchParams.set("sort_by", column);
        url.searchParams.set("sort_direction", "asc");
    }

    router.get(url.href, undefined, {
        preserveState: true,
    });
};

const handleDelete = (id) => {
    router.delete(route("products.destroy", id));
};

const search = ref();

// debouncing
let searchTimeout = null;

watch(search, (value) => {
    if (searchTimeout !== null) {
        clearTimeout(searchTimeout);
        searchTimeout = null;
    }

    searchTimeout = setTimeout(() => {
        const url = new URL(window.location.href);

        url.searchParams.set("search", value);
        url.searchParams.set("page", "1");

        router.get(url, undefined, {
            preserveState: true,
        });
    }, 250);
});

const handleChangeCategory = (categoryId) => {
    const url = new URL(window.location.href);

    url.searchParams.set("category_id", categoryId);
    url.searchParams.set("page", "1");

    router.get(url, undefined, {
        preserveState: true,
    });
};

const handleChangePerPage = (perPage) => {
    const url = new URL(window.location.href);

    url.searchParams.set("page", 1);
    url.searchParams.set("per_page", perPage);

    router.get(url.href, undefined, {
        replaced: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Products" />

    <div class="flex justify-end mb-5" v-if="$page.props.flash.successMessage">
        <SuccessAlert :message="$page.props.flash.successMessage" />
    </div>

    <div class="flex justify-between">
        <h1 class="font-bold text-4xl text-gray-800">Products</h1>
        <CreateButton />
    </div>
    <Card>
        <div
            class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4"
        >
            <div class="flex items-center">
                <SelectInput
                    id="category_id"
                    :modelValue="category_id"
                    @update:modelValue="handleChangeCategory"
                    :data="categories"
                    popUpClass="w-96"
                    buttonClass="py-2"
                />
                <div class="mx-2"></div>
                <SelectInput
                    :model-value="products.per_page"
                    @update:model-value="handleChangePerPage"
                    :data="
                        [10, 20, 50, 100].map((el) => ({
                            name: el,
                            id: el,
                        }))
                    "
                    buttonClass="py-2"
                />
                <div class="mx-2"></div>
                <PrintButton />
            </div>
            <Search v-model="search" />
        </div>
        <table
            class="block w-full text-sm mb-2 text-left rtl:text-right text-gray-500 overflow-x-scroll"
        >
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">No</th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">
                        Image
                    </th>
                    <th
                        scope="col"
                        class="px-3 py-5 whitespace-nowrap cursor-pointer"
                        @click="() => handleSort('name')"
                    >
                        <div class="flex items-center gap-4">
                            <img :src="`/icons/${getSortIcon('name')}`" />
                            <span>Product Name</span>
                        </div>
                    </th>
                    <th
                        scope="col"
                        class="px-3 py-5 whitespace-nowrap cursor-pointer"
                        @click="() => handleSort('categories.name')"
                    >
                        <div class="flex items-center gap-4">
                            <img
                                :src="`/icons/${getSortIcon(
                                    'categories.name'
                                )}`"
                            />
                            <span>Category</span>
                        </div>
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3 whitespace-nowrap cursor-pointer"
                        @click="() => handleSort('qty')"
                    >
                        <div class="flex items-center gap-2">
                            <img :src="`/icons/${getSortIcon('qty')}`" />
                            <span>Quantity</span>
                        </div>
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3 whitespace-nowrap cursor-pointer"
                        @click="() => handleSort('price')"
                    >
                        <div class="flex items-center gap-2">
                            <img :src="`/icons/${getSortIcon('price')}`" />
                            <span>Price</span>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody v-for="(product, idx) in products.data" :key="product.id">
                <tr class="bg-white border-b">
                    <td class="px-6 py-4">
                        {{
                            products.per_page * (products.current_page - 1) +
                            idx +
                            1
                        }}
                    </td>

                    <td class="px-6 py-4 text-center">
                        <img
                            v-if="product.images.length > 0"
                            :src="product.images[0].url"
                            :alt="product.images[0].alt"
                            class="w-40"
                        />

                        <span v-else>-</span>
                    </td>

                    <th
                        scope="row"
                        class="whitespace-nowrap px-6 py-4 text-wrap font-medium text-gray-900"
                    >
                        {{ product.name }}
                    </th>
                    <td class="px-6 py-4">
                        <span
                            id="badge-dismiss-default"
                            class="whitespace-nowrap inline-flex items-center px-2 py-1 me-2 text-sm font-medium text-green-800 bg-green-100 rounded"
                        >
                            {{ product.category.name }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <template v-if="product.qty">
                            {{ product.qty }}
                        </template>

                        <template v-else>&infin;</template>
                    </td>
                    <td class="px-6 py-4">
                        {{ formatCurrency(product.price) }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <template v-if="product.status">
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
                        <Link
                            :href="route('products.edit', product.id)"
                            class="font-medium mr-4 text-blue-600 hover:underline"
                            >Edit</Link
                        >
                        <button
                            type="submit"
                            @click="() => handleDelete(product.id)"
                            class="font-medium text-red-600 hover:underline"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="flex justify-between items-center">
            <span class="total_products">
                Total Products: {{ props.total_products }}
            </span>
            <Pagination :products="props.products" />
        </div>
    </Card>
</template>
