<template>
    <div>
        <Head :title="title"/>
        <div class="w-1/2 md:w-1/3 lg:w-64 fixed md:top-0 md:left-0 h-screen lg:block bg-white border-r"
             :class="sideBarOpen ? '' : 'hidden'" id="main-nav">

            <div class="w-full h-20 border-b flex px-4 items-center mb-8">
                <div class="pl-4">
                    <img :src="'/images/app_logo.jpg'" class="object-contain h-20 w-full">
                </div>
            </div>

            <div class="mb-4 px-4">
                <SideBarLink :active="route().current('dashboard')">
                    <i class="fas fa-tachometer-alt h-6 w-6 fill-current mr-2"></i>

                    <Link :href="route('dashboard')" class="text-gray-700">
                        Dashboard
                    </Link>
                </SideBarLink>
                <SideBarLink :active="route().current('jasa.index')">
                    <i class="fas fa-briefcase h-6 w-6 fill-current mr-2"></i>
                    <Link :href="route('jasa.index')" class="text-gray-700">
                        Vendor
                    </Link>
                </SideBarLink>

                <SideBarLink :active="route().current('booking.index')">
                    <i class="fas fa-folder-plus h-6 w-6 fill-current mr-2"></i>
                    <Link :href="route('booking.index')" class="text-gray-700">
                        Booking
                    </Link>
                </SideBarLink>
                <SideBarLink :active="route().current('pembayaran.index')">
                    <i class="fas fa-money-bill-wave h-6 w-6 fill-current mr-2"></i>
                    <Link :href="route('pembayaran.index')" class="text-gray-700">
                        Pembayaran
                    </Link>
                </SideBarLink>
                <SideBarLink :active="route().current('jadwal.index')">
                    <i class="fas fa-calendar-alt h-6 w-6 fill-current mr-2"></i>
                    <Link :href="route('jadwal.index')" class="text-gray-700">
                        Jadwal
                    </Link>
                </SideBarLink>
                <SideBarLink :active="route().current('post.index')">
                    <i class="fas fa-list h-6 w-6 fill-current mr-2"></i>
                    <Link :href="route('post.index')" class="text-gray-700">
                        Katalog
                    </Link>
                </SideBarLink>
                <SideBarLink :active="route().current('akun-bank.index')">
                    <i class="fas fa-money-check h-6 w-6 fill-current mr-2"></i>
                    <Link :href="route('akun-bank.index')" class="text-gray-700">
                        Akun Bank
                    </Link>
                </SideBarLink>
                <SideBarLink :active="route().current('kategori-paket.index')">
                    <i class="fas fa-list h-6 w-6 fill-current mr-2"></i>
                    <Link :href="route('kategori-paket.index')" class="text-gray-700">
                        Kategori Paket
                    </Link>
                </SideBarLink>
                <SideBarLink :active="route().current('kategori-tema.index')">
                    <i class="fas fa-money-check h-6 w-6 fill-current mr-2"></i>
                    <Link :href="route('kategori-tema.index')" class="text-gray-700">
                        Kategori Tema
                    </Link>
                </SideBarLink>
            </div>

        </div>

        <jet-banner/>

        <div class="min-h-screen bg-gray-100 pl-0 lg:pl-64" :class="sideBarOpen ? 'overlay' : ''" id="main-content">
            <div class="sticky top-0">
                <div class="w-full h-20 px-6 bg-white border-b flex items-center justify-between">

                    <!-- left navbar -->
                    <div class="flex">

                        <!-- mobile hamburger -->
                        <div class="inline-block lg:hidden flex items-center mr-4">
                            <button class="hover:text-blue-500 hover:border-white focus:outline-none navbar-burger"
                                    @click="sideBarOpen = !sideBarOpen">
                                <svg class="h-5 w-5" v-bind:style="{ fill: 'black' }" viewBox="0 0 20 20"
                                     xmlns="http://www.w3.org/2000/svg"><title>Menu</title>
                                    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/>
                                </svg>
                            </button>
                        </div>

                        <!-- search bar -->
                        <div class="relative text-gray-600">
                            <input type="search" name="serch" placeholder="Search products..."
                                   class="bg-white h-10 w-full xl:w-64 px-5 rounded-lg border text-sm focus:outline-none">
                            <button type="submit" class="absolute right-0 top-0 mt-3 mr-4">
                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                     xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px"
                                     y="0px" viewBox="0 0 56.966 56.966"
                                     style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve" width="512px"
                                     height="512px">
                      <path
                          d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z"/>
                    </svg>
                            </button>
                        </div>
                    </div>

                    <!-- right navbar -->
                    <div class="flex items-center relative">
                        <!--                                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24" class="fill-current mr-3 hover:text-blue-500"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z"/></svg>-->
                        <!--                                <img src="https://a7sas.net/wp-content/uploads/2019/07/4060.jpeg" class="w-12 h-12 rounded-full shadow-lg" @click="dropDownOpen = !dropDownOpen">-->
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"
                             class="fill-current mr-3 hover:text-blue-500">
                            <path d="M0 0h24v24H0z" fill="none"/>
                            <path
                                d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z"/>
                        </svg>
                        <button type="button"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition"
                                @click="dropDownOpen = !dropDownOpen">
                            {{ $page.props.user.nama_depan }}

                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>


                </div>

                <!-- dropdown menu -->
                <div
                    class="absolute bg-gray-100 border border-t-0 shadow-xl text-gray-700 rounded-b-lg w-48 right-0 mr-6"
                    :class="dropDownOpen ? '' : 'hidden'">
                    <!--                            <a href="#" class="block px-4 py-2 hover:bg-gray-200">Profil</a>-->
                    <jet-responsive-nav-link :href="route('profile.show')"
                                             :active="route().current('profile.show')">
                        Profile
                    </jet-responsive-nav-link>
                    <!--                            <a href="#" class="block px-4 py-2 hover:bg-gray-200">Settings</a>-->
                    <form method="POST" @submit.prevent="logout">
                        <jet-responsive-nav-link as="button">
                            Log Out
                        </jet-responsive-nav-link>
                    </form>
                </div>
                <!-- dropdown menu end -->

            </div>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header"></slot>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot></slot>
            </main>
        </div>
    </div>
    <!--        </div>-->
    <!--    </div>-->
