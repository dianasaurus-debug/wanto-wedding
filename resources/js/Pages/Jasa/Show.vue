<template>
    <app-layout title="Detail Vendor">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Vendor
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <section class="text-gray-700 body-font overflow-hidden bg-white">
                    <div class="container px-5 py-24 mx-auto">
                        <div class="lg:w-4/5 mx-auto flex flex-wrap">
                            <img alt="ecommerce" class="lg:w-1/2 w-full object-cover object-center rounded border border-gray-200" :src="'/images/uploads/'+jasa.cover">
                            <div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">
                                <h1 class="text-gray-900 text-3xl title-font font-medium mb-1">{{jasa.nama}}</h1>
                                <div class="flex mb-4">
          <span class="flex items-center">
            Harga: <b>{{jasa.harga}}</b>
          </span>
           <span class="flex ml-3 pl-3 py-2 border-l-2 border-gray-200">
            DP: <b>{{jasa.nominal_dp}}</b>
          </span>
          <span class="flex ml-3 pl-3 py-2 border-l-2 border-gray-200">
            <div style="padding-top: 0.1em; padding-bottom: 0.1rem" class="text-sm px-3 bg-blue-200 text-white-800 rounded-full">{{jasa.category.nama_kategori}}</div>
          </span>
                                </div>
                                <p class="leading-relaxed">
                                    <span v-html="jasa.deskripsi"></span>
                                </p>
                                <h3 class="mt-6 mb-2"><b>GALERI</b></h3>
                                <div class="flex items-center pb-5 border-b-2 border-gray-200 mb-5">
                                    <carousel :items-to-show="1">
                                        <Slide v-for="(image, index) in images" :key="image.id">
                                            <img :src="'/images/uploads/'+image.url" />
                                        </Slide>

                                        <template #addons>
                                            <navigation />
                                            <pagination />
                                        </template>
                                    </carousel>
                                </div>
                                <div class="flex ml-auto justify-end">
                                    <button class="text-white bg-blue-500 border-0 py-2 px-6 focus:outline-none hover:bg-blue-600 rounded">Edit</button>
                                    <button class="text-white bg-red-500 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded ml-2">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </app-layout>
</template>

<script>
import {defineComponent} from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
// import Welcome from '@/Jetstream/Welcome.vue'
import useVuelidate from '@vuelidate/core'
import {required} from '@vuelidate/validators'
import {VMoney} from 'v-money';
import Editor from '@tinymce/tinymce-vue'
import 'vue3-carousel/dist/carousel.css';
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';

export default defineComponent({
    components: {
        AppLayout,
        editor: Editor,
        Carousel,
        Slide,
        Pagination,
        Navigation,
    },
    props : ['jasa'],
    created(){
        this.jasa.media.forEach(media=>{
            this.images.push({
                    id : media.id,
                    url : media.filename
            })
        })
    },
    data(){
        return {
            images : [],
        }
    }
})
</script>


