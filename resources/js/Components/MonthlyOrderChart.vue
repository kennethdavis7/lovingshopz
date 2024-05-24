<script setup>
import Chart from "chart.js/auto";
import { ref, onMounted } from "vue";
const chart = ref(null);

onMounted(() => {
    new Chart(chart.value, {
        type: "bar",
        data: {
            labels: [
                "Januari",
                "Februari",
                "Maret",
                "April",
                "Mei",
                "Juni",
                "Juli",
                "Agustus",
                "September",
                "Oktober",
                "November",
                "Desember",
            ],
            datasets: [
                {
                    label: "Jumlah Order",
                    data: [0, 10, 1, 2, 20, 0, 0, 0, 0, 0, 0, 0],
                    backgroundColor: "rgba(54,73,93,.5)",
                    borderColor: "#36495d",
                    borderWidth: 3,
                },
                // {
                //     label: "Planetary Mass (relative to the Sun x 10^-6)",
                //     data: [
                //         0.166, 2.081, 3.003, 0.323, 954.792, 285.886, 43.662,
                //         51.514,
                //     ],
                //     backgroundColor: "rgba(71, 183,132,.5)",
                //     borderColor: "#47b784",
                //     borderWidth: 3,
                // },
            ],
        },
        options: {
            responsive: true,
            lineTension: 1,
            scales: {
                yAxes: [
                    {
                        ticks: {
                            beginAtZero: true,
                            padding: 25,
                        },
                    },
                ],
            },
        },
    });
});
</script>
<template>
    <canvas ref="chart"></canvas>
</template>
