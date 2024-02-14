<script setup>
import { Link, router } from "@inertiajs/vue3";
import { watch } from "vue";
import { ref, computed } from "vue";
const props = defineProps(["products"]);
const currentPage = ref(
    props.products.links.filter((link) => link.active === true)
);

const length = computed(() => props.products.links.length);
const pageCount = computed(() => length.value - 2);

const currentPageIndex = Number.parseInt(currentPage.value[0].label, 10);

const pagesToShow = computed(() => {
    if (pageCount <= 3) {
        return Array.from({ length: pageCount }, (_, idx) => idx + 1);
    }

    if (currentPageIndex === 1) {
        return [1, 2, 3];
    }

    if (currentPageIndex === pageCount.value) {
        return [pageCount.value - 2, pageCount.value - 1, pageCount.value];
    }

    return [currentPageIndex - 1, currentPageIndex, currentPageIndex + 1];
});

const handleSelectPage = (pageNumber) => {
    const url = new URL(window.location.href);
    url.searchParams.set("page", pageNumber.toString());

    router.get(url, undefined, { replace: true });
};

// awal
// for (let i = 1; i <= 3; i++) {}

// // tengah
// for (let i = page - 1; i <= page + 1; i++) {}

// // akhir
// for (let i = total - 2; i <= total; i++) {}

// 1: 1, 2, 3
// 2: 1, 2, 3
// 3: 2, 3, 4
// 4: 2, 3, 4

// PAGES_TO_SHOW = 4
// current_page = 3
// 1, 2, 3, 4
// 2, 3, 4, 5

// PAGES_TO_SHOW = 5
// 1, 2, 3, 4, 5
</script>
<template>
    <nav>
        <ul class="inline-flex -space-x-px text-base h-10">
            <Link
                :href="products.links[0].url"
                :class="[
                    products.links[0].active
                        ? 'flex items-center justify-center px-3 h-8 text-green-600 border border-gray-300 bg-green-50 hover:bg-green-100 hover:text-green-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white'
                        : 'flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white',
                ]"
                ><span
                    class="description"
                    v-html="products.links[0].label"
                ></span
            ></Link>

            <template v-for="n in pagesToShow" :key="n">
                <li>
                    <Link
                        :href="products.links[n].url"
                        :class="[
                            products.links[n].active
                                ? 'flex items-center justify-center px-3 h-8 text-green-600 border border-gray-300 bg-green-50 hover:bg-green-100 hover:text-green-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white'
                                : 'flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white',
                        ]"
                        ><span
                            class="description"
                            v-html="products.links[n].label"
                        ></span
                    ></Link>
                </li>
            </template>

            <!-- <select @change="(e) => handleSelectPage(e.target.value)">
                <option
                    v-for="pageNumber in Array.from(
                        { length: pageCount },
                        (_, idx) => idx + 1
                    )"
                    :key="pageNumber"
                    :value="pageNumber"
                    :selected="pageNumber === currentPageIndex"
                >
                    {{ pageNumber }}
                </option>
            </select> -->

            <Link
                :href="products.links[length - 1].url"
                :class="[
                    products.links[length - 1].active
                        ? 'flex items-center justify-center px-3 h-8 text-green-600 border border-gray-300 bg-green-50 hover:bg-green-100 hover:text-green-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white'
                        : 'flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white',
                ]"
                ><span
                    class="description"
                    v-html="products.links[length - 1].label"
                ></span
            ></Link>
        </ul>
    </nav>
</template>
