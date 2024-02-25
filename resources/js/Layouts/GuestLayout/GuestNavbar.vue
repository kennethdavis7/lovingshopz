<script setup>
import { Link } from "@inertiajs/vue3";
</script>
<template>
    <nav class="bg-white border-gray-200">
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
        <div
            class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4"
        >
            <div
                class="text-sm text-gray-500 sm:text-center flex items-center mb-4 sm:mb-0 space-x-3 rtl:space-x-reverse"
            >
                <a href="https://flowbite.com/">
                    <img
                        src="../../../img/logo.png"
                        class="w-44"
                        alt="Lovingshopz Logo"
                    />
                </a>
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
            <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                <ul
                    class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white md:dark:bg-gray-900"
                >
                    <li>
                        <a
                            href="http://127.0.0.1:8000"
                            class="block py-2 px-3 text-white bg-green-700 rounded md:bg-transparent md:text-green-700 md:p-0 md:dark:text-green-500"
                            aria-current="page"
                            >Home</a
                        >
                    </li>
                    <li>
                        <a
                            href="http://127.0.0.1:8000/about"
                            class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-green-700 md:p-0 md:dark:hover:text-green-500 md:dark:hover:bg-transparent"
                            >About</a
                        >
                    </li>
                    <li>
                        <Link
                            :href="route('login.user')"
                            class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-green-700 md:p-0 md:dark:hover:text-green-500 md:dark:hover:bg-transparent"
                            >Login
                        </Link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <nav class="bg-gray-50 mb-10">
        <div class="w-full max-w-screen-xl px-4 py-5 mx-auto">
            <div class="flex items-center">
                <ul
                    class="flex flex-row font-medium mt-0 space-x-8 rtl:space-x-reverse text-sm"
                >
                    <li>
                        <a
                            href="#"
                            class="text-gray-900 hover:underline"
                            aria-current="page"
                            >Pulpen</a
                        >
                    </li>
                    <li>
                        <a href="#" class="text-gray-900 hover:underline"
                            >Printer</a
                        >
                    </li>
                    <li>
                        <a href="#" class="text-gray-900 hover:underline"
                            >Pensil</a
                        >
                    </li>
                    <li>
                        <a href="#" class="text-gray-900 hover:underline"
                            >Penghapus</a
                        >
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
