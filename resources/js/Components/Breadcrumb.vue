<script setup>
import { Link } from "@inertiajs/vue3";
const props = defineProps({
    level1: Object,
    level2: Object | null,
});
</script>
<template>
    <nav class="flex" aria-label="Breadcrumb">
        <ol
            class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse"
        >
            <li class="inline-flex items-center">
                <Link
                    :href="route('home')"
                    class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-green-600 dark:text-gray-400 dark:hover:text-white"
                >
                    <svg
                        class="w-3 h-3 me-2.5"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"
                        />
                    </svg>
                    Home
                </Link>
            </li>
            <li>
                <div class="flex items-center">
                    <svg
                        class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 6 10"
                    >
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m1 9 4-4-4-4"
                        />
                    </svg>
                    <Link
                        :href="route('categories.show', props.level1.id)"
                        :class="[
                            route().current('categories.show', {
                                category: props.level1.id,
                            })
                                ? 'text-green-600'
                                : 'text-gray-700 hover:text-green-600',
                            'ms-1 text-sm font-medium md:ms-2 dark:text-gray-400 dark:hover:text-white',
                        ]"
                        >{{ props.level1.name }}</Link
                    >
                </div>
            </li>
            <li v-if="props.level2">
                <div class="flex items-center">
                    <svg
                        class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 6 10"
                    >
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m1 9 4-4-4-4"
                        />
                    </svg>
                    <Link
                        :href="route('products.show', props.level2.id)"
                        :class="[
                            route().current('products.show', {
                                product: props.level2.id,
                            })
                                ? 'text-green-600'
                                : 'text-gray-700 hover:text-green-600',
                            'ms-1 text-sm font-medium md:ms-2 dark:text-gray-400 dark:hover:text-white',
                        ]"
                        >{{ props.level2.name }}</Link
                    >
                </div>
            </li>
        </ol>
    </nav>
</template>
