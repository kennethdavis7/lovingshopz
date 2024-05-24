<script setup>
import { Link } from "@inertiajs/vue3";
import Logo from "@/Components/Logo.vue";
</script>
<template>
    <footer class="bg-white rounded-lg">
        <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
            <hr class="my-6 border-gray-200 sm:mx-auto lg:my-8" />
            <div class="sm:flex sm:items-center sm:justify-between">
                <div
                    class="text-sm text-gray-500 sm:text-center flex items-center mb-4 sm:mb-0 space-x-3 rtl:space-x-reverse"
                >
                    <Logo imageClass="w-44" />
                    <div class="flex-col flex">
                        <div class="flex">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                fill="currentColor"
                                class="bi bi-geo-alt-fill"
                                viewBox="0 0 16 16"
                            >
                                <path
                                    d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"
                                />
                            </svg>
                            <span
                                class="ml-5 text-sm text-gray-500 sm:text-center flex items-center mb-4 sm:mb-0 space-x-3 rtl:space-x-reverse"
                            >
                                Taman Palem Blok K32, Cengkareng, Jakarta Barat
                            </span>
                        </div>
                        <div class="flex">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                fill="currentColor"
                                class="bi bi-envelope"
                                viewBox="0 0 16 16"
                            >
                                <path
                                    d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"
                                />
                            </svg>
                            <span
                                class="ml-5 text-sm text-gray-500 sm:text-center flex items-center mb-4 sm:mb-0 space-x-3 rtl:space-x-reverse"
                            >
                                salesmarketinglovingshopz@gmail.com
                            </span>
                        </div>
                    </div>
                </div>
                <ul
                    class="flex flex-wrap items-center gap-4 mb-6 text-sm font-medium text-gray-500 sm:mb-0"
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
                        <li v-if="$page.props.auth.user.role_id == 1">
                            <Link
                                :href="route('dashboard')"
                                class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-green-700 md:p-0 md:dark:hover:text-green-500 md:dark:hover:bg-transparent"
                                >Dashboard
                            </Link>
                        </li>
                        <li>
                            <Link
                                method="post"
                                :href="route('logout')"
                                as="button"
                                class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-green-700 md:p-0 md:dark:hover:text-green-500 md:dark:hover:bg-transparent"
                                >Logout
                            </Link>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </footer>
</template>
