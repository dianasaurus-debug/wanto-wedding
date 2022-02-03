<template>
    <app-layout title="Daftar Customer">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Daftar Customer
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                    <div class="flex justify-between ...">
                        <div>
                            <div class="pt-2 relative mx-auto text-gray-600">

                                <form @submit.prevent="searchData">
                                    <input
                                        class="border-2 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none"
                                        type="search" name="cari" placeholder="Search" v-model="query">

                                    <button type="submit" class="absolute right-0 top-0 mt-5 mr-4">
                                        <svg class="text-gray-600 h-4 w-4 fill-current"
                                             xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1"
                                             x="0px" y="0px"
                                             viewBox="0 0 56.966 56.966"
                                             style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve"
                                             width="512px" height="512px">
                                        <path
                                            d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z"/>
                                      </svg>
                                    </button>
                                </form>
                                <a @click="clearSearch" v-if="isSearching"
                                   class="my-2 text-red" style="cursor : pointer">
                                    Clear Search
                                </a>

                            </div>
                        </div>
                    </div>
                    <div v-if="all_users.data.length>0">
                        <table class="table-fixed w-full">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 w-20">No.</th>
                                <th class="px-4 py-2">Nama</th>
                                <th class="px-4 py-2">E-Mail</th>
                                <th class="px-4 py-2">Alamat</th>
                                <th class="px-4 py-2">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, i) in all_users.data">
                                <td class="border px-4 py-2">{{ i+1 }}</td>
                                <td class="border px-4 py-2">{{ row.nama_depan }} {{row.nama_belakang}}</td>
                                <td class="border px-4 py-2">{{ row.email}}</td>
                                <td class="border px-4 py-2">{{ row.alamat}}</td>
                                <td class="border px-4 py-2">
                                    <button @click="openModal(row)"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <pagination class="mt-6" :links="all_users.links"/>
                    </div>
                    <div v-else>
                        <h5 class="text-gray-400 font-medium font-bold text-center"> Belum ada data</h5>
                    </div>
                </div>

            </div>
        </div>
        <div class="fixed z-1000 inset-0 overflow-y-auto overflow-x-hidden ease-out duration-400" v-if="isOpen">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-full max-w-3xl" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="bg-white">
                    <div class="bg-white shadow-md rounded">
                        <table class="text-left w-full border-collapse"> <!--Border collapse doesn't work on this site yet but it's available in newer tailwind versions -->
                            <thead>
                            <tr>
                                <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Detail Customer</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="hover:bg-grey-lighter">
                                <td class="py-4 px-6 border-b border-grey-light font-bold bg-gray-50">Nama Customer</td>
                                <td class="py-4 px-6 border-b border-grey-light">
                                    {{ detail_user.nama_depan+" "+detail_user.nama_belakang }}
                                </td>
                            </tr>
                            <tr class="hover:bg-grey-lighter">
                                <td class="py-4 px-6 border-b border-grey-light font-bold bg-gray-50">E-Mail</td>
                                <td class="py-4 px-6 border-b border-grey-light">
                                    {{ detail_user.email }}
                                </td>
                            </tr>
                            <tr class="hover:bg-grey-lighter">
                                <td class="py-4 px-6 border-b border-grey-light font-bold bg-gray-50">Alamat</td>
                                <td class="py-4 px-6 border-b border-grey-light">
                                    {{ detail_user.alamat }}
                                </td>
                            </tr>
                            <tr class="hover:bg-grey-lighter">
                                <td class="py-4 px-6 border-b border-grey-light font-bold bg-gray-50">Daftar Booking</td>
                                <td class="py-4 px-6 border-b border-grey-light" v-if="detail_user.booking.length>0">
                                    <ul class="list-disc" v-for="booking in detail_user.booking">
                                        <li>Nama Vendor : {{booking.product.nama}}<br>Tanggal Mulai Booking : {{booking.start_booking}}<br>Status Booking : {{status_bookings[booking.status]}} </li>
                                    </ul>
                                </td>
                                <td class="py-4 px-6 border-b border-grey-light" v-else>
                                    Tidak ada data booking
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
    props: ['all_users', 'status_bookings'],
    created(){
        this.isSearching = !!new URLSearchParams(window.location.search).get('cari');
    },
    data() {
        return {
            editMode: false,
            isOpen: false,
            query: '',
            isSearching: false,
            detail_user : null,
        }
    },
    methods: {
        searchData: function () {
            this.$inertia.get(`/users?cari=${this.query}`)
        },
        openModal: function (user) {
            this.isOpen = true;
            this.detail_user = user;
        },
        closeModal: function () {
            this.isOpen = false;
            this.reset();
            this.editMode=false;
        },
        clearSearch: function () {
            this.query = '';
            this.$inertia.get(`users`)
        },
    }
})
</script>
