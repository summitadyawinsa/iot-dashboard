<x-layout.default>
    <link rel='stylesheet' type='text/css' href='{{ Vite::asset('resources/css/nice-select2.css') }}'>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/flatpickr.min.css') }}">
    <script src="/assets/js/flatpickr.js"></script>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/nouislider.min.css') }}">
    <script src="/assets/js/nouislider.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- <link rel="stylesheet" href="/assets/css/select.css"> -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/erimicel/select2-tailwindcss-theme/dist/select2-tailwindcss-theme-plain.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/erimicel/select2-tailwindcss-theme@x.x.x/dist/select2-tailwindcss-theme-plain.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <div x-data="form">
        <div class="flex justify-between">
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
                </li>
                <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                    <span>One Point Lesson</span>
                </li>
            </ul>
            <ul class="flex justify-between">
                <button type="button" class="btn btn-primary mr-2" onclick="scanModal()">Scan</button>
                <button type="button" class="btn btn-primary" onclick="createModal()">Create</button>
            </ul>
        </div>

        <div class="pt-5 space-y-8">
            <div class="flex justify-between">
                <select id="search" class="form-select w-50 max-w-xs">
                </select>
                <!-- <input type="text" class="form-input w-50 max-w-xs" id="scan_search" onchange="scanSearch()"> -->
            </div>
            <div id="cardContainer"
                class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 gap-2 p-4">
            </div>
            <div id="modalCreate"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div class="bg-white dark:bg-[#1b2e4b] rounded-lg shadow-lg p-6 w-full max-w-4xl m-5">
                    <div class="flex justify-between">
                        <h3 class="text-lg font-semibold mb-4">Create Part</h3>
                        <button type="button" class="btn btn-warning btn-sm flex items-center"
                            onclick="document.getElementById('modalCreate').classList.add('hidden')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="mb-4">
                        <label for="filterName" class="block text-sm font-medium text-gray-500">Part Number</label>
                        <select id="part_number" class="form-select w-full" style="width: 100%">
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="filterDate" class="block text-sm font-medium text-gray-500">Photo</label>
                        <input type="file" id="photo" class="form-input w-full" style="color-scheme: dark;">
                    </div>
                    <div class="mb-4">
                        <label for="filterDate" class="block text-sm font-medium text-gray-500">Part Relation</label>
                        <select id="PartRelation" class="form-select w-full" style="width: 100%">
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button class="btn btn-primary" id="submitCreate">Submit</button>
                    </div>
                </div>
            </div>
            <div id="modalScan"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div class="bg-white dark:bg-[#1b2e4b] rounded-lg shadow-lg p-6 w-full max-w-4xl m-5">
                    <div class="flex justify-between">
                        <h3 class="text-lg font-semibold mb-4">Scan Part</h3>
                        <button type="button" class="btn btn-warning btn-sm flex items-center"
                            onclick="document.getElementById('modalScan').classList.add('hidden')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="mb-4">
                        <label for="filterDate" class="block text-sm font-medium text-gray-500">Search</label>
                        <input type="text" id="input_search" class="form-input w-full" style="color-scheme: dark;">
                    </div>
                    <div class="flex justify-end">
                        <button class="btn btn-primary" id="submitScan">Submit</button>
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
    <script>
        document.addEventListener("DOMContentLoaded", function (e) {
            var els = document.querySelectorAll(".selectize");
            els.forEach(function (select) {
                NiceSelect.bind(select);
            });
            cardShow(null);
            search()
        });

        function search() {
            $.ajax({
                url: "{{ url('one-point-lesson/part/relation') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response && response.length > 0) {
                        $("#search").append(`<option value="" selected>Pilih Part</option>`);
                        response.forEach(function (item) {
                            $("#search").append(
                                `<option value="${item.PartNum}">${item.PartNum}</option>`
                            );
                        })
                    } else {
                        ("#search").append(
                            `<option>Data tidak ada</option>`
                        );
                    }
                },
                error: function (xhr) {
                    console.log(xhr)
                        ("#search").append(
                            `<option>Terjadi kesalahan</option>`
                        );
                }
            })
        }
        function cardShow(search) {
            $.ajax({
                url: "{{ url('one-point-lesson/part-list') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    search: search,
                    // scan_search: document.getElementById("scan_search").value
                },
                success: function (response) {
                    const data = response;
                    let html = "";
                    const basePath = "{{ asset('part') }}/";
                    data.forEach(item => {
                        let model = '';
                        if (item.Model) {
                            model = `
                        <a
                        class="inline-block px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                        ${item.Model}
                        </a>`;
                        }
                        const urlQr = "{{ url('one-point-lesson/qr_view') }}" + '/' + item.id
                        html += `
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 
                rounded-xl shadow overflow-hidden transition duration-300">

        <div class="w-full overflow-hidden bg-gray-100 dark:bg-gray-900 
                    flex items-center justify-center" style="height:27rem">
            <img src="${basePath}/${item.Photo}" 
                 class="w-full h-full object-cover">
        </div>

        <div class="p-4">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                ${item.PartNum}
            </h2>

            <p class="text-lg font-medium text-gray-600 dark:text-gray-300 mb-4">
                ${item.PartName}
            </p>

            <div class='flex justify-between'>
                ${model}
                <a class="inline-block px-4 py-2 
                          bg-blue-600 text-white text-sm font-medium 
                          rounded hover:bg-blue-700 transition"
                   href="${urlQr}">
                   QR
                </a>
            </div>
        </div>
    </div>
`;

                    });
                    $("#cardContainer").html(html);
                }
            });
        }
        $("#search").on('change', function () {
            const search = $("#search").val()
            cardShow(search)
        })
        function scanModal() {
            $("#modalScan").removeClass('hidden');
        }
        function createModal() {
            $("#modalCreate").removeClass('hidden');
            $("#part_number").empty().append('<option value="">Loading...</option>');
            $.ajax({
                url: "{{ url('one-point-lesson/part/list') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",

                },
                success: function (response) {
                    $("#part_number").empty();
                    $("#part_number").append('<option value="">-- Pilih Part Number --</option>');

                    if (response && response.length > 0) {
                        response.forEach(function (item) {
                            $("#part_number").append(
                                `<option value="${item.PartNum}">${item.PartNum}</option>`
                            );
                        });
                        PartRelation()
                    } else {
                        $("#part_number").append('<option value="">Tidak ada data</option>');
                    }
                },
                error: function (xhr) {
                    console.error(xhr);
                    $("#part_number").empty().append('<option value="">Gagal memuat data</option>');
                }
            });
        }

        function PartRelation() {
            $("#PartRelation").append('<option>Loading...</option>')
            $.ajax({
                url: "{{ url('one-point-lesson/part/relation') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    $("#PartRelation").empty();
                    $("#PartRelation").append(
                        `<option value="" selected>Pilih Part</option>`
                    );
                    if (response && response.length > 0) {
                        response.forEach(function (item) {
                            $("#PartRelation").append(
                                `<option value="${item.PartNum}">${item.PartNum}</option>`
                            );
                        })
                    } else {
                        $("#PartRelation").append(
                            `<option>Tidak ada data</option>`
                        );
                    }
                },
                error: function (xhr) {
                    console.error(xhr);
                    $("#PartRelation").empty().append('<option value="">Gagal memuat data</option>');
                }
            })
        }
        $("#submitCreate").on('click', function (e) {
            e.preventDefault();

            const partNum = $("#part_number").val();
            const photo = $("#photo")[0].files[0];
            const PartRelation = $("#PartRelation").val()

            if (!partNum || !photo) {
                new window.Swal({
                    icon: 'error',
                    text: 'Kolom tidak boleh kosong',
                    padding: '2em',
                    customClass: 'sweet-alerts',
                });
                return;
            }
            const data = new FormData();
            data.append('PartNum', partNum);
            data.append('photo', photo);
            data.append('_token', "{{ csrf_token() }}");
            data.append('PartRelation', PartRelation);

            $.ajax({
                url: "{{ url('one-point-lesson/part/create') }}",
                type: "POST",
                data: data,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status == 'success') {
                        new window.Swal({
                            icon: 'success',
                            text: 'Data berhasil dibuat',
                            padding: '2em',
                            customClass: 'sweet-alerts',
                        });
                        $("#modalCreate").addClass('hidden');
                        cardShow(null);
                    } else {
                        new window.Swal({
                            icon: 'error',
                            text: response.message,
                            padding: '2em',
                            customClass: 'sweet-alerts',
                        });
                    }
                },
                error: function (xhr) {
                    console.error(xhr);
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = [];
                        for (let key in errors) {
                            messages.push(errors[key][0]);
                        }

                        new window.Swal({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: messages.join('\n'),
                            padding: '2em',
                            customClass: 'sweet-alerts',
                        });
                    } else {
                        new window.Swal({
                            icon: 'error',
                            title: 'Server Error',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan pada server',
                            padding: '2em',
                            customClass: 'sweet-alerts',
                        });
                    }
                }
            });
        });
        $("#submitScan").on('click', function () {
            const scan = $("#input_search").val()
            if (!scan) {
                new window.Swal({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Kolom wajib di isi',
                    padding: '2em',
                    customClass: 'sweet-alerts',
                });
                return;
            }
            $.ajax({
                url: "{{ url('one-point-lesson/part/show_data') }}",
                type: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    scan: scan
                }, success: function (response) {
                    if (response.status == 200) {
                        new window.Swal({
                            icon: 'success',
                            title: 'Success',
                            text: 'Data berhasil di tampilkan',
                            padding: '2em',
                            customClass: 'sweet-alerts',
                        });
                    } else {
                        new window.Swal({
                            icon: 'success',
                            title: 'Success',
                            text: 'Data tidak ditemukan',
                            padding: '2em',
                            customClass: 'sweet-alerts',
                        });
                    }
                }, error: function (xhr) {
                    new window.Swal({
                        icon: 'error',
                        title: 'Server Error',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan pada server',
                        padding: '2em',
                        customClass: 'sweet-alerts',
                    });
                }
            })
        })
    </script>

</x-layout.default>