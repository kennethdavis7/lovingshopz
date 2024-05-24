<script setup>
import { Link, router } from "@inertiajs/vue3";
import { ref } from "vue";

const currentRoute = ref(null);

router.on("navigate", () => {
    currentRoute.value = route().current();
});
</script>
<template>
    <aside
        id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0"
        aria-label="Sidebar"
    >
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
            <ul class="space-y-2 font-medium" :key="currentRoute">
                <li>
                    <Link
                        href="/dashboard"
                        :class="[
                            route().current('dashboard*')
                                ? 'text-green-600 bg-gray-100 '
                                : 'text-gray-500 hover:text-green-600 hover:bg-gray-100',
                            'flex items-center px-2 rounded-lg group',
                        ]"
                        v-if="$page.props.auth.user.role_id == 1"
                    >
                        <svg
                            class="flex-shrink-0 w-5 h-5"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor"
                            viewBox="0 0 22 21"
                        >
                            <path
                                d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"
                            />
                            <path
                                d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"
                            />
                        </svg>
                        <span
                            class="flex-1 whitespace-nowrap block py-2 px-2 rounded-lg group"
                            >Dashboard</span
                        >
                    </Link>
                </li>
                <li>
                    <Link
                        href="/orders"
                        :class="[
                            route().current('orders*')
                                ? 'text-green-600 bg-gray-100 '
                                : 'text-gray-500 hover:text-green-600 hover:bg-gray-100',
                            'flex items-center px-2 rounded-lg group',
                        ]"
                        v-if="$page.props.auth.user.role_id == 2"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="16"
                            height="16"
                            fill="currentColor"
                            class="bi bi-newspaper"
                            viewBox="0 0 16 16"
                        >
                            <path
                                d="M0 2.5A1.5 1.5 0 0 1 1.5 1h11A1.5 1.5 0 0 1 14 2.5v10.528c0 .3-.05.654-.238.972h.738a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 1 1 0v9a1.5 1.5 0 0 1-1.5 1.5H1.497A1.497 1.497 0 0 1 0 13.5zM12 14c.37 0 .654-.211.853-.441.092-.106.147-.279.147-.531V2.5a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0-.5.5v11c0 .278.223.5.497.5z"
                            />
                            <path
                                d="M2 3h10v2H2zm0 3h4v3H2zm0 4h4v1H2zm0 2h4v1H2zm5-6h2v1H7zm3 0h2v1h-2zM7 8h2v1H7zm3 0h2v1h-2zm-3 2h2v1H7zm3 0h2v1h-2zm-3 2h2v1H7zm3 0h2v1h-2z"
                            />
                        </svg>

                        <span
                            class="flex-1 whitespace-nowrap block py-2 px-2 rounded-lg group"
                            >Transaksi</span
                        >
                    </Link>
                </li>
                <li>
                    <Link
                        href="/products"
                        :class="[
                            route().current('products*')
                                ? 'text-green-600 bg-gray-100 '
                                : 'text-gray-500 hover:text-green-600 hover:bg-gray-100',
                            'flex items-center px-2 rounded-lg group',
                        ]"
                        v-if="$page.props.auth.user.role_id == 1"
                    >
                        <svg
                            class="flex-shrink-0 w-5 h-5"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor"
                            viewBox="0 0 18 20"
                        >
                            <path
                                d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"
                            />
                        </svg>
                        <span
                            class="flex-1 whitespace-nowrap block py-2 px-2 rounded-lg group"
                            >Products</span
                        >
                    </Link>
                </li>
                <li>
                    <Link
                        href="/categories"
                        :class="[
                            route().current('categories*')
                                ? 'text-green-600 bg-gray-100 '
                                : 'text-gray-500 hover:text-green-600 hover:bg-gray-100',
                            'flex items-center px-2 rounded-lg group',
                        ]"
                        v-if="$page.props.auth.user.role_id == 1"
                    >
                        <svg
                            class="flex-shrink-0 w-5 h-5"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor"
                            viewBox="0 0 18 18"
                        >
                            <path
                                d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"
                            />
                        </svg>
                        <span
                            class="flex-1 whitespace-nowrap block py-2 px-2 rounded-lg group"
                            >Categories</span
                        >
                    </Link>
                </li>
            </ul>
        </div>
    </aside>
</template>
