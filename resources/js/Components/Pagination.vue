<script setup>
import { Link, router } from "@inertiajs/vue3";
import { watch } from "vue";
import { ref, computed } from "vue";
import SelectInput from "./SelectInput.vue";

const props = defineProps(["products"]);

// Kita:
// Selalu render 3 page, yaitu yang mendekati current page.

// Laravel:
// Selalu render 5 page yang mendekati current page, DAN beberapa page paling awal DAN beberapa page paling akhir.
// current_page = 20
// 1, 2, 3, ..., 16, 17, 18, 19, 20, 21, 22, ..., 123, 124, 125

// const pagesToShow = computed(() => {
//     if (pageCount <= 3) {
//         return Array.from({ length: pageCount }, (_, idx) => idx + 1);
//     }

//     if (props. === 1) {
//         return [1, 2, 3];
//     }

//     if (currentPageIndex === pageCount.value) {
//         return [pageCount.value - 2, pageCount.value - 1, pageCount.value];
//     }

//     return [currentPageIndex - 1, currentPageIndex, currentPageIndex + 1];
// });

const handleSelectPage = (url) => {
    router.get(url, undefined, {
        replace: true,
        preserveScroll: true,
        onSuccess: () => {
            setTimeout(() => {
                window.scrollBy({
                    top: document.body.scrollHeight,
                });
            }, 50);
        },
    });
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
    <nav class="flex justify-between">
        <ul class="inline-flex text-base">
            <!-- <Link
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
            ></Link> -->

            <!-- <template v-for="n in pagesToShow" :key="n">
                <li>
                    <button
                        type="button"
                        :class="[
                            'flex items-center justify-center px-3 h-8 border border-gray-300 dark:border-gray-700',
                            products.links[n].active
                                ? 'text-green-600 bg-green-50 hover:bg-green-100 hover:text-green-700 dark:bg-gray-700 dark:text-white'
                                : 'leading-tight text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white',
                        ]"
                        @click="() => handleSelectPage(n)"
                    >
                        <span class="description">{{ n }}</span>
                    </button>
                </li>
            </template> -->

            <template v-for="link in products.links" :key="link.label">
                <li>
                    <button
                        type="button"
                        :class="[
                            'flex items-center justify-center px-3 h-8 border border-gray-300 dark:border-gray-700',
                            link.active
                                ? 'text-green-600 bg-green-50 hover:bg-green-100 hover:text-green-700 dark:bg-gray-700 dark:text-white'
                                : 'leading-tight text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white',
                        ]"
                        @click="() => handleSelectPage(link.url)"
                    >
                        <span v-html="link.label"></span>
                    </button>
                </li>
            </template>

            <!-- <select @change="(e) => handleSelectPage(e.target.value)">
                <option
                    v-for="pageNumber in Array.from(
                        { length: products.last_page },
                        (_, idx) => idx + 1
                    )"
                    :key="pageNumber"
                    :value="pageNumber"
                    :selected="pageNumber === products.current_page"
                >
                    {{ pageNumber }}
                </option>
            </select> -->

            <!-- <Link
                :href="products.links[products.links.length - 1].url"
                :class="[
                    products.links[products.links.length - 1].active
                        ? 'flex items-center justify-center px-3 h-8 text-green-600 border border-gray-300 bg-green-50 hover:bg-green-100 hover:text-green-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white'
                        : 'flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white',
                ]"
                ><span
                    class="description"
                    v-html="products.links[products.links.length - 1].label"
                ></span
            ></Link> -->
        </ul>
    </nav>
</template>