</template>

<script>
import {defineComponent} from 'vue'
import JetApplicationMark from '@/Jetstream/ApplicationMark.vue'
import JetBanner from '@/Jetstream/Banner.vue'
import JetDropdown from '@/Jetstream/Dropdown.vue'
import JetDropdownLink from '@/Jetstream/DropdownLink.vue'
import JetNavLink from '@/Jetstream/NavLink.vue'
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue'
import {Head, Link} from '@inertiajs/inertia-vue3';
import Sidebar from "@/Shared/Sidebar";
import Navbar from "@/Shared/Navbar";
import SideBarLink from "@/Shared/SideBarLink";

export default defineComponent({
    props: {
        title: String,
    },

    components: {
        SideBarLink,
        Navbar,
        Sidebar,
        Head,
        JetApplicationMark,
        JetBanner,
        JetDropdown,
        JetDropdownLink,
        JetNavLink,
        JetResponsiveNavLink,
        Link,
    },

    data() {
        return {
            showingNavigationDropdown: false,
            sideBarOpen: false,
            dropDownOpen: false
        }
    },

    methods: {
        // toggleSidebar() {
        //     this.sideBarOpen = true;
        // },
        switchToTeam(team) {
            this.$inertia.put(route('current-team.update'), {
                'team_id': team.id
            }, {
                preserveState: false
            })
        },

        logout() {
            this.$inertia.post(route('logout'));
        },
    }
})
</script>
