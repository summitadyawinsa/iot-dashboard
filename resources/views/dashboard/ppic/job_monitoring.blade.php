<x-layout.default>
    <script defer src="/assets/js/apexcharts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <style>
        #monitoring_table tbody tr:hover,
        #total_job_open_close tbody tr:hover {
            background-color: #727e93;
            cursor: pointer;
        }

        #monitoring_table tbody tr,
        #total_job_open_close tbody tr {
            background-color: #4B5563;
            color: whitesmoke;
            cursor: pointer;
            font-size: small;
        }

        #monitoring_table thead tr,
        #total_job_open_close thead tr {
            color: whitesmoke;
        }

        #month_year::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        #month_year_table::-webkit-calendar-picker-indicator,
        #selectMonth::-webkit-calendar-picker-indicator,
        #selectDay::-webkit-calendar-picker-indicator,
        #month_id::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        select[name="monitoring_table_length"],
        select[name="total_job_open_close_length"] {
            width: 80px;
        }
    </style>
    <div x-data="analytics()">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>PPIC</span>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>Job Monitoring</span>
            </li>
        </ul>
        <div class="pt-5">
            <div class="grid sm:grid-cols-2 xl:grid-cols-12 gap-6 mb-6">
                <?php
$currentYear = date('Y');
$startYear = $currentYear - 5;
$currentMonth = date('Y-m');
$currentDay = date('Y-m-d');

                ?>

                <!-- Monitoring Month -->

                <div class="panel h-full sm:col-span-12 xl:col-span-12">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-3">
                            <label for="current_year" class="text-xl font-bold">Job By Months</label>
                            <span></span>
                            <input id="current_year" type="number" class="form-input" value="<?= $currentYear ?>"
                                style="color: white;" />
                        </div>
                    </div>

                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart"
                                x-on:click="() => ChartRecall1(inpt_val_job_data_open.value, inpt_val_job_data_close.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_job_data_open" value="" hidden> <input type="text"
                                id="inpt_val_job_data_close" value="" hidden>
                            <div x-ref="BoxChart2" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>
                <!-- Monitoring Days -->
                <div class="panel h-full sm:col-span-12 xl:col-span-12">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-3">
                            <label for="month_year" class="text-xl font-bold">Job By Days</label>
                            <span></span>
                            <input id="month_year" type="month" class="form-input" value="" style="color: white;"
                                hidden />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_cust_date"
                                x-on:click="() => ChartRecall5(inpt_job_open_days.value, inpt_job_close_days.value, inpt_val_date.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_job_open_days" value="" hidden>
                            <input type="text" id="inpt_job_close_days" value="" hidden>
                            <input type="text" id="inpt_val_date" value="$currentMonth" hidden>
                            <div x-ref="BoxChart5" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>
                <!-- Dept -->
                <div class="panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-2">
                            <label for="selectMonth" class="text-xl font-bold">By Month Departement</label>
                            <input id="selectMonth" type="month" class="form-input" value="<?= $currentMonth ?>"
                                style="color: white;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_dept"
                                x-on:click="() => ChartRecall6(inpt_data_departement.value, inpt_data_job_open.value, inpt_data_job_close.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_data_departement" value="" hidden>
                            <input type="text" id="inpt_data_job_open" value="" hidden>
                            <input type="text" id="inpt_data_job_close" value="" hidden>
                            <div x-ref="BoxChart6" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>
                <!-- Days Dept -->
                <div class="panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-3">
                            <label for="selectMonth" class="text-xl font-bold">By Day Departement</label>
                            <span></span>
                            <input id="selectDay" type="date" class="form-input" value="<?= $currentDay ?>"
                                style="color: white;" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_dept_date"
                                x-on:click="() => ChartRecall7(inpt_data_departement_date.value, inpt_data_job_open_date.value, inpt_data_job_close_date.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_data_departement_date" value="" hidden>
                            <input type="text" id="inpt_data_job_open_date" value="" hidden>
                            <input type="text" id="inpt_data_job_close_date" value="" hidden>
                            <div x-ref="BoxChart7" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>
                <!-- Monitoring Table -->
                <div class="panel h-full col-span-12">
                    <div class="grid grid-cols-1 sm:grid-cols-6 gap-4 p-5 dark:text-white-light">
                        <div>
                            <label for="month_year_table" hidden>Month</label>
                            <input id="month_year_table" type="date" class="form-input" value="" style="color: white;"
                                hidden />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-1">
                            <div class="flex pb-5 border-b border-[#e0e6ed] dark:border-[#ffffff]">
                                <div
                                    class="shrink-0 bg-primary/10 text-primary rounded-xl w-14 h-11 flex justify-center items-center dark:bg-primary dark:text-white-light">
                                    <div class="align-center xl:text-1xl sm:text-xl">
                                        <span id="total_issue_doc">0</span>
                                    </div>
                                </div>
                                <div class="ltr:ml-3 rtl:mr-3 font-semibold">
                                    <p class="text-xl dark:text-white-light">Total Jo Number</p>
                                    <h5 class="text-[#506690] text-xs">All Jo Number</h5>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1">
                            <div class="flex pb-5 border-b border-[#e0e6ed] dark:border-[#ffffff]">
                                <div
                                    class="shrink-0 bg-danger text-danger rounded-xl w-14 h-11 flex justify-center items-center dark:bg-danger dark:text-white">
                                    <div class="align-center xl:text-1xl sm:text-xl">
                                        <span id="total_open_doc">0</span>
                                    </div>
                                </div>
                                <div class="ltr:ml-3 rtl:mr-3 font-semibold">
                                    <p class="text-xl dark:text-white-light">Total Job Open</p>
                                    <h5 class="text-[#506690] text-xs">All This Job Open</h5>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1">
                            <div class="flex pb-5 border-b border-[#e0e6ed] dark:border-[#ffffff]">
                                <div
                                    class="shrink-0 bg-success/10 text-success rounded-xl w-14 h-11 flex justify-center items-center dark:bg-success dark:text-white-light">
                                    <div class="align-center xl:text-1xl sm:text-xl">
                                        <span id="total_close_doc">0</span>
                                    </div>
                                </div>
                                <div class="ltr:ml-3 rtl:mr-3 font-semibold">
                                    <p class="text-xl dark:text-white-light">Total Job Close</p>
                                    <h5 class="text-[#506690] text-xs">All This Job Close</h5>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1">
                            <div class="flex pb-5 border-b border-[#e0e6ed] dark:border-[#ffffff]">
                                <input class="form-input" type="date" name="month_id" id="month_id"
                                    style="background-color: #4B5563 ; color: whitesmoke;" value="<?= $currentDay ?>">
                            </div>
                        </div>
                    </div>
                    <div class="relative overflow-hidden pt-5">
                        <table id="monitoring_table" class="min-w-full rounded-md shadow-md overflow-hidden">
                            <thead>
                                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                                    <th class="py-3 px-6 text-left">Job Number</th>
                                    <th class="py-3 px-6 text-left">Plan</th>
                                    <th class="py-3 px-6 text-left">Actual</th>
                                    <th class="py-3 px-6 text-left">Receive</th>
                                    <th class="py-3 px-6 text-left">Issue</th>
                                    <th class="py-3 px-6 text-left">Prod</th>
                                    <th class="py-3 px-6 text-left">Receipt</th>
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
        $(document).ready(function () {
            let currentDepartment = null;

            const monthInputElement = $('#month_id');
            const totalJoNumber = $('#total_issue_doc');
            const totalOpenDocElement = $('#total_open_doc');
            const totalCloseDocElement = $('#total_close_doc');


            function fetchDataAndUpdateUI(selectedMonth, department = null) {
                if (!selectedMonth) {
                    totalJoNumber.text('0');
                    totalOpenDocElement.text('0');
                    totalCloseDocElement.text('0');
                    console.warn('Month not selected.');
                    return;
                }

                totalJoNumber.text('...');
                totalOpenDocElement.text('...');
                totalCloseDocElement.text('...');

                let url = `https://${window.location.host}/api/ppic/get_ppic_monitoring_table_close_open/${selectedMonth}`;

                const ajaxParams = {
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    data: {},
                    success: function (data) {
                        if (data.error) {
                            console.error('Error from server:', data.error);
                            totalJoNumber.text('Error');
                            totalOpenDocElement.text('Error');
                            totalCloseDocElement.text('Error');
                            return;
                        }

                        const sumJoNumber = Array.isArray(data.data_jo_number) ?
                            data.data_jo_number.reduce((sum, val) => sum + (parseInt(val) || 0), 0) :
                            0;

                        const sumJobOpen = Array.isArray(data.data_job_open) ?
                            data.data_job_open.reduce((sum, val) => sum + (parseInt(val) || 0), 0) :
                            0;

                        const sumJobClose = Array.isArray(data.data_job_close) ?
                            data.data_job_close.reduce((sum, val) => sum + (parseInt(val) || 0), 0) :
                            0;

                        totalJoNumber.text(sumJoNumber);
                        totalOpenDocElement.text(sumJobOpen);
                        totalCloseDocElement.text(sumJobClose);

                        if ($('#current_department').length) {
                            $('#current_department').text(department || 'All Departments');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching PPIC data:', error);
                        console.error('Status:', status);
                        console.error('Response:', xhr.responseText);

                        totalJoNumber.text('N/A');
                        totalOpenDocElement.text('N/A');
                        totalCloseDocElement.text('N/A');

                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            alert('Error: ' + xhr.responseJSON.error);
                        }
                    }
                };

                if (department) {
                    ajaxParams.data.department = department;
                    currentDepartment = department;
                } else {
                    currentDepartment = null;
                }

                $.ajax(ajaxParams);
            }

            monthInputElement.on('change', function () {
                fetchDataAndUpdateUI($(this).val(), currentDepartment);
            });

            window.fetchDepartmentData = function (department) {
                fetchDataAndUpdateUI(monthInputElement.val(), department);
            };

            if (monthInputElement.val()) {
                fetchDataAndUpdateUI(monthInputElement.val());
            } else {
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const currentMonthValue = `${year}-${month}`;
                monthInputElement.val(currentMonthValue);
                fetchDataAndUpdateUI(currentMonthValue);
            }

            if (!$('#clear_department_filter').length) {
                const clearButton = $('<button id="clear_department_filter" class="btn btn-sm btn-outline-secondary">Clear Department Filter</button>');
                clearButton.on('click', function () {
                    fetchDataAndUpdateUI(monthInputElement.val());
                    if ($('#current_department').length) {
                        $('#current_department').text('All Departments');
                    }
                });

                $('#filter_controls').append(clearButton);
            }
        });

        document.addEventListener("DOMContentLoaded", function () {
            let today = new Date().toISOString().split('T')[0];
            document.getElementById("month_year_table").value = today;

            function detail_table() {
                var detailTable = $("#monitoring_table").DataTable({
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
                        url: `https://${window.location.host}/api/ppic/get_ppic_monitoring_Table`,
                        type: 'POST',
                        contentType: "application/json",
                        data: function (d) {
                            let selectedMonth = document.getElementById('month_year_table').value;
                            if (selectedMonth) {
                                let jobs = selectedMonth.split("-");
                                d.year = `${jobs[0]}-${jobs[1]}-${jobs[2]}`;
                            }
                            let selectedDate = document.getElementById('month_year_table').value;
                            if (selectedDate) {
                                d.date = selectedDate;
                            }

                            if (window.selectedDepartment) {
                                d.department = window.selectedDepartment;
                            }

                            return JSON.stringify(d);
                        },
                        cache: false,
                        dataType: 'json'
                    },
                    columns: [{
                        data: 'JoNumber'
                    },
                    {
                        data: 'Plan'
                    },
                    {
                        data: 'Actual'
                    },
                    {
                        data: 'Received'
                    },
                    {
                        data: 'Issue'
                    },
                    {
                        data: 'Prod'
                    },
                    {
                        data: 'Receipt'
                    },
                    {
                        data: 'JobClosed'
                    }
                    ],
                    createdRow: function (row, data, dataIndex) {
                        if (dataIndex % 2 === 0) {
                            $(row).css('background-color', '#1D1D1D');
                        } else {
                            $(row).css('background-color', '#2D2D2D');
                        }

                        ['Issue', 'Prod', 'Receipt', 'JobClosed'].forEach(function (column) {
                            var cell = $('td', row).eq(getColumnIndex(column));

                            if (data[column] === 'CLOSE') {
                                cell.html('<span class="badge badge-outline-success">CLOSE</span>');
                            } else if (data[column] === 'OPEN') {
                                cell.html('<span class="badge badge-outline-warning">OPEN</span>');
                            }
                        });
                    },
                    drawCallback: function (settings) {
                        updateTableTitle();
                    }
                });

                setTimeout(function () {
                    detailTable.ajax.reload();
                }, 500);

                window.monitoringTable = detailTable;

                const buttonContainer = document.createElement('div');
                buttonContainer.style.display = 'flex';
                buttonContainer.style.gap = '10px';
                buttonContainer.style.marginTop = '10px';

                const downloadButton = document.createElement('button');
                downloadButton.id = 'download_button';
                downloadButton.textContent = 'Download Data';
                downloadButton.className = 'btn btn-primary';
                downloadButton.innerHTML = `<svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down-fill" viewBox="0 0 16 16" style="margin-right: 8px;">
<path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"/>
</svg> Download Data`;

                const resetFilterButton = document.createElement('button');
                resetFilterButton.id = 'reset_filter_button';
                resetFilterButton.textContent = 'Reset Filter';
                resetFilterButton.className = 'btn btn-secondary';
                resetFilterButton.innerHTML = `<svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16" style="margin-right: 8px;">
<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
</svg> Reset Filter`;

                buttonContainer.appendChild(downloadButton);
                buttonContainer.appendChild(resetFilterButton);

                const panel = document.querySelector('.panel.h-full.col-span-12');
                const existingDownloadBtn = document.getElementById('download_button');
                const existingResetBtn = document.getElementById('reset_filter_button');
                if (existingDownloadBtn) existingDownloadBtn.remove();
                if (existingResetBtn) existingResetBtn.remove();

                panel.appendChild(buttonContainer);

                const tableTitle = document.createElement('div');

                const tableWrapper = document.querySelector('#monitoring_table_wrapper');
                if (tableWrapper) {
                    tableWrapper.parentNode.insertBefore(tableTitle, tableWrapper);
                }

                updateTableTitle();

                downloadButton.addEventListener('click', function () {
                    const selectedDate = document.getElementById('month_year_table').value;
                    if (selectedDate) {
                        const apiUrl = `https://${window.location.host}/api/ppic/get_ppic_monitoring_Table_All`;
                        const postData = {
                            date: selectedDate
                        };

                        if (window.selectedDepartment) {
                            postData.department = window.selectedDepartment;
                        }

                        axios.post(apiUrl, postData)
                            .then(response => {
                                const tableData = response.data;
                                if (tableData.length > 0) {
                                    const excelContent = convertToExcel(tableData);
                                    const filename = window.selectedDepartment ?
                                        `job_monitoring_${window.selectedDepartment}_${selectedDate}.xlsx` :
                                        `job_monitoring_${selectedDate}.xlsx`;
                                    downloadExcel(excelContent, filename);
                                } else {
                                    alert('No data available to download.');
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching data:', error);
                                alert('Failed to fetch data for download.');
                            });
                    } else {
                        alert('Please select a date to download data.');
                    }
                });

                resetFilterButton.addEventListener('click', function () {
                    window.selectedDepartment = null;

                    $('#current_department').text('All');
                    updateTableTitle();

                    detailTable.ajax.reload();
                });

                return detailTable;
            }

            function updateTableTitle() {
                const titleElement = document.getElementById('table_title');
                if (titleElement) {
                    if (window.selectedDepartment) {
                        const departmentMapping = {
                            'ASY': 'ASSY',
                            'STP': 'STAMPING',
                            'SBC': 'SUBCONT',
                            'RPC': 'REPACKING'
                        };
                        const displayName = departmentMapping[window.selectedDepartment] || window.selectedDepartment;
                        titleElement.textContent = `Showing Jobs for Department: ${displayName}`;
                    } else {
                        titleElement.textContent = 'Showing All Jobs';
                    }
                }
            }

            function convertToExcel(data) {
                let workbook = XLSX.utils.book_new();
                let worksheet = XLSX.utils.json_to_sheet(data);
                XLSX.utils.book_append_sheet(workbook, worksheet, "Monitoring Data");
                return workbook;
            }

            function downloadExcel(workbook, filename) {
                XLSX.writeFile(workbook, filename);
            }

            function getColumnIndex(columnName) {
                var columns = ['JoNumber', 'Plan', 'Actual', 'Received', 'Issue', 'Prod', 'Receipt', 'JobClosed'];
                return columns.indexOf(columnName);
            }
            // Data bulanan
            function setDefaultData() {
                var currentHost = window.location.host;
                var currentYear = document.getElementById('current_year').value;

                const currentMonth = String(new Date().getMonth() + 1).padStart(2, '0');
                document.getElementById('month_year').value = `${currentYear}-${currentMonth}`;

                const apiUrlMonthly = `https://${currentHost}/api/ppic/get_ppic_monitoring_Monthly/${currentYear}`;
                axios.get(apiUrlMonthly)
                    .then(response => {
                        var data_chart_open_month = response.data.data_chart_open_month;
                        var data_chart_close_month = response.data.data_chart_close_month;
                        document.getElementById('inpt_val_job_data_open').value = data_chart_open_month;
                        document.getElementById('inpt_val_job_data_close').value = data_chart_close_month;
                        document.getElementById('btn_update_chart').click();
                    })

                // Data harian
                const apiUrlDaily = `https://${currentHost}/api/ppic/get_ppic_monitoring_Days/${currentYear}-${currentMonth}`;

                axios.get(apiUrlDaily)
                    .then(response => {
                        var data_open_days = response.data.data_open_days;
                        var data_close_days = response.data.data_close_days;
                        var data_cust_date = response.data.data_val_date;
                        document.getElementById('inpt_job_open_days').value = data_open_days;
                        document.getElementById('inpt_job_close_days').value = data_close_days;
                        document.getElementById('inpt_val_date').value = data_cust_date;
                        document.getElementById('btn_update_cust_date').click();
                    })

                // Data month depart
                var month = document.getElementById('selectMonth').value;
                const apiMonthDept = `https://${currentHost}/api/ppic/get_data_month_departement/${month}`;
                axios.get(apiMonthDept)
                    .then(response => {
                        var data_dept = response.data.data_dept;
                        var data_open_job = response.data.data_open_job;
                        var data_close_job = response.data.data_close_job;
                        document.getElementById('inpt_data_departement').value = data_dept;
                        document.getElementById('inpt_data_job_open').value = data_open_job;
                        document.getElementById('inpt_data_job_close').value = data_close_job;
                        document.getElementById('btn_update_dept').click();
                    })

                var month = document.getElementById('selectDay').value;
                const apiDayDept = `https://${currentHost}/api/ppic/get_data_day_departement/${month}`;
                axios.get(apiDayDept)
                    .then(response => {
                        var data_dept_date = response.data.data_dept_date;
                        var data_open_job_date = response.data.data_open_job_date;
                        var data_close_job_date = response.data.data_close_job_date;
                        document.getElementById('inpt_data_departement_date').value = data_dept_date;
                        document.getElementById('inpt_data_job_open_date').value = data_open_job_date;
                        document.getElementById('inpt_data_job_close_date').value = data_close_job_date;
                        document.getElementById('btn_update_dept_date').click();
                    })
            }
            setDefaultData()
            detail_table();

            document.getElementById('month_year').addEventListener('change', function () {
                updateDailyChart();
            });

            document.getElementById('current_year').addEventListener('change', function () {
                var selectedYear = this.value;
                document.getElementById('month_year').value = `${selectedYear}-01`;
                updateDailyChart();
            });

            document.getElementById('selectMonth').addEventListener('change', function () {
                updateMonthDept();
            });
            document.getElementById('selectDay').addEventListener('change', function () {
                updateDayDept();
            });

            document.getElementById('month_year_table').addEventListener('input', function () {
                if ($.fn.DataTable.isDataTable('#monitoring_table')) {
                    $('#monitoring_table').DataTable().ajax.reload(null, false);
                }
            });

            function updateDailyChart() {

                var currentHost = window.location.host;
                var yearX = document.getElementById('current_year').value;
                const apiUrlMonthly = `https://${currentHost}/api/ppic/get_ppic_monitoring_Monthly/${yearX}`;
                axios.get(apiUrlMonthly)
                    .then(response => {
                        var data_chart_open_month = response.data.data_chart_open_month;
                        var data_chart_close_month = response.data.data_chart_close_month;
                        var data_cust_date = response.data.data_val_date;
                        document.getElementById('inpt_val_job_data_open').value = data_chart_open_month;
                        document.getElementById('inpt_val_job_data_close').value = data_chart_close_month;
                        document.getElementById('btn_update_chart').click();
                    })

                var currentHost = window.location.host;
                var date_job = document.getElementById('month_year').value;
                const apiUrl = `https://${currentHost}/api/ppic/get_ppic_monitoring_Days/${date_job}`;
                axios.get(apiUrl)
                    .then(response => {
                        var data_open_days = response.data.data_open_days;
                        var data_close_days = response.data.data_close_days;
                        var data_cust_date = response.data.data_val_date;
                        document.getElementById('inpt_job_open_days').value = data_open_days;
                        document.getElementById('inpt_job_close_days').value = data_close_days;
                        document.getElementById('inpt_val_date').value = data_cust_date;
                        document.getElementById('btn_update_cust_date').click();
                    })
            }

            function updateMonthDept() {
                var currentHost = window.location.host;
                var month = document.getElementById('selectMonth').value;
                const apiMonthDept = `https://${currentHost}/api/ppic/get_data_month_departement/${month}`;
                axios.get(apiMonthDept)
                    .then(response => {
                        var data_dept = response.data.data_dept;
                        var data_open_job = response.data.data_open_job;
                        var data_close_job = response.data.data_close_job;
                        document.getElementById('inpt_data_departement').value = data_dept;
                        document.getElementById('inpt_data_job_open').value = data_open_job;
                        document.getElementById('inpt_data_job_close').value = data_close_job;
                        document.getElementById('btn_update_dept').click();
                    })
            }
            function updateDayDept() {
                var currentHost = window.location.host;
                var day = document.getElementById('selectDay').value;
                const apiMonthDept = `https://${currentHost}/api/ppic/get_data_day_departement/${day}`;
                axios.get(apiMonthDept)
                    .then(response => {
                        var data_dept_date = response.data.data_dept_date;
                        var data_open_job_date = response.data.data_open_job_date;
                        var data_close_job_date = response.data.data_close_job_date;
                        document.getElementById('inpt_data_departement_date').value = data_dept_date;
                        document.getElementById('inpt_data_job_open_date').value = data_open_job_date;
                        document.getElementById('inpt_data_job_close_date').value = data_close_job_date;
                        document.getElementById('btn_update_dept_date').click();
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
                ChartRecall1(jobOpen, jobCloseData) {
                    const open = jobOpen.split(',').map(Number);
                    const close = jobCloseData.split(',').map(Number);

                    this.BoxChart2.updateSeries([{
                        name: 'Job Open',
                        data: open
                    },
                    {
                        name: 'Job Close',
                        data: close
                    },
                    ]);
                },

                ChartRecall5(dataOpen, dataClose, categoriesData) {
                    const open = dataOpen.split(',').map(Number);
                    const close = dataClose.split(',').map(Number);
                    const categories = categoriesData.split(',');

                    this.BoxChart5.updateOptions({
                        xaxis: {
                            categories: categories
                        }
                    });

                    this.BoxChart5.updateSeries([{
                        name: 'Job Open',
                        data: open
                    },
                    {
                        name: 'Job Close',
                        data: close
                    }
                    ]);
                },

                ChartRecall6(dataDept, dataOpen, dataClose) {
                    const dept = dataDept.split(',');
                    const open = dataOpen.split(',').map(Number);
                    const close = dataClose.split(',').map(Number);

                    if (this.BoxChart6.el) {
                        if (this.boxChartClickHandler) {
                            this.BoxChart6.el.removeEventListener('click', this.boxChartClickHandler);
                        }
                    }

                    this.BoxChart6.updateOptions({
                        xaxis: {
                            categories: dept,
                            labels: {
                                style: {
                                    cssClass: 'cursor-pointer'
                                }
                            }
                        },
                        chart: {
                            events: {
                                click: (event, chartContext, config) => {
                                    if (config.dataPointIndex !== -1) {
                                        const selectedDept = dept[config.dataPointIndex];
                                        this.fetchDepartmentData(selectedDept);
                                    }
                                }
                            },
                        },
                        states: {
                            hover: {
                                filter: {
                                    type: 'darken',
                                    value: 0.85
                                }
                            }
                        }
                    });

                    this.BoxChart6.updateSeries([{
                        name: 'Job Open',
                        type: 'bar',
                        data: open
                    },
                    {
                        name: 'Job Close',
                        type: 'line',
                        data: close
                    }
                    ]);

                    setTimeout(() => {
                        const xaxisLabels = this.BoxChart6.el.querySelectorAll('.apexcharts-xaxis-label');

                        xaxisLabels.forEach((label, index) => {
                            label.style.cursor = 'pointer';

                            label.removeEventListener('click', label._tempClickHandler);

                            label._tempClickHandler = () => {
                                const selectedDept = dept[index];
                                this.fetchDepartmentData(selectedDept);
                            };

                            label.addEventListener('click', label._tempClickHandler);
                        });
                    }, 500);
                },

                fetchDepartmentData(department) {
                    const monthValue = $('#month_id').val();

                    if (!monthValue) {
                        console.warn('Month is not selected.');
                        return;
                    }

                    $('#total_issue_doc').text('...');
                    $('#total_open_doc').text('...');
                    $('#total_close_doc').text('...');

                    const departmentMapping = {
                        'ASSY': 'ASY',
                        'STAMPING': 'STP',
                        'SUBCONT': 'SBC',
                        'REPACKING': 'RPC'
                    };

                    const searchPattern = departmentMapping[department] || department;

                    window.selectedDepartment = searchPattern;

                    $.ajax({
                        url: `https://${window.location.host}/api/ppic/get_ppic_monitoring_table_close_open/${monthValue}`,
                        type: 'GET',
                        data: {
                            department: searchPattern,
                            displayName: department
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.error) {
                                console.error('Error from server:', data.error);
                                $('#total_issue_doc').text('Error');
                                $('#total_open_doc').text('Error');
                                $('#total_close_doc').text('Error');
                                return;
                            }

                            const sumJoNumber = Array.isArray(data.data_jo_number) ?
                                data.data_jo_number.reduce((sum, val) => sum + (parseInt(val) || 0), 0) :
                                0;

                            const sumJobOpen = Array.isArray(data.data_job_open) ?
                                data.data_job_open.reduce((sum, val) => sum + (parseInt(val) || 0), 0) :
                                0;

                            const sumJobClose = Array.isArray(data.data_job_close) ?
                                data.data_job_close.reduce((sum, val) => sum + (parseInt(val) || 0), 0) :
                                0;
                            var jobopn = sumJoNumber - sumJobClose;
                            $('#total_issue_doc').text(sumJoNumber);
                            $('#total_open_doc').text(jobopn);
                            $('#total_close_doc').text(sumJobClose);

                            $('#current_department').text(department || 'All');

                            if (window.monitoringTable) {
                                window.monitoringTable.ajax.reload();
                            } else {
                                window.monitoringTable = detail_table();
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching PPIC data:', error);
                            console.error('Status:', status);
                            console.error('Response:', xhr.responseText);

                            $('#total_issue_doc').text('N/A');
                            $('#total_open_doc').text('N/A');
                            $('#total_close_doc').text('N/A');

                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                alert('Error: ' + xhr.responseJSON.error);
                            }
                        }
                    });
                },
                ChartRecall7(dataDept, dataOpen, dataClose) {
                    const dept = dataDept.split(',');
                    const open = dataOpen.split(',').map(Number);
                    const close = dataClose.split(',').map(Number);

                    if (this.BoxChart7.el) {
                        if (this.boxChartClickHandler) {
                            this.BoxChart7.el.removeEventListener('click', this.boxChartClickHandler);
                        }
                    }

                    this.BoxChart7.updateOptions({
                        xaxis: {
                            categories: dept,
                            labels: {
                                style: {
                                    cssClass: 'cursor-pointer'
                                }
                            }
                        },
                        chart: {
                            events: {
                                click: (event, chartContext, config) => {
                                    if (config.dataPointIndex !== -1) {
                                        const selectedDept = dept[config.dataPointIndex];
                                        this.fetchDepartmentData(selectedDept);
                                    }
                                }
                            },
                        },
                        states: {
                            hover: {
                                filter: {
                                    type: 'darken',
                                    value: 0.85
                                }
                            }
                        }
                    });

                    this.BoxChart7.updateSeries([{
                        name: 'Job Open',
                        type: 'bar',
                        data: open
                    },
                    {
                        name: 'Job Close',
                        type: 'line',
                        data: close
                    }
                    ]);

                    setTimeout(() => {
                        const xaxisLabels = this.BoxChart7.el.querySelectorAll('.apexcharts-xaxis-label');

                        xaxisLabels.forEach((label, index) => {
                            label.style.cursor = 'pointer';

                            label.removeEventListener('click', label._tempClickHandler);

                            label._tempClickHandler = () => {
                                const selectedDept = dept[index];
                                this.fetchDepartmentData(selectedDept);
                            };

                            label.addEventListener('click', label._tempClickHandler);
                        });
                    }, 500);
                },

                fetchDepartmentData(department) {
                    const monthValue = $('#month_id').val();

                    if (!monthValue) {
                        console.warn('Month is not selected.');
                        return;
                    }

                    $('#total_issue_doc').text('...');
                    $('#total_open_doc').text('...');
                    $('#total_close_doc').text('...');

                    const departmentMapping = {
                        'ASSY': 'ASY',
                        'STAMPING': 'STP',
                        'SUBCONT': 'SBC',
                        'REPACKING': 'RPC'
                    };

                    const searchPattern = departmentMapping[department] || department;

                    window.selectedDepartment = searchPattern;

                    $.ajax({
                        url: `https://${window.location.host}/api/ppic/get_ppic_monitoring_table_close_open/${monthValue}`,
                        type: 'GET',
                        data: {
                            department: searchPattern,
                            displayName: department
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.error) {
                                console.error('Error from server:', data.error);
                                $('#total_issue_doc').text('Error');
                                $('#total_open_doc').text('Error');
                                $('#total_close_doc').text('Error');
                                return;
                            }

                            const sumJoNumber = Array.isArray(data.data_jo_number) ?
                                data.data_jo_number.reduce((sum, val) => sum + (parseInt(val) || 0), 0) :
                                0;

                            const sumJobOpen = Array.isArray(data.data_job_open) ?
                                data.data_job_open.reduce((sum, val) => sum + (parseInt(val) || 0), 0) :
                                0;

                            const sumJobClose = Array.isArray(data.data_job_close) ?
                                data.data_job_close.reduce((sum, val) => sum + (parseInt(val) || 0), 0) :
                                0;

                            $('#total_issue_doc').text(sumJoNumber);
                            $('#total_open_doc').text(sumJobOpen);
                            $('#total_close_doc').text(sumJobClose);

                            $('#current_department').text(department || 'All');

                            if (window.monitoringTable) {
                                window.monitoringTable.ajax.reload();
                            } else {
                                window.monitoringTable = detail_table();
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching PPIC data:', error);
                            console.error('Status:', status);
                            console.error('Response:', xhr.responseText);

                            $('#total_issue_doc').text('N/A');
                            $('#total_open_doc').text('N/A');
                            $('#total_close_doc').text('N/A');

                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                alert('Error: ' + xhr.responseJSON.error);
                            }
                        }
                    });
                },

                renderCharts() {
                    this.BoxChart2 = new ApexCharts(this.$refs.BoxChart2, this.BoxChart2Options);
                    this.BoxChart2.render();

                    this.BoxChart5 = new ApexCharts(this.$refs.BoxChart5, this.BoxChart5Options);
                    this.BoxChart5.render();

                    this.BoxChart6 = new ApexCharts(this.$refs.BoxChart6, this.BoxChart6Options);
                    this.BoxChart6.render();

                    this.BoxChart7 = new ApexCharts(this.$refs.BoxChart7, this.BoxChart7Options);
                    this.BoxChart7.render();

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
                            name: 'Job Open',
                            type: 'line',
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                        },
                        {
                            name: 'Job Close',
                            type: 'bar',
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                        }
                        ],
                        chart: {
                            height: 260,
                            type: 'line',
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: (value) => {
                                if (value >= 1000000000) return (Math.floor(value / 100000000) / 10).toFixed(1) + "B";
                                if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(1) + "M";
                                if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(1) + "K";
                                return value;
                            }
                        },
                        stroke: {
                            width: [2, 0],
                            curve: 'smooth'
                        },
                        colors: ['#f4a261', '#2a9d8f'],
                        dropShadow: {
                            enabled: true,
                            blur: 3,
                            color: '#515365',
                            opacity: 0.2
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
                        yaxis: {
                            labels: {
                                formatter: (value) => {
                                    if (value >= 1000000000) return (Math.floor(value / 100000000) / 10).toFixed(1) + "B";
                                    if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(1) + "M";
                                    if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(1) + "K";
                                    return value;
                                }
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
                        tooltip: {
                            y: {
                                formatter: (value) => {
                                    return new Intl.NumberFormat('id-ID', {}).format(value);
                                }
                            }
                        },
                        fill: {
                            type: ['solid', 'gradient'],
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
                            name: 'Job Open',
                            type: 'line',
                            data: []
                        },
                        {
                            name: 'Job Close',
                            type: 'bar',
                            data: []
                        }
                        ],
                        chart: {
                            height: 260,
                            type: 'line',
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: (value) => {
                                if (value >= 1000000000) return (Math.floor(value / 100000000) / 10).toFixed(1) + "B";
                                if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(1) + "M";
                                if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(1) + "K";
                                return value;
                            }
                        },
                        stroke: {
                            width: [2, 0],
                            curve: 'smooth'
                        },
                        colors: ['#f4a261', '#2a9d8f'],
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
                        yaxis: {
                            labels: {
                                formatter: (value) => {
                                    if (value >= 1000000000) return (Math.floor(value / 100000000) / 10).toFixed(1) + "B";
                                    if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(1) + "M";
                                    if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(1) + "K";
                                    return value;
                                }
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
                        tooltip: {
                            y: {
                                formatter: (value) => {
                                    return new Intl.NumberFormat('id-ID', {}).format(value);
                                }
                            }
                        },
                        fill: {
                            type: ['solid', 'gradient'],
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
                        series: [],
                        chart: {
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
                            width: [0, 2],
                            curve: 'smooth',
                            colors: ['#2a9d8f', '#f4a261'],
                        },
                        colors: ['#2a9d8f', '#f4a261'],
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
                get BoxChart7Options() {
                    return {
                        series: [],
                        chart: {
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
                            width: [0, 2],
                            curve: 'smooth',
                            colors: ['#2a9d8f', '#f4a261'],
                        },
                        colors: ['#2a9d8f', '#f4a261'],
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
