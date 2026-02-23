<x-layout.default>
    <script defer src="/assets/js/apexcharts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <style>
        #tables_container tbody tr:hover {
            background-color: #727e93;
            cursor: pointer;
        }

        #tables_container tbody tr {
            background-color: #4B5563;
            color: whitesmoke;
            cursor: pointer;
            font-size: small;
        }

        #tables_container thead tr {
            color: whitesmoke;
        }

        #current_year::-webkit-calendar-picker-indicator,
        #date_daily::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }
    </style>

    <style>
        select[name="monitoring_table_A1_length"],
        select[name="monitoring_table_A2_length"],
        select[name="monitoring_table_A3_length"],
        select[name="monitoring_table_A4_length"],
        select[name="monitoring_table_B1_length"],
        select[name="monitoring_table_B2_length"],
        select[name="monitoring_table_D14_length"],
        select[name="monitoring_table_P9_length"],
        select[name="monitoring_table_A5_length"],
        select[name="monitoring_table_A6_length"] {
            width: 80px;
        }
    </style>
    <div x-data="analytics">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>Production</span>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>Production Achiev</span>
            </li>
        </ul>
        <div class="pt-5">
            <div class="grid sm:grid-cols-2 xl:grid-cols-12 gap-6 mb-6">
                <?php
