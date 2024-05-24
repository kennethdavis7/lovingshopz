<script setup>
import { Link, usePage, router } from "@inertiajs/vue3";
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import Logo from "@/Components/Logo.vue";
import Search from "@/Components/Search.vue";
import { computed, onMounted, onUnmounted, ref, watch } from "vue";
import logout from "@/utils/logout";
import { MeiliSearch } from "meilisearch";
import { Float } from "@headlessui-float/vue";

const {
    props: { categories },
} = usePage();

const getCategoriesToShow = (width) => {
    if (width <= 500) {
        return 0;
    }

    return width / 140;
};

const searchInput = ref(null);

const show = ref(false);
const results = ref([]);
const client = ref(null);
const currentHit = ref(0);

const search = ref(
    (() => {
        const url = new URL(window.location.href);
        return url.searchParams.get("search");
    })()
);

const handleSearch = () => {
    if (search.value === "") return;

    const url = new URL(route("search"));

    url.searchParams.set("search", search.value);
    url.searchParams.set("page", "1");

    router.get(url, undefined, {
        preserveState: true,
    });

    show.value = false;
    searchInput.value.blurSearch();
};

const currentRoute = ref(route().current("search*"));

const categoriesToShow = ref(getCategoriesToShow(window.outerWidth));

const previewCategories = computed(() =>
    categories.slice(0, categoriesToShow.value)
);

const moreCategories = computed(() => categories.slice(10));

const handleWindowResize = (e) => {
    categoriesToShow.value = getCategoriesToShow(e.currentTarget.outerWidth);
};

const resetPreview = () => {
    show.value = false;
    results.value = [];
};

const showDetailProduct = (id, searchValue) => {
    router.get(route("products.show", id));
    show.value = false;
};

let searchTimeout = null;

const searchPreview = (val) => {
    if (searchTimeout !== null) {
        clearTimeout(searchTimeout);
        searchTimeout = null;
    }

    searchTimeout = setTimeout(async () => {
        results.value = await client.value.index("products").search(val);
    }, 250);
};

onMounted(async () => {
    client.value = new MeiliSearch({
        host: "http://localhost:7700",
    });

    results.value = await client.value.index("products").search(search.value);

    window.addEventListener("resize", handleWindowResize);

    router.on("navigate", () => {
        currentRoute.value = route().current("search*");
        if (!currentRoute.value) {
            search.value = "";
        }
    });
});

onUnmounted(() => {
    window.removeEventListener("resize", handleWindowResize);
});

watch(search, (val) => {
    searchPreview(val);
});
</script>

