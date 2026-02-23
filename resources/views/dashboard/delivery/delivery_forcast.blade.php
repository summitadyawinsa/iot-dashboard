<x-layout.default>
    <script defer src="/assets/js/apexcharts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <style>
        #delivery_tabel tbody tr:hover {
            background-color: #727e93;
            cursor: pointer;
        }

        #delivery_tabel tbody tr {
            background-color: #4B5563;
            color: whitesmoke;
            cursor: pointer;
            font-size: small;
        }

        #delivery_tabel thead tr {
            color: whitesmoke;
        }

        #month_year::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }
    </style>

    <style>
        select[name="delivery_tabel_length"] {
            width: 80px;
        }
    </style>
    <div x-data="analytics()">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>Delivery</span>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>Forcast Report</span>
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
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 dark:text-white-light">
                        <div>
                            <label for="current_year">Years</label>
                            <input id="current_year" type="number" class="form-input" value="<?= $currentYear ?>"
                                style="color: white;" />
                        </div>
                        <div>
                            <label for="cust_month">Customer</label>
                            <select class="form-input" name="cust_month" id="cust_month">
                                <option value="HPM">HPM</option>
                                <option value="MMKI">MMKI</option>
                                <option value="MMKSI">MMKSI</option>
                                <option value="MKM">MKM</option>
                                <option value="SIS">SIS</option>
                                <option value="IAMI">IAMI</option>
                                <option value="TMMIN">TMMIN</option>
                                <option value="UDAMI">UDAMI</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart"
                                x-on:click="() => ChartRecall1(inpt_val_order_chart.value, inpt_val_ship_chart.value)"
                                hidden>Recall and Update
                            </button>
                            <input type="text" id="inpt_val_order_chart" value="" hidden>
                            <input type="text" id="inpt_val_ship_chart" value="" hidden>
                            <div x-ref="BoxChart2" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>
                <!-- Chart Harian -->
                <div class="panel h-full sm:col-span-12 xl:col-span-12">
                    <div class="grid grid-cols-1 sm:grid-cols-6 gap-4 p-5 dark:text-white-light">
                        <div>
                            <label for="month_year">Month</label>
                            <input id="month_year" type="month" class="form-input" value="" style="color: white;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_cust_date"
                                x-on:click="() => ChartRecall5(inpt_val_ship_days.value, inpt_val_order_days.value, inpt_val_date.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_ship_days" value="" hidden>
                            <input type="text" id="inpt_val_order_days" value="" hidden> <input type="text"
                                id="inpt_val_date" value="$currentMonth" hidden>
                            <div x-ref="BoxChart5" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>
                <!-- Table -->
                <div class="panel h-full col-span-12">
                    <div class="grid grid-cols-1 sm:grid-cols-6 gap-4 p-5 dark:text-white-light">
                        <div>
                            <label for="month_year_table">Month</label>
                            <input id="month_year_table" type="date" class="form-input" value="2025-02-26"
                                style="color: white;" />
                        </div>
                    </div>
                    <hr>
                    <div class="relative overflow-hidden pt-5">
                        <table id="delivery_tabel" class="min-w-full rounded-md shadow-md overflow-hidden">
                            <thead>
                                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                                    <th class="py-3 px-6 text-left">Part Num</th>
                                    <th class="py-3 px-6 text-left">Ship Qty</th>
                                    <th class="py-3 px-6 text-left">Order Qty</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            function detail_table() {
                var detailTable = $("#delivery_tabel").DataTable({
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
                        url: `https://${window.location.host}/api/delivery/get_delivery_Table`,
                        type: 'POST',
                        contentType: "application/json",
                        data: function (d) {
                            let selectedMonth = document.getElementById('month_year_table').value;
                            if (selectedMonth) {
                                let parts = selectedMonth.split("-");
                                d.year = `${parts[0]}-${parts[1]}-${parts[2]}`;
                            }
                            return JSON.stringify(d);
                        },
                        cache: false,
                        dataType: 'json'
                    },
                    columns: [{
                        data: 'PartNum'
                    },
                    {
                        data: 'ShipQty',
                    },
                    {
                        data: 'OrderQty',
                    },
                    ],
                    createdRow: function (row, data, dataIndex) {
                        if (dataIndex % 2 === 0) {
                            $(row).css('background-color', '#1D1D1D');
                        } else {
                            $(row).css('background-color', '#2D2D2D');
                        }
                    }
                });

                setTimeout(function () {
                    detailTable.ajax.reload();
                }, 500);

                return detailTable;
            }

            function setDefaultData() {
                var currentHost = window.location.host;
                var currentYear = document.getElementById('current_year').value;
                var customer = document.getElementById('cust_month').value;

                document.getElementById('month_year').value = `${currentYear}-01`;

                const apiUrlMonthly = `https://${currentHost}/api/delivery/get_forecast_order_Monhtly/${currentYear}~${customer}`;
                axios.get(apiUrlMonthly)
                    .then(response => {
                        var data_chart_order_month = response.data.data_chart_order_month;
                        var data_chart_ship_month = response.data.data_chart_ship_month;
                        document.getElementById('inpt_val_order_chart').value = data_chart_order_month;
                        document.getElementById('inpt_val_ship_chart').value = data_chart_ship_month;
                        document.getElementById('btn_update_chart').click();
                    })

                const apiUrlDaily = `https://${currentHost}/api/delivery/get_forecast_order_Daily/${currentYear}-01~${customer}`;
                axios.get(apiUrlDaily)
                    .then(response => {
                        var data_ship_days = response.data.data_ship_days;
                        var data_order_days = response.data.data_order_days;
                        var data_cust_date = response.data.data_val_date;
                        document.getElementById('inpt_val_ship_days').value = data_ship_days;
                        document.getElementById('inpt_val_order_days').value = data_order_days;
                        document.getElementById('inpt_val_date').value = data_cust_date;
                        document.getElementById('btn_update_cust_date').click();
                    })
            }
            setDefaultData()
            detail_table();

            document.getElementById('month_year').addEventListener('change', function () {
                updateDailyChart();
            });

            document.getElementById('cust_month').addEventListener('change', function () {
                updateDailyChart();
            });
            document.getElementById('current_year').addEventListener('change', function () {
                var selectedYear = this.value;
                document.getElementById('month_year').value = `${selectedYear}-01`;

                updateDailyChart();
            });
            document.getElementById('month_year_table').addEventListener('change', function () {

            });

            document.getElementById('month_year_table').addEventListener('input', function () {
                if ($.fn.DataTable.isDataTable('#delivery_tabel')) {
                    $('#delivery_tabel').DataTable().ajax.reload(null, false);
                }
            });

            function updateDailyChart() {

                var currentHost = window.location.host;
                var yearX = document.getElementById('current_year').value + '~' + document.getElementById('cust_month').value;
                const apiUrlMonthly = `https://${currentHost}/api/delivery/get_forecast_order_Monhtly/${yearX}`;
                axios.get(apiUrlMonthly)
                    .then(response => {
                        var data_chart_order_month = response.data.data_chart_order_month;
                        var data_chart_ship_month = response.data.data_chart_ship_month;
                        var data_cust_date = response.data.data_val_date;
                        document.getElementById('inpt_val_order_chart').value = data_chart_order_month;
                        document.getElementById('inpt_val_ship_chart').value = data_chart_ship_month;
                        document.getElementById('btn_update_chart').click();
                    })

                var currentHost = window.location.host;
                var date_cust = document.getElementById('month_year').value + '~' + document.getElementById('cust_month').value;
                const apiUrl = `https://${currentHost}/api/delivery/get_forecast_order_Daily/${date_cust}`;
                axios.get(apiUrl)
                    .then(response => {
                        var data_ship_days = response.data.data_ship_days;
                        var data_order_days = response.data.data_order_days;
                        var data_cust_date = response.data.data_val_date;
                        document.getElementById('inpt_val_ship_days').value = data_ship_days;
                        document.getElementById('inpt_val_order_days').value = data_order_days;
                        document.getElementById('inpt_val_date').value = data_cust_date;
                        document.getElementById('btn_update_cust_date').click();
                    })
            }
        });

        document.addEventListener("alpine:init", () => {
            Alpine.data("analytics", () => ({
                data: {
                    analytics: "Initial Data"
                },
                formatNumber(value) {
                    return value;
                },
                ChartRecall1(orderData, shipData) {
                    const order = orderData.split(',').map(Number);
                    const ship = shipData.split(',').map(Number);

                    this.BoxChart2.updateSeries([{
                        name: 'Order',
                        data: order
                    },
                    {
                        name: 'Ship',
                        data: ship
                    },
                    ]);
                },

                ChartRecall5(orderData, costData, categoriesData) {
                    const order = orderData.split(',').map(Number);
                    const ship = costData.split(',').map(Number);
                    const categories = categoriesData.split(',');

                    this.BoxChart5.updateOptions({
                        xaxis: {
                            categories: categories
                        }
                    });

                    this.BoxChart5.updateSeries([{
                        name: 'Order',
                        data: order
                    },
                    {
                        name: 'Ship',
                        data: ship
                    }
                    ]);
                },

                renderCharts() {
                    this.BoxChart2 = new ApexCharts(this.$refs.BoxChart2, this.BoxChart2Options);
                    this.BoxChart2.render();

                    this.BoxChart5 = new ApexCharts(this.$refs.BoxChart5, this.BoxChart5Options);
                    this.BoxChart5.render();

                    this.BoxChart2.addEventListener('click', (event, chartContext, config) => {
                        if (config.dataPointIndex !== -1) {

                            const clickedMonth = this.BoxChart2.w.globals.labels[config.dataPointIndex];
                            const monthsMap = {
                                'Jan': '01',
                                '1': '01',
                                '01': '01',
                                'Feb': '02',
                                '2': '02',
                                '02': '02',
                                'Mar': '03',
                                '3': '03',
                                '03': '03',
                                'Apr': '04',
                                '4': '04',
                                '04': '04',
                                'May': '05',
                                '5': '05',
                                '05': '05',
                                'Jun': '06',
                                '6': '06',
                                '06': '06',
                                'Jul': '07',
                                '7': '07',
                                '07': '07',
                                'Aug': '08',
                                '8': '08',
                                '08': '08',
                                'Sep': '09',
                                '9': '09',
                                '09': '09',
                                'Oct': '10',
                                '10': '10',
                                'Nov': '11',
                                '11': '11',
                                'Dec': '12',
                                '12': '12'
                            };

                            const monthNumber = monthsMap[clickedMonth];
                            const yearInput = document.getElementById('current_year');
                            const year = yearInput.value;
                            const monthYear = `${year}-${monthNumber}`;
                            const monthInput = document.getElementById('month_year');

                            monthInput.value = monthYear;
                            monthInput.dispatchEvent(new Event('input', {
                                bubbles: true
                            }));
                            monthInput.dispatchEvent(new Event('change', {
                                bubbles: true
                            }));
                        }
                    });
                    this.BoxChart5.addEventListener('click', (event, chartContext, config) => {
                        if (config.dataPointIndex !== -1) {

                            const clickedDay = this.BoxChart5.w.globals.labels[config.dataPointIndex];
                            const daysMap = {
                                '1': '01',
                                '2': '02',
                                '3': '03',
                                '4': '04',
                                '5': '05',
                                '6': '06',
                                '7': '07',
                                '8': '08',
                                '9': '09',
                                '10': '10',
                                '11': '11',
                                '12': '12',
                                '13': '13',
                                '14': '14',
                                '15': '15',
                                '16': '16',
                                '17': '17',
                                '18': '18',
                                '19': '19',
                                '20': '20',
                                '21': '21',
                                '22': '22',
                                '23': '23',
                                '24': '24',
                                '25': '25',
                                '26': '26',
                                '27': '27',
                                '28': '28',
                                '29': '29',
                                '30': '30',
                                '31': '31'
                            };

                            const monthNumber = daysMap[clickedDay];
                            const yearInput = document.getElementById('month_year');
                            const year = yearInput.value;
                            const monthYear = `${year}-${monthNumber}`;
                            const monthInput = document.getElementById('month_year_table');

                            monthInput.value = monthYear;
                            monthInput.dispatchEvent(new Event('input', {
                                bubbles: true
                            }));
                            monthInput.dispatchEvent(new Event('change', {
                                bubbles: true
                            }));
                        }
                    });

                },

                get BoxChart2Options() {
                    return {
                        series: [{
                            name: 'Order',
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                        },
                        {
                            name: 'Ship',
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                        },
                        ],
                        chart: {
                            height: 260,
                            type: 'area',
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                            zoom: {
                                enabled: false
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: (value) => this.formatNumber(value)
                        },
                        stroke: {
                            width: 2,
                            colors: ['#2196f3', '#f44336']
                        },
                        colors: ['#2196f3', '#f44336'],
                        dropShadow: {
                            enabled: true,
                            blur: 3,
                            color: '#515365',
                            opacity: 0.4
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
                            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Des'],
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
                                formatter: (value) => this.formatNumber(value)
                            },
                            forceNiceScale: true
                        },
                        tooltip: {
                            y: {
                                formatter: (value) => this.formatNumber(value)
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
                            name: 'Order',
                            data: []
                        },
                        {
                            name: 'Ship',
                            data: []
                        }
                        ],
                        chart: {
                            height: 260,
                            type: 'area',
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: (value) => this.formatNumber(value)
                        },
                        stroke: {
                            width: 2,
                            colors: ['#2196f3', '#f44336'] //merah, biru
                        },
                        colors: ['#2196f3', '#f44336'],
                        dropShadow: {
                            enabled: true,
                            blur: 3,
                            color: '#515365',
                            opacity: 0.4
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
                            categories: [],
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
                                formatter: (value) => this.formatNumber(value)
                            },
                            forceNiceScale: true
                        },
                        tooltip: {
                            y: {
                                formatter: (value) => this.formatNumber(value)
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
                }
            }))
        });
    </script>
</x-layout.default>
