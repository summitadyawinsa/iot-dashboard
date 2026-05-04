<x-layout.default>
    <link rel='stylesheet' type='text/css' href='{{ Vite::asset('resources/css/nice-select2.css') }}'>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/flatpickr.min.css') }}">
    <script src="/assets/js/flatpickr.js"></script>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/nouislider.min.css') }}">
    <script src="/assets/js/nouislider.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="/assets/css/select.css">
    <style>
        .table {
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
            padding: 8px;
            border: 2px solid white;
            color: white;
        }

        .table tbody tr {
            border-radius: 12px;
            overflow: hidden;
        }

        /* Kolom chart transparan */
        .table td:last-child {
            background-color: #0e1726 !important;
            border: 2px solid white !important
        }

        /* Atur tinggi chart agar pas */
        .chart-wrapper {
            width: 60px;
            height: 60px;
            margin: auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        canvas {
            background-color: transparent !important;
        }
    </style>
    <div x-data="form">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span id="namePage"></span>
            </li>
        </ul>

        <div class="pt-5 space-y-8">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-bold lg:text-4xl md:text-2xl dark:text-white-light">Production
                        Achievement <span id="titleCard">Realtime</span>
                    </h5>
                    <button class="btn btn-primary btn-sm" onclick="toolTips()">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="white"
                            xmlns="https://www.w3.org/2000/svg">
                            <path d="M1 21h22L12 2 1 21z" stroke="white" stroke-width="2" fill="none" />
                            <line x1="12" y1="8" x2="12" y2="14" stroke="white"
                                stroke-width="2" />
                            <circle cx="12" cy="17" r="1.5" fill="white" />
                        </svg>
                    </button>
                </div>
                <div id="modalFilter"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                    <div class="bg-white dark:bg-[#1b2e4b] rounded-lg shadow-lg p-6 w-full max-w-md m-5">
                        <div class="flex justify-between">
                            <h3 class="text-lg font-semibold mb-4">Filter Table</h3>
                            <button type="button" class="btn btn-warning btn-sm flex items-center"
                                onclick="document.getElementById('modalFilter').classList.add('hidden')">
                                <svg xmlns="https://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        {{-- Form Filter --}}
                        {{-- <div class="mb-4">
                            <label for="filterName" class="block text-sm font-medium text-gray-500">Job
                                Number</label>
                            <select id="job_num" class="form-select w-full">
                            </select>
                        </div> --}}
                        <div class="flex grid grid-cols-2 gap-1">
                            <div class="mb-4">
                                <label for="filterDate" class="block text-sm font-medium text-gray-500">Start
                                    date</label>
                                <input type="date" id="start" class="form-input w-full"
                                    style="color-scheme: dark;">
                            </div>
                            <div class="mb-4">
                                <label for="filterDate" class="block text-sm font-medium text-gray-500">End
                                    date</label>
                                <input type="date" id="end" class="form-input w-full"
                                    style="color-scheme: dark;">
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <button class="btn btn-primary" onclick="submitExport()">Export</button>
                            <button class="btn btn-primary" id="searchFilter">Search</button>
                        </div>
                        {{-- End Filter --}}
                    </div>
                </div>
                <div class="mb-5" x-data="{ active: 1 }">
                    <div class="space-y-2 font-semibold text-2xl">
                        <div class="border border-[#d3d3d3] dark:border-[#1b2e4b] rounded dark:text-white-light">
                            <div x-cloak x-show="active === 1" x-collapse>
                                <div class="dark:text-white-light overflow-x-auto">
                                    <div class="flex justify-between items-center mb-2 mt-2">
                                        <button class="btn btn-primary" style="visibility: hidden"
                                            onclick="filterForm()" id="btnFilter">Filter</button>

                                        <select name="selectTable" id="selectTable"
                                            class="form-select w-52 lg:text-xs md:text-sm p-2 rounded-md border border-gray-300
                                                    bg-white text-gray-700
                                                    dark:bg-[#1e293b] dark:text-gray-200 dark:border-gray-600
                                                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                            onchange="selectTable()" style="width:13rem">
                                            <option selected disabled>Selected Table</option>
                                            <option value="filter">Main Table</option>
                                            <option value="jo_pending">Job Pending</option>
                                            <option value="jo_history">Job History</option>
                                        </select>
                                    </div>
                                    <div class="" id="mainTableCard">
                                        <table class="min-w-full table" id="main-table">
                                            <thead
                                                class="text-black dark:text-white uppercase text-center border border-sky-600 bg-sky-600">
                                                <tr>
                                                    <th>Machine</th>
                                                    <th id="model-column" style="display: none">Model</th>
                                                    <th>Job Number</th>
                                                    <th>Part No</th>
                                                    <th>Plan</th>
                                                    <th>Act</th>
                                                    <th id="qtyOK" style="display: none">Qty OK</th>
                                                    <th class="whitespace-nowrap w-40">Start</th>
                                                    <th class="whitespace-nowrap w-40">Finish</th>
                                                    <th>Prod.Time <br><span class="text-xs">(Hour)</span></th>
                                                    <th>Progress</th>
                                                </tr>
                                            </thead>

                                            <tbody class="text-white">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="hidden" id='joPendingCard'>
                                        <table class="min-w-full table" id="jo_pending_table">
                                            <thead
                                                class="text-black dark:text-white uppercase text-center border border-sky-600 bg-sky-600">
                                                <tr>
                                                    <th>Machine</th>
                                                    <th>Job Num</th>
                                                    <th>Part No</th>
                                                    <th>Date</th>
                                                    <th>Shift</th>
                                                </tr>
                                            </thead>

                                            <tbody class="text-white">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="hidden" id="joHistoryCard">
                                        <table class="min-w-full table" id="jo_history_table">
                                            <thead
                                                class="text-black dark:text-white uppercase text-center border border-sky-600 bg-sky-600">
                                                <tr>
                                                    <th>Machine</th>
                                                    <th>Job Number</th>
                                                    <th>Part No</th>
                                                    <th>Plan</th>
                                                    <th>Act</th>
                                                    <th>Start</th>
                                                    <th>Finish</th>
                                                    <th>Prod<br>Time</th>
                                                    <th>Progress</th>
                                                </tr>
                                            </thead>

                                            <tbody class="text-white">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="{{ Vite::asset('resources/css/highlight.min.css') }}">
    <script src="/assets/js/highlight.min.js"></script>
    <script src="/assets/js/nice-select2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script>
        function selectTable() {
            const selected = document.getElementById('selectTable').value;
            window.location.hash = selected;
            showTable(selected);
        }

        function showTable(selected) {
            const mainTable = document.getElementById('mainTableCard');
            const pendingTable = document.getElementById('joPendingCard');
            const historyTable = document.getElementById('joHistoryCard');
            const titleCard = document.getElementById('titleCard');
            const btnFilter = document.getElementById('btnFilter');

            if (selected === 'jo_pending') {
                mainTable.classList.add('hidden');
                historyTable.classList.add('hidden');
                pendingTable.classList.remove('hidden');
                titleCard.textContent = 'Pending';
                btnFilter.style.visibility = 'hidden';

                let pathId = window.location.pathname.split('/');
                let lineId = pathId[pathId.length - 1];
                axios.post(`https://${window.location.host}/api/main/jo_pending/${lineId}`, {
                    'Content-Type': 'application/x-www-form-urlencoded'
                });

            } else if (selected === 'jo_history') {
                mainTable.classList.add('hidden');
                pendingTable.classList.add('hidden');
                historyTable.classList.remove('hidden');
                titleCard.textContent = 'History';
                btnFilter.style.visibility = 'visible';

            } else {
                mainTable.classList.remove('hidden');
                pendingTable.classList.add('hidden');
                historyTable.classList.add('hidden');
                titleCard.textContent = 'Main Table';
                btnFilter.style.visibility = 'hidden';
            }
        }
        window.addEventListener('hashchange', () => {
            const table = window.location.hash.replace('#', '') || 'filter';
            document.getElementById('selectTable').value = table;
            showTable(table);
        });
        window.addEventListener('load', () => {
            const table = window.location.hash.replace('#', '') || 'filter';
            document.getElementById('selectTable').value = table;
            showTable(table);
        });

        function toolTips() {
            new window.Swal({
                position: "top-end",
                icon: 'info',
                html: `<div style="text-align: left; font-size: 0.85em; line-height: 1.6;">
            <p>
                <span style="display:inline-block; width:14px; height:14px; background-color:#4b5563; border-radius:3px; vertical-align:middle; margin-right:6px;"></span>
                <strong>Abu-abu</strong>: Tidak Aktif / Belum Dimulai
            </p>
            <p>
                <span style="display:inline-block; width:14px; height:14px; background-color:#3b82f6; border-radius:3px; vertical-align:middle; margin-right:6px;"></span>
                <strong>Biru Muda</strong>: Mesin Dimulai, Belum Produksi
            </p>
            <p>
                <span style="display:inline-block; width:14px; height:14px; background:linear-gradient(to right, #1e40af 50%, #3b82f6 50%); border-radius:3px; vertical-align:middle; margin-right:6px;"></span>
                <strong>Biru Gradasi</strong>: Sedang Berlangsung / Produksi Aktif
            </p>
            <p>
                <span style="display:inline-block; width:14px; height:14px; background-color:#22c55e; border-radius:3px; vertical-align:middle; margin-right:6px;"></span>
                <strong>Hijau</strong>: Selesai - Target Tercapai
            </p>
            <p>
                <span style="display:inline-block; width:14px; height:14px; background-color:#f59e0b; border-radius:3px; vertical-align:middle; margin-right:6px;"></span>
                <strong>Oranye</strong>: Selesai - Target Tidak Tercapai
            </p>
        </div>`,
                showConfirmButton: false,
                width: '350px',
            });
        }

        function filterForm() {
            document.getElementById('modalFilter').classList.remove('hidden');
        }

        function submitExport() {
            let pathId = window.location.pathname.split('/');
            let lastPart = pathId[pathId.length - 1];
            let lineId = lastPart.split('#')[0];
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('start', document.getElementById('start').value);
            formData.append('end', document.getElementById('end').value);
            formData.append('lineID', lineId);

            fetch("{{ url('machine/export-history-log') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Gagal mengunduh file');
                    return response.blob();
                })
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    const today = new Date();
                    const dateOnly = today.toISOString().split('T')[0];
                    let pathId = window.location.pathname.split('/');
                    let lineId = pathId[pathId.length - 1];
                    a.download = 'IoT_export_' + dateOnly + '_' + lineId + '.xlsx';
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                })
                .catch(error => {
                    console.error(error);
                    new window.Swal({
                        icon: 'error',
                        text: 'Export Failed!',
                        padding: '2em',
                        customClass: 'sweet-alerts'
                    });
                });
        }
        $(document).ready(function() {
            let pathId = window.location.pathname.split('/');
            let lineId = pathId[pathId.length - 1];
            let linePage = pathId[1]
            let urlTable = `https://${window.location.host}/api/main/${lineId}`;
            let columns = [{
                    data: 'machine_id',
                    name: 'machine_id'
                },
                {
                    data: 'job_num',
                    name: 'job_num'
                },
                {
                    data: 'part_no',
                    name: 'part_no'
                },
                {
                    data: 'qty_plan',
                    name: 'qty_plan',
                    render: function(data, type, row) {
                        return Number(data).toLocaleString('en-US', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        });
                    }
                },
                {
                    data: 'qty_actual',
                    name: 'qty_actual',
                    render: function(data, type, row) {
                        return Number(data).toLocaleString('en-US', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        });
                    }
                },
                {
                    data: 'started_at',
                    name: 'started_at'
                },
                {
                    data: 'finished_at',
                    name: 'finished_at'
                },
                {
                    data: 'operation_time',
                    name: 'operation_time'
                },
                {
                    data: 'planvsact',
                    name: 'planvsact',
                    orderable: false,
                    searchable: false,
                    width: '70px'
                }
            ];
            if (lineId === 'RBT-5H45') {
                $('#model-column').show()
                columns.splice(1, 0, {
                    data: 'model',
                    name: 'model'
                });
            } else if (lineId === 'A6') {
                $('#qtyOK').show()
                columns.splice(5, 0, {
                    data: 'qty_ok',
                    name: 'qty_ok'
                });
            }
            let table = $('#main-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: urlTable,
                    type: 'POST',
                },
                order: [
                    [5, 'desc']
                ],
                pageLength: 25,
                columns: columns,
                createdRow: function(row, data) {
                    const machine_id = data.machine_id
                    const qtyPlan = parseFloat((data.qty_plan + '').replace(/\./g, '').replace(/,/g,
                        '.')) || 0;
                    const qtyActual = parseFloat((data.qty_actual + '').replace(/\./g, '').replace(/,/g,
                        '.')) || 0;
                    const operationTime = parseFloat(data.operation_time) || 0;
                    const startedAt = data.started_at;
                    const finishedAt = data.finished_at;
                    const jobNum = data.job_num;
                    const status_finish = data.status_finish
                    const isActive = data.is_active === true || data.is_active === "1";
                    const progress = qtyPlan > 0 ? Math.round((qtyActual / qtyPlan) * 100) : 0;
                    let backgroundStyle = '#4b5563';
                    let titleText = 'Belum dimulai';
                    if (lineId == 'RBT-5H45') {
                        if (finishedAt != null && status_finish == true && qtyActual >= qtyPlan &&
                            qtyActual > 0 && qtyPlan > 0) {
                            backgroundStyle = '#22c55e';
                            titleText = 'Selesai mencapai target';
                        } else if (qtyActual > 0 && startedAt) {
                            backgroundStyle =
                                `linear-gradient(to right, #1e40af ${progress}%, #3b82f6 ${progress}%)`;
                            titleText = `Progress: ${progress.toFixed(1)}%`;
                            const finishedAtIndex = table.settings()[0].aoColumns.findIndex(col => col
                                .data === 'finished_at');
                            if (finishedAtIndex !== -1) {
                                $('td', row).eq(finishedAtIndex).text('-');
                            }
                        } else if (startedAt && qtyActual === 0 && qtyPlan > 0) {
                            backgroundStyle = '#3b82f6';
                            titleText = 'Mesin sudah dimulai, belum produksi';
                        } else if (qtyActual < qtyPlan && finishedAt != null && qtyActual > 0) {
                            backgroundStyle = '#f59e0b';
                            titleText = 'Selesai tapi belum capai target';
                        } else if (jobNum == null && !startedAt) {
                            backgroundStyle = '#4b5563';
                            titleText = 'Belum dimulai / Tidak aktif';
                            $('td', row).eq(1).text('-');
                            $('td', row).eq(2).text('-');
                            $('td', row).eq(3).text('-');
                            $('td', row).eq(4).text('-');
                            $('td', row).eq(5).text('-');
                            $('td', row).eq(6).text('-');
                        }
                    } else {
                        if (status_finish == true && qtyActual >= qtyPlan) {
                            backgroundStyle = '#22c55e';
                            titleText = 'Selesai mencapai target';
                        } else if (qtyActual > 0 && startedAt) {
                            backgroundStyle =
                                `linear-gradient(to right, #1e40af ${progress}%, #3b82f6 ${progress}%)`;
                            titleText = `Progress: ${progress.toFixed(1)}%`;
                            $('td', row).eq(6).text('');
                        } else if (startedAt && qtyActual === 0) {
                            backgroundStyle = '#3b82f6';
                            titleText = 'Mesin sudah dimulai, belum produksi';
                        } else if (qtyActual < qtyPlan && status_finish === true && operationTime ===
                            0) {
                            backgroundStyle = '#f59e0b';
                            titleText = 'Selesai tapi belum capai target';
                        } else if (jobNum == null && !startedAt) {
                            backgroundStyle = '#4b5563';
                            titleText = 'Belum dimulai / Tidak aktif';
                            $('td', row).eq(1).text('-');
                            $('td', row).eq(2).text('-');
                            $('td', row).eq(3).text('-');
                            $('td', row).eq(4).text('-');
                            $('td', row).eq(5).text('-');
                            $('td', row).eq(6).text('-');
                        }
                    }

                    $(row).css('background', backgroundStyle);
                    $(row).attr('title', titleText);
                    $(row).css('cursor', 'pointer');
                    $(row).off('click').on('click', function() {
                        const machineName = data.machine_id.split('/')
                        const toolID = machineName[1]
                        const machineID = machineName[0]
                        if (toolID) {
                            window.location.href =
                                `https://${window.location.host}/${linePage}/dashboard/${machineID}/${toolID}`;
                        } else {
                            if (machineID.startsWith('SSW')) {
                                window.location.href =
                                    `https://dashboard.summitadyawinsa.co.id/dashboard-profile/${machineID}`;
                            } else {
                                window.location.href =
                                    `https://${window.location.host}/${linePage}/dashboard/${machineID}`;
                            }
                        }
                    });
                },
                drawCallback: function() {
                    const tbody = $('#stamping-table tbody');
                    const pendingRows = tbody.find('tr.row-pending');
                    pendingRows.each(function() {
                        tbody.append(this);
                    });
                }
            });
            //Tabel pending
            const pending = $('#jo_pending_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: `https://${window.location.host}/api/main/jo_pending/${lineId}`,
                    type: 'POST'
                },
                order: [
                    [3, 'desc']
                ],
                columns: [{
                        data: 'ResourceID',
                        name: 'ResourceID'
                    },
                    {
                        data: 'JobNum',
                        name: 'JobNum'
                    },
                    {
                        data: 'PartNum',
                        name: 'PartNum'
                    },
                    {
                        data: 'DueDate',
                        name: 'DueDate'
                    },
                    {
                        data: 'JobCode',
                        name: 'JobCode'
                    }
                ]
            });
            //Tabel History
            const history = $('#jo_history_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: `https://${window.location.host}/api/main/history/${lineId}`,
                    type: 'POST',
                    data: function(d) {
                        if ($('#start').val()) d.start = $('#start').val();
                        if ($('#end').val()) d.end = $('#end').val();
                    }
                },
                order: [
                    [5, 'desc']
                ],
                columns: [{
                        data: 'machine_id',
                        name: 'machine_id'
                    },
                    {
                        data: 'job_num',
                        name: 'job_num'
                    },
                    {
                        data: 'part_no',
                        name: 'part_no'
                    },
                    {
                        data: 'qty_plan',
                        name: 'qty_plan',
                        render: function(data, type, row) {
                            return Number(data).toLocaleString('en-US', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                        }
                    },
                    {
                        data: 'qty_actual',
                        name: 'qty_actual',
                        render: function(data, type, row) {
                            return Number(data).toLocaleString('en-US', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                        }
                    },
                    {
                        data: 'started_at',
                        name: 'started_at'
                    },
                    {
                        data: 'finished_at',
                        name: 'finished_at'
                    },
                    {
                        data: 'operation_time',
                        name: 'operation_time',
                        render: function(data, type, row) {
                            const value = parseFloat(data);
                            return isNaN(value) ? '0.00' : value.toFixed(2);
                        }
                    },
                    {
                        data: 'planvsact',
                        name: 'planvsact',
                        orderable: false,
                        searchable: false,
                        width: '70px'
                    }
                ],
                createdRow: function(row, data) {
                    const startedAt = data.started_at;
                    const finishedAt = data.finished_at;
                    const qtyPlan = parseFloat(data.qty_plan) || 0;
                    const qtyActual = parseFloat(data.qty_actual) || 0;
                    let backgroundStyle = '#6b7280';
                    const progress = qtyPlan > 0 ? Math.min((qtyActual / qtyPlan) * 100, 100) : 0;
                    if (startedAt && finishedAt) {
                        if (qtyActual >= qtyPlan) {
                            backgroundStyle = '#22c55e';
                        } else {
                            backgroundStyle = '#f59e0b'
                        }
                        $(row).attr('title', `Capaian: ${progress.toFixed(1)}%`);
                    } else if (startedAt && !finishedAt) {
                        backgroundStyle = '#3b82f6';
                    }
                    $(row).css('background', backgroundStyle);
                    $(row).css('cursor', 'pointer');
                    $(row).off('click').on('click', function() {
                        const numberShift = data.shift.split(' ')[1]
                        window.location.href =
                            `https://${window.location.host}/${linePage}/dashboard/${data.machine_id}/${data.job_num}/${data.production_date}/${numberShift}`;
                    });
                }
            });
            $('#searchFilter').on('click', function(e) {
                e.preventDefault();
                history.ajax.reload();
                document.getElementById('modalFilter').classList.add('hidden');
            });
        });
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
                if (eventTitle === 'update_stroke' || eventTitle === 'update_stroke_tool') {
                    const table = $('#main-table').DataTable();
                    const machine_id = eventTitle === 'update_stroke' ?
                        response.machine_id :
                        `${response.machine_id}/${response.tool_id}`;
                    const rowIdx = table.rows().indexes().filter(idx => {
                        return table.row(idx).data().machine_id === machine_id;
                    })[0];

                    if (rowIdx !== undefined) {
                        // console.log(response)
                        let rowData = table.row(rowIdx).data();
                        const qtyActual = parseFloat(response.qty_actual) || 0;
                        rowData.qty_actual = qtyActual;
                        rowData.qty_ok = parseInt(response.qty_ok) || 0;
                        if (response.operation_time) {
                            const opTimeSplit = response.operation_time.trim().split(' ');
                            if (opTimeSplit.length === 3) {
                                rowData.started_at = opTimeSplit[0];
                                rowData.finished_at = opTimeSplit[1];
                                rowData.operation_time = parseFloat(opTimeSplit[2]) || 0;
                            }
                        }
                        const qtyPlan = parseFloat(rowData.qty_plan) || 0;
                        const progress = qtyPlan > 0 ? Math.round((qtyActual / qtyPlan) * 100) : 0;
                        const backgroundStyle = `linear-gradient(to right, #1e40af ${progress}%, #3b82f6 ${progress}%)`;
                        const titleText = `Progress: ${progress.toFixed(1)}%`;
                        rowData.progress = progress;
                        rowData.backgroundStyle = backgroundStyle;
                        rowData.titleText = titleText;
                        table.row(rowIdx).data(rowData).invalidate().draw(false);
                        const rowNode = table.row(rowIdx).node();
                        $(rowNode)
                            .css('background', backgroundStyle)
                            .attr('title', titleText);
                    }
                } else if (eventTitle == 'start-machine' || eventTitle == 'finish-machine' || eventTitle ==
                    'start-machine-tool' || eventTitle == 'finish-tool') {
                    const table = $('#main-table').DataTable();
                    table.ajax.reload(null, false)
                }
            } catch (e) {
                console.error(e);
            }
        };
        socket.onclose = () => {
            console.log('Disconnected from WebSocket')
        }
        socket.onerror = (err) => {
            console.log('WebSocket error:', err)
        }
    </script>
    <script>
        $('#job_number').select2({
            width: '100%',
            allowClear: true,
            ajax: {
                url: `https://${window.location.host}/api/machine/get-all-job-number`,
                type: 'POST',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                        page: params.page || 1,
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination?.more || false
                        }
                    };
                },
                cache: true
            },
            minimumInputLength: 1,
            placeholder: 'Pilih Job Number',
        });
    </script>
</x-layout.default>
