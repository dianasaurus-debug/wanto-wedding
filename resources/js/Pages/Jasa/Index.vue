<template>
    <app-layout title="Jasa">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manajemen Data Vendor
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
<!--                <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert" v-if="$page.flash.message">-->
<!--                    <div class="flex">-->
<!--                        <div>-->
<!--                            <p class="text-sm">{{ $page.flash.message }}</p>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                    <div class="flex justify-between ...">
                        <div>
                            <inertia-link :href="route('jasa.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">
                                Tambah Data
                            </inertia-link>
                        </div>

                        <!--                        <div>-->
                        <!--                            <button @click="clearSearch" v-if="isSearching"-->
                        <!--                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded my-3">-->
                        <!--                                Clear Search-->
                        <!--                            </button>-->
                        <!--                        </div>-->
                        <div>
                            <div class="my-3 pt-2 relative mx-auto text-gray-600">
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
                    <div v-if="alljasa.data.length>0">
                        <table class="table-fixed w-full">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 w-20">No.</th>
                                <th class="px-4 py-2">Nama Jasa</th>
                                <th class="px-4 py-2">Harga</th>
                                <th class="px-4 py-2">Dibuat Oleh</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, i) in alljasa.data">
                                <td class="border px-4 py-2">{{i+1 }}</td>
                                <td class="border px-4 py-2">{{ row.nama }}</td>
                                <td class="border px-4 py-2">{{ row.harga }}</td>
                                <td class="border px-4 py-2">{{ row.user.nama_depan }} {{row.user.nama_belakang}}</td>
                                <td class="border px-4 py-2">
                                    <inertia-link :href="route('jasa.edit', row.id)" class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded mr-2">
                                        Ubah Data
                                    </inertia-link>
                                    <button @click="deleteRow(row)"
                                        class="bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-2 px-4 rounded">Hapus
                                        Data
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <pagination class="mt-6" :links="alljasa.links"/>
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
import { defineComponent } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

// import Welcome from '@/Jetstream/Welcome.vue'
import Pagination from "@/Shared/Pagination";
export default defineComponent({
    components: {
        AppLayout,
        Pagination
        // Welcome,
    },
    created(){
        this.isSearching = !!new URLSearchParams(window.location.search).get('cari');
    },
    data(){
        return {
            query: '',
            isSearching: false,
        }
    },
    props: ['alljasa'],
    methods:{
        searchData: function () {
            this.$inertia.get(`/jasa?cari=${this.query}`)
        },
        clearSearch: function () {
            this.query = '';
            this.$inertia.get(`/jasa`)
        },
        deleteRow: function (data) {
            if (!confirm('Are you sure want to remove?')) return;
            data._method = 'DELETE';
            this.$inertia.post('/jasa/' + data.id, data)
            this.reset();
            this.closeModal();
        }
    }
})
</script>
