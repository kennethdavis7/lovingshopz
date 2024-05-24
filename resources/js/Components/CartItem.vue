<script setup>
import {
    Popover,
    PopoverButton,
    PopoverPanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
} from "@headlessui/vue";
import { useForm, Link, router } from "@inertiajs/vue3";
import InputField from "@/Components/InputField.vue";
import { onMounted, onUnmounted, ref, watch } from "vue";
import { Float } from "@headlessui-float/vue";
import formatCurrency from "@/utils/formatCurrency";
import CartQtyInput from "@/Components/CartQtyInput.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    item: Object,
});

const emit = defineEmits(["load", "finish", "update:hasError"]);

const cart = useForm({ qty: null });

const errorMessage = ref("");

let changeQtyTimeout = null;

const popoverCloseButton = ref(null);

const handleChangeQty = (qty) => {
    if (changeQtyTimeout !== null) {
        clearTimeout(changeQtyTimeout);
        changeQtyTimeout = null;
    }

    changeQtyTimeout = setTimeout(() => {
        emit("load");

        cart.qty = qty;
        cart.put(route("carts.update", props.item.id), {
            preserveScroll: true,
            preserveState: true,
            onFinish: () => {
                emit("finish");
            },
        });
    }, 250);
};

const closeDetailsPopover = () => {
    if (popoverCloseButton.value == null) return;
    popoverCloseButton.value.el.click();
};

defineExpose({
    closeDetailsPopover,
});

const deleteCart = (id) => {
    router.delete(route("carts.destroy", id), {
        preserveScroll: true,
        preserveState: true,
    });

    closeDetailsPopover();
};

const seeDetail = (id) => {
    router.get(route("products.show", id), {
        preserveScroll: true,
        preserveState: true,
    });
};

const handleUpdateErrorMessage = (val) => {
    errorMessage.value = val;
    // emit("update:errorMessage", val);
};

const handleUpdateHasError = (val) => {
    emit("update:hasError", val);
};
</script>

<template>
    <div class="flex justify-between items-center focus:outline-none">
        <div class="item flex w-11/12">
            <img
                :src="'/' + props.item.product_url"
                alt=""
                class="w-20 h-auto object-contain"
            />
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-900">
                    {{ props.item.product_name }}
                </p>

                <p class="text-sm font-medium text-gray-500">
                    {{ formatCurrency(props.item.product_price) }}
                </p>

                <div class="flex gap-4 items-center">
                    <span class="text-sm text-gray-500"> Qty: </span>
                    <CartQtyInput
                        :modelValue="item.qty"
                        @update:errorMessage="handleUpdateErrorMessage"
                        @update:hasError="handleUpdateHasError"
                        @handleQty="(val) => handleChangeQty(val)"
                        :maxQty="
                            item.product_stock === null
                                ? 10000
                                : item.product_stock
                        "
                        :minQty="item.product_min_order"
                    />
                </div>
                <InputError class="mt-2" :message="errorMessage" />
            </div>
        </div>

        <div class="actions flex justify-end gap-3 w-1/12">
            <Menu>
                <Float
                    :flip="10"
                    placement="bottom-start"
                    enter="transition duration-200 ease-out"
                    enter-from="opacity-0 -translate-y-1"
                    enter-to="opacity-100 translate-y-0"
                    leave="transition duration-150 ease-in"
                    leave-from="opacity-100 translate-y-0"
                    leave-to="opacity-0 -translate-y-1"
                >
                    <MenuButton>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            aria-hidden="true"
                            class="h-5 w-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"
                            ></path>
                        </svg>
                    </MenuButton>

                    <MenuItems
                        class="flex flex-col items-stretch bg-white border border-gray-200 rounded-md shadow-lg overflow-hidden focus:outline-none"
                    >
                        <MenuButton
                            class="h-0 w-0 m-0 p-0"
                            aria-hidden="true"
                            ref="popoverCloseButton"
                        />

                        <MenuItem v-slot="{ active }">
                            <button
                                @click.prevent="
                                    () => seeDetail(props.item.product_id)
                                "
                                :class="[
                                    'w-full pe-8 flex items-center gap-3 text-blue-600 p-2',
                                    active && 'bg-gray-100',
                                ]"
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
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                    />
                                </svg>
                                <span>Detail</span>
                            </button>
                        </MenuItem>
                        <MenuItem v-slot="{ active }">
                            <button
                                @click.prevent="() => deleteCart(props.item.id)"
                                type="button"
                                :class="[
                                    'w-full pe-8 flex gap-3 items-center text-red-600 p-2 cursor-pointer',
                                    active && 'bg-gray-100',
                                ]"
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
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                    />
                                </svg>
                                <span>Delete</span>
                            </button>
                        </MenuItem>
                    </MenuItems>
                </Float>
            </Menu>
        </div>
    </div>
</template>
