<script setup>
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";
import { router } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import CartItem from "@/Components/CartItem.vue";
import { computed, reactive, ref, watch } from "vue";
import { Float } from "@headlessui-float/vue";
import formatCurrency from "@/utils/formatCurrency";
import axios from "axios";

// const form = useForm({});

const cartItems = ref([]);

const cartItemsLoadingState = reactive({});
const cartItemErrors = reactive({});

const isLoading = computed(() =>
    Object.values(cartItemsLoadingState).some(
        (state) => state != null && state.length > 0
    )
);

const hasError = computed(() =>
    Object.values(cartItemErrors).some((error) => error)
);

const calculateTotalPrice = (data) => {
    return data.reduce((acc, curr) => {
        return acc + curr.product_price * curr.qty;
    }, 0);
};

const throttle = (cb, delay = 1000) => {
    let shouldWait = false;

    return () => {
        if (shouldWait === true) return;

        cb();
        shouldWait = true;

        setTimeout(() => {
            shouldWait = false;
        }, delay);
    };
};

const closeAllDetailsPopover = () => {
    cartItems.value.forEach((item) => {
        item.closeDetailsPopover();
    });
};

const handleScroll = throttle(closeAllDetailsPopover);

const handleCheckOut = async () => {
    axios
        .post(route("orders.checkout"))
        .then((res) => {
            window.snap.pay(res.data.snap_token);
        })
        .catch((err) => console.log(err.response.data.message));
};
</script>

<template>
    <Popover v-slot="{ open }">
        <Float
            placement="bottom-start"
            :offset="15"
            :shift="6"
            :flip="10"
            portal
            enter="transition duration-200 ease-out"
            enter-from="opacity-0 -translate-y-1"
            enter-to="opacity-100 translate-y-0"
            leave="transition duration-150 ease-in"
            leave-from="opacity-100 translate-y-0"
            leave-to="opacity-0 -translate-y-1"
        >
            <PopoverButton
                :class="open ? 'text-white' : 'text-white/90'"
                class="fixed bottom-24 shadow-md right-24 group inline-flex items-center rounded-md bg-green-700 px-3 py-2 text-base font-medium hover:text-white focus:outline-none z-10"
            >
                <div
                    v-badge="
                        $page.props.carts
                            .reduce((acc, curr) => {
                                return acc + curr.qty;
                            }, 0)
                            .toString()
                    "
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-6 h-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"
                        />
                    </svg>
                </div>
            </PopoverButton>

            <div class="h-full">
                <PopoverPanel
                    class="fixed z-20 bottom-40 right-24 max-h-[min(80vh,500px)] rounded-lg shadow-xl w-screen max-w-sm px-4 sm:px-0 overflow-y-scroll"
                    @scroll="handleScroll"
                    :focus="true"
                >
                    <div
                        class="relative grid gap-6 bg-white p-7 lg:grid-cols-1"
                    >
                        <CartItem
                            ref="cartItem"
                            v-for="item in $page.props.carts"
                            :key="item.name"
                            :item="item"
                            @load="
                                () => (cartItemsLoadingState[item.id] = true)
                            "
                            @finish="
                                () => (cartItemsLoadingState[item.id] = false)
                            "
                            @update:hasError="
                                (val) => (cartItemErrors[item.id] = val)
                            "
                        />
                    </div>

                    <div class="bg-gray-50 p-4 sticky bottom-0 inset-x-0">
                        <div
                            class="flex items-center justify-between rounded-md px-2 py-2 transition duration-150 ease-in-out focus:outline-none focus-visible:ring focus-visible:ring-orange-500/50"
                        >
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-900">
                                    Total harga
                                    {{ $page.props.snap_token }}
                                </span>
                                <span
                                    class="text-xl font-bold uppercase text-gray-900"
                                >
                                    {{
                                        formatCurrency(
                                            calculateTotalPrice(
                                                $page.props.carts
                                            )
                                        )
                                    }}
                                </span>
                            </div>
                            <span class="block text-sm text-gray-500">
                                <PrimaryButton
                                    class="px-2 py-2 disabled:opacity-25 disabled:cursor-not-allowed"
                                    :disabled="isLoading || hasError"
                                    @click="handleCheckOut"
                                >
                                    {{ isLoading ? "Loading..." : "Check Out" }}
                                </PrimaryButton>
                            </span>
                        </div>
                    </div>
                </PopoverPanel>
            </div>
        </Float>
    </Popover>
</template>
