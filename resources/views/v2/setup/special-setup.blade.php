<x-layout.default>
    <link rel='stylesheet' type='text/css' href='{{ Vite::asset('resources/css/nice-select2.css') }}'>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/flatpickr.min.css') }}">
    <script src="/assets/js/flatpickr.js"></script>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/nouislider.min.css') }}">
    <script src="/assets/js/nouislider.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/select.css') }}">
    <div x-data="form">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>Special Setup</span>
            </li>
        </ul>

        <div class="pt-5 space-y-8">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Special Setup</h5>
                </div>
                <div class="mb-5" x-data="{ active: 1 }">
                    <div class="space-y-2 font-semibold">
                        <div class="border border-[#d3d3d3] dark:border-[#1b2e4b] rounded dark:text-white-light">
                            <button type="button" id="btnStartSetup"
                                class="p-4 w-full flex items-center text-white-light dark:bg-[#1b2e4b]"
                                :class="{ '!text-white-light': active === 1 }"
                                x-on:click="active === 1 ? active = null : active = 1">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="https://www.w3.org/2000/svg"
                                    class="w-5 h-5 ltr:mr-2 rtl:ml-2 text-white-light shrink-0">
                                    <path opacity="0.5"
                                        d="M7.142 18.9706C5.18539 18.8995 3.99998 18.6568 3.17157 17.8284C2 16.6569 2 14.7712 2 11C2 7.22876 2 5.34315 3.17157 4.17157C4.34315 3 6.22876 3 10 3H14C17.7712 3 19.6569 3 20.8284 4.17157C22 5.34315 22 7.22876 22 11C22 14.7712 22 16.6569 20.8284 17.8284C20.0203 18.6366 18.8723 18.8873 17 18.965"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path
                                        d="M9.94955 16.0503C10.8806 15.1192 11.3461 14.6537 11.9209 14.6234C11.9735 14.6206 12.0261 14.6206 12.0787 14.6234C12.6535 14.6537 13.119 15.1192 14.0501 16.0503C16.0759 18.0761 17.0888 19.089 16.8053 19.963C16.7809 20.0381 16.7506 20.1112 16.7147 20.1815C16.2973 21 14.8648 21 11.9998 21C9.13482 21 7.70233 21 7.28489 20.1815C7.249 20.1112 7.21873 20.0381 7.19436 19.963C6.91078 19.089 7.92371 18.0761 9.94955 16.0503Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                </svg>
                                Special Setup
                                <div class="ltr:ml-auto rtl:mr-auto" :class="{ 'rotate-180': active === 1 }">

                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="https://www.w3.org/2000/svg" class="w-4 h-4">
                                        <path d="M19 9L12 15L5 9" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>

                            <div x-cloak x-show="active === 1" x-collapse>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 dark:text-white-light">
                                    <div>
                                        <label for="inputDefault">Category</label>
                                        <select class="selectize" id="category_id" onchange="categorySelect()">
                                            <option disabled selected>Pilih Kategori</option>
                                            {{-- <option value="STP" selected>Stamping</option> --}}
                                            <option value="ASSY">Assembly</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label>Shift</label>
                                        <select class="form-input" id="shift" onchange="shiftChange()">
                                        </select>
                                    </div>
                                    <div>
                                        <label>Machine ID</label>
                                        <select class="form-select" id="machineID" onchange="machineSelect()">
                                        </select>
                                    </div>
                                    <div>
                                        <label for="production_date">Production Date (Job)</label>
                                        <input id="production_date" type="date" class="form-input text-white"
                                            style="color-scheme: dark;" readonly />
                                    </div>
                                    <div>
                                        <label>Employee</label>
                                        <select class="form-select" id="employeeID" name="employeeID">
                                        </select>
                                    </div>
                                    <div>
                                        <label for="avail-select">Work Time</label>
                                        {{-- <input id="workTime" type="text" class="form-input text-white"
                                            style="color-scheme: dark;" readonly /> --}}
                                        <select class="form-input" id="workTime" onchange="workTimeSelect()">
                                        </select>
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 sm:grid-cols-1 lg:grid-cols-3 gap-4 p-5 dark:text-white-light items-center"
                                    id="listJig">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="btn btn-primary" id="btn_start" onclick="startMachine()">
                        <svg id="start_icon" xmlns="https://www.w3.org/2000/svg"
                            class="w-5 h-5 ltr:mr-1.5 rtl:ml-1.5 shrink-0" width="24" height="24"
                            viewBox="0 0 24 24" fill="none">
                            <path d="M10 2H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path
                                d="M13.8876 10.9348C14.9625 11.8117 15.5 12.2501 15.5 13C15.5 13.7499 14.9625 14.1883 13.8876 15.0652C13.5909 15.3073 13.2966 15.5352 13.0261 15.7251C12.7888 15.8917 12.5201 16.064 12.2419 16.2332C11.1695 16.8853 10.6333 17.2114 10.1524 16.8504C9.6715 16.4894 9.62779 15.7336 9.54038 14.2222C9.51566 13.7947 9.5 13.3757 9.5 13C9.5 12.6243 9.51566 12.2053 9.54038 11.7778C9.62779 10.2664 9.6715 9.51061 10.1524 9.1496C10.6333 8.78859 11.1695 9.11466 12.2419 9.76679C12.5201 9.93597 12.7888 10.1083 13.0261 10.2749C13.2966 10.4648 13.5909 10.6927 13.8876 10.9348Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path
                                d="M7.5 5.20404C8.82378 4.43827 10.3607 4 12 4C16.9706 4 21 8.02944 21 13C21 17.9706 16.9706 22 12 22C7.02944 22 3 17.9706 3 13C3 11.3607 3.43827 9.82378 4.20404 8.5"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>

                        <svg id="start_loader" viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                            stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"
                            class="w-5 h-5 ltr:mr-1.5 rtl:ml-1.5  animate-[spin_2s_linear_infinite] inline-block align-middle shrink-0 hidden">
                            <line x1="12" y1="2" x2="12" y2="6"></line>
                            <line x1="12" y1="18" x2="12" y2="22"></line>
                            <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                            <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                            <line x1="2" y1="12" x2="6" y2="12"></line>
                            <line x1="18" y1="12" x2="22" y2="12"></line>
                            <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                            <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                        </svg>

                        Start
                    </button>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="{{ Vite::asset('resources/css/highlight.min.css') }}">
    <script src="/assets/js/highlight.min.js"></script>
    <script src="/assets/js/nice-select2.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function(e) {
            var els = document.querySelectorAll(".selectize");
            els.forEach(function(select) {
                NiceSelect.bind(select);
            });
            $(".form-select").select2({
                width: '100%',
                allowClear: true
            });
        });

        function categorySelect() {
            const data = new URLSearchParams()
            data.append('category', document.getElementById('category_id').value)
            axios.post(`https://${window.location.host}/api/special-setup/category`, data, {
                    'Content-Type': 'application/x-www-form-urlencoded'
                })
                .then(response => {
                    // console.log(response)
                    const output = response.data
                    const container = document.getElementById('machineID')
                    container.innerHTML = '<option disabled selected>Pilih Mesin</option>'
                    output.machine.forEach(element => {
                        container.innerHTML +=
                            `<option value="${element.machine_id}">${element.machine_id}</option>`
                    })
                    const shiftContainer = document.getElementById('shift')
                    output.shift.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.Shift;
                        option.textContent = item.Description;
                        shiftContainer.appendChild(option);
                    });
                    // shiftContainer.onchange = function() {
                    //     const selectedShift = this.value;

                    //     const selectedData = output.shift.find(item => item.Shift == selectedShift);

                    //     const workTime = document.getElementById('workTime');
                    //     workTime.innerHTML = ''; // reset dulu
                    //     workTime.innerHTML = '<option disabled selected>Pilih waktu</option>';

                    //     if (selectedData) {
                    //         const option = document.createElement('option');
                    //         option.value = selectedData.total_hours;
                    //         option.textContent = selectedData.total_hours;

                    //         workTime.appendChild(option);

                    //         // 🔥 set selected dengan cara yang benar
                    //         workTime.value = selectedData.total_hours;
                    //     }
                    // };
                    shiftContainer.onchange = function() {
    const selectedShift = this.value;

    const selectedData = output.shift.find(item => item.Shift == selectedShift);

    const workTime = document.getElementById('workTime');

    // reset + default
    workTime.innerHTML = '<option disabled selected>Pilih waktu</option>';

    if (selectedData) {
        const option = document.createElement('option');
        option.value = selectedData.total_hours;
        option.textContent = selectedData.total_hours;

        workTime.appendChild(option);
    }
};
                })
                .catch(error => {
                    console.log(error)
                })
        }

        function shiftChange() {
            const container = document.getElementById('workTime')
            const shift = document.getElementById('shift').value
            container.innerHTML = '<option selected disabled>Pilih waktu</option>'
            if (shift === 'SHIFT 1') {
                container.innerHTML += '<option value="8">8</option>'
            } else {
                container.innerHTML += '<option value="8.25">8.25</option>'
                container.innerHTML += '<option value="7">7</option>'
            }
        }

        function machineSelect() {
            const machineSelect = document.getElementById('machineID');
            const machine = machineSelect.selectedOptions.length > 0 ?
                machineSelect.selectedOptions[0].value :
                null;
            const machineID = machine.split('/')[0]
            const data = new URLSearchParams()
            data.append('machine', machineID)
            data.append('shift', document.getElementById('shift').value)
            axios.post(`https://${window.location.host}/api/special-setup/machine`, data, {
                'Content-Type': 'application/x-www-form-urlencoded'
            }).then(response => {
                // console.log(response)
                const output = response.data
                const today = new Date();
                const yyyy = today.getFullYear();
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const dd = String(today.getDate()).padStart(2, '0');
                const formattedDate = `${yyyy}-${mm}-${dd}`;
                document.getElementById('production_date').value = formattedDate
                // const container = document.getElementById('workTime')
                // container.innerHTML = '<option selected disabled>Pilih waktu</option>'
                // output.availData.forEach(element => {
                //     container.innerHTML += `<option value="${element}">${element}</option>`
                // })
                $('#employeeID').select2({
                    width: '100%',
                    allowClear: true,
                    ajax: {
                        url: `https://${window.location.host}/api/machine/get-employees`,
                        type: 'POST',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term,
                                page: params.page || 1
                            };
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: data.pagination.more
                                }
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 2
                });
            }).catch(error => {
                console.log(error)
            })
        }
        async function jo_list() {
            const data = new URLSearchParams();
            data.append('production_date', document.getElementById('production_date').value);
            data.append('shift', document.getElementById('shift').value);
            data.append('category_id', document.getElementById('category_id').value);

            const apiUrl = `https://${window.location.host}/api/machine/v2/jo_list`;

            try {
                const res = await axios.post(apiUrl, data, {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                });
                return Array.isArray(res.data?.data) ? res.data.data : [];
            } catch (e) {
                console.error('jo_list error:', e);
                return [];
            }
        }

        async function populateAllJobSelectsWithSelect2(jobList, defaults = []) {
            const selects = document.querySelectorAll('select[name="jobNumber[]"]');

            selects.forEach((select, idx) => {
                const defaultValue = defaults[idx] ?? ''; // misal default JobNum

                // Hapus Select2 sebelumnya jika ada
                if ($(select).hasClass('select2-hidden-accessible')) {
                    $(select).select2('destroy');
                }

                // Kosongkan dan buat ulang opsi dari jobList
                select.innerHTML = '';
                const optPlaceholder = document.createElement('option');
                optPlaceholder.value = '';
                optPlaceholder.textContent = 'Pilih Job Number';
                optPlaceholder.disabled = true;
                optPlaceholder.selected = true;
                select.appendChild(optPlaceholder);

                jobList.forEach(item => {
                    const valueText = `${item.JobNum}_${item.ProdCode}`;
                    const opt = document.createElement('option');
                    opt.value = valueText;
                    opt.textContent = valueText;
                    // Select default berdasarkan JobNum (atau sesuaikan)
                    if (defaultValue && valueText.startsWith(defaultValue)) {
                        opt.selected = true;
                        optPlaceholder.selected = false;
                    }
                    select.appendChild(opt);
                });

                // Init Select2 dengan search box
                $(select).select2({
                    width: '100%',
                    placeholder: 'Pilih Job Number',
                    allowClear: true,
                    dropdownParent: $('#listJig')
                });
            });
        }

        async function workTimeSelect() {
            const data = new URLSearchParams();
            data.append('machineID', document.getElementById('machineID')?.value || '');
            data.append('shift', document.getElementById('shift').value);
            data.append('category', document.getElementById('category_id').value);

            try {
                const res = await axios.post(
                    `https://${window.location.host}/api/special-setup/work-time`,
                    data, {
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    }
                );

                const listTool = res.data.data || [];
                const request = res.data.req || {};
                const machineID = request.machineID || '';
                const labelText = request.category === 'STP' ? 'Standard SPH' : 'Standard JPH';

                const container = document.getElementById('listJig');
                container.innerHTML = '';

                // Simpan default job number per tool jika ada
                const defaultJobNumbers = [];

                listTool.forEach((element, index) => {
                    const standardSPH = parseFloat(element.standard_sph) || 0;
                    const jobNumberSelect = `jobNumber_${index}`;

                    // Default job number dari backend (opsional)
                    defaultJobNumbers.push(element.default_job_num ?? '');

                    const cardHTML = `
                <div class="border-2 border-white block w-full p-6 bg-white rounded-lg shadow-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 mb-4">
                    <div class="w-full border-b border-gray-600 flex items-center gap-3 py-2">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" style="color:#43A047" stroke="currentColor">
                                <path d="M12 2v10m6.364-4.364a9 9 0 11-12.728 0" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                            ${machineID} - ${element.tool_id}
                        </h2>
                        <input type="hidden" name="tool_id[]" value="${element.tool_id}" />
                    </div>

                    <div class="grid sm:grid-cols-1 md:grid-cols-1 gap-4 mb-4">
                        <div>
                            <label class="block mb-2 lg:text-lg md:text-sm font-bold dark:text-white">${labelText}</label>
                            <input type="text" name="standard_sph[]" value="${standardSPH}" class="form-input text-white" />
                        </div>
                    </div>

                    <div class="mt-2">
                        <label class="block mb-2 lg:text-lg md:text-sm font-bold dark:text-white">Job Number</label>
                        <select name="jobNumber[]" id="${jobNumberSelect}" class="form-select lg:text-lg md:text-sm text-white"></select>
                    </div>
                </div>
            `;

                    container.insertAdjacentHTML('beforeend', cardHTML);
                });

                // Ambil job list dan isi dropdown dengan Select2
                const jobList = await jo_list();
                await populateAllJobSelectsWithSelect2(jobList, defaultJobNumbers);

            } catch (error) {
                console.error("workTimeSelect Error:", error);
            }
        }

        function startMachine() {
            const icon = document.getElementById("start_icon");
            const loader = document.getElementById("start_loader");
            const shift = document.getElementById('shift').value;
            const production_date = document.getElementById('production_date').value;
            const machineID = document.getElementById('machineID').value;
            const employeeID = document.getElementById('employeeID').value;
            const workTime = document.getElementById('workTime').value;
            const toolIDInput = document.querySelectorAll('input[name="tool_id[]"]');
            const toolID = Array.from(toolIDInput).map(input => input.value);
            const standardSPHSelect = document.querySelectorAll('input[name="standard_sph[]"]');
            const standardSPH = Array.from(standardSPHSelect).map(select => select.value);
            const jobNumSelect = document.querySelectorAll('select[name="jobNumber[]"]');
            const jobNum = Array.from(jobNumSelect).map(select => select.value);
            if (!shift || !machineID || !employeeID || !workTime || !standardSPH || jobNum.length < 1) {
                new window.Swal({
                    icon: 'error',
                    text: 'Please fill all fields!',
                    padding: '2em',
                    customClass: 'sweet-alerts',
                });
                return;
            }
            loader.classList.remove('hidden');
            icon.classList.add('hidden');
            const data = new URLSearchParams();
            data.append('shift', shift);
            data.append('production_date', production_date);
            data.append('machineID', machineID);
            data.append('employeeID', employeeID);
            data.append('workTime', workTime);
            toolID.forEach(tool_id => {
                data.append('toolID[]', tool_id);
            });
            standardSPH.forEach(sph => {
                data.append('standardSPH[]', sph);
            });
            jobNum.forEach(job_num => {
                data.append('jobNumber[]', job_num);
            });
            axios.post(`https://${window.location.host}/api/special-setup/start`, data, {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                .then(response => {
                    const message = response.data
                    const status = message.status;
                    if (status === true) {
                        new window.Swal({
                            icon: 'success',
                            text: 'Job Number Updated!',
                            padding: '2em',
                            customClass: 'sweet-alerts',
                        });
                    } else {
                        new window.Swal({
                            icon: 'error',
                            text: response.data.message,
                            padding: '2em',
                            customClass: 'sweet-alerts',
                        });

                    }
                    loader.classList.add('hidden');
                    icon.classList.remove('hidden');
                })
                .catch(error => {
                    let msg = 'Gagal Update';
                    if (error.response && error.response.data && error.response.data.message) {
                        msg = error.response.data.message;
                    }
                    new window.Swal({
                        icon: 'error',
                        text: msg,
                        padding: '2em',
                        customClass: 'sweet-alerts',
                    });
                    console.log(error)
                })
        }
    </script>

</x-layout.default>
