<template>
    <app-layout title="Daftar Pesanan">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Daftar Pembayaran
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                    <div class="flex justify-between mb-2">
                        <div>
                            <div class="pt-2 relative mx-auto text-gray-600">
                                <div class="flex justify-between">
                                    <div>
                                        <div class="flex justify-start px-2">
                                            <div class="mr-2">
                                                <h6>Jenis Pembayaran</h6>
                                            </div>
                                            <div>
                                                <select>
                                                    <option>Semua</option>
                                                    <option>DP</option>
                                                    <option>Pelunasan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="allpayments.data.length>0">
                        <table class="table w-full">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 w-20">No.</th>
                                <th class="px-4 py-2">Customer</th>
                                <th class="px-4 py-2">Nominal</th>
                                <th class="px-4 py-2">Rekening Tujuan</th>
                                <th class="px-4 py-2">Diverifikasi Pada</th>
                                <th class="px-4 py-2">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, i) in allpayments.data">
                                <td class="border px-4 py-2">{{ i+1 }}</td>
                                <td class="border px-4 py-2">{{ row.booking.user.nama_depan+" "+row.booking.user.nama_belakang }}</td>
                                <td class="border px-4 py-2">{{ row.nominal }}</td>
                                <td class="border px-4 py-2">{{ row.bank_account.nomor_rekening }} ({{row.bank_account.nama_bank}})</td>
                                <td class="border px-4 py-2">{{ row.confirmed_at == null ? 'Belum dikonfirmasi' : row.confirmed_at  }}</td>
                                <td class="border px-4 py-2">
                                    <button
                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2">
                                        Detail
                                    </button>
                                    <button
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Verifikasi
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <pagination class="mt-6" :links="allpayments.links"/>
                    </div>
                    <div v-else>
                        <h5 class="text-gray-400 font-medium font-bold text-center"> Belum ada data</h5>
                    </div>
                </div>

            </div>
        </div>



    </app-layout>
</template>

<script>
import {defineComponent} from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
// import Welcome from '@/Jetstream/Welcome.vue'
import Pagination from "@/Shared/Pagination";
export default defineComponent({
    components: {
        AppLayout,
        Pagination
        // Welcome,
    },
    props: ['allpayments'],
    // data() {
    //     return {
    //         editMode: false,
    //         isOpen: false,
    //         form: {
    //             nama_bank: null,
    //             nomor_rekening: null,
    //             acc_holder:null
    //         },
    //     }
    // },
    // methods: {
    //
    // }
})
</script>
