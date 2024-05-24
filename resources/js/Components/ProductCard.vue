<script setup>
import { Link, router, useForm } from "@inertiajs/vue3";
import formatCurrency from "@/utils/formatCurrency";
import { useToast } from "vue-toastification";

const props = defineProps(["data"]);

const cart = useForm({
    qty: props.data.min_order,
    product_id: props.data.id,
});

const toast = useToast();

const removeTags = (str) => {
    if (str === null || str === "") return false;
    else str = str.toString();
    return str.replace(/(<([^>]+)>)/gi, "");
};

const limitString = (str) => {
    const isLong = str.length > 100 ? true : false;
    if (isLong) str = str.substring(0, 100) + "...";
    return str;
};

const seeDetail = (id) => {
    router.get(route("products.show", id));
};

const addCart = () => {
    cart.post(route("carts.store"), {
        preserveScroll: true,
        preserveState: true,
        onError: (err) => {
            toast.error(err.qty, {
                timeout: 2000,
            });
        },
        onSuccess: () => {
            toast.success(`${props.data.name} telah dimasukkan ke keranjang`, {
                timeout: 2000,
            });
        },
    });
};
</script>
<template>
    <div class="bg-white max-w-64 border border-gray-200 rounded-lg shadow-md">
        <Link :href="route('products.show', props.data.id)">
            <img
                class="rounded-t-lg object-cover h-56 w-full"
                :src="'/' + props.data.images[0].url"
                :alt="props.data.images[0].alt"
            />
        </Link>
        <div class="px-5 pb-5 w-full h-96 flex flex-col justify-between">
            <div class="content">
                <h5
                    class="text-xl font-semibold tracking-tight text-gray-900 mt-3"
                >
                    {{ props.data.name }}
                </h5>

                <div class="w-full my-5">
                    <span class="block text-lg font-bold text-green-900">{{
                        formatCurrency(props.data.price)
                    }}</span>
                    <span class="text-md">{{
                        limitString(removeTags(props.data.description))
                    }}</span>
                </div>
                <div
                    class="flex gap-3 justify-between items-center w-full mb-2"
                >
                    <span class="text-gray-600 text-sm">
                        Min Order: {{ props.data.min_order }}
                    </span>
                    <span
                        v-if="!(props.data.stock === null)"
                        class="text-gray-600 text-sm"
                    >
                        Stock: <span>{{ props.data.stock }}</span>
                    </span>
                </div>
            </div>
            <div class="actions flex justify-between gap-4 items-center">
                <button
                    @click="() => seeDetail(props.data.id)"
                    class="whitespace-nowrap text-white w-full bg-green-700 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                >
                    Lihat Detail
                </button>
                <button
                    v-if="$page.props.auth.user?.role_id == 2"
                    @click="() => addCart()"
                    class="flex justify-center items-center text-green-600 w-1/4 border border-green-600 hover:border-white hover:bg-green-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-5 h-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>
