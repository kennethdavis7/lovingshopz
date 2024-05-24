<script setup>
import ProductCard from "@/Components/ProductCard.vue";
import Cart from "@/Components/Cart.vue";
import Pagination from "@/Components/Pagination.vue";
import Select from "@/Components/Select.vue";
import { Head, router } from "@inertiajs/vue3";

const props = defineProps({
    searchedProducts: Object,
    total_products: Number,
    sort_by: String,
    sort_direction: String,
    search: String,
});

const handleSort = (sortName) => {
    const url = new URL(window.location.href);

    url.searchParams.set("sort_by", "price");
    if (sortName === "Termahal") {
        url.searchParams.set("sort_direction", "desc");
    } else if (sortName === "Termurah") {
        url.searchParams.set("sort_direction", "asc");
    } else {
        url.searchParams.set("sort_by", "created_at");
        url.searchParams.set("sort_direction", "desc");
    }

    url.searchParams.set("page", "1");

    router.get(url.href, undefined, {
        preserveState: true,
        preserveScroll: true,
    });
};

const getSortName = (sort_direction, sort_by) => {
    if (sort_direction === "desc" && sort_by === "price") {
        return "Termahal";
    } else if (sort_direction === "asc" && sort_by === "price") {
        return "Termurah";
    } else {
        return "Terbaru";
    }
};
</script>
<template>
    <Head title="Search" />
    <div class="w-full max-w-screen-xl mx-auto p-4 my-10">
        <div class="w-full flex justify-between">
            <span
                >Menampilkan 1 - 20 barang dari
                {{ props.searchedProducts.total }} untuk "<b>{{
                    props.search
                }}</b
                >"
            </span>
            <div class="w-28">
                <Select
                    :model-value="
                        getSortName(props.sort_direction, props.sort_by)
                    "
                    @update:model-value="handleSort"
                    :data="
                        ['Terbaru', 'Termahal', 'Termurah'].map((el) => ({
                            name: el,
                            id: el,
                        }))
                    "
                    buttonClass="py-2"
                />
            </div>
        </div>
        <div class="content flex flex-wrap gap-4 mt-8 justify-items-auto">
            <ProductCard
                v-for="product in props.searchedProducts.data"
                :key="product.id"
                :data="product"
                class="flex-1 min-w-[300px]"
            />
        </div>
        <div class="flex justify-end mt-8">
            <Pagination :data="props.searchedProducts" />
        </div>
    </div>
    <Cart v-if="$page.props.auth.user?.role_id == 2" />
</template>
