<template>
    <app-layout title="Manajemen Jadwal">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manajemen Jadwal
            </h2>
        </template>
        <div class="max-w-7xl mx-auto sm:p-6 lg:p-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-5">
                <div class="flex justify-between">
                    <div>
                        <button @click="openModal()"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">
                            Tambah Tanggal Off
                        </button>
                    </div>

                </div>
                <FullCalendar :options="calendarOptions"/>
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
                                           class="block text-gray-700 text-sm font-bold mb-2">Mulai tanggal Off:</label>
                                    <input type="datetime-local"
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                           placeholder="Masukkan tanggaal off awal" v-model="form.start_date">
                                </div>
                                <div class="mb-4">
                                    <label for="exampleFormControlInput1"
                                           class="block text-gray-700 text-sm font-bold mb-2">Berakhir tanggal
                                        Off:</label>
                                    <input type="datetime-local"
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                           placeholder="Masukkan tanggaal off akhir" v-model="form.end_date">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                              <button @click="tambah_date_off()" type="button"
                                      class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                Save
                              </button>
                            </span>
                            <!--                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">-->
                            <!--                              <button wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" v-show="editMode" @click="update(form)">-->
                            <!--                                Update-->
                            <!--                              </button>-->
                            <!--                            </span>-->
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
import '@fullcalendar/core/vdom' // solves problem with Vite
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import moment from 'moment'

export default defineComponent({
    components: {
        AppLayout,
        FullCalendar
    },
    props: ['jadwal'],
    created() {
        this.jadwal.jadwal_terpakai.forEach(data => {
            this.data_jadwal.push({
                title: `${data.booking.product.nama}`,
                start: data.start_booking,
                end: data.end_booking,
                extendedProps: {
                    jenis : data.status,
                    customer:`${data.booking.user.nama_depan} ${data.booking.user.nama_belakang}`,
                },
            })
            this.data_jadwal.push({
                title: `Jam mulai`,
                start: data.start_booking,
            })
            this.data_jadwal.push({
                title: `Jam berakhir`,
                start: data.end_booking,
            })
        })
        this.jadwal.jadwal_off.forEach(data => {
            this.data_jadwal.push({
                title: `Jadwal libur`,
                start: data.start_booking,
                end: data.end_booking,
                extendedProps: {
                    jenis : data.status,
                },
                color: 'grey'
            })
            this.data_jadwal.push({
                title: `Jam mulai`,
                start: data.start_booking,
            })
            this.data_jadwal.push({
                title: `Jam berakhir`,
                start: data.end_booking,
            })
        })

        this.calendarOptions.events = this.data_jadwal;
    },
    data() {
        return {
            isOpen: false,
            form: {
                start_date: null,
                end_date: null,
            },
            data_jadwal: [],
            calendarOptions: {
                plugins: [dayGridPlugin, interactionPlugin],
                initialView: 'dayGridMonth',
                nextDayThreshold: '00:00:00',
                eventColor: 'green',
                dateClick: this.handleDateClick,
                eventSources: {
                    events: this.data_jadwal,
                    color: 'yellow',
                    backgroundColor: 'red',
                    textColor: 'green',
                },
                eventClick: this.handleEventClick,
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                locale: 'id'
            }
        }
    },
    methods: {
        openModal: function () {
            this.isOpen = true;
        },
        closeModal: function () {
            this.isOpen = false;
        },
        handleDateClick: function (arg) {
            console.log(arg);
        },
        handleEventClick: function (info) {
            if(info.event.extendedProps.jenis==0){
                this.$swal.fire({
                    title: 'Jadwal libur (off)',
                    html : `<ul>
                            <li><b>Tanggal mulai</b> : ${moment(info.event.start).format('YYYY-MM-DD HH:mm')} </li>
                            <li><b>Tanggal Berakhir</b> : ${moment(info.event.end).format('YYYY-MM-DD HH:mm')}</li>
                        </ul>`,
                })
            } else {
                this.$swal.fire({
                    title: 'Detail event',
                    html : `<ul>
                            <li><b>Nama Vendor</b> : ${info.event.title}</li>
                            <li><b>Nama Customer</b> : ${info.event.extendedProps.customer}</li>
                            <li><b>Tanggal mulai</b> : ${moment(info.event.start).format('YYYY-MM-DD HH:mm')} </li>
                            <li><b>Tanggal Berakhir</b> : ${moment(info.event.end).format('YYYY-MM-DD HH:mm')}</li>
                        </ul>`,
                })
            }

        },
        tambah_date_off() {
            axios.post('/jadwal/tambah/off', this.form)
                .then(res => {
                    if (res.data.success == true) {
                        this.$swal.fire('Tanggal Off berhasil ditambahkan!', res.data.message, 'success');
                        this.$inertia.get('jadwal')
                    } else {
                        this.$swal.fire('Gagal ditambahkan!', res.data.message, 'error')
                    }
                })
        }
    }
})
</script>
