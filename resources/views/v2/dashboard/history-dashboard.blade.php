<x-layout.default>
    <link rel='stylesheet' type='text/css' href='{{ Vite::asset('resources/css/nice-select2.css') }}'>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/flatpickr.min.css') }}">
    <script src="/assets/js/flatpickr.js"></script>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/nouislider.min.css') }}">
    <script src="/assets/js/nouislider.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/select.css') }}">
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
        <div class="p-6 space-y-6 bg-gray-100 dark:bg-gray-900 min-h-screen transition" id="dashboard_profile_view">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                    Dashboard Operator
                </h1>
                <div class="flex gap-2">
                    <div class="hidden" id="div_pilih_jo">
                        <select
                            class="w-full px-3 py-2 rounded-md border
           bg-white text-gray-800 border-gray-300
           focus:outline-none focus:ring-2 focus:ring-blue-500
           dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:focus:ring-blue-400"
                            id="pilih_jo">
                            {{-- <option selected disabled>Pilih JO</option> --}}
                        </select>
                    </div>
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
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 space-y-4">
                    <div class="flex justify-between">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <img src="https://ui-avatars.com/api/?name=Summit+Adyawinsa+Indonesia&length=3"
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

                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Line</span>
                            <span class="text-sm text-gray-800 dark:text-white" id="line_text">
                            </span>
                        </div>
                        {{-- <div class="flex justify-between">
                            <span class="text-gray-500">Opr Seq</span>
                            <span class="text-sm text-gray-800 dark:text-white" id="opr_seq_text">
                            </span>
                        </div> --}}
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status</span>
                            <span class="px-2 py-1 text-xs rounded bg-green-100" id="status_text" style="color:red">
                                Non Active
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
                            <p class="text-sm dark:text-white">Total DT</p>
                            <p class="text-sm font-bold dark:text-white" id="total_dt"></p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Duration DT</p>
                            <p class="text-sm font-bold dark:text-white" id="duration_dt"></p>
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

                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Machine</p>
                            <p class="text-sm font-bold dark:text-white" id="machine_info_text"></p>
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
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9.75 14.25 12m0 0 2.25 2.25M14.25 12l2.25-2.25M14.25 12 12 14.25m-2.58 4.92-6.374-6.375a1.125 1.125 0 0 1 0-1.59L9.42 4.83c.21-.211.497-.33.795-.33H19.5a2.25 2.25 0 0 1 2.25 2.25v10.5a2.25 2.25 0 0 1-2.25 2.25h-9.284c-.298 0-.585-.119-.795-.33Z" />
                    </svg>
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

                    </ol>
                </div>
            </div>
        </div>
    </div>
    <input id="clock_in_date" type="text" hidden />
    <input id="clock_in_time" type="text" hidden />
    <input id="clock_out_time" type="text" hidden />
    <input id="lunch_out_time" type="text" hidden />
    <input id="lunch_in_time" type="text" hidden />
    <input id="laborHedSeq" type="text" hidden />
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/highlight.min.css') }}">
    <script src="/assets/js/highlight.min.js"></script>
    <script src="/assets/js/nice-select2.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        let gsphChart
        let dtChart;
        $(document).ready(function() {
            var els = document.querySelectorAll(".selectize");
            els.forEach(function(select) {
                NiceSelect.bind(select);
            });
            $(".form-select").select2({
                width: '100%'
            });
            let url = window.location.pathname;
            main_history(url)
            chart_main(url)
            init_dt_chart(url)
        })

        function main_history(url) {
            $.ajax({
                url: "{{ url('api/profile/history') }}",
                method: 'POST',
                data: {
                    url: url
                },
                success: function(res) {
                    const data = res.data
                    const downtime = res.downtime
                    if (res.status == true) {
                        $("#name_text").text(data.employee_name)
                        $("#nik_name").text(data.employee_id)
                        $("#line_text").text(data.category_line_id)
                        // $("#opr_seq_text").text(data.opr_seq)
                        $("#prod_date_text").text(data.production_date)
                        // $("#total_job").text(data.total_job)
                        // $("#total_downtime").text(data.total_downtime)
                        // $("#total_qty_plan").text(data.qty_plan)
                        // $("#total_qty_actual").text(data.qty_actual)
                        if (data.tool_id !== null) {
                            $("#machine_info_text").text(data.machine_name + " ~ " + data.tool_id)
                        } else {
                            $("#machine_info_text").text(data.machine_name)
                        }

                        $("#part_info_text").text(data.part_no)
                        $("#jo_info_text").text(data.job_num)
                        $("#qty_plan_info_text").text(Number(data.qty_plan).toFixed(0))
                        $("#qty_actual_info_text").text(Number(data.qty_actual).toFixed(0))
                        $("#qty_ng_info_text").text(Number(data.qty_ng).toFixed(0))
                        const progress = data.qty_plan > 0 ? (data.qty_actual / data.qty_plan) * 100 : 0;
                        $("#progress_percent").text(Number(progress).toFixed(0) + "%")
                        $("#progress_bar").css('width', 100 + '%')
                        const totalDowntime = parseFloat(downtime?.total_downtime ?? 0);
                        const totalDuration = parseFloat(downtime?.total_duration ?? 0);

                        $("#total_dt").text(totalDowntime.toFixed(0));
                        $("#duration_dt").text(totalDuration.toFixed(2) + " mnt");
                    } else {
                        console.log(res)
                    }
                },
                error: function(err) {
                    console.log(err)
                }
            })
        }

        function chart_main(url) {
            $.ajax({
                url: "{{ url('api/profile/history_main_gsph') }}",
                type: 'POST',
                data: {
                    machineID: url,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    let gsphData = [];
                    let timeCategory = [];

                    let previousGsph = 0;

                    response.data.forEach(function(item) {
                        const currentGsph = parseFloat(item.gsph) || 0;
                        let diffGsph = currentGsph - previousGsph;
                        if (diffGsph < 0) {
                            diffGsph = currentGsph;
                        }
                        gsphData.push(diffGsph);
                        timeCategory.push(item.cut_off_time);
                        previousGsph = currentGsph;
                    });
                    if (gsphChart) {
                        gsphChart.destroy();
                    }
                    const gsph_chart = {
                        chart: {
                            type: 'area',
                            height: 300
                        },
                        title: {
                            text: 'JPH/GSPH',
                            align: 'center'
                        },
                        series: [{
                            name: 'JPH/GSPH',
                            data: gsphData
                        }],
                        stroke: {
                            curve: 'smooth'
                        },
                        xaxis: {
                            categories: timeCategory
                        }
                    };

                    gsphChart = new ApexCharts(
                        document.querySelector("#gsphChart"),
                        gsph_chart
                    );

                    gsphChart.render();
                },

                error: function(xhr) {

                    console.log(xhr.responseText);

                }
            });
        }

        function init_dt_chart(url) {
            $.ajax({
                url: "{{ url('api/profile/history_main_dt') }}",
                type: 'POST',
                data: {
                    machineID: url
                },
                success: function(response) {
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
                error: function(xhr) {
                    console.log(xhr);
                }
            });

        }

        $("#back_btn_view").on('click', function() {
            $("#oee_view").addClass('hidden')
            $("#dashboard_profile_view").removeClass('hidden')
        })
        let oeeChart
        $("#view_more").on('click', function() {
            $("#dashboard_profile_view").addClass('hidden')
            $("#oee_view").removeClass('hidden')
            activity_get()
            oee_chart()
        })

        function oee_chart() {
            $.ajax({
                url: "{{ url('api/profile/history_oee') }}",
                method: 'POST',
                data: {
                    url: window.location.pathname
                },
                success: function(res) {
                    if (res.status == true) {
                        $("#availability").text(res.oee_availability)
                        $("#performance_val").text(res.oee_performance)
                        $("#quality").text(res.oee_quality)
                        var oeeOption = {
                            chart: {
                                height: 280,
                                type: "radialBar"
                            },

                            series: [res.percentage_rata_rata],

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
                    } else {

                    }
                },
                error: function(err) {
                    console.log(err)
                }
            })
        }

        function activity_get() {
            $.ajax({
                url: "{{ url('api/profile/activity_history') }}",
                method: 'POST',
                data: {
                    url: window.location.pathname
                },
                success: function(res) {
                    if (res.status == true) {
                        let data = res.data
                        let html = ''
                        data.forEach(function(item) {
                            html += `
                            <li class="mb-10 ml-4">
                                <div class="absolute w-3 h-3 bg-blue-600 rounded-full -left-1.5 border border-white dark:border-gray-900"></div>
                                <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">${item.time}</time>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">${item.activity}</h3>
                                <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">${item.description}</p>
                            </li>
                            `
                        })
                        $("#activityTimeline").html(html)
                    } else {
                        console.log(res)
                    }
                },
                error: function(err) {
                    console.log(err)
                }
            })
        }
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
    </script>
</x-layout.default>