$currentYear = date('Y');
$startYear = $currentYear - 5;
$currentMonth = date('Y-m');
                ?>
                <div class="panel h-full sm:col-span-12 xl:col-span-12">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-3">
                            <span class="text-xl font-bold">By Years</span>
                            <span></span>
                            <input id="current_year" type="number" class="form-input" value="<?= $currentYear ?>"
                                style="color: white;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart"
                                x-on:click="() => ChartRecall1(inpt_val_planing_data.value, inpt_val_entry_data.value, inpt_val_received_data.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_planing_data" value="" hidden>
                            <input type="text" id="inpt_val_entry_data" value="" hidden>
                            <input type="text" id="inpt_val_received_data" value="" hidden>
                            <div x-ref="BoxChart2" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>
                <div class="panel h-full sm:col-span-12 xl:col-span-12">
                    <div class="grid grid-cols-3 sm:grid-cols-3 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-3">
                            <span class="text-xl font-bold">By Months</span>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_month"
                                x-on:click="() => ChartRecall2(inpt_val_planing_month.value, inpt_val_entry_month.value, inpt_val_received_month.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_planing_month" value="" hidden>
                            <input type="text" id="inpt_val_entry_month" value="" hidden>
                            <input type="text" id="inpt_val_received_month" value="" hidden>
                            <div x-ref="BoxChart3" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>
                <!-- Daily -->
                <div class="panel h-full sm:col-span-12 xl:col-span-12">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div>
                            <div class="grid grid-cols-3">
                                <span class="text-xl font-bold">By Days</span>
                                <span></span>
                                <input id="date_daily" type="date" class="form-input" value="" style="color: white;" />
                            </div>

                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_daily"
                                x-on:click="() => ChartRecall5(inpt_val_planing_daily.value, inpt_val_entry_daily.value, inpt_val_received_daily.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_planing_daily" value="" hidden>
                            <input type="text" id="inpt_val_entry_daily" value="" hidden>
                            <input type="text" id="inpt_val_received_daily" value="" hidden>
                            <div x-ref="BoxChart5" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>
                <!-- Table -->
                <script>
                    const lines = ['A1', 'A2', 'A3', 'A4', 'B1', 'B2', 'D14', 'P9', 'A5', 'A6'];

                    lines.forEach(line => {
                        document.write(`
                                <div class="panel h-full col-span-6">
                                    <div id="tables_container">
                                            <div class="relative overflow-hidden">
                                                <h3 class="font-bold text-lg text-white mb-5">Line - ${line}</h3>
                                                <table id="monitoring_table_${line}" class="min-w-full rounded-md shadow-md overflow-hidden">
                                                    <thead>
                                                        <tr class="bg-gray-600 text-white text-sm leading-normal">
                                                            <th class="py-3 px-6 text-left">Part Number</th>
                                                            <th class="py-3 px-6 text-left">Part Qty</th>
                                                            <th class="py-3 px-6 text-left">Qty Complete</th>
                                                            <th class="py-3 px-6 text-left">Received Qty</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                    </div>
                                </div>
                            `);
                    });
                </script>


            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const lines = ['A1', 'A2', 'A3', 'A4', 'B1', 'B2', 'D14', 'P9', 'A5', 'A6'];

            function detail_table(tableId, inputId, apiUrl, line) {
                let detailTable = $(tableId).DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: false,
                    deferLoading: 57,
                    language: {
                        'processing': '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
                    },
                    info: false,
                    order: [],
                    columnDefs: [{
                        orderable: false,
                        targets: 0
                    }],
                    ajax: {
                        url: apiUrl,
                        type: 'POST',
                        contentType: "application/json",
                        data: function (d) {
                            let selectedDate = document.getElementById(inputId).value;
                            if (selectedDate) {
                                d.date = selectedDate;
                            }
                            d.line = line;
                            return JSON.stringify(d);
                        },
                        cache: false,
                        dataType: 'json'
                    },
                    columns: [{
                        data: 'PartNum',
                        title: 'Part Number'
                    },
                    {
                        data: 'PartQty',
                        title: 'Part Qty'
                    },
                    {
                        data: 'QtyComplete',
                        title: 'Qty Complete'
                    },
                    {
                        data: 'ReceivedQty',
                        title: 'Received Qty'
                    }
                    ],
                    createdRow: function (row, data, dataIndex) {
                        $(row).css('background-color', dataIndex % 2 === 0 ? '#1D1D1D' : '#2D2D2D');
                    }
                });
                setTimeout(function () {
                    detailTable.ajax.reload();
                });
                return detailTable;
            }

            function setDefaultDate() {
                let today = new Date();
                let formattedDate = today.toISOString().split('T')[0];
                document.getElementById('date_daily').value = formattedDate;
            }

            function fetchDataAndUpdateCharts(callback) {
                let currentHost = window.location.host;
                let currentYear = document.getElementById('current_year').value;
                selectedLine = 'A1';
                let selectedDate = document.getElementById('date_daily').value;

                if (!selectedDate) return;

                const [year, month, day] = selectedDate.split('-');

                const apiUrlYearly = `https://${currentHost}/api/production/get_production_achiev_Year/${currentYear}`;
                axios.get(apiUrlYearly).then(response => {
                    let data_chart = response.data.data_chart || {};
                    let planningData = Object.keys(data_chart).map(line => data_chart[line]?.PartQty || 0);
                    let entryData = Object.keys(data_chart).map(line => data_chart[line]?.QtyComplete || 0);
                    let receivedData = Object.keys(data_chart).map(line => data_chart[line]?.ReceivedQty || 0);

                    document.getElementById('inpt_val_planing_data').value = planningData;
                    document.getElementById('inpt_val_entry_data').value = entryData;
                    document.getElementById('inpt_val_received_data').value = receivedData;

                    document.getElementById('btn_update_chart').click();
                });
                const apiUrlMonthly = `https://${currentHost}/api/production/get_production_achiev_Month/${currentYear}~${selectedLine}`;
                axios.get(apiUrlMonthly).then(response => {
                    let data_monthly = response.data.data_monthly || {};
                    let planningData = Object.keys(data_monthly).map(line => data_monthly[line]?.PartQty || 0);
                    let entryData = Object.keys(data_monthly).map(line => data_monthly[line]?.QtyComplete || 0);
                    let receivedData = Object.keys(data_monthly).map(line => data_monthly[line]?.ReceivedQty || 0);

                    document.getElementById('inpt_val_planing_month').value = planningData;
                    document.getElementById('inpt_val_entry_month').value = entryData;
                    document.getElementById('inpt_val_received_month').value = receivedData;

                    document.getElementById('btn_update_month').click();
                });

                const apiUrlDaily = `https://${currentHost}/api/production/get_production_achiev_Daily/${year}-${month}-${day}`;
                axios.get(apiUrlDaily).then(response => {
                    let data_daily = response.data.data_daily || {};
                    let planningDaily = Object.keys(data_daily).map(line => data_daily[line]?.PartQty || 0);
                    let entryDaily = Object.keys(data_daily).map(line => data_daily[line]?.QtyComplete || 0);
                    let receivedDaily = Object.keys(data_daily).map(line => data_daily[line]?.ReceivedQty || 0);

                    document.getElementById('inpt_val_planing_daily').value = planningDaily;
                    document.getElementById('inpt_val_entry_daily').value = entryDaily;
                    document.getElementById('inpt_val_received_daily').value = receivedDaily;

                    document.getElementById('btn_update_daily').click();
                    if (callback) callback();
                });
            }

            setDefaultDate();
            fetchDataAndUpdateCharts();

            document.getElementById('current_year').addEventListener('change', fetchDataAndUpdateCharts);
            document.getElementById('date_daily').addEventListener('change', function () {
                fetchDataAndUpdateCharts(() => {
                    setTimeout(() => {
                        lines.forEach(line => {
                            let tableId = `#monitoring_table_${line}`;
                            if ($.fn.DataTable.isDataTable(tableId)) {
                                $(tableId).DataTable().ajax.reload(null, false);
                            }
                        });
                    }, 500);
                });
            });

            lines.forEach(line => {
                let tableId = `#monitoring_table_${line}`;
                let inputId = `date_daily`;
                let apiUrl = `https://${window.location.host}/api/production/get_production_achiev_Table`;
                detail_table(tableId, inputId, apiUrl, line);
            });
        });


        document.addEventListener("alpine:init", () => {
            Alpine.data("analytics", () => ({
                data: {
                    analytics: "Initial Data"
                },
                formatNumber(value) {
                    return value;
                },

                ChartRecall1(monitoringPlanning, monitoringEntry, monitoringReceived) {
                    const planning = monitoringPlanning.split(',').map(Number);
                    const entry = monitoringEntry.split(',').map(Number);
                    const received = monitoringReceived.split(',').map(Number);

                    this.BoxChart2.updateSeries([{
                        name: 'Planning',
                        data: planning
                    },
                    {
                        name: 'Time Entry',
                        data: entry
                    },
                    {
                        name: 'Received',
                        data: received
                    }
                    ]);

                    this.BoxChart2.updateOptions({
                        chart: {
                            events: {
                                dataPointSelection: (event, chartContext, {
                                    dataPointIndex
                                }) => {
                                    const selectedLine = this.BoxChart2Options.xaxis.categories[dataPointIndex];
                                    this.updateMonthlyChart(selectedLine);
                                }
                            }
                        }
                    });
                },

                updateMonthlyChart(selectedLine) {
                    let currentYear = document.getElementById('current_year').value;
                    let currentHost = window.location.host;
                    const apiUrlMonthly = `https://${currentHost}/api/production/get_production_achiev_Month/${currentYear}~${selectedLine}`;

                    axios.get(apiUrlMonthly).then(response => {
                        let data_monthly = response.data.data_monthly || {};
                        let planningData = Object.keys(data_monthly).map(line => data_monthly[line]?.PartQty || 0);
                        let entryData = Object.keys(data_monthly).map(line => data_monthly[line]?.QtyComplete || 0);
                        let receivedData = Object.keys(data_monthly).map(line => data_monthly[line]?.ReceivedQty || 0);

                        document.getElementById('inpt_val_planing_month').value = planningData;
                        document.getElementById('inpt_val_entry_month').value = entryData;
                        document.getElementById('inpt_val_received_month').value = receivedData;

                        this.ChartRecall2(planningData.join(','), entryData.join(','), receivedData.join(','));
                    });
                },

                ChartRecall2(monthPlanning, monthEntry, monthReceived) {
                    const planning = monthPlanning.split(',').map(Number);
                    const entry = monthEntry.split(',').map(Number);
                    const received = monthReceived.split(',').map(Number);

                    this.BoxChart3.updateSeries([{
                        name: 'Planning',
                        data: planning
                    },
                    {
                        name: 'Time Entry',
                        data: entry
                    },
                    {
                        name: 'Received',
                        data: received
                    }
                    ]);
                },
                ChartRecall5(dailyPlanning, dailyEntry, dailyReceived) {
                    const planningDaily = dailyPlanning.split(',').map(Number);
                    const entryDaily = dailyEntry.split(',').map(Number);
                    const receivedDaily = dailyReceived.split(',').map(Number);

                    this.BoxChart5.updateSeries([{
                        name: 'Planning',
                        data: planningDaily
                    },
                    {
                        name: 'Time Entry',
                        data: entryDaily
                    },
                    {
                        name: 'Received',
                        data: receivedDaily
                    }
                    ]);
                },

                renderCharts() {
                    this.BoxChart2 = new ApexCharts(this.$refs.BoxChart2, this.BoxChart2Options);
                    this.BoxChart2.render();

                    this.BoxChart3 = new ApexCharts(this.$refs.BoxChart3, this.BoxChart3Options);
                    this.BoxChart3.render();

                    this.BoxChart5 = new ApexCharts(this.$refs.BoxChart5, this.BoxChart5Options);
                    this.BoxChart5.render();
                },

                get BoxChart2Options() {
                    return {
                        series: [{
                            name: 'Part Qty',
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                        },
                        {
                            name: 'Qty Complete',
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                        },
                        {
                            name: 'Received Qty',
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                        }
                        ],
                        chart: {
                            type: 'bar',
                            height: 260,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                        },
                        stroke: {
                            width: [0, 0, 0],
                            curve: 'smooth'
                        },
                        dataLabels: {
                            enabled: true,
                            position: "top",
                            offsetY: -25,
                            style: {
                                fontSize: "10px",
                            },
                            formatter: (value) => {
                                if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(1) + "M";
                                if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(1) + "K";
                                return value;
                            }
                        },
                        colors: ['#2A9D8F', '#F4A261', '#c026d1'],
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '90%',
                                endingShape: 'rounded',
                                dataLabels: {
                                    position: "top",
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            itemMargin: {
                                horizontal: 8,
                                vertical: 8
                            }
                        },
                        grid: {
                            borderColor: '#191e3a',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: ['A1', 'A2', 'A3', 'A4', 'B1', 'B2', 'D14', 'P9', 'A5', 'A6'],
                            axisBorder: {
                                show: true,
                                color: '#3b3f5c'
                            },
                            axisTicks: {
                                show: false
                            }
                        },
                        yaxis: {
                            labels: {
                                show: true,
                                offsetX: 0,
                                formatter: (value) => {
                                    if (value >= 1000000) return (Math.floor(value / 100000000) / 10).toFixed(0) + "B";
                                    if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(0) + "M";
                                    if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(0) + "K";
                                    return value;
                                }
                            },
                            forceNiceScale: true
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return new Intl.NumberFormat('id-ID', {}).format(value);
                                }
                            }
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shade: 'dark',
                                type: 'vertical',
                                shadeIntensity: 0.3,
                                inverseColors: false,
                                opacityFrom: 1,
                                opacityTo: 0.3,
                                stops: [0, 100]
                            }
                        }
                    };
                },

                get BoxChart3Options() {
                    return {
                        series: [{
                            name: 'Part Qty',
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                        },
                        {
                            name: 'Qty Complete',
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                        },
                        {
                            name: 'Received Qty',
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                        }
                        ],
                        chart: {
                            type: 'bar',
                            height: 260,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            position: "top",
                            offsetY: -25,
                            style: {
                                colors: ["#fff"],
                                fontSize: "10px",
                            },
                            formatter: (value) => {
                                if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(1) + "M";
                                if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(1) + "K";
                                return value;
                            }
                        },
                        colors: ['#2A9D8F', '#F4A261', '#c026d1'],
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '90%',
                                endingShape: 'rounded',
                                dataLabels: {
                                    position: "top",
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            itemMargin: {
                                horizontal: 8,
                                vertical: 8
                            }
                        },
                        grid: {
                            borderColor: '#191e3a',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                            axisBorder: {
                                show: true,
                                color: '#3b3f5c'
                            },
                            axisTicks: {
                                show: false
                            }
                        },
                        yaxis: {
                            labels: {
                                show: true,
                                offsetX: 0,
                                formatter: (value) => {
                                    if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(1) + "M";
                                    if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(1) + "K";
                                    return value;
                                }
                            },
                            forceNiceScale: true
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return new Intl.NumberFormat('id-ID', {}).format(value);
                                }
                            }
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shade: 'dark',
                                type: 'vertical',
                                shadeIntensity: 0.3,
                                inverseColors: false,
                                opacityFrom: 1,
                                opacityTo: 0.8,
                                stops: [0, 100]
                            }
                        }
                    };
                },

                get BoxChart5Options() {
                    return {
                        series: [{
                            name: 'Part Qty',
                            data: []
                        },
                        {
                            name: 'Qty Complete',
                            data: []
                        },
                        {
                            name: 'Received Qty',
                            data: []
                        }
                        ],
                        chart: {
                            type: 'bar',
                            height: 260,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            position: "top",
                            offsetY: -25,
                            style: {
                                colors: ["#fff"],
                                fontSize: "10px",
                            },
                            formatter: (value) => {
                                if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(1) + "M";
                                if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(1) + "K";
                                return value;
                            }


                        },
                        colors: ['#2A9D8F', '#F4A261', '#c026d1'],
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '90%',
                                endingShape: 'rounded',
                                dataLabels: {
                                    position: "top",
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            itemMargin: {
                                horizontal: 8,
                                vertical: 8
                            }
                        },
                        grid: {
                            borderColor: '#191e3a',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: ['A1', 'A2', 'A3', 'A4', 'B1', 'B2', 'D14', 'P9', 'A5', 'A6'],
                            axisBorder: {
                                show: true,
                                color: '#3b3f5c'
                            },
                            axisTicks: {
                                show: false
                            }
                        },
                        yaxis: {
                            labels: {
                                show: true,
                                offsetX: 0,
                                formatter: (value) => {
                                    if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(1) + "M";
                                    if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(1) + "K";
                                    return value;
                                }
                            },
                        },
                        tooltip: {
                            y: {
                                formatter: (value) => {
                                    return new Intl.NumberFormat('id-ID', {
                                    }).format(value);
                                }
                            }
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shade: 'dark',
                                type: 'vertical',
                                shadeIntensity: 0.3,
                                inverseColors: false,
                                opacityFrom: 1,
                                opacityTo: 0.8,
                                stops: [0, 100]
                            }
                        }
                    };
                },
                init() {
                    this.data.analytics = "Initial Data";
                    this.renderCharts();
                    setTimeout(() => {
                        document.getElementById('btn_update_chart').click();
                        document.getElementById('btn_update_month').click();
                        document.getElementById('btn_update_daily').click();
                    }, 500);
                }
            }))
        });
    </script>
</x-layout.default>
