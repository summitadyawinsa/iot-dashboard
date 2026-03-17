<x-layout.default>
    <link rel='stylesheet' type='text/css' href='{{ Vite::asset('resources/css/nice-select2.css') }}'>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/flatpickr.min.css') }}">
    <script src="/assets/js/flatpickr.js"></script>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/nouislider.min.css') }}">
    <script src="/assets/js/nouislider.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/select.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        #analytics:fullscreen {
            width: 100vw;
            height: 100vh;
            overflow: auto;
            background: #fff;
        }

        #analytics:fullscreen * {
            pointer-events: auto;
        }
    </style>
    <div id="analytics">
        <input type="text" id="user_id" value="{{ Auth::user()->id }}" hidden>
        <div class="p-6 space-y-6 bg-gray-100 dark:bg-gray-900 min-h-screen transition" id="dashboard_profile_view">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                    Profile Dashboard
                </h1>
                <div class="flex gap-2">
                    <button class="btn btn-primary btn-sm btn-icon" id="view_more">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6" width="24" height="24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </button>
                    <button id="fullscreen_btn"
                        class="p-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition"
                        title="Toggle Fullscreen">
                        <svg id="fullscreen_icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 3.75h6v1.5h-4.5v4.5h-1.5v-6Zm16.5 0v6h-1.5v-4.5h-4.5v-1.5h6ZM3.75 20.25v-6h1.5v4.5h4.5v1.5h-6Zm16.5-6v6h-6v-1.5h4.5v-4.5h1.5Z" />
                        </svg>
                    </button>
                    <button id="config_btn"
                        class="px-4 py-2 rounded-lg bg-gray-800 text-white dark:bg-yellow-400 dark:text-black">
                        Configuration
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 space-y-4">
                    <div class="flex justify-between">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <img src="https://ui-avatars.com/api/?name=Aziz+FR"
                                    class="w-20 h-20 rounded-full border object-cover" id="img_pp">
                                <button type="button" id="btn_open_img"
                                    class="absolute bottom-0 right-0 bg-blue-600 hover:bg-blue-700 text-white rounded-full p-1.5 shadow-md transition hidden"
                                    onclick="document.getElementById('photo_input').click()">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7h4l2-2h6l2 2h4v13H3V7z" />
                                        <circle cx="12" cy="13" r="4" />
                                    </svg>
                                </button>
                                <input type="file" id="photo_input" class="hidden" accept="image/*">
                            </div>
                            <div>
                                <h2 class="text-md font-semibold text-gray-800 dark:text-white" id="name_text">
                                    -
                                </h2>
                                <p class="text-gray-500 dark:text-gray-400" id="nik_name">
                                    -
                                </p>
                            </div>
                        </div>
                        <div>
                            <button id="edit_btn"
                                class="p-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition"
                                title="Toggle">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6" width="24" height="24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                            <button id="update_btn"
                                class="p-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition hidden"
                                title="Toggle">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6" width="24" height="24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Line</span>
                            <span class="text-sm text-gray-800 dark:text-white" id="line_text">

                            </span>
                        </div>
                        <!-- <div class="flex justify-between">
                            <span class="text-gray-500">Machine</span>
                            <button class="text-sm text-gray-800 dark:text-white" id="machine_text">

                            </button>
                        </div> -->
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status</span>
                            <span class="px-2 py-1 text-xs rounded bg-green-100" id="status_text"
                                style="color:greenyellow">
                                Active
                            </span>
                        </div>
                        <!-- <div class="flex justify-between">
                            <span class="text-gray-500">Production Date</span>
                            <span class="text-sm text-gray-800 dark:text-white" id="prod_date_text">

                            </span>
                        </div> -->
                    </div>
                    <div class="w-full">
                        <div class="flex justify-between mb-1 text-sm">
                            <span>Production Progress</span>
                            <span id="progress_percent">0%</span>
                        </div>

                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                            <div id="progress_bar" class="bg-blue h-4 rounded-full transition-all duration-500"
                                style="width: 0%;background-color: #3490dc;">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Sum Job</p>
                            <p class="text-sm font-bold dark:text-white" id="total_job">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Sum DT</p>
                            <p class="text-sm font-bold dark:text-white" id="total_downtime">-</p>
                        </div>
                        <!-- <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Sum Qty Plan</p>
                            <p class="text-sm font-bold dark:text-white" id="total_qty_plan">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Sum Qty Act</p>
                            <p class="text-sm font-bold dark:text-white" id="total_qty_actual">-</p>
                        </div> -->
                    </div>
                    <div id="dtChart" style="width: 100%;"></div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 md:col-span-2">
                    <div class="flex justify-between mb-2">
                        <h3 class="font-semibold text-gray-800 dark:text-white" id="prod_date_text">
                            Machine Info
                        </h3>
                        <div class="flex">
                            <button class="btn btn-primary btn-sm btn-icon hidden" id="btn_technician_arrived">
                                Technician Arrived
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6" width="24" height="24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                            <button class="btn btn-primary btn-sm btn-icon hidden" id="btn_machine_resume">
                                Resume
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6" width="24" height="24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061A1.125 1.125 0 0 1 3 16.811V8.69ZM12.75 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061a1.125 1.125 0 0 1-1.683-.977V8.69Z" />
                                </svg>
                            </button>
                            <button class="btn btn-warning btn-sm btn-icon" id="btn_machine_finish">
                                Finish
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6" width="24" height="24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Machine</p>
                            <p class="text-sm font-bold dark:text-white" id="machine_info_text">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Part</p>
                            <p class="text-sm font-bold dark:text-white" id="part_info_text">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Job Number</p>
                            <p class="text-sm font-bold dark:text-white" id="jo_info_text">-</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mt-2">
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Qty Plan</p>
                            <p class="text-sm font-bold dark:text-white" id="qty_plan_info_text">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Qty Actual</p>
                            <p class="text-sm font-bold dark:text-white" id="qty_actual_info_text">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Qty NG</p>
                            <p class="text-sm font-bold dark:text-white" id="qty_ng_info_text">-</p>
                        </div>
                    </div>
                    <div class="mt-2" id="div_dt_list">
                        <select class="form-select" id="downtime_list">
                        </select>
                    </div>
                    <div class="mt-2" id="div_dt_now">
                        <div class="p-4 rounded-lg bg-red-50 dark:bg-gray-700 border border-red-200">
                            <div class="flex justify-between">
                                <p class="text-lg font-bold text-gray-800 dark:text-white" id="nama_dt">

                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-300" id="lama_dt">

                                </p>
                            </div>
                        </div>
                    </div>
                    <div id="gsphChart" style="width:100%;"></div>
                </div>
            </div>
        </div>
        <div class="p-6 space-y-6 bg-gray-100 dark:bg-gray-900 min-h-screen transition hidden" id="oee_view">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    OEE & Activities
                </h1>
                <button id="back_btn_view"
                    class="px-4 py-2 rounded-lg bg-gray-800 text-white dark:bg-yellow-400 dark:text-black">
                    Back
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <h3 class="font-semibold text-gray-800 dark:text-white mb-4">
                        OEE Chart
                    </h3>
                    <div id="oeeChart" height="200"></div>
                    <div class="grid grid-cols-3 gap-4 mt-3">
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">A</p>
                            <p class="text-sm font-bold dark:text-white" id="availability">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">P</p>
                            <p class="text-sm font-bold dark:text-white" id="performance_val">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Q</p>
                            <p class="text-sm font-bold dark:text-white" id="quality">-</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 md:col-span-2">
                    <h3 class="font-semibold text-gray-800 dark:text-white mb-6">
                        Recent Activity
                    </h3>

                    <ol class="relative" id="activityTimeline">
                        <!-- <li class="relative flex gap-4 pb-10 mb-3">
                            <span class="absolute left-5 top-10 h-full w-px bg-gray-300 dark:bg-gray-600"></span>
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-white">
                                    Login System
                                </p>
                                <p class="text-xs text-gray-500">
                                    10 minutes ago
                                </p>
                            </div>
                        </li> -->
                    </ol>
                </div>
            </div>
        </div>
        <div class="p-6 space-y-6 bg-gray-100 dark:bg-gray-900 min-h-screen transition hidden" id="config_view">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white" id="configTitle">
                    Configuration
                </h1>
                <button id="back_btn"
                    class="px-4 py-2 rounded-lg bg-gray-800 text-white dark:bg-yellow-400 dark:text-black">
                    Back
                </button>
            </div>
            <!-- <div class="bg-white dark:bg-gray-800 rounded-xl shadow hidden" id="condition_check_form">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-5 dark:text-white-light">
                    <div>
                        <p class="font-medium text-gray-800 dark:text-white mb-2">
                            Apakah anda dalam kondisi sehat?
                        </p>

                        <div class="flex gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="physical_condition" value="1" required
                                    class="w-4 h-4 text-blue-600 dark:bg-gray-700 dark:border-gray-600">
                                <span class="text-gray-700 dark:text-white">Ya</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="physical_condition" value="0"
                                    class="w-4 h-4 text-red-600 dark:bg-gray-700 dark:border-gray-600">
                                <span class="text-gray-700 dark:text-white">Tidak</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-white mb-2">
                            Apakah dalam 24 jam terakhir anda telah tidur
                            cukup selama 5-8 jam sehari?
                        </p>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="sleep_condition" value="1" required
                                    class="w-4 h-4 text-blue-600 dark:bg-gray-700 dark:border-gray-600">
                                <span class="text-gray-700 dark:text-white">Ya</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="sleep_condition" value="0"
                                    class="w-4 h-4 text-red-600 dark:bg-gray-700 dark:border-gray-600">
                                <span class="text-gray-700 dark:text-white">Tidak</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-white mb-2">
                            Apakah anda sedang mengonsumsi obat-obatan tertentu yang
                            menyebabkan mengantuk/pusing/menganggu konsentrasi bekerja?
                        </p>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="medicine_condition" value="0" required
                                    class="w-4 h-4 text-blue-600 dark:bg-gray-700 dark:border-gray-600">
                                <span class="text-gray-700 dark:text-white">Ya</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="medicine_condition" value="1"
                                    class="w-4 h-4 text-red-600 dark:bg-gray-700 dark:border-gray-600">
                                <span class="text-gray-700 dark:text-white">Tidak</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label for="standard_sph"></label>
                        <p class="font-medium text-gray-800 dark:text-white mb-2">
                            Apakah anda bebas dari pengaruh alkohol, narkotika,
                            atau zat aditif lainnya?
                        </p>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="drug_condition" value="1" required
                                    class="w-4 h-4 text-blue-600 dark:bg-gray-700 dark:border-gray-600">
                                <span class="text-gray-700 dark:text-white">Ya</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="drug_condition" value="0"
                                    class="w-4 h-4 text-red-600 dark:bg-gray-700 dark:border-gray-600">
                                <span class="text-gray-700 dark:text-white">Tidak</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-white mb-2">
                            Apakah kondisi mental anda saat ini stabil, fokus, dan
                            siap untuk bekerja?
                        </p>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="mental_condition" value="1" required
                                    class="w-4 h-4 text-blue-600 dark:bg-gray-700 dark:border-gray-600">
                                <span class="text-gray-700 dark:text-white">Ya</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="mental_condition" value="0"
                                    class="w-4 h-4 text-red-600 dark:bg-gray-700 dark:border-gray-600">
                                <span class="text-gray-700 dark:text-white">Tidak</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end p-3">
                    <button type="button" class="btn btn-primary btn-sm" id="next_health_btn">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6" width="24" height="24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3" />
                        </svg>
                        Next
                    </button>
                </div>
            </div> -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow" id="config_machine_form">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 dark:text-white-light">
                    <div>
                        <label for="jo-select">Category</label>
                        <select class="selectize" id="category">
                            <option selected disabled>Pilih kategori</option>
                            <option value="assy">Assy</option>
                            <option value="stamping">Stamping</option>
                        </select>
                    </div>
                    <div>
                        <label for="jo-select">Shift</label>
                        <select class="form-select" id="shift">
                        </select>
                    </div>
                    <div>
                        <label for="production_date">Production Date (Job)</label>
                        <input id="production_date" type="date" class="form-input text-black dark:text-white"
                            style="color-scheme: dark;" readonly />
                    </div>
                    <div>
                        <label for="jo-select">Job Num</label>
                        <select class="form-select" id="job_num">
                        </select>
                    </div>
                    <div>
                        <label for="standard_sph">Customer</label>
                        <input id="customer" type="text" class="form-input text-black dark:text-white" readonly />
                    </div>
                </div>
                <div class="flex justify-between p-3">
                    <!-- <button type="button" class="btn btn-primary btn-sm" id="back_config_machine">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6" width="24" height="24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>

                        Back
                    </button> -->
                    <button type="button" class="btn btn-primary btn-sm" id="submit_btn">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6" width="24" height="24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Submit
                    </button>
                </div>
            </div>
            <div id="show_machine" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
        </div>
    </div>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/highlight.min.css') }}">
    <script src="/assets/js/highlight.min.js"></script>
    <script src="/assets/js/nice-select2.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            var els = document.querySelectorAll(".selectize");
            els.forEach(function (select) {
                NiceSelect.bind(select);
            });
            $(".form-select").select2({
                width: '100%'
            });
            main_dashboard()
            downtime_list()
            progress_bar()
            chart_main()
            init_dt_chart()
        })
        let gsphChart
        function chart_main() {
            const employeeID = $("#user_id").val()

            $.ajax({
                url: "{{ url('api/profile/main_gsph') }}",
                type: 'POST',
                data: {
                    employee: employeeID,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {

                    let gsphData = [];
                    let timeCategory = [];

                    response.data.forEach(function (item) {
                        gsphData.push(parseFloat(item.gsph));
                        timeCategory.push(item.cut_off_time);
                    });

                    const gsph_chart = {
                        chart: {
                            type: 'area',
                            height: 300
                        },
                        title: {
                            text: 'GSPH',
                            align: 'center'
                        },
                        series: [{
                            name: 'GSPH',
                            data: gsphData
                        }],
                        stroke: {
                            curve: 'smooth'
                        },
                        xaxis: {
                            categories: timeCategory
                        }
                    };

                    gsphChart = new ApexCharts(document.querySelector("#gsphChart"), gsph_chart);
                    gsphChart.render();
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
        let dtChart;
        function init_dt_chart() {
            const employeeID = $("#user_id").val();
            $.ajax({
                url: "{{ url('api/profile/main-dt') }}",
                type: 'POST',
                data: {
                    empId: employeeID
                },
                success: function (response) {

                    const dtMap = {};

                    response.dt_log.forEach(item => {

                        const name = item.name;

                        // konversi jam → menit
                        const minutes = Number(item.downtime) * 60;

                        if (!dtMap[name]) {
                            dtMap[name] = 0;
                        }

                        dtMap[name] += minutes;

                    });

                    // singkatan label
                    const shortLabel = (text) => {
                        return text
                            .split(" ")
                            .map(w => w[0])
                            .join("")
                            .toUpperCase();
                    };

                    const categories = [];
                    const data = [];

                    Object.keys(dtMap).forEach(name => {

                        categories.push(shortLabel(name));

                        data.push(Number(dtMap[name].toFixed(2)));

                    });

                    const options = {

                        chart: {
                            type: 'bar',
                            height: 250
                        },

                        title: {
                            text: 'Downtime List',
                            align: 'center'
                        },

                        series: [{
                            name: 'Downtime (Minutes)',
                            data: data
                        }],

                        xaxis: {
                            categories: categories
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                columnWidth: '35%'
                            }
                        },

                        dataLabels: {
                            enabled: true
                        }

                    };

                    dtChart = new ApexCharts(document.querySelector("#dtChart"), options);
                    dtChart.render();

                },
                error: function (xhr) {
                    console.log(xhr);
                }
            });

        }
        function main_dashboard() {
            const employeeID = $("#user_id").val()
            const today = new Date();

            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');

            const formattedDate = `${year}-${month}-${day}`;
            $.ajax({
                url: "{{ url('api/profile/main') }}",
                type: 'POST',
                data: {
                    employeeID: employeeID,
                    production_date: formattedDate
                },
                success: function (response) {
                    if (response.status == 'error') {
                        $("#status_text").text('Non Active')
                        $("#div_dt_list").addClass('hidden')
                        $("#div_dt_now").addClass('hidden')
                    }
                    const data = response.data
                    if (data.avatar) {
                        document.getElementById('img_pp').src = `https://dashboard.summitadyawinsa.co.id/uploads/${data.avatar}`
                    }
                    $("#name_text").text(data.name ?? '-')
                    $("#nik_name").text(data.username ?? '-')
                    // $("#machine_text").text(data.machine_id ?? '-')
                    const pro_date = 'Machine Info ' + data.production_date ?? '-'
                    $('#prod_date_text').text(pro_date)
                    if (!data.machine_id && !data.job_num) {
                        $("#status_text").text('Non Active')
                    }
                    $("#line_text").text(data.category_line_id)
                    // $("#total_qty_plan").text(data.qty_plan ?? '-')
                    // $("#total_qty_actual").text(data.qty_actual ?? '-');
                    $("#machine_info_text").text(data.machine_id)
                    $("#part_info_text").text(data.part_no)
                    $("#jo_info_text").text(data.job_num)
                    const qty_plan = Number(data.qty_plan);
                    const qty_actual = Number(data.qty_actual)
                    const qty_ng = Number(data.qty_ng)
                    $("#qty_plan_info_text").text(qty_plan.toFixed(0) ?? '-')
                    $("#qty_actual_info_text").text(qty_actual.toFixed(0) ?? '-')
                    $("#qty_ng_info_text").text(qty_ng.toFixed(0) ?? '-')
                    if (data.start_dt !== null && data.nama_dt !== null) {
                        if (data.dt_type == 'MTN') {
                            $("#div_dt_list").addClass('hidden')
                            $("#btn_machine_resume").addClass('hidden')
                            $("#btn_technician_arrived").removeClass('hidden')
                            $("#btn_machine_finish").addClass('hidden')
                            $("#div_dt_now").removeClass('hidden')
                            $("#nama_dt").text(data.nama_dt)
                            startDowntimeCounter(data.start_dt)
                        } else {
                            $("#div_dt_list").addClass('hidden')
                            $("#btn_machine_resume").removeClass('hidden')
                            $("#btn_technician_arrived").addClass('hidden')
                            $("#btn_machine_finish").addClass('hidden')
                            $("#div_dt_now").removeClass('hidden')
                            $("#nama_dt").text(data.nama_dt)
                            startDowntimeCounter(data.start_dt)
                        }
                    } else {
                        $("#div_dt_list").removeClass('hidden')
                        $("#btn_machine_resume").addClass('hidden')
                        $("#btn_technician_arrived").addClass('hidden')
                        $("#btn_machine_finish").removeClass('hidden')
                        $("#div_dt_now").addClass('hidden')
                        if (dtInterval) clearInterval(dtInterval)
                        $("#lama_dt").text('00:00')
                    }
                    if (response.activity !== null) {
                        renderActivityTimeline(response.activity);
                    }
                    if (response.act_summary !== null) {
                        const sum = response.act_summary
                        $("#total_downtime").text(sum.total_downtime)
                        // $("#total_qty_plan").text(Number(sum.total_qty_plan).toLocaleString('id-ID'))
                        $("#total_job")
                        // $("#total_qty_actual").text(Number(sum.total_qty_actual).toLocaleString('id-ID'))
                        $("#availability").text(sum.availability)
                        $("#performance_val").text(sum.performance)
                        $("#quality").text(sum.quality)
                    }
                },
                error: function (xhr) {
                }
            })
        }
        function main_summary() {
            $.ajax({
                url: "{{ url('api/profile/main_summary') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                }, success: function (response) {
                    $("#total_job").val()
                }, error: function (xhr) {

                }
            })
        }
        function renderActivityTimeline(activities) {
            let html = '';

            activities.forEach(item => {

                const startDate = item.start_date;
                const endDate = item.end_date ? item.end_date : 'On Progress';

                html += `
            <li class="relative flex gap-4 pb-10 mb-3">
                <span class="absolute left-5 top-10 h-full w-px bg-gray-300 dark:bg-gray-600"></span>

                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <div>
                    <p class="text-sm font-bold text-gray-800 dark:text-white">
                        ${item.activity}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-white">
                        Start: ${startDate}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-white">
                        End: ${endDate}
                    </p>
                </div>
            </li>
        `;
            });

            $("#activityTimeline").html(html);
        }
        let dtInterval = null
        const parseJakartaTime = (datetime) => {
            const [date, time] = datetime.split(' ')
            const [y, m, d] = date.split('-').map(Number)
            const [h, i, s] = time.split(':').map(Number)

            return new Date(y, m - 1, d, h, i, s)
        }

        const startDowntimeCounter = (startDt) => {
            if (dtInterval) clearInterval(dtInterval)

            const startTime = parseJakartaTime(startDt)

            const update = () => {
                const now = new Date()
                let diff = Math.floor((now - startTime) / 1000)

                if (diff < 0) diff = 0

                const hours = String(Math.floor(diff / 3600)).padStart(2, '0')
                const minutes = String(Math.floor((diff % 3600) / 60)).padStart(2, '0')
                const seconds = String(diff % 60).padStart(2, '0')

                $("#lama_dt").text(`${hours}:${minutes}:${seconds}`)
            }

            update()
            dtInterval = setInterval(update, 1000)
        }


        function downtime_list() {
            $('#downtime_list').select2({
                dropdownParent: $('#analytics'),
                placeholder: 'Selected Downtime',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                ajax: {
                    url: "{{ url('api/config/get_downtime') }}",
                    type: 'POST',
                    delay: 300,
                    dataType: 'json',
                    data: function (params) {
                        return {
                            _token: "{{ csrf_token() }}",
                            search: params.term,
                            page: params.page || 1,
                            dept: $("#line_text").text()
                        };
                    },
                    processResults: function (response, params) {
                        params.page = params.page || 1;
                        return {
                            results: response.results,
                            pagination: {
                                more: response.pagination.more
                            }
                        };
                    }
                }
            });
            $('#downtime_list').on('select2:select', function (e) {
                let data = e.params.data;
                showRemarkAlert(data);
            });
        }

        function showRemarkAlert(downtime) {
            Swal.fire({
                target: document.getElementById('analytics'),
                html: `
            <div class="text-start">
                <p><strong>Downtime:</strong> ${downtime.text}</p>
                <textarea id="downtime_remark"
                    class="swal2-textarea"
                    placeholder="Enter remark here..."
                    rows="4"></textarea>
            </div>
        `,
                showCancelButton: true,
                confirmButtonText: 'Submit',
                cancelButtonText: 'Cancel',
                focusConfirm: false,
                preConfirm: () => {
                    const remark = document.getElementById('downtime_remark').value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    saveDowntime(downtime, result.value);
                }
            });
        }

        function saveDowntime(downtime, result) {
            $.ajax({
                url: "{{ url('api/config/save_downtime') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    downtime: downtime,
                    result: result,
                    machine: $("#machine_info_text").text()
                }, success: function (response) {
                    main_dashboard()
                    if (response.status == 200) {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: "Downtime berhasil di kirim"
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: response.message
                        });
                    }
                }, error: function (xhr) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: xhr.responseText
                    });
                }
            })
        }
        $("#btn_machine_resume").on('click', function () {
            $.ajax({
                url: "{{ url('api/config/stop_downtime') }}",
                type: 'post',
                data: {
                    machine: $("#machine_info_text").text(),
                    job_num: $("#jo_info_text").text()
                }, success: function (response) {
                    main_dashboard()
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.message
                    });
                }, error: function (xhr) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: xhr.responseText
                    });
                }
            })
        })
        $("#config_btn").on('click', function () {
            $("#config_view").removeClass('hidden')
            $("#condition_check_form").removeClass('hidden')
            $("#dashboard_profile_view").addClass('hidden')
            const today = new Date().toISOString().split('T')[0];
            $("#production_date").val(today)
        })
        $("#back_btn").on('click', function () {
            $("#config_view").addClass('hidden')
            $("#dashboard_profile_view").removeClass('hidden')
        })
        $("#back_btn_view").on('click', function () {
            $("#oee_view").addClass('hidden')
            $("#dashboard_profile_view").removeClass('hidden')
        })
        let oeeChart
        $("#view_more").on('click', function () {
            const empID = $("#user_id").val()
            $.ajax({
                url: "{{ url('api/profile/view_more') }}",
                type: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    employee_id: empID,
                    machine_id: $("#machine_info_text").text(),
                }, success: function (response) {
                    $("#dashboard_profile_view").addClass('hidden')
                    $("#oee_view").removeClass('hidden')
                    $("#availability").text(response.oee_availability)
                    $("#performance_val").text(response.oee_performance)
                    $("#quality").text(response.ooe_quality)
                    var oeeOption = {
                        chart: {
                            height: 280,
                            type: "radialBar"
                        },

                        series: [response.percentage_rata_rata],

                        plotOptions: {
                            radialBar: {
                                hollow: {
                                    margin: 15,
                                    size: "70%"
                                },

                                dataLabels: {
                                    showOn: "always",
                                    name: {
                                        offsetY: -10,
                                        show: true,
                                        color: "#888",
                                        fontSize: "13px"
                                    },
                                    value: {
                                        color: "#111",
                                        fontSize: "30px",
                                        show: true
                                    }
                                }
                            }
                        },

                        stroke: {
                            lineCap: "round",
                        },
                        labels: ["OEE"]
                    };

                    oeeChart = new ApexCharts(document.querySelector("#oeeChart"), oeeOption);
                    oeeChart.render();
                }
            })
        })

        $("#category").on('change', function () {
            shift()
        })

        function shift() {
            $.ajax({
                url: "{{ url('api/config/shift_get_all') }}",
                type: 'post',
                data: {
                    category: $("#category").val()
                },
                success: function (response) {
                    const select = $("#shift");
                    select.empty();
                    select.append('<option value="">Pilih shift</option>');

                    if (!response || response.length === 0) {
                        select.append('<option value="">Data tidak tersedia</option>');
                        return;
                    }
                    $.each(response, function (index, item) {
                        select.append(
                            `<option value="${item.Shift}">
                                ${item.Description}
                            </option>`
                        );
                    });
                }
            });
        }
        $('#shift').on('change', function () {
            job_num()
        });
        function job_num() {
            $('#job_num').select2({
                placeholder: 'Pilih Job Number',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                ajax: {
                    url: "{{ url('api/config/get_job_all') }}",
                    type: 'POST',
                    delay: 300,
                    dataType: 'json',
                    data: function (params) {
                        return {
                            _token: "{{ csrf_token() }}",
                            search: params.term,
                            page: params.page || 1,
                            category: $("#category").val()
                        };
                    },
                    processResults: function (response, params) {
                        params.page = params.page || 1;
                        return {
                            results: response.results,
                            pagination: {
                                more: response.pagination.more
                            }
                        };
                    }
                }
            });
            $('#job_num').on('select2:select', function (e) {
                let data = e.params.data;
                $('#customer').val(data.ProdCode ?? '');
                show_machine()
            });
            $('#job_num').on('select2:clear', function () {
                $('#customer').val('');
            });
        }

        function show_machine() {
            const job_num = $("#job_num").val()
            $.ajax({
                url: "{{ url('api/config/get_machine') }}",
                type: 'POST',
                data: {
                    job_num: job_num
                },
                success: function (response) {
                    const container = document.getElementById('show_machine');
                    container.innerHTML = '';
                    const prodMap = {};
                    response.data.jobOper.forEach(op => {
                        prodMap[op.oprSeq] = op.prodStandard;
                    });

                    response.machine.forEach(machine => {
                        const opDtl = response.data.jobOpDtl.find(
                            d => d.resourceID === machine.machine_id
                        );

                        const oprSeq = opDtl?.oprSeq ?? '-';
                        const prodStandard = oprSeq !== '-' ? prodMap[oprSeq] : '-';
                        const card = `
                        <div class="machine-card group rounded-2xl border border-gray-200 dark:border-gray-700
                                    bg-white dark:bg-gray-800 shadow-sm hover:shadow-lg transition-all duration-200
                                    flex flex-col"
                            data-machine-id="${machine.machine_id}"
                            data-opr-seq="${oprSeq}">

                            <!-- HEADER -->
                            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-start justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                                        ${machine.machine_name}
                                    </h3>
                                    <p class="text-xs text-gray-500 dark:text-white mt-0.5">
                                        ID: ${machine.machine_id}
                                    </p>
                                </div>

                                <span class="text-xs font-medium px-2 py-1 rounded-full
                                            ${oprSeq !== '-'
                                ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'
                                : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-white'}">
                                    ${oprSeq !== '-' ? 'Active' : 'Unassigned'}
                                </span>
                            </div>

                            <!-- BODY -->
                            <div class="px-5 py-4 flex-1 grid grid-cols-2 gap-4 text-sm">
                                <div class="space-y-1">
                                    <p class="text-xs uppercase tracking-wide text-black dark:text-white">
                                        Opr Seq
                                    </p>
                                    <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                                        ${oprSeq}
                                    </p>
                                </div>

                                <div class="space-y-1">
                                    <p class="text-xs uppercase tracking-wide text-black dark:text-white">
                                        Standard JPH
                                    </p>
                                    <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                                        ${prodStandard}
                                    </p>
                                </div>
                            </div>

                            <!-- FOOTER -->
                            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700">
                                <label class="block text-xs font-medium mb-2 text-black dark:text-white">
                                    Assign Employee
                                </label>

                                <select
                                    class="employee_select w-full rounded-xl border border-gray-300 dark:border-gray-600
                                        bg-gray-50 dark:bg-gray-700
                                        text-gray-800 dark:text-gray-100
                                        text-sm px-4 py-2
                                        focus:outline-none focus:ring-2 focus:ring-blue-500
                                        group-hover:border-blue-400 transition"
                                    data-machine-id="${machine.machine_id}"
                                    data-opr-seq="${oprSeq}"
                                    data-prod-standard="${prodStandard}">
                                </select>
                            </div>
                        </div>
                        `;
                        container.insertAdjacentHTML('beforeend', card);
                    });
                    employee_select();
                },
                error: function (xhr) {
                    console.log(xhr)
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: xhr.responseText
                    });
                }
            })
        }

        function employee_select() {
            $('.employee_select').select2({
                placeholder: 'Pilih Employee',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                ajax: {
                    url: "{{ url('api/config/get_employee') }}",
                    type: 'POST',
                    delay: 300,
                    dataType: 'json',
                    data: function (params) {
                        return {
                            _token: "{{ csrf_token() }}",
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function (response, params) {
                        params.page = params.page || 1;

                        return {
                            results: response.results,
                            pagination: {
                                more: response.pagination.more
                            }
                        };
                    }
                }
            });
            $('.employee_select').on('select2:select', function (e) {
                const data = e.params.data;
                const machineId = $(this).data('machine-id');
                const oprSeq = $(this).data('opr-seq');
                console.log({
                    employee_id: data.id,
                    employee_name: data.text,
                    machine_id: machineId,
                    oprSeq: oprSeq
                });
            });
            $('.employee_select').on('select2:clear', function () {
                console.log('Employee dibatalkan');
            });
        }

        function collect_machine_data() {
            let machines = [];

            $(".machine-card").each(function () {
                const machine_id = $(this).data('machine-id');
                const opr_seq = $(this).data('opr-seq');
                const employee_id = $(this).find('.employee_select').val();
                const std_jph = $(this).find('.employee_select').data('prod-standard');
                // if (!machine_id) return;
                // if (!employee_id) {
                //     Swal.fire({
                //         icon: "error",
                //         title: "Oops...",
                //         text: "Pilih Operator!"
                //     });
                // }
                machines.push({
                    machine_id,
                    opr_seq,
                    employee_id,
                    std_jph
                });
            });

            console.log('Collected machines:', machines);
            return machines;
        }

        // $("#back_config_machine").on('click', function () {
        //     $("#condition_check_form").removeClass('hidden')
        //     $("#config_machine_form").addClass('hidden')
        //     $("#config_view").removeClass('hidden')
        // })
        $("#submit_btn").on('click', function () {
            // const physical_condition = $('input[name="physical_condition"]:checked').val();
            // const sleep_condition = $('input[name="sleep_condition"]:checked').val();
            // const medicine_condition = $('input[name="medicine_condition"]:checked').val();
            // const drug_condition = $('input[name="drug_condition"]:checked').val();
            // const mental_condition = $('input[name="mental_condition"]:checked').val();
            const production_date = $("#production_date").val();
            const job_num = $("#job_num").val();
            const customer = $("#customer").val();
            const shift = $("#shift").val();
            const machine = collect_machine_data();
            if (
                !job_num ||
                !customer ||
                !shift
            ) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Semua kolom wajib diisi!"
                });
                return;
            }

            $.ajax({
                url: "{{ url('api/config/setup') }}",
                type: 'POST',
                contentType: 'application/json',
                processData: false,
                data: JSON.stringify({
                    production_date,
                    job_num,
                    customer,
                    shift,
                    machine
                }),
                success: function (response) {
                    Swal.fire({
                        icon: response.status === 'success' ? 'success' : 'error',
                        title: response.status === 'success' ? 'Success' : 'Oops...',
                        text: response.message
                    });
                },
                error: function (xhr) {
                    let message = 'Terjadi kesalahan pada server';
                    // if (xhr.responseJSON && xhr.responseJSON.message) {
                    //     message = xhr.responseJSON.message;
                    // }
                    Swal.fire({
                        icon: "error",
                        title: "Server Error",
                        text: message
                    });
                }
            });
        });
        $("#btn_technician_arrived").on('click', function () {
            $.ajax({
                url: "{{ url('api/config/technician_arrived') }}",
                type: 'post',
                data: {
                    machine: $("#machine_info_text").text(),
                    job_num: $("#jo_info_text").text()
                }, success: function (response) {
                    const msg = response.message
                    $("#btn_machine_resume").removeClass('hidden')
                    $("#btn_technician_arrived").addClass('hidden')
                    if (response.status !== 200) {
                        Swal.fire({
                            icon: "error",
                            title: 'Error',
                            text: message
                        });
                    } else {
                        Swal.fire({
                            icon: "success",
                            title: 'Success',
                            text: message
                        });
                    }
                }, error: function (xhr) {
                    Swal.fire({
                        icon: "error",
                        title: 'Error',
                        text: 'Unknown Error'
                    });
                }
            })
        })
        $("#btn_machine_finish").on('click', function () {
            Swal.fire({
                target: document.getElementById('analytics'),
                title: "Are you sure?",
                text: "You are about to finish this job.!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, finish it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('api/config/finish_machine') }}",
                        type: 'post',
                        data: {
                            machine: $("#machine_info_text").text(),
                            job_num: $("#jo_info_text").text()
                        }, success: function (response) {
                            Swal.fire({
                                target: document.getElementById('analytics'),
                                icon: "success",
                                title: "Success",
                                text: "Mesin berhasil di finish"
                            });
                            main_dashboard()
                        }, error: function (xhr) {
                            console.log(xhr.responseJSON.message)
                            Swal.fire({
                                target: document.getElementById('analytics'),
                                icon: "error",
                                title: "Server Error",
                                text: 'Terjadi kesalahan'
                            });
                        }
                    })
                }
            });
        })
    </script>
    <script>
        const fullscreenBtn = document.getElementById('fullscreen_btn');
        const fullscreenIcon = document.getElementById('fullscreen_icon');
        const content = document.getElementById('analytics');

        fullscreenBtn.addEventListener('click', () => {

            if (!document.fullscreenElement) {
                content.requestFullscreen();

                fullscreenIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round"
            d="M9 9H5.25V5.25m0 0L9 9m-3.75-3.75L9 9m6 6h3.75v3.75m0 0L15 15m3.75 3.75L15 15" />
        `;
            } else {
                document.exitFullscreen();

                fullscreenIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round"
            d="M3.75 3.75h6v1.5h-4.5v4.5h-1.5v-6Zm16.5 0v6h-1.5v-4.5h-4.5v-1.5h6ZM3.75 20.25v-6h1.5v4.5h4.5v1.5h-6Zm16.5-6v6h-6v-1.5h4.5v-4.5h1.5Z" />
        `;
            }

        });
        function progress_bar() {
            const employeeID = $("#user_id").val()
            $.ajax({
                url: "{{ url('api/profile/progress_bar') }}",
                type: 'POST',
                data: {
                    employeeID: employeeID
                }, success: function (response) {
                    const data = response.data;
                    let actual = data.actual;
                    let target = data.target;
                    let percent = (actual / target) * 100;
                    percent_bar = Math.min(percent, 100);
                    document.getElementById("progress_bar").style.width = percent_bar + "%";
                    document.getElementById("progress_percent").innerText = percent.toFixed(1) + "%";
                }
            })
        }

        var options = {
            series: [{
                name: 'Inflation',
                data: [2.3, 3.1, 4.0, 10.1, 4.0, 3.6, 3.2, 2.3, 1.4, 0.8]
            }],
            chart: {
                height: 350,
                type: 'bar',
            },
            plotOptions: {
                bar: {
                    borderRadius: 10,
                    dataLabels: {
                        position: 'top', // top, center, bottom
                    },
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val + "%";
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },

            xaxis: {
                categories: ["07.30", "08.30", "09.30", "10.30", "11.30", "12.30", "13.30", "14.30", "15.30", "16.30"],
                position: 'top',
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                crosshairs: {
                    fill: {
                        type: 'gradient',
                        gradient: {
                            colorFrom: '#D8E3F0',
                            colorTo: '#BED1E6',
                            stops: [0, 100],
                            opacityFrom: 0.4,
                            opacityTo: 0.5,
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                }
            },
            yaxis: {
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false,
                },
                labels: {
                    show: false,
                    formatter: function (val) {
                        return val + "%";
                    }
                }

            },
            title: {
                text: 'Monthly Inflation in Argentina, 2002',
                floating: true,
                offsetY: 330,
                align: 'center',
                style: {
                    color: '#444'
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#counterChart"), options);
        chart.render();
    </script>
    <script>
        const socket = new WebSocket('wss://websocket.summitadyawinsa.co.id');

        socket.onopen = () => {
            console.log('Connected to WebSocket server');
            socket.send(JSON.stringify({
                action: "subscribe",
                channel: "machine"
            }));
        };
        socket.onmessage = (event) => {
            try {
                const msg = JSON.parse(event.data)
                const response = msg.data.message
                const eventTitle = msg.event
                const machine = $("#machine_info_text").text()
                // console.log(eventTitle)
                if (eventTitle === 'update_stroke') {
                    console.log(response)
                    const machineID = response.machine_id;
                    const conditionID = response.condition_id;
                    const qty_actual = Number(response.qty_actual)
                    const qty_plan = Number(response.qty_plan)
                    const qty_ng = Number(response.qty_ng)
                    const oee_availability = Number(response.oee_availability)
                    const oee_performance = Number(response.oee_performance)
                    const ooe_quality = Number(response.ooe_quality)
                    if ((conditionID === true || conditionID === 1 || conditionID === '1') && machineID == machine) {
                        let percent = (qty_actual / qty_plan) * 100;
                        percent_bar = Math.min(percent, 100);
                        document.getElementById("progress_bar").style.width = percent_bar + "%";
                        document.getElementById("progress_percent").innerText = percent.toFixed(1) + "%";
                        $("#qty_actual_info_text").text(qty_actual.toFixed(0))
                        $("#qty_ng_info_text").text(qty_ng)
                        $("#availability").text(oee_availability.toFixed(0))
                        $("#performance_val").text(oee_performance.toFixed(0))
                        $("#quality").text(ooe_quality.toFixed(0))
                        const oee = (oee_availability + oee_performance + ooe_quality) / 3;
                        const oeeFinal = Math.min(oee, 100);
                        if (oeeChart) {
                            oeeChart.updateSeries([Number(oeeFinal.toFixed(1))]);
                        }
                        const labels = Object.keys(response.gsphLabel).map(h => h.padStart(2, '0') + ":00");
                        const values = Object.values(response.gsphLabel).map(Number);
                        if (gsphChart) {
                            gsphChart.updateOptions({
                                xaxis: {
                                    categories: labels
                                }
                            });

                            gsphChart.updateSeries([{
                                name: 'GSPH',
                                data: values
                            }]);
                        }
                    }
                }
            } catch (e) {
                console.error('Invalid message from WebSocket:', event.data);
            }
        }
        socket.onclose = () => {
            console.log('Disconnected from WebSocket')
        }
        socket.onerror = (err) => {
            console.log('WebSocket error:', err)
        }
        document.getElementById('photo_input').addEventListener('change', function (e) {
            const reader = new FileReader();
            reader.onload = function (event) {
                document.getElementById('img_pp').src = event.target.result;
            };
            reader.readAsDataURL(e.target.files[0]);
        });
        $("#edit_btn").on('click', function () {
            $("#btn_open_img").removeClass('hidden')
            $("#edit_btn").addClass('hidden')
            $("#update_btn").removeClass('hidden')
        })
        $("#update_btn").on('click', function () {
            let file = $("#photo_input")[0].files[0];
            if (!file) {
                alert("Pilih foto dulu");
                return;
            }
            const employeeID = $("#user_id").val()
            let formData = new FormData();
            formData.append('photo', file);
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('employeeID', employeeID)
            $.ajax({
                url: "{{ url('api/profile/upload-photo') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    alert("Foto berhasil diupdate");
                    location.reload();
                },
                error: function (err) {
                    console.log(err);
                    alert("Upload gagal");
                }
            });
        })
    </script>
</x-layout.default>