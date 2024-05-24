<script setup>
import { Link } from "@inertiajs/vue3";
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import Logo from "@/Components/Logo.vue";
const props = defineProps(["active"]);

// const handleClick = () => {
//     console.log("fasjkfajsklfj");
// };
</script>
<template>
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start rtl:justify-end">
                    <Logo imageClass="h-12 me-3" />
                </div>

                <div class="flex items-center">
                    <div class="flex items-center ms-3">
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
                                            class="w-8 h-8 rounded-full"
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
                                    class="absolute right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
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
                                                Profile
                                            </Link>
                                        </MenuItem>
                                        <MenuItem v-slot="{ active }">
                                            <Link
                                                method="post"
                                                :href="route('logout')"
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
                                            </Link>
                                        </MenuItem>
                                    </div>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>
