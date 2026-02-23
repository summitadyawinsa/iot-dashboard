<x-layout.default>
    <script defer src="/assets/js/apexcharts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        select[name="profit_model_year_table_length"],
        select[name="profit_by_month_table_length"],
        select[name="profit_category_year_table_length"],
        select[name="profit_category_month_table_length"],
        select[name="profit_cust_by_month_table_length"],
        select[name="profit_by_year_table_length"],
        select[name="profit_cust_by_year_table_length"],
        select[name="profit_model_month_table_length"] {
            background-color: #4B5563;
            width: 50px;
            font-size: 9pt;
            margin: 10px;
        }

        .dataTables_paginate  {
            margin-top: 10px
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
                <span>Finance</span>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>Invoice Report</span>
            </li>
        </ul>

        <div class="pt-5">
            <div class="grid sm:grid-cols-2 xl:grid-cols-12 gap-6 mb-6">
                <?php
                $currentYear = date('Y');
                $startYear = $currentYear - 5;
                $currentMonth = date('Y-m');
                $date = DateTime::createFromFormat('Y-m', $currentMonth);

                $date->modify('-1 month');

                $lastMonth = $date->format('Y-m');
                ?>

                <div class="panel h-full sm:col-span-6 xl:col-span-6">

                    <div class="p-5 dark:text-white-light">
                        <div class="flex items-center justify-between w-full">
                            <label for="start_year" class="text-xl font-bold">
                                Profit By Years
                            </label>

                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <label for="start_year" class="font-medium">Start</label>
                                    <input id="start_year" type="number"
                                        class="form-input w-24 border border-gray-600 rounded-md"
                                        value="<?= $startYear ?>" />
                                </div>
                                <div class="flex items-center gap-2">
                                    <label for="end_year" class="font-medium">End</label>
                                    <input id="end_year" type="number"
                                        class="form-input w-24 border border-gray-600 rounded-md"
                                        value="<?= $currentYear ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart"
                                x-on:click="() => ChartRecall1(inpt_val_data_year.value, inpt_val_sales_chart.value,
                                 inpt_val_cost_chart.value,
                                 inpt_val_expenses_chart.value,inpt_val_profit_chart.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_data_year" value="" hidden>
                            <input type="text" id="inpt_val_sales_chart" value="" hidden>
                            <input type="text" id="inpt_val_cost_chart" value="" hidden>
                            <input type="text" id="inpt_val_expenses_chart" value="" hidden>
                            <input type="text" id="inpt_val_profit_chart" value="" hidden>
                            <div x-ref="BoxChart1" class="overflow-hidden"></div>
                        </div>
                    </div>
                    <div
                        class="relative overflow-x-auto border p-4 border-gray-300 dark:border-gray-700 sm:rounded-lg pt-5">
                        <table id="profit_by_year_table"
                            class="w-full text-sm text-left text-gray-800 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-900 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Year
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Sales
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Cost
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Expenses
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Profit
                                    </th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>

                <div class="panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-2 items-center w-full">
                            <label for="current_year" class="text-xl font-bold">
                                Profit By Months
                            </label>
                            <input id="current_year" type="number" class="form-input justify-self-end w-[50%]"
                                value="<?= $currentYear ?>" ;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart_month"
                                x-on:click="() => ChartRecall2(inpt_val_sales_chart_month.value,
                                    inpt_val_cost_chart_month.value,
                                    inpt_val_expenses_chart_month.value,inpt_val_profit_chart_month.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_sales_chart_month" value="" hidden>
                            <input type="text" id="inpt_val_cost_chart_month" value="" hidden>
                            <input type="text" id="inpt_val_expenses_chart_month" value="" hidden>
                            <input type="text" id="inpt_val_profit_chart_month" value="" hidden>
                            <div x-ref="BoxChart2" class="overflow-hidden"></div>
                        </div>
                    </div>
                    <div
                        class="relative overflow-x-auto border p-4 border-gray-300 dark:border-gray-700 sm:rounded-lg pt-5">
                        <table id="profit_by_month_table"
                            class="w-full text-sm text-left text-gray-800 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-900 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Year</th>
                                    <th scope="col" class="px-6 py-3">Months</th>
                                    <th scope="col" class="px-6 py-3">Sales</th>
                                    <th scope="col" class="px-6 py-3">Cost</th>
                                    <th scope="col" class="px-6 py-3">Expenses</th>
                                    <th scope="col" class="px-6 py-3">Profit</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-2 items-center w-full">
                            <label for="current_year_customer" class="text-xl font-bold">
                                Profit Per Customers By Years
                            </label>
                            <input id="current_year_customer" type="number"
                                class="form-input justify-self-end w-[50%]" value="<?= $currentYear ?>" ;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_cust_year"
                                x-on:click="() => ChartRecall3(inpt_val_data_month.value, inpt_val_sales_cust_year.value, inpt_val_cost_cust_year.value,inpt_val_expenses_cust_year.value,inpt_val_profit_cust_year.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_data_month" value="" hidden>
                            <input type="text" id="inpt_val_sales_cust_year" value="" hidden>
                            <input type="text" id="inpt_val_cost_cust_year" value="" hidden>
                            <input type="text" id="inpt_val_expenses_cust_year" value="" hidden>
                            <input type="text" id="inpt_val_profit_cust_year" value="" hidden>
                            <div x-ref="BoxChart3" class="overflow-hidden"></div>
                        </div>
                    </div>
                    <div
                        class="relative overflow-x-auto border p-4 border-gray-300 dark:border-gray-700 sm:rounded-lg pt-5">
                        <table id="profit_cust_by_year_table"
                            class="w-full text-sm text-left text-gray-800 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-900 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Year</th>
                                    <th scope="col" class="px-6 py-3">Cust</th>
                                    <th scope="col" class="px-6 py-3">Sales</th>
                                    <th scope="col" class="px-6 py-3">Cost</th>
                                    <th scope="col" class="px-6 py-3">Expenses</th>
                                    <th scope="col" class="px-6 py-3">Profit</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <div class="panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-2 items-center w-full">
                            <label for="current_month_customer" class="text-xl font-bold">
                                Profit Per Customers By Months
                            </label>
                            <input id="current_month_customer" type="month"
                                class="form-input justify-self-end w-[50%]" value="<?= $lastMonth ?>" ;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_cust_month"
                                x-on:click="() => ChartRecall4(inpt_val_data_cust.value, inpt_val_sales_cust_month.value, inpt_val_cost_cust_month.value, inpt_val_expenses_cust_month.value, inpt_val_profit_cust_month.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_data_cust" value="" hidden>
                            <input type="text" id="inpt_val_sales_cust_month" value="" hidden>
                            <input type="text" id="inpt_val_cost_cust_month" value="" hidden>
                            <input type="text" id="inpt_val_expenses_cust_month" value="" hidden>
                            <input type="text" id="inpt_val_profit_cust_month" value="" hidden>
                            <div x-ref="BoxChart4" class="overflow-hidden"></div>
                        </div>
                    </div>
                    <div
                        class="relative overflow-x-auto border p-4 border-gray-300 dark:border-gray-700 sm:rounded-lg pt-5">
                        <table id="profit_cust_by_month_table"
                            class="w-full text-sm text-left text-gray-800 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-900 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Year</th>
                                    <th scope="col" class="px-6 py-3">Months</th>
                                    <th scope="col" class="px-6 py-3">Cust</th>
                                    <th scope="col" class="px-6 py-3">Sales</th>
                                    <th scope="col" class="px-6 py-3">Cost</th>
                                    <th scope="col" class="px-6 py-3">Expenses</th>
                                    <th scope="col" class="px-6 py-3">Profit</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-3">
                            <label for="selectYearly" class="text-xl font-bold">Profit By Model Year</label>
                            <span></span>
                            <input id="selectYearly" type="number" class="form-input"
                                value="<?= $currentYear ?>";" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart_model"
                                x-on:click="() => ChartRecall12(inpt_data_sales.value, inpt_data_cost.value, inpt_data_expenses.value, inpt_data_profit.value, inpt_data_model.value)"
                                hidden>Recall and Update
                            </button>
                            <input type="text" id="inpt_data_sales" value="" hidden>
                            <input type="text" id="inpt_data_cost" value="" hidden>
                            <input type="text" id="inpt_data_expenses" value="" hidden>
                            <input type="text" id="inpt_data_profit" value="" hidden>
                            <input type="text" id="inpt_data_model" value="" hidden>
                            <div x-ref="BoxChart13" class="overflow-hidden"></div>
                        </div>
                    </div>
                    <div
                        class="relative overflow-x-auto border p-4 border-gray-300 dark:border-gray-700 sm:rounded-lg pt-5">
                        <table id="profit_model_year_table"
                            class="w-full text-sm text-left text-gray-800 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-900 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Invoice Year</th>
                                    <th scope="col" class="px-6 py-3">Model</th>
                                    <th scope="col" class="px-6 py-3">Sales</th>
                                    <th scope="col" class="px-6 py-3">Cost</th>
                                    <th scope="col" class="px-6 py-3">Expenses</th>
                                    <th scope="col" class="px-6 py-3">Profit</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-3">
                            <label for="selectMonthly" class="text-xl font-bold">Profit By Model Month</label>
                            <span></span>
                            <input id="selectMonthly" type="month" class="form-input" value="<?= $lastMonth ?>"
                                ;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_month"
                                x-on:click="() => ChartRecall13(inpt_data_sales_month.value, inpt_data_cost_month.value, inpt_data_expenses_month.value, inpt_data_profit_month.value, inpt_data_model_month.value)"
                                hidden>Recall and Update
                            </button>
                            <input type="text" id="inpt_data_sales_month" value="" hidden>
                            <input type="text" id="inpt_data_cost_month" value="" hidden>
                            <input type="text" id="inpt_data_expenses_month" value="" hidden>
                            <input type="text" id="inpt_data_profit_month" value="" hidden>
                            <input type="text" id="inpt_data_model_month" value="" hidden>
                            <div x-ref="BoxChart14" class="overflow-hidden"></div>
                        </div>
                    </div>

                    <div
                        class="relative overflow-x-auto border p-4 border-gray-300 dark:border-gray-700 sm:rounded-lg pt-5">
                        <table id="profit_model_month_table"
                            class="w-full text-sm text-left text-gray-800 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-900 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Invoice Year</th>
                                    <th scope="col" class="px-6 py-3">Invoice Month</th>
                                    <th scope="col" class="px-6 py-3">Model</th>
                                    <th scope="col" class="px-6 py-3">Sales</th>
                                    <th scope="col" class="px-6 py-3">Cost</th>
                                    <th scope="col" class="px-6 py-3">Expenses</th>
                                    <th scope="col" class="px-6 py-3">Profit</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!--  -->
                <div class="panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-3">
                            <label for="selectDateCategory" class="text-xl font-bold">Profit By Category Year</label>
                            <span></span>
                            <input id="selectDateCategory" type="number" class="form-input"
                                value="<?= $currentYear ?>" ;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_category"
                                x-on:click="() => ChartRecall14(inpt_data_sales_category.value, inpt_data_cost_category.value, inpt_data_expenses_category.value, inpt_data_profit_category.value, inpt_data_category.value)"
                                hidden>Recall and Update
                            </button>
                            <input type="text" id="inpt_data_sales_category" value="" hidden>
                            <input type="text" id="inpt_data_cost_category" value="" hidden>
                            <input type="text" id="inpt_data_expenses_category" value="" hidden>
                            <input type="text" id="inpt_data_profit_category" value="" hidden>
                            <input type="text" id="inpt_data_category" value="" hidden>
                            <div x-ref="BoxChart15" class="overflow-hidden"></div>
                        </div>
                    </div>

                    <div
                        class="relative overflow-x-auto border p-4 border-gray-300 dark:border-gray-700 sm:rounded-lg pt-5">
                        <table id="profit_category_year_table"
                            class="w-full text-sm text-left text-gray-800 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-900 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Year</th>
                                    <th scope="col" class="px-6 py-3">Category</th>
                                    <th scope="col" class="px-6 py-3">Sales</th>
                                    <th scope="col" class="px-6 py-3">Cost</th>
                                    <th scope="col" class="px-6 py-3">Expenses</th>
                                    <th scope="col" class="px-6 py-3">Profit</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- Months -->
                <div class="panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-3">
                            <label for="selectMonthCategory" class="text-xl font-bold">Profit By Category
                                Month</label>
                            <span></span>
                            <input id="selectMonthCategory" type="month" class="form-input"
                                value="<?= $lastMonth ?>" ;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_category_month"
                                x-on:click="() => ChartRecall15(inpt_data_sales_category_month.value, inpt_data_cost_category_month.value, inpt_data_expenses_category_month.value, inpt_data_profit_category_month.value, inpt_data_category_month.value)"
                                hidden>Recall and Update
                            </button>
                            <input type="text" id="inpt_data_sales_category_month" value="" hidden>
                            <input type="text" id="inpt_data_cost_category_month" value="" hidden>
                            <input type="text" id="inpt_data_expenses_category_month" value="" hidden>
                            <input type="text" id="inpt_data_profit_category_month" value="" hidden>
                            <input type="text" id="inpt_data_category_month" value="" hidden>
                            <div x-ref="BoxChart16" class="overflow-hidden"></div>
                        </div>
                    </div>
                    <div
                        class="relative overflow-x-auto border p-4 border-gray-300 dark:border-gray-700 sm:rounded-lg pt-5">
                        <table id="profit_category_month_table"
                            class="w-full text-sm text-left text-gray-800 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-900 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Year</th>
                                    <th scope="col" class="px-6 py-3">Months</th>
                                    <th scope="col" class="px-6 py-3">Category</th>
                                    <th scope="col" class="px-6 py-3">Sales</th>
                                    <th scope="col" class="px-6 py-3">Cost</th>
                                    <th scope="col" class="px-6 py-3">Expenses</th>
                                    <th scope="col" class="px-6 py-3">Profit</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="panel h-full sm:col-span-12 xl:col-span-12" hidden>
                    <label for="selectYearly" class="text-xl font-bold">Day by Day Graphic</label>

                    <div class="grid grid-cols-1 sm:grid-cols-6 gap-4 p-5 dark:text-white-light">
                        <div>
                            <label for="month_year">Month</label>
                            <input id="month_year" type="month" class="form-input" value="2025-01" ;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_cust_date"
                                x-on:click="() => ChartRecall5(inpt_val_sales_cust_date.value, inpt_val_cost_cust_date.value, inpt_val_date.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_sales_cust_date" value="" hidden> <input
                                type="text" id="inpt_val_cost_cust_date" value="" hidden> <input
                                type="text" id="inpt_val_date" value="" hidden>
                            <div x-ref="BoxChart5" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-12 gap-6 mb-6">
                <div class="panel h-full col-span-12">
                    <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-3">
                            <label for="month_year_table" class="text-xl font-bold">Part Monthly</label>
                            <span></span>
                            <input id="month_year_table" type="month" class="form-input" value="<?= $lastMonth ?>"
                                ;" />
                        </div>
                    </div>

                    <div
                        class="relative overflow-x-auto border p-4 border-gray-300 dark:border-gray-700 sm:rounded-lg pt-5">
                        <table id="sales-cost-table"
                            class="min-w-full text-sm text-left text-gray-800 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-900 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">No</th>
                                    <th scope="col" class="px-6 py-3">Part No</th>
                                    <th scope="col" class="px-6 py-3">Qty</th>
                                    <th scope="col" class="px-6 py-3">Sales</th>
                                    <th scope="col" class="px-6 py-3">Cost</th>
                                    <th scope="col" class="px-6 py-3">Expenses</th>
                                    <th scope="col" class="px-6 py-3">Profit</th>
                                    <th scope="col" class="px-6 py-3">Percents</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div id="detail_modal"
                class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
                <div class="panel rounded-lg shadow-xl w-full max-w-5xl max-h-[90vh] flex flex-col">

                    <div class="flex justify-between items-center p-4 border-b border-gray-600">
                        <div>
                            <h5 class="text-xl font-bold">
                                Row Detail
                            </h5>
                            <span id="date-title" class="text-md mt-1"></span>
                        </div>
                        <button id="closeModal" class="focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div id="modal-body-content" class="p-4 overflow-y-auto">
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

            function detail_table() {
                detailTablePartByMonth = $("#sales-cost-table").DataTable({
                    destroy: true,
                    scrollX: true,
                    processing: true,
                    serverSide: true,
                    responsive:true,
                    language: {
                        processing: '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
                    },
                    info: false,
                    order: [],
                    columnDefs: [{
                        orderable: false,
                        targets: 0
                    }],
                    ajax: {
                        url: `https://${window.location.host}/api/finance/get_invoice_cost_table`,
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
                            data: 'PartNum'
                        },
                        {
                            data: 'qty'
                        },
                        {
                            data: 'sales'
                        },
                        {
                            data: 'cost'
                        },
                        {
                            data: 'expenses'
                        },
                        {
                            data: 'profit'
                        },
                        {
                            data: 'percens',
                            render: function(data, type, row) {
                                if (data === null) return '0.0%';
                                return data + '%';
                            }
                        },
                        {
                            data: 'status'
                        }
                    ],
                    createdRow: function(row, data, dataIndex) {


                        ['status'].forEach(function(column) {
                            var cell = $('td', row).eq(getColumnIndex(column));

                            if (data[column] === 'Profit') {
                                cell.html(
                                    '<span class="badge badge-outline-success">Profit</span>'
                                );
                            } else if (data[column] === 'Loss') {
                                cell.html(
                                    '<span class="badge badge-outline-danger">Loss</span>'
                                );
                            }
                        });
                    },
                });

                function formatNumber(num) {
                    if (num === null || num === undefined || num === '') return 'N/A';
                    const sanitizedNum = String(num).replace(/,/g, '');
                    const number = parseFloat(sanitizedNum);
                    if (isNaN(number)) return 'N/A';
                    return number.toLocaleString('en-US', {

                    });
                }

                $('#sales-cost-table tbody').on('click', 'td', function() {
                    const cell = detailTablePartByMonth.cell(this);
                    const columnIndex = cell.index().column;

                    if (columnIndex >= 1 && columnIndex <= 6) {
                        const rowData = detailTablePartByMonth.row(this).data();
                        const year = $('#month_year_table').val();

                        document.getElementById('date-title').innerText = `Part Number: ${rowData.PartNum}`;
                        document.getElementById('detail_modal').classList.remove('hidden');
                        document.getElementById('modal-body-content').innerHTML =
                            `<div class="lds-roller text-center mx-auto"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>`;

                        $.ajax({
                            url: `https://${window.location.host}/api/finance/get_invoice_cost_table_detail`,
                            type: 'POST',
                            contentType: 'application/json',
                            data: JSON.stringify({
                                "PartNum": rowData.PartNum,
                                "year": year
                            }),
                            success: function(response) {
                                if (!response.data || response.data.length === 0) {
                                    document.getElementById('modal-body-content').innerHTML =
                                        `<div class="text-center py-8">No data available.</div>`;
                                    return;
                                }

                                let totals = {
                                    LaborCost: 0,
                                    BurdenCost: 0,
                                    MtlUnitCost: 0,
                                    BurMtlUniCost: 0,
                                    SbcCost: 0,
                                    VarianceAplLabor: 0,
                                    VarianceAplMaterial: 0,
                                    VarianceAplBurden: 0,
                                    VarianceMtlBurden: 0,
                                    VarianceSubCont: 0,
                                };

                                response.data.forEach(item => {
                                    for (const key in totals) {
                                        totals[key] += parseFloat(String(item[key])
                                            .replace(/,/g, '')) || 0;
                                    }
                                });

                                let cardsHTML = `<div class="p-4">`;

                                cardsHTML += `
                                        <h3 class="text-lg font-semibold mb-4">Direct Costs & COGM</h3>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                                            <div class="bg-gray-700 p-4 rounded-lg flex items-center">
                                                <div class="shrink-0 bg-gray-800 border border-gray-600 rounded-xl w-14 h-14 flex justify-center items-center font-bold text-white text-xl">L</div>
                                                <div class="ltr:ml-4 rtl:mr-4">
                                                    <p class="text-lg font-bold">${formatNumber(totals.LaborCost)}</p>
                                                    <h5 class="text-md">Labor Cost</h5>
                                                </div>
                                            </div>
                                            <div class="bg-gray-700 p-4 rounded-lg flex items-center">
                                                <div class="shrink-0 bg-gray-800 border border-gray-600 rounded-xl w-14 h-14 flex justify-center items-center font-bold text-white text-xl">B</div>
                                                <div class="ltr:ml-4 rtl:mr-4">
                                                    <p class="text-lg font-bold">${formatNumber(totals.BurdenCost)}</p>
                                                    <h5 class="text-md">Burden Cost</h5>
                                                </div>
                                            </div>
                                            <div class="bg-gray-700 p-4 rounded-lg flex items-center">
                                                <div class="shrink-0 bg-gray-800 border border-gray-600  rounded-xl w-14 h-14 flex justify-center items-center font-bold text-white text-xl">M</div>
                                                <div class="ltr:ml-4 rtl:mr-4">
                                                    <p class="text-lg font-bold">${formatNumber(totals.MtlUnitCost)}</p>
                                                    <h5 class="text-md">Material Unit Cost</h5>
                                                </div>
                                            </div>
                                            <div class="bg-gray-700 p-4 rounded-lg flex items-center">
                                                <div class="shrink-0 bg-gray-800 border border-gray-600 rounded-xl w-14 h-14 flex justify-center items-center font-bold text-white text-xl">BM</div>
                                                <div class="ltr:ml-4 rtl:mr-4">
                                                    <p class="text-lg font-bold">${formatNumber(totals.BurMtlUniCost)}</p>
                                                    <h5 class="text-md">Burden Material Cost</h5>
                                                </div>
                                            </div>
                                            <div class="bg-gray-700 p-4 rounded-lg flex items-center">
                                                <div class="shrink-0 bg-gray-800 border border-gray-600 rounded-xl w-14 h-14 flex justify-center items-center font-bold text-white text-xl">S</div>
                                                <div class="ltr:ml-4 rtl:mr-4">
                                                    <p class="text-lg font-bold">${formatNumber(totals.SbcCost)}</p>
                                                    <h5 class="text-md">SBC Cost</h5>
                                                </div>
                                            </div>
                                        </div>
                                    `;

                                cardsHTML += `
                                        <h3 class="text-lg font-semibold mb-4">Variances</h3>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4">
                                            <div class="bg-gray-700 p-4 rounded-lg flex items-center">
                                                <div class="shrink-0 bg-gray-800 border border-gray-600 rounded-xl w-14 h-14 flex justify-center items-center font-bold text-white text-xl">VL</div>
                                                <div class="ltr:ml-4 rtl:mr-4">
                                                    <p class="text-lg font-bold">${formatNumber(totals.VarianceAplLabor)}</p>
                                                    <h5 class="text-md">Variance Apl Labor</h5>
                                                </div>
                                            </div>
                                            <div class="bg-gray-700 p-4 rounded-lg flex items-center">
                                                <div class="shrink-0 bg-gray-800 border border-gray-600  rounded-xl w-14 h-14 flex justify-center items-center font-bold text-white text-xl">VM</div>
                                                <div class="ltr:ml-4 rtl:mr-4">
                                                    <p class="text-lg font-bold">${formatNumber(totals.VarianceAplMaterial)}</p>
                                                    <h5 class=" text-md">Variance Apl Material</h5>
                                                </div>
                                            </div>
                                            <div class="bg-gray-700 p-4 rounded-lg flex items-center">
                                                <div class="shrink-0 bg-gray-800 border border-gray-600  rounded-xl w-14 h-14 flex justify-center items-center font-bold text-white text-xl">VB</div>
                                                <div class="ltr:ml-4 rtl:mr-4">
                                                    <p class="text-lg font-bold">${formatNumber(totals.VarianceAplBurden)}</p>
                                                    <h5 class=" text-md">Variance Apl Burden</h5>
                                                </div>
                                            </div>
                                            <div class="bg-gray-700 p-4 rounded-lg flex items-center">
                                                <div class="shrink-0 bg-gray-800 border border-gray-600  rounded-xl w-14 h-14 flex justify-center items-center font-bold text-white text-xl">VMB</div>
                                                <div class="ltr:ml-4 rtl:mr-4">
                                                    <p class="text-lg font-bold">${formatNumber(totals.VarianceMtlBurden)}</p>
                                                    <h5 class=" text-md">Variance Mtl Burden</h5>
                                                </div>
                                            </div>
                                            <div class="bg-gray-700 p-4 rounded-lg flex items-center">
                                                <div class="shrink-0 bg-gray-800 border border-gray-600  rounded-xl w-14 h-14 flex justify-center items-center font-bold text-white text-xl">VSC</div>
                                                <div class="ltr:ml-4 rtl:mr-4">
                                                    <p class="text-lg font-bold">${formatNumber(totals.VarianceSubCont)}</p>
                                                    <h5 class=" text-md">Variance Sub Cont</h5>
                                                </div>
                                            </div>
                                        </div>
                                    `;

                                cardsHTML += `</div>`;

                                document.getElementById('modal-body-content').innerHTML =
                                    cardsHTML;
                            },
                            error: function(error) {}
                        });
                    }
                });

                document.getElementById('closeModal').addEventListener('click', function() {
                    document.getElementById('detail_modal').classList.add('hidden');
                });

                document.getElementById('detail_modal').addEventListener('click', function(event) {
                    if (event.target === this) {
                        this.classList.add('hidden');
                    }
                });

                document.addEventListener('keydown', function(event) {
                    if (event.key === "Escape") {
                        document.getElementById('detail_modal').classList.add('hidden');
                    }
                });

                function formatNumber(num) {
                    if (num === null || num === undefined) return 'Error';
                    const sanitizedNum = String(num).replace(/[^0-9.-]+/g, "");
                    const number = parseFloat(sanitizedNum);
                    if (isNaN(number)) return 'Error';
                    return number.toLocaleString('en-US', {

                    });
                }

                const buttonContainer = document.createElement('div');
                buttonContainer.style.display = 'flex';
                buttonContainer.style.gap = '10px';
                buttonContainer.style.marginTop = '10px';
                buttonContainer.id = 'sales-cost-table_button_container';

                const downloadButton = document.createElement('button');
                downloadButton.id = 'download_sales-cost-table_button';
                downloadButton.className = 'btn btn-primary';
                downloadButton.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down-fill" viewBox="0 0 16 16" style="margin-right: 8px;">
                        <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"/>
                    </svg> Download Data
                `;

                buttonContainer.appendChild(downloadButton);

                const tableWrapper = document.querySelector('#sales-cost-table_wrapper');
                if (tableWrapper) {
                    const existingContainer = document.getElementById('sales-cost-table_button_container');
                    if (existingContainer) existingContainer.remove();
                    tableWrapper.insertAdjacentElement('afterend', buttonContainer);
                } else {
                    console.error('Table wrapper #sales-cost-table_wrapper not found');
                }

                downloadButton.addEventListener('click', function() {
                    downloadPartByMonth();
                });

                setTimeout(function() {
                    detailTablePartByMonth.ajax.reload();
                }, 100);

                return detailTablePartByMonth;
            }

            function setDefaultData() {
                var currentHost = window.location.host;
                var yearX = document.getElementById('start_year').value + '~' + document.getElementById('end_year')
                    .value;
                const apiUrl = `https://${currentHost}/api/finance/get_profit_invoice_yearly/${yearX}`;

                axios.get(apiUrl)
                    .then(response => {
                        var data_year = response.data.data_year;
                        var data_chart_sales = response.data.data_chart_sales;
                        var data_chart_cost = response.data.data_chart_cost;
                        var data_chart_expenses = response.data.data_chart_expenses;
                        var data_chart_profit = response.data.data_chart_profit;
                        document.getElementById('inpt_val_data_year').value = data_year;
                        document.getElementById('inpt_val_sales_chart').value = data_chart_sales;
                        document.getElementById('inpt_val_cost_chart').value = data_chart_cost;
                        document.getElementById('inpt_val_expenses_chart').value = data_chart_expenses;
                        document.getElementById('inpt_val_profit_chart').value = data_chart_profit;
                        document.getElementById('btn_update_chart').click();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });

                var monthX = document.getElementById('current_year').value;
                const apiUrlMonth = `https://${currentHost}/api/finance/get_invoice_profit_monthly/${monthX}`;
                axios.get(apiUrlMonth)
                    .then(response => {
                        var data_chart_sales_month = response.data.data_chart_sales_month;
                        var data_chart_cost_month = response.data.data_chart_cost_month;
                        var data_chart_expenses_month = response.data.data_chart_expenses_month;
                        var data_chart_profit_month = response.data.data_chart_profit_month;

                        document.getElementById('inpt_val_sales_chart_month').value = Array.isArray(
                                data_chart_sales_month) ? data_chart_sales_month.join(',') :
                            data_chart_sales_month;
                        document.getElementById('inpt_val_cost_chart_month').value = Array.isArray(
                            data_chart_cost_month) ? data_chart_cost_month.join(',') : data_chart_cost_month;
                        document.getElementById('inpt_val_expenses_chart_month').value = Array.isArray(
                                data_chart_expenses_month) ? data_chart_expenses_month.join(',') :
                            data_chart_expenses_month;
                        document.getElementById('inpt_val_profit_chart_month').value = Array.isArray(
                                data_chart_profit_month) ? data_chart_profit_month.join(',') :
                            data_chart_profit_month;

                        document.getElementById('btn_update_chart_month').click();
                        $("#current_year").val(value);
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });

                var year_cust = document.getElementById('current_year_customer').value;
                const apiUrlCust = `https://${currentHost}/api/finance/get_profit_invoice_cust_yearly/${year_cust}`;
                axios.get(apiUrlCust)
                    .then(response => {
                        var data_month = response.data.data_month;
                        var data_cust_sales_year = response.data.data_cust_sales_year;
                        var data_cust_cost_year = response.data.data_cust_cost_year;
                        var data_cust_expenses_year = response.data.data_cust_expenses_year;
                        var data_cust_profit_year = response.data.data_cust_profit_year;
                        document.getElementById('inpt_val_data_month').value = data_month;
                        document.getElementById('inpt_val_sales_cust_year').value = data_cust_sales_year;
                        document.getElementById('inpt_val_cost_cust_year').value = data_cust_cost_year;
                        document.getElementById('inpt_val_expenses_cust_year').value = data_cust_expenses_year;
                        document.getElementById('inpt_val_profit_cust_year').value = data_cust_profit_year;

                        document.getElementById('btn_update_cust_year').click();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });

                var month_cust = document.getElementById('current_month_customer').value;
                const apiUrlCustMonth =
                    `https://${currentHost}/api/finance/get_profit_invoice_cust_monthly/${month_cust}`;
                axios.get(apiUrlCustMonth)
                    .then(response => {
                        var data_cust = response.data.data_cust;
                        var data_cust_sales_month = response.data.data_cust_sales_month;
                        var data_cust_cost_month = response.data.data_cust_cost_month;
                        var data_cust_expenses_month = response.data.data_cust_expenses_month;
                        var data_cust_profit_month = response.data.data_cust_profit_month;
                        document.getElementById('inpt_val_data_cust').value = data_cust;
                        document.getElementById('inpt_val_sales_cust_month').value = data_cust_sales_month;
                        document.getElementById('inpt_val_cost_cust_month').value = data_cust_cost_month;
                        document.getElementById('inpt_val_expenses_cust_month').value =
                            data_cust_expenses_month;
                        document.getElementById('inpt_val_profit_cust_month').value = data_cust_profit_month;
                        document.getElementById('btn_update_cust_month').click();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });

                var date_cust = document.getElementById('month_year').value;
                const apiUrlCustDate = `https://${currentHost}/api/finance/get_profit_cust_date/${date_cust}`;
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
            detail_table_profit_model_yearly();
            detail_table_profit_by_year();
            detail_table_profit_by_month();
            detail_table_profi_cust_by_year();
            detail_table_profi_cust_by_month();
            detail_table_profit_model_monthly();
            detail_table_profit_category_yearly();
            detail_table_profit_category_monthly();
            setDefaultDatas();


            document.getElementById('current_year').addEventListener('change', function() {
                var currentHost = window.location.host;
                var year = document.getElementById('current_year').value;
                const apiUrlMonth = `https://${currentHost}/api/finance/get_invoice_profit_monthly/${year}`;
                axios.get(apiUrlMonth)
                    .then(response => {
                        var data_chart_sales_month = response.data.data_chart_sales_month;
                        var data_chart_cost_month = response.data.data_chart_cost_month;
                        var data_chart_expenses_month = response.data.data_chart_expenses_month;
                        var data_chart_profit_month = response.data.data_chart_profit_month;
                        document.getElementById('inpt_val_sales_chart_month').value =
                            data_chart_sales_month;
                        document.getElementById('inpt_val_cost_chart_month').value =
                            data_chart_cost_month;
                        document.getElementById('inpt_val_expenses_chart_month').value =
                            data_chart_expenses_month;
                        document.getElementById('inpt_val_profit_chart_month').value =
                            data_chart_profit_month;

                        document.getElementById('btn_update_chart_month').click();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        alert('Gagal mengambil data untuk grafik Profit By Months.');
                    });
            });

            document.getElementById('current_year_customer').addEventListener('change', function() {
                var currentHost = window.location.host;
                var year_cust = document.getElementById('current_year_customer').value;
                const apiUrlCust =
                    `https://${currentHost}/api/finance/get_profit_invoice_cust_yearly/${year_cust}`;
                axios.get(apiUrlCust)
                    .then(response => {
                        var data_month = response.data.data_month;
                        var data_cust_sales_year = response.data.data_cust_sales_year;
                        var data_cust_cost_year = response.data.data_cust_cost_year;
                        var data_cust_expenses_year = response.data.data_cust_expenses_year;
                        var data_cust_profit_year = response.data.data_cust_profit_year;
                        document.getElementById('inpt_val_data_month').value = data_month;
                        document.getElementById('inpt_val_sales_cust_year').value =
                            data_cust_sales_year;
                        document.getElementById('inpt_val_cost_cust_year').value = data_cust_cost_year;
                        document.getElementById('inpt_val_expenses_cust_year').value =
                            data_cust_expenses_year;
                        document.getElementById('inpt_val_profit_cust_year').value =
                            data_cust_profit_year;
                        document.getElementById('btn_update_cust_year').click();
                        detail_table_profi_cust_by_year();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        alert('Gagal mengambil data untuk grafik Profit Per Customers By Years.');
                    });
            });


            document.getElementById('current_month_customer').addEventListener('change', function() {
                var currentHost = window.location.host;
                var month_cust = document.getElementById('current_month_customer').value;
                const apiUrlCustMonth =
                    `https://${currentHost}/api/finance/get_profit_invoice_cust_monthly/${month_cust}`;
                axios.get(apiUrlCustMonth)
                    .then(response => {

                        var data_cust = response.data.data_cust;
                        var data_cust_sales_month = response.data.data_cust_sales_month;
                        var data_cust_cost_month = response.data.data_cust_cost_month;
                        var data_cust_expenses_month = response.data.data_cust_expenses_month;
                        var data_cust_profit_month = response.data.data_cust_profit_month;
                        document.getElementById('inpt_val_data_cust').value = data_cust;
                        document.getElementById('inpt_val_sales_cust_month').value =
                            data_cust_sales_month;
                        document.getElementById('inpt_val_cost_cust_month').value =
                            data_cust_cost_month;
                        document.getElementById('inpt_val_expenses_cust_month').value =
                            data_cust_expenses_month;
                        document.getElementById('inpt_val_profit_cust_month').value =
                            data_cust_profit_month;
                        document.getElementById('btn_update_cust_month').click();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        alert('Gagal mengambil data untuk grafik Profit Per Customers By Months.');
                    });
            });

            document.getElementById('month_year').addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    var currentHost = window.location.host;
                    var date_cust = document.getElementById('month_year').value;
                    const apiUrlCustDate =
                        `https://${currentHost}/api/finance/get_invoice_profit_cust_date/${date_cust}`;
                    axios.get(apiUrlCustDate)
                        .then(response => {
                            var data_cust_sales_date = response.data.data_cust_sales_date;
                            var data_cust_cost_date = response.data.data_cust_cost_date;
                            var data_cust_date = response.data.data_val_date;
                            document.getElementById('inpt_val_sales_cust_date').value =
                                data_cust_sales_date;
                            document.getElementById('inpt_val_cost_cust_date').value =
                                data_cust_cost_date;
                            document.getElementById('inpt_val_date').value = data_cust_date;
                            document.getElementById('btn_update_cust_date').click();
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                        });
                }
            });

            document.getElementById('month_year_table').addEventListener('change', function(event) {
                refresh_detail_table();
            });

        });


        function updateChartYear() {
            var currentHost = window.location.host;

            const selectYearly = document.getElementById('selectYearly').value;
            const apiUrlYearly = `https://${currentHost}/api/finance/get_profit_model_yearly/${selectYearly}`;
            axios.get(apiUrlYearly)
                .then(response => {
                    var data_chart_sales = response.data.data_chart_sales;
                    var data_chart_cost = response.data.data_chart_cost;
                    var data_chart_expenses = response.data.data_chart_expenses;
                    var data_chart_profit = response.data.data_chart_profit;
                    var data_chart_model = response.data.data_chart_model;
                    document.getElementById('inpt_data_sales').value = data_chart_sales;
                    document.getElementById('inpt_data_cost').value = data_chart_cost;
                    document.getElementById('inpt_data_expenses').value = data_chart_expenses;
                    document.getElementById('inpt_data_profit').value = data_chart_profit;
                    document.getElementById('inpt_data_model').value = data_chart_model;
                    document.getElementById('btn_update_chart_model').click();
                })
                .catch(error => {
                    console.error('Error fetching default data:', error);
                });
        }

        function updateChartMonth() {
            var currentHost = window.location.host;

            const selectMonthly = document.getElementById('selectMonthly').value;
            const apiUrlMonthly = `https://${currentHost}/api/finance/get_profit_model_monthly/${selectMonthly}`;
            axios.get(apiUrlMonthly)
                .then(response => {
                    var data_chart_sales = response.data.data_chart_sales;
                    var data_chart_cost = response.data.data_chart_cost;
                    var data_chart_expenses = response.data.data_chart_expenses;
                    var data_chart_profit = response.data.data_chart_profit;
                    var data_chart_model = response.data.data_chart_model;
                    document.getElementById('inpt_data_sales_month').value = data_chart_sales;
                    document.getElementById('inpt_data_cost_month').value = data_chart_cost;
                    document.getElementById('inpt_data_expenses_month').value = data_chart_expenses;
                    document.getElementById('inpt_data_profit_month').value = data_chart_profit;
                    document.getElementById('inpt_data_model_month').value = data_chart_model;
                    document.getElementById('btn_update_month').click();
                })
                .catch(error => {
                    console.error('Error fetching default data:', error);
                });
        }

        function updateChartYearCat() {
            var currentHost = window.location.host;

            const selectCatYear = document.getElementById('selectDateCategory').value;
            const apiUrlCatYear = `https://${currentHost}/api/finance/get_profit_category_year/${selectCatYear}`;
            axios.get(apiUrlCatYear)
                .then(response => {
                    var data_chart_sales = response.data.data_chart_sales;
                    var data_chart_cost = response.data.data_chart_cost;
                    var data_chart_expenses = response.data.data_chart_expenses;
                    var data_chart_profit = response.data.data_chart_profit;
                    var data_chart_category = response.data.data_chart_category;
                    document.getElementById('inpt_data_sales_category').value = data_chart_sales;
                    document.getElementById('inpt_data_cost_category').value = data_chart_cost;
                    document.getElementById('inpt_data_expenses_category').value = data_chart_expenses;
                    document.getElementById('inpt_data_profit_category').value = data_chart_profit;
                    document.getElementById('inpt_data_category').value = data_chart_category;
                    document.getElementById('btn_update_category').click();
                })
                .catch(error => {
                    console.error('Error fetching default data:', error);
                });
        }

        function updateChartMonthCat() {
            var currentHost = window.location.host;

            const selectCatMonth = document.getElementById('selectMonthCategory').value;
            const apiUrlCatMonth = `https://${currentHost}/api/finance/get_profit_category_month/${selectCatMonth}`;
            axios.get(apiUrlCatMonth)
                .then(response => {
                    var data_chart_sales_month = response.data.data_chart_sales_month;
                    var data_chart_cost_month = response.data.data_chart_cost_month;
                    var data_chart_expenses_month = response.data.data_chart_expenses_month;
                    var data_chart_profit_month = response.data.data_chart_profit_month;
                    var data_chart_category_month = response.data.data_chart_category_month;
                    document.getElementById('inpt_data_sales_category_month').value = data_chart_sales_month;
                    document.getElementById('inpt_data_cost_category_month').value = data_chart_cost_month;
                    document.getElementById('inpt_data_expenses_category_month').value = data_chart_expenses_month;
                    document.getElementById('inpt_data_profit_category_month').value = data_chart_profit_month;
                    document.getElementById('inpt_data_category_month').value = data_chart_category_month;
                    document.getElementById('btn_update_category_month').click();
                })
                .catch(error => {
                    console.error('Error fetching default data:', error);
                });
        }

        document.addEventListener("alpine:init", () => {
            Alpine.data("analytics", () => ({
                data: {
                    analytics: "Initial Data"
                },

                formatNumber(value) {
                    const absVal = Math.abs(value);

                    if (absVal >= 1e12) return (value / 1e12).toFixed(0) + 'T';
                    if (absVal >= 1e9) return (value / 1e9).toFixed(0) + 'M';
                    if (absVal >= 1e6) return (value / 1e6).toFixed(0) + 'J';
                    if (absVal >= 1e3) return (value / 1e3).toFixed(0) + 'K';

                    return value;
                },


                ChartRecall1(dataYear, salesData, costData, expensesData, profitData) {
                    const year = dataYear.split(',').map(Number);
                    const sales = salesData.split(',').map(Number);
                    const cost = costData.split(',').map(Number);
                    const expenses = expensesData.split(',').map(Number);
                    const profit = profitData.split(',').map(Number);

                    this.BoxChart1.updateSeries([{
                            name: 'Sales',
                            type: 'column',
                            data: sales
                        },
                        {
                            name: 'Cost',
                            type: 'column',
                            data: cost
                        },
                        {
                            name: 'Expenses',
                            type: 'column',
                            data: expenses
                        },
                        {
                            name: 'Profit',
                            type: 'line',
                            data: profit
                        }
                    ]);

                    const allData = [...sales, ...cost, ...expenses, ...profit];
                    const minVal = Math.min(...allData);
                    const maxVal = Math.max(...allData);

                    const range = maxVal - minVal || 1;
                    const margin = range * 0.1;

                    const adjustedMin = minVal < 0 ? minVal - margin : Math.max(0, minVal - margin);
                    const adjustedMax = maxVal + margin;

                    this.BoxChart1.updateOptions({
                        xaxis: {
                            categories: year
                        }
                    });
                    this.BoxChart1.updateOptions({
                        chart: {
                            events: {
                                click: function(event, chartContext, config) {
                                    let i = (config && typeof config.dataPointIndex ===
                                            'number') ?
                                        config.dataPointIndex :
                                        -1;

                                    if (i < 0) {
                                        try {
                                            const w = chartContext.w;
                                            const cats = (w.globals.categoryLabels?.length ?
                                                w.globals.categoryLabels :
                                                w.config.xaxis.categories) || [];
                                            if (!cats.length) return;

                                            const gridLeft = w.globals.gridPos?.left ?? 0;
                                            const gridWidth = w.globals.gridWidth ?? 0;
                                            if (!gridWidth) return;

                                            const rect = w.globals.dom.baseEl
                                                .getBoundingClientRect();
                                            const xInSvg = event.clientX - rect.left;
                                            const xInGrid = xInSvg - gridLeft;
                                            if (xInGrid < 0 || xInGrid > gridWidth) return;

                                            const band = gridWidth / cats.length;
                                            i = Math.floor(xInGrid / band);
                                            if (i < 0) i = 0;
                                            if (i >= cats.length) i = cats.length - 1;
                                        } catch (e) {
                                            return;
                                        }
                                    }

                                    const labels = chartContext.w.globals.categoryLabels
                                        ?.length ?
                                        chartContext.w.globals.categoryLabels :
                                        chartContext.w.config.xaxis.categories;
                                    const value = labels?.[i];
                                    if (value == null) return;

                                    var currentHost = window.location.host;

                                    const apiUrlMonth =
                                        `https://${currentHost}/api/finance/get_invoice_profit_monthly/${value}`;
                                    axios.get(apiUrlMonth)
                                        .then(response => {
                                            var data_chart_sales_month = response.data
                                                .data_chart_sales_month;
                                            var data_chart_cost_month = response.data
                                                .data_chart_cost_month;
                                            var data_chart_expenses_month = response
                                                .data.data_chart_expenses_month;
                                            var data_chart_profit_month = response.data
                                                .data_chart_profit_month;
                                            document.getElementById(
                                                    'inpt_val_sales_chart_month')
                                                .value = data_chart_sales_month;
                                            document.getElementById(
                                                    'inpt_val_cost_chart_month').value =
                                                data_chart_cost_month;
                                            document.getElementById(
                                                    'inpt_val_expenses_chart_month')
                                                .value = data_chart_expenses_month;
                                            document.getElementById(
                                                    'inpt_val_profit_chart_month')
                                                .value = data_chart_profit_month;
                                            document.getElementById(
                                                'btn_update_chart_month').click();
                                            $("#current_year").val(value);
                                        })
                                        .catch(error => {
                                            console.error('Error fetching data:',
                                                error);
                                        });

                                    const apiUrlCust =
                                        `https://${currentHost}/api/finance/get_profit_invoice_cust_yearly/${value}`;
                                    axios.get(apiUrlCust)
                                        .then(response => {
                                            var data_month = response.data.data_month;
                                            var data_cust_sales_year = response.data
                                                .data_cust_sales_year;
                                            var data_cust_cost_year = response.data
                                                .data_cust_cost_year;
                                            var data_cust_expenses_year = response.data
                                                .data_cust_expenses_year;
                                            var data_cust_profit_year = response.data
                                                .data_cust_profit_year;
                                            document.getElementById(
                                                    'inpt_val_data_month').value =
                                                data_month;
                                            document.getElementById(
                                                    'inpt_val_sales_cust_year').value =
                                                data_cust_sales_year;
                                            document.getElementById(
                                                    'inpt_val_cost_cust_year').value =
                                                data_cust_cost_year;
                                            document.getElementById(
                                                    'inpt_val_expenses_cust_year')
                                                .value = data_cust_expenses_year;
                                            document.getElementById(
                                                    'inpt_val_profit_cust_year').value =
                                                data_cust_profit_year;
                                            document.getElementById(
                                                'btn_update_cust_year').click();
                                        })
                                        .catch(error => {
                                            console.error('Error fetching data:',
                                                error);
                                        });

                                    $("#current_year_customer").val(value);
                                }
                            }
                        }
                    });


                    this.BoxChart1.updateOptions({
                        yaxis: {
                            labels: {
                                show: true,
                                formatter: (value) => this.formatNumber(value)
                            },
                            min: adjustedMin,
                            max: adjustedMax,
                            tickAmount: 5
                        },
                    });
                },

                ChartRecall2(salesData, costData, expensesData, profitData) {
                    const sales = salesData.split(',').map(Number);
                    const cost = costData.split(',').map(Number);
                    const expenses = expensesData.split(',').map(Number);
                    const profit = profitData.split(',').map(Number);

                    this.BoxChart2.updateSeries([{
                            name: 'Sales',
                            data: sales
                        },
                        {
                            name: 'Cost',
                            data: cost
                        },
                        {
                            name: 'Expenses',
                            data: expenses
                        },
                        {
                            name: 'Profit',
                            data: profit
                        }
                    ]);

                    const allData = [...sales, ...cost, ...expenses, ...profit];
                    const minVal = Math.min(...allData);
                    const maxVal = Math.max(...allData);
                    const allZero = allData.every(val => val === 0);

                    let adjustedMin, adjustedMax;

                    if (maxVal === 0 && minVal === 0) {
                        adjustedMin = 0;
                        adjustedMax = 0;
                    } else {
                        const range = maxVal - minVal || 1;
                        const margin = range * 0.1;

                        adjustedMin = minVal < 0 ? minVal - margin : Math.max(0, minVal - margin);
                        adjustedMax = maxVal + margin;
                    }

                    this.BoxChart2.updateOptions({
                        dataLabels: {
                            enabled: !allZero
                        }
                    });



                    this.BoxChart2.updateOptions({
                        chart: {
                            events: {
                                click: function(event, chartContext, config) {
                                    var value = config.globals.categoryLabels[config
                                        .dataPointIndex];
                                    var monthIndex = '' + (config.dataPointIndex + 1);
                                    if (monthIndex.length === 1) {
                                        monthIndex = '0' + monthIndex;
                                    }
                                    var years = $("#current_year").val();
                                    var postValue = years + "-" + monthIndex;
                                    var currentHost = window.location.host;

                                    const apiUrlCustMonth =
                                        `https://${currentHost}/api/finance/get_profit_invoice_cust_monthly/${postValue}`;
                                    axios.get(apiUrlCustMonth)
                                        .then(response => {
                                            var data_cust = response.data.data_cust;
                                            var data_cust_sales_month = response.data
                                                .data_cust_sales_month;
                                            var data_cust_cost_month = response.data
                                                .data_cust_cost_month;
                                            var data_cust_expenses_month = response.data
                                                .data_cust_expenses_month;
                                            var data_cust_profit_month = response.data
                                                .data_cust_profit_month;
                                            document.getElementById(
                                                    'inpt_val_data_cust').value =
                                                data_cust;
                                            document.getElementById(
                                                    'inpt_val_sales_cust_month').value =
                                                data_cust_sales_month;
                                            document.getElementById(
                                                    'inpt_val_cost_cust_month').value =
                                                data_cust_cost_month;
                                            document.getElementById(
                                                    'inpt_val_expenses_cust_month')
                                                .value =
                                                data_cust_expenses_month;
                                            document.getElementById(
                                                    'inpt_val_profit_cust_month')
                                                .value =
                                                data_cust_profit_month;
                                            document.getElementById(
                                                'btn_update_cust_month').click();
                                        })
                                        .catch(error => {
                                            console.error('Error fetching data:',
                                                error);
                                        });
                                    $("#current_month_customer").val(postValue);

                                    $("#month_year").val(postValue);
                                    const apiUrlCustDate =
                                        `https://${currentHost}/api/finance/get_invoice_profit_cust_date/${postValue}`;
                                    axios.get(apiUrlCustDate)
                                        .then(response => {
                                            var data_cust_sales_date = response.data
                                                .data_cust_sales_date;
                                            var data_cust_cost_date = response.data
                                                .data_cust_cost_date;
                                            var data_cust_date = response.data
                                                .data_val_date;
                                            document.getElementById(
                                                    'inpt_val_sales_cust_date').value =
                                                data_cust_sales_date;
                                            document.getElementById(
                                                    'inpt_val_cost_cust_date').value =
                                                data_cust_cost_date;
                                            document.getElementById('inpt_val_date')
                                                .value = data_cust_date;
                                            document.getElementById(
                                                'btn_update_cust_date').click();
                                        })
                                        .catch(error => {
                                            console.error('Error fetching data:',
                                                error);
                                        });
                                    $("#month_year_table").val(postValue);
                                    $("#month_year_table").change();
                                }
                            },
                        },
                    });
                    this.BoxChart2.updateOptions({
                        yaxis: {
                            labels: {
                                show: true,
                                formatter: (value) => this.formatNumber(value)
                            },
                            min: adjustedMin,
                            max: adjustedMax,
                            tickAmount: 6
                        },
                    });
                },

                ChartRecall3(dataMonth, salesData, costData, expensesData, profitData) {
                    const month = dataMonth.split(',');
                    const sales = salesData.split(',').map(Number);
                    const cost = costData.split(',').map(Number);
                    const expenses = expensesData.split(',').map(Number);
                    const profit = profitData.split(',').map(Number);

                    this.BoxChart3.updateSeries([{
                            name: 'Sales',
                            data: sales
                        },
                        {
                            name: 'Cost',
                            data: cost
                        },
                        {
                            name: 'Expenses',
                            data: expenses
                        },
                        {
                            name: 'Profit',
                            data: profit
                        }
                    ]);

                    const allData = [...sales, ...cost, ...expenses, ...profit];
                    const minVal = Math.min(...allData);
                    const maxVal = Math.max(...allData);
                    const allZero = allData.every(val => val === 0);

                    let adjustedMin, adjustedMax;

                    if (maxVal === 0 && minVal === 0) {
                        adjustedMin = 0;
                        adjustedMax = 0;
                    } else {
                        const range = maxVal - minVal || 1;
                        const margin = range * 0.1;

                        adjustedMin = minVal < 0 ? minVal - margin : Math.max(0, minVal - margin);
                        adjustedMax = maxVal + margin;
                    }

                    this.BoxChart3.updateOptions({
                        yaxis: {
                            labels: {
                                show: true,
                                formatter: (value) => this.formatNumber(value)
                            },
                            min: adjustedMin,
                            max: adjustedMax,
                            tickAmount: 6
                        },
                    });

                    this.BoxChart3.updateOptions({
                        xaxis: {
                            categories: month
                        },
                        dataLabels: {
                            enabled: !allZero
                        }
                    });

                },

                ChartRecall4(dataCust, salesData, costData, expensesData, profitData) {
                    const cust = dataCust.split(',');
                    const sales = salesData.split(',').map(Number);
                    const cost = costData.split(',').map(Number);
                    const expenses = expensesData.split(',').map(Number);
                    const profit = profitData.split(',').map(Number);

                    this.BoxChart4.updateSeries([{
                            name: 'Sales',
                            data: sales
                        },
                        {
                            name: 'Cost',
                            data: cost
                        },
                        {
                            name: 'Expenses',
                            data: expenses
                        },
                        {
                            name: 'Profit',
                            data: profit
                        }
                    ]);

                    const allData = [...sales, ...cost, ...expenses, ...profit];
                    const minVal = Math.min(...allData);
                    const maxVal = Math.max(...allData);
                    const allZero = allData.every(val => val === 0);

                    let adjustedMin, adjustedMax;

                    if (maxVal === 0 && minVal === 0) {
                        adjustedMin = 0;
                        adjustedMax = 0;
                    } else {
                        const range = maxVal - minVal || 1;
                        const margin = range * 0.1;

                        adjustedMin = minVal < 0 ? minVal - margin : Math.max(0, minVal - margin);
                        adjustedMax = maxVal + margin;
                    }

                    this.BoxChart4.updateOptions({
                        yaxis: {
                            labels: {
                                show: true,
                                formatter: (value) => this.formatNumber(value)
                            },
                            min: adjustedMin,
                            max: adjustedMax,
                            tickAmount: 6
                        },
                    });

                    this.BoxChart4.updateOptions({
                        xaxis: {
                            categories: cust
                        },
                        dataLabels: {
                            enabled: !allZero
                        }
                    });
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
                        },
                        {
                            name: 'Cost',
                            data: cost
                        }
                    ]);

                    const allData = [...sales, ...cost];
                    const minVal = Math.min(...allData);
                    const maxVal = Math.max(...allData);

                    const range = maxVal - minVal || 1;
                    const margin = range * 0.1;

                    const adjustedMin = minVal < 0 ? minVal - margin : Math.max(0, minVal - margin);
                    const adjustedMax = maxVal + margin;

                    this.BoxChart5.updateOptions({
                        yaxis: {
                            labels: {
                                show: true,
                                formatter: (value) => this.formatNumber(value)
                            },
                            min: adjustedMin,
                            max: adjustedMax,
                            tickAmount: 6
                        },
                    });
                },
                ChartRecall12(dataSales, dataCost, dataExpenses, dataProfit, dataModel) {
                    const sales = dataSales.split(',').map(Number);
                    const cost = dataCost.split(',').map(Number);
                    const expenses = dataExpenses.split(',').map(Number);
                    const profit = dataProfit.split(',').map(Number);
                    const model = dataModel.split(',');

                    this.BoxChart13.updateSeries([{
                            name: 'Sales',
                            data: sales,
                        },
                        {
                            name: 'Cost',
                            data: cost,
                        },
                        {
                            name: 'Expenses',
                            data: expenses,
                        },
                        {
                            name: 'Profit',
                            data: profit,
                        },
                    ]);

                    const allData = [...sales, ...cost, ...expenses, ...profit];
                    const minVal = Math.min(...allData);
                    const maxVal = Math.max(...allData);
                    const allZero = allData.every(val => val === 0);

                    let adjustedMin, adjustedMax;

                    if (maxVal === 0 && minVal === 0) {
                        adjustedMin = 0;
                        adjustedMax = 0;
                    } else {
                        const range = maxVal - minVal || 1;
                        const margin = range * 0.1;

                        adjustedMin = minVal < 0 ? minVal - margin : Math.max(0, minVal - margin);
                        adjustedMax = maxVal + margin;
                    }

                    this.BoxChart13.updateOptions({
                        yaxis: {
                            labels: {
                                show: true,
                                formatter: (value) => this.formatNumber(value)
                            },
                            min: adjustedMin,
                            max: adjustedMax,
                            tickAmount: 6
                        },
                    });

                    this.BoxChart13.updateOptions({
                        xaxis: {
                            categories: model
                        },
                        dataLabels: {
                            enabled: !allZero
                        }
                    });
                },

                ChartRecall13(dataSales, dataCost, dataExpenses, dataProfit, dataModel) {
                    const sales = dataSales.split(',').map(Number);
                    const cost = dataCost.split(',').map(Number);
                    const expenses = dataExpenses.split(',').map(Number);
                    const profit = dataProfit.split(',').map(Number);
                    const model = dataModel.split(',');

                    this.BoxChart14.updateSeries([{
                            name: 'Sales',
                            data: sales,
                        },
                        {
                            name: 'Cost',
                            data: cost,
                        },
                        {
                            name: 'Expenses',
                            data: expenses,
                        },
                        {
                            name: 'Profit',
                            data: profit,
                        },
                    ]);

                    const allData = [...sales, ...cost, ...expenses, ...profit];
                    const minVal = Math.min(...allData);
                    const maxVal = Math.max(...allData);
                    const allZero = allData.every(val => val === 0);

                    let adjustedMin, adjustedMax;

                    if (maxVal === 0 && minVal === 0) {
                        adjustedMin = 0;
                        adjustedMax = 0;
                    } else {
                        const range = maxVal - minVal || 1;
                        const margin = range * 0.1;

                        adjustedMin = minVal < 0 ? minVal - margin : Math.max(0, minVal - margin);
                        adjustedMax = maxVal + margin;
                    }

                    this.BoxChart14.updateOptions({
                        yaxis: {
                            labels: {
                                show: true,
                                formatter: (value) => this.formatNumber(value)
                            },
                            min: adjustedMin,
                            max: adjustedMax,
                            tickAmount: 6
                        },
                    });

                    this.BoxChart14.updateOptions({
                        xaxis: {
                            categories: model
                        },
                        dataLabels: {
                            enabled: !allZero
                        }
                    });
                },

                ChartRecall14(dataSales, dataCost, dataExpenses, dataProfit, dataCategory) {
                    const sales = dataSales.split(',').map(Number);
                    const cost = dataCost.split(',').map(Number);
                    const expenses = dataExpenses.split(',').map(Number);
                    const profit = dataProfit.split(',').map(Number);
                    const category = dataCategory.split(',');

                    this.BoxChart15.updateSeries([{
                            name: 'Sales',
                            data: sales,
                        },
                        {
                            name: 'Cost',
                            data: cost,
                        },
                        {
                            name: 'Expenses',
                            data: expenses,
                        },
                        {
                            name: 'Profit',
                            data: profit,
                        },
                    ]);

                    this.BoxChart15.updateOptions({
                        xaxis: {
                            categories: category
                        }
                    });
                },

                ChartRecall15(dataSales, dataCost, dataExpenses, dataProfit, dataCategory) {
                    const sales = dataSales.split(',').map(Number);
                    const cost = dataCost.split(',').map(Number);
                    const expenses = dataExpenses.split(',').map(Number);
                    const profit = dataProfit.split(',').map(Number);
                    const category = dataCategory.split(',');

                    sales.push(0);
                    cost.push(0);
                    category.push('');

                    this.BoxChart16.updateSeries([{
                            name: 'Sales',
                            data: sales
                        },
                        {
                            name: 'Cost',
                            data: cost
                        },
                        {
                            name: 'Expenses',
                            data: expenses
                        },
                        {
                            name: 'Profit',
                            data: profit
                        }
                    ]);

                    this.BoxChart16.updateOptions({
                        xaxis: {
                            categories: category
                        }
                    });
                },


                // Render Chart
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

                    this.BoxChart13 = new ApexCharts(this.$refs.BoxChart13, this.BoxChart13Options);
                    this.BoxChart13.render();

                    this.BoxChart14 = new ApexCharts(this.$refs.BoxChart14, this.BoxChart14Options);
                    this.BoxChart14.render();

                    this.BoxChart15 = new ApexCharts(this.$refs.BoxChart15, this.BoxChart15Options);
                    this.BoxChart15.render();

                    this.BoxChart16 = new ApexCharts(this.$refs.BoxChart16, this.BoxChart16Options);
                    this.BoxChart16.render();
                },

                // Opsi Chart
                get BoxChart1Options() {
                    return {
                        series: [],
                        chart: {
                            height: 360,
                            type: 'area',
                            stacked: true,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            enabledOnSeries: [0, 1, 2, 3],
                            formatter: (value) => this.formatNumber(value)
                        },
                        stroke: {
                            width: [0, 0, 0, 3],
                            curve: 'smooth',
                            colors: ['#2196f3', '#f44336', '#EF8742', '#0b6e31']
                        },
                        colors: ['#2196f3', '#f44336', '#EF8742', '#0b6e31'],
                        dropShadow: {
                            enabled: false,
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
                            borderColor: '#89898930',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: [],
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
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
                                type: 'column',
                                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                            },
                            {
                                name: 'Cost',
                                type: 'column',
                                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                            },
                            {
                                name: 'Expenses',
                                type: 'column',
                                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                            },
                            {
                                name: 'Profit',
                                type: 'line',
                                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                            }
                        ],
                        chart: {
                            height: 360,
                            type: 'line',
                            stacked: true,
                            // stackType: "50%",
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
                            width: [0, 0, 0, 3],
                            curve: 'smooth',
                            colors: ['#2196f3', '#f44336', '#EF8742', '#0b6e31'],
                        },
                        colors: ['#2196f3', '#f44336', '#EF8742', '#0b6e31'],
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
                            borderColor: '#89898930',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug',
                                'Sep', 'Oct', 'Nov', 'Dec'
                            ],
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: false
                            }
                        },

                        tooltip: {
                            y: {
                                formatter: (value) => this.formatNumber(value)
                            }
                        },

                    };
                },

                get BoxChart3Options() {
                    return {
                        series: [{
                                name: 'Sales',
                                type: 'column',
                                data: []
                            },
                            {
                                name: 'Cost',
                                type: 'column',
                                data: []
                            },
                            {
                                name: 'Expenses',
                                type: 'column',
                                data: []
                            },
                            {
                                name: 'Profit',
                                type: 'line',
                                data: []
                            }
                        ],
                        chart: {
                            height: 360,
                            type: 'area',
                            stacked: true,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            enabledOnSeries: [3],
                            formatter: (value) => this.formatNumber(value)
                        },
                        stroke: {
                            width: [0, 0, 0, 3],
                            curve: 'smooth',
                            colors: ['#2196f3', '#f44336', '#EF8742', '#0b6e31'],
                        },
                        colors: ['#2196f3', '#f44336', '#EF8742', '#0b6e31'],
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
                            borderColor: '#89898930',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: [],
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
                        },
                        yaxis: {
                            labels: {
                                show: true,
                                formatter: (value) => this.formatNumber(value)
                            },
                            min: -50000000000,
                            tickAmount: 5
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
                                type: 'column',
                                data: []
                            },
                            {
                                name: 'Cost',
                                type: 'column',
                                data: []
                            },
                            {
                                name: 'Expenses',
                                type: 'column',
                                data: []
                            },
                            {
                                name: 'Profit',
                                type: 'line',
                                data: []
                            }
                        ],
                        chart: {
                            height: 360,
                            type: 'area',
                            stacked: true,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            enabledOnSeries: [3],
                            formatter: (value) => this.formatNumber(value)
                        },
                        stroke: {
                            width: [0, 0, 0, 3],
                            curve: 'smooth',
                            colors: ['#2196f3', '#f44336', '#EF8742', '#0b6e31'],
                        },
                        colors: ['#2196f3', '#f44336', '#EF8742', '#0b6e31'],
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
                            borderColor: '#89898930',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: [],
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
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
                            borderColor: '#89898930',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: [],
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
                        },
                        yaxis: {
                            labels: {
                                show: true,
                                formatter: (value) => this.formatNumber(value)
                            },
                            tickAmount: 5
                        },
                        annotations: {
                            yaxis: [{
                                y: 0,
                                borderColor: '#999',
                                strokeDashArray: 5,
                                label: {
                                    show: true,
                                    text: '0',
                                    style: {
                                        color: '#fff',
                                        background: '#777'
                                    }
                                }
                            }]
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
                get BoxChart13Options() {
                    return {
                        series: [{
                                name: 'Sales',
                                type: 'column',
                                data: []
                            },
                            {
                                name: 'Cost',
                                type: 'column',
                                data: []
                            },
                            {
                                name: 'Expenses',
                                type: 'column',
                                data: []
                            },
                            {
                                name: 'Profit',
                                type: 'line',
                                data: []
                            }
                        ],
                        chart: {
                            height: 360,
                            type: 'area',
                            stacked: true,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            enabledOnSeries: [3],
                            formatter: (value) => this.formatNumber(value)
                        },
                        stroke: {
                            width: [0, 0, 0, 3],
                            curve: 'smooth',
                            colors: ['#2196f3', '#f44336', '#EF8742', '#0b6e31'],
                        },
                        colors: ['#2196f3', '#f44336', '#EF8742', '#0b6e31'],
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
                            borderColor: '#89898930',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: [],
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
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

                get BoxChart14Options() {
                    return {
                        series: [{
                                name: 'Sales',
                                type: 'column',
                                data: []
                            },
                            {
                                name: 'Cost',
                                type: 'column',
                                data: []
                            },
                            {
                                name: 'Expenses',
                                type: 'column',
                                data: []
                            },
                            {
                                name: 'Profit',
                                type: 'line',
                                data: []
                            }
                        ],
                        chart: {
                            height: 360,
                            type: 'area',
                            stacked: true,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            enabledOnSeries: [3],
                            formatter: (value) => this.formatNumber(value)
                        },
                        stroke: {
                            width: [0, 0, 0, 3],
                            curve: 'smooth',
                            colors: ['#2196f3', '#f44336', '#EF8742', '#0b6e31'],
                        },
                        colors: ['#2196f3', '#f44336', '#EF8742', '#0b6e31'],
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
                            borderColor: '#89898930',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: [],
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
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

                get BoxChart15Options() {
                    return {
                        series: [{
                                name: 'Sales',
                                type: 'column',
                                data: []
                            },
                            {
                                name: 'Cost',
                                type: 'column',
                                data: []
                            },
                            {
                                name: 'Expenses',
                                type: 'column',
                                data: []
                            },
                            {
                                name: 'Profit',
                                type: 'column',
                                data: []
                            }
                        ],
                        chart: {
                            height: 360,
                            type: 'area',
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            enabledOnSeries: [3],
                            formatter: (value) => this.formatNumber(value)
                        },
                        stroke: {
                            width: [0, 0, 0, 0],
                            curve: 'smooth',
                            colors: ['#2196f3', '#f44336', '#EF8742', '#0b6e31'],
                        },
                        colors: ['#2196f3', '#f44336', '#EF8742', '#0b6e31'],
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
                            borderColor: '#89898930',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: [],
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
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

                get BoxChart16Options() {
                    return {
                        series: [{
                                name: 'Sales',
                                type: 'column',
                                data: []
                            },
                            {
                                name: 'Cost',
                                type: 'column',
                                data: []
                            },
                            {
                                name: 'Expenses',
                                type: 'column',
                                data: []
                            },
                            {
                                name: 'Profit',
                                type: 'column',
                                data: []
                            }
                        ],
                        chart: {
                            height: 360,
                            type: 'area',
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            enabledOnSeries: [3],
                            formatter: (value) => this.formatNumber(value)
                        },
                        stroke: {
                            width: [0, 0, 0, 0],
                            curve: 'smooth',
                            colors: ['#2196f3', '#f44336', '#EF8742', '#0b6e31'],
                        },
                        colors: ['#2196f3', '#f44336', '#EF8742', '#0b6e31'],
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
                            borderColor: '#89898930',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: [],
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
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

        function detail_table_profit_model_yearly() {
            var selectYear = $('#selectYearly').val();
            detailTableProfitYear = $("#profit_model_year_table").DataTable({
                destroy: true,
                scrollX: true,
                processing: true,
                serverSide: true,
                responsive:true,
                deferLoading: 57,

                language: {
                    processing: '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
                },
                info: false,
                order: [],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],
                ajax: {
                    url: `https://${window.location.host}/api/finance/get_profit_model_by_yearly_table`,
                    type: 'POST',
                    contentType: "application/json",
                    data: function(d) {
                        return JSON.stringify({
                            ...d,
                            date: selectYear,
                        });
                    },
                    cache: false,
                    dataType: 'json',
                    error: function(xhr, error, thrown) {
                        console.error('AJAX Error (detail_table_profit_model_yearly):', error, thrown, xhr
                            .responseText);
                    }
                },
                columns: [{
                        data: 'Years',
                        width: '130px'
                    },
                    {
                        data: 'ModelName',
                        width: '100px'
                    },
                    {
                        data: 'Sales',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Cost',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Expenses',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Profit',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Loss',

                    }
                ],
                createdRow: function(row, data, dataIndex) {


                    ['Loss'].forEach(function(column) {
                        var cell = $('td', row).eq(getColumnIndex(column));

                        if (data[column] === 'Profit') {
                            cell.html('<span class="badge badge-outline-success">Profit</span>');
                        } else if (data[column] === 'Loss') {
                            cell.html('<span class="badge badge-outline-danger">Loss</span>');
                        }
                    });
                },
            });

            const buttonContainer = document.createElement('div');
            buttonContainer.style.display = 'flex';
            buttonContainer.style.gap = '10px';
            buttonContainer.style.marginTop = '10px';
            buttonContainer.id = 'profit_model_year_table_button_container';

            const downloadButton = document.createElement('button');
            downloadButton.id = 'download_profit_model_year_table_button';
            downloadButton.className = 'btn btn-primary';
            downloadButton.innerHTML = `
            <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down-fill" viewBox="0 0 16 16" style="margin-right: 8px;">
                <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"/>
            </svg> Download Data
        `;

            buttonContainer.appendChild(downloadButton);

            const tableWrapper = document.querySelector('#profit_model_year_table_wrapper');
            if (tableWrapper) {
                const existingContainer = document.getElementById('profit_model_year_table_button_container');
                if (existingContainer) existingContainer.remove();
                tableWrapper.insertAdjacentElement('afterend', buttonContainer);
            } else {
                console.error('Table wrapper #profit_model_year_table_wrapper not found');
            }

            downloadButton.addEventListener('click', function() {
                downloadProfitModelYearData();
            });

            setTimeout(function() {
                detailTableProfitYear.ajax.reload();
            }, 100);

            return detailTableProfitYear;
        }

        function detail_table_profit_by_year() {
            var selectYear = $('#current_year').val();

            detailTableProfitByYears = $("#profit_by_year_table").DataTable({
                destroy: true,
                scrollX: true,
                processing: true,
                serverSide: true,
                responsive:true,
                language: {
                    processing: '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
                },
                info: false,
                order: [],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],
                ajax: {
                    url: `https://${window.location.host}/api/finance/get_profit_by_year_table`,
                    type: 'POST',
                    contentType: "application/json",
                    data: function(d) {
                        return JSON.stringify({});
                    },
                    cache: false,
                    dataType: 'json',
                    error: function(xhr, error, thrown) {
                        console.error('AJAX Error (detail_table_profit_by_year):', error, thrown, xhr
                            .responseText);
                    }
                },
                columns: [{
                        data: 'Years',
                        width: '60px',
                    },

                    {
                        data: 'Sales',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Cost',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Expenses',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Profit',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                ],
                createdRow: function(row, data, dataIndex) {


                    ['Loss'].forEach(function(column) {
                        var cell = $('td', row).eq(getColumnIndex(column));

                        if (data[column] === 'Profit') {
                            cell.html('<span class="badge badge-outline-success">Profit</span>');
                        } else if (data[column] === 'Loss') {
                            cell.html('<span class="badge badge-outline-danger">Loss</span>');
                        }
                    });
                },
            });

            const buttonContainer = document.createElement('div');
            buttonContainer.style.display = 'flex';
            buttonContainer.style.gap = '10px';
            buttonContainer.style.marginTop = '10px';
            buttonContainer.id = 'profit_by_year_table_button_container';

            const downloadButton = document.createElement('button');
            downloadButton.id = 'download_profit_by_year_table_button';
            downloadButton.className = 'btn btn-primary';
            downloadButton.innerHTML = `
            <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down-fill" viewBox="0 0 16 16" style="margin-right: 8px;">
                <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"/>
            </svg> Download Data
        `;

            buttonContainer.appendChild(downloadButton);

            const tableWrapper = document.querySelector('#profit_by_year_table_wrapper');
            if (tableWrapper) {
                const existingContainer = document.getElementById('profit_by_year_table_button_container');
                if (existingContainer) existingContainer.remove();
                tableWrapper.insertAdjacentElement('afterend', buttonContainer);
            } else {
                console.error('Table wrapper #profit_by_year_table_wrapper not found');
            }

            downloadButton.addEventListener('click', function() {
                downloadProfitYearData();
            });

            setTimeout(function() {
                detailTableProfitByYears.ajax.reload();
            }, 100);

            return detailTableProfitByYears;
        }

        function detail_table_profit_by_month() {
            var selectYear = $('#current_year').val();

            detailTableProfitByMonths = $("#profit_by_month_table").DataTable({
                destroy: true,
                scrollX: true,
                processing: true,
                serverSide: true,
                responsive:true,
                language: {
                    processing: '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
                },
                info: false,
                order: [],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],
                ajax: {
                    url: `https://${window.location.host}/api/finance/get_profit_by_month_table`,
                    type: 'POST',
                    contentType: "application/json",
                    data: function(d) {
                        return JSON.stringify({
                            ...d,
                            date: selectYear,
                        });
                    },
                    cache: false,
                    dataType: 'json',
                    error: function(xhr, error, thrown) {
                        console.error('AJAX Error (detail_table_profit_by_month):', error, thrown, xhr
                            .responseText);
                    }
                },
                columns: [{
                        data: 'Years',
                        width: '60px'
                    },
                    {
                        data: 'Months',
                        width: '70px'
                    },

                    {
                        data: 'Sales',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Cost',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Expenses',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Profit',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                ],
                createdRow: function(row, data, dataIndex) {


                    ['Loss'].forEach(function(column) {
                        var cell = $('td', row).eq(getColumnIndex(column));

                        if (data[column] === 'Profit') {
                            cell.html('<span class="badge badge-outline-success">Profit</span>');
                        } else if (data[column] === 'Loss') {
                            cell.html('<span class="badge badge-outline-danger">Loss</span>');
                        }
                    });
                },
            });

            const buttonContainer = document.createElement('div');
            buttonContainer.style.display = 'flex';
            buttonContainer.style.gap = '10px';
            buttonContainer.style.marginTop = '10px';
            buttonContainer.id = 'profit_by_month_table_button_container';

            const downloadButton = document.createElement('button');
            downloadButton.id = 'download_profit_by_month_table_button';
            downloadButton.className = 'btn btn-primary';
            downloadButton.innerHTML = `
            <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down-fill" viewBox="0 0 16 16" style="margin-right: 8px;">
                <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"/>
            </svg> Download Data
        `;

            buttonContainer.appendChild(downloadButton);

            const tableWrapper = document.querySelector('#profit_by_month_table_wrapper');
            if (tableWrapper) {
                const existingContainer = document.getElementById('profit_by_month_table_button_container');
                if (existingContainer) existingContainer.remove();
                tableWrapper.insertAdjacentElement('afterend', buttonContainer);
            } else {
                console.error('Table wrapper #profit_by_month_table_wrapper not found');
            }

            downloadButton.addEventListener('click', function() {
                downloadProfitMonthData();
            });

            setTimeout(function() {
                detailTableProfitByMonths.ajax.reload();
            }, 100);

            return detailTableProfitByMonths;
        }

        function detail_table_profi_cust_by_year() {
            var selectYear = $('#current_year_customer').val();

            detailTableProfitCustByYear = $("#profit_cust_by_year_table").DataTable({
                destroy: true,
                scrollX: true,
                processing: true,
                serverSide: true,
                responsive:true,
                language: {
                    processing: '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
                },
                info: false,
                order: [],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],
                ajax: {
                    url: `https://${window.location.host}/api/finance/profit_invoice_cust_yearly_table`,
                    type: 'POST',
                    contentType: "application/json",
                    data: function(d) {
                        return JSON.stringify({
                            ...d,
                            date: selectYear,
                        });
                    },
                    cache: false,
                    dataType: 'json',
                    error: function(xhr, error, thrown) {
                        console.error('AJAX Error (detail_table_profi_cust_by_year):', error, thrown, xhr
                            .responseText);
                    }
                },
                columns: [{
                        data: 'Years',
                        width: '60px'
                    },
                    {
                        data: 'CustNum',
                        width: '70px'
                    },

                    {
                        data: 'Sales',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Cost',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Expenses',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Profit',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                ],
                createdRow: function(row, data, dataIndex) {


                    ['Loss'].forEach(function(column) {
                        var cell = $('td', row).eq(getColumnIndex(column));

                        if (data[column] === 'Profit') {
                            cell.html('<span class="badge badge-outline-success">Profit</span>');
                        } else if (data[column] === 'Loss') {
                            cell.html('<span class="badge badge-outline-danger">Loss</span>');
                        }
                    });
                },
            });

            const buttonContainer = document.createElement('div');
            buttonContainer.style.display = 'flex';
            buttonContainer.style.gap = '10px';
            buttonContainer.style.marginTop = '10px';
            buttonContainer.id = 'profit_cust_by_year_table_button_container';

            const downloadButton = document.createElement('button');
            downloadButton.id = 'download_profit_cust_by_year_table_button';
            downloadButton.className = 'btn btn-primary';
            downloadButton.innerHTML = `
            <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down-fill" viewBox="0 0 16 16" style="margin-right: 8px;">
                <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"/>
            </svg> Download Data
        `;

            buttonContainer.appendChild(downloadButton);

            const tableWrapper = document.querySelector('#profit_cust_by_year_table_wrapper');
            if (tableWrapper) {
                const existingContainer = document.getElementById('profit_cust_by_year_table_button_container');
                if (existingContainer) existingContainer.remove();
                tableWrapper.insertAdjacentElement('afterend', buttonContainer);
            } else {
                console.error('Table wrapper #profit_cust_by_year_table_wrapper not found');
            }

            downloadButton.addEventListener('click', function() {
                downloadProfitCustYearData();
            });

            setTimeout(function() {
                detailTableProfitCustByYear.ajax.reload();
            }, 100);

            return detailTableProfitCustByYear;
        }

        function detail_table_profi_cust_by_month() {
            var selectYear = $('#current_month_customer').val();

            detailTableProfitCustByMonth = $("#profit_cust_by_month_table").DataTable({
                destroy: true,
                scrollX: true,
                processing: true,
                serverSide: true,
                responsive:true,
                language: {
                    processing: '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
                },
                info: false,
                order: [],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],
                ajax: {
                    url: `https://${window.location.host}/api/finance/profit_invoice_cust_month_table`,
                    type: 'POST',
                    contentType: "application/json",
                    data: function(d) {
                        return JSON.stringify({
                            ...d,
                            date: selectYear,
                        });
                    },
                    cache: false,
                    dataType: 'json',
                    error: function(xhr, error, thrown) {
                        console.error('AJAX Error (detail_table_profi_cust_by_month):', error, thrown, xhr
                            .responseText);
                    }
                },
                columns: [{
                        data: 'Years',
                        width: '60px'
                    },
                    {
                        data: 'Months',
                        width: '70px'
                    },
                    {
                        data: 'CustNum',
                        width: '70px'
                    },

                    {
                        data: 'Sales',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Cost',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Expenses',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Profit',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                ],
                createdRow: function(row, data, dataIndex) {


                    ['Loss'].forEach(function(column) {
                        var cell = $('td', row).eq(getColumnIndex(column));

                        if (data[column] === 'Profit') {
                            cell.html('<span class="badge badge-outline-success">Profit</span>');
                        } else if (data[column] === 'Loss') {
                            cell.html('<span class="badge badge-outline-danger">Loss</span>');
                        }
                    });
                },
            });

            const buttonContainer = document.createElement('div');
            buttonContainer.style.display = 'flex';
            buttonContainer.style.gap = '10px';
            buttonContainer.style.marginTop = '10px';
            buttonContainer.id = 'profit_cust_by_month_table_button_container';

            const downloadButton = document.createElement('button');
            downloadButton.id = 'download_profit_cust_by_month_table_button';
            downloadButton.className = 'btn btn-primary';
            downloadButton.innerHTML = `
            <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down-fill" viewBox="0 0 16 16" style="margin-right: 8px;">
                <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"/>
            </svg> Download Data
        `;

            buttonContainer.appendChild(downloadButton);

            const tableWrapper = document.querySelector('#profit_cust_by_month_table_wrapper');
            if (tableWrapper) {
                const existingContainer = document.getElementById('profit_cust_by_month_table_button_container');
                if (existingContainer) existingContainer.remove();
                tableWrapper.insertAdjacentElement('afterend', buttonContainer);
            } else {
                console.error('Table wrapper #profit_cust_by_month_table_wrapper not found');
            }

            downloadButton.addEventListener('click', function() {
                downloadProfitCustMonthData();
            });

            setTimeout(function() {
                detailTableProfitCustByMonth.ajax.reload();
            }, 100);

            return detailTableProfitCustByMonth;
        }

        function detail_table_profit_category_yearly() {
            var selectYear = $('#selectDateCategory').val();
            detailTableCategoryProfitYear = $("#profit_category_year_table").DataTable({
                destroy: true,
                scrollX: true,
                processing: true,
                serverSide: true,
                responsive:true,
                language: {
                    processing: '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
                },
                info: false,
                order: [],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],
                ajax: {
                    url: `https://${window.location.host}/api/finance/get_profit_category_by_yearly_table`,
                    type: 'POST',
                    contentType: "application/json",
                    data: function(d) {
                        return JSON.stringify({
                            ...d,
                            date: selectYear,
                        });
                    },
                    cache: false,
                    dataType: 'json',
                    error: function(xhr, error, thrown) {
                        console.error('AJAX Error (detail_table_profit_category_yearly):', error, thrown, xhr
                            .responseText);
                    }
                },
                columns: [{
                        data: 'Years',
                        width: '100px'
                    },
                    {
                        data: 'Category',
                        width: '150px',


                    },
                    {
                        data: 'Sales',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }


                    },
                    {
                        data: 'Cost',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }


                    },
                    {
                        data: 'TotalExpenses',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }


                    },
                    {
                        data: 'Profit',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }


                    },
                ],
                createdRow: function(row, data, dataIndex) {


                    ['Loss'].forEach(function(column) {
                        var cell = $('td', row).eq(getColumnIndex(column));

                        if (data[column] === 'Profit') {
                            cell.html('<span class="badge badge-outline-success">Profit</span>');
                        } else if (data[column] === 'Loss') {
                            cell.html('<span class="badge badge-outline-danger">Loss</span>');
                        }
                    });
                },
            });

            const buttonContainer = document.createElement('div');
            buttonContainer.style.display = 'flex';
            buttonContainer.style.gap = '10px';
            buttonContainer.style.marginTop = '10px';
            buttonContainer.id = 'profit_category_year_table_button_container';

            const downloadButton = document.createElement('button');
            downloadButton.id = 'download_profit_category_year_table_button';
            downloadButton.className = 'btn btn-primary';
            downloadButton.innerHTML = `
            <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down-fill" viewBox="0 0 16 16" style="margin-right: 8px;">
                <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"/>
            </svg> Download Data
        `;

            buttonContainer.appendChild(downloadButton);

            const tableWrapper = document.querySelector('#profit_category_year_table_wrapper');
            if (tableWrapper) {
                const existingContainer = document.getElementById('profit_category_year_table_button_container');
                if (existingContainer) existingContainer.remove();
                tableWrapper.insertAdjacentElement('afterend', buttonContainer);
            } else {
                console.error('Table wrapper #profit_category_year_table_wrapper not found');
            }

            downloadButton.addEventListener('click', function() {
                downloadProfitCategoryYearData();
            });

            setTimeout(function() {
                detailTableProfitYear.ajax.reload();
            }, 100);

            return detailTableCategoryProfitYear;
        }

        function detail_table_profit_category_monthly() {
            var selectYear = $('#selectMonthCategory').val();
            detailTableCategoryProfitMonth = $("#profit_category_month_table").DataTable({
                destroy: true,
                scrollX: true,
                processing: true,
                serverSide: true,
                responsive:true,
                language: {
                    processing: '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
                },
                info: false,
                order: [],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],
                ajax: {
                    url: `https://${window.location.host}/api/finance/get_profit_category_by_monthly_table`,
                    type: 'POST',
                    contentType: "application/json",
                    data: function(d) {
                        return JSON.stringify({
                            ...d,
                            date: selectYear,
                        });
                    },
                    cache: false,
                    dataType: 'json',
                    error: function(xhr, error, thrown) {
                        console.error('AJAX Error (detail_table_profit_category_monthly):', error, thrown, xhr
                            .responseText);
                    }
                },
                columns: [{
                        data: 'Years',
                        width: '80px'
                    },
                    {
                        data: 'Months',
                        width: '80px'
                    },
                    {
                        data: 'Category',
                        width: '150px',


                    },
                    {
                        data: 'Sales',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }


                    },
                    {
                        data: 'Cost',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Expenses',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Profit',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }


                    },
                    {
                        data: 'Status',
                    },
                ],
                createdRow: function(row, data, dataIndex) {


                    ['Status'].forEach(function(column) {
                        var cell = $('td', row).eq(getColumnIndex(column));

                        if (data[column] === 'Profit') {
                            cell.html('<span class="badge badge-outline-success">Profit</span>');
                        } else if (data[column] === 'Loss') {
                            cell.html('<span class="badge badge-outline-danger">Loss</span>');
                        }
                    });
                },
            });

            const buttonContainer = document.createElement('div');
            buttonContainer.style.display = 'flex';
            buttonContainer.style.gap = '10px';
            buttonContainer.style.marginTop = '10px';
            buttonContainer.id = 'profit_category_month_table_button_container';

            const downloadButton = document.createElement('button');
            downloadButton.id = 'download_profit_category_month_table_button';
            downloadButton.className = 'btn btn-primary';
            downloadButton.innerHTML = `
            <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down-fill" viewBox="0 0 16 16" style="margin-right: 8px;">
                <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"/>
            </svg> Download Data
        `;

            buttonContainer.appendChild(downloadButton);

            const tableWrapper = document.querySelector('#profit_category_month_table_wrapper');
            if (tableWrapper) {
                const existingContainer = document.getElementById('profit_category_month_table_button_container');
                if (existingContainer) existingContainer.remove();
                tableWrapper.insertAdjacentElement('afterend', buttonContainer);
            } else {
                console.error('Table wrapper #profit_category_month_table_wrapper not found');
            }

            downloadButton.addEventListener('click', function() {
                downloadProfitCategoryMonthData();
            });

            setTimeout(function() {
                detailTableCategoryProfitMonth.ajax.reload();
            }, 100);

            return detailTableCategoryProfitMonth;
        }

        function detail_table_profit_model_monthly() {
            var selectMonth = $('#selectMonthly').val();
            detailTableProfitMonth = $("#profit_model_month_table").DataTable({
                destroy: true,
                scrollX: true,
                processing: true,
                serverSide: true,
                responsive:true,
                deferLoading: 57,
                language: {
                    processing: '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
                },
                info: false,
                order: [],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],
                ajax: {
                    url: `https://${window.location.host}/api/finance/get_profit_model_by_monthly_table`,
                    type: 'POST',
                    contentType: "application/json",
                    data: function(d) {
                        return JSON.stringify({
                            ...d,
                            date: selectMonth,
                        });
                    },
                    cache: false,
                    dataType: 'json',
                    error: function(xhr, error, thrown) {
                        console.error('AJAX Error (detail_table_profit_model_monthly):', error, thrown, xhr
                            .responseText);
                    }
                },
                columns: [{
                        data: 'Years',
                        width: '130px'
                    },
                    {
                        data: 'Months',
                        width: '130px'
                    },
                    {
                        data: 'ModelName',
                        width: '100px'
                    },
                    {
                        data: 'Sales',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Cost',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Expenses',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Profit',
                        width: '150px',
                        render: function(data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Loss',

                    }
                ],
                createdRow: function(row, data, dataIndex) {


                    ['Loss'].forEach(function(column) {
                        var cell = $('td', row).eq(getColumnIndex(column));

                        if (data[column] === 'Profit') {
                            cell.html('<span class="badge badge-outline-success">Profit</span>');
                        } else if (data[column] === 'Loss') {
                            cell.html('<span class="badge badge-outline-danger">Loss</span>');
                        }

                    });
                },
            });

            const buttonContainer = document.createElement('div');
            buttonContainer.style.display = 'flex';
            buttonContainer.style.gap = '10px';
            buttonContainer.style.marginTop = '10px';
            buttonContainer.id = 'profit_model_month_table_button_container';

            const downloadButton = document.createElement('button');
            downloadButton.id = 'download_profit_model_month_table_button';
            downloadButton.className = 'btn btn-primary';
            downloadButton.innerHTML = `
            <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down-fill" viewBox="0 0 16 16" style="margin-right: 8px;">
                <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"/>
            </svg> Download Data
        `;

            buttonContainer.appendChild(downloadButton);

            const tableWrapper = document.querySelector('#profit_model_month_table_wrapper');
            if (tableWrapper) {
                const existingContainer = document.getElementById('profit_model_month_table_button_container');
                if (existingContainer) existingContainer.remove();
                tableWrapper.insertAdjacentElement('afterend', buttonContainer);
            } else {
                console.error('Table wrapper #profit_model_month_table_wrapper not found');
            }

            downloadButton.addEventListener('click', function() {
                downloadProfitModelMonthData();
            });

            setTimeout(function() {
                detailTableProfitMonth.ajax.reload();
            }, 100);

            return detailTableProfitMonth;
        }

        function getColumnIndex(columnName) {
            var columns = [];
            return columns.indexOf(columnName);
        }

        function createDateRangePopup(tableType) {
            const existingPopup = document.getElementById('dateRangePopup');
            if (existingPopup) {
                existingPopup.remove();
            }

            const overlay = document.createElement('div');
            overlay.id = 'dateRangePopup';
            overlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            overlay.style.zIndex = '9999';

            const popup = document.createElement('div');
            popup.className = 'bg-white rounded-lg shadow-xl p-6 w-96 max-w-md mx-4';

            popup.innerHTML = `
            <div class="flex items-center mb-6">
                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Export Data Range</h3>
                    <p class="text-sm text-gray-500">Select the time period for your data export</p>
                </div>
            </div>
            <div class="space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-2">From Date</label>
                        <input type="date" id="popupStartDate" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-700 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-2">To Date</label>
                        <input type="date" id="popupEndDate" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-700 font-medium">
                    </div>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12</path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-800">Export Information</p>
                            <p class="text-xs text-blue-600 mt-1">Data will be exported in Excel format (.xlsx) and may take a few moments depending on the date range selected.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200">
                <div class="text-xs text-gray-500">
                    <span class="inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Secure export
                    </span>
                </div>
                <div class="flex space-x-3">
                    <button id="popupCancelBtn" class="px-6 py-2.5 text-gray-700 bg-white border-2 border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 font-medium">
                        Cancel
                    </button>
                    <button id="popupDownloadBtn" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export Data
                        </span>
                    </button>
                </div>
            </div>
        `;

            overlay.appendChild(popup);
            document.body.appendChild(overlay);

            const todayDate = new Date();
            const oneWeekAgo = new Date(todayDate.getTime() - 7 * 24 * 60 * 60 * 1000);

            document.getElementById('popupStartDate').value = oneWeekAgo.toISOString().split('T')[0];
            document.getElementById('popupEndDate').value = todayDate.toISOString().split('T')[0];

            document.getElementById('popupCancelBtn').addEventListener('click', function() {
                overlay.remove();
            });

            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    overlay.remove();
                }
            });

            document.getElementById('popupDownloadBtn').addEventListener('click', function() {
                const startDate = document.getElementById('popupStartDate').value;
                const endDate = document.getElementById('popupEndDate').value;

                if (!startDate || !endDate) {
                    alert('Please select both start and end dates.');
                    return;
                }

                if (new Date(startDate) > new Date(endDate)) {
                    alert('Start date cannot be after end date.');
                    return;
                }
                overlay.remove();
            });
        }


        function downloadProfitYearData() {

            const loadingDiv = document.createElement('div');
            loadingDiv.id = 'loadingOverlay';
            loadingDiv.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center';
            loadingDiv.style.zIndex = '10000';
            loadingDiv.innerHTML = `
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-sm mx-auto border border-gray-100">
            <div class="flex flex-col items-center space-y-4">
                <div class="relative">
                    <div class="w-12 h-12 border-4 border-gray-200 rounded-full animate-spin border-t-blue-600"></div>
                    <div class="absolute inset-0 w-12 h-12 border-4 border-transparent rounded-full animate-pulse border-t-blue-400 opacity-75"></div>
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-800 mb-1">Downloading Data</h3>
                    <p class="text-sm text-gray-500">Please wait while we prepare your file...</p>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div class="bg-blue-600 h-1.5 rounded-full animate-pulse" style="width: 60%"></div>
                </div>
            </div>
        </div>
    `;
            document.body.appendChild(loadingDiv);

            const apiUrl = `https://${window.location.host}/api/finance/get_profit_by_year_table_export`;
            const postData = {

            };

            axios.post(apiUrl, postData)
                .then(response => {
                    console.log('API Response (profit_by_year_table):', response);
                    const tableData = response.data.data;

                    if (tableData && tableData.length > 0) {
                        const excelContent = convertToExcel(tableData);
                        const filename = `profit_by_year_table.xlsx`;
                        downloadExcel(excelContent, filename);
                    } else {
                        alert('No data available for the selected date.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching data (profit_by_year_table):', error);
                    console.error('Error details:', error.response);
                    alert('Failed to fetch data for download.');
                })
                .finally(() => {
                    const loading = document.getElementById('loadingOverlay');
                    if (loading) {
                        loading.remove();
                    }
                });
        }

        function downloadProfitMonthData() {
            var date = $('#current_year').val();

            const loadingDiv = document.createElement('div');
            loadingDiv.id = 'loadingOverlay';
            loadingDiv.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center';
            loadingDiv.style.zIndex = '10000';
            loadingDiv.innerHTML = `
                <div class="bg-white rounded-xl shadow-2xl p-8 max-w-sm mx-auto border border-gray-100">
                    <div class="flex flex-col items-center space-y-4">
                        <div class="relative">
                            <div class="w-12 h-12 border-4 border-gray-200 rounded-full animate-spin border-t-blue-600"></div>
                            <div class="absolute inset-0 w-12 h-12 border-4 border-transparent rounded-full animate-pulse border-t-blue-400 opacity-75"></div>
                        </div>
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-gray-800 mb-1">Downloading Data</h3>
                            <p class="text-sm text-gray-500">Please wait while we prepare your file...</p>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-blue-600 h-1.5 rounded-full animate-pulse" style="width: 60%"></div>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(loadingDiv);

            const apiUrl = `https://${window.location.host}/api/finance/get_profit_by_month_table_export`;
            const postData = {
                date: date
            };

            axios.post(apiUrl, postData)
                .then(response => {
                    console.log('API Response (profit_by_month_table):', response);
                    const tableData = response.data.data;

                    if (tableData && tableData.length > 0) {
                        const excelContent = convertToExcel(tableData);
                        const filename = `profit_by_month_table.xlsx`;
                        downloadExcel(excelContent, filename);
                    } else {
                        alert('No data available for the selected date.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching data (profit_by_month_table):', error);
                    console.error('Error details:', error.response);
                    alert('Failed to fetch data for download.');
                })
                .finally(() => {
                    const loading = document.getElementById('loadingOverlay');
                    if (loading) {
                        loading.remove();
                    }
                });
        }


        function downloadProfitModelYearData() {
            var date = $('#selectYearly').val();

            const loadingDiv = document.createElement('div');
            loadingDiv.id = 'loadingOverlay';
            loadingDiv.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center';
            loadingDiv.style.zIndex = '10000';
            loadingDiv.innerHTML = `
            <div class="bg-white rounded-xl shadow-2xl p-8 max-w-sm mx-auto border border-gray-100">
                <div class="flex flex-col items-center space-y-4">
                    <div class="relative">
                        <div class="w-12 h-12 border-4 border-gray-200 rounded-full animate-spin border-t-blue-600"></div>
                        <div class="absolute inset-0 w-12 h-12 border-4 border-transparent rounded-full animate-pulse border-t-blue-400 opacity-75"></div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">Downloading Data</h3>
                        <p class="text-sm text-gray-500">Please wait while we prepare your file...</p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-blue-600 h-1.5 rounded-full animate-pulse" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        `;
            document.body.appendChild(loadingDiv);

            const apiUrl = `https://${window.location.host}/api/finance/get_profit_model_by_yearly_export`;
            const postData = {
                date: date
            };

            axios.post(apiUrl, postData)
                .then(response => {
                    console.log('API Response (profit_model_year_table):', response);
                    const tableData = response.data.data;
                    if (tableData && tableData.length > 0) {
                        const excelContent = convertToExcel(tableData);
                        const filename = `profit_model_year_table_${date.replace(/\s+/g, '_')}.xlsx`;
                        downloadExcel(excelContent, filename);
                    } else {
                        alert('No data available for the selected date.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching data (profit_model_year_table):', error);
                    console.error('Error details:', error.response);
                    alert('Failed to fetch data for download.');
                })
                .finally(() => {
                    const loading = document.getElementById('loadingOverlay');
                    if (loading) {
                        loading.remove();
                    }
                });
        }

        function downloadProfitModelMonthData() {
            var date = $('#selectMonthly').val();

            const loadingDiv = document.createElement('div');
            loadingDiv.id = 'loadingOverlay';
            loadingDiv.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center';
            loadingDiv.style.zIndex = '10000';
            loadingDiv.innerHTML = `
            <div class="bg-white rounded-xl shadow-2xl p-8 max-w-sm mx-auto border border-gray-100">
                <div class="flex flex-col items-center space-y-4">
                    <div class="relative">
                        <div class="w-12 h-12 border-4 border-gray-200 rounded-full animate-spin border-t-blue-600"></div>
                        <div class="absolute inset-0 w-12 h-12 border-4 border-transparent rounded-full animate-pulse border-t-blue-400 opacity-75"></div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">Downloading Data</h3>
                        <p class="text-sm text-gray-500">Please wait while we prepare your file...</p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-blue-600 h-1.5 rounded-full animate-pulse" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        `;
            document.body.appendChild(loadingDiv);

            const apiUrl = `https://${window.location.host}/api/finance/get_profit_model_by_monthly_export`;
            const postData = {
                date: date
            };

            axios.post(apiUrl, postData)
                .then(response => {
                    console.log('API Response (profit_model_monthly_table):', response);
                    const tableData = response.data.data;
                    if (tableData && tableData.length > 0) {
                        const excelContent = convertToExcel(tableData);
                        const filename = `profit_model_monthly_table_${date.replace(/\s+/g, '_')}.xlsx`;
                        downloadExcel(excelContent, filename);
                    } else {
                        alert('No data available for the selected date.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching data (profit_model_monthly_table):', error);
                    console.error('Error details:', error.response);
                    alert('Failed to fetch data for download.');
                })
                .finally(() => {
                    const loading = document.getElementById('loadingOverlay');
                    if (loading) {
                        loading.remove();
                    }
                });
        }

        function downloadProfitCategoryMonthData() {
            var date = $('#selectMonthCategory').val();

            const loadingDiv = document.createElement('div');
            loadingDiv.id = 'loadingOverlay';
            loadingDiv.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center';
            loadingDiv.style.zIndex = '10000';
            loadingDiv.innerHTML = `
            <div class="bg-white rounded-xl shadow-2xl p-8 max-w-sm mx-auto border border-gray-100">
                <div class="flex flex-col items-center space-y-4">
                    <div class="relative">
                        <div class="w-12 h-12 border-4 border-gray-200 rounded-full animate-spin border-t-blue-600"></div>
                        <div class="absolute inset-0 w-12 h-12 border-4 border-transparent rounded-full animate-pulse border-t-blue-400 opacity-75"></div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">Downloading Data</h3>
                        <p class="text-sm text-gray-500">Please wait while we prepare your file...</p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-blue-600 h-1.5 rounded-full animate-pulse" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        `;
            document.body.appendChild(loadingDiv);

            const apiUrl = `https://${window.location.host}/api/finance/get_profit_category_by_monthly_table_export`;
            const postData = {
                date: date
            };

            axios.post(apiUrl, postData)
                .then(response => {
                    console.log('API Response (profit_category_month_table):', response);
                    const tableData = response.data.data;
                    if (tableData && tableData.length > 0) {
                        const excelContent = convertToExcel(tableData);
                        const filename = `profit_category_month_table${date.replace(/\s+/g, '_')}.xlsx`;
                        downloadExcel(excelContent, filename);
                    } else {
                        alert('No data available for the selected date.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching data (profit_model_monthly_table):', error);
                    console.error('Error details:', error.response);
                    alert('Failed to fetch data for download.');
                })
                .finally(() => {
                    const loading = document.getElementById('loadingOverlay');
                    if (loading) {
                        loading.remove();
                    }
                });
        }

        function downloadPartByMonth() {
            var date = $('#month_year_table').val();

            const loadingDiv = document.createElement('div');
            loadingDiv.id = 'loadingOverlay';
            loadingDiv.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center';
            loadingDiv.style.zIndex = '10000';
            loadingDiv.innerHTML = `
            <div class="bg-white rounded-xl shadow-2xl p-8 max-w-sm mx-auto border border-gray-100">
                <div class="flex flex-col items-center space-y-4">
                    <div class="relative">
                        <div class="w-12 h-12 border-4 border-gray-200 rounded-full animate-spin border-t-blue-600"></div>
                        <div class="absolute inset-0 w-12 h-12 border-4 border-transparent rounded-full animate-pulse border-t-blue-400 opacity-75"></div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">Downloading Data</h3>
                        <p class="text-sm text-gray-500">Please wait while we prepare your file...</p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-blue-600 h-1.5 rounded-full animate-pulse" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        `;
            document.body.appendChild(loadingDiv);

            const apiUrl = `https://${window.location.host}/api/finance/get_invoice_cost_table_export`;
            const postData = {
                date: date
            };

            axios.post(apiUrl, postData)
                .then(response => {
                    console.log('API Response (sales-cost-table):', response);
                    const tableData = response.data.data;
                    if (tableData && tableData.length > 0) {
                        const excelContent = convertToExcel(tableData);
                        const filename = `sales-cost-table_${date.replace(/\s+/g, '_')}.xlsx`;
                        downloadExcel(excelContent, filename);
                    } else {
                        alert('No data available for the selected date.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching data (sales-cost-table):', error);
                    console.error('Error details:', error.response);
                    alert('Failed to fetch data for download.');
                })
                .finally(() => {
                    const loading = document.getElementById('loadingOverlay');
                    if (loading) {
                        loading.remove();
                    }
                });
        }

        function downloadProfitCustYearData() {
            var date = $('#current_year_customer').val();

            const loadingDiv = document.createElement('div');
            loadingDiv.id = 'loadingOverlay';
            loadingDiv.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center';
            loadingDiv.style.zIndex = '10000';
            loadingDiv.innerHTML = `
            <div class="bg-white rounded-xl shadow-2xl p-8 max-w-sm mx-auto border border-gray-100">
                <div class="flex flex-col items-center space-y-4">
                    <div class="relative">
                        <div class="w-12 h-12 border-4 border-gray-200 rounded-full animate-spin border-t-blue-600"></div>
                        <div class="absolute inset-0 w-12 h-12 border-4 border-transparent rounded-full animate-pulse border-t-blue-400 opacity-75"></div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">Downloading Data</h3>
                        <p class="text-sm text-gray-500">Please wait while we prepare your file...</p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-blue-600 h-1.5 rounded-full animate-pulse" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        `;
            document.body.appendChild(loadingDiv);

            const apiUrl = `https://${window.location.host}/api/finance/profit_invoice_cust_yearly_table_export`;
            const postData = {
                date: date
            };

            axios.post(apiUrl, postData)
                .then(response => {
                    console.log('API Response (profit_cust_by_year_table):', response);
                    const tableData = response.data.data;
                    if (tableData && tableData.length > 0) {
                        const excelContent = convertToExcel(tableData);
                        const filename = `profit_cust_by_year_table_${date.replace(/\s+/g, '_')}.xlsx`;
                        downloadExcel(excelContent, filename);
                    } else {
                        alert('No data available for the selected date.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching data (profit_cust_by_year_table):', error);
                    console.error('Error details:', error.response);
                    alert('Failed to fetch data for download.');
                })
                .finally(() => {
                    const loading = document.getElementById('loadingOverlay');
                    if (loading) {
                        loading.remove();
                    }
                });
        }

        function downloadProfitCustMonthData() {
            var date = $('#current_month_customer').val();

            const loadingDiv = document.createElement('div');
            loadingDiv.id = 'loadingOverlay';
            loadingDiv.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center';
            loadingDiv.style.zIndex = '10000';
            loadingDiv.innerHTML = `
            <div class="bg-white rounded-xl shadow-2xl p-8 max-w-sm mx-auto border border-gray-100">
                <div class="flex flex-col items-center space-y-4">
                    <div class="relative">
                        <div class="w-12 h-12 border-4 border-gray-200 rounded-full animate-spin border-t-blue-600"></div>
                        <div class="absolute inset-0 w-12 h-12 border-4 border-transparent rounded-full animate-pulse border-t-blue-400 opacity-75"></div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">Downloading Data</h3>
                        <p class="text-sm text-gray-500">Please wait while we prepare your file...</p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-blue-600 h-1.5 rounded-full animate-pulse" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        `;
            document.body.appendChild(loadingDiv);

            const apiUrl = `https://${window.location.host}/api/finance/profit_invoice_cust_month_table_export`;
            const postData = {
                date: date
            };

            axios.post(apiUrl, postData)
                .then(response => {
                    console.log('API Response (profit_cust_by_month_table):', response);
                    const tableData = response.data.data;
                    if (tableData && tableData.length > 0) {
                        const excelContent = convertToExcel(tableData);
                        const filename = `profit_cust_by_month_table_${date.replace(/\s+/g, '_')}.xlsx`;
                        downloadExcel(excelContent, filename);
                    } else {
                        alert('No data available for the selected date.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching data (profit_cust_by_month_table):', error);
                    console.error('Error details:', error.response);
                    alert('Failed to fetch data for download.');
                })
                .finally(() => {
                    const loading = document.getElementById('loadingOverlay');
                    if (loading) {
                        loading.remove();
                    }
                });
        }

        function downloadProfitCategoryYearData() {
            var date = $('#selectDateCategory').val();

            const loadingDiv = document.createElement('div');
            loadingDiv.id = 'loadingOverlay';
            loadingDiv.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center';
            loadingDiv.style.zIndex = '10000';
            loadingDiv.innerHTML = `
            <div class="bg-white rounded-xl shadow-2xl p-8 max-w-sm mx-auto border border-gray-100">
                <div class="flex flex-col items-center space-y-4">
                    <div class="relative">
                        <div class="w-12 h-12 border-4 border-gray-200 rounded-full animate-spin border-t-blue-600"></div>
                        <div class="absolute inset-0 w-12 h-12 border-4 border-transparent rounded-full animate-pulse border-t-blue-400 opacity-75"></div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">Downloading Data</h3>
                        <p class="text-sm text-gray-500">Please wait while we prepare your file...</p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-blue-600 h-1.5 rounded-full animate-pulse" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        `;
            document.body.appendChild(loadingDiv);

            const apiUrl = `https://${window.location.host}/api/finance/get_profit_category_by_yearly_table_export`;
            const postData = {
                date: date
            };

            axios.post(apiUrl, postData)
                .then(response => {
                    console.log('API Response (profit_category_year_table):', response);
                    const tableData = response.data.data;
                    if (tableData && tableData.length > 0) {
                        const excelContent = convertToExcel(tableData);
                        const filename = `profit_category_year_table_${date.replace(/\s+/g, '_')}.xlsx`;
                        downloadExcel(excelContent, filename);
                    } else {
                        alert('No data available for the selected date.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching data (profit_model_year_table):', error);
                    console.error('Error details:', error.response);
                    alert('Failed to fetch data for download.');
                })
                .finally(() => {
                    const loading = document.getElementById('loadingOverlay');
                    if (loading) {
                        loading.remove();
                    }
                });
        }

        function convertToExcel(data) {
            let workbook = XLSX.utils.book_new();
            let worksheet = XLSX.utils.json_to_sheet(data);
            XLSX.utils.book_append_sheet(workbook, worksheet, "Data");
            return workbook;
        }

        function downloadExcel(workbook, filename) {
            XLSX.writeFile(workbook, filename);
        }

        function setDefaultDatas() {
            var currentHost = window.location.host;

            const selectYearly = document.getElementById('selectYearly').value;
            const apiUrlYearly = `https://${currentHost}/api/finance/get_profit_model_yearly/${selectYearly}`;
            axios.get(apiUrlYearly)
                .then(response => {
                    var data_chart_sales = response.data.data_chart_sales;
                    var data_chart_cost = response.data.data_chart_cost;
                    var data_chart_expenses = response.data.data_chart_expenses;
                    var data_chart_profit = response.data.data_chart_profit;
                    var data_chart_model = response.data.data_chart_model;
                    document.getElementById('inpt_data_sales').value = data_chart_sales;
                    document.getElementById('inpt_data_cost').value = data_chart_cost;
                    document.getElementById('inpt_data_expenses').value = data_chart_expenses;
                    document.getElementById('inpt_data_profit').value = data_chart_profit;
                    document.getElementById('inpt_data_model').value = data_chart_model;
                    document.getElementById('btn_update_chart_model').click();
                })
                .catch(error => {
                    console.error('Error fetching default data:', error);
                });

            const selectMonthly = document.getElementById('selectMonthly').value;
            const apiUrlMonthly = `https://${currentHost}/api/finance/get_profit_model_monthly/${selectMonthly}`;
            axios.get(apiUrlMonthly)
                .then(response => {
                    var data_chart_sales = response.data.data_chart_sales;
                    var data_chart_cost = response.data.data_chart_cost;
                    var data_chart_expenses = response.data.data_chart_expenses;
                    var data_chart_profit = response.data.data_chart_profit;
                    var data_chart_model = response.data.data_chart_model;
                    document.getElementById('inpt_data_sales_month').value = data_chart_sales;
                    document.getElementById('inpt_data_cost_month').value = data_chart_cost;
                    document.getElementById('inpt_data_expenses_month').value = data_chart_expenses;
                    document.getElementById('inpt_data_profit_month').value = data_chart_profit;
                    document.getElementById('inpt_data_model_month').value = data_chart_model;
                    document.getElementById('btn_update_month').click();
                })
                .catch(error => {
                    console.error('Error fetching default data:', error);
                });

            const selectCatYear = document.getElementById('selectDateCategory').value;
            const apiUrlCatYear = `https://${currentHost}/api/finance/get_profit_category_year/${selectCatYear}`;
            axios.get(apiUrlCatYear)
                .then(response => {
                    var data_chart_sales = response.data.data_chart_sales;
                    var data_chart_cost = response.data.data_chart_cost;
                    var data_chart_expenses = response.data.data_chart_expenses;
                    var data_chart_profit = response.data.data_chart_profit;
                    var data_chart_category = response.data.data_chart_category;
                    document.getElementById('inpt_data_sales_category').value = data_chart_sales;
                    document.getElementById('inpt_data_cost_category').value = data_chart_cost;
                    document.getElementById('inpt_data_expenses_category').value = data_chart_expenses;
                    document.getElementById('inpt_data_profit_category').value = data_chart_profit;
                    document.getElementById('inpt_data_category').value = data_chart_category;
                    document.getElementById('btn_update_category').click();
                })
                .catch(error => {
                    console.error('Error fetching default data:', error);
                });

            const selectCatMonth = document.getElementById('selectMonthCategory').value;
            const apiUrlCatMonth = `https://${currentHost}/api/finance/get_profit_category_month/${selectCatMonth}`;
            axios.get(apiUrlCatMonth)
                .then(response => {
                    var data_chart_sales_month = response.data.data_chart_sales_month;
                    var data_chart_cost_month = response.data.data_chart_cost_month;
                    var data_chart_expenses_month = response.data.data_chart_expenses_month;
                    var data_chart_profit_month = response.data.data_chart_profit_month;
                    var data_chart_category_month = response.data.data_chart_category_month;
                    document.getElementById('inpt_data_sales_category_month').value = data_chart_sales_month;
                    document.getElementById('inpt_data_cost_category_month').value = data_chart_cost_month;
                    document.getElementById('inpt_data_expenses_category_month').value = data_chart_expenses_month;
                    document.getElementById('inpt_data_profit_category_month').value = data_chart_profit_month;
                    document.getElementById('inpt_data_category_month').value = data_chart_category_month;
                    document.getElementById('btn_update_category_month').click();
                })
                .catch(error => {
                    console.error('Error fetching default data:', error);
                });
        }



        document.getElementById('selectYearly').addEventListener('change', function() {
            updateChartYear();
            detail_table_profit_model_yearly();

        });
        document.getElementById('selectDateCategory').addEventListener('change', function() {
            updateChartYearCat();
            detail_table_profit_category_yearly();

        });
        document.getElementById('selectMonthly').addEventListener('change', function() {
            updateChartMonth();
            detail_table_profit_model_monthly();

        });
        document.getElementById('selectMonthCategory').addEventListener('change', function() {
            updateChartMonthCat();
            detail_table_profit_category_monthly();
        });

        document.getElementById('current_year').addEventListener('change', function() {
            detail_table_profit_by_month();
        });
        document.getElementById('current_month_customer').addEventListener('change', function() {
            detail_table_profi_cust_by_month();
        });

        document.getElementById('current_year_customer').addEventListener('change', function() {
            detail_table_profi_cust_by_year();
        });
    </script>
</x-layout.default>
