<script setup>
import BoxShadow from "@/Components/BoxShadow.vue";
import Search from "@/Components/Search.vue";
import Pagination from "@/Components/Pagination.vue";
import Flash from "@/Components/Flash.vue";
import Select from "@/Components/Select.vue";
import { watch, ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import StatusBadge from "@/Components/StatusBadge.vue";
import Action from "@/Components/Action.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    categories: Object,
    total_categories: Number,
});

const handleChangePerPage = (perPage) => {
    const url = new URL(window.location.href);

    url.searchParams.set("page", 1);
    url.searchParams.set("per_page", perPage);

    router.get(url.href, undefined, {
        replaced: true,
        preserveScroll: true,
    });
};

const handleDelete = (id) => {
    router.delete(route("categories.destroy", id));
};

const handleEdit = (id) => {
    router.get(route("categories.edit", id));
};

const handleCreate = () => {
    router.get(route("categories.create"));
};

const search = ref();

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
</script>
<template>
    <Head title="Categories" />
    <div class="flex justify-end mb-5" v-if="$page.props.flash.message">
        <Flash :message="$page.props.flash.message" />
    </div>
    <div class="flex justify-between">
        <h1 class="font-bold text-4xl text-gray-800">Categories</h1>
        <PrimaryButton class="px-6 py-2" @click="handleCreate"
            >Create</PrimaryButton
        >
    </div>
    <BoxShadow>
        <div
            class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4"
        >
            <div class="flex items-center gap-2">
                <Select
                    :model-value="categories.per_page"
                    @update:model-value="handleChangePerPage"
                    :data="
                        [10, 20, 50, 100].map((el) => ({
                            name: el,
                            id: el,
                        }))
                    "
                    buttonClass="py-2"
                />
            </div>
            <Search v-model="search" />
        </div>
        <table
            class="w-full text-sm mb-2 text-left rtl:text-right text-gray-500 overflow-x-scroll"
        >
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">No</th>
                    <th
                        scope="col"
                        class="px-6 py-3 whitespace-nowrap cursor-pointer"
                    >
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody
                v-for="(category, idx) in categories.data"
                :key="category.id"
            >
                <tr class="bg-white border-b">
                    <td class="px-6 py-4">
                        {{
                            categories.per_page *
                                (categories.current_page - 1) +
                            idx +
                            1
                        }}
                    </td>

                    <td class="px-6 py-4">
                        {{ category.name }}
                    </td>
                    <td class="px-6 py-4">
                        <StatusBadge :status="category.status" />
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <Action
                                class="text-blue-600"
                                @click="() => handleEdit(category.id)"
                            >
                                Edit
                            </Action>
                            <Action
                                class="text-red-600"
                                @click="() => handleDelete(category.id)"
                            >
                                Delete
                            </Action>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="flex justify-between items-center">
            <span class="total_categories">
                Total categories: {{ props.total_categories }}
            </span>
            <Pagination :data="props.categories" />
        </div>
    </BoxShadow>
</template>
