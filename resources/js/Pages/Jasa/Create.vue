<template>
    <app-layout title="Vendor">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Buat Data Vendor
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form class="bg-white flex flex-col" @submit.prevent="save_product" enctype="multipart/form-data">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                        <div class="-mx-3 md:flex mb-4">
                            <div class="md:w-1/2 px-3 md:mb-0">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">
                                    Nama Vendor
                                </label>
                                <input
                                    class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                    v-model="form.nama" type="text" placeholder="Nama Vendor">
                            </div>
                            <div class="md:w-1/2 px-3 md:mb-0">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">
                                    Kategori Vendor
                                </label>
                                <select v-model="form.category_id"
                                        class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                        type="text" placeholder="Nama Vendor">
                                    <option v-for="kategori in allkategori" v-bind:value="kategori.id">
                                        {{ kategori.nama_kategori }}
                                    </option>
                                </select>
                            </div>

                        </div>
                        <div class="-mx-3 md:flex mb-4">
                            <div class="md:w-1/2 px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">
                                    Harga
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                      <span class="text-gray-500 sm:text-sm">
                                        Rp.
                                      </span>
                                    </div>
                                    <input v-model.lazy="form.harga" v-money="money" name="price" id="price"
                                           class="appearance-none block w-full pl-9 pr-12 sm:text-sm bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3"
                                           placeholder="Harga">
                                </div>
                            </div>
                            <div class="md:w-1/2 px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">
                                    Nominal DP
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                      <span class="text-gray-500 sm:text-sm">
                                        Rp.
                                      </span>
                                    </div>
                                    <input v-model="form.nominal_dp" v-money="money" name="price" id="dp"
                                           class="appearance-none block w-full pl-9 pr-12 sm:text-sm bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3"
                                           placeholder="Nominal DP (Deposit)">
                                </div>
                            </div>
                        </div>
                        <div class="-mx-3 md:flex mb-4">
                            <div class="md:w-full px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">
                                    Detail Vendor
                                </label>
                                <editor
                                    initialValue="<p>Initial editor content</p>"
                                    :init="{
                                      height: 250,
                                      menubar: false,
                                      plugins: [
                                        'advlist autolink lists link image charmap',
                                        'searchreplace visualblocks code fullscreen',
                                        'print preview anchor insertdatetime media',
                                        'paste code help wordcount table'
                                      ],
                                      toolbar:
                                        'undo redo | formatselect | bold italic | \
                                        alignleft aligncenter alignright | \
                                        bullist numlist outdent indent | help'
                                    }"
                                    v-model="form.deskripsi"
                                />
                                <!--                                <textarea v-model="form.deskripsi" class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" placeholder="Masukkan detail vendor"></textarea>-->
                            </div>
                        </div>
                        <div class="-mx-3 md:flex mb-4">
                            <div class="md:w-full px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">
                                    Cover Vendor
                                </label>
                                <input @change="singleupload($event)"
                                       class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                       type="file" placeholder="Cover Vendor">
                            </div>
                        </div>
                        <div class="-mx-3 md:flex mb-4">
                            <div class="md:w-full px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">
                                    Gambar Galeri (Bisa dipilih lebih dari satu)
                                </label>
                                <input @change="upload($event)"
                                       class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                       type="file" name="imageFile[]" multiple="multiple">
                                <div v-if="previewImages.length>0" class="flex flex-wrap justify-start">
                                    <div class="p-2 border-gray-400" v-for="img in previewImages">
                                        <div class="py-2">
                                            <button @click="removePic(img)" class="flex flex-row-reverse">
                                                <i class="fas fa-times text-red-500"></i>
                                            </button>
                                        </div>
                                        <img
                                            :src="img"
                                            alt="..."
                                            class="rounded border-none px-2" style="width: 150px"
                                        />
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="flex flex-row-reverse ...">
                            <div>
                                <!-- Using utilities: -->
                                <inertia-link :href="route('jasa.index')" class="mr-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Batal
                                </inertia-link>
                            </div>
                            <div class="mr-2">
                                <!-- Using utilities: -->
                                <button type="submit"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Simpan
                                </button>
                            </div>

                        </div>
                    </div>
                </form>
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

export default defineComponent({
    components: {
        AppLayout,
        editor: Editor
        // Welcome,
    },
    setup() {
        return {v$: useVuelidate()}
    },
    props: ['allkategori'],
    data: function () {
        return {
            form: {
                nama: null,
                harga: null,
                deskripsi: null,
                category_id: null,
                cover: null,
                image_posts: [],
                nominal_dp: null
            },
            previewImages: [],
            money: {
                decimal: ',',
                thousands: '.',
            }
        }
    },
    directives: {money: VMoney},
    validations() {
        return {
            form: {
                nama: {required},
                harga: {required},
                deskripsi: {required},
                category_id: {required},
            }
        }
    },
    methods: {
        upload(event) {
            let file = event.target.files[0];
            this.form.image_posts.push(file);
            // event.forEach(img=>{
            let reader = new FileReader
            reader.onload = e => {
                this.previewImages.push(e.target.result);
            }
            reader.readAsDataURL(file)
        },
        singleupload(event) {
            this.form.cover = event.target.files[0];
        },
        removePic(img) {
            var deletedImage = this.previewImages.indexOf(img);
            this.previewImages.splice(deletedImage, 1);
        },
        save_product() {
            let data = new FormData();
            Object.entries(this.form).forEach(([key, value]) => {
                if (key == 'image_posts') {
                    value.forEach(img => {
                        data.append('image_posts[]', img);
                    })
                } else {
                    data.append(key, value);
                }
            });
            this.$inertia.post('/jasa', data, {
                forceFormData: true,
            });
        }
    }
})
</script>
