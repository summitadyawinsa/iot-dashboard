<x-layout.default>
    <script defer src="/assets/js/apexcharts.js"></script>

    <div id="analytics">
        <span id="inpt_machine" hidden></span>
        <div class="pt-5 -mt-5">
            <div class="grid lg:grid-cols-4 md:grid-cols-1 lg:gap-3 d:gap-2 mb-3">
                <div
                    class="panel col-span-1 overflow-hidden before:absolute before:-right-44 before:top-0 before:bottom-0 before:m-auto before:rounded-full before:w-96 before:h-96 content-between gap-6">
                    <div class="text-center text-black bg-white gap-2 mb-2 rounded-lg border">
                        <div class="align-center shadow-[0_0_2px_0_#bfc9d4] rounded-lg p-1">
                            <p class="align-center text-black p-1 lg:text-2xl md:text-xl">
                                Job Number
                            </p>
                            <span id="job_number" class="font-bold lg:text-2xl md:text-xl "></span>
                        </div>
                    </div>

                    <div class="text-center text-black bg-white z-[7] mb-3 rounded-lg border">
                        <div class="align-center shadow-[0_0_2px_0_#bfc9d4] rounded-lg p-1">
                            <p class="align-center text-black bg-white p-1 lg:text-2xl md:text-xl">
                                Part No.
                            </p>
                            <span id="part_number" class="font-bold lg:text-2xl md:text-xl"></span>
                        </div>
                    </div>
                    <div class="text-center text-black bg-white z-[7] mb-3 border rounded-lg">
                        <div class="align-center shadow-[0_0_2px_0_#bfc9d4] rounded-lg p-1">
                            <p class="align-center text-black bg-white p-1 lg:text-2xl md:text-xl">
                                Customer.
                            </p>
                            <span id="customer" class="font-bold lg:text-2xl md:text-xl"></span>
                        </div>
                    </div>
                    <div class="text-center text-black bg-white z-[7] mb-3 border rounded-lg">
                        <div class="align-center shadow-[0_0_2px_0_#bfc9d4] rounded-lg p-1">
                            <p class="align-center text-black bg-white p-1 lg:text-2xl md:text-xl">
                                Time start.
                            </p>
                            <span id="time_start" class="font-bold lg:text-2xl md:text-xl"></span>
                        </div>
                    </div>
                    <div class="text-center text-black bg-white z-[7] border rounded-lg">
                        <div class="align-center shadow-[0_0_2px_0_#bfc9d4] rounded-lg p-1">
                            <p class="align-center text-black bg-white p-1 lg:text-2xl md:text-xl">
                                Time Finish.
                            </p>
                            <span id="time_finish" class="font-bold lg:text-2xl md:text-xl"></span>
                        </div>
                    </div>
                </div>

                <div
                    class="panel col-span-1 h-full overflow-hidden before:absolute before:-right-44 before:top-0 before:bottom-0 before:m-auto before:rounded-full before:w-96 before:h-96 content-between gap-6">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
                        <div class="text-center text-white-light border border-white z-[7] rounded">
                            <div class="align-center shadow-[0_0_2px_0_#bfc9d4] rounded p-1 bg-blue-600">
                                <div class="text-center">
                                    <span
                                        class="ltr:ml-5 rtl:mr-5 lg:text-3xl md:text-xl font-bold dark:text-white-light">Plan
                                        Vs
                                        Actual Achievement</span>
                                </div>
                            </div>
                        </div>
                        <canvas id="planVsActualChart" class="mx-auto mt-4"
                            style="max-width: 300px; max-height: 300px;"></canvas>
                    </div>
                </div>
                <div
                    class="panel col-span-2 h-full flex flex-col overflow-hidden gap-4 before:absolute before:-right-44 before:top-0 before:bottom-0 before:m-auto before:rounded-full before:w-96 before:h-96">

                    <!-- Judul Atas -->
                    <div class="text-center text-white-light border border-white z-[7] rounded">
                        <div class="shadow-[0_0_2px_0_#bfc9d4] rounded p-1 bg-blue-600">
                            <span class="lg:text-3xl md:text-xl font-bold" id="gsph_achievement_title"></span>
                        </div>
                    </div>

                    <!-- Konten -->
                    <div class="flex flex-1 justify-around items-stretch">

                        <!-- STD GSPH -->
                        <div class="flex flex-col justify-between items-center w-full px-2">
                            <div class="text-white lg:text-3xl md:text-xl font-semibold" id="std_gsph_title"></div>
                            <div class="flex-grow flex items-center justify-center">
                                <div class="font-bold" style="color: #4ade80; font-size: 5vw;" id="std_gsph">
                                </div>
                            </div>
                            <div class="text-white text-base text-center">
                                <div class="lg:text-3xl md:text-xl font-semibold">Std.Cycletime</div> <span
                                    id="std_cycletime" class="font-bold text-3xl"></span>
                            </div>
                        </div>

                        <!-- ACT GSPH -->
                        <div class="flex flex-col justify-between items-center w-full px-2">
                            <div class="text-white lg:text-3xl md:text-xl font-semibold" id="act_gsph_title"></div>
                            <div class="flex-grow flex items-center justify-center">
                                <div class="font-bold text-white" style="font-size: 5vw;" id="act_gsph"></div>
                            </div>
                            <div class="text-white text-base text-center">
                                <div class="lg:text-3xl md:text-xl font-semibold">Act.Cycletime</div> <span
                                    id="act_cycletime" class="font-bold text-3xl"></span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="grid lg:grid-cols-4 md:grid-cols-1 lg:gap-3 d:gap-2 mb-2">
                <div
                    class="panel col-span-1 overflow-hidden before:absolute before:-right-44 before:top-0 before:bottom-0 before:m-auto before:rounded-full before:w-96 before:h-96 content-between gap-6">
                    <div class="text-center text-white-light border border-white gap-4 mb-3">
                        <div class="align-center shadow-[0_0_2px_0_#bfc9d4] rounded p-1 bg-blue-600">
                            <p class="align-center text-white-light p-1 lg:text-3xl md:text-xl font-bold">
                                OEE%
                            </p>
                        </div>
                    </div>
                    <canvas id="oee_chart" class="mx-auto mt-4 w-full max-w-[250px] max-h-[250px]"></canvas>
                    <div class="flex justify-around">
                        <div class="text-center font-bold text-white text-2xl">
                            <div>A</div>
                            <span id="availability"></span>
                        </div>
                        <div class="text-center font-bold text-white text-2xl">
                            <div>P</div>
                            <span id="performance"></span>
                        </div>
                        <div class="text-center text-white text-2xl font-bold">
                            <div>Q</div>
                            <span id="quality"></span>
                        </div>
                    </div>
                </div>
                <div
                    class="panel col-span-2 h-full flex flex-col justify-between gap-6 relative overflow-hidden before:absolute before:-right-44 before:top-0 before:bottom-0 before:m-auto before:rounded-full before:w-96 before:h-96">
                    <div class="flex flex-col items-center justify-center flex-1">
                        <div class="text-center text-white-light border border-white z-[7] rounded w-full">
                            <div class="shadow-[0_0_2px_0_#bfc9d4] rounded p-1 bg-blue-600">
                                <span class="lg:text-3xl md:text-xl font-bold dark:text-white-light">Prod.
                                    Downtime</span>
                            </div>
                        </div>
                        <canvas id="downtime_chart" class="w-full h-72 mt-2"></canvas>
                    </div>
                    <div class="flex flex-col items-center justify-center flex-1">
                        <div class="text-center text-white-light border border-white z-[7] rounded w-full">
                            <div class="shadow-[0_0_2px_0_#bfc9d4] rounded p-1 bg-blue-600">
                                <span class="lg:text-3xl md:text-xl font-bold dark:text-white-light"> <span
                                        id="monitoringTitle"></span> Monitoring</span>
                            </div>
                        </div>
                        <canvas id="gsph_monitoring" class="w-full h-72 mt-2"></canvas>
                    </div>
                </div>
                <div
                    class="panel col-span-1 h-full overflow-hidden before:absolute before:-right-44 before:top-0 before:bottom-0 before:m-auto before:rounded-full before:w-96 before:h-96 content-between gap-6">
                    <div class="text-center text-white-light border border-white z-[7] rounded">
                        <div class="align-center shadow-[0_0_2px_0_#bfc9d4] rounded p-1 bg-blue-600">
                            <div class="text-center">
                                <span
                                    class="ltr:ml-5 rtl:mr-5 lg:text-3xl md:text-xl font-bold dark:text-white-light">Log
                                    Activity</span>
                            </div>
                        </div>
                    </div>
                    <div id="activity"></div>
                </div>
            </div>
        </div>
    </div>

    <input type="number" id="inpt_standard_sph" hidden readonly>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>

    <script>
        const socket = new WebSocket('wss://websocket.summitadyawinsa.co.id');
        socket.onopen = () => {
            console.log('Connected to WebSocket server');
            // Subscribe ke channel
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
                const pathSegments = window.location.pathname.split('/');
                const machine_id = pathSegments[pathSegments.length - 1];
                if (response.machine_id !== machine_id) {
                    return;
                }
                const actCycle = parseFloat(response.act_cycletime).toFixed(0)
                const gsph = parseFloat(response.current_gsph) || 0;
                if (gsph > 0) {
                    document.getElementById('act_cycletime').textContent = actCycle;
                    document.getElementById('act_gsph').textContent = parseFloat(response.current_gsph).toFixed(0)
                }
                lastActual = 0
                lastRemaining = 0
                if (response === 'update_stroke_tool') {
                    console.log(response)
                    const plan = parseInt(response.qty_plan);
                    const actual = parseInt(response.qty_actual);
                    const remaining = Math.max(plan - actual);
                    const percentage = plan > 0 ? Math.round((actual / plan) * 100) : 0;
                    lastActual = actual;
                    lastRemaining = remaining;
                    if (window.planVsActualChartInstance) {
                        const chart = window.planVsActualChartInstance;
                        chart.data.datasets[0].data = [lastActual, lastRemaining];
                        chart.update();
                    }
                }
                if (response === 'start-downtime-tool' || eventTitle == 'finish-downtime-tool') {
                    if (window.downtimeChartInstance) {
                        const chartDT = window.downtimeChartInstance;
                        chartDT.data.labels = response.downtimeChartLabels || [];
                        chartDT.data.datasets[0].data = response.downtimeChartValues || [];
                        chartDT.update();
                    }

                    const container = document.getElementById("activity");
                    const logs = response.log_activity || [];
                    let html = `<div class="space-y-6 mt-4">`;
                    logs.forEach((item, index) => {
                        html += `
                <div class="flex items-start space-x-3 pl-2" id="activity-${index}">
                    <div class="text-red-500 lg:text-xl md:text-lg select-none">🔔</div>
                    <div>
                        <p class="text-white lg:text-xl md:text-lg font-bold">
                            <strong>${item.activity}</strong>
                        </p>
                        <p class="text-white font-bold lg:text-xl md:text-sm">from ${item.start_date?.substring(11) || '-'} to ${item.end_date?.substring(11) || '-'}</p>
                        <button onclick="noteShow(${index})">Read more...</button>
                    </div>
                </div>
                <div id="toast-${index}" class="flex items-center w-full max-w-xs p-1 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800 hidden" role="alert">
                        <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-orange-500 bg-orange-100 rounded-lg dark:bg-orange-700 dark:text-orange-200">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
                            </svg>
                            <span class="sr-only">Warning icon</span>
                        </div>
                        <div class="ms-3 text-sm font-normal">${item.note}</div>
                        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-warning" aria-label="Close" onclick="closeNote(${index})">
                            <span class="sr-only">Close</span>
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                        </button>
                    </div>`;
                    });
                    html += `</div>`;
                    container.innerHTML = html;
                }
                if (eventTitle == 'start-machine-tool' || eventTitle == 'finish-tool') {
                    window.location.reload()
                }
            } catch (error) {
                console.error(error)
            }
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const pathSegments = window.location.pathname.split('/');
            const machine_id = pathSegments[3]
            const toolID = pathSegments[4]
            const line = pathSegments[1];
            const url = `https://${window.location.host}/api/dashboard-tool/${machine_id}/${toolID}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (!data.machine) {
                        document.getElementById('job_number').textContent = 'N/A';
                        return;
                    }

                    if (line === 'assy') {
                        document.getElementById('gsph_achievement_title').textContent = 'JPH Achievement';
                        document.getElementById('std_gsph_title').textContent = 'Std.JPH';
                        document.getElementById('act_gsph_title').textContent = 'Act.JPH';
                        document.getElementById('monitoringTitle').textContent = 'JPH'
                    } else {
                        document.getElementById('gsph_achievement_title').textContent = 'GSPH Achievement';
                        document.getElementById('std_gsph_title').textContent = 'Std.GSPH';
                        document.getElementById('act_gsph_title').textContent = 'Act.GSPH';
                        document.getElementById('monitoringTitle').textContent = 'GSPH'
                    }

                    const machine = data.machine;
                    const robotName = machine.machine_id.split('-').pop()
                    const showRobotName = 'RBT-' + robotName
                    document.getElementById('job_number').textContent = machine.job_num || '-';
                    document.getElementById('part_number').textContent = machine.part_no || '-';
                    if (line === 'assy') {
                        document.getElementById('customer').textContent = machine.customer + ' (' +
                            showRobotName + ')' || '-';
                    } else {
                        document.getElementById('customer').textContent = machine.customer || '-';
                    }

                    const startedAt = machine.started_at ? machine.started_at.split(' ')[1] : '-';
                    let finishedAt = '-'
                    if (machine.status_finish === false || machine.status_finish === 1) {
                        const time_finish = machine.finished_at
                        const timeReplace = time_finish.replace('T', ' ');
                        const date = new Date(timeReplace);
                        finishedAt = date.toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit'
                        });
                    } else {
                        finishedAt = '-';
                    }
                    document.getElementById('time_start').textContent = startedAt;
                    document.getElementById('time_finish').textContent = finishedAt;

                    const plan = parseInt(machine.qty_plan || 0);
                    const actual = parseInt(machine.qty_actual || 0);
                    const remaining = Math.max(plan - actual, 0);
                    const percentage = plan > 0 ? Math.round((actual / plan) * 100) : 0;

                    const planCtx = document.getElementById('planVsActualChart').getContext('2d');
                    if (!window.planVsActualChartInstance) {
                        window.planVsActualChartInstance = new Chart(planCtx, {
                            type: 'doughnut',
                            data: {
                                labels: ['Actual', 'Remaining'],
                                datasets: [{
                                    data: [actual, remaining],
                                    backgroundColor: ['#4ade80', '#e5e7eb'],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                cutout: '75%',
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        enabled: true
                                    }
                                }
                            },
                            plugins: [{
                                id: 'centerText',
                                beforeDraw(chart) {
                                    const {
                                        width,
                                        height,
                                        ctx
                                    } = chart;
                                    const text = `${percentage}%`;
                                    const fontSize = (height / 6).toFixed(2);
                                    ctx.save();
                                    ctx.font = `${fontSize}px Arial`;
                                    ctx.fillStyle = '#fff';
                                    ctx.textBaseline = 'middle';
                                    const textX = (width - ctx.measureText(text).width) / 2;
                                    const textY = height / 2;
                                    ctx.fillText(text, textX, textY);
                                    ctx.restore();
                                }
                            }]
                        });
                    }

                    // GSPH & Cycle Time
                    document.getElementById('std_gsph').textContent = parseFloat(machine.standard_sph).toFixed(
                        0) || 0;
                    document.getElementById('act_gsph').textContent = parseFloat(machine.current_gsph).toFixed(
                        0) || 0;

                    const stdCycle = machine.standard_sph > 0 ? (3600 / machine.standard_sph).toFixed(2) : '0';
                    const actCycle = machine.current_gsph > 0 ? (3600 / machine.current_gsph).toFixed(2) : '0';

                    document.getElementById('std_cycletime').textContent = stdCycle;
                    document.getElementById('act_cycletime').textContent = actCycle;

                    // OEE Chart
                    const availability = data.oee_availability || 0;
                    const performance = data.oee_performance || 0;
                    const quality = data.oee_quality || 0;
                    const oeePercent = ((availability + performance + quality) / 3).toFixed(0);

                    document.getElementById('availability').textContent = `${availability.toFixed(0)}%`;
                    document.getElementById('performance').textContent = `${performance.toFixed(0)}%`;
                    document.getElementById('quality').textContent = `${quality.toFixed(0)}%`;

                    const oeeCtx = document.getElementById('oee_chart').getContext('2d');
                    if (!window.oeeChartInstance) {
                        window.oeeChartInstance = new Chart(oeeCtx, {
                            type: 'doughnut',
                            data: {
                                labels: ['OEE', 'Remaining'],
                                datasets: [{
                                    data: [oeePercent, 100 - oeePercent],
                                    backgroundColor: ['#4ade80', '#e5e7eb'],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                cutout: '75%',
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        enabled: true
                                    }
                                }
                            },
                            plugins: [{
                                id: 'centerTextOEE',
                                beforeDraw(chart) {
                                    const {
                                        width,
                                        height,
                                        ctx
                                    } = chart;
                                    const text = `${oeePercent}%`;
                                    const fontSize = (height / 6).toFixed(2);
                                    ctx.save();
                                    ctx.font = `${fontSize}px Arial`;
                                    ctx.fillStyle = '#fff';
                                    ctx.textBaseline = 'middle';
                                    const textX = (width - ctx.measureText(text).width) / 2;
                                    const textY = height / 2;
                                    ctx.fillText(text, textX, textY);
                                    ctx.restore();
                                }
                            }]
                        });
                    }

                    // Downtime Bar Chart
                    const downtimeCtx = document.getElementById('downtime_chart').getContext('2d');
                    if (!window.downtimeChartInstance) {
                        window.downtimeChartInstance = new Chart(downtimeCtx, {
                            type: 'bar',
                            data: {
                                labels: data.downtimeChartLabels,
                                datasets: [{
                                    label: 'Downtime (minutes)',
                                    data: data.downtimeChartValues,
                                    backgroundColor: '#4ade80',
                                    'maxBarThickness': 50
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        enabled: true
                                    }
                                },
                                scales: {
                                    x: {
                                        ticks: {
                                            color: 'white',
                                            font: {
                                                size: 12
                                            }
                                        }
                                    },
                                    y: {
                                        ticks: {
                                            color: 'white',
                                            font: {
                                                size: 10
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                    const gsphMonitoringCtx = document.getElementById('gsph_monitoring').getContext('2d')
                    if (!window.gsphMonitoringChartInstance) {
                        window.gsphMonitoringChartInstance = new Chart(gsphMonitoringCtx, {
                            type: 'bar',
                            data: {
                                labels: data.gsphLabels,
                                datasets: [{
                                    label: 'GSPH Monitoring',
                                    data: data.gsphValues,
                                    backgroundColor: '#4ade80',
                                    'maxBarThickness': 50
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        enabled: true
                                    }
                                },
                                scales: {
                                    x: {
                                        ticks: {
                                            color: 'white',
                                            font: {
                                                size: 12
                                            }
                                        }
                                    },
                                    y: {
                                        ticks: {
                                            color: 'white',
                                            font: {
                                                size: 10
                                            }
                                        }
                                    }
                                }
                            }
                        })
                    }
                    // Activity log
                    const container = document.getElementById("activity");
                    const logs = data.activity || [];
                    let html = `<div class="space-y-6 mt-4">`;
                    logs.forEach((item, index) => {
                        html += `
                        <div class="flex items-start space-x-3 pl-2" id="activity-${index}">
                            <div class="text-red-500 lg:text-xl md:text-lg select-none">🔔</div>
                            <div>
                                <p class="text-white lg:text-xl md:text-lg font-bold">
                                    <strong>${item.activity}</strong>
                                </p>
                                <p class="text-white font-bold lg:text-xl md:text-sm">from ${item.start_date?.substring(11) || '-'} to ${item.end_date?.substring(11) || '-'}</p>
                                <button onclick="noteShow(${index})">Read more...</button>
                            </div>
                        </div>
                        <div id="toast-${index}" class="flex items-center w-full max-w-xs p-1 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800 hidden" role="alert">
                        <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-orange-500 bg-orange-100 rounded-lg dark:bg-orange-700 dark:text-orange-200">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
                            </svg>
                            <span class="sr-only">Warning icon</span>
                        </div>
                        <div class="ms-3 text-sm font-normal">${item.note}</div>
                        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-warning" aria-label="Close" onclick="closeNote(${index})">
                            <span class="sr-only">Close</span>
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                        </button>
                    </div>`;
                    });
                    html += `</div>`;
                    container.innerHTML = html;
                })
                .catch(err => {
                    console.error('Terjadi error:', err);
                });
        });
    </script>
    <script>
        function noteShow(index) {
            const toast = document.getElementById(`toast-${index}`);
            toast.classList.remove('hidden');
            const activityDiv = document.getElementById(`activity-${index}`);
            activityDiv.classList.add('hidden');
        }

        function closeNote(index) {
            const toast = document.getElementById(`toast-${index}`);
            toast.classList.add('hidden');
            const activityDiv = document.getElementById(`activity-${index}`);
            activityDiv.classList.remove('hidden');
        }
    </script>
</x-layout.default>
