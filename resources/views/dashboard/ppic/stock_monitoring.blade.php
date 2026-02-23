<x-layout.default>
    <script defer src="/assets/js/apexcharts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <style>
        #stok_table tbody tr:hover,
        #monitoring_table2 tbody tr:hover,
        #monitoring_table3 tbody tr:hover {
            background-color: #727e93;
            cursor: pointer;
        }

        #stok_table tbody tr,
        #monitoring_table2 tbody tr,
        #monitoring_table3 tbody tr {
            background-color: #4B5563;
            color: whitesmoke;
            cursor: pointer;
            font-size: small;
        }

        #stok_table thead tr,
        #monitoring_table2 thead tr,
        #monitoring_table3 thead tr {
            color: whitesmoke;
        }

        #stok_table::-webkit-calendar-picker-indicator,
        #month_year_table2::-webkit-calendar-picker-indicator,
        #month_year_table3::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        select[name="stok_table_length"] {
            width: 80px;
        }
    </style>

    <div x-data="analytics">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>PPIC</span>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>Stock Monitoring</span>
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
                    <div class="grid grid-cols-3 gap-4 p-5 dark:text-white-light">
                        <div>
                            <label for="monitoring_year">Year</label>
                            <input id="monitoring_year" type="number" class="form-input" value="<?= $currentYear ?>"
                                style="color: white;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn-update-barchart"
                                x-on:click="() => ChartRecall2(inpt_kritis_warehouse.value, inpt_over_warehouse.value, inpt_oke_warehouse.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_kritis_warehouse" value="" hidden>
                            <input type="text" id="inpt_over_warehouse" value="" hidden>
                            <input type="text" id="inpt_oke_warehouse" value="" hidden>
                            <div x-ref="BoxChart3" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>
                <div class="panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-3 gap-4 p-5 dark:text-white-light">
                        <div>
                            <label for="category_year">Year</label>
                            <input id="category_year" type="number" class="form-input" value="<?= $currentYear ?>"
                                style="color: white;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart"
                                x-on:click="() => ChartRecall1(inpt_val_kritis.value, inpt_val_over.value, inpt_val_oke.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_kritis" value="" hidden>
                            <input type="text" id="inpt_val_over" value="" hidden>
                            <input type="text" id="inpt_val_oke" value="" hidden>
                            <div x-ref="BoxChart2" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>
                <div class="panel h-full col-span-6 ">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-5 dark:text-white-light">
                        <div>
                            <label for="month_year_table">Month</label>
                            <input id="month_year_table" type="date" class="form-input" value="2025-02-26"
                                style="color: white;" />
                            <input type="text" id="selected_warehouse" value="" hidden>
                        </div>
                    </div>
                    <hr>
                    <div class="relative overflow-hidden pt-5">
                        <table id="stok_table" class="min-w-full rounded-md shadow-md overflow-hidden">
                            <thead>
                                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                                    <th class="py-3 px-6 text-left">Part Number</th>
                                    <th class="py-3 px-6 text-left">Onhand</th>
                                    <th class="py-3 px-6 text-left">Status</th>
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
                detailTable = $("#stok_table").DataTable({
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
                        url: `https://${window.location.host}/api/ppic/get_stock_monitoring_table`,
                        type: 'POST',
                        contentType: "application/json",
                        data: function (d) {
                            let selectedWarehouse = document.getElementById('selected_warehouse').value;
                            let selectedMonth = document.getElementById('month_year_table').value;
                            d.warehouse = selectedWarehouse;

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
                        data: 'PartNumber'
                    },
                    {
                        data: 'Onhand',
                    },
                    {
                        data: 'Status',
                    },
                    ],
                    createdRow: function (row, data, dataIndex) {
                        $(row).css('background-color', dataIndex % 2 === 0 ? '#1D1D1D' : '#2D2D2D');

                        var api = this.api();
                        var columnIndex = api.columns().indexes().filter(function (index) {
                            return api.column(index).header().textContent.trim() === 'Status';
                        })[0];

                        if (columnIndex !== undefined) {
                            var cell = $('td', row).eq(columnIndex);
                            var status = data.Status;
                            var badgeClass = 'badge px-3 py-2 fs-6 fw-normal';

                            if (status === 'OKE') {
                                cell.html('<span class="badge badge-outline-success">OKE</span>');
                            } else if (status === 'OVER') {
                                cell.html('<span class="badge badge-outline-warning">OVER</span>');
                            } else if (status === 'KRITIS') {
                                cell.html('<span class="badge badge-outline-danger">KRITIS</span>');
                            }
                        }
                    }


                });

                setTimeout(function () {
                    detailTable.ajax.reload();
                }, 200);

                return detailTable;
            }
            detail_table();

            function setDefaultData() {

                function fetchData(url, callback) {
                    axios.get(url)
                        .then(response => callback(response.data))
                }
                const categoryYear = document.getElementById('category_year').value;
                const defaultWarehouse = "After Nut";
                const warehouseName = window.warehouseName || defaultWarehouse;
                const apiUrl = `https://${currentHost}/api/ppic/get_stock_monitoring/${encodeURIComponent(warehouseName)}`;

                fetchData(apiUrl, data => {
                    let warehouseData = Array.isArray(data) ? data[0] : data;

                    document.getElementById('inpt_val_kritis').value = warehouseData?.data_kritis || 0;
                    document.getElementById('inpt_val_over').value = warehouseData?.data_over || 0;
                    document.getElementById('inpt_val_oke').value = warehouseData?.data_oke || 0;
                    document.getElementById('btn_update_chart').click();
                });

                fetchData(`https://${currentHost}/api/ppic/get_stock_monitoring_warehouse`, data => {
                    document.getElementById('inpt_kritis_warehouse').value = (data.data_kritis_warehouse || []);
                    document.getElementById('inpt_over_warehouse').value = (data.data_over_warehouse || []);
                    document.getElementById('inpt_oke_warehouse').value = (data.data_oke_warehouse || []);
                    document.getElementById('btn-update-barchart').click();
                });


            }
            setDefaultData();

            document.getElementById('selected_warehouse').value = "After Nut";
            detailTable.ajax.reload();

        });

        var currentHost = window.location.host;

        function fetchData(url, callback) {
            axios.get(url)
                .then(response => callback(response.data))
        }

        function updateBarChart() {
            fetchData(`https://${currentHost}/api/ppic/get_stock_monitoring_warehouse`, data => {
                document.getElementById('inpt_kritis_warehouse').value = (data.data_kritis_warehouse || []);
                document.getElementById('inpt_over_warehouse').value = (data.data_over_warehouse || []);
                document.getElementById('inpt_oke_warehouse').value = (data.data_oke_warehouse || []);
                document.getElementById('btn-update-barchart').click();
            });
        }
        document.addEventListener("alpine:init", () => {
            Alpine.data("analytics", () => ({
                data: {
                    analytics: "Initial Data"
                },
                formatNumber(value) {
                    return value;
                },
                ChartRecall1(kritisData, overData, okeData) {

                    const kritis = kritisData.split(',').map(Number);
                    const over = overData.split(',').map(Number);
                    const oke = okeData.split(',').map(Number);

                    const actual = [kritis[0] || 0, over[0] || 0, oke[0] || 0];

                    this.BoxChart2.updateSeries(actual);
                    this.BoxChart2.updateOptions({
                        labels: ['Kritis', 'Over', 'Oke'],
                    });
                },

                ChartRecall2(kritisDataWH, overDataWH, okeDataWH) {

                    const kritis = kritisDataWH.split(',').map(Number);
                    const over = overDataWH.split(',').map(Number);
                    const oke = okeDataWH.split(',').map(Number);

                    const actual = [{
                        name: "Kritis",
                        data: kritis
                    },
                    {
                        name: "Over",
                        data: over
                    },
                    {
                        name: "Oke",
                        data: oke
                    }
                    ];

                    this.BoxChart3.updateSeries(actual);
                },


                renderCharts() {

                    this.BoxChart2 = new ApexCharts(this.$refs.BoxChart2, this.BoxChart2Options);
                    this.BoxChart2.render();

                    this.BoxChart3 = new ApexCharts(this.$refs.BoxChart3, this.BoxChart3Options);
                    this.BoxChart3.render();

                    this.BoxChart3.addEventListener('click', (event, chartContext, config) => {
                        if (config.dataPointIndex !== -1) {
                            const clickedIndex = config.dataPointIndex;
                            const categories = this.BoxChart3.w.config.xaxis.categories;
                            const warehouseName = categories[clickedIndex];

                            fetchData(`https://${currentHost}/api/ppic/get_stock_monitoring/${warehouseName}`, data => {
                                let warehouseData = Array.isArray(data) ? data[0] : data;

                                document.getElementById('inpt_val_kritis').value = warehouseData?.data_kritis || 0;
                                document.getElementById('inpt_val_over').value = warehouseData?.data_over || 0;
                                document.getElementById('inpt_val_oke').value = warehouseData?.data_oke || 0;
                                document.getElementById('btn_update_chart').click();
                            });
                            document.getElementById('selected_warehouse').value = warehouseName;
                            window.detailTable.ajax.reload();
                        }
                    });
                },

                get BoxChart2Options() {
                    return {
                        series: [],
                        chart: {
                            type: 'pie',
                            height: 400,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                            offsetY: 50,
                            animations: {
                                enabled: true,
                                easing: 'easeout',
                                speed: 800,
                                animateGradually: {
                                    enabled: true,
                                    delay: 300
                                },
                                dynamicAnimation: {
                                    enabled: true,
                                    speed: 500
                                }
                            }
                        },
                        colors: ['#E63946', '#F4A261', '#2A9D8F'],
                        labels: ['Kritis', 'Over', 'Oke'],
                        stroke: {
                            show: false,
                        },
                        dropShadow: {
                            enabled: true,
                            blur: 3,
                            color: '#515365',
                            opacity: 0.4
                        },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            itemMargin: {
                                horizontal: 15,
                                vertical: 30
                            }
                        },
                        tooltip: {
                            y: {

                            }
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '12px',
                            },
                            fontSize: '20px',
                            formatter: (value) => `${Math.round(value)}%`
                        },
                    };
                },
                get BoxChart3Options() {
                    return {
                        series: [{
                            name: 'Kritis',
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                        },
                        {
                            name: 'Over',
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                        },
                        {
                            name: 'Oke',
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                        },
                        ],
                        chart: {
                            height: 400,
                            type: 'bar',
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                            animations: {
                                enabled: true,
                                easing: 'easeout',
                                speed: 500,
                                animateGradually: {
                                    enabled: true,
                                    delay: 100
                                },
                                dynamicAnimation: {
                                    enabled: true,
                                    speed: 200
                                }
                            }
                        },
                        plotOptions: {
                            bar: {
                                columnWidth: "80%",
                                barHeight: "80%",
                                distributed: false,
                                dataLabels: {
                                    position: 'top'
                                }
                            }
                        },
                        colors: ['#E63946', '#F4A261', '#2A9D8F'],
                        dataLabels: {
                            enabled: true,
                            formatter: (value) => this.formatNumber(value),
                            offsetY: -20,
                            style: {
                                colors: ["#000"],
                                fontSize: "10px",
                                fontWeight: "bold"
                            }
                        },
                        stroke: {
                            width: 0,
                        },
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
                            categories: ["After Nut",
                                "CKD",
                                "Inspection",
                                "Main",
                                "Outgoing Area",
                                "PC Store 1",
                                "PC Store 2",
                                "Production Coridor AB",
                                "Production Coridor BC",
                                "Production Engineering",
                            ],
                            axisBorder: {
                                show: true,
                                color: '#3b3f5c'
                            },
                            axisTicks: {
                                show: false
                            },
                            labels: {
                                style: {
                                    fontSize: "10px",
                                    fontWeight: "bold",
                                    colors: "#fff"
                                }
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
                            shared: true,
                            intersect: false,
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
                                opacityTo: 1,
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
