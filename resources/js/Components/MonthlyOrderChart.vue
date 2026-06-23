<script setup>
import Chart from "chart.js/auto";
import { ref, onMounted } from "vue";

const props = defineProps({
    data: Array,
});

const chart = ref(null);

onMounted(() => {
    new Chart(chart.value, {
        type: "bar",
        data: {
            labels: props.data.map((item) => item.month),
            datasets: [
                {
                    label: "Jumlah Order",
                    data: props.data.map((item) => item.total),
                    backgroundColor: "rgba(54,73,93,.5)",
                    borderColor: "#36495d",
                    borderWidth: 3,
                },
            ],
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                    },
                },
            },
        },
    });
});
</script>

<template>
    <canvas ref="chart"></canvas>
</template>
