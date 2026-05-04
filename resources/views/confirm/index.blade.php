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
                    <h5 class="font-bold lg:text-4xl md:text-2xl dark:text-white-light">Confirm Job Number
                    </h5>
                </div>
                <div class="mb-5" x-data="{ active: 1 }">
                    <div class="space-y-2 font-semibold text-2xl">
                        <div class="border border-[#d3d3d3] dark:border-[#1b2e4b] rounded dark:text-white-light">
                            <div x-cloak x-show="active === 1" x-collapse>
                                <div class="dark:text-white-light overflow-x-auto">
                                    <div class="" id="mainTableCard">
                                        <table class="min-w-full table" id="confirmTable">
                                            <thead
                                                class="text-black dark:text-white uppercase text-center border border-sky-600 bg-sky-600">
                                                <tr>
                                                    <th>Machine</th>
                                                    <th>Job Number</th>
                                                    <th>Part No</th>
                                                    <th>Plan</th>
                                                    <th class="whitespace-nowrap w-40">Start</th>
                                                    <th>Action</th>
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
        let path = window.location.pathname.replace(/\/$/, '');
        const last = path.substring(path.lastIndexOf('/') + 1);
        $(document).ready(function() {
            mainTable()
        })

        function mainTable() {
            $("#confirmTable").DataTable({
                proccesing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('api/confirm_table') }}",
                    type: 'POST',
                    data: function(d) {
                        d.page = last;
                    }
                },
                pageLength: 25,
                columns: [{
                    data: 'machine_id',
                    name: 'machine_id'
                }, {
                    data: 'job_num',
                    name: 'job_num'
                }, {
                    data: 'part_no',
                    name: 'part_no'
                }, {
                    data: 'qty_plan',
                    name: 'qty_plan'
                }, {
                    data: 'start',
                    name: 'start'
                }, {
                    data: 'action',
                    name: 'action'
                }]
            })
        }

        function confirm_jo(machine_id) {
            $.ajax({
                url: "{{ url('api/confirm_submit') }}",
                type: 'post',
                data: {
                    machine_id: machine_id
                },
                success: function(resp) {
                    new window.Swal({
                        icon: 'success',
                        text: resp.message,
                        padding: '2em',
                        customClass: 'sweet-alerts'
                    });
                },
                error: function(xhr) {
                    console.log(xhr)
                    new window.Swal({
                        icon: 'error',
                        text: 'Unknown Error!',
                        padding: '2em',
                        customClass: 'sweet-alerts'
                    });
                }
            })
        }
    </script>
</x-layout.default>
