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
    <div id="analytics">
        <div class="p-6 space-y-6 bg-gray-100 dark:bg-gray-900 min-h-screen transition" id="dashboard_profile_view">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Profile Dashboard
                </h1>
                <button id="config_btn"
                    class="px-4 py-2 rounded-lg bg-gray-800 text-white dark:bg-yellow-400 dark:text-black">
                    Configuration
                </button>
            </div>
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 space-y-4">
                    <div class="flex items-center gap-4">
                        <img src="https://ui-avatars.com/api/?name=Aziz+FR" class="w-20 h-20 rounded-full border">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white" id="name_text">
                                Aziz Fatkhu Rohman
                            </h2>
                            <p class="text-gray-500 dark:text-gray-400" id="nik_name">
                                200525-001
                            </p>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Line</span>
                            <span class="font-medium text-gray-800 dark:text-white" id="line_text">

                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Machine</span>
                            <button class="font-medium text-gray-800 dark:text-white" id="machine_text">

                            </button>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status</span>
                            <span class="px-2 py-1 text-xs rounded bg-green-100" id="status_text"
                                style="color:greenyellow">
                                Active
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Job Order</span>
                            <span class="font-medium text-gray-800 dark:text-white" id="job_text">

                            </span>
                        </div>
                        <!-- <div class="flex justify-between">
                            <span class="text-gray-500">Health</span>
                            <span class="font-medium text-gray-800 dark:text-white">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" style="color: yellow">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967h4.175c.969 0 1.371 1.24.588 1.81l-3.378 2.455 1.287 3.966c.3.922-.755 1.688-1.54 1.118L10 13.347l-3.37 2.456c-.784.57-1.838-.196-1.539-1.118l1.287-3.966-3.378-2.455c-.783-.57-.38-1.81.588-1.81h4.175l1.286-3.967z" />
                                </svg>
                            </span>
                        </div> -->
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <h3 class="font-semibold text-gray-800 dark:text-white mb-4">
                        Activity Summary
                    </h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Total Job</p>
                            <p class="text-2xl font-bold dark:text-white" id="total_job">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Total Downtime</p>
                            <p class="text-2xl font-bold dark:text-white" id="total_downtime">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Total Qty Plan</p>
                            <p class="text-2xl font-bold dark:text-white" id="total_qty_plan">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Total Qty Actual</p>
                            <p class="text-2xl font-bold dark:text-white" id="total_qty_actual">-</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mt-3">
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Avability</p>
                            <p class="text-2xl font-bold dark:text-white" id="availability">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Performance</p>
                            <p class="text-2xl font-bold dark:text-white" id="performance">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Quality</p>
                            <p class="text-2xl font-bold dark:text-white" id="quality">-</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <h3 class="font-semibold text-gray-800 dark:text-white mb-4">
                        Progress Chart
                    </h3>
                    <div id="activityChart" height="200"></div>
                </div>
            </div>
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <div class="flex justify-between mb-3">
                        <h3 class="font-semibold text-gray-800 dark:text-white mb-4">
                            Machine Info
                        </h3>
                        <div class="flex">
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
                            <p class="text-2xl font-bold dark:text-white" id="machine_info_text">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Part</p>
                            <p class="text-2xl font-bold dark:text-white" id="part_info_text">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Job Number</p>
                            <p class="text-2xl font-bold dark:text-white" id="jo_info_text">-</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mt-2">
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Qty Plan</p>
                            <p class="text-2xl font-bold dark:text-white" id="qty_plan_info_text">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Qty Actual</p>
                            <p class="text-2xl font-bold dark:text-white" id="qty_actual_info_text">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Qty NG</p>
                            <p class="text-2xl font-bold dark:text-white" id="qty_ng_info_text">-</p>
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
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <h3 class="font-semibold text-gray-800 dark:text-white mb-4">
                        OEE Chart
                    </h3>
                    <div id="oeeChart" height="200"></div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
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
        })
        function main_dashboard() {
            const path = window.location.pathname;
            const segments = path.split('/').filter(Boolean);
            const employeeID = segments[segments.length - 1];
            $.ajax({
                url: "{{ url('api/profile/main') }}",
                type: 'POST',
                data: {
                    employeeID: employeeID
                },
                success: function (response) {
                    if (response.status == 'error') {
                        $("#status_text").text('Non Active')
                        $("#div_dt_list").addClass('hidden')
                        $("#div_dt_now").addClass('hidden')
                    }
                    const data = response.data
                    $("#name_text").text(data.name ?? '-')
                    $("#nik_text").text(data.employee_id)
                    $("#machine_text").text(data.machine_id ?? '-')
                    $('#job_text').text(data.job_num ?? '-')
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
                        $("#div_dt_list").addClass('hidden')
                        $("#btn_machine_resume").removeClass('hidden')
                        $("#btn_machine_finish").addClass('hidden')
                        $("#div_dt_now").removeClass('hidden')
                        $("#nama_dt").text(data.nama_dt)
                        startDowntimeCounter(data.start_dt)
                    } else {
                        $("#div_dt_list").removeClass('hidden')
                        $("#btn_machine_resume").addClass('hidden')
                        $("#btn_machine_finish").removeClass('hidden')
                        $("#div_dt_now").addClass('hidden')
                        if (dtInterval) clearInterval(dtInterval)
                        $("#lama_dt").text('00:00')
                    }
                    if (response.activity !== null) {
                        renderActivityTimeline(response.activity);
                    }
                    if(response.act_summary !== null){
                        const sum = response.act_summary
                        $("#total_downtime").text(sum.total_downtime)
                        $("#total_qty_plan").text(sum.total_qty_plan)
                        $("#total_job")
                        $("#total_qty_actual").text(sum.total_qty_plan)
                        $("#availability").text(sum.availability)
                        $("#performance").text(sum.performance)
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
        // $("#next_health_btn").on('click', function () {
        //     const physical_condition = $('input[name="physical_condition"]:checked').val()
        //     const sleep_condition = $('input[name="sleep_condition"]:checked').val()
        //     const medicine_condition = $('input[name="medicine_condition"]:checked').val()
        //     const drug_condition = $('input[name="drug_condition"]:checked').val()
        //     const mental_condition = $('input[name="mental_condition"]:checked').val()
        //     if (!physical_condition || !sleep_condition || !medicine_condition || !drug_condition || !
        //         mental_condition) {
        //         Swal.fire({
        //             icon: "error",
        //             title: "Oops...",
        //             text: "Semua kolom wajib diisi!"
        //         });
        //         return
        //     }
        //     $("#condition_check_form").addClass('hidden')
        //     $("#config_machine_form").removeClass('hidden')
        // })
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
            // const path = window.location.pathname;
            // const segments = path.split('/').filter(Boolean);
            // const empID = segments[segments.length - 1];
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
        $("#btn_machine_finish").on('click', function () {
            Swal.fire({
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
                                icon: "success",
                                title: "Success",
                                text: "Mesin berhasil di finish"
                            });
                            main_dashboard()
                        }, error: function (xhr) {
                            console.log(xhr.responseJSON.message)
                            Swal.fire({
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
        var actOption = {
            chart: {
                height: 280,
                type: "radialBar"
            },

            series: [67],

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
            labels: ["Progress"]
        };

        var actChart = new ApexCharts(document.querySelector("#activityChart"), actOption);
        actChart.render();
        var oeeOption = {
            chart: {
                height: 280,
                type: "radialBar"
            },

            series: [67],

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
            labels: ["Progress"]
        };

        var oeeChart = new ApexCharts(document.querySelector("#oeeChart"), oeeOption);
        oeeChart.render();
    </script>

</x-layout.default>