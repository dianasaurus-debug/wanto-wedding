<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex flex-wrap">
                        <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                            <!--Metric Card-->
                            <div class="bg-white border-b-4 border-blue-600 rounded-lg shadow-xl p-5">
                                <div class="flex flex-row items-center">
                                    <div class="flex-shrink pr-4">
                                        <div class="rounded-full p-5 bg-blue-600">
                                            <i class="fas fa-briefcase fa-2x fa-inverse"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 text-right md:text-center">
                                        <h5 class="font-bold uppercase text-gray-600">Total Vendor</h5>
                                        <h3 class="font-bold text-3xl">10</h3>
                                    </div>
                                </div>
                            </div>
                            <!--/Metric Card-->
                        </div>
                        <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                            <!--Metric Card-->
                            <div class="bg-white border-b-4 border-blue-600 rounded-lg shadow-xl p-5">
                                <div class="flex flex-row items-center">
                                    <div class="flex-shrink pr-4">
                                        <div class="rounded-full p-5 bg-blue-600">
                                            <i class="fas fa-list fa-2x fa-inverse"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 text-right md:text-center">
                                        <h5 class="font-bold uppercase text-gray-600">Total Katalog</h5>
                                        <h3 class="font-bold text-3xl">10</h3>
                                    </div>
                                </div>
                            </div>
                            <!--/Metric Card-->
                        </div>
                        <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                            <!--Metric Card-->
                            <div class="bg-white border-b-4 border-blue-600 rounded-lg shadow-xl p-5">
                                <div class="flex flex-row items-center">
                                    <div class="flex-shrink pr-4">
                                        <div class="rounded-full p-5 bg-blue-600">
                                            <i class="fas fa-folder-plus fa-2x fa-inverse"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 text-right md:text-center">
                                        <h5 class="font-bold uppercase text-gray-600">Total Pesanan</h5>
                                        <h3 class="font-bold text-3xl">10</h3>
                                    </div>
                                </div>
                            </div>
                            <!--/Metric Card-->
                        </div>

                    </div>
                    <div class="p-5 flex justify-between">
                        <apexchart
                            width="500"
                            type="bar"
                            :options="chartOptionsOrder"
                            :series="seriesOrder"
                        ></apexchart>
                        <apexchart
                            width="500"
                            type="bar"
                            :options="chartOptionsBayar"
                            :series="seriesBayar"
                        ></apexchart>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import Welcome from '@/Jetstream/Welcome.vue'
    import VueApexCharts from "vue3-apexcharts";

    export default defineComponent({
        components: {
            AppLayout,
            Welcome,
            apexchart: VueApexCharts,
        },
        data: function() {
            return {
                chartOptionsOrder: {
                    title : {
                      text : "Grafik Jumlah Order Per Bulan"
                    },
                    chart: {
                        id: "chart-jumlah-order",
                    },
                    xaxis: {
                        categories: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agust", "Sep", "Okt", "Nov"],
                    },
                },
                chartOptionsBayar: {
                    title : {
                        text : "Grafik Jumlah Pendapatan Per Bulan"
                    },
                    chart: {
                        id: "chart-jumlah-bayar",
                    },
                    xaxis: {
                        categories: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agust", "Sep", "Okt", "Nov"],
                    },
                },
                seriesOrder: [
                    {
                        name: "Jumlah Pemesanan",
                        data: [30, 40, 35, 50, 49, 60, 70, 91, 49, 60, 70, 91],
                    },
                ],
                seriesBayar: [
                    {
                        name: "Jumlah Pendapatan (Juta)",
                        data: [30, 40, 35, 50, 49, 60, 70, 91, 49, 60, 70, 91],
                    },
                ],
            };
        },
    })
</script>
