<x-layout.default>
    <link rel='stylesheet' type='text/css' href='{{ Vite::asset('resources/css/nice-select2.css') }}'>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/flatpickr.min.css') }}">
    <script src="/assets/js/flatpickr.js"></script>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/nouislider.min.css') }}">
    <script src="/assets/js/nouislider.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/select.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        #analytics:fullscreen {
            width: 100vw;
            height: 100vh;
            overflow: auto;
            background: #fff;
        }

        #analytics:fullscreen * {
            pointer-events: auto;
        }
    </style>
    <div id="analytics">
        <input type="text" id="user_id" value="{{ Auth::user()->id }}" hidden>
        <div class="p-6 space-y-6 bg-gray-100 dark:bg-gray-900 min-h-screen transition" id="config_view">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white" id="configTitle">
                    User Management
                </h1>
                <button id="create_btn" class="px-4 py-2 rounded-lg font-medium
bg-gray-800 text-white hover:bg-gray-700
dark:bg-blue-600 dark:hover:bg-blue-500
transition duration-200">
                    Create
                </button>
            </div>
            <div class="overflow-x-auto">
                <table id="table_user_mgn" class="min-w-full border border-black dark:border-white border-collapse">

                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th
                                class="px-4 py-3 border border-black dark:border-white text-left text-sm font-semibold text-black dark:text-white">
                                Name
                            </th>

                            <th
                                class="px-4 py-3 border border-black dark:border-white text-left text-sm font-semibold text-black dark:text-white">
                                Email
                            </th>

                            <th
                                class="px-4 py-3 border border-black dark:border-white text-left text-sm font-semibold text-black dark:text-white">
                                EmployeeID
                            </th>

                            <th
                                class="px-4 py-3 border border-black dark:border-white text-left text-sm font-semibold text-black dark:text-white">
                                Action
                            </th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
    <div id="create_analytics" class="hidden">
        <input type="text" id="user_id" value="{{ Auth::user()->id }}" hidden>
        <div class="p-6 space-y-6 bg-gray-100 dark:bg-gray-900 min-h-screen transition" id="config_view">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white" id="configTitle">
                    Create users
                </h1>
                <button id="back_btn"
                    class="px-4 py-2 rounded-lg font-medium bg-gray-800 text-white hover:bg-gray-700 dark:bg-blue-600 dark:hover:bg-blue-500 transition duration-200">
                    Back
                </button>
            </div>
            <div class="overflow-x-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 dark:text-white-light">
                    <div>
                        <label for="inputDefault">Name</label>
                        <input id="name" type="text" class="form-input text-black dark:text-white" />
                    </div>
                    <div>
                        <label for="inputDefault">Employee ID</label>
                        <input id="username" type="text" class="form-input text-black dark:text-white" />
                    </div>
                    <div>
                        <label>Email</label>
                        <input id="email" type="email" class="form-input text-black dark:text-white" />
                    </div>
                    <div id="password_div">
                        <label for="standard_sph">Password</label>
                        <input id="password" type="password" class="form-input text-black dark:text-white" />
                    </div>
                    <div id="epicor_div" class="hidden">
                        <label for="standard_sph">Epicor ID</label>
                        <input id="epicor_id" type="text" class="form-input text-black dark:text-white" readonly />
                    </div>
                    <div>
                        <button class="btn btn-primary" id="submit">Submit</button>
                        <button class="btn btn-primary hidden" id="update">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/highlight.min.css') }}">
    <script src="/assets/js/highlight.min.js"></script>
    <script src="/assets/js/nice-select2.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        let table_user_mgn;
        $('document').ready(function () {
            user_table_func()
        })
        function user_table_func() {
            table_user_mgn = $("#table_user_mgn").DataTable({

                processing: true,
                serverSide: true,

                ajax: {
                    url: "{{ url('api/user-management/list') }}",
                    type: "POST"
                },

                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'username', name: 'username' },
                    { data: 'view', name: 'view', orderable: false, searchable: false }
                ],

                createdRow: function (row, data, dataIndex) {
                    $('td', row).addClass(
                        'px-4 py-3 border border-black dark:border-white text-black dark:text-white'
                    )
                }
            });
        }
        $("#create_btn").on('click', function () {
            $("#analytics").addClass('hidden')
            $("#create_analytics").removeClass('hidden')
            $("#name").val('')
            $("#username").val('')
            $("#email").val('')
            $("#password_div").removeClass('hidden')
            $("#epicor_div").addClass('hidden')
            $("#password").val('')
            $("#submit").removeClass('hidden')
            $("#update").addClass('hidden')
        })
        $("#back_btn").on('click', function () {
            $("#create_analytics").addClass('hidden')
            $("#analytics").removeClass('hidden')
            table_user_mgn.ajax.reload()
        })
        $("#submit").on('click', function () {
            const name = $("#name").val()
            const username = $("#username").val()
            const email = $("#email").val()
            const password = $("#password").val()
            if (!name || !username || !email || !password) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Semua kolom wajib di isi!"
                });
                return
            }
            $.ajax({
                url: "{{ url('api/user-management/store-users') }}",
                type: "POST",
                data: {
                    name: name,
                    username: username,
                    email: email,
                    password: password
                }, success: function (response) {
                    Swal.fire({
                        icon: response.icon,
                        title: response.title,
                        text: response.text
                    });
                }, error: function (xhr) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Unknown Error"
                    });
                }
            })
        })
        function ViewBtn(id) {
            $.ajax({
                url: "{{ url('api/user-management/find-data') }}",
                type: 'post',
                data: {
                    id: id
                }, success: function (response) {
                    if (response.status == 'error') {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: response.message
                        });
                    } else {
                        $("#analytics").addClass('hidden')
                        $("#create_analytics").removeClass('hidden')
                        $("#password_div").addClass('hidden')
                        $("#epicor_div").removeClass('hidden')
                        const data = response.data
                        $("#name").val(data.name)
                        $("#username").val(data.username)
                        $("#email").val(data.email)
                        $("#epicor_id").val(data.employee_id)
                        $("#submit").addClass('hidden')
                        $("#update").removeClass('hidden')
                    }
                }, error: function (xhr) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Unknown Error"
                    });
                }
            })
        }
        $("#update").on('click', function () {
            const name = $("#name").val()
            const username = $("#username").val()
            const email = $("#email").val()
            if (!name || !username || !email) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Semua kolom wajib di isi!"
                });
                return
            }
            $.ajax({
                url: "{{ url('api/user-management/update-user') }}",
                type: "POST",
                data: {
                    name: name,
                    username: username,
                    email: email,
                    epicor_id: $("#epicor_id").val()
                }, success: function (response) {
                    if (response.message == 'success') {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: response.message
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: response.message
                        });
                    }
                }, error: function (xhr) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: 'Unknow Error'
                    });
                }
            })
        })
        function DeleteBtn(id) {
            Swal.fire({
                title: "Apakah anda serius?",
                text: "Data akan di hapus secara permanent",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus saja"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('api/user-management/delete-user') }}",
                        type: 'post',
                        data: {
                            id: id
                        }, success: function (response) {
                            if (response.status == 'success') {
                                table_user_mgn.ajax.reload()
                                Swal.fire({
                                    icon: "success",
                                    title: "Success",
                                    text: response.message
                                });
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: response.message
                                });
                            }
                        }, error: function (xhr) {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: 'Unknown Error'
                            });
                        }
                    })
                }
            });
        }
    </script>
</x-layout.default>