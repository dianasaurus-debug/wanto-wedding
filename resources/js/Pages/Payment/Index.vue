<template>
    <app-layout title="Daftar Pembayaran">
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
                                <div class="flex justify-start">
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
                                                <h6>Jenis</h6>
                                            </div>
                                            <div>
                                                <select v-model="form.jenis_pembayaran">
                                                    <option value="" selected>Semua</option>
                                                    <option value="2">DP</option>
                                                    <option value="1">Pelunasan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="flex justify-start px-2">
                                        <div class="mr-2">
                                            <h6>Status</h6>
                                        </div>
                                        <div>
                                            <select v-model="form.status_pembayaran">
                                                <option value="" selected>Semua</option>
                                                <option value="2">Belum diverifikasi</option>
                                                <option value="1">Sudah diverifikasi</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="allpayments.data.length>0" class="overflow-x-auto">
                        <table class="table w-full p-2 m-1">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 w-20">No.</th>
                                <th class="px-4 py-2">Customer</th>
                                <th class="px-4 py-2">Nominal</th>
                                <th class="px-4 py-2">Rekening Tujuan</th>
                                <th class="px-4 py-2">Nama Vendor</th>
                                <th class="px-4 py-2">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, i) in allpayments.data">
                                <td class="border px-4 py-2">{{ i+1 }}</td>
                                <td class="border px-4 py-2">{{ row.booking.user.nama_depan+" "+row.booking.user.nama_belakang }}</td>
                                <td class="border px-4 py-2">{{ row.nominal }}</td>
                                <td class="border px-4 py-2">{{ row.bank_account.nomor_rekening }} ({{row.bank_account.nama_bank}})</td>
                                <td class="border px-4 py-2">{{ row.booking.product.nama  }}</td>
                                <td class="border px-4 py-2">
                                    <button
                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2" @click="openModal(row)">
                                        Detail
                                    </button>
                                    <button
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" v-if="row.status_pembayaran == 2" @click="verifikasi(row)">
                                        Verifikasi
                                    </button>
                                    <button
                                        class="bg-blue-500 text-white font-bold py-2 px-4 rounded" v-if="row.status_pembayaran == 1" disabled>
                                        Sudah diverifikasi
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
        <div class="fixed z-1000 inset-0 overflow-y-auto ease-out duration-400" v-if="isOpen">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                <div class="fixed inset-0 transition-opacity">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                        <div class="bg-white">
                                <div class="bg-white shadow-md rounded">
                                    <table class="text-left w-full border-collapse"> <!--Border collapse doesn't work on this site yet but it's available in newer tailwind versions -->
                                        <thead>
                                        <tr>
                                            <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Detail Pembayaran</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="hover:bg-grey-lighter">
                                            <td class="py-4 px-6 border-b border-grey-light font-bold bg-gray-50">Nama Customer</td>
                                            <td class="py-4 px-6 border-b border-grey-light">
                                                {{ detail_pembayaran.booking.user.nama_depan+" "+detail_pembayaran.booking.user.nama_belakang }}
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-grey-lighter">
                                            <td class="py-4 px-6 border-b border-grey-light font-bold bg-gray-50">Vendor</td>
                                            <td class="py-4 px-6 border-b border-grey-light">
                                                {{ detail_pembayaran.booking.product.nama }}
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-grey-lighter">
                                            <td class="py-4 px-6 border-b border-grey-light font-bold bg-gray-50">Jenis Pembayaran</td>
                                            <td class="py-4 px-6 border-b border-grey-light">
                                                {{ detail_pembayaran.jenis_pembayaran == 2 ? 'DP' : 'Pelunasan' }}
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-grey-lighter">
                                            <td class="py-4 px-6 border-b border-grey-light font-bold bg-gray-50">Rekening Tujuan</td>
                                            <td class="py-4 px-6 border-b border-grey-light">
                                                {{ detail_pembayaran.bank_account.nomor_rekening }} ({{detail_pembayaran.bank_account.nama_bank}})
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-grey-lighter">
                                            <td class="py-4 px-6 border-b border-grey-light font-bold bg-gray-50">Status Pembayaran</td>
                                            <td class="py-4 px-6 border-b border-grey-light">
                                                {{ detail_pembayaran.status_pembayaran == 2 ? 'Belum diverifikasi' : `Sudah diverifikasi (Pada ${detail_pembayaran.confirmed_at})` }}
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-grey-lighter">
                                            <td class="py-4 px-6 border-b border-grey-light font-bold bg-gray-50">Bukti Pembayaran</td>
                                            <td class="py-4 px-6 border-b border-grey-light">
                                                <img :src="'images/bukti_pembayaran/'+detail_pembayaran.bukti_pembayaran" class="object-none h-48 w-full" v-if="detail_pembayaran.bukti_pembayaran!=null">
                                                <p v-else>Belum diunggah</p>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                              <button @click="closeModal()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                Tutup
                              </button>
                            </span>
                            <span class="flex w-full rounded-md shadow-sm sm:w-auto" v-if="detail_pembayaran.status_pembayaran==2">
                              <button type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" v-show="!editMode" @click="save(form)">
                                Verifikasi Pembayaran
                              </button>
                            </span>
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
import throttle from "lodash/throttle";
import pickBy from "lodash/pickBy";
import mapValues from "lodash/mapValues";
export default defineComponent({
    components: {
        AppLayout,
        Pagination
        // Welcome,
    },
    props: ['allpayments', 'filters'],
    data() {
        return {
            isOpen: false,
            category : 2,
            status : 2,
            detail_pembayaran : '',
            query: '',
            isSearching: false,
            form: {
                search: this.filters.search,
                jenis_pembayaran: this.filters.jenis_pembayaran,
                status_pembayaran: this.filters.status_pembayaran,
            },
        }
    },
    watch: {
        form: {
            deep: true,
            handler: throttle(function() {
                this.$inertia.get(this.route('pembayaran.index'), pickBy(this.form), { preserveState: true })
            }, 150),
        },
    },
    methods: {
        showAlert() {
            // Use sweetalert2
            this.$swal('Hello Vue world!!!');
        },
        openModal: function (data) {
            this.isOpen = true;
            this.detail_pembayaran = data;
        },
        closeModal: function () {
            this.isOpen = false;
            this.editMode=false;
        },
        reset() {
            this.form = mapValues(this.form, () => null)
        },
        verifikasi(data){
            let jenis_pembayaran = data.jenis_pembayaran == 2 ? 'DP' : 'Pelunasan';
            this.$swal.fire({
                title: 'Apakah Anda ingin memverifikasi tagihan?',
                showDenyButton: true,
                showCancelButton: true,
                html : `<ul>
                    <li><b>Nama Customer</b> : ${data.booking.user.nama_depan} ${data.booking.user.nama_belakang} </li>
                    <li><b>Nama Vendor</b> : ${data.booking.product.nama}</li>
                    <li><b>Nominal</b> : ${data.nominal} </li>
                    <li><b>Jenis Pembayaran</b> : ${jenis_pembayaran}</li>
                </ul>`,
                confirmButtonText: 'Verifikasi',
                denyButtonText: `Don't save`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    axios.put('/pembayaran/'+data.id)
                    .then(res=>{
                        if(res.data.success==true){
                            this.$swal.fire('Terverifikasi!', res.data.message, 'success');
                            this.$inertia.get('pembayaran')
                        } else {
                            this.$swal.fire('Gagal diverifikasi!', res.data.message, 'error')
                        }
                    })
                } else if (result.isDenied) {
                    this.$swal.fire('Tidak jadi diverifikasi', '', 'info')
                }
            })
        }
    },

});
</script>
