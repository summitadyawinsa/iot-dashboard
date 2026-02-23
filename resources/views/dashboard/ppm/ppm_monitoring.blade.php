<x-layout.default>
    <script defer src="/assets/js/apexcharts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

    <style>
        #ppm-list tbody tr:hover {
            background-color: #727e93;
            /* Ganti dengan warna yang Anda inginkan */
            cursor: pointer;
            /* Menambahkan pointer saat hover */
        }

        #ppm-list tbody tr {
            background-color: #4B5563;
            color: whitesmoke;
            /* Ganti dengan warna yang Anda inginkan */
            cursor: pointer;
            /* Menambahkan pointer saat hover */
            font-size: small;
        }

        #ppm-list thead tr {
            color: whitesmoke;
        }
    </style>

    <style>
        select[name="ppm-list_length"] {
            width: 80px;
        }
    </style>

    <script>
        function getCountDoc() {
            const currentHost = window.location.host;
            const apiUrl = `https://${currentHost}/api/ppm/get-count-doc`;
            var data = new URLSearchParams();
            data.append('month_id', document.getElementById('month_id').value);

            axios.post(apiUrl, data, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
                .then(function (response) {
                    const data = response.data;
                    $('#total_open_doc').text(data.total_open_doc || 0);
                    $('#total_issue_doc').text(data.total_issue_doc || 0);
                    $('#total_close_doc').text(data.total_close_doc || 0);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        $(document).ready(function () {
            getCountDoc(); detail_table();
            $('#month_id').on('change', function () {
                refresh_detail_table(); getCountDoc();
            });
        });
    </script>
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>Maintenance</span>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>PPM Monitoring</span>
            </li>
        </ul>

        <div class="pt-5">
            <div class="grid sm:grid-cols-2 xl:grid-cols-8 gap-6 mb-6">
                <div class="panel h-full sm:col-span-3 xl:col-span-2">
                    <div class="flex pb-5 border-b  border-[#e0e6ed] dark:border-[#1b2e4b]">
                        <div
                            class="shrink-0 bg-primary/10 text-primary rounded-xl w-14 h-11 flex justify-center items-center dark:bg-primary dark:text-white-light ">
                            <div class="align-center xl:text-1xl sm:text-xl">
                                <span id="total_issue_doc">-</span>
                            </div>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3 font-semibold">
                            <p class="text-xl dark:text-white-light">Total Issue</p>
                            <h5 class="text-[#506690] text-xs">All PPM This Month</h5>
                        </div>
                    </div>
                </div>

                <div class="panel h-full sm:col-span-3 xl:col-span-2">
                    <div class="flex pb-5 border-b  border-[#e0e6ed] dark:border-[#1b2e4b]">
                        <div
                            class="shrink-0 bg-danger text-danger rounded-xl w-14 h-11 flex justify-center items-center dark:bg-danger dark:text-white ">
                            <div class="align-center xl:text-1xl sm:text-xl">
                                <span id="total_open_doc">-</span>
                            </div>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3 font-semibold">
                            <p class="text-xl dark:text-white-light">Total Open</p>
                            <h5 class="text-[#506690] text-xs">All PPM Issue</h5>
                        </div>
                    </div>
                </div>



                <div class="panel h-full sm:col-span-3 xl:col-span-2">
                    <div class="flex pb-5 border-b  border-[#e0e6ed] dark:border-[#1b2e4b]">
                        <div
                            class="shrink-0 bg-success/10 text-success rounded-xl w-14 h-11 flex justify-center items-center dark:bg-success dark:text-white-light ">
                            <div class="align-center xl:text-1xl sm:text-xl">
                                <span id="total_close_doc">-</span>
                            </div>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3 font-semibold">
                            <p class="text-xl dark:text-white-light">Total Close</p>
                            <h5 class="text-[#506690] text-xs">All PPM This Month</h5>
                        </div>
                    </div>
                </div>

                <div class="panel h-full sm:col-span-3 xl:col-span-2">
                    <div class="flex pb-5 border-b  border-[#e0e6ed] dark:border-[#1b2e4b]">
                        <?php $currentMonth = date('Y-m'); ?>
                        <input class="form-input" type="month" name="month_id" id="month_id"
                            style="background-color: #4B5563 ; color: whitesmoke;" value="<?= $currentMonth ?>">
                    </div>
                </div>
            </div>

            <!-- <div class="grid grid-cols-12 gap-6 mb-6">
                <div class="panel h-full col-span-12">
                    <div class="flex pb-5 border-b  border-[#e0e6ed] dark:border-[#1b2e4b]">
                        <div class="shrink-0 bg-primary/10 text-primary rounded-xl w-11 h-11 flex justify-center items-center dark:bg-primary dark:text-white-light ">
                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="https://www.w3.org/2000/svg">
                                <path d="M6.94028 2C7.35614 2 7.69326 2.32421 7.69326 2.72414V4.18487C8.36117 4.17241 9.10983 4.17241 9.95219 4.17241H13.9681C14.8104 4.17241 15.5591 4.17241 16.227 4.18487V2.72414C16.227 2.32421 16.5641 2 16.98 2C17.3958 2 17.733 2.32421 17.733 2.72414V4.24894C19.178 4.36022 20.1267 4.63333 20.8236 5.30359C21.5206 5.97385 21.8046 6.88616 21.9203 8.27586L22 9H2.92456H2V8.27586C2.11571 6.88616 2.3997 5.97385 3.09665 5.30359C3.79361 4.63333 4.74226 4.36022 6.1873 4.24894V2.72414C6.1873 2.32421 6.52442 2 6.94028 2Z" fill="currentColor"></path>
                                <path opacity="0.5" d="M21.9995 14.0001V12.0001C21.9995 11.161 21.9963 9.66527 21.9834 9H2.00917C1.99626 9.66527 1.99953 11.161 1.99953 12.0001V14.0001C1.99953 17.7713 1.99953 19.6569 3.1711 20.8285C4.34267 22.0001 6.22829 22.0001 9.99953 22.0001H13.9995C17.7708 22.0001 19.6564 22.0001 20.828 20.8285C21.9995 19.6569 21.9995 17.7713 21.9995 14.0001Z" fill="currentColor"></path>
                            </svg>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3 font-semibold">
                            <p class="text-xl dark:text-white-light">PPM Monitoring</p>
                            <h5 class="text-[#506690] text-xs">Period <span>January 2025</span></h5>
                        </div>
                    </div>

                    <div class="relative overflow-hidden">
                        <button id="btn_update_chart_accumulate" x-on:click="() => accumulateRecall(inpt_val_chart_accumulate.value)" hidden>Recall and Update</button>
                        <input type="text" id="inpt_val_chart_accumulate" value="" hidden>
                        <div x-ref="accumulateChart" class="bg-white dark:bg-black rounded-lg"></div>
                    </div>
                </div>
            </div> -->

            <div class="grid grid-cols-12 gap-6 mb-6">
                <div class="panel h-full col-span-12">
                    <div class="flex pb-5 border-b  border-[#e0e6ed] dark:border-[#1b2e4b]">
                        <div
                            class="shrink-0 bg-primary/10 text-primary rounded-xl w-11 h-11 flex justify-center items-center dark:bg-primary dark:text-white-light ">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="https://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z"
                                    fill="currentColor"></path>
                                <path opacity="0.5"
                                    d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z"
                                    fill="currentColor"></path>
                            </svg>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3 font-semibold">
                            <p class="text-xl dark:text-white-light">PPM List Issue</p>
                            <h5 class="text-[#506690] text-xs">Period <span>January 2025</span></h5>
                        </div>
                    </div>

                    <div class="relative overflow-hidden pt-5">
                        <table id="ppm-list" class="min-w-full rounded-md shadow-md overflow-hidden">
                            <thead>
                                <tr class="bg-gray-600 text-gray-600 text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">No</th>
                                    <th class="py-3 px-6 text-left">PPM Num</th>
                                    <th class="py-3 px-6 text-left">Mc Num</th>
                                    <th class="py-3 px-6 text-left">Requested By</th>
                                    <th class="py-3 px-6 text-left">Date</th>
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

        function refresh_detail_table() {
            if ($.fn.DataTable.isDataTable('#ppm-list')) {
                $('#ppm-list').DataTable().destroy();
            }
            detail_table();
        }

        function detail_table() {
            var detailTable = $("#ppm-list").DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                deferLoading: 57,
                language: {
                    'processing': '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
                },
                info: false,
                order: [],
                columnDefs: [
                    {
                        orderable: false,
                        targets: 0
                    }
                ],
                ajax: {
                    url: `https://${window.location.host}/api/ppm/list`,
                    type: 'POST',
                    data: function (d) {
                        d.month_id = $("#month_id").val();
                        d.search = $('input[type="search"]').val();
                    },
                    cache: false,
                    dataType: 'json'
                },
                columns: [
                    { data: 'no', orderable: false, searchable: false },
                    { data: 'ppm_num' },
                    { data: 'mc_num' },
                    { data: 'requested_by' },
                    { data: 'doc_date' },
                    { data: 'status' }
                ],
                createdRow: function (row, data, dataIndex) {
                    // Apply style manually during row creation
                    if (dataIndex % 2 === 0) {
                        $(row).css('background-color', '#1D1D1D'); // Even rows
                    } else {
                        $(row).css('background-color', '#2D2D2D'); // Odd rows
                    }
                }
            });

            setTimeout(function () {
                detailTable.ajax.reload();
            }, 500);

            return true;
        }

        let audioInstance;
        const playSound = () => {
            if (!audioInstance) {
                audioInstance = new Audio('/assets/sound/happy.wav'); // Path relatif ke folder public
            }
            audioInstance.loop = true; // Opsi: aktifkan loop jika suara perlu diulang
            audioInstance.play()
                .then(() => console.log('Sound started playing'))
                .catch(error => console.error('Error playing sound:', error));
        };

        const stopSound = () => {
            if (audioInstance) {
                audioInstance.pause(); // Hentikan pemutaran
                audioInstance.currentTime = 0; // Reset waktu ke awal
                console.log('Sound stopped');
            } else {
                console.warn('No sound is currently playing.');
            }
        };

        function refeshUi(id) {
            var responseData = id;
            var machine_id = responseData.message.machine_id;
            var topic = responseData.message.topic;
            if (topic == 'andon_notif') {
                if (responseData.message.condition == 1) {
                    playSound();
                } else {
                    stopSound();
                }
                refresh_detail_table(); getCountDoc();
            }
        }

        document.getElementById("start-notifications").addEventListener("click", () => {
            console.log("User interacted, enabling sound...");

            // Aktifkan suara setelah interaksi pengguna
            const playSound = () => {
                const audio = new Audio('/assets/sound/happy.wav');
                audio.play().catch(error => console.error('Error playing sound:', error));
            };
        });
    </script>
</x-layout.default>
