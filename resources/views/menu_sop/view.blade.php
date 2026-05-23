<x-layout.default>
    <link rel='stylesheet' type='text/css' href='{{ Vite::asset('resources/css/nice-select2.css') }}'>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/flatpickr.min.css') }}">
    <script src="/assets/js/flatpickr.js"></script>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/nouislider.min.css') }}">
    <script src="/assets/js/nouislider.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/select.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <style>
        label.required::after {
            content: " *";
            color: red;
        }
    </style>
    <div x-data="form">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>Standard Operational Procedure</span>
            </li>
        </ul>

        <div class="pt-5 space-y-8">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">{{ $Part }}</h5>
                    <button id="deleteAll" class="btn btn-danger btn-sm">Delete all</button>
                    <button class="btn btn-primary btn-sm" id="backBtn" style="display: none">Back</button>
                </div>
                <div class="mb-5" x-data="{ active: 1 }" id="table_sop">
                    @foreach ($data as $item)
                        <div class="relative pl-10 mb-8">
                            <div
                                class="absolute left-0 top-6 w-8 h-8 flex items-center justify-center rounded-full
               bg-primary text-white font-bold">
                                {{ $item->Step }}
                            </div>
                            <div
                                class="bg-white dark:bg-[#1b2e4b]
               border border-gray-200 dark:border-[#253b5c]
               rounded-lg p-5 shadow-sm">

                                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                                    {{ $item->Title }}
                                </h6>
                                <div class="flex flex-col md:flex-row gap-4">
                                    @if ($item->Image)
                                        <div>
                                            <img src="{{ asset('menu_sop/' . $item->Image) }}" alt="SOP Image"
                                                class="rounded-md border
                           border-gray-200 dark:border-[#253b5c]
                           max-w-full md:max-w-md">
                                        </div>
                                    @endif

                                    {{-- TEXT --}}
                                    <div class="flex-1">
                                        <div class="text-sm leading-relaxed text-gray-700 dark:text-gray-300">
                                            {!! $item->Description !!}
                                        </div>
                                    </div>

                                </div>

                                {{-- ACTION BUTTON (TETAP DI BAWAH) --}}
                                <div class="mt-4 flex justify-end gap-2">
                                    <button class="btn btn-primary btn-sm" onclick="Edit({{ $item->ID }})">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="Delete({{ $item->ID }})">
                                        Delete
                                    </button>
                                </div>

                            </div>
                        </div>
                    @endforeach

                </div>
                <div class="mb-5" x-data="{ active: 1 }" id="update_form" style="display: none">
                    <div class="space-y-2 font-semibold">
                        <div class="border border-[#d3d3d3] dark:border-[#1b2e4b] rounded dark:text-white-light">
                            <div x-cloak x-show="active === 1" x-collapse>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 dark:text-white-light">
                                    <div>
                                        <label for="part" class="required">Part Number</label>
                                        <input id="partNum" type="text"
                                            class="form-input text-black dark:text-white" style="color-scheme: dark;"
                                            readonly />
                                    </div>
                                    <div>
                                        <label class="required">Title</label>
                                        <input id="title" type="text"
                                            class="form-input text-black dark:text-white" style="color-scheme: dark;" />
                                    </div>
                                    <div>
                                        <label for="step" class="required">Step</label>
                                        <input id="step" type="number"
                                            class="form-input text-black dark:text-white" style="color-scheme: dark;" />
                                    </div>
                                    <div>
                                        <label for="img">Image</label>
                                        <input id="image" type="file"
                                            class="form-input text-black dark:text-white" style="color-scheme: dark;" />
                                        <div id="viewDiv"></div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
                                    <div>
                                        <label for="desc" class="required">Description</label>
                                        <textarea id="description" class="form-input text-black dark:text-white" style="color-scheme: dark;"></textarea>
                                    </div>
                                </div>
                                <input type="text" id="id_edit" hidden>
                                <div class="flex justify-end">
                                    <button type="button" class="btn btn-primary btn-sm" id="btn_submit">
                                        <svg id="submit_icon" xmlns="https://www.w3.org/2000/svg"
                                            class="w-5 h-5 ltr:mr-1.5 rtl:ml-1.5 shrink-0" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path d="M10 2H14" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" />
                                            <path
                                                d="M13.8876 10.9348C14.9625 11.8117 15.5 12.2501 15.5 13C15.5 13.7499 14.9625 14.1883 13.8876 15.0652C13.5909 15.3073 13.2966 15.5352 13.0261 15.7251C12.7888 15.8917 12.5201 16.064 12.2419 16.2332C11.1695 16.8853 10.6333 17.2114 10.1524 16.8504C9.6715 16.4894 9.62779 15.7336 9.54038 14.2222C9.51566 13.7947 9.5 13.3757 9.5 13C9.5 12.6243 9.51566 12.2053 9.54038 11.7778C9.62779 10.2664 9.6715 9.51061 10.1524 9.1496C10.6333 8.78859 11.1695 9.11466 12.2419 9.76679C12.5201 9.93597 12.7888 10.1083 13.0261 10.2749C13.2966 10.4648 13.5909 10.6927 13.8876 10.9348Z"
                                                stroke="currentColor" stroke-width="1.5" />
                                            <path
                                                d="M7.5 5.20404C8.82378 4.43827 10.3607 4 12 4C16.9706 4 21 8.02944 21 13C21 17.9706 16.9706 22 12 22C7.02944 22 3 17.9706 3 13C3 11.3607 3.43827 9.82378 4.20404 8.5"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                        </svg>

                                        <svg id="submit_loader" viewBox="0 0 24 24" width="24" height="24"
                                            stroke="currentColor" stroke-width="1.5" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="w-5 h-5 ltr:mr-1.5 rtl:ml-1.5  animate-[spin_2s_linear_infinite] inline-block align-middle shrink-0 hidden">
                                            <line x1="12" y1="2" x2="12" y2="6">
                                            </line>
                                            <line x1="12" y1="18" x2="12" y2="22">
                                            </line>
                                            <line x1="4.93" y1="4.93" x2="7.76" y2="7.76">
                                            </line>
                                            <line x1="16.24" y1="16.24" x2="19.07" y2="19.07">
                                            </line>
                                            <line x1="2" y1="12" x2="6" y2="12">
                                            </line>
                                            <line x1="18" y1="12" x2="22" y2="12">
                                            </line>
                                            <line x1="4.93" y1="19.07" x2="7.76" y2="16.24">
                                            </line>
                                            <line x1="16.24" y1="7.76" x2="19.07" y2="4.93">
                                            </line>
                                        </svg>

                                        Submit
                                    </button>
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
    <script>
        $(document).ready(function() {
            ClassicEditor
                .create(document.querySelector('#description'), {
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'link', '|',
                            'bulletedList', 'numberedList', '|',
                            'insertTable', 'blockQuote', '|',
                            'undo', 'redo'
                        ]
                    },
                    table: {
                        contentToolbar: [
                            'tableColumn',
                            'tableRow',
                            'mergeTableCells',
                            'tableCellProperties',
                            'tableProperties'
                        ]
                    }
                })
                .then(editor => {
                    window.editor = editor
                })
                .catch(error => {
                    console.error(error)
                })
        })
        $("#deleteAll").on('click', function() {
            Swal.fire({
                title: "kamu yakin hapus ini?",
                text: "Data ini akan terhapus permanen",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('api/menu_sop/delete_all') }}",
                        type: "POST",
                        data: {
                            Part: "{{ $Part }}"
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                new window.Swal({
                                    icon: 'success',
                                    text: response.message,
                                    padding: '2em',
                                    customClass: 'sweet-alerts',
                                });
                                window.location.href =
                                    "{{ url('standard_operational_procedure') }}"
                            } else {
                                new window.Swal({
                                    icon: 'error',
                                    text: response.message,
                                    padding: '2em',
                                    customClass: 'sweet-alerts',
                                });
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr)
                            new window.Swal({
                                icon: 'error',
                                text: 'Something went wrong',
                                padding: '2em',
                                customClass: 'sweet-alerts',
                            });
                        }
                    })
                }
            });
        })

        function Edit(id) {
            $("#deleteAll").hide()
            $("#table_sop").hide()
            $("#update_form").show()
            $("#backBtn").show()
            $.ajax({
                url: "{{ url('api/menu_sop/edit_show') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function(response) {
                    const data = response.data
                    const part_num = $("#partNum").val(data.PartNum)
                    const title = $("#title").val(data.Title)
                    const step = $('#step').val(data.Step)
                    const id_edit = $("#id_edit").val(id)
                    if (window.editor) {
                        window.editor.setData(data.Description ?? '')
                    }
                },
                error: function(xhr) {
                    console.log(xhr)
                }
            })
        }
        $("#backBtn").on('click', function() {
            $("#deleteAll").show()
            $("#table_sop").show()
            $("#update_form").hide()
            $("#backBtn").hide()
        })
        $("#btn_submit").on('click', function() {
            const part_num = $("#partNum").val()
            const title = $("#title").val()
            const step = $('#step').val()
            const image = $("#image")[0].files[0]
            const description = window.editor.getData();
            if (!title || !step || !description) {
                new window.Swal({
                    icon: 'error',
                    text: 'Beberapa kolom harus di isi',
                    padding: '2em',
                    customClass: 'sweet-alerts',
                });
                return
            }
            if (image) {
                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/jpg',
                    'image/webp'
                ];
                const maxSize = 3 * 1024 * 1024;
                if (!allowedTypes.includes(image.type)) {
                    new window.Swal({
                        icon: 'error',
                        text: 'Format gambar harus JPG, JPEG, PNG, atau WEBP',
                        padding: '2em',
                        customClass: 'sweet-alerts',
                    });
                    $("#image").val('');
                    return false;
                }
                if (image.size > maxSize) {
                    new window.Swal({
                        icon: 'error',
                        text: 'Ukuran gambar maksimal 3 MB',
                        padding: '2em',
                        customClass: 'sweet-alerts',
                    });
                    $("#image").val('');
                    return false;
                }
            }
            $("#submit_icon").hide()
            $("#submit_loader").show()
            const data = new FormData()
            data.append('id', $("#id_edit").val())
            data.append('partNum', part_num)
            data.append('title', title)
            data.append('step', step)
            data.append('image', image)
            data.append('description', description)
            $.ajax({
                url: "{{ url('api/menu_sop/update') }}",
                type: "POST",
                processData: false,
                contentType: false,
                data: data,
                success: function(response) {
                    if (response.status == 'success') {
                        new window.Swal({
                            icon: 'success',
                            text: response.message,
                            padding: '2em',
                            customClass: 'sweet-alerts',
                        });
                    } else {
                        new window.Swal({
                            icon: 'error',
                            text: response.message,
                            padding: '2em',
                            customClass: 'sweet-alerts',
                        });
                    }
                    $("#submit_icon").show()
                    $("#submit_loader").hide()
                },
                error: function(xhr) {
                    console.log(xhr)
                    $("#submit_icon").show()
                    $("#submit_loader").hide()
                }
            })
        })

        function Delete(id) {
            Swal.fire({
                title: "kamu yakin hapus ini?",
                text: "Data ini akan terhapus permanen",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('api/menu_sop/delete') }}",
                        type: "POST",
                        data: {
                            id: id
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                new window.Swal({
                                    icon: 'success',
                                    text: response.message,
                                    padding: '2em',
                                    customClass: 'sweet-alerts',
                                });
                                window.location.reload()
                            } else {
                                new window.Swal({
                                    icon: 'error',
                                    text: response.message,
                                    padding: '2em',
                                    customClass: 'sweet-alerts',
                                });
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr)
                            new window.Swal({
                                icon: 'error',
                                text: 'Something went wrong',
                                padding: '2em',
                                customClass: 'sweet-alerts',
                            });
                        }
                    })
                }
            });
        }
    </script>
</x-layout.default>
