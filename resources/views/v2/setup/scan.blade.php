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
                <span>Scan Setup</span>
            </li>
        </ul>

        <div class="pt-5 space-y-8">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Standard Setup</h5>
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
                                Standard Setup
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
                                        <select class="selectize" id="category_id" onchange="getCategory()">
                                            <option value="STP">Stamping</option>
                                            <option value="ASSY">Assembly</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label>Shift</label>
                                        <select class="form-select" id="shiftSelect" onchange="joList()">
                                        </select>
                                    </div>
                                    <div>
                                        <label for="production_date">Production Date (Job)</label>
                                        <input id="production_date" type="date"
                                            class="form-input tex-black dark:text-white" onchange="prodDate()"
                                            style="color-scheme: dark;" readonly />
                                    </div>
                                    <div>
                                        <label for="jo-select">Job Num</label>
                                        <select class="form-select" id="jobNumSelect" onchange="jobNumSelect()">
                                        </select>
                                    </div>
                                    <div>
                                        <label for="standard_sph">Customer</label>
                                        <input id="customer_input" type="text"
                                            class="form-input text-black dark:text-white" readonly />
                                    </div>
                                    <div class="mb-5">
                                        <label for="avail-select">Work Time</label>
                                        <input id="workTime" type="text"
                                            class="form-input text-black dark:text-white" readonly />
                                        {{-- <select class="form-input text-black dark:text-white" id="workTime">
                                        </select> --}}
                                    </div>
                                    <div class="mb-5">
                                        <input type="time" id="actual_clock_out_time" x-model="actual_clock_out_time"
                                            hidden />
                                    </div>
                                </div>
                                <div class="mb-2 text-center">
                                    <h4 class="text-white text-2xl font-bold" id="listMesinNama"></h4>
                                </div>
                                <div class="grid md:grid-cols-2 sm:grid-cols-1 lg:grid-cols-3 gap-4 p-5 dark:text-white-light items-center"
                                    id="listMesin">

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="btn btn-primary" id="btn_start" onclick="setJobNum()">
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
    </div>

    <link rel="stylesheet" href="{{ Vite::asset('resources/css/highlight.min.css') }}">
    <script src="/assets/js/highlight.min.js"></script>
    <script src="/assets/js/nice-select2.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script></script>
</x-layout.default>
