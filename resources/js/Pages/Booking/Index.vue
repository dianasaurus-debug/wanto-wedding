<template>
    <app-layout title="Daftar Pesanan">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Daftar Pesanan
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                    <div class="flex justify-start mb-2">
                        <div>
                            <div class="pt-2 relative mx-auto text-gray-600">
                                <input
                                    class="border-2 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none"
                                    type="search" name="cari" placeholder="Search" v-model="form.search">

                                <a @click="reset"
                                   class="my-2 text-red" style="cursor : pointer">
                                    Clear Search
                                </a>
                            </div>
                        </div>
                        <div class="flex justify-start px-2">
                            <div class="mr-2">
                                <label>Kategori Vendor</label>
                                <select v-model="form.kategori">
                                    <option value="" selected>Semua</option>
                                    <option v-for="kategori in categories" v-bind:value="kategori.id">
                                        {{ kategori.nama_kategori }}
                                    </option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div v-if="allbookings.data.length>0">
                        <table class="table-fixed w-full">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 w-20">No.</th>
                                <th class="px-4 py-2">Vendor</th>
                                <th class="px-4 py-2">Customer</th>
                                <th class="px-4 py-2">Mulai</th>
                                <th class="px-4 py-2">Berakhir</th>
                                <th class="px-4 py-2">Action</th>
<!--                                <th class="px-4 py-2">Status Pembayaran</th>-->
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, i) in allbookings.data">
                                <td class="border px-4 py-2">{{ row.id }}</td>
                                <td class="border px-4 py-2">{{ row.product.nama }}</td>
                                <td class="border px-4 py-2">{{ row.user.nama_depan+" "+row.user.nama_belakang }}</td>
                                <td class="border px-4 py-2">{{ row.start_booking }}</td>
                                <td class="border px-4 py-2">{{ row.end_booking }}</td>
                                <td class="border px-4 py-2">
                                <button v-if="mauSelesai(row)"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2" @click="changeStatus(row.id)">
                                    {{ 'Selesaikan event'}}
                                </button>
                                </td>
                                <!--                                <td class="border px-4 py-2">{{ row.payment_id == null ? status_pembayaran[0] : (row.payment.confirmed_at == null ? status_pembayaran[1] :status_pembayaran[2] ) }}</td>-->

                            </tr>
                            </tbody>
                        </table>
                        <pagination class="mt-6" :links="allbookings.links"/>
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
import moment from "moment";
import throttle from "lodash/throttle";
import pickBy from "lodash/pickBy";
export default defineComponent({
    components: {
        AppLayout,
        Pagination
        // Welcome,
    },
    props: ['allbookings', 'filters', 'categories'],
    data() {
        return {
            status_pembayaran : [
                'Belum terbayar',
                'Belum diverifikasi',
                'Sudah diverifikasi',
            ],
            form: {
                search: this.filters.search,
                kategori: this.filters.kategori,
            },
        }
    },
    watch: {
        form: {
            deep: true,
            handler: throttle(function() {
                this.$inertia.get(this.route('booking.index'), pickBy(this.form), { preserveState: true })
            }, 150),
        },
    },
    methods: {
        mauSelesai(row){
            return row.status==4||row.status==6;
        },
        selesai(row){
            return row.status==7||row.status==8;
        },
        blmSelesai(row){
            return row.status>=0&&row.status<=3;
        },

        reset() {
            this.form = mapValues(this.form, () => null)
        },
        changeStatus(data){
            this.$swal.fire({
                title: 'Apakah Anda ingin merubah status event menjadi selesai?',
                showCancelButton: true,
                confirmButtonText: 'Iya',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    axios.put('/pembayaran/selesai/'+data)
                        .then(res=>{
                            if(res.data.success==true){
                                this.$swal.fire('Event Selesai!', res.data.message, 'success');
                                this.$inertia.get('booking')
                            } else {
                                this.$swal.fire('Gagal selesai!', res.data.message, 'error')
                            }
                        })
                } else if (result.isDenied) {
                    this.$swal.fire('Tidak jadi diselesaikan', '', 'info')
                }
            })
        }

    },
    // methods: {
    //
    // }
})
</script>
