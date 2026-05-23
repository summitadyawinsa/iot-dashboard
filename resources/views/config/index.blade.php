<x-layout.default>
    <link rel='stylesheet' type='text/css' href='{{ Vite::asset('resources/css/nice-select2.css') }}'>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/flatpickr.min.css') }}">
    <script src="/assets/js/flatpickr.js"></script>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/nouislider.min.css') }}">
    <script src="/assets/js/nouislider.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/select.css') }}">
    <div x-data="form">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>Setup Machine</span>
            </li>
        </ul>

        <div class="pt-5 space-y-8">
            <div class="panel" id="health_condition">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Health Condition</h5>
                </div>
                <div class="mb-5" x-data="{ active: 1 }">
                    <div class="space-y-2 font-semibold">
                        <div class="border border-[#d3d3d3] dark:border-[#1b2e4b] rounded dark:text-white-light">
                            <button type="button" id="btnStartSetup"
                                class="p-4 w-full flex items-center text-white-light dark:bg-[#1b2e4b]">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="https://www.w3.org/2000/svg"
                                    class="w-5 h-5 ltr:mr-2 rtl:ml-2 text-white-light shrink-0">
                                    <path opacity="0.5"
                                        d="M7.142 18.9706C5.18539 18.8995 3.99998 18.6568 3.17157 17.8284C2 16.6569 2 14.7712 2 11C2 7.22876 2 5.34315 3.17157 4.17157C4.34315 3 6.22876 3 10 3H14C17.7712 3 19.6569 3 20.8284 4.17157C22 5.34315 22 7.22876 22 11C22 14.7712 22 16.6569 20.8284 17.8284C20.0203 18.6366 18.8723 18.8873 17 18.965"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path
                                        d="M9.94955 16.0503C10.8806 15.1192 11.3461 14.6537 11.9209 14.6234C11.9735 14.6206 12.0261 14.6206 12.0787 14.6234C12.6535 14.6537 13.119 15.1192 14.0501 16.0503C16.0759 18.0761 17.0888 19.089 16.8053 19.963C16.7809 20.0381 16.7506 20.1112 16.7147 20.1815C16.2973 21 14.8648 21 11.9998 21C9.13482 21 7.70233 21 7.28489 20.1815C7.249 20.1112 7.21873 20.0381 7.19436 19.963C6.91078 19.089 7.92371 18.0761 9.94955 16.0503Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                </svg>
                                Health Condition
                                <div class="ltr:ml-auto rtl:mr-auto" :class="{ 'rotate-180': active === 1 }">

                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="https://www.w3.org/2000/svg" class="w-4 h-4">
                                        <path d="M19 9L12 15L5 9" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>

                            <div x-cloak x-show="active === 1" x-collapse>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 dark:text-white-light">
                                    <div>
                                        <label for="inputDefault">Employee</label>
                                        <select class="form-select" id="employee_selected">
                                        </select>
                                    </div>
                                    <div>
                                        <label>Apakah anda dalam kondisi sehat?</label>
                                        <select class="selectize" id="physical_condition">
                                            <option value="" selected>Pilih kondisi anda</option>
                                            <option value="Ya">Ya</option>
                                            <option value="Tidak">Tidak</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="production_date_setup">Apakah dalam 24 jam terakhir anda telah tidur
                                            cukup
                                            <br>
                                            selama 5-8 jam sehari?</label>
                                        <select class="selectize" id="sleep_condition">
                                            <option value="" selected>Pilih kondisi anda</option>
                                            <option value="Ya">Ya</option>
                                            <option value="Tidak">Tidak</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="jo-select">Apakah anda sedang mengonsumsi obat-obatan tertentu yang
                                            menyebabkan mengantuk/pusing/menganggu konsentrasi bekerja?</label>
                                        <select class="selectize" id="medicine_condition">
                                            <option value="" selected>Pilih kondisi anda</option>
                                            <option value="Ya">Ya</option>
                                            <option value="Tidak">Tidak</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="standard_sph">Apakah anda bebas dari pengaruh alkohol, narkotika,
                                            atau zat aditif lainnya?</label>
                                        <select class="selectize" id="drug_condition">
                                            <option value="" selected>Pilih kondisi anda</option>
                                            <option value="Ya">Ya</option>
                                            <option value="Tidak">Tidak</option>
                                        </select>
                                    </div>
                                    <div class="">
                                        <label for="avail-select">Apakah kondisi mental anda saat ini stabil, fokus, dan
                                            siap untuk bekerja?</label>
                                        <select class="selectize" id="mental_condition">
                                            <option value="" selected>Pilih kondisi anda</option>
                                            <option value="Ya">Ya</option>
                                            <option value="Tidak">Tidak</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="btn btn-primary" id="btn_health_submit">
                        <svg id="health_icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                        </svg>
                        <svg id="health_loader" viewBox="0 0 24 24" width="24" height="24"
                            stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="w-5 h-5 ltr:mr-1.5 rtl:ml-1.5  animate-[spin_2s_linear_infinite] inline-block align-middle shrink-0 hidden">
                            <line x1="12" y1="2" x2="12" y2="6"></line>
                            <line x1="12" y1="18" x2="12" y2="22"></line>
                            <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                            <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                            <line x1="2" y1="12" x2="6" y2="12"></line>
                            <line x1="18" y1="12" x2="22" y2="12"></line>
                            <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                            <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                        </svg>
                        Submit
                    </button>
                </div>
            </div>
            <div class="panel hidden" id="setup_machine_view">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Setup Machine Std</h5>
                </div>
                <div class="mb-5" x-data="{ active: 1 }">
                    <div class="space-y-2 font-semibold">
                        <div class="border border-[#d3d3d3] dark:border-[#1b2e4b] rounded dark:text-white-light">
                            <button type="button" id="btnStartSetup"
                                class="p-4 w-full flex items-center text-white-light dark:bg-[#1b2e4b]"
                                :class="{ '!text-white-light': active === 1 }"
                                x-on:click="active === 1 ? active = null : active = 1">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="https://www.w3.org/2000/svg"
                                    class="w-5 h-5 ltr:mr-2 rtl:ml-2 text-white-light shrink-0">
                                    <path opacity="0.5"
                                        d="M7.142 18.9706C5.18539 18.8995 3.99998 18.6568 3.17157 17.8284C2 16.6569 2 14.7712 2 11C2 7.22876 2 5.34315 3.17157 4.17157C4.34315 3 6.22876 3 10 3H14C17.7712 3 19.6569 3 20.8284 4.17157C22 5.34315 22 7.22876 22 11C22 14.7712 22 16.6569 20.8284 17.8284C20.0203 18.6366 18.8723 18.8873 17 18.965"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path
                                        d="M9.94955 16.0503C10.8806 15.1192 11.3461 14.6537 11.9209 14.6234C11.9735 14.6206 12.0261 14.6206 12.0787 14.6234C12.6535 14.6537 13.119 15.1192 14.0501 16.0503C16.0759 18.0761 17.0888 19.089 16.8053 19.963C16.7809 20.0381 16.7506 20.1112 16.7147 20.1815C16.2973 21 14.8648 21 11.9998 21C9.13482 21 7.70233 21 7.28489 20.1815C7.249 20.1112 7.21873 20.0381 7.19436 19.963C6.91078 19.089 7.92371 18.0761 9.94955 16.0503Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                </svg>
                                Setup Machine Std
                                <div class="ltr:ml-auto rtl:mr-auto" :class="{ 'rotate-180': active === 1 }">

                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="https://www.w3.org/2000/svg" class="w-4 h-4">
                                        <path d="M19 9L12 15L5 9" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>

                            <div x-cloak x-show="active === 1" x-collapse>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 dark:text-white-light">
                                    <div>
                                        <label for="inputDefault">Category</label>
                                        <select class="selectize" id="category_setup">
                                            <option selected>Selected Category</option>
                                            <option value="STP">Stamping</option>
                                            <option value="ASSY">Assembly</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label>Shift</label>
                                        <select class="form-select" id="shift_setup">
                                        </select>
                                    </div>
                                    <div>
                                        <label for="production_date_setup">Production Date (Job)</label>
                                        <input id="production_date_setup" type="date"
                                            class="form-input tex-black dark:text-white" style="color-scheme: dark;"
                                            readonly />
                                    </div>
                                    <div>
                                        <label for="jo-select">Job Num</label>
                                        <select class="form-select" id="job_num_setup">
                                        </select>
                                    </div>
                                    <div>
                                        <label for="standard_sph">Customer</label>
                                        <input id="customer_setup" type="text"
                                            class="form-input text-black dark:text-white" readonly />
                                    </div>
                                    <div class="">
                                        <label for="avail-select">Work Time</label>
                                        <select class="form-input text-black dark:text-white" id="work_time_setup">
                                            <option value="8.25">8.25 H</option>
                                            <option value="7">7 H</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="grid md:grid-cols-2 sm:grid-cols-1 lg:grid-cols-3 gap-4 p-5 dark:text-white-light items-center"
                                id="list_machine">

                            </div>

                        </div>

                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="btn btn-primary" id="btn_start">
                        <svg id="start_icon" xmlns="https://www.w3.org/2000/svg"
                            class="w-5 h-5 ltr:mr-1.5 rtl:ml-1.5 shrink-0" width="24" height="24"
                            viewBox="0 0 24 24" fill="none">
                            <path d="M10 2H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path
                                d="M13.8876 10.9348C14.9625 11.8117 15.5 12.2501 15.5 13C15.5 13.7499 14.9625 14.1883 13.8876 15.0652C13.5909 15.3073 13.2966 15.5352 13.0261 15.7251C12.7888 15.8917 12.5201 16.064 12.2419 16.2332C11.1695 16.8853 10.6333 17.2114 10.1524 16.8504C9.6715 16.4894 9.62779 15.7336 9.54038 14.2222C9.51566 13.7947 9.5 13.3757 9.5 13C9.5 12.6243 9.51566 12.2053 9.54038 11.7778C9.62779 10.2664 9.6715 9.51061 10.1524 9.1496C10.6333 8.78859 11.1695 9.11466 12.2419 9.76679C12.5201 9.93597 12.7888 10.1083 13.0261 10.2749C13.2966 10.4648 13.5909 10.6927 13.8876 10.9348Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path
                                d="M7.5 5.20404C8.82378 4.43827 10.3607 4 12 4C16.9706 4 21 8.02944 21 13C21 17.9706 16.9706 22 12 22C7.02944 22 3 17.9706 3 13C3 11.3607 3.43827 9.82378 4.20404 8.5"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>

                        <svg id="start_loader" viewBox="0 0 24 24" width="24" height="24"
                            stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="w-5 h-5 ltr:mr-1.5 rtl:ml-1.5  animate-[spin_2s_linear_infinite] inline-block align-middle shrink-0 hidden">
                            <line x1="12" y1="2" x2="12" y2="6"></line>
                            <line x1="12" y1="18" x2="12" y2="22"></line>
                            <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                            <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                            <line x1="2" y1="12" x2="6" y2="12"></line>
                            <line x1="18" y1="12" x2="22" y2="12"></line>
                            <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                            <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                        </svg>

                        Start
                    </button>
                </div>
            </div>
        </div>
        <div class="panel hidden" id="setup_machine_tool_view">
            <div class="flex items-center justify-between mb-5">
                <h5 class="font-semibold text-lg dark:text-white-light">Setup Machine Special</h5>
            </div>
            <div class="mb-5" x-data="{ active: 1 }">
                <div class="space-y-2 font-semibold">
                    <div class="border border-[#d3d3d3] dark:border-[#1b2e4b] rounded dark:text-white-light">
                        <button type="button" id="btnStartSetup"
                            class="p-4 w-full flex items-center text-white-light dark:bg-[#1b2e4b]"
                            :class="{ '!text-white-light': active === 1 }"
                            x-on:click="active === 1 ? active = null : active = 1">

                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="https://www.w3.org/2000/svg"
                                class="w-5 h-5 ltr:mr-2 rtl:ml-2 text-white-light shrink-0">
                                <path opacity="0.5"
                                    d="M7.142 18.9706C5.18539 18.8995 3.99998 18.6568 3.17157 17.8284C2 16.6569 2 14.7712 2 11C2 7.22876 2 5.34315 3.17157 4.17157C4.34315 3 6.22876 3 10 3H14C17.7712 3 19.6569 3 20.8284 4.17157C22 5.34315 22 7.22876 22 11C22 14.7712 22 16.6569 20.8284 17.8284C20.0203 18.6366 18.8723 18.8873 17 18.965"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M9.94955 16.0503C10.8806 15.1192 11.3461 14.6537 11.9209 14.6234C11.9735 14.6206 12.0261 14.6206 12.0787 14.6234C12.6535 14.6537 13.119 15.1192 14.0501 16.0503C16.0759 18.0761 17.0888 19.089 16.8053 19.963C16.7809 20.0381 16.7506 20.1112 16.7147 20.1815C16.2973 21 14.8648 21 11.9998 21C9.13482 21 7.70233 21 7.28489 20.1815C7.249 20.1112 7.21873 20.0381 7.19436 19.963C6.91078 19.089 7.92371 18.0761 9.94955 16.0503Z"
                                    stroke="currentColor" stroke-width="1.5" />
                            </svg>
                            Setup Machine Special
                            <div class="ltr:ml-auto rtl:mr-auto" :class="{ 'rotate-180': active === 1 }">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="https://www.w3.org/2000/svg" class="w-4 h-4">
                                    <path d="M19 9L12 15L5 9" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </button>

                        <div x-cloak x-show="active === 1" x-collapse>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 dark:text-white-light">
                                <div>
                                    <label for="inputDefault">Category</label>
                                    <select class="selectize" id="category_setup_tool">
                                        <option value="ASSY" selected>Assembly</option>
                                    </select>
                                </div>
                                <div>
                                    <label>Shift</label>
                                    <select class="selectize" id="shift_setup_tool">
                                        <option value="6">Day</option>
                                        <option value="1">Night</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="jo-select">Machine ID</label>
                                    <select class="selectize" id="machine_id_tool">
                                        <option value="RSW-5H45-09">RSW-5H45-09</option>
                                        <option value="RSW-5H45-10">RSW-5H45-10</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="production_date_setup">Production Date (Job)</label>
                                    <input id="production_date_setup_tool" type="date"
                                        class="form-input tex-black dark:text-white" style="color-scheme: dark;"
                                        readonly />
                                </div>
                                <div>
                                    <label for="standard_sph">Employee</label>
                                    <select class="form-select" id="employee_id_tool">
                                    </select>
                                </div>
                                <div class="">
                                    <label for="avail-select">Work Time</label>
                                    <select class="selectize" id="work_time_setup_tool">
                                        <option value="8.25">8.25 H</option>
                                        <option value="7">7 H</option>
                                    </select>
                                </div>
                                {{-- <div class="mb-5">
                                        <input type="time" id="actual_clock_out_time" x-model="actual_clock_out_time"
                                            hidden />
                                    </div> --}}
                            </div>
                            {{-- <div class="mb-2 text-center">
                                    <h4 class="text-white text-2xl font-bold" id="list_machine_name"></h4>
                                </div> --}}
                            <div class="grid md:grid-cols-2 sm:grid-cols-1 lg:grid-cols-3 gap-4 p-5 dark:text-white-light items-center"
                                id="list_machine_tool">

                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="button" class="btn btn-primary" id="btn_start_tool">
                    <svg id="start_icon_tool" xmlns="https://www.w3.org/2000/svg"
                        class="w-5 h-5 ltr:mr-1.5 rtl:ml-1.5 shrink-0" width="24" height="24"
                        viewBox="0 0 24 24" fill="none">
                        <path d="M10 2H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path
                            d="M13.8876 10.9348C14.9625 11.8117 15.5 12.2501 15.5 13C15.5 13.7499 14.9625 14.1883 13.8876 15.0652C13.5909 15.3073 13.2966 15.5352 13.0261 15.7251C12.7888 15.8917 12.5201 16.064 12.2419 16.2332C11.1695 16.8853 10.6333 17.2114 10.1524 16.8504C9.6715 16.4894 9.62779 15.7336 9.54038 14.2222C9.51566 13.7947 9.5 13.3757 9.5 13C9.5 12.6243 9.51566 12.2053 9.54038 11.7778C9.62779 10.2664 9.6715 9.51061 10.1524 9.1496C10.6333 8.78859 11.1695 9.11466 12.2419 9.76679C12.5201 9.93597 12.7888 10.1083 13.0261 10.2749C13.2966 10.4648 13.5909 10.6927 13.8876 10.9348Z"
                            stroke="currentColor" stroke-width="1.5" />
                        <path
                            d="M7.5 5.20404C8.82378 4.43827 10.3607 4 12 4C16.9706 4 21 8.02944 21 13C21 17.9706 16.9706 22 12 22C7.02944 22 3 17.9706 3 13C3 11.3607 3.43827 9.82378 4.20404 8.5"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>

                    <svg id="start_loader_tool" viewBox="0 0 24 24" width="24" height="24"
                        stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"
                        stroke-linejoin="round"
                        class="w-5 h-5 ltr:mr-1.5 rtl:ml-1.5  animate-[spin_2s_linear_infinite] inline-block align-middle shrink-0 hidden">
                        <line x1="12" y1="2" x2="12" y2="6"></line>
                        <line x1="12" y1="18" x2="12" y2="22"></line>
                        <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                        <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                        <line x1="2" y1="12" x2="6" y2="12"></line>
                        <line x1="18" y1="12" x2="22" y2="12"></line>
                        <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                        <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                    </svg>

                    Start
                </button>
            </div>
        </div>
    </div>
    </div>

    <link rel="stylesheet" href="{{ Vite::asset('resources/css/highlight.min.css') }}">
    <script src="/assets/js/highlight.min.js"></script>
    <script src="/assets/js/nice-select2.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            var els = document.querySelectorAll(".selectize");
            els.forEach(function(select) {
                NiceSelect.bind(select);
            });
            $(".form-select").select2({
                width: '100%',
                allowClear: true
            });
            employeeSelected()
        })

        function employeeSelected() {
            $('#employee_selected').select2({
                width: '100%',
                placeholder: "Pilih nama anda",
                allowClear: true,
                ajax: {
                    url: "{{ url('api/machine/get-employees') }}",
                    type: 'POST',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2
            });
        }
        $("#btn_health_submit").on('click', function() {
            const employee_selected = $("#employee_selected").val()
            const physical_condition = $("#physical_condition").val()
            const sleep_condition = $("#sleep_condition").val()
            const medicine_condition = $("#medicine_condition").val()
            const drug_condition = $("#drug_condition").val()
            const mental_condition = $("#mental_condition").val()
            if (employeeSelected == '' || !physical_condition || !sleep_condition || !medicine_condition || !
                drug_condition || !mental_condition) {
                new window.Swal({
                    icon: 'error',
                    text: 'All fields required',
                    padding: '2em',
                    customClass: 'sweet-alerts',
                });
                return false;
            }
            $("#health_icon").hide()
            $("#health_loader").show()
            $.ajax({
                url: "{{ url('api/health_condition/submit') }}",
                type: "POST",
                data: {
                    employee_selected: employee_selected,
                    physical_condition: physical_condition,
                    sleep_condition: sleep_condition,
                    medicine_condition: medicine_condition,
                    drug_condition: drug_condition,
                    mental_condition: mental_condition
                },
                success: function(response) {
                    const status = response.status
                    const message = response.message
                    if (status == 'success') {
                        Swal.fire({
                            title: 'Setup Machine',
                            text: message,
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonText: 'Standard Setup',
                            cancelButtonText: 'Special Setup',
                            reverseButtons: true,
                            padding: '2em',
                            customClass: {
                                popup: 'sweet-alerts',
                                confirmButton: 'btn btn-success',
                                cancelButton: 'btn btn-warning'
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                ViewSetupMachine();
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                ViewSetupMachineTool();
                            }
                        });
                    } else {
                        new window.Swal({
                            icon: 'error',
                            text: message,
                            padding: '2em',
                            customClass: 'sweet-alerts',
                        });
                    }
                    $("#health_icon").show()
                    $("#health_loader").hide()
                },
                error: function(xhr) {
                    $("#health_icon").show()
                    $("#health_loader").hide()
                    console.log(xhr)
                    new window.Swal({
                        icon: 'error',
                        text: 'Something went wrong!',
                        padding: '2em',
                        customClass: 'sweet-alerts',
                    });
                }
            })
        })

        function ViewSetupMachine() {
            $("#health_condition").addClass('hidden')
            $("#setup_machine_view").removeClass('hidden')
        }

        function ViewSetupMachineTool() {
            $("#health_condition").addClass('hidden')
            $("#setup_machine_tool_view").removeClass('hidden')
            const today = new Date().toISOString().split('T')[0];
            $("#production_date_setup_tool").val(today)
        }
        $("#category_setup").on('change', function() {
            const category = $(this).val();
            const shift = $("#shift_setup");

            shift.empty()
                .append(`<option value="">Pilih Opsi</option>`);

            if (category === 'STP') {
                shift.append(`
            <option value="1">Day</option>
            <option value="2">Night</option>
        `);
            } else {
                shift.append(`
            <option value="6">Day</option>
            <option value="1">Night</option>
        `);
            }
        });
        $("#shift_setup").on('change', function() {
            const shift = $("#shift_setup");
            const today = new Date().toISOString().split('T')[0];
            $("#production_date_setup").val(today)
            JobNumSelected()
        })

        function JobNumSelected() {
            $.ajax({
                url: "{{ url('api/job_num/get_all') }}",
                type: "POST",
                data: {
                    shift: $("#shift_setup").val(),
                    category: $("#category_setup").val(),
                    production_date_setup: $("#production_date_setup").val()
                },
                success: function(response) {
                    const select = $("#job_num_setup");
                    select.empty();
                    select.append('<option value="">Pilih Job Number</option>');

                    if (!response.results || response.results.length === 0) {
                        select.append('<option value="">Data tidak tersedia</option>');
                        return;
                    }
                    $.each(response.results, function(index, item) {
                        select.append(
                            `<option value="${item.id}">
                                ${item.text}
                            </option>`
                        );
                    });
                },
                error: function(xhr) {
                    console.log(xhr)
                }
            })
        }
        $("#job_num_setup").on('change', function() {
            $.ajax({
                url: "{{ url('api/job_num/get_customer') }}",
                type: "POST",
                data: {
                    JobNum: $("#job_num_setup").val()
                },
                success: function(response) {
                    $("#customer_setup").val(response)
                    ListJobEntry()
                },
                error: function(xhr) {
                    console.log(xhr)
                }
            })
        })

        function ListJobEntry() {
            $.ajax({
                url: "{{ url('api/job_num/list_setup_machine') }}",
                type: "POST",
                dataType: "json",
                data: {
                    job_num: $("#job_num_setup").val(),
                    shift: $("#shift_setup").val(),
                    category_id: $("#category_setup").val(),
                    production_date: $("#production_date_setup").val()
                },
                success: function(response) {
                    const container = $("#list_machine");
                    container.html('');
                    const data = response.data || {};
                    const jobOper = data.jobOper || [];
                    const jobOpDtl = data.jobOpDtl || [];
                    const jobHead = data.jobhead || {};
                    const machineResponse = response.machine || [];
                    const getProdStandardByOprSeq = (oprSeq) => {
                        const found = jobOper.find(op => op.oprSeq === oprSeq);
                        return found ? found.prodStandard : '';
                    };
                    jobOpDtl.forEach((item, index) => {

                        const machineInfo = machineResponse.find(
                            m => m.machine_code === item.resourceID
                        );
                        const jph = getProdStandardByOprSeq(item.oprSeq);
                        const selectId = `employeeId_${index}`;
                        const machineId = `machine_${index}`;

                        const cardHTML = `
                <div class="border-2 border-white block w-full p-6 bg-white rounded-lg shadow-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 mb-4">

                    ${machineInfo ? `
                                                                                                                                                                                                                                                                                                                                                                                                    <div class="w-full border-b border-gray-600 flex items-center justify-between py-2">
                                                                                                                                                                                                                                                                                                                                                                                                        <div class="flex items-center gap-6">
                                                                                                                                                                                                                                                                                                                                                                                                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                                                                                                                                                                                                                                                                                                                                                                                                                style="color:${machineInfo.is_active === '1' ? '#43A047' : '#FDD835'}"
                                                                                                                                                                                                                                                                                                                                                                                                                stroke="currentColor">
                                                                                                                                                                                                                                                                                                                                                                                                                <path d="M12 2v10m6.364-4.364a9 9 0 11-12.728 0"
                                                                                                                                                                                                                                                                                                                                                                                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                                                                                                                                                                                                                                                                                                                                                            </svg>
                                                                                                                                                                                                                                                                                                                                                                                                            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                                                                                                                                                                                                                                                                                                                                                                                                                Machine ${machineInfo.is_active === '1' ? 'ON' : 'OFF'}
                                                                                                                                                                                                                                                                                                                                                                                                            </h2>
                                                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                                                                    ` : `
                                                                                                                                                                                                                                                                                                                                                                                                    <div class="w-full border-b border-gray-600 flex items-center gap-3 py-2">
                                                                                                                                                                                                                                                                                                                                                                                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                                                                                                                                                                                                                                                                                                                                                                                                            style="color:#FDD835" stroke="currentColor">
                                                                                                                                                                                                                                                                                                                                                                                                            <path d="M12 2v10m6.364-4.364a9 9 0 11-12.728 0"
                                                                                                                                                                                                                                                                                                                                                                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                                                                                                                                                                                                                                                                                                                                                        </svg>
                                                                                                                                                                                                                                                                                                                                                                                                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                                                                                                                                                                                                                                                                                                                                                                                                            Machine OFF
                                                                                                                                                                                                                                                                                                                                                                                                        </h2>
                                                                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                                                                    `}

                    <div class="grid gap-4 mb-4">
                        <div>
                            <label class="block mb-2 font-bold">Machine ID</label>
                             <input
                                type="text"
                                name="machineId[]"
                                id="${machineId}"
                                value="${item.resourceID}"
                                readonly
                                class="form-input"
                            />
                        </div>

                        <div>
                            <label class="block mb-2 font-bold">Operation Seq</label>
                            <input type="text" value="${item.oprSeq}" readonly
                                class="form-input" />
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 font-bold">Standard JPH</label>
                        <input type="text" name="standard_sph[]" value="${jph}"
                            class="form-input" readonly />
                    </div>

                    <div class="mt-2">
                        <label class="block mb-2 font-bold">Employee</label>
                        <select name="employeeId[]" id="${selectId}"
                            class="form-select"></select>
                    </div>

                </div>
                `;

                        container.append(cardHTML);
                        $(`#${selectId}`).select2({
                            width: '100%',
                            placeholder: "Pilih Pegawai",
                            allowClear: true,
                            ajax: {
                                url: "{{ url('api/machine/get-employees') }}",
                                type: 'POST',
                                dataType: 'json',
                                delay: 250,
                                data: function(params) {
                                    return {
                                        q: params.term,
                                        page: params.page || 1
                                    };
                                },
                                processResults: function(data, params) {
                                    params.page = params.page || 1;
                                    return {
                                        results: data.results,
                                        pagination: {
                                            more: data.pagination.more
                                        }
                                    };
                                }
                            },
                            minimumInputLength: 1
                        });

                    });
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }
        async function jo_list() {
            const data = new URLSearchParams();
            data.append('production_date', document.getElementById('production_date')?.value || '');
            data.append('shift', document.getElementById('shift_setup_tool')?.value || '');
            data.append('category_id', document.getElementById('category_setup_tool')?.value || '');

            try {
                const res = await axios.post(
                    `https://${window.location.host}/api/machine/v2/jo_list`,
                    data, {
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    }
                );

                return Array.isArray(res.data?.data) ? res.data.data : [];
            } catch (e) {
                console.error('jo_list error:', e);
                return [];
            }
        }

        $("#machine_id_tool").on('change', async function() {

            const payload = {
                machineID: $('#machine_id_tool').val(),
                shift: $('#shift_setup_tool').val(),
                category: $('#category_setup_tool').val()
            };

            try {
                const res = await $.ajax({
                    url: `https://${window.location.host}/api/special-setup/work-time`,
                    type: 'POST',
                    data: payload,
                    dataType: 'json'
                });

                const listTool = res.data || [];
                const container = document.getElementById('list_machine_tool');
                container.innerHTML = '';

                const defaultJobNumbers = [];

                listTool.forEach((item, index) => {
                    const jobSelectId = `jobNumber_${index}`;
                    defaultJobNumbers.push(item.default_job_num ?? '');

                    container.insertAdjacentHTML('beforeend', `
                <div class="border-2 border-white p-6 bg-white rounded-lg shadow mb-4
                            dark:bg-gray-800 dark:border-gray-700">

                    <div class="border-b border-gray-600 pb-2 mb-3">
                        <h2 class="text-lg font-semibold dark:text-white">
                            ${item.machine_id} - ${item.tool_id}
                        </h2>
                        <input type="hidden" name="tool_id[]" value="${item.tool_id}">
                    </div>

                    <div class="mb-3">
                        <label class="font-bold dark:text-white">Standard JPH</label>
                        <input type="text"
                               name="standard_sph_tool[]"
                               value="${parseFloat(item.standard_sph) || 0}"
                               class="form-input w-full text-black dark:text-white">
                    </div>

                    <div>
                        <label class="font-bold dark:text-white">Job Number</label>
                        <select name="jobNumberTool[]" id="${jobSelectId}"
                                class="form-select w-full dark:text-white"></select>
                    </div>
                </div>
            `);
                });
                jobNumtool()
            } catch (err) {
                console.error('workTimeSelect error:', err);
            }
        });

        function jobNumtool() {

            const payload = {
                shift: $('#shift_setup_tool').val(),
                production_date: $('#production_date_setup_tool').val(),
                category: 'ASSY'
            };

            $.ajax({
                url: "{{ url('api/job_num/get_all') }}",
                type: "POST",
                data: payload,
                dataType: "json",

                success: function(res) {

                    const jobList = res.results || [];
                    const selects = document.querySelectorAll('select[name="jobNumberTool[]"]');

                    selects.forEach(select => {
                        if ($(select).hasClass('select2-hidden-accessible')) {
                            $(select).select2('destroy');
                        }

                        select.innerHTML = '';

                        const placeholder = document.createElement('option');
                        placeholder.value = '';
                        placeholder.textContent = 'Pilih Job Number';
                        placeholder.selected = true;
                        select.appendChild(placeholder);

                        jobList.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item.text;
                            select.appendChild(option);
                        });
                        $(select).select2({
                            width: '100%',
                            placeholder: 'Pilih Job Number',
                            allowClear: true,
                            dropdownParent: $('#list_machine_tool')
                        });
                    });
                },

                error: function(xhr) {
                    console.error('jobNumtool error:', xhr.responseText);
                }
            });
        }

        function showError(message) {
            Swal.fire({
                icon: 'error',
                text: message,
                padding: '2em',
                customClass: 'sweet-alerts'
            });
            return false;
        }
        $("#btn_start").on('click', function() {
            const machineInputs = document.querySelectorAll('input[name="machineId[]"]');
            const machine_id = Array.from(machineInputs).map(input => input.value);
            const employeeId = document.querySelectorAll('select[name="employeeId[]"]');
            const employee_id = Array.from(employeeId).map(select => select.value)
            const inputSph = document.querySelectorAll('input[name="standard_sph[]"]');
            const standardSph = Array.from(inputSph).map(input => input.value)
            const category = $("#category_setup").val()
            const shift = $("#shift_setup").val()
            const production_date = $("#production_date_setup").val()
            const job_num = $("#job_num_setup").val()
            const work_time = $("#work_time_setup").val()
            if (!category) {
                return showError('Category wajib dipilih');
            }

            if (!shift) {
                return showError('Shift wajib dipilih');
            }

            if (!production_date) {
                return showError('Production Date wajib diisi');
            }

            if (!job_num) {
                return showError('Job Number wajib dipilih');
            }

            if (!work_time) {
                return showError('Work Time wajib dipilih');
            }
            if (machine_id.length === 0) {
                return showError('Machine belum tersedia');
            }

            if (machine_id.some(v => !v)) {
                return showError('Machine ID wajib dipilih');
            }

            if (employee_id.some(v => !v)) {
                return showError('Employee wajib dipilih');
            }

            if (standardSph.some(v => !v || Number(v) <= 0)) {
                return showError('Standard JPH / SPH wajib diisi dan > 0');
            }
            $("#start_icon").addClass('hidden')
            $("#start_loader").removeClass('hidden')
            const data = new FormData()
            data.append('category_id', category)
            data.append('production_date', production_date)
            data.append('job_num', job_num)
            data.append('shift', shift)
            data.append('avail_time', work_time)
            data.append('customer', $("#customer_setup").val())
            machine_id.forEach(id => {
                data.append('machine_id[]', id);
            });
            employee_id.forEach(empId => {
                data.append('employee_id[]', empId)
            })
            standardSph.forEach(sph => {
                data.append('standard_sph[]', sph);
            });
            $.ajax({
                url: "{{ url('api/job_num/start_machine_std') }}",
                type: "POST",
                data: data,
                processData: false,
                contentType: false,
                success: function(response) {
                    new window.Swal({
                        icon: 'success',
                        text: 'Machine setup successfully',
                        padding: '2em',
                        customClass: 'sweet-alerts',
                    });
                    $("#start_icon").removeClass('hidden')
                    $("#start_loader").addClass('hidden')
                },
                error: function(xhr) {
                    $("#start_icon").removeClass('hidden')
                    $("#start_loader").addClass('hidden')
                    console.log(xhr)
                    new window.Swal({
                        icon: 'error',
                        text: 'Something went wrong',
                        padding: '2em',
                        customClass: 'sweet-alerts',
                    });
                }
            })
        })
        $("#btn_start_tool").on('click', function() {
            const category = $("#category_setup_tool").val()
            const shift = $("#shift_setup_tool").val()
            const machine_id = $("#machine_id_tool").val()
            const production_date = $("#productio_setup_tool").val()
            const employee_id = $("#employee_id_tool").val()
            const work_time = $("#work_time_setup_tool")
            const toolIDInput = document.querySelectorAll('input[name="tool_id[]"]');
            const toolID = Array.from(toolIDInput).map(input => input.value);
            const standardSPHSelect = document.querySelectorAll('input[name="standard_sph_tool[]"]');
            const standardSPH = Array.from(standardSPHSelect).map(select => select.value);
            const jobNumSelect = document.querySelectorAll('select[name="jobNumberTool[]"]');
            const jobNum = Array.from(jobNumSelect).map(select => select.value);
            if (!category) {
                return showError('Category wajib di isi')
            }
            if (!standardSPH) {
                return showError('Standard SPH wajib di isi')
            }
            if (!job_num) {
                return showError('Job number wajib di isi')
            }
            $("#start_icon_tool").addClass('hidden')
            $("#start_loader_tool").removeClass('hidden')
            const data = new FormData()
            data.append('production_date', production_date)
            data.append('shift', shift)
            data.append('workTime', work_time)
            data.append('machineID', machine_id)
            dta.append('employeeID', employee_id)
            toolID.forEach(tool_id => {
                data.append('toolID[]', tool_id);
            });
            standardSPH.forEach(sph => {
                data.append('standardSPH[]', sph);
            });
            jobNum.forEach(job_num => {
                data.append('jobNumber[]', job_num);
            });
            $.ajax({
                url: "{{ url('api/job_num/start_tool') }}",
                type: "POST",
                data: data,
                success: function(response) {
                    const message = response.data.message
                    const status = message.status
                    if (status === true) {
                        new window.Swal({
                            icon: 'success',
                            text: 'Job Number Updated!',
                            padding: '2em',
                            customClass: 'sweet-alerts',
                        });
                    } else {
                        new window.Swal({
                            icon: 'error',
                            text: response.data.message,
                            padding: '2em',
                            customClass: 'sweet-alerts',
                        });

                    }
                    $("#start_icon_tool").removeClass('hidden')
                    $("#start_loader_tool").addClass('hidden')
                },
                error: function(xhr) {
                    console.log(xhr)
                    $("#start_icon_tool").removeClass('hidden')
                    $("#start_loader_tool").addClass('hidden')
                    return showError('Someting went wrong')
                }
            })
        })
    </script>
</x-layout.default>
