<script setup>
import InputField from "@/Components/InputField.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import formatCurrency from "@/utils/formatCurrency";
import CartQtyInput from "@/Components/CartQtyInput.vue";
import { computed, ref, watch } from "vue";
import { useToast } from "vue-toastification";

const props = defineProps({
    product: Object,
    carts: Object,
});

const toast = useToast();

const existingQty = computed(() => {
    return (
        props.carts.find((item) => item.product_id === props.product.id)?.qty ??
        0
    );
});

const cart = useForm({
    qty: existingQty.value === props.product.stock ? 0 : 1,
    product_id: props.product.id,
});

const canAddToCart = computed(() => {
    const stock = props.product.stock === null ? 10000 : props.product.stock;
    return cart.qty > 0 && cart.qty + existingQty.value <= stock;
});

const changeQty = (val) => {
    cart.qty = Number(val);
};

const addCart = () => {
    cart.post(route("carts.store"), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast.success(
                `${props.product.name} telah ditambahkan ke keranjang`,
                {
                    timeout: 2000,
                    position: "bottom-center",
                }
            );
        },
    });
};

const hasError = ref(false);
const errorMessage = ref("");

watch(errorMessage, (val) => {
    if (val) {
        toast.error(val, {
            timeout: 2000,
            position: "bottom-center",
        });
    }
});

watch(
    [existingQty, () => props.product.stock],
    ([existingQtyVal, stockVal]) => {
        if (cart.qty + existingQtyVal > props.product.stock) {
            cart.qty = Math.max(stockVal - existingQtyVal, cart.qty);
        }
    }
);

const handlePayment = () => {};
</script>

<template>
    <footer
        class="fixed bottom-0 h-20 left-0 z-[10000] w-full px-24 mx-auto bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between dark:bg-gray-800 dark:border-gray-600"
    >
        <div class="flex gap-4 items-center">
            <img :src="'/' + props.product.images[0].url" alt="" width="50px" />
            <span>{{ props.product.name }}</span>
        </div>
        <div
            class="flex gap-5 flex-wrap items-center mt-3 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0"
        >
            <CartQtyInput
                :modelValue="cart.qty"
                @update:hasError="(val) => (hasError = val)"
                @update:errorMessage="(val) => (errorMessage = val)"
                :productId="props.product.id"
                :maxQty="
                    props.product.stock === null
                        ? 10000
                        : props.product.stock - existingQty
                "
                :minQty="canAddToCart ? props.product.min_order : 0"
                @handleQty="(val) => changeQty(val)"
                showErrorToast
            />

            <div class="price text-black">
                <span>Total harga</span>
                <h1 class="font-bold text-lg">
                    {{ formatCurrency(props.product.price * cart.qty) }}
                </h1>
            </div>

            <SecondaryButton
                class="p-3 disabled:opacity-25 disabled:cursor-not-allowed"
                type="button"
                @click="addCart"
                :disabled="!canAddToCart || hasError"
            >
                Tambah ke keranjang
            </SecondaryButton>

            <PrimaryButton
                class="p-3 disabled:opacity-25 disabled:cursor-not-allowed"
                type="button"
                :disabled="!canAddToCart || hasError"
                @click="handlePayment"
            >
                Beli sekarang
            </PrimaryButton>
        </div>
    </footer>
</template>
