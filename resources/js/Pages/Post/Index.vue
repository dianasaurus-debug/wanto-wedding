<template>
    <app-layout title="Katalog">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Katalog Inspirasi Pernikahan
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                    <div class="flex justify-between ...">
                        <div>
                            <button @click="openModal()"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">
                                Tambah Data
                            </button>
                        </div>
                        <!--                        <div>-->
                        <!--                            <button @click="clearSearch" v-if="isSearching"-->
                        <!--                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded my-3">-->
                        <!--                                Clear Search-->
                        <!--                            </button>-->
                        <!--                        </div>-->
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
                    <div v-if="allpost.data.length>0" class="py-5">
                        <div class="mb-2 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-5">
                            <!--Card 1-->
                            <div v-for="row in allpost.data" class="rounded overflow-hidden shadow-lg">
                                <img class="w-full" :src="'images/uploads/katalog/'+row.cover" :alt="row.judul">
                                <div class="p-4">
                                    <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">{{row.tema_id!=null ? row.tema.nama_tema : 'Semua tema'}}</span>
                                    <div class="font-bold text-xl mb-2">{{ row.judul }}</div>
                                    <p class="text-gray-700 text-base">
                                        {{ row.isi }}
                                    </p>
                                </div>
                                <div class="px-6 pt-4 pb-2 flex flex-row-reverse">
                                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="edit(row)">
                                        Edit
                                    </button>
                                    <button @click="deleteRow(row)"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded  mr-2">Hapus
                                    </button>                            </div>
                            </div>
                            <!--Card 2-->

                        </div>
                        <pagination class="mt-6" :links="allpost.links"/>
                    </div>
                    <div v-else>
                        <h5 class="text-gray-400 font-medium font-bold text-center"> Belum ada data</h5>
                    </div>
                </div>

            </div>
        </div>
        <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-if="isOpen">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                <div class="fixed inset-0 transition-opacity">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                    role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                    <form>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="">
                                <div class="mb-4">
                                    <label for="exampleFormControlInput1"
                                           class="block text-gray-700 text-sm font-bold mb-2">Judul katalog:</label>
                                    <input type="text"
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                           id="exampleFormControlInput1" placeholder="Masukkan judul katalog"
                                           v-model="form.judul">
                                </div>
                                <div class="mb-4">
                                    <label for="exampleFormControlInput2"
                                           class="block text-gray-700 text-sm font-bold mb-2">Detail katalog:</label>
                                    <textarea
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        id="exampleFormControlInput2" v-model="form.isi"
                                        placeholder="Masukkan detail katalog"></textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="exampleFormControlInput2"
                                           class="block text-gray-700 text-sm font-bold mb-2">Kategori Adat (Pilih semua adat jika tidak memakai adat tertentu):</label>
                                    <select v-model="form.tema_id"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            type="text">
                                        <option v-bind:value=null>Pilih tema katalog</option>
                                        <option v-for="kategori in alltema" v-bind:value="kategori.id">
                                            {{ kategori.nama_tema }}
                                        </option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="exampleFormControlInput2"
                                           class="block text-gray-700 text-sm font-bold mb-2">Gambar katalog:</label>
                                    <input type="file"
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                           id="exampleFormControlInput1" placeholder="Masukkan cover katalog"
                                           @change="singleupload($event)">
                                    <img :src="previewImage"
                                         class="rounded border-none p-2" style="width: 150px"
                                         v-if="previewImage!=null"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                              <button wire:click.prevent="store()" type="button"
                                      class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                                      v-show="!editMode" @click="save(form)">
                                Save
                              </button>
                            </span>
                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                              <button wire:click.prevent="store()" type="button"
                                      class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                                      v-show="editMode" @click="update(form)">
                                Update
                              </button>
                            </span>
                            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">

                              <button @click="closeModal()" type="button"
                                      class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                Cancel
                              </button>
                            </span>
                        </div>
                    </form>

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
    props: ['allpost', 'allkategori', 'alltema'],
    created(){
        this.isSearching = !!new URLSearchParams(window.location.search).get('cari');
    },
    data() {
        return {
            editMode: false,
            isOpen: false,
            form: {
                judul: null,
                cover: null,
                isi: null,
                tema_id: null
            },
            previewImage: null,
            query: '',
            isSearching: false,
        }
    },
    methods: {
        searchData: function () {
            this.$inertia.get(`/post?cari=${this.query}`)
        },
        clearSearch: function () {
            this.query = '';
            this.$inertia.get(`/post`)
        },
        openModal: function () {
            this.isOpen = true;
        },
        closeModal: function () {
            this.isOpen = false;
            this.reset();
            this.editMode = false;
        },
        reset: function () {
            this.form = {
                    judul: null,
                    cover: null,
                    isi: null,
                    tema_id: null
            };
            this.previewImage = null;
        },
        save: function (data) {
            this.$inertia.post('/post', data)
            this.reset();
            this.closeModal();
            this.editMode = false;
        },
        edit: function (data) {
            this.form = Object.assign({}, data);
            this.editMode = true;
            this.openModal();
        },
        update: function (data) {
            data._method = 'PUT';
            this.$inertia.post('/post/' + data.id, data)
            this.reset();
            this.closeModal();
        },
        deleteRow: function (data) {
            if (!confirm('Are you sure want to remove?')) return;
            data._method = 'DELETE';
            this.$inertia.post('/post/' + data.id, data)
            this.reset();
            this.closeModal();
        },
        singleupload(event) {
            let file = event.target.files[0];
            this.form.cover = file;
            let reader = new FileReader
            reader.onload = e => {
                this.previewImage = e.target.result;
            }
            reader.readAsDataURL(file)

        },
    }
})
</script>
