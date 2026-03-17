<x-layout.default>
    <link rel='stylesheet' type='text/css' href='{{ Vite::asset('resources/css/nice-select2.css') }}'>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/flatpickr.min.css') }}">
    <script src="/assets/js/flatpickr.js"></script>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/nouislider.min.css') }}">
    <script src="/assets/js/nouislider.min.js"></script>
    <link rel="stylesheet" href="/assets/css/select.css">
    <style>
        .form-input w-full text-black dark:text-white {
            color: white
        }
    </style>
    <div id="machineBoard" class="flex justify-between">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>Machine</span>
            </li>
        </ul>
        <ul id="filterCategory">
            <select
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 hidden"
                onchange="getCategory()" id="categorySelected">
                <option selected disabled>Filter Kategori</option>
                <option value="machine">Machine ID</option>
                <option value="tool">Tool ID</option>
            </select>
        </ul>
    </div>
    <div class="grid mt-3 lg:grid-cols-3 grid-rows-1 md:grid-cols-1 gap-2" id="listMachine"></div>
    <div class="grid mt-3 lg:grid-cols-3 grid-rows-1 md:grid-cols-1 gap-2 hidden" id="listMachineJig"></div>
    <div class="pt-5 space-y-8 hidden" id="timeEntryBoard">
        <div class="panel">
            <div class="mb-3" x-data="{ active: 1 }">
                <div class="space-y-2 font-semibold">
                    <div class="border border-[#d3d3d3] dark:border-[#1b2e4b] rounded dark:text-white-light">
                        <button type="button" id="btnStartSetup"
                            class="p-4 w-full flex items-center text-white-light dark:bg-[#1b2e4b]"
                            :class="{ '!text-white-light': active === 1 }"
                            x-on:click="active === null ? active = null : active = 1">

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
                            Time Entry
                            <div class="ltr:ml-auto rtl:mr-auto" :class="{ 'rotate-180': active === 1 }">
                            </div>
                        </button>

                        <div x-cloak x-show="active === 1" x-collapse>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 text-gray-900 dark:text-white">
                                <div>
                                    <label>Job Number</label>
                                    <input class="form-input w-full text-black dark:text-white" id="jobNumber"
                                        type="text" readonly />
                                    <input type="text" id="laborHedSeq" hidden>
                                </div>
                                <div>
                                    <label for="jo-select">Employee</label>
                                    <select class="form-select" id="employeeID" onchange="employeeID()" required>
                                    </select>
                                </div>
                                <div>
                                    <label>Production Date</label>
                                    <input class="form-input w-full text-black dark:text-white" id="productionDate"
                                        type="date" onchange="productionDate()" required />
                                </div>
                                <div>
                                    <label for="jo-select">Shift</label>
                                    <select class="form-input w-full text-black dark:text-white" id="shiftSelect"
                                        required>
                                    </select>
                                </div>
                                <div>
                                    <label for="standard_sph">NIK</label>
                                    <input id="nik" type="text" class="form-input w-full text-black dark:text-white"
                                        required />
                                </div>
                                <div class="mb-5">
                                    <label for="avail-select">Password</label>
                                    <div class="flex justify-between">
                                        <input class="form-input w-full text-black dark:text-white" id="password"
                                            type="password" required />
                                        <button onclick="stepOne()" id="btnStepOne" class="btn btn-primary btn-sm">
                                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M19 12H5m14 0-4 4m4-4-4-4" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-5">
                                    <label for="avail-select">Work Date</label>
                                    <input class="form-input w-full text-black dark:text-white" id="workDate"
                                        type="date" required />
                                </div>
                                <div class="mb-5">
                                    <label for="avail-select">Pay Hour</label>
                                    <input class="form-input w-full text-black dark:text-white" id="payHour" type="text"
                                        readonly />
                                </div>
                                <div class="mb-5" style="display: none">
                                    <label for="avail-select">Plan Clock In Date</label>
                                    <input class="form-input w-full text-black dark:text-white" id="planClockInDate"
                                        type="date" required hidden />
                                </div>
                                <div class="mb-5">
                                    <label for="avail-select">Actual Clock In Date</label>
                                    <input class="form-input w-full text-black dark:text-white" id="actualClockInDate"
                                        type="date" required />
                                </div>
                                <div class="mb-5" style="display: none">
                                    <label for="avail-select">Plan Clock In Time</label>
                                    <input class="form-input w-full text-black dark:text-white" id="planClockInTime"
                                        type="time" required hidden />
                                </div>
                                <div class="mb-5">
                                    <label for="avail-select">Actual Clock In Time</label>
                                    <input class="form-input w-full text-black dark:text-white" id="actualClockInTime"
                                        type="time" onchange="calculatePayhour()" required />
                                </div>
                                <div class="mb-5" style="display: none">
                                    <label for="avail-select">Plan Clock Out Time</label>
                                    <input class="form-input w-full text-black dark:text-white" id="planClockOutTime"
                                        type="time" required hidden />
                                </div>
                                <div class="mb-5">
                                    <label for="avail-select">Actual Clock Out Time</label>
                                    <input class="form-input w-full text-black dark:text-white" id="actualClockOutTime"
                                        type="time" value="{{ now()->format('H:i') }}" onchange="calculatePayhour()"
                                        required />
                                </div>
                                <div class="mb-5" style="display: none">
                                    <label for="avail-select">Plan Lunch Out Time</label>
                                    <input class="form-input w-full text-black dark:text-white" id="planLunchOutTime"
                                        type="time" required hidden />
                                </div>
                                <div class="mb-5">
                                    <label for="avail-select">Actual Lunch Out Time</label>
                                    <input class="form-input w-full text-black dark:text-white" id="actualLunchOutTime"
                                        type="time" onchange="calculatePayhour()" required />
                                </div>
                                <div class="mb-5" style="display: none">
                                    <label for="avail-select">Plan Lunch In Time</label>
                                    <input class="form-input w-full text-black dark:text-white" id="planLunchInTime"
                                        type="time" required hidden />
                                </div>
                                <div class="mb-5">
                                    <label for="avail-select">Actual Lunch In Time</label>
                                    <div class="flex justify-between">
                                        <input class="form-input w-full text-black dark:text-white"
                                            id="actualLunchInTime" type="time" onchange="calculatePayhour()" required />
                                        <button onclick="stepTwo()" id="btnStepTwo"
                                            class="btn btn-primary btn-sm hidden">
                                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M19 12H5m14 0-4 4m4-4-4-4" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label for="" class="block text-sm font-medium mb-2">Labor
                                        type</label>
                                    <div class="flex justify-between">
                                        <select id="laborTypePseudo"
                                            class="form-input w-full text-black dark:text-white mt-1 block w-full"
                                            required>
                                            <option value="P" selected>P - Production (Default)</option>
                                            <option value="I">I Indirect</option>
                                            <!-- <option value="S">S Setup</option>
                                            <option value="J">J Project</option>
                                            <option value="V">V Service</option> -->
                                        </select>
                                        <button onclick="stepThree()" id="btnStepThree"
                                            class="btn btn-primary btn-sm hidden">
                                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M19 12H5m14 0-4 4m4-4-4-4" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-5" style="display: none">
                                    <label for="avail-select">SEQ</label>
                                    <div class="flex justify-between">
                                        <input class="form-input w-full text-black dark:text-white" id="opSeq"
                                            type="text" readonly />

                                    </div>
                                </div>
                                <div class="mb-5">
                                    <label for="avail-select">Labor QTY</label>
                                    <input class="form-input w-full text-black dark:text-white" id="laborQty"
                                        type="number" required />
                                </div>
                                <div class="mb-5">
                                    <label for="avail-select">Discrep QTY</label>
                                    <input class="form-input w-full text-black dark:text-white" id="discrepQty"
                                        type="number" value="0" required />
                                </div>
                                <div class="mb-2">
                                    <label for="" class="block text-sm font-medium mb-2">Discrep Reason
                                        Code</label>
                                    <select id="discrep_reason_code"
                                        class="form-input w-full text-black dark:text-white w-full">
                                        <option value="">Select Discrep Reason</option>
                                        <option value="SPDC">Material/Part discoloration</option>
                                        <option value="SQCC">Material/Part quality changed</option>
                                        <option value="SMRJ">Material/Part rejected</option>
                                    </select>
                                </div>
                                <div class="flex grid grid-cols-2 gap-2" style="display: none">
                                    <div class="mb-5">
                                        <label for="avail-select">Grup ID</label>
                                        <input class="form-input w-full text-black dark:text-white" id="resourceGrpID"
                                            type="text" readonly hidden />
                                    </div>
                                    <div class="mb-5">
                                        <label for="avail-select">Machine ID</label>
                                        <input class="form-input w-full text-black dark:text-white" id="resourceID"
                                            type="text" readonly />
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label for="production_time_modal" class="block text-sm font-medium mb-2">Labor
                                        note</label>
                                    <textarea id="labor_note" rows="2"
                                        class="form-textarea mt-1 block w-full"></textarea>
                                </div>
                                <div class="flex grid grid-cols-2 gap-2" style="display: none">
                                    <div class="mb-5">
                                        <label for="avail-select">Labor Header ID</label>
                                        <input class="form-input w-full text-black dark:text-white" id="laborHeaderId"
                                            type="text" readonly hidden />
                                    </div>
                                    <div class="mb-5">
                                        <label for="avail-select">Labor Detail ID</label>
                                        <input class="form-input w-full text-black dark:text-white" id="laborDetailId"
                                            type="text" readonly hidden />
                                    </div>
                                </div>
                                <input type="text" id="laborHrs" hidden>
                                <input type="text" id="burdenHrs" hidden>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="button" class="btn btn-primary hidden" id="btnSubmit" onclick="submitTimeEntry()">
                    Submit
                </button>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/highlight.min.css') }}">
    <script src="/assets/js/highlight.min.js"></script>
    <script src="/assets/js/nice-select2.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function (e) {
            const pathId = window.location.pathname.split('/')
            const categoryLine = pathId[pathId.length - 1]
            const blok = pathId[1];
            if (categoryLine === 'RBT-5H45') {
                document.getElementById('categorySelected').classList.remove('hidden')
            }
            const ApiUrl = `https://${window.location.host}/api/machine/get-machine-running`
            axios.post(ApiUrl, {
                categoryLine: categoryLine,
                blok: blok
            })
                .then(response => {
                    const container = document.getElementById('listMachine')
                    container.innerHTML = ''
                    let cardMachine = ''
                    const downtimeList = response.data.downtime
                    const sortedMachines = response.data.machine.sort((a, b) => {
                        if (a.laborEntryMethod === 'T' && b.laborEntryMethod !== 'T') return -1;
                        if (a.laborEntryMethod !== 'T' && b.laborEntryMethod === 'T') return 1;
                        return 0;
                    });
                    sortedMachines.forEach(item => {
                        let statusHTML = '';
                        let cardDT = ''
                        if (item.laborEntryMethod === 'T') {
                            statusHTML = `
            <button type="button" class="btn btn-warning py-2 md:py-1" id="btn_finish" onclick="timeEntry('${item.machine_id}')">
                <svg id="finish_icon_${item.machine_id}" xmlns="https://www.w3.org/2000/svg"
                    class="w-5 h-5 ltr:mr-1.5 rtl:ml-1.5 shrink-0" width="24" height="24" viewBox="0 0 24 24"
                    fill="none">
                    <path
                        d="M17 3.33782C15.5291 2.48697 13.8214 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 10.5778 21.7031 9.22492 21.1679 8"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-dasharray="0.5 3.5" />
                    <path
                        d="M22 12C22 10.1786 21.513 8.47087 20.6622 7M12 2C13.8214 2 15.5291 2.48697 17 3.33782"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M12 9V13H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <svg id="finish_loader_${item.machine_id}" viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                    stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"
                    class="w-5 h-5 ltr:mr-1.5 rtl:ml-1.5 animate-[spin_2s_linear_infinite] inline-block align-middle shrink-0 hidden">
                    <line x1="12" y1="2" x2="12" y2="6"></line>
                    <line x1="12" y1="18" x2="12" y2="22"></line>
                    <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                    <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                    <line x1="2" y1="12" x2="6" y2="12"></line>
                    <line x1="18" y1="12" x2="22" y2="12"></line>
                    <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                    <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                </svg>
                Finish
                <span id="production_date_${item.machine_id}" hidden>${item.production_date}</span>
            </button>
              <span class="lg:text-lg md:text-sm" id="start_${item.machine_id}"></span>
        `;
                        } else if (item.laborEntryMethod === 'B') {
                            statusHTML = `
            <span class="inline-flex items-center justify-center text-sm md:text-xs lg:text-xs font-medium rounded-full px-2 py-1 bg-blue-600 text-white dark:text-white">
                    <span class="w-2 h-2 md:w-1.5 md:h-1.5 bg-white rounded-full me-2"></span>
                    Backflush
                </span>
                        `;
                        } else {
                            statusHTML = `
            <button type="button" class="btn btn-warning py-2 md:py-1" id="btn_finish" onclick="timeEntry('${item.machine_id}')">
                <svg id="finish_icon_${item.machine_id}" xmlns="https://www.w3.org/2000/svg"
                    class="w-5 h-5 ltr:mr-1.5 rtl:ml-1.5 shrink-0" width="24" height="24" viewBox="0 0 24 24"
                    fill="none">
                    <path
                        d="M17 3.33782C15.5291 2.48697 13.8214 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 10.5778 21.7031 9.22492 21.1679 8"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-dasharray="0.5 3.5" />
                    <path
                        d="M22 12C22 10.1786 21.513 8.47087 20.6622 7M12 2C13.8214 2 15.5291 2.48697 17 3.33782"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M12 9V13H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <svg id="finish_loader_${item.machine_id}" viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                    stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"
                    class="w-5 h-5 ltr:mr-1.5 rtl:ml-1.5 animate-[spin_2s_linear_infinite] inline-block align-middle shrink-0 hidden">
                    <line x1="12" y1="2" x2="12" y2="6"></line>
                    <line x1="12" y1="18" x2="12" y2="22"></line>
                    <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                    <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                    <line x1="2" y1="12" x2="6" y2="12"></line>
                    <line x1="18" y1="12" x2="22" y2="12"></line>
                    <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                    <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                </svg>
                Finish
                <span id="production_date_${item.machine_id}" hidden>${item.production_date}</span>
            </button>
              <span class="lg:text-lg md:text-sm" id="start_${item.machine_id}"></span>
        `;
                        }
                        if ((item.laborEntryMethod === 'T' || item.laborEntryMethod === 'B') && (item
                            .status_downtime === 1 || item.status_downtime === true || item
                                .status_downtime === '1')) {
                            cardDT +=
                                `
                                <button class="btn btn-primary py-2 md:py-1" onclick="downtimeFinish('${item.machine_id}')">Downtime</button>`
                        } else {
                            cardDT += `<select name="downtime" id="downtime_${item.machine_id}" data-machine-id="${item.machine_id}" data-index="${item.machine_id}" class="form-select lg:text-xs md:text-sm" onchange="downTimeSelect('${item.machine_id}')" style="width:8rem">
                        <option selected disabled>Downtime Select</option>
                        ${downtimeList.map(dt => `<option value="${dt.id}">${dt.name}</option>`).join('')}
                    </select>`
                        }
                        cardMachine += `
        <div class="border-2 border-white block w-full p-6 bg-white rounded-lg shadow-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 mb-4">
            <div class="w-full border-b border-gray-600 flex justify-between gap-3 py-2">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="color:#f97316" xmlns="https://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.983 13.725a1.743 1.743 0 100-3.486 1.743 1.743 0 000 3.486zM19.427 12a7.44 7.44 0 01-.153 1.482l2.023 1.58a.547.547 0 01.13.675l-1.917 3.32a.547.547 0 01-.66.24l-2.385-.96a7.435 7.435 0 01-1.28.744l-.36 2.534a.547.547 0 01-.543.458H9.73a.547.547 0 01-.543-.458l-.36-2.534a7.412 7.412 0 01-1.28-.744l-2.386.96a.547.547 0 01-.66-.24l-1.917-3.32a.547.547 0 01.13-.675l2.023-1.58A7.474 7.474 0 014.548 12c0-.508.052-1.002.153-1.482l-2.023-1.58a.547.547 0 01-.13-.675l1.917-3.32a.547.547 0 01.66-.24l2.386.96c.398-.298.833-.55 1.28-.744l.36-2.534a.547.547 0 01.543-.458h3.54a.547.547 0 01.543.458l.36 2.534c.448.194.882.446 1.28.744l2.385-.96a.547.547 0 01.66.24l1.917 3.32a.547.547 0 01-.13.675l-2.023 1.58c.101.48.153.974.153 1.482z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-base md:text-sm lg:text-2xl font-bold text-black dark:text-white" id="machineID">${item.machine_id}</h1>
                        <span class="text-base md:text-sm lg:text-2xl font-bold text-black dark:text-white">${item.machine_name}</span>
                    </div>
                </div>
                <div class="flex flex-col">
                    ${statusHTML}
                <div>
                </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-2 mt-2 text-black dark:text-white text-center">
                <div class="border border-gray-400 dark:border-white">
                    <h4 class="text-base lg:text-xl md:text-sm font-semibold">Part num</h4>
                    <span class="lg:text-lg md:text-sm" id="part_no_${item.machine_id}">${item.part_no ?? '-'}</span>
                </div>
                <div class="border border-gray-400 dark:border-white">
                    <h4 class="text-base lg:text-xl md:text-sm font-semibold">Job num</h4>
                    <span id='job_num_${item.machine_id}' class="lg:text-lg md:text-sm">${item.job_num ?? '-'}</span>
                    <input value="${item.job_num}" id="job_num_input_${item.machine_id}" hidden/>
                    <input hidden id="shift_${item.machine_id}" value="${item.shift}"/>
                </div>
                <div class="border border-gray-400 dark:border-white">
                    <h4 class="text-base lg:text-xl md:text-sm font-semibold">Customer</h4>
                    <span class="lg:text-lg md:text-sm" id="customer_${item.machine_id}">${item.customer ?? '-'}</span>
                </div>
            </div>

           <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-2 mt-2 text-black dark:text-white text-center">
                <div class="border border-gray-400 dark:border-white">
                    <h4 class="text-base lg:text-xl md:text-sm font-semibold">Qty Plan</h4>
                    <span class="lg:text-lg md:text-sm" id="qty_plan_${item.machine_id}">${!isNaN(parseFloat(item.qty_plan)) ? parseFloat(item.qty_plan) : '-'}</span>
                </div>
                <div class="border border-gray-400 dark:border-white">
                    <h4 class="text-base lg:text-xl md:text-sm font-semibold">Qty Actual</h4>
                    <span class="lg:text-lg md:text-sm" id="qty_actual_span_${item.machine_id}">${!isNaN(parseFloat(item.qty_actual)) ? parseFloat(item.qty_actual) : '-'}</span>
                    <input hidden id="downtime_input_${item.machine_id}" value="${item.qty_actual}"/>
                    <input hidden id="production_date_input_${item.machine_id}" value="${item.production_date}"/>
                </div>
                <div class="border border-gray-400 dark:border-white">
                    <h4 class="text-base lg:text-xl md:text-sm font-semibold">Qty NG</h4>
                    <span class="lg:text-lg md:text-sm">${!isNaN(parseFloat(item.qty_ng)) ? parseFloat(item.qty_ng) : '-'}</span>
                    <span hidden id="opr_${item.machine_id}">${item.opr}</span>
                </div>
            </div>

            <div class="border border-gray-400 dark:border-white text-center text-black dark:text-white">
                <h4 class="text-base lg:text-xl md:text-sm font-semibold">Employee</h4>
                <span class="lg:text-lg md:text-sm" id="emp_name_${item.machine_id}">${item.employee_name ?? '-'}</span>
            </div>
            <div class="mt-2 flex justify-end flex-wrap">
                ${cardDT}
            </div>
        </div>
    `;
                    });

                    container.innerHTML = cardMachine
                    $(".form-select").select2({
                        width: '100%',
                        allowClear: true
                    });
                    sortedMachines.forEach(item => {
                        updateHours(item.machine_id, item.started_at);
                        // if (item.status_downtime == true || item.status_downtime == 1) {
                        //     updateDTHours(item.machine_id, item.)
                        // }
                        setInterval(() => {
                            updateHours(item.machine_id, item.started_at);
                        }, 1000);
                    });
                })
                .catch(error => {
                    console.log(error)
                })
        });

        function updateHours(machine_id, startedAt) {
            if (!startedAt || startedAt === '-') {
                document.getElementById(`start_${machine_id}`).innerText = '-';
                return;
            }

            const start = new Date(startedAt);
            const now = new Date();
            const diffMs = now - start;

            const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
            const diffMinutes = Math.floor((diffMs / (1000 * 60)) % 60);

            document.getElementById(`start_${machine_id}`).innerText =
                `${diffHours} Hours ${diffMinutes} Minute`;
        }

        function downTimeSelect(machine_id) {
            const downTimeId = document.getElementById(`downtime_${machine_id}`).value;
            if (!downTimeId) return;

            Swal.fire({
                title: 'Keterangan Downtime',
                input: 'textarea',
                inputPlaceholder: 'Masukkan keterangan downtime',
                inputAttributes: {
                    'aria-label': 'Keterangan Downtime',
                    'style': 'color:white'
                },
                showCancelButton: true,
                confirmButtonText: 'Submit',
                cancelButtonText: 'Cancel'
            }).then((textareaResult) => {
                if (!textareaResult.isConfirmed || !textareaResult.value.trim()) return;

                const note = textareaResult.value.trim();

                const data = new URLSearchParams();
                data.append('machineId', machine_id);
                data.append('downTimeId', downTimeId);
                data.append('job_num', document.getElementById(`job_num_input_${machine_id}`).value);
                data.append('shift', document.getElementById(`shift_${machine_id}`).value);
                data.append('production_date', document.getElementById(`production_date_input_${machine_id}`)
                    .value);
                data.append('opr', document.getElementById(`opr_span_${machine_id}`)?.innerText.trim() ?? '');
                data.append('note', note);
                data.append('downtime_qty', document.getElementById(`downtime_input_${machine_id}`).value)
                axios.post(`https://${window.location.host}/api/machine/set-downtime`, data, {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                }).then(response => {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Downtime berhasil diset.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    document.dispatchEvent(new Event("DOMContentLoaded"));
                }).catch(error => {
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat mengirim data.',
                        icon: 'error'
                    });
                    console.log(error);
                });
            });
        }

        function downtimeFinish(machine_id) {
            const data = new URLSearchParams()
            data.append('machine_id', machine_id)
            data.append('job_num', document.getElementById(`job_num_input_${machine_id}`).value)
            data.append('shift', document.getElementById(`shift_${machine_id}`).value)
            data.append('production_date', document.getElementById(`production_date_input_${machine_id}`).value)
            data.append('downtime_qty', document.getElementById(`downtime_input_${machine_id}`).value)
            axios.post(`https://${window.location.host}/api/machine/finish-downtime`, data, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
                .then(response => {
                    console.log(response)
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Downtime berhasil diset.',
                        icon: 'success',
                        showConfirmButton: false
                    });
                    document.dispatchEvent(new Event("DOMContentLoaded"));
                }).catch(error => {
                    console.log(error)
                })
        }

        function setFinish(machine_id) {
            const jobNum = document.getElementById(`job_num_${machine_id}`).value
            if (jobNum === null) {
                new window.Swal({
                    icon: 'error',
                    text: 'Check Job Number!',
                    padding: '2em',
                    customClass: 'sweet-alerts',
                });
                return false;
            }
            const data = new URLSearchParams()
            data.append('production_date', document.getElementById(`production_date_${machine_id}`).textContent)
            data.append('machine_id', machine_id)
            const urlApi = `https://${window.location.host}/api/machine/v2/set_finish`
            axios.post(urlApi, data, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
                .then(response => {
                    const data = response.data
                    const machine = data.machine
                    if (data.status === true) {
                        location.reload()
                    } else {
                        console.log(response)
                    }
                })
                .catch(error =>
                    console.error(error)
                )
        }
    </script>
    <script>
        function getCategory() {
            const listMachine = document.getElementById('listMachine');
            const listJig = document.getElementById('listMachineJig');
            const category = document.getElementById('categorySelected').value;

            if (category === 'tool') {
                listMachine.classList.add('hidden');
                listJig.classList.remove('hidden');

                axios.post(`https://${window.location.host}/api/machine/v2/tool-running-machine`, {}, {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                    .then(response => {
                        console.log(response);
                        const container = document.getElementById('listMachineJig');
                        const machineList = response.data.machine;
                        const downtimeList = response.data.downtime;
                        container.innerHTML = '';
                        machineList.forEach(item => {
                            const ToolID = `${item.machine_id}_${item.tool_id}`
                            let cardDT = '';
                            downtimeList.forEach(dt => {
                                cardDT += `<option value="${dt.id}">${dt.name}</option>`;
                            });

                            const cardHTML = `
                        <div class="border-2 border-white block w-full p-6 bg-white rounded-lg shadow-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 mb-4 text-white">
                            <div class="flex justify-between items-center mb-3">
                                <div>
                                    <h1 class="text-base md:text-sm lg:text-2xl font-bold text-black dark:text-white">${item.machine_id}/${item.tool_id}</h1>
                                </div>
                                ${item.job_num != null || item.production_date != null ?
                                    `<button type="button" class="btn btn-warning py-2 md:py-1" onclick="timeEntryTool('${ToolID}')">
                                                                                                                                                                                                                                                                                                                                    <svg id="finish_icon_${ToolID}" xmlns="https://www.w3.org/2000/svg"
                                                                                                                                                                                                                                                                                                                class="w-5 h-5 ltr:mr-1.5 rtl:ml-1.5 shrink-0" width="24" height="24" viewBox="0 0 24 24"
                                                                                                                                                                                                                                                                                                                fill="none">
                                                                                                                                                                                                                                                                                                                <path
                                                                                                                                                                                                                                                                                                                    d="M17 3.33782C15.5291 2.48697 13.8214 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 10.5778 21.7031 9.22492 21.1679 8"
                                                                                                                                                                                                                                                                                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                                                                                                                                                                                                                                                                                    stroke-dasharray="0.5 3.5" />
                                                                                                                                                                                                                                                                                                                <path
                                                                                                                                                                                                                                                                                                                    d="M22 12C22 10.1786 21.513 8.47087 20.6622 7M12 2C13.8214 2 15.5291 2.48697 17 3.33782"
                                                                                                                                                                                                                                                                                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                                                                                                                                                                                                                                                                                <path d="M12 9V13H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                                                                                                                                                                                                                                                                                    stroke-linejoin="round" />
                                                                                                                                                                                                                                                                                                            </svg>
                                                                                                                                                                                                                                                                                                            <svg id="finish_loader_${ToolID}" viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                                                                                                                                                                                                                                                                                                stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                                                                                                                                                                                                                                                                                                class="w-5 h-5 ltr:mr-1.5 rtl:ml-1.5 animate-[spin_2s_linear_infinite] inline-block align-middle shrink-0 hidden">
                                                                                                                                                                                                                                                                                                                <line x1="12" y1="2" x2="12" y2="6"></line>
                                                                                                                                                                                                                                                                                                                <line x1="12" y1="18" x2="12" y2="22"></line>
                                                                                                                                                                                                                                                                                                                <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                                                                                                                                                                                                                                                                                                                <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                                                                                                                                                                                                                                                                                                                <line x1="2" y1="12" x2="6" y2="12"></line>
                                                                                                                                                                                                                                                                                                                <line x1="18" y1="12" x2="22" y2="12"></line>
                                                                                                                                                                                                                                                                                                                <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                                                                                                                                                                                                                                                                                                                <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                                                                                                                                                                                                                                                                                                            </svg>
                                                                                                                                                                                                                                                                                                                                Finish
                                                                                                                                                                                                                                                                                                                            </button>`: ``}
                                <input id="production_date_${ToolID}" value="${item.production_date}" hidden />
                                    <input id="job_num_${ToolID}" value="${item.job_num}" hidden />
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mb-2 mt-2 text-black dark:text-white text-center">
                                <div class='border border-gray-400 dark:border-white' id="part_no"><strong>Part num</strong><br>${item.part_no ?? '-'}</div>
                                <div class='border border-gray-400 dark:border-white'><strong>Job num</strong><br>${item.job_num ?? '-'}</div>
                                <div class='border border-gray-400 dark:border-white'><strong>Customer</strong><br>${item.customer ?? '-'}</div>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mb-2 mt-2 text-black dark:text-white text-center">
                                <div class='border border-gray-400 dark:border-white'><strong>Qty Plan</strong><br>${parseFloat(item.qty_plan) || '-'}</div>
                                <div class='border border-gray-400 dark:border-white'><strong>Qty Actual</strong><br><span id="qty_actual_span_tool_${item.ToolID}">${parseFloat(item.qty_actual) || '-'}</span></div>
                                <div class='border border-gray-400 dark:border-white'><strong>Qty NG</strong><br>${parseFloat(item.qty_ng) || '-'}</div>
                            </div>
                            <div class="border border-gray-400 dark:border-white text-center text-black dark:text-white">
                                <strong>Employee</strong><br>${item.employee_name ?? '-'}</div>
                                <div class="text-left">
                                ${(item.dt_active === 1 || item.dt_active === '1' || item.dt_active === true) ?
                                    `<button type="button" class="btn btn-primary py-2 md:py-1" onclick="finishDowntimeTool('${ToolID}')">Downtime</button>` :
                                    `<select name="downtime" id="downtime_${ToolID}" data-machine-id="${ToolID}" class="form-select lg:text-xs md:text-sm" onchange="downTimeJig('${ToolID}')">
                                                                                                                                                                                                                                                                                                    <option value="">Downtime Select</option>
                                                                                                                                                                                                                                                                                                    ${cardDT}
                                                                                                                                                                                                                                                                                                </select>`
                                }

                            </div>
                        </div>`;
                            const wrapper = document.createElement('div');
                            wrapper.innerHTML = cardHTML;
                            container.appendChild(wrapper.firstElementChild);
                        });
                        $(".form-select").select2({
                            width: '100%',
                            allowClear: true
                        });
                    })
                    .catch(error => {
                        console.error('Gagal mengambil data:', error);
                    });

            } else {
                listMachine.classList.remove('hidden');
                listJig.classList.add('hidden');
            }
        }

        function downTimeJig(ToolID) {
            const [machine, tool_id] = ToolID.split('_').map(e => e.trim());

            Swal.fire({
                title: 'Keterangan Downtime',
                input: 'textarea',
                inputPlaceholder: 'Masukkan keterangan downtime',
                inputAttributes: {
                    'aria-label': 'Keterangan Downtime',
                    'style': 'color:white'
                },
                showCancelButton: true,
                confirmButtonText: 'Submit',
                cancelButtonText: 'Cancel'
            }).then((textareaResult) => {
                if (!textareaResult.isConfirmed || !textareaResult.value.trim()) return;

                const note = textareaResult.value.trim();

                const productionDate = document.getElementById(`production_date_${ToolID}`).value;
                const jobNum = document.getElementById(`job_num_${ToolID}`).value;
                const downtimeID = document.getElementById(`downtime_${ToolID}`).value

                const data = new URLSearchParams();
                data.append('machineID', machine);
                data.append('toolID', tool_id);
                data.append('jobNum', jobNum);
                data.append('productionDate', productionDate);
                data.append('downtimeID', downtimeID);
                data.append('note', note);

                axios.post(`https://${window.location.host}/api/machine/v2/set-downtime-jig`, data, {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                    .then((response) => {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Downtime berhasil diset.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        console.log(response);
                        getCategory()
                    })
                    .catch((error) => {
                        console.error(error);
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menyimpan downtime.',
                            icon: 'error'
                        });
                    });
            });
        }

        function finishDowntimeTool(ToolID) {
            const machine = ToolID.split('_')[0]
            const tool = ToolID.split('_')[1]
            const data = new URLSearchParams()
            data.append('machineID', machine)
            data.append('jobNum', document.getElementById(`job_num_${ToolID}`).value)
            data.append('productionDate', document.getElementById(`production_date_${ToolID}`).value)
            data.append('toolID', tool)
            axios.post(`https://${window.location.host}/api/machine/v2/finish-downtime-tool`, data, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
                .then(response => {
                    console.log(response)
                    const message = response.data
                    const status = message.status
                    if (status === true) {
                        new window.Swal({
                            icon: 'success',
                            text: 'Finish downtime!',
                            padding: '2em',
                            customClass: 'sweet-alerts',
                        });
                        getCategory()
                    } else {
                        new window.Swal({
                            icon: 'error',
                            text: 'Failed finish downtime!',
                            padding: '2em',
                            customClass: 'sweet-alerts',
                        });
                    }
                }).catch(error => {
                    console.log(error)
                })
        }

        function setFinishJig(ToolID) {
            const machine = ToolID.split('_')[0]
            const tool = ToolID.split('_')[1]
            const data = new URLSearchParams()
            data.append('machineID', machine)
            data.append('toolID', tool)
            data.append('production_date', document.getElementById(`production_date_${ToolID}`).value)
            data.append('jobNum', document.getElementById(`job_num_${ToolID}`).value)
            axios.post(`https://${window.location.host}/api/machine/v2/set-finish-tool`, data, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
                .then(response => {
                    const status = response.data.status
                    console.log(response)
                    if (status === true) {
                        new window.Swal({
                            icon: 'success',
                            text: 'Machine Finish!',
                            padding: '2em',
                            customClass: 'sweet-alerts',
                        });
                        location.reload()
                    } else {
                        new window.Swal({
                            icon: 'error',
                            text: 'Machined Finish Failed!',
                            padding: '2em',
                            customClass: 'sweet-alerts',
                        });
                    }
                })
                .catch(error => {
                    console.log(error)
                })
        }
    </script>
    <script>
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
                const msg = JSON.parse(event.data)
                const response = msg.data.message
                const eventTitle = msg.event
                if (eventTitle === 'update_stroke') {
                    // console.log(response)
                    const machineID = response.machine_id;
                    const conditionID = response.condition_id;
                    if (conditionID === true || conditionID === 1 || conditionID === '1') {
                        const el = document.getElementById(`qty_actual_span_${machineID}`);
                        if (el) el.textContent = response.qty_actual;
                    }
                } else if (eventTitle === 'update_stroke_tool') {
                    // console.log(response)
                    const toolID = `${response.machine_id}_${response.tool_id}`;
                    const conditionId = response.condition_id
                    if (conditionId === true || conditionId === 1 || conditionId === '1') {
                        const el = document.getElementById(`qty_actual_span_tool_${toolID}`);
                        if (el) el.textContent = response.qty_actual;
                    }
                } else if (eventTitle === 'start-machine') {
                    console.log(response)
                    const machineID = response.machine_id;
                    const updateField = (id, value) => {
                        const el = document.getElementById(`${id}_${machineID}`);
                        if (el) el.textContent = value;
                    };
                    updateField('part_no', response.part_no);
                    updateField('job_num', response.job_num);
                    updateField('shift', response.shift);
                    updateField('qty_actual_span', response.qty_actual);
                    updateField('qty_plan', response.qty_plan);
                    updateField('emp_name', response.emp_name);
                } else if (eventTitle === 'start-machine-tool') {
                    console.log(response)
                    const toolID = `${response.machine_id}_${response.tool_id}`;
                    const updateField = (id, value) => {
                        const el = document.getElementById(`${id}_${toolID}`);
                        if (el) el.textContent = value;
                    };
                    updateField('part_no', response.part_no);
                    updateField('job_num', response.job_num);
                    updateField('shift', response.shift);
                    updateField('qty_actual_span_tool', response.qty_actual);
                    updateField('qty_plan', response.qty_plan);
                    updateField('emp_name', response.emp_name);
                }
            } catch (e) {
                console.error('Invalid message from WebSocket:', event.data);
            }
        }
        socket.onclose = () => {
            console.log('Disconnected from WebSocket')
        }
        socket.onerror = (err) => {
            console.log('WebSocket error:', err)
        }
    </script>
    <script>
        function calculatePayhour() {
            const clockInDate = document.getElementById('actualClockInDate').value;
            const actualClockInTime = document.getElementById('actualClockInTime').value;
            const actualClockOutTime = document.getElementById('actualClockOutTime').value;
            const lunchOutTime = document.getElementById('actualLunchOutTime').value;
            const lunchInTime = document.getElementById('actualLunchInTime').value;

            if (!clockInDate || !actualClockInTime || !actualClockOutTime) {
                document.getElementById('payHour').value = 0;
                return;
            }

            let start = new Date(`${clockInDate}T${actualClockInTime}`);
            let end = new Date(`${clockInDate}T${actualClockOutTime}`);
            if (end <= start) end.setDate(end.getDate() + 1);

            let lunchHours = 0;
            if (lunchOutTime && lunchInTime) {
                let lunchStart = new Date(`${clockInDate}T${lunchOutTime}`);
                let lunchEnd = new Date(`${clockInDate}T${lunchInTime}`);
                if (lunchEnd <= lunchStart) lunchEnd.setDate(lunchEnd.getDate() + 1);
                lunchHours = (lunchEnd - lunchStart) / (1000 * 60 * 60);
            }

            const totalHours = (end - start) / (1000 * 60 * 60);
            const payHours = totalHours - lunchHours;

            document.getElementById('payHour').value = isNaN(payHours) || payHours < 0 ? 0 : payHours.toFixed(2);
        }

        function timeEntry(machine_id) {
            document.getElementById(`finish_icon_${machine_id}`).classList.add('hidden');
            document.getElementById(`finish_loader_${machine_id}`).classList.remove('hidden');
            const data = new URLSearchParams()
            data.append('machineID', machine_id)
            axios.post(`https://${window.location.host}/api/machine/v2/time-entry`, data, {
                Headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }).then(response => {
                const message = response.data
                document.getElementById(`finish_icon_${machine_id}`).classList.remove('hidden');
                document.getElementById(`finish_loader_${machine_id}`).classList.add('hidden');
                document.getElementById('timeEntryBoard').classList.remove('hidden')
                document.getElementById('listMachine').classList.add('hidden')
                document.getElementById('filterCategory').classList.add('hidden')
                const machine = message.machine
                const jobNumber = document.getElementById('jobNumber').value = machine.job_num
                const selectEmp = document.getElementById('employeeID')
                selectEmp.innerHTML = `<option value="${machine.employee_id}">${machine.employee_name}</option>`
                $('#employeeID').select2({
                    width: '100%',
                    allowClear: true,
                    ajax: {
                        url: `https://${window.location.host}/api/machine/v2/get-emp-select`,
                        type: 'POST',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term,
                                page: params.page || 1
                            };
                        },
                        processResults: function (data, params) {
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
                    placeholder: 'Pilih karyawan'
                });
                const shiftFromMachine = machine.shift.split(' ')[1];
                const shiftSelect = document.getElementById('shiftSelect');
                const filteredShifts = message.shift_options.filter(shift => shift.id !== shiftFromMachine);
                let optionsHtml = `<option value="${shiftFromMachine}" selected>SHIFT ${shiftFromMachine}</option>`;
                filteredShifts.forEach(shift => {
                    optionsHtml += `<option value="${shift.id}">${shift.text}</option>`;
                });
                shiftSelect.innerHTML = optionsHtml;
                const actualClockIn = machine.started_at.split(' ')
                let group
                if (machine.category_line_id === 'ASSY-002' || machine.category_line_id === 'ASSY-014') {
                    group = machine.machine_id.split('-')[1]
                } else {
                    group = machine.machine_id.split('-')[0]
                }
                document.getElementById('productionDate').value = machine.production_date
                document.getElementById('actualClockInDate').value = actualClockIn[0]
                document.getElementById('actualClockInTime').value = actualClockIn[1]
                document.getElementById('laborQty').value = parseInt(machine.qty_actual)
                document.getElementById('discrepQty').value = parseInt(machine.qty_ng) || 0
                document.getElementById('resourceGrpID').value = group
                document.getElementById('resourceID').value = machine.machine_id
            })
                .catch(error => {
                    console.log(error)
                })
        }

        function formatDate(dateStr) {
            return dateStr.split("T")[0];
        }

        function formatTime(decimalTime) {
            const hours = Math.floor(decimalTime);
            const minutes = Math.round((decimalTime - hours) * 60);
            return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
        }

        function stepOne() {
            const employee = document.getElementById('employeeID').value
            const productionDate = document.getElementById('productionDate').value
            const shift = document.getElementById('shiftSelect').value
            const nik = document.getElementById('nik').value
            const password = document.getElementById('password').value
            const data = new URLSearchParams()
            data.append('employee', employee)
            data.append('productionDate', productionDate)
            data.append('nik', nik)
            data.append('password', password)
            axios.post(`https://${window.location.host}/api/machine/v2/create-new-header`, data, {
                'headers': {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
                .then(response => {
                    const message = response.data
                    const laborHedSeq = message.data.laborHedSeq
                    document.getElementById('laborHedSeq').value = laborHedSeq
                    console.log(laborHedSeq)
                    const data = new URLSearchParams({
                        laborHedSeq,
                        shift,
                        nik,
                        password
                    })
                    axios.post(`https://${window.location.host}/api/machine/v2/change-shift`, data, {
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    }).then(response => {
                        const message = response.data
                        const workDate = document.getElementById('workDate')
                        const payHour = document.getElementById('payHour')
                        const planClockInDate = document.getElementById('planClockInDate')
                        const planClockInTime = document.getElementById('planClockInTime')
                        const planClockOutTime = document.getElementById('planClockOutTime')
                        const planLunchOutTime = document.getElementById('planLunchOutTime')
                        const planLunchInTime = document.getElementById('planLunchInTime')
                        workDate.value = formatDate(message.data.workDate)
                        payHour.value = message.data.payHour
                        planClockInDate.value = formatDate(message.data.clockInDate)
                        planClockInTime.value = formatTime(message.data.clockInTime)
                        planClockOutTime.value = formatTime(message.data.clockOutTime)
                        planLunchOutTime.value = formatTime(message.data.lunchOutTime)
                        planLunchInTime.value = formatTime(message.data.lunchInTime)
                        document.getElementById('btnStepTwo').classList.remove('hidden')
                        document.getElementById('btnStepOne').classList.add('hidden')
                    }).catch(error => {
                        console.log(error)
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Change Shift',
                            text: error.response?.data?.message || error.message || 'Terjadi kesalahan'
                        });
                    })
                })
                .catch(error => {
                    console.log(error)
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Change Shift',
                        text: error.response?.data?.message || error.message || 'Terjadi kesalahan'
                    });
                })
        }

        function stepTwo() {
            const data = new URLSearchParams([
                ['workDate', document.getElementById('workDate').value],
                ['laborHedSeq', document.getElementById('laborHedSeq').value],
                ['shift', document.getElementById('shiftSelect').value],
                ['payHours', document.getElementById('payHour').value],
                ['clockInDate', document.getElementById('planClockInDate').value],
                ['clockInTime', document.getElementById('planClockInTime').value],
                ['clockOutTime', document.getElementById('planClockOutTime').value],
                ['lunchInTime', document.getElementById('planLunchInTime').value],
                ['lunchOutTime', document.getElementById('planLunchOutTime').value],
                ['actualClockinDate', document.getElementById('actualClockInDate').value],
                ['actualClockInTime', document.getElementById('actualClockInTime').value],
                ['actualClockOutTime', document.getElementById('actualClockOutTime').value],
                ['actualLunchInTime', document.getElementById('actualLunchInTime').value],
                ['actualLunchOutTime', document.getElementById('actualLunchOutTime').value],
                ['nik', document.getElementById('nik').value],
                ['password', document.getElementById('password').value]
            ])
            console.log(data)
            axios.post(`https://${window.location.host}/api/machine/v2/update-header`, data, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
                .then(response => {
                    document.getElementById('btnStepThree').classList.remove('hidden')
                    document.getElementById('btnStepTwo').classList.add('hidden')
                    const data = new URLSearchParams()
                    data.append('jobNum', document.getElementById('jobNumber').value)
                    data.append('nik', document.getElementById('nik').value)
                    data.append('password', document.getElementById('password').value)
                    axios.post(`https://${window.location.host}/api/machine/v2/get-op-seq`, data, {
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    }).then(response => {
                        document.getElementById('opSeq').value = response.data.oprSeq
                    }).catch(error => {
                        console.log(error)
                    })
                })
                .catch(error => {
                    console.log(error)
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Change Shift',
                        text: error.response?.data?.message || error.message || 'Terjadi kesalahan'
                    });
                })
        }

        function stepThree() {
            const data = new URLSearchParams([
                ['laborTypePseudo', document.getElementById('laborTypePseudo').value],
                ['laborHedSeq', document.getElementById('laborHedSeq').value],
                ['jobNum', document.getElementById('jobNumber').value],
                ['opSeq', document.getElementById('opSeq').value],
                ['date', document.getElementById('productionDate').value],
                ['nik', document.getElementById('nik').value],
                ['password', document.getElementById('password').value]
            ])
            axios.post(`https://${window.location.host}/api/machine/v2/get-newt-labor-dtl`, data, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }).then(response => {
                const message = response.data.data
                document.getElementById('laborDetailId').value = message.laborDtlSeq
                document.getElementById('laborHeaderId').value = message.laborHeadSeq
                const data = new URLSearchParams([
                    ['laborHedSeq', message.laborHeadSeq],
                    ['laborDtlSeq', message.laborDtlSeq],
                    ['shift', document.getElementById('shiftSelect').value],
                    ['clockinTime', document.getElementById('actualClockInTime').value],
                    ['clockOutTime', document.getElementById('actualClockOutTime').value],
                    ['nik', document.getElementById('nik').value],
                    ['password', document.getElementById('password').value]
                ])
                axios.post(`https://${window.location.host}/api/machine/v2/change-labor-time`, data, {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                }).then(response => {
                    const message = response.data.data
                    document.getElementById('laborHrs').value = message.laborHrs
                    document.getElementById('burdenHrs').value = message.burdenHrs
                    document.getElementById('btnSubmit').classList.remove('hidden')
                    document.getElementById('btnStepThree').classList.add('hidden')
                }).catch(error => {
                    console.log(error)
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Change Shift',
                        text: error.response?.data?.message || error.message || 'Terjadi kesalahan'
                    });
                })
            }).catch(error => {
                console.log(error)
                Swal.fire({
                    icon: 'error',
                    title: 'Error Change Shift',
                    text: error.response?.data?.message || error.message || 'Terjadi kesalahan'
                });
            })
        }

        function submitTimeEntry() {
            const data = new URLSearchParams([
                ['laborHedSeq', document.getElementById('laborHedSeq').value],
                ['laborDtlSeq', document.getElementById('laborDetailId').value],
                ['date', document.getElementById('productionDate').value],
                ['clockInDate', document.getElementById('planClockInDate').value],
                ['clockinTime', document.getElementById('planClockInTime').value],
                ['clockOutTime', document.getElementById('planClockOutTime').value],
                ['laborHrs', document.getElementById('laborHrs').value],
                ['burdenHrs', document.getElementById('burdenHrs').value],
                ['laborQty', document.getElementById('laborQty').value],
                ['scrapQty', 0],
                ['discrepQty', document.getElementById('discrepQty').value],
                ['discrpRsnCode', document.getElementById('discrep_reason_code').value],
                ['scrapReasonCode', ""],
                ['resourceGrpID', document.getElementById('resourceGrpID').value],
                ['resourceID', document.getElementById('resourceID').value],
                ['resourceGrpDescription', ""],
                ['indirectCode', ""],
                ['laborNote', document.getElementById('labor_note').value ?? ""],
                ['rowMod', "U"],
                ['nik', document.getElementById('nik').value],
                ['password', document.getElementById('password').value]
            ])
            axios.post(`https://${window.location.host}/api/machine/v2/update-dtl`, data, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }).then(response => {
                const data = new URLSearchParams([
                    ['laborHedSeq', document.getElementById('laborHedSeq').value],
                    ['laborDtlSeq', document.getElementById('laborDetailId').value],
                    ['nik', document.getElementById('nik').value],
                    ['password', document.getElementById('password').value]
                ])
                axios.post(`https://${window.location.host}/api/machine/v2/submit-time-entry`, data, {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                }).then(response => {
                    const machineID = document.getElementById('resourceID').value
                    setFinish(machineID)
                }).catch(error => {
                    // const machineID = document.getElementById('resourceID').value
                    // setFinish(machineID)
                    console.log(error)
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Change Shift',
                        text: error.response?.data?.message || error.message || 'Terjadi kesalahan'
                    });
                })
            }).catch(error => {
                console.log(error)
                Swal.fire({
                    icon: 'error',
                    title: 'Error Change Shift',
                    text: error.response?.data?.message || error.message || 'Terjadi kesalahan'
                });
            })
        }

        function timeEntryTool(ToolID) {
            const machine_id = ToolID.split('_')[0]
            const tool_id = ToolID.split('_')[1]
            document.getElementById(`finish_icon_${ToolID}`).classList.add('hidden');
            document.getElementById(`finish_loader_${ToolID}`).classList.remove('hidden');
            const data = new URLSearchParams()
            data.append('machineID', machine_id)
            data.append('toolID', tool_id)
            axios.post(`https://${window.location.host}/api/machine/v2/time-entry-tool`, data, {
                Headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }).then(response => {
                const message = response.data
                document.getElementById(`finish_icon_${ToolID}`).classList.remove('hidden');
                document.getElementById(`finish_loader_${ToolID}`).classList.add('hidden');
                document.getElementById('timeEntryBoard').classList.remove('hidden')
                document.getElementById('listMachineJig').classList.add('hidden')
                document.getElementById('filterCategory').classList.add('hidden')
                const machine = message.machine
                const jobNumber = document.getElementById('jobNumber').value = machine.job_num
                const selectEmp = document.getElementById('employeeID')
                selectEmp.innerHTML = `<option value="${machine.employee_id}">${machine.employee_name}</option>`
                $('#employeeID').select2({
                    width: '100%',
                    allowClear: true,
                    ajax: {
                        url: `https://${window.location.host}/api/machine/v2/get-emp-select`,
                        type: 'POST',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term,
                                page: params.page || 1
                            };
                        },
                        processResults: function (data, params) {
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
                    placeholder: 'Pilih karyawan'
                });
                const shiftFromMachine = machine.shift.split(' ')[1];
                const shiftSelect = document.getElementById('shiftSelect');
                const filteredShifts = message.shift_options.filter(shift => shift.id !== shiftFromMachine);
                let optionsHtml = `<option value="${shiftFromMachine}" selected>SHIFT ${shiftFromMachine}</option>`;
                filteredShifts.forEach(shift => {
                    optionsHtml += `<option value="${shift.id}">${shift.text}</option>`;
                });
                shiftSelect.innerHTML = optionsHtml;
                const actualClockIn = machine.started_at.split(' ')
                const group = machine.machine_id.split('-')[1]
                document.getElementById('productionDate').value = machine.production_date
                document.getElementById('actualClockInDate').value = actualClockIn[0]
                document.getElementById('actualClockInTime').value = actualClockIn[1]
                document.getElementById('laborQty').value = parseInt(machine.qty_actual)
                document.getElementById('discrepQty').value = parseInt(machine.qty_ng) || 0
                document.getElementById('resourceGrpID').value = group
                document.getElementById('resourceID').value = machine.machine_id
            })
                .catch(error => {
                    console.log(error)
                })
        }
    </script>
</x-layout.default>