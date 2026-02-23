<x-layout.default>
    <script defer src="/assets/js/apexcharts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <div id="analytics">
        <span id="inpt_machine" hidden></span>
        <div class="pt-5 -mt-5">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-2">
                <div
                    class="panel h-full overflow-hidden before:absolute before:-right-44 before:top-0 before:bottom-0 before:m-auto before:rounded-full before:w-96 before:h-96 content-between gap-6">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
                        <div class="text-center text-white-light z-[7]">
                            <div class="align-center text-3xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2 bg-blue-600">
                                <div class="text-center">
                                    <span class="ltr:ml-5 rtl:mr-5 xl:text-3xl sm:text-3xl dark:text-white-light">Summary
                                        plan vs
                                        Actual achievement</span>
                                </div>
                            </div>
                        </div>
                        <canvas id="planVsActualChart" class="mx-auto mt-4"></canvas>
                    </div>
                </div>
                <div
                    class="panel h-full overflow-hidden before:absolute before:-right-44 before:top-0 before:bottom-0 before:m-auto before:rounded-full before:w-96 before:h-96 content-between gap-6">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 h-full">
                        <div class="text-center text-white-light z-[7]">
                            <div class="align-center text-3xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2 bg-blue-600">
                                <div class="text-center">
                                    <span
                                        class="ltr:ml-5 rtl:mr-5 xl:text-3xl sm:text-3xl dark:text-white-light">Summary
                                        <span id="nameSummaryTitle"></span> achievement</span>
                                </div>
                            </div>
                        </div>
                        <canvas id="summary_gsph" class="w-full h-full block"></canvas>
                    </div>
                </div>
                <div
                    class="panel h-full overflow-hidden before:absolute before:-right-44 before:top-0 before:bottom-0 before:m-auto before:rounded-full before:w-96 before:h-96 content-between gap-6">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 h-full">
                        <div class="text-center text-white-light z-[7]">
                            <div class="align-center text-3xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2 bg-blue-600">
                                <div class="text-center">
                                    <span
                                        class="ltr:ml-5 rtl:mr-5 xl:text-3xl sm:text-3xl dark:text-white-light">Summary
                                        Downtime</span>
                                </div>
                            </div>
                        </div>
                        <canvas id="downtime_chart" class="w-full h-full block"></canvas>
                    </div>
                </div>
            </div>
            <div class="grid sm:grid-cols-3 lg:grid-cols-3 gap-3 mb-2">
                <div
                    class="col-span-2 panel h-full overflow-hidden before:absolute before:-right-44 before:top-0 before:bottom-0 before:m-auto before:rounded-full before:w-96 before:h-96 content-between gap-6">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
                        <div class="text-center text-white-light z-[7]">
                            <div class="align-center text-3xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2 bg-blue-600">
                                <div class="text-center">
                                    <span
                                        class="ltr:ml-5 rtl:mr-5 xl:text-3xl sm:text-sm dark:text-white-light">Performance
                                        OEE%</span>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4 px-4">
                            <!-- Availability -->
                            <div class="flex items-center justify-center space-x-4">
                                <h1 class="text-white font-bold lg:text-3xl md:text-lg w-20 text-right">A%</h1>
                                <div class="flex-1 h-16 bg-white rounded-full overflow-hidden">
                                    <div id="availability_bar" class="h-full rounded-full"
                                        style="background-color:#4ade80; width: 0%"></div>
                                </div>
                                <div id="availability_label"
                                    class="text-white font-bold lg:text-3xl md:text-lg text-left min-w-[4.5rem]">0%
                                </div>
                            </div>

                            <!-- Performance -->
                            <div class="flex items-center justify-center space-x-4">
                                <h1 class="text-white font-bold lg:text-3xl md:text-lg w-20 text-right">P%</h1>
                                <div class="flex-1 h-16 bg-white rounded-full overflow-hidden">
                                    <div id="performance_bar" class="h-full rounded-full"
                                        style="background-color:#4ade80; width: 0%"></div>
                                </div>
                                <div id="performance_label"
                                    class="text-white font-bold lg:text-3xl md:text-lg text-left min-w-[4.5rem]">0%
                                </div>
                            </div>

                            <!-- Quality -->
                            <div class="flex items-center justify-center space-x-4">
                                <h1 class="text-white font-bold lg:text-3xl md:text-lg w-20 text-right">Q%</h1>
                                <div class="flex-1 h-16 bg-white rounded-full overflow-hidden">
                                    <div id="quality_bar" class="h-full rounded-full"
                                        style="background-color:#4ade80; width: 0%"></div>
                                </div>
                                <div id="quality_label"
                                    class="text-white font-bold lg:text-3xl md:text-lg text-left min-w-[4.5rem]">
                                    0%
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <div>
                                    <div class="text-white text-lg">Qty Plan</div>
                                    <span class="text-white text-xl" id="qty_plan_span"></span>
                                </div>
                                <div>
                                    <div class="text-white text-lg">Qty Act</div>
                                    <span class="text-white text-xl" id="qty_span"></span>
                                </div>
                                <div>
                                    <div class="text-white text-lg">Opr Time</div>
                                    <span class="text-white text-xl" id="opr_time_span"></span>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <div>
                                    <div class="text-white text-lg">DT Duration</div>
                                    <span class="text-white text-xl" id="downtime_span"></span>
                                </div>
                                <div>
                                    <div class="text-white text-lg">OPR - DT</div>
                                    <span class="text-white text-xl" id="opr_dt_span"></span>
                                </div>
                                <div>
                                    <div class="text-white text-lg">CT</div>
                                    <span class="text-white text-xl" id="ct_span"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="col-span-1 panel h-full overflow-hidden before:absolute before:-right-44 before:top-0 before:bottom-0 before:m-auto before:rounded-full before:w-96 before:h-96 content-between gap-6">
                    <div class="text-center text-white-light z-[7]">
                        <div class="align-center text-3xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2 bg-blue-600">
                            <div class="text-center">
                                <span class="ltr:ml-5 rtl:mr-5 xl:text-3xl sm:text-3xl dark:text-white-light">OEE</span>
                            </div>
                        </div>
                    </div>
                    <canvas id="oeeChart" class="mx-auto mt-4" style="max-width: 300px; max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <input type="number" id="inpt_standard_sph" hidden readonly>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script>
        document.getElementById('planVsActualChart').width = 400;
        document.getElementById('planVsActualChart').height = 400;
        document.getElementById('summary_gsph').width = 400;
        document.getElementById('summary_gsph').height = 400;
        document.getElementById('downtime_chart').width = 400;
        document.getElementById('downtime_chart').height = 400;
        document.getElementById('oeeChart').width = 400;
        document.getElementById('oeeChart').height = 400;
        document.addEventListener('DOMContentLoaded', function() {
            const pathSegments = window.location.pathname.split('/');
            const machine_id = pathSegments[3]
            let url
            if (machine_id === 'RSW-5H45-10' || machine_id === 'RSW-5H45-09') {
                url = `https://${window.location.host}/api/machine/v2/dashboard-by-machine-tool/${machine_id}`
            } else {
                url = `https://${window.location.host}/api/machine/v2/dashboard-by-machine/${machine_id}`
            }
            axios.post(url)
                .then(response => {
                    const message = response.data
                    const qty_actual = message.qty_actual;
                    const qty_plan = message.qty_plan;
                    const remaining = qty_plan - qty_actual
                    document.getElementById('qty_plan_span').textContent = qty_plan
                    document.getElementById('qty_span').textContent = qty_actual
                    const opr_time = parseFloat(message.opr_time)
                    document.getElementById('opr_time_span').textContent = opr_time.toFixed(2)
                    const dtDrt = parseFloat(message.dtDuration)
                    document.getElementById('downtime_span').textContent = dtDrt.toFixed(2)
                    const oprDT = parseFloat(message.oprDT)
                    document.getElementById('opr_dt_span').textContent = oprDT.toFixed(2)
                    const ct = parseFloat(message.ct)
                    document.getElementById('ct_span').textContent = ct.toFixed(2)
                    // Destroy previous chart
                    if (window.planVsActualChartInstance) window.planVsActualChartInstance.destroy();
                    const ctx = document.getElementById('planVsActualChart').getContext('2d');
                    window.planVsActualChartInstance = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Actual', 'Remaining'],
                            datasets: [{
                                data: [qty_actual, remaining],
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
                                const percentage = chart.options.plugins.centerText
                                    ?.percentage ?? Math.round((qty_actual / qty_plan) * 100);
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

                    // Summary GSPH Chart
                    if (window.summaryGsphInstance) window.summaryGsphInstance.destroy();
                    const summaryGsphCtx = document.getElementById('summary_gsph').getContext('2d');
                    window.summaryGsphInstance = new Chart(summaryGsphCtx, {
                        type: 'bar',
                        data: {
                            labels: message.gsphLabel,
                            datasets: [{
                                label: 'Summary Gsph (hours)',
                                data: message.gsphValues,
                                backgroundColor: '#4ade80',
                                borderColor: '#fff',
                                borderWidth: 1,
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
                                },
                                datalabels: {
                                    color: 'white',
                                    anchor: 'end',
                                    align: 'end',
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    },
                                    formatter: value => value
                                }
                            },
                            scales: {
                                x: {
                                    ticks: {
                                        color: 'white',
                                        font: {
                                            size: 20
                                        }
                                    },
                                    offset: false,
                                    grid: {
                                        offset: false
                                    }
                                },
                                y: {
                                    display: false
                                }
                            }
                        },
                        plugins: [ChartDataLabels]
                    });

                    // Summary Downtime Chart
                    if (window.summaryDowntimeInstance) window.summaryDowntimeInstance.destroy();
                    const summaryDowntimeCtx = document.getElementById('downtime_chart').getContext('2d');
                    const formattedLabels = message.downtimeChartLabels.map(label =>
                        label.split(' ').map(word => word[0]).join('').toUpperCase()
                    );
                    window.summaryDowntimeInstance = new Chart(summaryDowntimeCtx, {
                        type: 'bar',
                        data: {
                            labels: formattedLabels,
                            datasets: [{
                                label: 'Summary Downtime (hours)',
                                data: message.downtimeChartValues,
                                backgroundColor: '#4ade80',
                                borderColor: '#fff',
                                borderWidth: 1,
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
                                            size: 20
                                        }
                                    },
                                    offset: false,
                                    grid: {
                                        offset: false
                                    }
                                },
                                y: {
                                    display: false
                                }
                            }
                        }
                    });

                    // OEE Calculation
                    let availability = message.oee_availability;
                    let quality = message.oee_quality
                    let performance = message.oee_performance

                    document.getElementById('availability_bar').style.width = availability + '%';
                    document.getElementById('availability_label').innerText = availability.toFixed(0) + '%';
                    document.getElementById('performance_bar').style.width = performance + '%';
                    document.getElementById('performance_label').innerText = performance.toFixed(0) + '%';
                    document.getElementById('quality_bar').style.width = quality + '%';
                    document.getElementById('quality_label').innerText = quality.toFixed(0) + '%';

                    const oeePercent = ((availability + performance + quality) / 3).toFixed(0);
                    if (window.oeeChartInstance) window.oeeChartInstance.destroy();
                    const oeeCtx = document.getElementById('oeeChart').getContext('2d');
                    window.oeeChartInstance = new Chart(oeeCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Remaining', 'OEE'],
                            datasets: [{
                                data: [oeePercent, 100 - oeePercent],
                                backgroundColor: ['#4ade80', '#e5e7eb'],
                                borderWidth: 0
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
                })
        })
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
                const pathName = window.location.pathname.split('/')
                const machineID = pathName[pathName.length - 1]
                if (eventTitle == 'update_stroke') {
                    if (response.machine_id == machineID) {
                        console.log(response)
                        const planQty = parseInt(response.qty_plan) || 0
                        const actQty = parseInt(response.actual_qty) || 0
                        const remaining = Math.max(planQty - actQty, 0);
                        const percentage = planQty > 0 ? Math.round((actQty / planQty) * 100) : 0;
                        if (window.planVsActualChartInstance) {
                            const chart = window.planVsActualChartInstance
                            chart.data.datasets[0].data = [actQty, remaining];
                            if (chart.options.plugins.centerText) {
                                chart.options.plugins.centerText.percentage = percentage;
                            }
                            chart.update();
                        }
                        const avail = response.ooe - availability
                        document.getElementById('availability_label').textContent = avail.toFixed(0)
                        const perform = response.oee - performance
                        document.getElementById('performance-label').textContent = perform.toFixed(0)
                        const qual = response.oee - quality
                        document.getElementById('quality_label').textContent = qual.toFixed(0)
                    }
                }
            } catch (error) {

            }
        }
        socket.onclose = () => {
            console.log('Disconnected from WebSocket')
        }
        socket.onerror = (err) => {
            console.log('WebSocket error:', err)
        }
    </script>
</x-layout.default>
