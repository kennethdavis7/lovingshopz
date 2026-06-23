<script setup>
import { Head } from "@inertiajs/vue3";
import BoxShadow from "@/Components/BoxShadow.vue";
import MonthlyOrderChart from "@/Components/MonthlyOrderChart.vue";
import Select from "@/Components/Select.vue";
import formatCurrency from "@/utils/formatCurrency";

const props = defineProps({
    total_published_products: Number,
    total_unpublished_products: Number,
    total_active_users: Number,
    total_penghasilan: Number,
    total_orders: Number,
    monthly_orders: Array,
});

// const handleChangeChartDate = () => {};
</script>
<template>
    <Head title="Dashboard" />
    <h1 class="font-bold text-4xl text-gray-800">Dashboard</h1>
    <div class="w-full">
        <div class="flex w-full gap-4">
            <BoxShadow class="w-2/4 flex flex-col justify-between">
                <h2 class="text-md text-gray-800 mb-2">Total Penghasilan</h2>
                <span class="text-sm text-green-700 mb-5"
                    >{{ props.total_orders }} Order</span
                >
                <span class="font-bold text-2xl text-gray-800"
                    >{{ formatCurrency(props.total_penghasilan) }}
                </span>
            </BoxShadow>
            <BoxShadow class="w-2/4 flex flex-col justify-between">
                <h2 class="text-md text-gray-800 mb-2">
                    Jumlah Pengunjung yang Sedang Aktif
                </h2>
                <span class="font-bold text-2xl text-gray-800">
                    {{ props.total_active_users }}
                </span>
            </BoxShadow>
            <BoxShadow class="w-2/4 flex flex-col justify-between">
                <h2 class="text-md text-gray-800 mb-2">
                    Jumlah Item Terpublikasi
                </h2>
                <span class="font-bold text-2xl text-gray-800">
                    {{ props.total_published_products }}
                </span>
            </BoxShadow>
            <BoxShadow class="w-2/4 flex flex-col justify-between">
                <h2 class="text-md text-gray-800 mb-2">
                    Jumlah Item Belum Terpublikasi
                </h2>
                <span class="font-bold text-2xl text-gray-800">
                    {{ props.total_unpublished_products }}
                </span>
            </BoxShadow>
        </div>
        <div class="flex flex-col items-stretch mt-10">
            <div class="flex justify-between items-center gap-4">
                <h2 class="text-3xl font-bold">Total Order</h2>

                <!-- <Select
                    model-value="Bulan ini"
                    @update:model-value="handleChangeChartDate"
                    :data="
                        ['Bulan ini', 'Tahun ini', 'Setiap Tahun'].map(
                            (el) => ({
                                name: el,
                                id: el,
                            }),
                        )
                    "
                    buttonClass="py-2"
                /> -->
            </div>
            <MonthlyOrderChart class="mt-5" :data="props.monthly_orders" />
        </div>
    </div>
    <!-- <div class="w-4/12">
            <BoxShadow class="flex flex-col justify-between">
                <h2 class="text-md text-gray-800 mb-2">Produk yang Diminati</h2>
                <div class="font-bold text-gray-800">
                    <ol class="px-5 list-decimal">
                        <li>Buku</li>
                        <li>Pensil</li>
                        <li>Penghapus</li>
                        <li>Kertas</li>
                    </ol>
                </div>
            </BoxShadow>
            <BoxShadow class="flex flex-col justify-between">
                <h2 class="text-md text-gray-800 mb-2">
                    Produk yang Tidak Diminati
                </h2>
                <div class="px-5 font-bold text-gray-800">
                    <ol class="list-decimal">
                        <li>Plastik</li>
                        <li>Lem Uhu</li>
                        <li>Baterai</li>
                        <li>Stempel</li>
                        <li>Tas Sekolah</li>
                    </ol>
                </div>
            </BoxShadow>
        </div> -->
    <!-- </div> -->
</template>
