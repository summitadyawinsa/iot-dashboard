<x-layout.default>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/flatpickr.min.css') }}">
    <script src="/assets/js/flatpickr.js"></script>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/nouislider.min.css') }}">
    <script src="/assets/js/nouislider.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        <div class="p-3 space-y-3 bg-gray-100 dark:bg-gray-900 min-h-screen transition" id="dashboard_profile_view">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                    Dashboard Machine
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
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 space-y-3">
                    <div class="flex justify-between">
                        <div class="flex items-center gap-4">
                            <div>
                                <button id="power_btn" class="btn btn-success btn-sm btn-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-5 h-5"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Machine</p>
                            <p class="text-sm font-bold dark:text-white" id="machine_name">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Shift</p>
                            <p class="text-sm font-bold dark:text-white" id="shift">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Part</p>
                            <p class="text-sm font-bold dark:text-white" id="part_no">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Job Number</p>
                            <p class="text-sm font-bold dark:text-white" id="job_num">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Qty Plan</p>
                            <p class="text-sm font-bold dark:text-white" id="qty_plan">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Qty Actual</p>
                            <p class="text-sm font-bold dark:text-white" id="qty_actual">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Qty NG</p>
                            <p class="text-sm font-bold dark:text-white" id="qty_ng">-</p>
                        </div>
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm dark:text-white">Qty Good</p>
                            <p class="text-sm font-bold dark:text-white" id="qty_good">-</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 space-y-3">
                    <div class="p-4 rounded-lg bg-red-50 dark:bg-gray-700 border border-red-200">
                        <div class="text-center">
                            <h3 class="font-semibold text-gray-800 dark:text-white" id="prod_date_text">
                                Plan Vs Actual
                            </h3>
                        </div>
                    </div>
                    <div class="mt-2 flex justify-center text-center">
                        <div id="plan_vs_actual" class="w-full max-w-xs"></div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 space-y-3">
                    <div class="p-4 rounded-lg bg-red-50 dark:bg-gray-700 border border-red-200">
                        <div class="text-center">
                            <h3 class="font-semibold text-gray-800 dark:text-white" id="prod_date_text">
                                JPH/GSPH
                            </h3>
                        </div>
                    </div>

                    <div class="mt-2 w-full">
                        <div id="gsph_chart" class="w-full"></div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 space-y-3">
                    <div class="p-4 rounded-lg bg-red-50 dark:bg-gray-700 border border-red-200">
                        <div class="text-center">
                            <h3 class="font-semibold text-gray-800 dark:text-white" id="prod_date_text">
                                Overall Equipment Effectiveness
                            </h3>
                        </div>
                    </div>
                    <div class="mt-2 flex justify-center text-center">
                        <div id="oee_chart" class="w-full"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 mt-4">

                        <div class="rounded-xl bg-blue-500 text-white p-4 text-center">
                            <div class="text-sm font-semibold">
                                A
                            </div>

                            <div class="text-2xl font-bold" id="availability_text">

                            </div>
                        </div>

                        <div class="rounded-xl bg-green-500 text-white p-4 text-center">
                            <div class="text-sm font-semibold">
                                P
                            </div>

                            <div class="text-2xl font-bold" id="performance_text">

                            </div>
                        </div>

                        <div class="rounded-xl bg-yellow-500 text-white p-4 text-center">
                            <div class="text-sm font-semibold">
                                Q
                            </div>

                            <div class="text-2xl font-bold" id="quality_text">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 space-y-3">
                    <div class="p-4 rounded-lg bg-red-50 dark:bg-gray-700 border border-red-200">
                        <div class="text-center">
                            <h3 class="font-semibold text-gray-800 dark:text-white" id="prod_date_text">
                                Activity Log
                            </h3>
                        </div>
                    </div>

                    <div class="mt-2 space-y-3 max-h-[500px] overflow-y-auto" id="activity_div">
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 space-y-3">
                    <div class="p-4 rounded-lg bg-red-50 dark:bg-gray-700 border border-red-200">
                        <div class="text-center">
                            <h3 class="font-semibold text-gray-800 dark:text-white">
                                Next Schedule
                            </h3>
                        </div>
                    </div>

                    <div class="mt-2 space-y-3 max-h-[500px] overflow-y-auto" id="next_schedule_div">
                    </div>
                </div>
            </div>
        </div>
        <script>
            let pathId = window.location.pathname.split('/');
            let machineId = pathId[pathId.length - 1];
            let planVsActualChart;
            let oeeChart;
            $(document).ready(function() {
                $.ajax({
                    url: "{{ url('api/profile/dashboard_machine') }}",
                    type: "POST",
                    data: {
                        machineId: machineId
                    },

                    success: function(res) {

                        const data = res.machine;
                        if (data.condition_id == 0) {

                            $("#power_btn")
                                .removeClass("btn-success")
                                .addClass("btn-danger");

                        } else {

                            $("#power_btn")
                                .removeClass("btn-danger")
                                .addClass("btn-success");
                        }
                        $("#machine_name").text(data.machine_id);

                        $("#shift").text(
                            data.shift == 6 ? "Day" : "Night"
                        );
                        $("#part_no").text(data.part_no);
                        $("#job_num").text(data.job_num);
                        const qtyPlan = Number(data.qty_plan) || 0;
                        const qtyActual = Number(data.qty_actual) || 0;
                        const qtyNg = Number(data.qty_ng) || 0;
                        const qtyGood = Number(data.qty_ok) || 0;
                        $("#qty_plan").text(qtyPlan.toFixed(0));
                        $("#qty_actual").text(qtyActual.toFixed(0));
                        $("#qty_ng").text(qtyNg.toFixed(0));
                        $("#qty_good").text(qtyGood.toFixed(0));
                        const avail = Number(res.oee_availability) || 0;
                        const perf = Number(res.oee_performance) || 0;
                        const qual = Number(res.oee_quality) || 0;
                        $("#availability_text").text(avail.toFixed(0) + '%');
                        $("#performance_text").text(perf.toFixed(0) + '%');
                        $("#quality_text").text(qual.toFixed(0) + '%')
                        initCharts();
                        updatePlanVsActual(qtyPlan, qtyActual);
                        updateOee(
                            Math.round(parseFloat(res.oee_average ?? 0))
                        );
                        const gsph = res.gsph || [];
                        const categories = [];
                        const qtyActualPerHour = [];
                        let previousQty = 0;
                        gsph.forEach(item => {
                            categories.push(
                                new Date(item.cut_off_time)
                                .toLocaleTimeString('en-GB', {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })
                            );
                            const currentQty = Number(item.qty_actual) || 0;
                            const diffQty = currentQty - previousQty;

                            qtyActualPerHour.push(diffQty);

                            previousQty = currentQty;
                        });
                        gsphChart.updateOptions({
                            xaxis: {
                                categories: categories
                            }
                        });
                        gsphChart.updateSeries([{
                            name: 'GSPH',
                            data: qtyActualPerHour
                        }]);
                        const activities = res.activity || [];

                        $("#activity_div").html('');
                        if (activities.length === 0) {
                            $("#activity_div").html(`
                            <div class="text-center text-gray-500 dark:text-gray-400 py-5">
                                No Activity
                            </div>
                        `);
                        } else {

                            activities.forEach((item, index) => {

                                const startDate = formatDate(item.start_date);
                                const endDate = item.end_date ?
                                    formatDate(item.end_date) :
                                    'On Progress';

                                const noteText = item.note ?
                                    `
                            <div class="mt-2 text-sm text-yellow-600 dark:text-yellow-400">
                                Note : ${item.note}
                            </div>
                        ` :
                                    '';

                                $("#activity_div").append(`
                        <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4
                                    bg-gray-50 dark:bg-gray-900 shadow-sm">

                            <div class="flex items-center justify-between mb-2">
                                <div class="font-bold text-gray-800 dark:text-white text-lg">
                                    ${item.activity}
                                </div>

                                <div class="text-xs px-3 py-1 rounded-full
                                            bg-blue-100 text-blue-700
                                            dark:bg-blue-900 dark:text-blue-300">
                                    ${item.shift}
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-2 text-sm">

                                <div>
                                    <span class="font-semibold text-gray-700 dark:text-gray-300">
                                        Start :
                                    </span>

                                    <span class="text-gray-600 dark:text-gray-400">
                                        ${startDate}
                                    </span>
                                </div>

                                <div>
                                    <span class="font-semibold text-gray-700 dark:text-gray-300">
                                        End :
                                    </span>

                                    <span class="text-gray-600 dark:text-gray-400">
                                        ${endDate}
                                    </span>
                                </div>

                                ${noteText}

                            </div>
                        </div>
                    `);

                            });

                        }
                        $("#next_schedule_div").html('');
                        const nextSchedules = res.next_schedule || [];
                        if (nextSchedules.length === 0) {
                            $("#next_schedule_div").html(`
                            <div class="text-center text-gray-500 dark:text-gray-400 py-5">
                                No schedule
                            </div>

                        `);
                        } else {
                            nextSchedules.forEach((item, index) => {
                                const startDate = formatDate(item.start_date);
                                const endDate = item.end_date ?
                                    formatDate(item.end_date) :
                                    'On Progress';

                                const noteText = item.note ?
                                    `
                            <div class="mt-2 text-sm text-yellow-600 dark:text-yellow-400">
                                Note : ${item.note}
                            </div>
                        ` :
                                    '';

                                $("#next_schedule_div").append(`
                        <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4
                                    bg-gray-50 dark:bg-gray-900 shadow-sm">

                            <div class="flex items-center justify-between mb-2">
                                <div class="font-bold text-gray-800 dark:text-white text-lg">
                                    ${item.JobNum}
                                </div>

                                <div class="text-xs px-3 py-1 rounded-full
                                            bg-blue-100 text-blue-700
                                            dark:bg-blue-900 dark:text-blue-300">
                                    ${item.JobCode}
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-2 text-sm">

                                <div>
                                    <span class="font-semibold text-gray-700 dark:text-gray-300">
                                        Start :
                                    </span>

                                    <span class="text-gray-600 dark:text-gray-400">
                                        ${item.StartDate}
                                    </span>
                                </div>

                                ${item.PartNum}

                            </div>
                        </div>
                        `);

                            });
                        }



                    },

                    error: function(xhr) {

                        console.log(xhr.responseJSON?.message);

                    }
                });

            });

            function updatePlanVsActual(plan, actual) {

                if (planVsActualChart) {
                    planVsActualChart.updateSeries([plan, actual]);
                }
            }

            function updateOee(oee) {

                if (oeeChart) {
                    oeeChart.updateSeries([oee]);
                }
            }

            function formatDate(dateString) {

                const date = new Date(dateString);

                return date.toLocaleString('en-GB', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

            }
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

            var gsphOptions = {
                series: [{
                    name: 'GSPH',
                    data: []
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    width: '100%',
                    toolbar: {
                        show: false
                    },
                    redrawOnWindowResize: true,
                    redrawOnParentResize: true
                },
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        columnWidth: '55%'
                    }
                },
                dataLabels: {
                    enabled: true
                },
                xaxis: {
                    categories: [],
                    labels: {
                        style: {
                            colors: '#9ca3af'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#9ca3af'
                        }
                    }
                },
                grid: {
                    borderColor: '#374151'
                }
            };

            let gsphChart;

            document.addEventListener('DOMContentLoaded', function() {

                gsphChart = new ApexCharts(
                    document.querySelector("#gsph_chart"),
                    gsphOptions
                );

                gsphChart.render();
            })

            function initCharts() {

                planVsActualChart = new ApexCharts(
                    document.querySelector("#plan_vs_actual"), {
                        series: [0, 0],
                        chart: {
                            type: 'pie',
                            height: 300,
                        },
                        labels: ['Qty Plan', 'Qty Actual'],
                        colors: ['#3b82f6', '#22c55e'],
                        legend: {
                            position: 'bottom'
                        }
                    }
                );

                planVsActualChart.render();

                oeeChart = new ApexCharts(
                    document.querySelector("#oee_chart"), {
                        series: [0],
                        chart: {
                            height: 350,
                            type: 'radialBar',
                            offsetY: -10
                        },
                        plotOptions: {
                            radialBar: {
                                startAngle: -135,
                                endAngle: 135,
                                dataLabels: {
                                    name: {
                                        fontSize: '16px',
                                        offsetY: 120
                                    },
                                    value: {
                                        offsetY: 76,
                                        fontSize: '22px',
                                        formatter: function(val) {
                                            return val + "%";
                                        }
                                    }
                                }
                            }
                        },
                        stroke: {
                            dashArray: 4
                        },
                        labels: ['OEE'],
                    }
                );

                oeeChart.render();
            }
            const socket = new WebSocket('wss://websocket.summitadyawinsa.co.id')
            socket.onopen = () => {
                console.log('Connected to WebSocket server');
                socket.send(JSON.stringify({
                    action: "subscribe",
                    channel: "machine"
                }));
            };
            socket.onmessage = (event) => {
                try {
                    const msg = JSON.parse(event.data);
                    const response = msg.data.message;
                    const eventTitle = msg.event;
                    if (eventTitle === 'update_stroke' || eventTitle === 'update_stroke_tool') {
                        if (machineId == response.machine_id) {
                            console.log(response);
                            const qtyPlan = Number(response.qty_plan) || 0;
                            const qtyActual = Number(response.qty_actual) || 0;
                            const qtyNg = Number(response.qty_ng) || 0;
                            const qtyGood = Number(response.qty_ok) || 0;
                            $("#qty_actual").text(qtyActual.toFixed(0));
                            $("#qty_ng").text(qtyNg.toFixed(0));
                            $("#qty_good").text(qtyGood.toFixed(0));
                            const avail = Number(response.oee_availability) || 0;
                            const perf = Number(response.oee_performance) || 0;
                            const qual = Number(response.ooe_quality) || 0;
                            $("#availability_text").text(avail.toFixed(0) + '%');
                            $("#performance_text").text(perf.toFixed(0) + '%');
                            $("#quality_text").text(qual.toFixed(0) + '%')
                            updatePlanVsActual(
                                Number(Math.round(response.qty_plan ?? 0)),
                                Number(Math.round(response.qty_actual ?? 0))
                            );

                            updateOee(
                                Math.round(parseFloat(response.oee_average ?? 0))
                            );
                            const gsphLabels = response.gsphLabel || {};
                            const gsphValues = response.gsphValues || [];

                            const categories = Object.keys(gsphLabels);

                            const qtyActualPerHour = [];

                            let previousQty = 0;

                            gsphValues.forEach(item => {
                                const currentQty = Number(item) || 0;
                                if (currentQty === 0) {
                                    previousQty = 0;
                                    return;
                                }
                                let diffQty = currentQty - previousQty;
                                if (diffQty < 0) {
                                    diffQty = currentQty;
                                }
                                qtyActualPerHour.push(diffQty);
                                previousQty = currentQty;
                            });
                            gsphChart.updateOptions({
                                xaxis: {
                                    categories: categories
                                }
                            });
                            gsphChart.updateSeries([{
                                name: 'GSPH',
                                data: qtyActualPerHour
                            }]);
                        }
                    } else if (eventTitle == 'start-downtime' || eventTitle == 'finish-machine') {
                        if (machineId == response.machine_id) {
                            window.location.reload();
                        }
                    }
                } catch (e) {
                    console.error(e);
                }
            };
            socket.onclose = () => {
                console.log('Disconnected from WebSocket')
            }
            socket.onerror = (err) => {
                console.log('WebSocket error:', err)
            }
        </script>
</x-layout.default>
