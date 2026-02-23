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
                <span>Standard Setup</span>
            </li>
        </ul>

        <div class="pt-5 space-y-8">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Standard Setup</h5>
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
                                Standard Setup
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
                                        <select class="selectize" id="category_id" onchange="getCategory()">
                                            <option value="STP" selected>Stamping</option>
                                            <option value="ASSY">Assembly</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label>Shift</label>
                                        <select class="form-select" id="shiftSelect" onchange="joList()">
                                        </select>
                                    </div>
                                    <div>
                                        <label for="production_date">Production Date (Job)</label>
                                        <input id="production_date" type="date"
                                            class="form-input tex-black dark:text-white" onchange="prodDate()"
                                            style="color-scheme: dark;" readonly />
                                    </div>
                                    <div>
                                        <label for="jo-select">Job Num</label>
                                        <select class="form-select" id="jobNumSelect" onchange="jobNumSelect()">
                                        </select>
                                    </div>
                                    <div>
                                        <label for="standard_sph">Customer</label>
                                        <input id="customer_input" type="text"
                                            class="form-input text-black dark:text-white" readonly />
                                    </div>
                                    <div class="mb-5">
                                        <label for="avail-select">Work Time</label>
                                        <select class="form-input text-black dark:text-white" id="workTime">
                                        </select>
                                    </div>
                                    <div class="mb-5">
                                        <input type="time" id="actual_clock_out_time" x-model="actual_clock_out_time"
                                            hidden />
                                    </div>
                                </div>
                                <div class="mb-2 text-center">
                                    <h4 class="text-white text-2xl font-bold" id="listMesinNama"></h4>
                                </div>
                                <div class="grid md:grid-cols-2 sm:grid-cols-1 lg:grid-cols-3 gap-4 p-5 dark:text-white-light items-center"
                                    id="listMesin">

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="btn btn-primary" id="btn_start" onclick="setJobNum()">
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

                        <svg id="start_loader" viewBox="0 0 24 24" width="24" height="24"
                            stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"
                            stroke-linejoin="round"
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
            joList();
        });

        function getCategory() {
            const categoryId = document.getElementById('category_id').value
            console.log(categoryId)
            const options = {
                searchable: true
            }
            const urlApi = `https://` + window.location.host + `/api/machine/get_category/${categoryId}`
            axios.post(urlApi)
                .then(response => {
                    // console.log(response)
                    const data = response.data.message
                    const container = document.getElementById('shiftSelect')
                    container.innerHTML = '<option value="" selected disabled>Pilih shift</option>'
                    const optionShift =
                        `<option value="SHIFT 1">Night</option>
                        <option value="SHIFT 2">Day</option>`;
                    container.innerHTML += optionShift
                    const today = new Date();
                    const yyyy = today.getFullYear();
                    const mm = String(today.getMonth() + 1).padStart(2, '0');
                    const dd = String(today.getDate()).padStart(2, '0');
                    const todayStr = `${yyyy}-${mm}-${dd}`;
                    document.getElementById('production_date').value = todayStr;
                })
                .catch(error => {
                    console.log(error)
                })


        }

        function prodDate() {
            const data = new URLSearchParams()
            data.append('production_date', document.getElementById('production_date').value)
            data.append('shift', document.getElementById('shiftSelect').value)
            data.append('category_id', document.getElementById('category_id').value)
            console.log("Mengirim data:", Object.fromEntries(data));
            const apiUrl = `https://${window.location.host}/api/machine/v2/jo_list`
            axios.post(apiUrl, data, {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                .then(response => {
                    const data = response.data.data
                    console.log(response)
                    const container = document.getElementById('jobNumSelect');
                    container.innerHTML = '<option value="" selected disabled>Pilih Job Number</option>';
                    data.forEach(item => {
                        const optionHTML =
                            `<option value="${item.JobNum}" ${item.selected}>${item.JobNum}</option>`;
                        container.innerHTML += optionHTML;
                    });
                    var btn_start = document.getElementById("btn_start");
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        function jobNumSelect() {
            const jobNum = document.getElementById('jobNumSelect').value;
            const urlApi = `https://${window.location.host}/api/machine/job-entry/`;
            const data = new URLSearchParams();
            data.append('job_num', jobNum);
            data.append('shift', document.getElementById('shiftSelect').value)
            data.append('category_id', document.getElementById('category_id').value);

            axios.post(urlApi, data, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }).then(response => {
                const container = document.getElementById('listMesin');
                container.innerHTML = '';
                const data = response.data.data;
                const jobOper = data.jobOper || [];
                const jobOpDtl = data.jobOpDtl || [];
                const jobHead = data.jobhead || {};
                const machineResponse = response.data.machine || [];

                document.getElementById('customer_input').value = jobHead.customer;

                const workTime = document.getElementById('workTime');
                workTime.innerHTML = '';
                response.data.availData.forEach(item => {
                    workTime.innerHTML += `<option value="${item}">${item}</option>`;
                });

                const getProdStandardByOprSeq = (oprSeq) => {
                    const found = jobOper.find(op => op.oprSeq === oprSeq);
                    return found ? found.prodStandard : '';
                };

                jobOpDtl.forEach((item, index) => {
                    const machineInfo = machineResponse.find(m => m.machine_code === item.resourceID);
                    const jph = getProdStandardByOprSeq(item.oprSeq);
                    const selectId = `employeeId_${index}`;
                    const machineId = `machine_${index}`;

                    const cardHTML = `
                <div class="border-2 border-white block w-full p-6 bg-white rounded-lg shadow-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 mb-4">
                    ${machineInfo ? `
                                                    <div class="w-full border-b border-gray-600 flex items-center justify-between py-2">
                                                        <div class="flex items-center gap-6">
                                                            <div class="w-8 h-8 rounded-full flex items-center justify-center">
                                                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                                                                    style="color: ${machineInfo.is_active === '1' ? '#43A047' : '#FDD835'}" stroke="currentColor">
                                                                    <path d="M12 2v10m6.364-4.364a9 9 0 11-12.728 0"
                                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                                                                    Machine ${machineInfo.is_active === '1' ? 'ON' : 'OFF'}
                                                                </h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    ` : `
                                                    <div class="w-full border-b border-gray-600 flex items-center gap-3 py-2">
                                                        <div class="w-8 h-8 rounded-full flex items-center justify-center">
                                                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" style="color:#FDD835" stroke="currentColor">
                                                                <path d="M12 2v10m6.364-4.364a9 9 0 11-12.728 0"
                                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </div>
                                                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                                                            Machine OFF
                                                        </h2>
                                                    </div>
                                                    `}
                    <div class="grid sm:grid-cols-1 md:grid-cols-1 gap-4 mb-4">
                        <div>
                            <label class="block mb-2 lg:text-lg md:text-sm font-bold dark:text-white">Machine ID</label>
                            <select name="machineId[]" id="${machineId}" class="form-select lg:text-lg md:text-sm">
                                <option value="${item.resourceID}" selected>${item.resourceID}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 lg:text-lg md:text-sm font-bold dark:text-white">Operation Seq</label>
                            <input type="text" name="opr" id="opr" value="${item.oprSeq}" readonly class="form-input text-black dark:text-white" />
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2 lg:text-lg md:text-sm font-bold dark:text-white">Standard JPH</label>
                        <input type="text" value="${jph}" name="standard_sph[]" class="form-input text-black dark:text-white" />
                    </div>
                    <div class="mt-2">
                        <label class="block mb-2 lg:text-lg md:text-sm font-bold dark:text-white">Employee</label>
                        <select name="employeeId[]" id="${selectId}" class="form-select lg:text-lg md:text-sm"></select>
                    </div>
                </div>
            `;
                    container.insertAdjacentHTML('beforeend', cardHTML);

                    $(`#${machineId}`).select2({
                        width: '100%',
                        allowClear: true,
                        ajax: {
                            url: `https://${window.location.host}/api/machine/get-machine`,
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
                        minimumInputLength: 1
                    });
                    $(`#${selectId}`).select2({
                        width: '100%',
                        placeholder: "Pilih Pegawai",
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
                        minimumInputLength: 1
                    });
                });
            }).catch(error => {
                console.error(error);
            });
        }

        function joList() {
            var data = new URLSearchParams();
            var options = {
                searchable: true
            };
            data.append('shift', document.getElementById('shiftSelect').value)
            data.append('production_date', document.getElementById('production_date').value);
            data.append('category_id', document.getElementById('category_id').value)
            var currentHost = window.location.host;
            const apiUrl = `https://` + currentHost + `/api/machine/v2/jo_list`;
            axios.post(apiUrl, data, {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                .then(response => {
                    const data = response.data?.data ?? [];

                    if (!Array.isArray(data)) {
                        console.error('Data bukan array:', data);
                        return;
                    }
                    const select = $('#jobNumSelect');
                    select.empty();
                    select.append(new Option('Pilih Job Number', '', true, true));
                    data.forEach(item => {
                        select.append(new Option(item.JobNum, item.JobNum, false, item.selected));
                    });
                    if (!select.hasClass('select2-hidden-accessible')) {
                        select.select2({
                            placeholder: "Pilih Job Number",
                            allowClear: true,
                            width: 'resolve',
                            dropdownAutoWidth: true
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        function setJobNum() {
            const machineSelects = document.querySelectorAll('select[name="machineId[]"]');
            const machine_id = Array.from(machineSelects).map(select => select.value);
            const inputSph = document.querySelectorAll('input[name="standard_sph[]"]');
            const standardSph = Array.from(inputSph).map(input => input.value);
            var job_num = document.getElementById('jobNumSelect').value
            var production_date = document.getElementById('production_date').value;
            const employeeId = document.querySelectorAll('select[name="employeeId[]"]');
            const employee_id = Array.from(employeeId).map(select => select.value);
            if (machine_id === null) {
                new window.Swal({
                    icon: 'error',
                    text: 'Check Machine ID!',
                    padding: '2em',
                    customClass: 'sweet-alerts',
                });
                return false;
            } else if (job_num === null) {
                new window.Swal({
                    icon: 'error',
                    text: 'Check Job Num!',
                    padding: '2em',
                    customClass: 'sweet-alerts',
                });
                return false;
            } else if (production_date === null) {
                new window.Swal({
                    icon: 'error',
                    text: 'Check Production Date!',
                    padding: '2em',
                    customClass: 'sweet-alerts',
                });
                return false;
            } else if (standardSph === null || standardSph <= 0) {
                new window.Swal({
                    icon: 'error',
                    text: 'Check Standar SPH / JPH!',
                    padding: '2em',
                    customClass: 'sweet-alerts',
                });
                return false;
            }

            var icon = document.getElementById("start_icon");
            icon.classList.add('hidden');
            var loader = document.getElementById("start_loader");
            loader.classList.remove('hidden');
            var data = new URLSearchParams();
            data.append('production_date', document.getElementById('production_date').value);
            data.append('job_num', document.getElementById('jobNumSelect').value);
            data.append('shift', document.getElementById('shiftSelect').value);
            data.append('avail_time', document.getElementById('workTime').value);
            data.append('customer', document.getElementById('customer_input').value)
            machine_id.forEach(id => {
                data.append('machine_id[]', id);
            });
            employee_id.forEach(empId => {
                data.append('employee_id[]', empId)
            })
            standardSph.forEach(sph => {
                data.append('standard_sph[]', sph);
            });
            var currentHost = window.location.host;
            const apiUrl = `https://` + currentHost + `/api/machine/v2/set_job_number`;

            axios.post(apiUrl, data, {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                .then(function(response) {
                    // var job_num = getJobNum();
                    var btn_start = document.getElementById("btn_start");
                    var btn_finish = document.getElementById("btn_finish");
                    const status = response.data.status;
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
                    var icon = document.getElementById("start_icon");
                    icon.classList.remove('hidden');
                    var loader = document.getElementById("start_loader");
                    loader.classList.add('hidden');
                })
                .catch(function(error) {
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
                    var icon = document.getElementById("start_icon");
                    icon.classList.remove('hidden');
                    var loader = document.getElementById("start_loader");
                    loader.classList.add('hidden');
                    console.error('Error:', error);
                });
        }

        function getMachine() {
            const inputs = document.querySelectorAll('input[name="machineId"]');
            const ids = Array.from(inputs).map(input => input.value).filter(val => val !== '');
            return ids.length > 0 ? ids : null;
        }
    </script>
</x-layout.default>
