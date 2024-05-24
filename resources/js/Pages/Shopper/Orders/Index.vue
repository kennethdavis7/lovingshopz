<script setup>
import { Head } from "@inertiajs/vue3";
import BoxShadow from "@/Components/BoxShadow.vue";
import formatCurrency from "@/utils/formatCurrency";
import Action from "@/Components/Action.vue";
import axios from "axios";

const props = defineProps({
    orders: Object,
});

const totalPrice = (orderItems) => {
    return orderItems.reduce((acc, orderItem) => {
        return acc + orderItem.total_price;
    }, 0);
};

const handleCancel = (orderId) => {
    axios
        .post(route("orders.cancel", orderId))
        .then((res) => console.log(res))
        .catch((err) => console.log(err));
};

const handleContinue = (snap_token) => {
    window.snap.pay(snap_token);
};
</script>
<template>
    <Head title="Orders" />
    <h1 class="font-bold text-4xl text-gray-800">Transaksi</h1>
    <BoxShadow>
        <table
            class="w-full text-sm mb-2 text-left rtl:text-right text-gray-500 overflow-x-scroll"
        >
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">No</th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">
                        Total Harga
                    </th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">
                        Metode Pembayaran
                    </th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">
                        Status Pembayaran
                    </th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">
                        Waktu Pembayaran
                    </th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody v-for="(order, idx) in props.orders" :key="order.id">
                <tr class="bg-white border-b items-center">
                    <td class="px-6 py-4">{{ idx + 1 }}</td>
                    <td class="px-6 py-4">
                        {{ formatCurrency(totalPrice(order.order_item)) }}
                    </td>
                    <td class="px-6 py-4">{{ order.payment_type }}</td>
                    <td class="px-6 py-4">{{ order.status }}</td>
                    <td class="px-6 py-4">
                        {{ order.transaction_time }}
                    </td>
                    <td class="px-6 py-4">
                        <div
                            class="flex items-center gap-4"
                            v-if="order.status !== 'settlement'"
                        >
                            <Action
                                class="text-blue-600"
                                @click="() => handleContinue(order.snap_token)"
                            >
                                Lanjut
                            </Action>
                            <Action
                                class="text-red-600"
                                @click="() => handleCancel(order.id)"
                            >
                                Cancel
                            </Action>
                        </div>
                        <div v-else>-</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </BoxShadow>
</template>
