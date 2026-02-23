<x-layout.default>

    <script defer src="/assets/js/apexcharts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <style>
        #sales-cost-table tbody tr:hover {
            background-color: #727e93;
            cursor: pointer;
        }

        #sales-cost-table tbody tr {
            background-color: #4B5563;
            color: whitesmoke;
            cursor: pointer;
            font-size: small;
        }

        #sales-cost-table thead tr {
            color: whitesmoke;
        }

        #current_month_customer::-webkit-calendar-picker-indicator,
        #month_year::-webkit-calendar-picker-indicator,
        #month_year_table::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }
    </style>
    <style>
        select[name="sales-cost-table_length"] {
            width: 80px;
        }
    </style>

    <div x-data="analytics()">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>Sales</span>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>Sales Report</span>
            </li>
        </ul>

        <div class="pt-5">
            <div class="grid sm:grid-cols-2 xl:grid-cols-12 gap-6 mb-6">
                <?php
                $currentYear = date('Y');
                $startYear = $currentYear - 5;
                $currentMonth = date('Y-m');
                ?>

                <div class="panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-1 sm:grid-cols-2 dark:text-white-light">
                            <label for="start_year">Start</label>
                            <input id="start_year" type="number" class="form-input" value="<?= $startYear ?>"
                                style="color: white;" />
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 dark:text-white-light">
                            <label for="end_year">End</label>
                            <input id="end_year" type="number" class="form-input" value="<?= $currentYear ?>"
                                style="color: white;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart"
                                x-on:click="() => ChartRecall1(inpt_val_sales_chart.value, inpt_val_cost_chart.value,inpt_val_budget_chart.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_sales_chart" value="" hidden>
                            <input type="text" id="inpt_val_cost_chart" value="" hidden>
                            <input type="text" id="inpt_val_budget_chart" value="" hidden>
                            <div x-ref="BoxChart1" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>


                <div class="panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-2 sm:grid-cols-2 dark:text-white-light">
                            <span class="text-xl font-bold">Profit By Month</span>
                            <input id="current_year" type="number" class="form-input" value="<?= $currentYear ?>"
                                style="color: white;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart_month"
                                x-on:click="() => ChartRecall2(inpt_val_sales_chart_month.value, inpt_val_cost_chart_month.value, inpt_val_budget_chart_month.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_sales_chart_month" value="" hidden>
                            <input type="text" id="inpt_val_cost_chart_month" value="" hidden>
                            <input type="text" id="inpt_val_budget_chart_month" value="" hidden>
                            <div x-ref="BoxChart2" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>

                <div class="panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-2 sm:grid-cols-2 dark:text-white-light">
                            <span class="text-xl font-bold">Customer By Year</span>
                            <input id="current_year_customer" type="number" class="form-input"
                                value="<?= $currentYear ?>" style="color: white;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_cust_year" x-on:click="() => ChartRecall3(inpt_val_sales_cust_year.value, inpt_val_cost_cust_year.value, inpt_val_budget_cust_year.value)" hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_sales_cust_year" value="" hidden>
                            <input type="text" id="inpt_val_cost_cust_year" value="" hidden>
                            <input type="text" id="inpt_val_budget_cust_year" value="" hidden>
                            <div x-ref="BoxChart3" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>


                <div class="panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-2 sm:grid-cols-2 dark:text-white-light">
                            <span class="text-xl font-bold">Customer By Month</span>
                            <input id="current_month_customer" type="month" class="form-input"
                                value="<?= $currentMonth ?>" style="color: white;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_cust_month"
                                x-on:click="() => ChartRecall4(inpt_val_sales_cust_month.value, inpt_val_cost_cust_month.value, inpt_val_budget_cust_month.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_sales_cust_month" value="" hidden>
                            <input type="text" id="inpt_val_cost_cust_month" value="" hidden>
                            <input type="text" id="inpt_val_budget_cust_month" value="" hidden>
                            <div x-ref="BoxChart4" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>

                <div class="panel h-full sm:col-span-12 xl:col-span-12">
                    <div class="grid grid-cols-1 sm:grid-cols-6 gap-4 p-5 dark:text-white-light">
                        <div>
                            <label for="month_year">Month</label>
                            <input id="month_year" type="month" class="form-input" value="<?= $currentMonth ?>"
                                style="color: white;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_cust_date"
                                x-on:click="() => ChartRecall5(inpt_val_sales_cust_date.value, inpt_val_cost_cust_date.value, inpt_val_date.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_sales_cust_date" value="" hidden>
                            <input type="text" id="inpt_val_cost_cust_date" value="" hidden>
                            <input type="text" id="inpt_val_date" value="" hidden>
                            <div x-ref="BoxChart5" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="grid grid-cols-12 gap-6 mb-6">
                <div class="panel h-full col-span-12">
                    <div class="grid grid-cols-1 sm:grid-cols-6 gap-4 p-5 dark:text-white-light">
                        <div>
                            <label for="month_year_table">Month</label>
                            <input id="month_year_table" type="month" class="form-input" value="2025-01"
                                style="color: white;" />
                        </div>
                    </div>
                    <hr>

                    <div class="relative overflow-hidden pt-5">
                        <table id="sales-cost-table" class="min-w-full rounded-md shadow-md overflow-hidden">
                            <thead>
                                <tr class="bg-gray-600 text-gray-600 text-sm leading-normal"
                                    style="font-size: 0.9674rem; color: whitesmoke;">
                                    <th class="py-3 px-6 text-left">No</th>
                                    <th class="py-3 px-6 text-left">Part No</th>
                                    <th class="py-3 px-6 text-left">Sales</th>
                                    <th class="py-3 px-6 text-left">Cost</th>
                                    <th class="py-3 px-6 text-left">Profit</th>
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
        document.addEventListener("DOMContentLoaded", function() {
            function refresh_detail_table() {
                if ($.fn.DataTable.isDataTable('#sales-cost-table')) {
                    $('#sales-cost-table').DataTable().destroy();
                }
                detail_table();
            }

            function formatNumberShort(num) {
                if (num >= 1_000_000_000) {
                    return (num / 1_000_000_000).toFixed(1).replace(/\.0$/, '') + 'B';
                }
                if (num >= 1_000_000) {
                    return (num / 1_000_000).toFixed(1).replace(/\.0$/, '') + 'M';
                }
                if (num >= 1_000) {
                    return (num / 1_000).toFixed(1).replace(/\.0$/, '') + 'K';
                }
                return num;
            }

            function detail_table() {
                var detailTable = $("#sales-cost-table").DataTable({
                    processing: true,
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
                    destroy: true,
                    paging: true,
                    searching: true,
                    lengthChange: true,

                    ajax: {
                        url: `http://${window.location.host}/api/sales/get_sales_cost_table`,
                        type: 'POST',
                        data: function(d) {
                            d.year = document.getElementById('month_year_table').value;
                        },
                        cache: false,
                        dataType: 'json'
                    },
                    columns: [{
                            data: 'no',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'part_no'
                        },
                        {
                            data: 'sales',
                            render: function(data, type, row) {
                                return formatNumberShort(data);
                            }
                        },
                        {
                            data: 'cost',
                            render: function(data, type, row) {
                                return formatNumberShort(data);
                            }
                        },
                        {
                            data: 'profit',
                            render: function(data, type, row) {
                                return formatNumberShort(data);
                            }
                        },
                        {
                            data: 'status'
                        }
                    ],
                    createdRow: function(row, data, dataIndex) {
                        if (dataIndex % 2 === 0) {
                            $(row).css('background-color', '#1D1D1D');
                        } else {
                            $(row).css('background-color', '#2D2D2D');
                        }
                    }
                });

                setTimeout(function() {
                    detailTable.ajax.reload();
                }, 200);

                return true;
            }


            function setDefaultData() {
                var currentHost = window.location.host;
                var yearX = document.getElementById('start_year').value + '~' + document.getElementById('end_year').value;
                const apiUrl = `http://${currentHost}/api/sales/get_profit_yearly/${yearX}`;

                axios.get(apiUrl)
                    .then(response => {
                        var data_chart_sales = response.data.data_chart_sales;
                        var data_chart_cost = response.data.data_chart_cost;
                        var data_chart_budget = response.data.data_chart_budget;
                        document.getElementById('inpt_val_sales_chart').value = data_chart_sales;
                        document.getElementById('inpt_val_cost_chart').value = data_chart_cost;
                        document.getElementById('inpt_val_budget_chart').value = data_chart_budget;
                        document.getElementById('btn_update_chart').click();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });

                var monthX = document.getElementById('current_year').value;
                const apiUrlMonth = `http://${currentHost}/api/sales/get_profit_monthly/${monthX}`;
                axios.get(apiUrlMonth)
                    .then(response => {
                        var data_chart_sales_month = response.data.data_chart_sales_month;
                        var data_chart_cost_month = response.data.data_chart_cost_month;
                        var data_chart_budget_month = response.data.data_chart_budget_month;
                        document.getElementById('inpt_val_sales_chart_month').value = data_chart_sales_month;
                        document.getElementById('inpt_val_cost_chart_month').value = data_chart_cost_month;
                        document.getElementById('inpt_val_budget_chart_month').value = data_chart_budget_month;
                        document.getElementById('btn_update_chart_month').click();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });

                var year_cust = document.getElementById('current_year_customer').value;
                const apiUrlCust = `http://${currentHost}/api/sales/get_profit_cust_yearly/${year_cust}`;
                axios.get(apiUrlCust)
                    .then(response => {
                        var data_cust_sales_year = response.data.data_cust_sales_year;
                        var data_cust_cost_year = response.data.data_cust_cost_year;
                        var data_cust_budget_year = response.data.data_cust_budget_year;
                        document.getElementById('inpt_val_sales_cust_year').value = data_cust_sales_year;
                        document.getElementById('inpt_val_cost_cust_year').value = data_cust_cost_year;
                        document.getElementById('inpt_val_budget_cust_year').value = data_cust_budget_year;
                        document.getElementById('btn_update_cust_year').click();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });

                var month_cust = document.getElementById('current_month_customer').value;
                const apiUrlCustMonth =
                    `http://${currentHost}/api/sales/get_profit_cust_monthly/${month_cust}`;
                axios.get(apiUrlCustMonth)
                    .then(response => {
                        var data_cust_sales_month = response.data.data_cust_sales_month;
                        var data_cust_cost_month = response.data.data_cust_cost_month;
                        var data_cust_budget_month = response.data.data_cust_budget_month;
                        document.getElementById('inpt_val_sales_cust_month').value = data_cust_sales_month;
                        document.getElementById('inpt_val_cost_cust_month').value = data_cust_cost_month;
                        document.getElementById('inpt_val_budget_cust_month').value = data_cust_budget_month;
                        document.getElementById('btn_update_cust_month').click();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });

                var date_cust = document.getElementById('month_year').value;
                const apiUrlCustDate = `http://${currentHost}/api/sales/get_profit_cust_date/${date_cust}`;
                axios.get(apiUrlCustDate)
                    .then(response => {
                        var data_cust_sales_date = response.data.data_cust_sales_date;
                        var data_cust_cost_date = response.data.data_cust_cost_date;
                        var data_cust_date = response.data.data_val_date;
                        document.getElementById('inpt_val_sales_cust_date').value = data_cust_sales_date;
                        document.getElementById('inpt_val_cost_cust_date').value = data_cust_cost_date;
                        document.getElementById('inpt_val_date').value = data_cust_date;
                        document.getElementById('btn_update_cust_date').click();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
            }

            setDefaultData();
            detail_table();

            document.getElementById('current_year').addEventListener('input', function() {
                var currentHost = window.location.host;
                var monthX = this.value;
                const apiUrlMonth = `http://${currentHost}/api/sales/get_profit_monthly/${monthX}`;

                axios.get(apiUrlMonth)
                    .then(response => {
                        var data_chart_sales_month = response.data.data_chart_sales_month;
                        var data_chart_cost_month = response.data.data_chart_cost_month;
                        var data_chart_budget_month = response.data.data_chart_budget_month;
                        document.getElementById('inpt_val_sales_chart_month').value = data_chart_sales_month;
                        document.getElementById('inpt_val_cost_chart_month').value = data_chart_cost_month;
                        document.getElementById('inpt_val_budget_chart_month').value = data_chart_budget_month;

                        document.getElementById('btn_update_chart_month').click();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
            });


            document.getElementById('current_year_customer').addEventListener('input', function() {
                var currentHost = window.location.host;
                var year_cust = this.value;
                const apiUrlCust = `http://${currentHost}/api/sales/get_profit_cust_yearly/${year_cust}`;

                axios.get(apiUrlCust)
                    .then(response => {
                        var data_cust_sales_year = response.data.data_cust_sales_year;
                        var data_cust_cost_year = response.data.data_cust_cost_year;
                        var data_cust_budget_year = response.data.data_cust_budget_year;
                        document.getElementById('inpt_val_sales_cust_year').value = data_cust_sales_year;
                        document.getElementById('inpt_val_cost_cust_year').value = data_cust_cost_year;
                        document.getElementById('inpt_val_budget_cust_year').value = data_cust_budget_year;
                        document.getElementById('btn_update_cust_year').click();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
            });

            document.getElementById('current_month_customer').addEventListener('input', function() {
                var currentHost = window.location.host;
                var month_cust = this.value;
                const apiUrlCustMonth = `http://${currentHost}/api/sales/get_profit_cust_monthly/${month_cust}`;

                axios.get(apiUrlCustMonth)
                    .then(response => {
                        var data_cust_sales_month = response.data.data_cust_sales_month;
                        var data_cust_cost_month = response.data.data_cust_cost_month;
                        var data_cust_budget_month = response.data.data_cust_budget_month;
                        document.getElementById('inpt_val_sales_cust_month').value = data_cust_sales_month;
                        document.getElementById('inpt_val_cost_cust_month').value = data_cust_cost_month;
                        document.getElementById('inpt_val_budget_cust_month').value = data_cust_budget_month;

                        document.getElementById('btn_update_cust_month').click();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
            });


            document.getElementById('month_year').addEventListener('input', function() {
                var currentHost = window.location.host;
                var date_cust = this.value;
                const apiUrlCustDate = `http://${currentHost}/api/sales/get_profit_cust_date/${date_cust}`;

                axios.get(apiUrlCustDate)
                    .then(response => {
                        var data_cust_sales_date = response.data.data_cust_sales_date;
                        var data_cust_cost_date = response.data.data_cust_cost_date;
                        var data_cust_date = response.data.data_val_date;
                        document.getElementById('inpt_val_sales_cust_date').value = data_cust_sales_date;
                        document.getElementById('inpt_val_cost_cust_date').value = data_cust_cost_date;
                        document.getElementById('inpt_val_date').value = data_cust_date;
                        document.getElementById('btn_update_cust_date').click();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
            });

            document.getElementById('month_year_table').addEventListener('change', function(event) {
                refresh_detail_table();
            });

        });

        document.addEventListener("alpine:init", () => {
            Alpine.data("analytics", () => ({
                data: {
                    analytics: "Initial Data"
                },

                formatNumber(value) {
                    if (value >= 1e12) return (value / 1e12).toFixed(0) + 'T';
                    if (value >= 1e9) return (value / 1e9).toFixed(0) + 'B';
                    if (value >= 1e6) return (value / 1e6).toFixed(0) + 'M';
                    if (value >= 1e3) return (value / 1e3).toFixed(0) + 'K';
                    return value;
                },

                ChartRecall1(salesData, costData, budgetData) {
                    const sales = salesData.split(',').map(Number);
                    const cost = costData.split(',').map(Number);
                    const budget = budgetData.split(',').map(Number);
                    const currentHost = window.location.host;

                    this.BoxChart1.updateSeries([{
                            name: 'Sales',
                            data: sales
                        },
                        {
                            name: 'Cost',
                            data: cost
                        }, {
                            name: 'Budget',
                            data: budget // Assuming budget is sales - cost
                        }
                    ]);

                    this.BoxChart1.updateOptions({
                        chart: {
                            events: {
                                click: function(event, chartContext, config) {
                                    const value = config.globals.categoryLabels[config.dataPointIndex];

                                    const apiUrlMonth = `http://${currentHost}/api/sales/get_profit_monthly/${value}`;
                                    axios.get(apiUrlMonth)
                                        .then(response => {
                                            document.getElementById('inpt_val_sales_chart_month').value = response.data.data_chart_sales_month;
                                            document.getElementById('inpt_val_cost_chart_month').value = response.data.data_chart_cost_month;
                                            document.getElementById('inpt_val_budget_chart_month').value = data_chart_budget_month;
                                            document.getElementById('btn_update_chart_month').click();
                                            $("#current_year").val(value);
                                        })

                                    const apiUrlCust = `http://${currentHost}/api/sales/get_profit_cust_yearly/${value}`;
                                    axios.get(apiUrlCust)
                                        .then(response => {
                                            document.getElementById('inpt_val_sales_cust_year').value = response.data.data_cust_sales_year;
                                            document.getElementById('inpt_val_cost_cust_year').value = response.data.data_cust_cost_year;
                                            document.getElementById('inpt_val_budget_cust_year').value = response.data.data_cust_budget_year;
                                            document.getElementById('btn_update_cust_year').click();
                                            $("#current_year_customer").val(value);
                                        })
                                }
                            }
                        }
                    });
                },

                ChartRecall2(salesData, costData, budgetData) {
                    const sales = salesData.split(',').map(Number);
                    const cost = costData.split(',').map(Number);
                    const budget = budgetData.split(',').map(Number);
                    const currentHost = window.location.host;

                    this.BoxChart2.updateSeries([{
                            name: 'Sales',
                            data: sales
                        },
                        {
                            name: 'Cost',
                            data: cost
                        },
                        {
                            name: 'Budget',
                            data: budget
                        }
                    ]);

                    this.BoxChart2.updateOptions({
                        chart: {
                            events: {
                                click: function(event, chartContext, config) {
                                    const value = config.globals.categoryLabels[config.dataPointIndex];

                                    let monthIndex = (config.dataPointIndex + 1).toString().padStart(2, '0');
                                    const year = $("#current_year").val();
                                    const postValue = `${year}-${monthIndex}`;

                                    const apiUrlCustMonth = `http://${currentHost}/api/sales/get_profit_cust_monthly/${postValue}`;
                                    axios.get(apiUrlCustMonth)
                                        .then(response => {
                                            document.getElementById('inpt_val_sales_cust_month').value = response.data.data_cust_sales_month;
                                            document.getElementById('inpt_val_cost_cust_month').value = response.data.data_cust_cost_month;
                                            document.getElementById('inpt_val_budget_cust_month').value = data_cust_budget_month;
                                            document.getElementById('btn_update_cust_month').click();
                                            $("#current_month_customer").val(postValue);
                                        })
                                        .catch(error => {
                                            console.error('Error fetching customer monthly data:', error);
                                        });

                                    const apiUrlCustDate = `http://${currentHost}/api/sales/get_profit_cust_date/${postValue}`;
                                    axios.get(apiUrlCustDate)
                                        .then(response => {
                                            document.getElementById('inpt_val_sales_cust_date').value = response.data.data_cust_sales_date;
                                            document.getElementById('inpt_val_cost_cust_date').value = response.data.data_cust_cost_date;
                                            document.getElementById('inpt_val_date').value = response.data.data_val_date;
                                            document.getElementById('btn_update_cust_date').click();
                                            $("#month_year").val(postValue);
                                        })
                                        .catch(error => {
                                            console.error('Error fetching customer date data:', error);
                                        });

                                    $("#month_year_table").val(postValue).change();
                                }
                            }
                        }
                    });
                },

                ChartRecall3(salesData, costData, budgetData) {
                    const sales = salesData.split(',').map(Number);
                    const cost = costData.split(',').map(Number);
                    const budget = budgetData.split(',').map(Number);

                    this.BoxChart3.updateSeries([{
                            name: 'Sales',
                            data: sales
                        },
                        {
                            name: 'Cost',
                            data: cost
                        },
                        {
                            name: 'Budget',
                            data: budget
                        },
                    ]);

                },

                ChartRecall4(salesData, costData, dataBudget) {
                    const sales = salesData.split(',').map(Number);
                    const cost = costData.split(',').map(Number);
                    const budget = dataBudget.split(',').map(Number);

                    this.BoxChart4.updateSeries([{
                            name: 'Sales',
                            data: sales
                        }, {
                            name: 'Cost',
                            data: cost
                        },
                        {
                            name: 'Budget',
                            data: budget
                        }
                    ]);
                },

                ChartRecall5(salesData, costData, categoriesData) {
                    const sales = salesData.split(',').map(Number);
                    const cost = costData.split(',').map(Number);
                    const categories = categoriesData.split(',');
                    this.BoxChart5.updateOptions({
                        xaxis: {
                            categories: categories
                        }
                    });

                    this.BoxChart5.updateSeries([{
                        name: 'Sales',
                        data: sales
                    }, {
                        name: 'Cost',
                        data: cost
                    }]);
                },



                renderCharts() {
                    this.BoxChart1 = new ApexCharts(this.$refs.BoxChart1, this.BoxChart1Options);
                    this.BoxChart1.render();

                    this.BoxChart2 = new ApexCharts(this.$refs.BoxChart2, this.BoxChart2Options);
                    this.BoxChart2.render();

                    this.BoxChart3 = new ApexCharts(this.$refs.BoxChart3, this.BoxChart3Options);
                    this.BoxChart3.render();

                    this.BoxChart4 = new ApexCharts(this.$refs.BoxChart4, this.BoxChart4Options);
                    this.BoxChart4.render();

                    this.BoxChart5 = new ApexCharts(this.$refs.BoxChart5, this.BoxChart5Options);
                    this.BoxChart5.render();
                },

                get BoxChart1Options() {
                    return {
                        series: [{
                                name: 'Sales',
                                data: [0, 0, 0, 0, 0, 0, 0]
                            },
                            {
                                name: 'Cost',
                                data: [0, 0, 0, 0, 0, 0, 0]
                            },
                            {
                                name: 'Budget',
                                data: [0, 0, 0, 0, 0, 0, 0]
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
                            colors: ['#22963a', '#f44336', '#1E90FF']
                        },
                        colors: ['#22963a', '#f44336', '#1E90FF'],
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
                            categories: ['2021', '2022', '2023', '2024', '2025', '2026'],
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

                get BoxChart2Options() {
                    return {
                        series: [{
                                name: 'Sales',
                                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                            },
                            {
                                name: 'Cost',
                                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                            },
                            {
                                name: 'Budget',
                                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
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
                            colors: ['#22963a', '#f44336', '#1E90FF'],
                        },
                        colors: ['#22963a', '#f44336', '#1E90FF'],
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

                get BoxChart3Options() {
                    return {
                        series: [{
                                name: 'Sales',
                                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                            },
                            {
                                name: 'Cost',
                                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                            },
                            {
                                name: 'Budget',
                                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
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
                            colors: ['#22963a', '#f44336', '#1E90FF'],
                        },
                        colors: ['#22963a', '#f44336', '#1E90FF'],
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
                            categories: ['MMKI', 'HPM', 'MMKSI', 'SIM', 'TMMIN', 'UDAMI', 'MKM',
                                'IAMI', 'SIS'
                            ],
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

                get BoxChart4Options() {
                    return {
                        series: [{
                                name: 'Sales',
                                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                            },
                            {
                                name: 'Cost',
                                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                            },
                            {
                                name: 'Cost',
                                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
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
                            colors: ['#22963a', '#f44336', '#1E90FF'],
                        },
                        colors: ['#22963a', '#f44336', '#1E90FF'],
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
                            categories: ['MMKI', 'HPM', 'MMKSI', 'SIM', 'TMMIN', 'UDAMI', 'MKM',
                                'IAMI', 'SIS'
                            ],
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
                                name: 'Sales',
                                data: []
                            },
                            {
                                name: 'Cost',
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
                            colors: ['#22963a', '#f44336']
                        },
                        colors: ['#22963a', '#f44336'],
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
            }));
        });
    </script>
</x-layout.default>