<template>
    <div class="w-full max-w-screen-xl mx-auto p-4">
        <div class="sm:flex sm:items-center sm:justify-between md:pb-5">
            <span class="text-sm text-gray-500 sm:text-center"
                >© 2024
                <a href="https://flowbite.com/" class="hover:underline"
                    >Lovingshopz</a
                >. All Rights Reserved.
            </span>

            <div class="flex mt-4 sm:justify-center sm:mt-0">
                <a
                    href="https://www.facebook.com/lovingshopz55"
                    class="text-gray-500 hover:text-gray-900"
                >
                    <svg
                        class="w-4 h-4"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor"
                        viewBox="0 0 8 19"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M6.135 3H8V0H6.135a4.147 4.147 0 0 0-4.142 4.142V6H0v3h2v9.938h3V9h2.021l.592-3H5V3.591A.6.6 0 0 1 5.592 3h.543Z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <span class="sr-only">Facebook page</span>
                </a>
                <a href="#" class="text-gray-500 hover:text-gray-900 ms-5">
                    <svg
                        class="w-4 h-4"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M8 4a2.6 2.6 0 0 0-2 .9 6.2 6.2 0 0 0-1.8 6 12 12 0 0 0 3.4 5.5 12 12 0 0 0 5.6 3.4 6.2 6.2 0 0 0 6.6-2.7 2.6 2.6 0 0 0-.7-3L18 12.9a2.7 2.7 0 0 0-3.8 0l-.6.6a.8.8 0 0 1-1.1 0l-1.9-1.8a.8.8 0 0 1 0-1.2l.6-.6a2.7 2.7 0 0 0 0-3.8L10 4.9A2.6 2.6 0 0 0 8 4Z"
                        />
                    </svg>

                    <span class="sr-only">Phone</span>
                </a>
            </div>
        </div>
        <hr class="border-gray-200 sm:mx-auto" />
    </div>

    <div class="sticky top-0 z-50">
        <nav
            class="bg-white border-gray-200 max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4"
        >
            <div
                class="text-sm text-gray-500 sm:text-center flex justify-center items-center mb-4 sm:mb-0 gap-4 w-3/4"
            >
                <Logo imageClass="w-44" />
                <div class="w-3/4">
                    <Menu class="relative inline-block text-left w-full">
                        <Float
                            strategy="sticky"
                            placement="bottom-start"
                            :offset="15"
                            :shift="6"
                            :flip="10"
                            :show="show"
                        >
                            <form @submit.prevent="handleSearch">
                                <Search
                                    ref="searchInput"
                                    @openPreview="() => (show = true)"
                                    @closePreview="() => (show = false)"
                                    v-model="search"
                                />
                            </form>
                            <MenuItems
                                class="absolute w-full z-50 max-h-56 mt-2 bg-white border border-gray-200 rounded-md shadow-lg overflow-y-scroll focus:outline-none"
                                static
                            >
                                <template
                                    v-if="
                                        Number(results.estimatedTotalHits) > 0
                                    "
                                >
                                    <MenuItem
                                        class="py-2"
                                        v-for="previewProduct in results.hits"
                                        :key="previewProduct.id"
                                        v-slot="{ active }"
                                    >
                                        <button
                                            type="button"
                                            :class="[
                                                active
                                                    ? 'bg-green-200 text-black'
                                                    : '',
                                                'block w-full px-4 py-2 text-left text-sm',
                                            ]"
                                            @click="
                                                () =>
                                                    showDetailProduct(
                                                        previewProduct.id
                                                    )
                                            "
                                        >
                                            <svg
                                                class="w-4 h-4 mr-2 text-gray-500 inline-block"
                                                aria-hidden="true"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                    clip-rule="evenodd"
                                                ></path>
                                            </svg>
                                            {{ previewProduct.name }}
                                        </button>
                                    </MenuItem>
                                </template>
                                <template v-else>
                                    <div
                                        class="w-full flex gap-2 py-2 items-center justify-center"
                                    >
                                        <svg
                                            class="w-4 h-4 mr-2 text-gray-500 inline-block"
                                            aria-hidden="true"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd"
                                            ></path>
                                        </svg>
                                        <h2 class="text-md">Not Found</h2>
                                    </div>
                                </template>
                            </MenuItems>
                        </Float>
                    </Menu>
                </div>
            </div>
            <div class="hidden w-1/4 md:block md:w-auto" id="navbar-default">
                <ul
                    class="font-medium flex items-center p-4 gap-8 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white md:dark:bg-gray-900"
                >
                    <li>
                        <Link
                            :href="route('home')"
                            :class="[
                                route().current('home*')
                                    ? 'text-green-700 '
                                    : 'text-gray-900 ',
                                'block py-2 px-3 md:p-0 rounded',
                            ]"
                            aria-current="page"
                            >Home</Link
                        >
                    </li>
                    <li>
                        <Link
                            :href="route('about')"
                            :class="[
                                route().current('about*')
                                    ? 'text-green-700 '
                                    : 'text-gray-900 ',
                                'block py-2 px-3 md:p-0 rounded',
                            ]"
                            >About</Link
                        >
                    </li>
                    <li v-if="$page.props.auth.user === null">
                        <Link
                            :href="route('user.login')"
                            class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-green-700 md:p-0 md:dark:hover:text-green-500 md:dark:hover:bg-transparent"
                            >Login
                        </Link>
                    </li>
                    <template v-if="$page.props.auth.user !== null">
                        <Menu as="div" class="relative inline-block text-left">
                            <MenuButton class="flex items-center">
                                <div>
                                    <button
                                        type="button"
                                        class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300"
                                        aria-expanded="false"
                                        data-dropdown-toggle="dropdown-user"
                                    >
                                        <span class="sr-only"
                                            >Open user menu</span
                                        >
                                        <img
                                            class="w-8 h-8 rounded-full object-contain"
                                            :src="
                                                '/' +
                                                $page.props.auth.user.image
                                            "
                                            alt="user photo"
                                        />
                                    </button>
                                </div>
                            </MenuButton>

                            <transition
                                enter-active-class="transition duration-100 ease-out"
                                enter-from-class="transform scale-95 opacity-0"
                                enter-to-class="transform scale-100 opacity-100"
                                leave-active-class="transition duration-75 ease-in"
                                leave-from-class="transform scale-100 opacity-100"
                                leave-to-class="transform scale-95 opacity-0"
                            >
                                <MenuItems
                                    class="absolute right-0 z-10 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
                                >
                                    <div class="px-1 py-1">
                                        <MenuItem v-slot="{ active }">
                                            <Link
                                                :href="route('profile.edit')"
                                                :class="[
                                                    active
                                                        ? 'bg-gray-50 text-green-600'
                                                        : 'text-gray-900',
                                                    'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                                ]"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="16"
                                                    height="16"
                                                    fill="currentColor"
                                                    class="bi bi-person mr-5"
                                                    viewBox="0 0 16 16"
                                                >
                                                    <path
                                                        d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"
                                                    />
                                                </svg>
                                                Profil
                                            </Link>
                                        </MenuItem>
                                        <MenuItem
                                            v-slot="{ active }"
                                            v-if="
                                                $page.props.auth.user.role_id ==
                                                1
                                            "
                                        >
                                            <Link
                                                :href="route('dashboard')"
                                                :class="[
                                                    active
                                                        ? 'bg-gray-50 text-green-600'
                                                        : 'text-gray-900',
                                                    'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                                ]"
                                                as="button"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="16"
                                                    height="16"
                                                    fill="currentColor"
                                                    class="bi bi-bar-chart-line mr-5"
                                                    viewBox="0 0 16 16"
                                                >
                                                    <path
                                                        d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1zm1 12h2V2h-2zm-3 0V7H7v7zm-5 0v-3H2v3z"
                                                    />
                                                </svg>
                                                Dashboard
                                            </Link>
                                        </MenuItem>
                                        <MenuItem
                                            v-slot="{ active }"
                                            v-if="
                                                $page.props.auth.user.role_id ==
                                                2
                                            "
                                        >
                                            <Link
                                                :href="route('orders.index')"
                                                :class="[
                                                    active
                                                        ? 'bg-gray-50 text-green-600'
                                                        : 'text-gray-900',
                                                    'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                                ]"
                                                as="button"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="16"
                                                    height="16"
                                                    fill="currentColor"
                                                    class="bi bi-newspaper mr-5"
                                                    viewBox="0 0 16 16"
                                                >
                                                    <path
                                                        d="M0 2.5A1.5 1.5 0 0 1 1.5 1h11A1.5 1.5 0 0 1 14 2.5v10.528c0 .3-.05.654-.238.972h.738a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 1 1 0v9a1.5 1.5 0 0 1-1.5 1.5H1.497A1.497 1.497 0 0 1 0 13.5zM12 14c.37 0 .654-.211.853-.441.092-.106.147-.279.147-.531V2.5a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0-.5.5v11c0 .278.223.5.497.5z"
                                                    />
                                                    <path
                                                        d="M2 3h10v2H2zm0 3h4v3H2zm0 4h4v1H2zm0 2h4v1H2zm5-6h2v1H7zm3 0h2v1h-2zM7 8h2v1H7zm3 0h2v1h-2zm-3 2h2v1H7zm3 0h2v1h-2zm-3 2h2v1H7zm3 0h2v1h-2z"
                                                    />
                                                </svg>

                                                Transaksi
                                            </Link>
                                        </MenuItem>
                                        <MenuItem v-slot="{ active }">
                                            <button
                                                @click="logout"
                                                :class="[
                                                    active
                                                        ? 'bg-gray-50 text-green-600'
                                                        : 'text-gray-900',
                                                    'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                                ]"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="16"
                                                    height="16"
                                                    fill="currentColor"
                                                    class="bi bi-box-arrow-left mr-5"
                                                    viewBox="0 0 16 16"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"
                                                    />
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"
                                                    />
                                                </svg>
                                                Logout
                                            </button>
                                        </MenuItem>
                                    </div>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </template>
                </ul>
            </div>
        </nav>
        <nav class="bg-gray-50">
            <div class="w-full max-w-screen-xl px-4 py-5 mx-auto">
                <div class="flex items-center">
                    <ul class="flex justify-between w-full gap-4">
                        <div
                            class="flex flex-row font-medium mt-0 gap-4 rtl:space-x-reverse text-sm"
                        >
                            <li
                                v-for="previewCategory in previewCategories"
                                :key="previewCategory.id"
                            >
                                <Link
                                    :href="
                                        route(
                                            'categories.show',
                                            previewCategory.id
                                        )
                                    "
                                    :class="
                                        route().current('categories.show', {
                                            category: previewCategory.id,
                                        })
                                            ? 'text-green-600'
                                            : 'text-gray-900 hover:text-green-600'
                                    "
                                    aria-current="page"
                                >
                                    {{ previewCategory.name }}
                                </Link>
                            </li>
                        </div>

                        <li
                            class="font-medium mt-0 rtl:space-x-reverse text-sm"
                        >
                            <Menu
                                as="div"
                                class="relative inline-block text-left"
                            >
                                <MenuButton>More</MenuButton>
                                <transition
                                    enter-active-class="transition duration-100 ease-out"
                                    enter-from-class="transform scale-95 opacity-0"
                                    enter-to-class="transform scale-100 opacity-100"
                                    leave-active-class="transition duration-75 ease-out"
                                    leave-from-class="transform scale-100 opacity-100"
                                    leave-to-class="transform scale-95 opacity-0"
                                >
                                    <MenuItems
                                        class="absolute mt-6 right-0 overflow-y-scroll h-96 z-20 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
                                    >
                                        <div
                                            class="px-1 py-1"
                                            v-for="moreCategory in moreCategories"
                                            :key="moreCategory.id"
                                        >
                                            <MenuItem as="template">
                                                <Link
                                                    :class="[
                                                        route().current(
                                                            'categories.show',
                                                            {
                                                                category:
                                                                    moreCategory.id,
                                                            }
                                                        )
                                                            ? 'bg-green-100 text-black'
                                                            : 'text-gray-900 hover:bg-green-100',
                                                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                                    ]"
                                                    :href="
                                                        route(
                                                            'categories.show',
                                                            moreCategory.id
                                                        )
                                                    "
                                                >
                                                    {{ moreCategory.name }}
                                                </Link>
                                            </MenuItem>
                                        </div>
                                    </MenuItems>
                                </transition>
                            </Menu>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</template>
