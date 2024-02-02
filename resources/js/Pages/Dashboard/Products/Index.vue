<script setup>
import Sidebar from "../Partials/Sidebar.vue";
import Navbar from "../Partials/Navbar.vue";
import Search from "../Components/Search.vue";
import Dropdown from "../Components/Dropdown.vue";
</script>
<template>
    <Navbar />
    <Sidebar />

    <div class="p-4 sm:ml-64">
        <div
            class="p-4 border-2 border-gray-200 rounded-lg dark:border-gray-700 mt-20"
        >
            <div
                class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4"
            >
                <Dropdown />
                <Search />
            </div>
            <table
                class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
            >
                <thead
                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                >
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Image</th>
                        <th scope="col" class="px-6 py-3">Product name</th>
                        <th scope="col" class="px-6 py-3">Category</th>
                        <th scope="col" class="px-6 py-3">Quantity</th>
                        <th scope="col" class="px-6 py-3">Price</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                    >
                        <td class="px-6 py-4">1</td>
                        <td class="px-6 py-4"><img src="" alt="" /></td>

                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                        >
                            Apple MacBook Pro 17"
                        </th>
                        <td class="px-6 py-4">Laptop</td>
                        <td class="px-6 py-4">20</td>
                        <td class="px-6 py-4">$2999</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div
                                    class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"
                                ></div>
                                Active
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <a
                                href="#"
                                class="font-medium mr-4 text-blue-600 dark:text-blue-500 hover:underline"
                                >Edit</a
                            >
                            <a
                                href="#"
                                class="font-medium text-red-600 dark:text-red-500 hover:underline"
                                >Delete</a
                            >
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
