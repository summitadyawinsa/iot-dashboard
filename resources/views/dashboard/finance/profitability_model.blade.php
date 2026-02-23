<x-layout.default>
    <script defer src="/assets/js/apexcharts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <style>
        #profit_model_year_table tbody tr:hover,
        #profit_category_month_table tbody:hover,
        #profit_category_year_table tbody tr:hover,
        #profit_model_month_table tbody tr:hover {
            background-color: #727e93;
            cursor: pointer;
        }


        #profit_model_year_table tbody tr,
        #profit_category_month_table tbody tr,
        #profit_category_year_table tbody tr,
        #profit_model_month_table tbody tr {
            background-color: #4B5563;
            color: whitesmoke;
            cursor: pointer;
            font-size: small;
        }


        #profit_model_year_table thead tr,
        #profit_category_month_table thead tr,
        #profit_category_year_table thead tr,
        #profit_model_month_table thead tr {
            background-color: #4B5563;
            color: whitesmoke;
        }

        #selectYearly::-webkit-calendar-picker-indicator,
        #selectMonthly::-webkit-calendar-picker-indicator,
        #selectMonthCategory::-webkit-calendar-picker-indicator,
        #selectMonthlyTable::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        select[name="profit_model_year_table_length"],
        select[name="profit_category_year_table_length"],
        select[name="profit_category_month_table_length"],
        select[name="profit_model_month_table_length"] {
            background-color: #4B5563;
            width: 80px;
        }
    </style>
    <div x-data="analytics">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>Finance</span>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>Profit By Model</span>
            </li>
        </ul>
        <div class="pt-5">
            <div class="grid sm:grid-cols-2 xl:grid-cols-12 gap-6 mb-6">
                <?php
        $currentYear = date('Y');
        $startYear = $currentYear - 5;
        $currentMonth = date('Y-m');
        $currentDate = date('Y-m-d');
        $startDate = date('Y-m-d');
        $toDate = date('Y-m-d');
                ?>

                <div class="panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-3">
                            <label for="selectYearly" class="text-xl font-bold">Profit By Model Year</label>
                            <span></span>
                            <input id="selectYearly" type="number" class="form-input" value="<?= $currentYear ?>"
                                style="color: white;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart"
                                x-on:click="() => ChartRecall2(inpt_data_sales.value, inpt_data_cost.value, inpt_data_model.value)"
                                hidden>Recall and Update
                            </button>
                            <input type="text" id="inpt_data_sales" value="" hidden>
                            <input type="text" id="inpt_data_cost" value="" hidden>
                            <input type="text" id="inpt_data_model" value="" hidden>
                            <div x-ref="BoxChart3" class="overflow-hidden"></div>
                        </div>
                    </div>
                    <div class="relative overflow-hidden pt-5">
                        <table id="profit_model_year_table" class="min-w-full rounded-md shadow-md overflow-hidden">
                            <thead>
                                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                                    <th class="py-3 px-6 text-left">Invoice Year</th>
                                    <th class="py-3 px-6 text-left">Model</th>
                                    <th class="py-3 px-6 text-left">Profit</th>
                                    <th class="py-3 px-6 text-left">Sales</th>
                                    <th class="py-3 px-6 text-left">Cost</th>
                                    <th class="py-3 px-6 text-left">Status</th>
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
                            <input id="selectMonthly" type="month" class="form-input" value="<?= $currentMonth ?>"
                                style="color: white;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_month"
                                x-on:click="() => ChartRecall3(inpt_data_sales_month.value, inpt_data_cost_month.value, inpt_data_model_month.value)"
                                hidden>Recall and Update
                            </button>
                            <input type="text" id="inpt_data_sales_month" value="" hidden>
                            <input type="text" id="inpt_data_cost_month" value="" hidden>
                            <input type="text" id="inpt_data_model_month" value="" hidden>
                            <div x-ref="BoxChart4" class="overflow-hidden"></div>
                        </div>
                    </div>
                    <div class="relative overflow-hidden pt-5">
                        <table id="profit_model_month_table" class="min-w-full rounded-md shadow-md overflow-hidden">
                            <thead>
                                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                                    <th class="py-3 px-6 text-left">Invoice Year</th>
                                    <th class="py-3 px-6 text-left">Invoice Month</th>
                                    <th class="py-3 px-6 text-left">Model</th>
                                    <th class="py-3 px-6 text-left">Profit</th>
                                    <th class="py-3 px-6 text-left">Sales</th>
                                    <th class="py-3 px-6 text-left">Cost</th>
                                    <th class="py-3 px-6 text-left">Status</th>
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
                            <input id="selectDateCategory" type="number" class="form-input" value="<?= $currentYear ?>"
                                style="color: white;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_category"
                                x-on:click="() => ChartRecall4(inpt_data_sales_category.value, inpt_data_cost_category.value, inpt_data_category.value)"
                                hidden>Recall and Update
                            </button>
                            <input type="text" id="inpt_data_sales_category" value="" hidden>
                            <input type="text" id="inpt_data_cost_category" value="" hidden>
                            <input type="text" id="inpt_data_category" value="" hidden>
                            <div x-ref="BoxChart5" class="overflow-hidden"></div>
                        </div>
                    </div>
                    <div class="relative overflow-hidden pt-5">
                        <table id="profit_category_year_table" class="min-w-full rounded-md shadow-md overflow-hidden">
                            <thead>
                                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                                    <th class="py-3 px-6 text-left">Years</th>
                                    <th class="py-3 px-6 text-left">Category</th>
                                    <th class="py-3 px-6 text-left">Sales</th>
                                    <th class="py-3 px-6 text-left">Cost</th>
                                    <th class="py-3 px-6 text-left">Profit</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- Months -->
                <div class="panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-3">
                            <label for="selectMonthCategory" class="text-xl font-bold">Profit By Category Month</label>
                            <span></span>
                            <input id="selectMonthCategory" type="month" class="form-input" value="<?= $currentMonth ?>"
                                style="color: white;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_category_month"
                                x-on:click="() => ChartRecall5(inpt_data_sales_category_month.value, inpt_data_cost_category_month.value, inpt_data_category_month.value)"
                                hidden>Recall and Update
                            </button>
                            <input type="text" id="inpt_data_sales_category_month" value="" hidden>
                            <input type="text" id="inpt_data_cost_category_month" value="" hidden>
                            <input type="text" id="inpt_data_category_month" value="" hidden>
                            <div x-ref="BoxChart6" class="overflow-hidden"></div>
                        </div>
                    </div>
                    <div class="relative overflow-hidden pt-5">
                        <table id="profit_category_month_table" class="min-w-full rounded-md shadow-md overflow-hidden">
                            <thead>
                                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                                    <th class="py-3 px-6 text-left">Years</th>
                                    <th class="py-3 px-6 text-left">Months</th>
                                    <th class="py-3 px-6 text-left">Category</th>
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
        let globalSelectedStatus = null;

        document.addEventListener("DOMContentLoaded", function () {

            function detail_table_profit_model_yearly() {
                var selectYear = $('#selectYearly').val();
                detailTableProfitYear = $("#profit_model_year_table").DataTable({
                    destroy: true,
                    scrollX: true,
                    processing: true,
                    serverSide: true,
                    responsive: false,
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
                        data: function (d) {
                            return JSON.stringify({
                                ...d,
                                date: selectYear,
                            });
                        },
                        cache: false,
                        dataType: 'json',
                        error: function (xhr, error, thrown) {
                            console.error('AJAX Error (detail_table_profit_model_yearly):', error, thrown, xhr.responseText);
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
                        data: 'Profit',
                        width: '150px',
                        render: function (data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Sales',
                        width: '150px',
                        render: function (data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Cost',
                        width: '150px',
                        render: function (data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Loss',

                    }
                    ],
                    createdRow: function (row, data, dataIndex) {
                        if (dataIndex % 2 === 0) {
                            $(row).css('background-color', '#1D1D1D');
                        } else {
                            $(row).css('background-color', '#2D2D2D');
                        }

                        ['Loss'].forEach(function (column) {
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

                downloadButton.addEventListener('click', function () {
                    downloadProfitYearData();
                });

                setTimeout(function () {
                    detailTableProfitYear.ajax.reload();
                }, 100);

                return detailTableProfitYear;
            }

            function detail_table_profit_category_yearly() {
                var selectYear = $('#selectDateCategory').val();
                detailTableCategoryProfitYear = $("#profit_category_year_table").DataTable({
                    destroy: true,
                    scrollX: true,
                    processing: true,
                    serverSide: true,
                    responsive: false,
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
                        data: function (d) {
                            return JSON.stringify({
                                ...d,
                                date: selectYear,
                            });
                        },
                        cache: false,
                        dataType: 'json',
                        error: function (xhr, error, thrown) {
                            console.error('AJAX Error (detail_table_profit_category_yearly):', error, thrown, xhr.responseText);
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
                        render: function (data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }


                    },
                    {
                        data: 'Cost',
                        width: '150px',
                        render: function (data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }


                    },
                    {
                        data: 'Profit',
                        width: '150px',
                        render: function (data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }


                    },
                    ],
                    createdRow: function (row, data, dataIndex) {
                        if (dataIndex % 2 === 0) {
                            $(row).css('background-color', '#1D1D1D');
                        } else {
                            $(row).css('background-color', '#2D2D2D');
                        }

                        ['Loss'].forEach(function (column) {
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

                downloadButton.addEventListener('click', function () {
                    downloadProfitYearData();
                });

                setTimeout(function () {
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
                    responsive: false,
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
                        data: function (d) {
                            return JSON.stringify({
                                ...d,
                                date: selectYear,
                            });
                        },
                        cache: false,
                        dataType: 'json',
                        error: function (xhr, error, thrown) {
                            console.error('AJAX Error (detail_table_profit_category_monthly):', error, thrown, xhr.responseText);
                        }
                    },
                    columns: [
                        {
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
                            render: function (data, type, row) {
                                return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                            }


                        },
                        {
                            data: 'Cost',
                            width: '150px',
                            render: function (data, type, row) {
                                return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                            }


                        },
                        {
                            data: 'Profit',
                            width: '150px',
                            render: function (data, type, row) {
                                return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                            }


                        },
                        {
                            data: 'Status',
                        },
                    ],
                    createdRow: function (row, data, dataIndex) {
                        if (dataIndex % 2 === 0) {
                            $(row).css('background-color', '#1D1D1D');
                        } else {
                            $(row).css('background-color', '#2D2D2D');
                        }

                        ['Status'].forEach(function (column) {
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

                downloadButton.addEventListener('click', function () {
                    downloadProfitYearData();
                });

                setTimeout(function () {
                    detailTableProfitYear.ajax.reload();
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
                    responsive: false,
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
                        data: function (d) {
                            return JSON.stringify({
                                ...d,
                                date: selectMonth,
                            });
                        },
                        cache: false,
                        dataType: 'json',
                        error: function (xhr, error, thrown) {
                            console.error('AJAX Error (detail_table_profit_model_monthly):', error, thrown, xhr.responseText);
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
                        data: 'Profit',
                        width: '150px',
                        render: function (data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Sales',
                        width: '150px',
                        render: function (data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Cost',
                        width: '150px',
                        render: function (data, type, row) {
                            return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'Loss',

                    }
                    ],
                    createdRow: function (row, data, dataIndex) {
                        if (dataIndex % 2 === 0) {
                            $(row).css('background-color', '#1D1D1D');
                        } else {
                            $(row).css('background-color', '#2D2D2D');
                        }

                        ['Loss'].forEach(function (column) {
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

                downloadButton.addEventListener('click', function () {
                    createDateRangePopup('profit_model_month_table');
                });

                setTimeout(function () {
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

                document.getElementById('popupCancelBtn').addEventListener('click', function () {
                    overlay.remove();
                });

                overlay.addEventListener('click', function (e) {
                    if (e.target === overlay) {
                        overlay.remove();
                    }
                });

                document.getElementById('popupDownloadBtn').addEventListener('click', function () {
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

                    if (tableType === 'profit_model_month_table') {
                        downloadProfitMonthData(startDate, endDate);
                    } else if (tableType === 'po_project_ammount') {
                        downloadPOProjectAmountData(startDate, endDate);
                    }
                    overlay.remove();
                });
            }

            function downloadProfitMonthData(startDate, endDate) {
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

                const apiUrl = `https://${window.location.host}/api/finance/get_profit_model_by_month_export`;
                const postData = {
                    startDate: startDate,
                    endDate: endDate
                };

                axios.post(apiUrl, postData)
                    .then(response => {
                        console.log('API Response (po_project):', response);
                        const tableData = response.data.data;
                        if (tableData && tableData.length > 0) {
                            const excelContent = convertToExcel(tableData);
                            const filename = `po_project_data_${startDate}_to_${endDate}.xlsx`;
                            downloadExcel(excelContent, filename);
                        } else {
                            alert('No data available for the selected date range.');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching data (po_project):', error);
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

            function downloadProfitYearData() {
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

            function convertToExcel(data) {
                let workbook = XLSX.utils.book_new();
                let worksheet = XLSX.utils.json_to_sheet(data);
                XLSX.utils.book_append_sheet(workbook, worksheet, "Data");
                return workbook;
            }

            function downloadExcel(workbook, filename) {
                XLSX.writeFile(workbook, filename);
            }

            function setDefaultData() {
                var currentHost = window.location.host;

                const selectYearly = document.getElementById('selectYearly').value;
                const apiUrlYearly = `https://${currentHost}/api/finance/get_profit_model_yearly/${selectYearly}`;
                axios.get(apiUrlYearly)
                    .then(response => {
                        var data_chart_sales = response.data.data_chart_sales;
                        var data_chart_cost = response.data.data_chart_cost;
                        var data_chart_model = response.data.data_chart_model;
                        document.getElementById('inpt_data_sales').value = data_chart_sales;
                        document.getElementById('inpt_data_cost').value = data_chart_cost;
                        document.getElementById('inpt_data_model').value = data_chart_model;
                        document.getElementById('btn_update_chart').click();
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
                        var data_chart_model = response.data.data_chart_model;
                        document.getElementById('inpt_data_sales_month').value = data_chart_sales;
                        document.getElementById('inpt_data_cost_month').value = data_chart_cost;
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
                        var data_chart_category = response.data.data_chart_category;
                        document.getElementById('inpt_data_sales_category').value = data_chart_sales;
                        document.getElementById('inpt_data_cost_category').value = data_chart_cost;
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
                        var data_chart_category_month = response.data.data_chart_category_month;
                        document.getElementById('inpt_data_sales_category_month').value = data_chart_sales_month;
                        document.getElementById('inpt_data_cost_category_month').value = data_chart_cost_month;
                        document.getElementById('inpt_data_category_month').value = data_chart_category_month;
                        document.getElementById('btn_update_category_month').click();
                    })
                    .catch(error => {
                        console.error('Error fetching default data:', error);
                    });
            }

            detail_table_profit_model_yearly();
            detail_table_profit_model_monthly();
            detail_table_profit_category_yearly();
            detail_table_profit_category_monthly();
            setDefaultData();



            document.getElementById('selectYearly').addEventListener('change', function () {
                updateChartYear();
                detail_table_profit_model_yearly();

            });
            document.getElementById('selectDateCategory').addEventListener('change', function () {
                updateChartYearCat();
                detail_table_profit_category_yearly();

            });
            document.getElementById('selectMonthly').addEventListener('change', function () {
                updateChartMonth();
                detail_table_profit_model_monthly();

            });
            document.getElementById('selectMonthCategory').addEventListener('change', function () {
                updateChartMonthCat();
                detail_table_profit_category_monthly();
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
                    var data_chart_model = response.data.data_chart_model;
                    document.getElementById('inpt_data_sales').value = data_chart_sales;
                    document.getElementById('inpt_data_cost').value = data_chart_cost;
                    document.getElementById('inpt_data_model').value = data_chart_model;
                    document.getElementById('btn_update_chart').click();
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
                    var data_chart_model = response.data.data_chart_model;
                    document.getElementById('inpt_data_sales_month').value = data_chart_sales;
                    document.getElementById('inpt_data_cost_month').value = data_chart_cost;
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
                    var data_chart_category = response.data.data_chart_category;
                    document.getElementById('inpt_data_sales_category').value = data_chart_sales;
                    document.getElementById('inpt_data_cost_category').value = data_chart_cost;
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
                    var data_chart_category_month = response.data.data_chart_category_month;
                    document.getElementById('inpt_data_sales_category_month').value = data_chart_sales_month;
                    document.getElementById('inpt_data_cost_category_month').value = data_chart_cost_month;
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
                    if (value >= 1e12) return (value / 1e12).toFixed(0) + 'T';
                    if (value >= 1e9) return (value / 1e9).toFixed(0) + 'B';
                    if (value >= 1e6) return (value / 1e6).toFixed(0) + 'M';
                    if (value >= 1e3) return (value / 1e3).toFixed(0) + 'K';
                    return value;
                },

                ChartRecall2(dataSales, dataCost, dataModel) {
                    const sales = dataSales.split(',').map(Number);
                    const cost = dataCost.split(',').map(Number);
                    const model = dataModel.split(',');

                    this.BoxChart3.updateSeries([{
                        name: 'Sales',
                        data: sales,
                    },
                    {
                        name: 'Cost',
                        data: cost,
                    },
                    ]);

                    this.BoxChart3.updateOptions({
                        xaxis: {
                            categories: model
                        }
                    });
                },

                ChartRecall3(dataSales, dataCost, dataModel) {
                    const sales = dataSales.split(',').map(Number);
                    const cost = dataCost.split(',').map(Number);
                    const model = dataModel.split(',');

                    this.BoxChart4.updateSeries([{
                        name: 'Sales',
                        data: sales,
                    },
                    {
                        name: 'Cost',
                        data: cost,
                    },
                    ]);

                    this.BoxChart4.updateOptions({
                        xaxis: {
                            categories: model
                        }
                    });
                },

                ChartRecall4(dataSales, dataCost, dataCategory) {
                    const sales = dataSales.split(',').map(Number);
                    const cost = dataCost.split(',').map(Number);
                    const category = dataCategory.split(',');

                    this.BoxChart5.updateSeries([{
                        name: 'Sales',
                        data: sales,
                    },
                    {
                        name: 'Cost',
                        data: cost,
                    },
                    ]);

                    this.BoxChart5.updateOptions({
                        xaxis: {
                            categories: category
                        }
                    });
                },

                ChartRecall5(dataSales, dataCost, dataCategory) {
                    const sales = dataSales.split(',').map(Number);
                    const cost = dataCost.split(',').map(Number);
                    const category = dataCategory.split(',');

                    sales.push(0);
                    cost.push(0);
                    category.push('');

                    this.BoxChart6.updateSeries([{
                        name: 'Sales',
                        data: sales
                    },
                    {
                        name: 'Cost',
                        data: cost
                    }
                    ]);

                    this.BoxChart6.updateOptions({
                        xaxis: {
                            categories: category
                        }
                    });
                },

                renderCharts() {

                    this.BoxChart3 = new ApexCharts(this.$refs.BoxChart3, this.BoxChart3Options);
                    this.BoxChart3.render();

                    this.BoxChart4 = new ApexCharts(this.$refs.BoxChart4, this.BoxChart4Options);
                    this.BoxChart4.render();

                    this.BoxChart5 = new ApexCharts(this.$refs.BoxChart5, this.BoxChart5Options);
                    this.BoxChart5.render();

                    this.BoxChart6 = new ApexCharts(this.$refs.BoxChart6, this.BoxChart6Options);
                    this.BoxChart6.render();

                },

                get BoxChart3Options() {
                    return {
                        series: [],
                        chart: {
                            type: 'area',
                            height: 360,
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
                            curve: 'smooth',
                            colors: ['#2196f3', '#f44336'],
                        },
                        colors: ['#2196f3', '#f44336'],
                        dropShadow: {
                            enabled: true,
                            blur: 3,

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
                            shared: false,
                            colors: ['#2196f3', '#f44336', '#07be35bb'],

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
                        series: [],
                        chart: {
                            type: 'area',
                            height: 360,
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
                            curve: 'smooth',
                            colors: ['#2196f3', '#f44336'],
                        },
                        colors: ['#2196f3', '#f44336'],
                        dropShadow: {
                            enabled: true,
                            blur: 3,

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
                            shared: false,
                            colors: ['#2196f3', '#f44336', '#07be35bb'],

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
                        series: [],
                        chart: {
                            type: 'area',
                            height: 360,
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
                            curve: 'smooth',
                            colors: ['#2196f3', '#f44336'],
                        },
                        colors: ['#2196f3', '#f44336'],
                        dropShadow: {
                            enabled: true,
                            blur: 3,

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
                            shared: false,
                            colors: ['#2196f3', '#f44336', '#07be35bb'],

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

                get BoxChart6Options() {
                    return {
                        series: [{
                            name: 'Sales',
                            data: ['0', '0'],
                        },
                        {
                            name: 'Cost',
                            data: ['0', '0'],
                        },
                        ],
                        chart: {
                            type: 'area',
                            height: 360,
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
                            curve: 'smooth',
                            colors: ['#2196f3', '#f44336'],
                        },
                        colors: ['#2196f3', '#f44336'],
                        dropShadow: {
                            enabled: true,
                            blur: 3,

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
                            shared: false,
                            colors: ['#2196f3', '#f44336', '#07be35bb'],

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
