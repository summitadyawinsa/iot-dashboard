<x-layout.default>
    <script defer src="/assets/js/apexcharts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <style>
        #bymonth::-webkit-calendar-picker-indicator,
        #alldept_last::-webkit-calendar-picker-indicator,
        #bymonth_dpc::-webkit-calendar-picker-indicator,
        #bymonth_hrga::-webkit-calendar-picker-indicator,
        #bymonth_tmc::-webkit-calendar-picker-indicator,
        #bymonth_ict::-webkit-calendar-picker-indicator,
        #bymonth_pur::-webkit-calendar-picker-indicator,
        #bymonth_assy::-webkit-calendar-picker-indicator,
        #alldept_bymonth::-webkit-calendar-picker-indicator,
        #weekly_month_select::-webkit-calendar-picker-indicator,
        #bymonth_qua::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        select[name="detail_findings_table_length"] {
            background-color: #4B5563;
            width: 50px;
            font-size: 9pt;
            margin: 10px;
        }

        .dataTables_paginate {
            margin-top: 10px
        }
    </style>
    <div x-data="analytics">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>QMS</span>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>Genba Dashboard</span>
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
                <div class="panel h-full sm:col-span-12 xl:col-span-12">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-4">
                            <span class="text-xl font-bold"> Overall Finding Status</span>
                            <span></span>
                            <span></span>
                            <input id="alldept_bymonth" type="month" class="form-input bg-gray-500 text-white"
                                value="<?= $currentMonth ?>">
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart_alldept"
                                x-on:click="() => ChartRecall5(inpt_data_open_all_dept.value, inpt_data_close_all_dept.value, inpt_data_over_all_dept.value, inpt_data_name_dept.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_data_open_all_dept" value="" hidden>
                            <input type="text" id="inpt_data_close_all_dept" value="" hidden>
                            <input type="text" id="inpt_data_over_all_dept" value="" hidden>
                            <input type="text" id="inpt_data_name_dept" value="" hidden>
                            <input id="month_year_table5" type="month" class="form-input" value=""
                                style="color: white;" hidden />
                            <div x-ref="BoxChart5" class="overflow-hidden"></div>
                        </div>
                    </div>
                    <div
                        class="relative overflow-x-auto border p-4 border-gray-300 dark:border-gray-700 sm:rounded-lg pt-5">
                        <table id="detail_findings_table"
                            class="w-full text-sm text-left text-gray-800 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-900 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        No
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Doc Num
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Findings
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Assign To
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Area Detail
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Dept
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Issued Date
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Terget Date
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Completion Date
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Status
                                    </th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
                <div class="panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-2">
                            <span class="text-xl font-bold"> Weekly Finding Status </span>
                            <input id="weekly_month_select" type="month" class="form-input bg-gray-500 text-white"
                                value="<?= $currentMonth ?>">
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart_last"
                                x-on:click="() => ChartRecall11(inpt_data_week_last.value, inpt_data_open_last.value, inpt_data_close_last.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_data_week_last" value="" hidden>
                            <input type="text" id="inpt_data_open_last" value="" hidden>
                            <input type="text" id="inpt_data_close_last" value="" hidden>
                            <div x-ref="BoxChart11" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>
                <div class="panel h-full sm:col-span-3 xl:col-span-3">
                    <div class="grid grid-cols-1 p-5 dark:text-white-light">
                        <div class="grid grid-cols-1 items-center">
                            <label for="alldept_bymonth" class="text-xm font-bold">PPIC</label>
                            <input id="alldept_bymonth" type="month" class="form-input bg-gray-800 text-white"
                                value="<?= $currentMonth ?>" / hidden>
                        </div>

                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart"
                                x-on:click="() => ChartRecall1(inpt_data_open.value, inpt_data_close.value, inpt_data_over.value)"
                                hidden>Recall and Update
                            </button>
                            <input type="text" id="inpt_data_open" value="" hidden>
                            <input type="text" id="inpt_data_close" value="" hidden>
                            <input type="text" id="inpt_data_over" value="" hidden>
                            <div x-ref="BoxChart1" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>
                <div class="panel h-full sm:col-span-3 xl:col-span-3">
                    <div class="grid grid-cols-1 p-5 dark:text-white-light">
                        <div class="grid grid-cols-2 items-center">
                            <label for="alldept_bymonth" class="text-xm font-bold">DPC</label>
                            <input id="alldept_bymonth" type="month" class="form-input bg-gray-800 text-white"
                                value="<?= $currentMonth ?>" / hidden>
                        </div>

                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart_dpc"
                                x-on:click="() => ChartRecall2(inpt_data_open_dpc.value, inpt_data_close_dpc.value, inpt_data_over_dpc.value)"
                                hidden>Recall and Update
                            </button>
                            <input type="text" id="inpt_data_open_dpc" value="" hidden>
                            <input type="text" id="inpt_data_close_dpc" value="" hidden>
                            <input type="text" id="inpt_data_over_dpc" value="" hidden>
                            <div x-ref="BoxChart2" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>
                <div class="panel h-full sm:col-span-3 xl:col-span-3">
                    <div class="grid grid-cols-1 p-5 dark:text-white-light">
                        <div class="grid grid-cols-2 items-center">
                            <label for="alldept_bymonth" class="text-xm font-bold">ASSY</label>
                            <input id="alldept_bymonth" type="month" class="form-input bg-gray-800 text-white"
                                value="<?= $currentMonth ?>" / hidden>
                        </div>

                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart_assy"
                                x-on:click="() => ChartRecall3(inpt_data_open_assy.value, inpt_data_close_assy.value, inpt_data_over_assy.value)"
                                hidden>Recall and Update
                            </button>
                            <input type="text" id="inpt_data_open_assy" value="" hidden>
                            <input type="text" id="inpt_data_close_assy" value="" hidden>
                            <input type="text" id="inpt_data_over_assy" value="" hidden>
                            <div x-ref="BoxChart3" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>
                <div class="panel h-full sm:col-span-3 xl:col-span-3">
                    <div class="grid grid-cols-1 p-5 dark:text-white-light">
                        <div class="grid grid-cols-2 items-center">
                            <label for="alldept_bymonth" class="text-xm font-bold">STP</label>
                            <input id="alldept_bymonth" type="month" class="form-input bg-gray-800 text-white"
                                value="<?= $currentMonth ?>" / hidden>
                        </div>

                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart_stp"
                                x-on:click="() => ChartRecall14(inpt_data_open_stp.value, inpt_data_close_stp.value, inpt_data_over_stp.value)"
                                hidden>Recall and Update
                            </button>
                            <input type="text" id="inpt_data_open_stp" value="" hidden>
                            <input type="text" id="inpt_data_close_stp" value="" hidden>
                            <input type="text" id="inpt_data_over_stp" value="" hidden>
                            <div x-ref="BoxChart14" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>
                <div class="panel h-full sm:col-span-3 xl:col-span-3">
                    <div class="grid grid-cols-1 p-5 dark:text-white-light">
                        <div class="grid grid-cols-2 items-center">
                            <label for="alldept_bymonth" class="text-xm font-bold">QC</label>
                            <input id="alldept_bymonth" type="month" class="form-input bg-gray-800 text-white"
                                value="<?= $currentMonth ?>" / hidden>
                        </div>

                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart_qua"
                                x-on:click="() => ChartRecall4(inpt_data_open_qua.value, inpt_data_close_qua.value, inpt_data_over_qua.value)"
                                hidden>Recall and Update
                            </button>
                            <input type="text" id="inpt_data_open_qua" value="" hidden>
                            <input type="text" id="inpt_data_close_qua" value="" hidden>
                            <input type="text" id="inpt_data_over_qua" value="" hidden>
                            <div x-ref="BoxChart4" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>
                <div class="panel h-full sm:col-span-3 xl:col-span-3">
                    <div class="grid grid-cols-1 p-5 dark:text-white-light">
                        <div class="grid grid-cols-2 items-center">
                            <label for="alldept_bymonth" class="text-xm font-bold">PUR</label>
                            <input id="alldept_bymonth" type="month" class="form-input bg-gray-800 text-white"
                                value="<?= $currentMonth ?>" / hidden>
                        </div>

                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart_pur"
                                x-on:click="() => ChartRecall6(inpt_data_open_pur.value, inpt_data_close_pur.value, inpt_data_over_pur.value)"
                                hidden>Recall and Update
                            </button>
                            <input type="text" id="inpt_data_open_pur" value="" hidden>
                            <input type="text" id="inpt_data_close_pur" value="" hidden>
                            <input type="text" id="inpt_data_over_pur" value="" hidden>
                            <div x-ref="BoxChart6" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>

                <div class="panel h-full sm:col-span-3 xl:col-span-3">
                    <div class="grid grid-cols-1 p-5 dark:text-white-light">
                        <div class="grid grid-cols-1 items-center">
                            <label for="alldept_bymonth" class="text-xm font-bold">TMC</label>
                            <input id="alldept_bymonth" type="month" class="form-input bg-gray-800 text-white"
                                value="<?= $currentMonth ?>" / hidden>
                        </div>

                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart_tmc"
                                x-on:click="() => ChartRecall7(inpt_data_open_tmc.value, inpt_data_close_tmc.value, inpt_data_over_tmc.value)"
                                hidden>Recall and Update
                            </button>
                            <input type="text" id="inpt_data_open_tmc" value="" hidden>
                            <input type="text" id="inpt_data_close_tmc" value="" hidden>
                            <input type="text" id="inpt_data_over_tmc" value="" hidden>
                            <div x-ref="BoxChart7" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>



                <div class="panel h-full sm:col-span-3 xl:col-span-3">
                    <div class="grid grid-cols-1 p-5 dark:text-white-light">
                        <div class="grid grid-cols-1 items-center">
                            <label for="alldept_bymonth" class="text-xm font-bold">HRGA</label>
                            <input id="alldept_bymonth" type="month" class="form-input bg-gray-800 text-white"
                                value="<?= $currentMonth ?>" / hidden>
                        </div>

                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart_hrga"
                                x-on:click="() => ChartRecall9(inpt_data_open_hrga.value, inpt_data_close_hrga.value, inpt_data_over_hrga.value)"
                                hidden>Recall and Update
                            </button>
                            <input type="text" id="inpt_data_open_hrga" value="" hidden>
                            <input type="text" id="inpt_data_close_hrga" value="" hidden>
                            <input type="text" id="inpt_data_over_hrga" value="" hidden>
                            <div x-ref="BoxChart9" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>

                <div class="panel h-full sm:col-span-3 xl:col-span-3">
                    <div class="grid grid-cols-1 p-5 dark:text-white-light">
                        <div class="grid grid-cols-1 items-center">
                            <label for="alldept_bymonth" class="text-xm font-bold">ICT</label>
                            <input id="alldept_bymonth" type="month" class="form-input bg-gray-800 text-white"
                                value="<?= $currentMonth ?>" / hidden>
                        </div>

                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart_ict"
                                x-on:click="() => ChartRecall10(inpt_data_open_ict.value, inpt_data_close_ict.value, inpt_data_over_ict.value)"
                                hidden>Recall and Update
                            </button>
                            <input type="text" id="inpt_data_open_ict" value="" hidden>
                            <input type="text" id="inpt_data_close_ict" value="" hidden>
                            <input type="text" id="inpt_data_over_ict" value="" hidden>
                            <div x-ref="BoxChart10" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>

                <div class="panel h-full sm:col-span-3 xl:col-span-3">
                    <div class="grid grid-cols-1 p-5 dark:text-white-light">
                        <div class="grid grid-cols-2 items-center">
                            <label for="alldept_bymonth" class="text-xm font-bold">TMF</label>
                            <input id="alldept_bymonth" type="month" class="form-input bg-gray-800 text-white"
                                value="<?= $currentMonth ?>" / hidden>
                        </div>

                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart_tmf"
                                x-on:click="() => ChartRecall12(inpt_data_open_tmf.value, inpt_data_close_tmf.value, inpt_data_over_tmf.value)"
                                hidden>Recall and Update
                            </button>
                            <input type="text" id="inpt_data_open_tmf" value="" hidden>
                            <input type="text" id="inpt_data_close_tmf" value="" hidden>
                            <input type="text" id="inpt_data_over_tmf" value="" hidden>
                            <div x-ref="BoxChart12" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>

                <div class="panel h-full sm:col-span-3 xl:col-span-3">
                    <div class="grid grid-cols-1 p-5 dark:text-white-light">
                        <div class="grid grid-cols-2 items-center">
                            <label for="alldept_bymonth" class="text-xm font-bold">NPC</label>
                            <input id="alldept_bymonth" type="month" class="form-input bg-gray-800 text-white"
                                value="<?= $currentMonth ?>" / hidden>
                        </div>

                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart_npc"
                                x-on:click="() => ChartRecall13(inpt_data_open_npc.value, inpt_data_close_npc.value, inpt_data_over_npc.value)"
                                hidden>Recall and Update
                            </button>
                            <input type="text" id="inpt_data_open_npc" value="" hidden>
                            <input type="text" id="inpt_data_close_npc" value="" hidden>
                            <input type="text" id="inpt_data_over_npc" value="" hidden>
                            <div x-ref="BoxChart13" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>

                <div class="panel h-full sm:col-span-3 xl:col-span-3">
                    <div class="grid grid-cols-1 p-5 dark:text-white-light">
                        <div class="grid grid-cols-2 items-center">
                            <label for="alldept_bymonth" class="text-xm font-bold">MTC</label>
                            <input id="alldept_bymonth" type="month" class="form-input bg-gray-800 text-white"
                                value="<?= $currentMonth ?>" / hidden>
                        </div>

                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart_mtc"
                                x-on:click="() => ChartRecall15(inpt_data_open_mtc.value, inpt_data_close_mtc.value, inpt_data_over_mtc.value)"
                                hidden>Recall and Update
                            </button>
                            <input type="text" id="inpt_data_open_mtc" value="" hidden>
                            <input type="text" id="inpt_data_close_mtc" value="" hidden>
                            <input type="text" id="inpt_data_over_mtc" value="" hidden>
                            <div x-ref="BoxChart15" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function detail_table_findings() {
                var selectYear = $('#alldept_bymonth').val();

                detailTableProfitByMonths = $("#detail_findings_table").DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: false,
                    pageLength: 5,
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
                        url: `https://${window.location.host}/api/qms/findings_detail_table`,
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
                            console.error('AJAX Error (detail_table_findings):', error, thrown,
                                xhr
                                .responseText);
                        }
                    },
                    columns: [{
                            data: null,
                            width: '5px',
                            render: function(data, type, row, meta) {
                                return meta.row + 1 + meta.settings._iDisplayStart;
                            }
                        },
                         {
                            data: 'DocNum',
                            width: '50px',

                        }, {
                            data: 'findings',
                            width: '250px',

                        },
                        {
                            data: 'asign_to_name',
                            width: '50px',

                        },
                        {
                            data: 'area_detail',
                            width: '100px',

                        },
                        {
                            data: 'asign_to_dept',
                            width: '50px',

                        },
                        {
                            data: 'created_at',
                            width: '30px',

                        },
                        {
                            data: 'due_date',
                            width: '30px',

                        },
                        {
                            data: 'complete_date',
                            width: '30px',

                        },
                        {
                            data: 'status',
                            width: '40px',
                            render: function(data, type, row) {
                                if (type === 'display') {
                                    return data === 'Close' ?
                                        '<span class="badge badge-outline-success">Close</span>' :
                                        '<span class="badge badge-outline-warning">Open</span>';
                                }
                                return data;
                            }

                        },
                    ],
                });

                setInterval(function() {
                    detailTableProfitByMonths.ajax.reload(null, false);
                }, 30000);


                return detailTableProfitByMonths;
            }

            function setDefaultData() {

                function fetchData(url, callback) {
                    axios.get(url)
                        .then(response => callback(response.data))
                }
                const currentHost = window.location.host;

                const selectMonthAllDept = document.getElementById('alldept_bymonth').value;
                const apiUrlDept = `https://${currentHost}/api/qms/get_all_dept_open_overdue/${selectMonthAllDept}`;
                axios.get(apiUrlDept)
                    .then(response => {
                        var data_total_open = response.data.data_total_open;
                        var data_total_close = response.data.data_total_close;
                        var data_total_overdue = response.data.data_total_overdue;
                        var data_name_dept = response.data.data_name_dept;
                        document.getElementById('inpt_data_open_all_dept').value = data_total_open;
                        document.getElementById('inpt_data_close_all_dept').value = data_total_close;
                        document.getElementById('inpt_data_over_all_dept').value = data_total_overdue;
                        document.getElementById('inpt_data_name_dept').value = data_name_dept;
                        document.getElementById('btn_update_chart_alldept').click();
                    })
                    .catch(error => {
                        console.error('Error fetching default data:', error);
                    });

                const selectMonthLast = document.getElementById('weekly_month_select').value;
                const apiUrlLast = `https://${currentHost}/api/qms/get_all_dept_open_remain/${selectMonthLast}`;
                axios.get(apiUrlLast)
                    .then(response => {
                        var data_week = response.data.data_week;
                        var data_total_open = response.data.data_total_open;
                        var data_total_close = response.data.data_total_close;
                        document.getElementById('inpt_data_week_last').value = data_week;
                        document.getElementById('inpt_data_open_last').value = data_total_open;
                        document.getElementById('inpt_data_close_last').value = data_total_close;
                        document.getElementById('btn_update_chart_last').click();
                    })
                    .catch(error => {
                        console.error('Error fetching default data:', error);
                    });

                const selectYearly = document.getElementById('alldept_bymonth').value;
                const apiUrlYearly = `https://${currentHost}/api/qms/get_total_genba_dept/${selectYearly}`;
                axios.get(apiUrlYearly)
                    .then(response => {
                        var total_open = response.data.total_open;
                        var total_close = response.data.total_close;
                        var total_due_date = response.data.total_due_date;
                        document.getElementById('inpt_data_open').value = total_open;
                        document.getElementById('inpt_data_close').value = total_close;
                        document.getElementById('inpt_data_over').value = total_due_date;
                        document.getElementById('btn_update_chart').click();
                    })
                    .catch(error => {
                        console.error('Error fetching default data:', error);
                    });

                const byMonthDPC = document.getElementById('alldept_bymonth').value;
                const apiUrlDPC = `https://${currentHost}/api/qms/get_total_genba_dept_dpc/${byMonthDPC}`;
                axios.get(apiUrlDPC)
                    .then(response => {
                        var total_open = response.data.total_open;
                        var total_close = response.data.total_close;
                        var total_due_date = response.data.total_due_date;
                        document.getElementById('inpt_data_open_dpc').value = total_open;
                        document.getElementById('inpt_data_close_dpc').value = total_close;
                        document.getElementById('inpt_data_over_dpc').value = total_due_date;
                        document.getElementById('btn_update_chart_dpc').click();
                    })
                    .catch(error => {
                        console.error('Error fetching default data:', error);
                    });


                const byMonthAssy = document.getElementById('alldept_bymonth').value;
                const apiUrlAssy = `https://${currentHost}/api/qms/get_total_genba_dept_assy/${byMonthAssy}`;
                axios.get(apiUrlAssy)
                    .then(response => {
                        var total_open = response.data.total_open;
                        var total_close = response.data.total_close;
                        var total_due_date = response.data.total_due_date;

                        document.getElementById('inpt_data_open_assy').value = total_open;
                        document.getElementById('inpt_data_close_assy').value = total_close;
                        document.getElementById('inpt_data_over_assy').value = total_due_date;
                        document.getElementById('btn_update_chart_assy').click();
                    })
                    .catch(error => {
                        console.error('Error fetching default data:', error);
                    });


                const byMonthSTP = document.getElementById('alldept_bymonth').value;
                const apiUrlSTP = `https://${currentHost}/api/qms/get_total_genba_dept_stp/${byMonthSTP}`;
                axios.get(apiUrlSTP)
                    .then(response => {
                        var total_open = response.data.total_open;
                        var total_close = response.data.total_close;
                        var total_due_date = response.data.total_due_date;

                        document.getElementById('inpt_data_open_stp').value = total_open;
                        document.getElementById('inpt_data_close_stp').value = total_close;
                        document.getElementById('inpt_data_over_stp').value = total_due_date;
                        document.getElementById('btn_update_chart_stp').click();
                    })
                    .catch(error => {
                        console.error('Error fetching default data:', error);
                    });

                const byMonthQUA = document.getElementById('alldept_bymonth').value;
                const apiUrlQUA = `https://${currentHost}/api/qms/get_total_genba_dept_qua/${byMonthQUA}`;

                axios.get(apiUrlQUA)
                    .then(response => {
                        var total_open = response.data.total_open;
                        var total_close = response.data.total_close;
                        var total_due_date = response.data.total_due_date;

                        document.getElementById('inpt_data_open_qua').value = total_open;
                        document.getElementById('inpt_data_close_qua').value = total_close;
                        document.getElementById('inpt_data_over_qua').value = total_due_date;
                        document.getElementById('btn_update_chart_qua').click();
                    });

                const byMonthPur = document.getElementById('alldept_bymonth').value;
                const apiUrlPur = `https://${currentHost}/api/qms/get_total_genba_dept_pur/${byMonthPur}`;
                axios.get(apiUrlPur)
                    .then(response => {
                        var total_open = response.data.total_open;
                        var total_close = response.data.total_close;
                        var total_due_date = response.data.total_due_date;
                        document.getElementById('inpt_data_open_pur').value = total_open;
                        document.getElementById('inpt_data_close_pur').value = total_close;
                        document.getElementById('inpt_data_over_pur').value = total_due_date;
                        document.getElementById('btn_update_chart_pur').click();
                    });

                const byMonthTMC = document.getElementById('alldept_bymonth').value;
                const apiUrlTMC = `https://${currentHost}/api/qms/get_total_genba_dept_tmc/${byMonthTMC}`;

                axios.get(apiUrlTMC)
                    .then(response => {
                        var total_open = response.data.total_open;
                        var total_close = response.data.total_close;
                        var total_due_date = response.data.total_due_date;

                        document.getElementById('inpt_data_open_tmc').value = total_open;
                        document.getElementById('inpt_data_close_tmc').value = total_close;
                        document.getElementById('inpt_data_over_tmc').value = total_due_date;
                        document.getElementById('btn_update_chart_tmc').click();
                    });

                const byMonthHRGA = document.getElementById('alldept_bymonth').value;
                const apiUrlHRGA = `https://${currentHost}/api/qms/get_total_genba_dept_hrga/${byMonthHRGA}`;

                axios.get(apiUrlHRGA)
                    .then(response => {
                        var total_open = response.data.total_open;
                        var total_close = response.data.total_close;
                        var total_due_date = response.data.total_due_date;

                        document.getElementById('inpt_data_open_hrga').value = total_open;
                        document.getElementById('inpt_data_close_hrga').value = total_close;
                        document.getElementById('inpt_data_over_hrga').value = total_due_date;
                        document.getElementById('btn_update_chart_hrga').click();
                    });

                const byMonthTMF = document.getElementById('alldept_bymonth').value;
                const apiUrlTMF = `https://${currentHost}/api/qms/get_total_genba_dept_tmf/${byMonthTMF}`;

                axios.get(apiUrlTMF)
                    .then(response => {
                        var total_open = response.data.total_open;
                        var total_close = response.data.total_close;
                        var total_due_date = response.data.total_due_date;

                        document.getElementById('inpt_data_open_tmf').value = total_open;
                        document.getElementById('inpt_data_close_tmf').value = total_close;
                        document.getElementById('inpt_data_over_tmf').value = total_due_date;
                        document.getElementById('btn_update_chart_tmf').click();
                    });

                const byMonthICT = document.getElementById('alldept_bymonth').value;
                const apiUrlICT = `https://${currentHost}/api/qms/get_total_genba_dept_ict/${byMonthICT}`;

                axios.get(apiUrlICT)
                    .then(response => {
                        var total_open = response.data.total_open;
                        var total_close = response.data.total_close;
                        var total_due_date = response.data.total_due_date;

                        document.getElementById('inpt_data_open_ict').value = total_open;
                        document.getElementById('inpt_data_close_ict').value = total_close;
                        document.getElementById('inpt_data_over_ict').value = total_due_date;
                        document.getElementById('btn_update_chart_ict').click();
                    });

                const byMonthNPC = document.getElementById('alldept_bymonth').value;
                const apiUrlNPC = `https://${currentHost}/api/qms/get_total_genba_dept_npc/${byMonthNPC}`;

                axios.get(apiUrlNPC)
                    .then(response => {
                        var total_open = response.data.total_open;
                        var total_close = response.data.total_close;
                        var total_due_date = response.data.total_due_date;

                        document.getElementById('inpt_data_open_npc').value = total_open;
                        document.getElementById('inpt_data_close_npc').value = total_close;
                        document.getElementById('inpt_data_over_npc').value = total_due_date;
                        document.getElementById('btn_update_chart_npc').click();
                    });

                const byMonthMTC = document.getElementById('alldept_bymonth').value;
                const apiUrlMTC = `https://${currentHost}/api/qms/get_total_genba_dept_mtc/${byMonthMTC}`;

                axios.get(apiUrlMTC)
                    .then(response => {
                        var total_open = response.data.total_open;
                        var total_close = response.data.total_close;
                        var total_due_date = response.data.total_due_date;

                        document.getElementById('inpt_data_open_mtc').value = total_open;
                        document.getElementById('inpt_data_close_mtc').value = total_close;
                        document.getElementById('inpt_data_over_mtc').value = total_due_date;
                        document.getElementById('btn_update_chart_mtc').click();
                    });

            }

            setDefaultData();
            detail_table_findings();



            document.getElementById('alldept_bymonth').addEventListener('change', function() {
                updateOpenOverdueDept();
                updateChartYear();
                updateChartDPC();
                updateChartAssy();
                updateChartQUA();
                updateChartPUR();
                updateChartTMC();
                updateChartHRGA();
                updateChartTMF();
                updateChartICT();
                updateChartNPC();
                updateChartMTC();

            });

            document.getElementById('alldept_bymonth').addEventListener('change', function() {
                detail_table_findings();
            });
            document.getElementById('weekly_month_select').addEventListener('change', function() {
                updateOpenLast();
            });

        });

        function updateOpenOverdueDept() {

            function fetchData(url, callback) {
                axios.get(url)
                    .then(response => callback(response.data))
            }
            const currentHost = window.location.host;
            const selectMonthAllDept = document.getElementById('alldept_bymonth').value;
            const apiUrlDept = `https://${currentHost}/api/qms/get_all_dept_open_overdue/${selectMonthAllDept}`;
            axios.get(apiUrlDept)
                .then(response => {
                    var data_total_open = response.data.data_total_open;
                    var data_total_close = response.data.data_total_close;
                    var data_total_overdue = response.data.data_total_overdue;
                    var data_name_dept = response.data.data_name_dept;
                    document.getElementById('inpt_data_open_all_dept').value = data_total_open;
                    document.getElementById('inpt_data_close_all_dept').value = data_total_close;
                    document.getElementById('inpt_data_over_all_dept').value = data_total_overdue;
                    document.getElementById('inpt_data_name_dept').value = data_name_dept;
                    document.getElementById('btn_update_chart_alldept').click();
                })
                .catch(error => {
                    console.error('Error fetching default data:', error);
                });
        }

        function updateOpenLast() {

            function fetchData(url, callback) {
                axios.get(url)
                    .then(response => callback(response.data))
            }
            const currentHost = window.location.host;
            const selectMonthLast = document.getElementById('weekly_month_select').value;
            const apiUrlLast = `https://${currentHost}/api/qms/get_all_dept_open_remain/${selectMonthLast}`;
            axios.get(apiUrlLast)
                .then(response => {
                    var data_week = response.data.data_week;
                    var data_total_open = response.data.data_total_open;
                    var data_total_close = response.data.data_total_close;
                    document.getElementById('inpt_data_week_last').value = data_week;
                    document.getElementById('inpt_data_open_last').value = data_total_open;
                    document.getElementById('inpt_data_close_last').value = data_total_close;
                    document.getElementById('btn_update_chart_last').click();
                })
                .catch(error => {
                    console.error('Error fetching default data:', error);
                });
        }

        function updateChartYear() {

            function fetchData(url, callback) {
                axios.get(url)
                    .then(response => callback(response.data))
            }
            const currentHost = window.location.host;
            const selectYearly = document.getElementById('alldept_bymonth').value;
            const apiUrlYearly = `https://${currentHost}/api/qms/get_total_genba_dept/${selectYearly}`;
            axios.get(apiUrlYearly)
                .then(response => {
                    var total_open = response.data.total_open;
                    var total_close = response.data.total_close;
                    var total_due_date = response.data.total_due_date;
                    document.getElementById('inpt_data_open').value = total_open;
                    document.getElementById('inpt_data_close').value = total_close;
                    document.getElementById('inpt_data_over').value = total_due_date;
                    document.getElementById('btn_update_chart').click();
                })
                .catch(error => {
                    console.error('Error fetching default data:', error);
                });
        }



        function updateChartDPC() {

            function fetchData(url, callback) {
                axios.get(url)
                    .then(response => callback(response.data))
            }
            const currentHost = window.location.host;
            const byMonthDPC = document.getElementById('alldept_bymonth').value;
            const apiUrlDPC = `https://${currentHost}/api/qms/get_total_genba_dept_dpc/${byMonthDPC}`;
            axios.get(apiUrlDPC)
                .then(response => {
                    var total_open = response.data.total_open;
                    var total_close = response.data.total_close;
                    var total_due_date = response.data.total_due_date;
                    document.getElementById('inpt_data_open_dpc').value = total_open;
                    document.getElementById('inpt_data_close_dpc').value = total_close;
                    document.getElementById('inpt_data_over_dpc').value = total_due_date;
                    document.getElementById('btn_update_chart_dpc').click();
                })
                .catch(error => {
                    console.error('Error fetching default data:', error);
                });
        }

        function updateChartAssy() {

            function fetchData(url, callback) {
                axios.get(url)
                    .then(response => callback(response.data))
            }
            const currentHost = window.location.host;
            const byMonthAssy = document.getElementById('alldept_bymonth').value;
            const apiUrlAssy = `https://${currentHost}/api/qms/get_total_genba_dept_assy/${byMonthAssy}`;
            axios.get(apiUrlAssy)
                .then(response => {
                    var total_open = response.data.total_open;
                    var total_close = response.data.total_close;
                    var total_due_date = response.data.total_due_date;

                    document.getElementById('inpt_data_open_assy').value = total_open;
                    document.getElementById('inpt_data_close_assy').value = total_close;
                    document.getElementById('inpt_data_over_assy').value = total_due_date;
                    document.getElementById('btn_update_chart_assy').click();
                })
                .catch(error => {
                    console.error('Error fetching default data:', error);
                });
        }

        function updateChartSTP() {

            function fetchData(url, callback) {
                axios.get(url)
                    .then(response => callback(response.data))
            }
            const currentHost = window.location.host;
            const byMonthSTP = document.getElementById('alldept_bymonth').value;
            const apiUrlSTP = `https://${currentHost}/api/qms/get_total_genba_dept_stp/${byMonthSTP}`;
            axios.get(apiUrlSTP)
                .then(response => {
                    var total_open = response.data.total_open;
                    var total_close = response.data.total_close;
                    var total_due_date = response.data.total_due_date;

                    document.getElementById('inpt_data_open_stp').value = total_open;
                    document.getElementById('inpt_data_close_stp').value = total_close;
                    document.getElementById('inpt_data_over_stp').value = total_due_date;
                    document.getElementById('btn_update_chart_stp').click();
                })
                .catch(error => {
                    console.error('Error fetching default data:', error);
                });
        }

        function updateChartQUA() {

            function fetchData(url, callback) {
                axios.get(url)
                    .then(response => callback(response.data))
            }
            const currentHost = window.location.host;
            const byMonthQUA = document.getElementById('alldept_bymonth').value;
            const apiUrlQUA = `https://${currentHost}/api/qms/get_total_genba_dept_qua/${byMonthQUA}`;

            axios.get(apiUrlQUA)
                .then(response => {
                    var total_open = response.data.total_open;
                    var total_close = response.data.total_close;
                    var total_due_date = response.data.total_due_date;

                    document.getElementById('inpt_data_open_qua').value = total_open;
                    document.getElementById('inpt_data_close_qua').value = total_close;
                    document.getElementById('inpt_data_over_qua').value = total_due_date;
                    document.getElementById('btn_update_chart_qua').click();
                });
        }

        function updateChartPUR() {

            function fetchData(url, callback) {
                axios.get(url)
                    .then(response => callback(response.data))
            }
            const currentHost = window.location.host;
            const byMonthPur = document.getElementById('alldept_bymonth').value;
            const apiUrlPur = `https://${currentHost}/api/qms/get_total_genba_dept_pur/${byMonthPur}`;
            axios.get(apiUrlPur)
                .then(response => {
                    var total_open = response.data.total_open;
                    var total_close = response.data.total_close;
                    var total_due_date = response.data.total_due_date;
                    document.getElementById('inpt_data_open_pur').value = total_open;
                    document.getElementById('inpt_data_close_pur').value = total_close;
                    document.getElementById('inpt_data_over_pur').value = total_due_date;
                    document.getElementById('btn_update_chart_pur').click();
                });
        }

        function updateChartTMC() {

            function fetchData(url, callback) {
                axios.get(url)
                    .then(response => callback(response.data))
            }
            const currentHost = window.location.host;
            const byMonthTMC = document.getElementById('alldept_bymonth').value;
            const apiUrlTMC = `https://${currentHost}/api/qms/get_total_genba_dept_tmc/${byMonthTMC}`;

            axios.get(apiUrlTMC)
                .then(response => {
                    var total_open = response.data.total_open;
                    var total_close = response.data.total_close;
                    var total_due_date = response.data.total_due_date;

                    document.getElementById('inpt_data_open_tmc').value = total_open;
                    document.getElementById('inpt_data_close_tmc').value = total_close;
                    document.getElementById('inpt_data_over_tmc').value = total_due_date;
                    document.getElementById('btn_update_chart_tmc').click();
                });
        }

        function updateChartHRGA() {

            function fetchData(url, callback) {
                axios.get(url)
                    .then(response => callback(response.data))
            }
            const currentHost = window.location.host;
            const byMonthHRGA = document.getElementById('alldept_bymonth').value;
            const apiUrlHRGA = `https://${currentHost}/api/qms/get_total_genba_dept_hrga/${byMonthHRGA}`;

            axios.get(apiUrlHRGA)
                .then(response => {
                    var total_open = response.data.total_open;
                    var total_close = response.data.total_close;
                    var total_due_date = response.data.total_due_date;

                    document.getElementById('inpt_data_open_hrga').value = total_open;
                    document.getElementById('inpt_data_close_hrga').value = total_close;
                    document.getElementById('inpt_data_over_hrga').value = total_due_date;
                    document.getElementById('btn_update_chart_hrga').click();
                });
        }

        function updateChartTMF() {

            function fetchData(url, callback) {
                axios.get(url)
                    .then(response => callback(response.data))
            }
            const currentHost = window.location.host;
            const byMonthTMF = document.getElementById('alldept_bymonth').value;
            const apiUrlTMF = `https://${currentHost}/api/qms/get_total_genba_dept_tmf/${byMonthTMF}`;

            axios.get(apiUrlTMF)
                .then(response => {
                    var total_open = response.data.total_open;
                    var total_close = response.data.total_close;
                    var total_due_date = response.data.total_due_date;

                    document.getElementById('inpt_data_open_tmf').value = total_open;
                    document.getElementById('inpt_data_close_tmf').value = total_close;
                    document.getElementById('inpt_data_over_tmf').value = total_due_date;
                    document.getElementById('btn_update_chart_tmf').click();
                });
        }


        function updateChartICT() {

            function fetchData(url, callback) {
                axios.get(url)
                    .then(response => callback(response.data))
            }
            const currentHost = window.location.host;
            const byMonthICT = document.getElementById('alldept_bymonth').value;
            const apiUrlICT = `https://${currentHost}/api/qms/get_total_genba_dept_ict/${byMonthICT}`;

            axios.get(apiUrlICT)
                .then(response => {
                    var total_open = response.data.total_open;
                    var total_close = response.data.total_close;
                    var total_due_date = response.data.total_due_date;

                    document.getElementById('inpt_data_open_ict').value = total_open;
                    document.getElementById('inpt_data_close_ict').value = total_close;
                    document.getElementById('inpt_data_over_ict').value = total_due_date;
                    document.getElementById('btn_update_chart_ict').click();
                });
        }

        function updateChartNPC() {

            function fetchData(url, callback) {
                axios.get(url)
                    .then(response => callback(response.data))
            }
            const currentHost = window.location.host;
            const byMonthNPC = document.getElementById('alldept_bymonth').value;
            const apiUrlNPC = `https://${currentHost}/api/qms/get_total_genba_dept_npc/${byMonthNPC}`;

            axios.get(apiUrlNPC)
                .then(response => {
                    var total_open = response.data.total_open;
                    var total_close = response.data.total_close;
                    var total_due_date = response.data.total_due_date;

                    document.getElementById('inpt_data_open_npc').value = total_open;
                    document.getElementById('inpt_data_close_npc').value = total_close;
                    document.getElementById('inpt_data_over_npc').value = total_due_date;
                    document.getElementById('btn_update_chart_npc').click();
                });
        }

        function updateChartMTC() {

            function fetchData(url, callback) {
                axios.get(url)
                    .then(response => callback(response.data))
            }
            const currentHost = window.location.host;
            const byMonthMTC = document.getElementById('alldept_bymonth').value;
            const apiUrlMTC = `https://${currentHost}/api/qms/get_total_genba_dept_mtc/${byMonthMTC}`;

            axios.get(apiUrlMTC)
                .then(response => {
                    var total_open = response.data.total_open;
                    var total_close = response.data.total_close;
                    var total_due_date = response.data.total_due_date;

                    document.getElementById('inpt_data_open_mtc').value = total_open;
                    document.getElementById('inpt_data_close_mtc').value = total_close;
                    document.getElementById('inpt_data_over_mtc').value = total_due_date;
                    document.getElementById('btn_update_chart_mtc').click();
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

                ChartRecall5(dataOpen, dataClose, dataOver, nameDept) {

                    const open = dataOpen.split(',').map(Number);
                    const close = dataClose.split(',').map(Number);
                    const over = dataOver.split(',').map(Number);
                    const name = nameDept.split(',');

                    this.BoxChart5.updateSeries([{
                            name: "Open",
                            data: open,

                        },
                        {
                            name: "Close",
                            data: close,

                        },
                        {
                            name: "Open Overdue",
                            data: over,

                        },
                    ]);

                    this.BoxChart5.updateOptions({
                        xaxis: {
                            categories: name,
                            axisBorder: {
                                show: true,
                            },
                            axisTicks: {
                                show: 200
                            },
                            labels: {
                                style: {
                                    fontSize: '14px',
                                    fontFamily: 'Arial, sans-serif'
                                }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '12px',
                                colors: ['#F4A261', '#2A9D8F', '#E63946'],
                                fontWeight: 'bold',
                            },
                            background: {
                                enabled: true,
                                foreColor: '#fff',
                                borderRadius: 4,
                                padding: 5,
                                opacity: 0.9,
                            },
                            formatter: (value) => {
                                return new Intl.NumberFormat('id-ID', {}).format(value);
                            }
                        },
                        plotOptions: {
                            bar: {
                                dataLabels: {
                                    position: 'top'
                                },
                            }
                        }
                    });
                },

                ChartRecall11(dataWeek, dataOpen, dataClose) {

                    const week = dataWeek.split(',');
                    const open = dataOpen.split(',').map(Number);
                    const close = dataClose.split(',').map(Number);

                    this.BoxChart11.updateSeries([{
                            name: "Open",
                            type: 'bar',
                            data: open,

                        },
                        {
                            name: "Close",
                            type: 'bar',

                            data: close,

                        },
                    ]);
                    this.BoxChart11.updateOptions({
                        xaxis: {
                            categories: week,
                            axisBorder: {
                                show: true,
                                color: '#3b3f5c'
                            },
                            axisTicks: {
                                show: true
                            },
                        }
                    });

                },

                ChartRecall1(dataOpen, dataClose, dataOver) {
                    const open = dataOpen.split(',').map(Number);
                    const close = dataClose.split(',').map(Number);
                    const over = dataOver.split(',').map(Number);

                    this.BoxChart1.updateSeries([{
                            name: "Open",
                            type: 'bar',
                            data: open,

                        },
                        {
                            name: "Close",
                            type: 'bar',
                            data: close,

                        },
                        {
                            name: "Overdue",
                            type: 'bar',
                            data: over,

                        },
                    ]);
                },

                ChartRecall2(dataOpen, dataClose, dataOver) {
                    const open = dataOpen.split(',').map(Number);
                    const close = dataClose.split(',').map(Number);
                    const over = dataOver.split(',').map(Number);

                    this.BoxChart2.updateSeries([{
                            name: "Open",
                            type: 'bar',
                            data: open,

                        },
                        {
                            name: "Close",
                            type: 'bar',
                            data: close,

                        },
                        {
                            name: "Overdue",
                            type: 'bar',
                            data: over,

                        },
                    ]);
                },

                ChartRecall3(dataOpen, dataClose, dataOver) {
                    const open = dataOpen.split(',').map(Number);
                    const close = dataClose.split(',').map(Number);
                    const over = dataOver.split(',').map(Number);

                    this.BoxChart3.updateSeries([{
                            name: "Open",
                            type: 'bar',
                            data: open,

                        },
                        {
                            name: "Close",
                            type: 'bar',
                            data: close,

                        },
                        {
                            name: "Overdue",
                            type: 'bar',
                            data: over,

                        },
                    ]);
                },

                ChartRecall4(dataOpen, dataClose, dataOver) {
                    const open = dataOpen.split(',').map(Number);
                    const close = dataClose.split(',').map(Number);
                    const over = dataOver.split(',').map(Number);


                    this.BoxChart4.updateSeries([{
                            name: "Open",
                            type: 'bar',
                            data: open,

                        },
                        {
                            name: "Close",
                            type: 'bar',
                            data: close,

                        },
                        {
                            name: "Overdue",
                            type: 'bar',
                            data: over,

                        },
                    ]);
                },

                ChartRecall6(dataOpen, dataClose, dataOver) {
                    const open = dataOpen.split(',').map(Number);
                    const close = dataClose.split(',').map(Number);
                    const over = dataOver.split(',').map(Number);


                    this.BoxChart6.updateSeries([{
                            name: "Open",
                            type: 'bar',
                            data: open,

                        },
                        {
                            name: "Close",
                            type: 'bar',
                            data: close,

                        },
                        {
                            name: "Overdue",
                            type: 'bar',
                            data: over,

                        },
                    ]);
                },

                ChartRecall7(dataOpen, dataClose, dataOver) {
                    const open = dataOpen.split(',').map(Number);
                    const close = dataClose.split(',').map(Number);
                    const over = dataOver.split(',').map(Number);

                    this.BoxChart7.updateSeries([{
                            name: "Open",
                            type: 'bar',
                            data: open,

                        },
                        {
                            name: "Close",
                            type: 'bar',
                            data: close,

                        },
                        {
                            name: "Overdue",
                            type: 'bar',
                            data: over,

                        },
                    ]);
                },

                ChartRecall9(dataOpen, dataClose, dataOver) {
                    const open = dataOpen.split(',').map(Number);
                    const close = dataClose.split(',').map(Number);
                    const over = dataOver.split(',').map(Number);

                    this.BoxChart9.updateSeries([{
                            name: "Open",
                            type: 'bar',
                            data: open,

                        },
                        {
                            name: "Close",
                            type: 'bar',
                            data: close,

                        },
                        {
                            name: "Overdue",
                            type: 'bar',
                            data: over,

                        },
                    ]);
                },



                ChartRecall10(dataOpen, dataClose, dataOver) {
                    const open = dataOpen.split(',').map(Number);
                    const close = dataClose.split(',').map(Number);
                    const over = dataOver.split(',').map(Number);


                    this.BoxChart10.updateSeries([{
                            name: "Open",
                            type: 'bar',
                            data: open,

                        },
                        {
                            name: "Close",
                            type: 'bar',
                            data: close,

                        },
                        {
                            name: "Overdue",
                            type: 'bar',
                            data: over,

                        },
                    ]);
                },

                ChartRecall12(dataOpen, dataClose, dataOver) {
                    const open = dataOpen.split(',').map(Number);
                    const close = dataClose.split(',').map(Number);
                    const over = dataOver.split(',').map(Number);

                    this.BoxChart12.updateSeries([{
                            name: "Open",
                            type: 'bar',
                            data: open,

                        },
                        {
                            name: "Close",
                            type: 'bar',
                            data: close,

                        },
                        {
                            name: "Overdue",
                            type: 'bar',
                            data: over,

                        },
                    ]);
                },

                ChartRecall13(dataOpen, dataClose, dataOver) {
                    const open = dataOpen.split(',').map(Number);
                    const close = dataClose.split(',').map(Number);
                    const over = dataOver.split(',').map(Number);

                    this.BoxChart13.updateSeries([{
                            name: "Open",
                            type: 'bar',
                            data: open,

                        },
                        {
                            name: "Close",
                            type: 'bar',
                            data: close,

                        },
                        {
                            name: "Overdue",
                            type: 'bar',
                            data: over,

                        },
                    ]);
                },

                ChartRecall14(dataOpen, dataClose, dataOver) {
                    const open = dataOpen.split(',').map(Number);
                    const close = dataClose.split(',').map(Number);
                    const over = dataOver.split(',').map(Number);

                    this.BoxChart14.updateSeries([{
                            name: "Open",
                            type: 'bar',
                            data: open,

                        },
                        {
                            name: "Close",
                            type: 'bar',
                            data: close,

                        },
                        {
                            name: "Overdue",
                            type: 'bar',
                            data: over,

                        },
                    ]);
                },

                ChartRecall15(dataOpen, dataClose, dataOver) {
                    const open = dataOpen.split(',').map(Number);
                    const close = dataClose.split(',').map(Number);
                    const over = dataOver.split(',').map(Number);

                    this.BoxChart15.updateSeries([{
                            name: "Open",
                            type: 'bar',
                            data: open,

                        },
                        {
                            name: "Close",
                            type: 'bar',
                            data: close,

                        },
                        {
                            name: "Overdue",
                            type: 'bar',
                            data: over,

                        },
                    ]);
                },

                renderCharts() {

                    this.BoxChart5 = new ApexCharts(this.$refs.BoxChart5, this.BoxChart5Options);
                    this.BoxChart5.render();

                    this.BoxChart11 = new ApexCharts(this.$refs.BoxChart11, this.BoxChart11Options);
                    this.BoxChart11.render();

                    this.BoxChart1 = new ApexCharts(this.$refs.BoxChart1, this.BoxChart1Options);
                    this.BoxChart1.render();

                    this.BoxChart2 = new ApexCharts(this.$refs.BoxChart2, this.BoxChart2Options);
                    this.BoxChart2.render();

                    this.BoxChart3 = new ApexCharts(this.$refs.BoxChart3, this.BoxChart3Options);
                    this.BoxChart3.render();

                    this.BoxChart4 = new ApexCharts(this.$refs.BoxChart4, this.BoxChart4Options);
                    this.BoxChart4.render();

                    this.BoxChart6 = new ApexCharts(this.$refs.BoxChart6, this.BoxChart6Options);
                    this.BoxChart6.render();

                    this.BoxChart7 = new ApexCharts(this.$refs.BoxChart7, this.BoxChart7Options);
                    this.BoxChart7.render();

                    this.BoxChart9 = new ApexCharts(this.$refs.BoxChart9, this.BoxChart9Options);
                    this.BoxChart9.render();

                    this.BoxChart10 = new ApexCharts(this.$refs.BoxChart10, this.BoxChart10Options);
                    this.BoxChart10.render();

                    this.BoxChart12 = new ApexCharts(this.$refs.BoxChart12, this.BoxChart12Options);
                    this.BoxChart12.render();

                    this.BoxChart13 = new ApexCharts(this.$refs.BoxChart13, this.BoxChart13Options);
                    this.BoxChart13.render();

                    this.BoxChart14 = new ApexCharts(this.$refs.BoxChart14, this.BoxChart14Options);
                    this.BoxChart14.render();

                    this.BoxChart15 = new ApexCharts(this.$refs.BoxChart15, this.BoxChart15Options);
                    this.BoxChart15.render();

                },

                get BoxChart5Options() {
                    return {
                        series: [],
                        chart: {
                            type: 'bar',
                            height: 360,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                        },
                        stroke: {
                            width: [0, 0, 0],
                            curve: 'smooth'
                        },
                        colors: ['#F4A261', '#2A9D8F', '#E63946'],
                        dropShadow: {
                            enabled: true,
                            blur: 2,
                            opacity: 0.7
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
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return new Intl.NumberFormat('id-ID', {}).format(value);
                                }
                            },
                        },
                        fill: {
                            type: ['solid', 'solid', 'solid'],
                            gradient: {
                                shade: 'dark',
                                type: 'vertical',
                                shadeIntensity: 0.3,
                                inverseColors: false,
                                opacityFrom: 1,
                                opacityTo: 0.8,
                                stops: [0, 100, 100]
                            }
                        }
                    };
                },

                get BoxChart11Options() {
                    return {
                        series: [],
                        chart: {
                            height: 400,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '12px',
                                colors: ['#F4A261', '#2A9D8F'],
                                fontWeight: 'bold',
                            },
                            background: {
                                enabled: true,
                                foreColor: '#fff',
                                borderRadius: 4,
                                padding: 5,
                                opacity: 0.9,
                            },
                            formatter: (value) => {
                                return new Intl.NumberFormat('id-ID', {}).format(value);
                            }
                        },
                        stroke: {
                            width: [0, 0, 0],
                            curve: 'smooth'
                        },
                        colors: ['#F4A261', '#2A9D8F'],
                        dropShadow: {
                            enabled: true,
                            blur: 2,
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
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return new Intl.NumberFormat('id-ID', {}).format(value);
                                }
                            },
                        },
                        fill: {
                            type: ['solid', 'solid', 'solid'],
                            gradient: {
                                shade: 'dark',
                                type: 'vertical',
                                shadeIntensity: 0.3,
                                inverseColors: false,
                                opacityFrom: 1,
                                opacityTo: 0.8,
                                stops: [0, 100, 100]
                            }
                        }
                    };
                },

                get BoxChart1Options() {
                    return {
                        series: [],
                        chart: {
                            height: 400,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                            offsetY: 30,
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
                        colors: ['#F4A261', '#2A9D8F', '#be2121ff'],
                        labels: ['PPIC'],
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
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            itemMargin: {
                                horizontal: 15,
                                vertical: 40
                            }
                        },
                        yaxis: {
                            labels: {
                                show: true,
                                offsetX: 0,
                                formatter: (value) => this.formatNumber(value)
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return new Intl.NumberFormat('id-ID', {}).format(value);
                                }
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '12px',
                            },
                            fontSize: '20px',
                            formatter: (value) => {
                                return new Intl.NumberFormat('id-ID', {}).format(value);
                            }
                        },
                    };
                },

                get BoxChart2Options() {
                    return {
                        series: [],
                        chart: {
                            height: 400,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                            offsetY: 30,
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
                        colors: ['#F4A261', '#2A9D8F', '#be2121ff'],
                        labels: ['DPC'],
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
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            itemMargin: {
                                horizontal: 15,
                                vertical: 40
                            }
                        },
                        yaxis: {
                            labels: {
                                show: true,
                                offsetX: 0,
                                formatter: (value) => this.formatNumber(value)
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return new Intl.NumberFormat('id-ID', {}).format(value);
                                }
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '12px',
                            },
                            fontSize: '20px',
                            formatter: (value) => {
                                return new Intl.NumberFormat('id-ID', {}).format(value);
                            }
                        },
                    };
                },

                get BoxChart3Options() {
                    return {
                        series: [],
                        chart: {
                            height: 400,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                            offsetY: 30,
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
                        colors: ['#F4A261', '#2A9D8F', '#be2121ff'],
                        labels: ['ASSY'],
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
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            itemMargin: {
                                horizontal: 15,
                                vertical: 40
                            }
                        },
                        yaxis: {
                            labels: {
                                show: true,
                                offsetX: 0,
                                formatter: (value) => this.formatNumber(value)
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return new Intl.NumberFormat('id-ID', {}).format(value);
                                }
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '12px',
                            },
                            fontSize: '20px',
                            formatter: (value) => {
                                return new Intl.NumberFormat('id-ID', {}).format(value);
                            }
                        },
                    };
                },

                get BoxChart4Options() {
                    return {
                        series: [],
                        chart: {
                            height: 400,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                            offsetY: 30,
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
                        colors: ['#F4A261', '#2A9D8F', '#be2121ff'],
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
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            itemMargin: {
                                horizontal: 15,
                                vertical: 40
                            }
                        },
                        labels: ['QC'],
                        yaxis: {
                            labels: {
                                show: true,
                                offsetX: 0,
                                formatter: (value) => this.formatNumber(value)
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return new Intl.NumberFormat('id-ID', {}).format(value);
                                }
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '12px',
                            },
                            fontSize: '20px',
                            formatter: (value) => {
                                return new Intl.NumberFormat('id-ID', {}).format(value);
                            }
                        },
                    };
                },

                get BoxChart6Options() {
                    return {
                        series: [],
                        chart: {
                            height: 400,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                            offsetY: 30,
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
                        colors: ['#F4A261', '#2A9D8F', '#be2121ff'],
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
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            itemMargin: {
                                horizontal: 15,
                                vertical: 40
                            }
                        },
                        labels: ['PUR'],
                        yaxis: {
                            labels: {
                                show: true,
                                offsetX: 0,
                                formatter: (value) => this.formatNumber(value)
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return new Intl.NumberFormat('id-ID', {}).format(value);
                                }
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '12px',
                            },
                            fontSize: '20px',
                            formatter: (value) => {
                                return new Intl.NumberFormat('id-ID', {}).format(value);
                            }
                        },
                    };
                },
                get BoxChart7Options() {
                    return {
                        series: [],
                        chart: {
                            height: 400,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                            offsetY: 30,
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
                        colors: ['#F4A261', '#2A9D8F', '#be2121ff'],
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
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            itemMargin: {
                                horizontal: 15,
                                vertical: 40
                            }
                        },
                        xaxis: {
                            categories: ['TMC'],
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
                        },
                        labels: [''],
                        yaxis: {
                            labels: {
                                show: true,
                                offsetX: 0,
                                formatter: (value) => this.formatNumber(value)
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return new Intl.NumberFormat('id-ID', {}).format(value);
                                }
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '12px',
                            },
                            fontSize: '20px',
                            formatter: (value) => {
                                return new Intl.NumberFormat('id-ID', {}).format(value);
                            }
                        },
                    };
                },

                get BoxChart9Options() {
                    return {
                        series: [],
                        chart: {
                            height: 400,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                            offsetY: 30,
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
                        colors: ['#F4A261', '#2A9D8F', '#be2121ff'],
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
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            itemMargin: {
                                horizontal: 15,
                                vertical: 40
                            }
                        },
                        labels: ['HRGA'],
                        yaxis: {
                            labels: {
                                show: true,
                                offsetX: 0,
                                formatter: (value) => this.formatNumber(value)
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return new Intl.NumberFormat('id-ID', {}).format(value);
                                }
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '12px',
                            },
                            fontSize: '20px',
                            formatter: (value) => {
                                return new Intl.NumberFormat('id-ID', {}).format(value);
                            }
                        },
                    };
                },

                get BoxChart10Options() {
                    return {
                        series: [],
                        chart: {
                            height: 400,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                            offsetY: 30,
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
                        colors: ['#F4A261', '#2A9D8F', '#be2121ff'],
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
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            itemMargin: {
                                horizontal: 15,
                                vertical: 40
                            }
                        },
                        labels: ['ICT'],
                        yaxis: {
                            labels: {
                                show: true,
                                offsetX: 0,
                                formatter: (value) => this.formatNumber(value)
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return new Intl.NumberFormat('id-ID', {}).format(value);
                                }
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '12px',
                            },
                            fontSize: '20px',
                            formatter: (value) => {
                                return new Intl.NumberFormat('id-ID', {}).format(value);
                            }
                        },
                    };
                },
                get BoxChart12Options() {
                    return {
                        series: [],
                        chart: {
                            height: 400,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                            offsetY: 30,
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
                        colors: ['#F4A261', '#2A9D8F', '#be2121ff'],
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
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            itemMargin: {
                                horizontal: 15,
                                vertical: 40
                            }
                        },
                        labels: ['TMF'],
                        yaxis: {
                            labels: {
                                show: true,
                                offsetX: 0,
                                formatter: (value) => this.formatNumber(value)
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return new Intl.NumberFormat('id-ID', {}).format(value);
                                }
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '12px',
                            },
                            fontSize: '20px',
                            formatter: (value) => {
                                return new Intl.NumberFormat('id-ID', {}).format(value);
                            }
                        },
                    };
                },

                get BoxChart13Options() {
                    return {
                        series: [],
                        chart: {
                            height: 400,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                            offsetY: 30,
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
                        colors: ['#F4A261', '#2A9D8F', '#be2121ff'],
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
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            itemMargin: {
                                horizontal: 15,
                                vertical: 40
                            }
                        },
                        labels: ['NPC'],
                        yaxis: {
                            labels: {
                                show: true,
                                offsetX: 0,
                                formatter: (value) => this.formatNumber(value)
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return new Intl.NumberFormat('id-ID', {}).format(value);
                                }
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '12px',
                            },
                            fontSize: '20px',
                            formatter: (value) => {
                                return new Intl.NumberFormat('id-ID', {}).format(value);
                            }
                        },
                    };
                },
                get BoxChart14Options() {
                    return {
                        series: [],
                        chart: {
                            height: 400,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                            offsetY: 30,
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
                        colors: ['#F4A261', '#2A9D8F', '#be2121ff'],
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
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            itemMargin: {
                                horizontal: 15,
                                vertical: 40
                            }
                        },
                        labels: ['STP'],
                        yaxis: {
                            labels: {
                                show: true,
                                offsetX: 0,
                                formatter: (value) => this.formatNumber(value)
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return new Intl.NumberFormat('id-ID', {}).format(value);
                                }
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '12px',
                            },
                            fontSize: '20px',
                            formatter: (value) => {
                                return new Intl.NumberFormat('id-ID', {}).format(value);
                            }
                        },
                    };
                },

                get BoxChart15Options() {
                    return {
                        series: [],
                        chart: {
                            height: 400,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                            offsetY: 30,
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
                        colors: ['#F4A261', '#2A9D8F', '#be2121ff'],
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
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            itemMargin: {
                                horizontal: 15,
                                vertical: 40
                            }
                        },
                        labels: ['MTC'],
                        yaxis: {
                            labels: {
                                show: true,
                                offsetX: 0,
                                formatter: (value) => this.formatNumber(value)
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return new Intl.NumberFormat('id-ID', {}).format(value);
                                }
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '12px',
                            },
                            fontSize: '20px',
                            formatter: (value) => {
                                return new Intl.NumberFormat('id-ID', {}).format(value);
                            }
                        },
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
