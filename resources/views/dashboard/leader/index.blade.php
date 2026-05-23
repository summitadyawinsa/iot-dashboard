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
                        Report <span id="titleCard">Realtime</span>
                    </h5>
                    {{-- <button class="btn btn-primary btn-sm" onclick="toolTips()">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="white"
                            xmlns="https://www.w3.org/2000/svg">
                            <path d="M1 21h22L12 2 1 21z" stroke="white" stroke-width="2" fill="none" />
                            <line x1="12" y1="8" x2="12" y2="14" stroke="white"
                                stroke-width="2" />
                            <circle cx="12" cy="17" r="1.5" fill="white" />
                        </svg>
                    </button> --}}
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
                                    {{-- <div class="flex justify-between items-center mb-2 mt-2">
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
                                    </div> --}}
                                    <div class="" id="">
                                        <table class="min-w-full table border border-gray-300 dark:border-gray-700"
                                            id="main-table">

                                            <thead
                                                class="uppercase text-center 
                                                bg-sky-500 text-white 
                                                dark:bg-gray-800 dark:text-gray-200 
                                                border border-sky-600 dark:border-gray-700">
                                                <tr>
                                                    <th class="px-3 py-2">Machine</th>
                                                    <th class="px-3 py-2">Job Number</th>
                                                    <th class="px-3 py-2">Part No</th>
                                                    <th class="px-3 py-2">Plan</th>
                                                    <th class="px-3 py-2">Act</th>
                                                    <th class="px-3 py-2">Start</th>
                                                    <th class="px-3 py-2">Finish</th>
                                                    <th class="px-3 py-2">Action</th>
                                                </tr>
                                            </thead>

                                            <tbody
                                                class="text-gray-800 bg-white 
                                                dark:text-gray-200 dark:bg-gray-900">
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
        $(document).ready(function() {
            $("#main-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('api/production-report') }}",
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
                        name: 'qty_plan'
                    },
                    {
                        data: 'qty_actual',
                        name: 'qty_actual'
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
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        })
    </script>
</x-layout.default>
