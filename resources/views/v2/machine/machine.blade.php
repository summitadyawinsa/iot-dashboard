<x-layout.default>
    <link rel='stylesheet' type='text/css' href='{{ Vite::asset('resources/css/nice-select2.css') }}'>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/flatpickr.min.css') }}">
    <script src="/assets/js/flatpickr.js"></script>
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/nouislider.min.css') }}">
    <script src="/assets/js/nouislider.min.js"></script>
    <link rel="stylesheet" href="/assets/css/select.css">

    <div x-data="form" class="flex justify-between">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>Machine</span>
            </li>
        </ul>
        <ul>
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
    <div id="analytics">
        <div class="flex justify-end mb-6 gap-2">
            <div class="panel p-3">
                <button type="button" class="btn btn-primary hidden" id="open_form">Open</button>
            </div>
        </div>
        <div id="modalOverlay"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white dark:bg-[#1b2e4b] rounded-lg shadow-lg p-6 w-full max-w-md m-5">
                <h3 class="text-lg font-semibold mb-4" id="name_form"></h3>
                <form onsubmit="stepOne(event)" id="stepOne">
                    <div class="mb-2" id="selectEmployee">

                    </div>
                    <div class="mb-2">
                        <label for="production_date_modal" class="block text-sm font-medium mb-2">Production
                            Date</label>
                        <input type="date" id="production_date_modal" class="form-input w-full"
                            style="color-scheme: dark;" required>
                    </div>
                    <div class="mb-2" id="shiftContainer">
                    </div>
                    <div class="mb-2">
                        <label for="production_date_modal" class="block text-sm font-medium mb-2">NIK</label>
                        <input type="text" id="nik_modal" class="form-input w-full" required>
                    </div>
                    <div class="mb-2">
                        <label for="production_date_modal" class="block text-sm font-medium mb-2">Password</label>
                        <input type="password" id="password_modal" class="form-input w-full" required>
                    </div>
                    <input type="hidden" id="laborHedSeq">
                    <div class="mb-2" id="response"></div>
                    <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                        <button type="button" onclick="closeModal()" class="btn btn-danger">Cancel</button>
                        <button type="submit" class="btn btn-primary">Next</button>
                    </div>
                </form>
                <form onsubmit="stepTwo(event)" id="stepTwo" class="hidden">
                    <input type="text" id="jobNumTimeEntry" hidden>
                    <div class="flex grid grid-cols-2 gap-2">
                        <div class="mb-2">
                            <label for="job_number" class="block text-sm font-medium mb-2">Work date</label>
                            <input type="date" id="work_date_modal" class="form-input w-full" readonly>
                        </div>
                        <div class="mb-2">
                            <label for="production_date_modal" class="block text-sm font-medium mb-2">Pay hours</label>
                            <input type="text" id="pay_hours_modal" class="form-input w-full" readonly>
                        </div>
                    </div>
                    <div class="flex grid grid-cols-2 gap-2">
                        <div class="mb-2">
                            <label for="production_date_modal" class="block text-sm font-medium mb-2">Plan clockin
                                date</label>
                            <input type="date" id="plan_clockin_date_modal" class="form-input w-full" readonly>
                        </div>
                        <div class="mb-2">
                            <label for="production_date_modal" class="block text-sm font-medium mb-2">Actual clockin
                                date</label>
                            <input type="date" id="actual_clockin_date_modal" onchange="actualClockInDateChanged()"
                                class="form-input w-full" style="color-scheme: dark;" required>
                        </div>
                    </div>
                    <div class="flex grid grid-cols-2 gap-2">
                        <div class="mb-2">
                            <label for="production_date_modal" class="block text-sm font-medium mb-2">Plan clockin
                                time</label>
                            <input type="time" id="plan_clockin_time_modal" class="form-input w-full" readonly>
                        </div>
                        <div class="mb-2">
                            <label for="production_time_modal" class="block text-sm font-medium mb-2">Actual clockin
                                time</label>
                            <input type="time" id="actual_clockin_time_modal" onchange="actualClockInOut()"
                                class="form-input w-full" style="color-scheme: dark;" required>
                        </div>
                    </div>
                    <div class="flex grid grid-cols-2 gap-2">
                        <div class="mb-2">
                            <label for="production_date_modal" class="block text-sm font-medium mb-2">Plan clockout
                                time</label>
                            <input type="time" id="plan_clockout_time_modal" class="form-input w-full" readonly>
                        </div>
                        <div class="mb-2">
                            <label for="production_time_modal" class="block text-sm font-medium mb-2">Actual clockout
                                time</label>
                            <input type="time" id="actual_clockout_time_modal" onchange="actualClockInOut()"
                                class="form-input w-full" style="color-scheme: dark;" required>
                        </div>
                    </div>
                    <div class="flex grid grid-cols-2 gap-2">
                        <div class="mb-2">
                            <label for="production_date_modal" class="block text-sm font-medium mb-2">Plan lunch
                                out</label>
                            <input type="time" id="plan_lunch_out_modal" class="form-input w-full" readonly>
                        </div>
                        <div class="mb-2">
                            <label for="production_time_modal" class="block text-sm font-medium mb-2">Actual lunch
                                out</label>
                            <input type="time" id="actual_lunch_out_modal" style="color-scheme: dark;"
                                class="form-input w-full" required>
                        </div>
                    </div>
                    <div class="flex grid grid-cols-2 gap-2">
                        <div class="mb-2">
                            <label for="production_date_modal" class="block text-sm font-medium mb-2">Plan lunch
                                in</label>
                            <input type="time" id="plan_lunch_in_modal" class="form-input w-full" readonly>
                        </div>
                        <div class="mb-2">
                            <label for="production_time_modal" class="block text-sm font-medium mb-2">Actual lunch
                                in</label>
                            <input type="time" id="actual_lunch_in_modal" style="color-scheme: dark;"
                                class="form-input w-full" required>
                        </div>
                    </div>
                    <div class="mb-2" id="responseStepTwo">

                    </div>
                    <div class="flex justify-between">
                        <div class="text-center">
                            <button type="button" onclick="backButton(1)" class="btn btn-warning">Back</button>
                        </div>
                        <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                            <button type="button" onclick="closeModal()" class="btn btn-danger">Cancel</button>
                            <button type="submit" class="btn btn-primary">Next</button>
                        </div>
                    </div>
                </form>
                <form onsubmit="stepThree(event)" class="hidden" id="stepThree">
                    <div class="flex grid grid-cols-2 gap-2">
                        <div class="mb-2">
                            <label for="production_time_modal" class="block text-sm font-medium mb-2">Job
                                number</label>
                            <input type="text" id="job_num_three" class="form-input w-full" readonly>
                        </div>
                        <div class="mb-2">
                            <label for="production_time_modal" class="block text-sm font-medium mb-2">Date</label>
                            <input type="date" id="date_three" class="form-input w-full">
                        </div>
                    </div>
                    <div class="flex grid grid-cols-2 gap-2">
                        <div class="mb-2">
                            <label for="production_time_modal" class="block text-sm font-medium mb-2">Labor
                                type</label>
                            <select id="labor_type_three" class="form-select mt-1 block w-full" required>
                                <option value="P" selected>P - Production (Default)</option>
                                <option value="I">I Indirect</option>
                                <option value="S">S Setup</option>
                                <option value="J">J Project</option>
                                <option value="V">V Service</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="production_time_modal" class="block text-sm font-medium mb-2">Seq</label>
                            <input type="number" id="seq_three" class="form-input w-full" readonly>
                        </div>
                    </div>
                    <div class="mb-2" id="responseStepThree">

                    </div>
                    <div class="flex justify-between">
                        <div class="text-center">
                            <button type="button" onclick="backButton(2)" class="btn btn-warning">Back</button>
                        </div>
                        <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                            <button type="button" onclick="closeModal()" class="btn btn-danger">Cancel</button>
                            <button type="submit" class="btn btn-primary">Next</button>
                        </div>
                    </div>
                </form>
                <form onsubmit="stepFour(event)" id="stepFour" class="hidden">
                    <div class="flex grid grid-cols-2 gap-2">
                        <div class="mb-2">
                            <label for="production_date_modal" class="block text-sm font-medium mb-2">Clock in</label>
                            <input type="time" id="clock_in_four" class="form-input w-full" readonly>
                        </div>
                        <div class="mb-2">
                            <label for="production_time_modal" class="block text-sm font-medium mb-2">Clock
                                out</label>
                            <input type="time" id="clock_out_four" class="form-input w-full" readonly>
                        </div>
                    </div>
                    <div class="mb-2" id="responseFour">

                    </div>
                    <input type="hidden" id="laborDtlSeq">
                    <div class="flex justify-between">
                        <div class="text-center">
                            <button type="button" onclick="backButton(3)" class="btn btn-warning">Back</button>
                        </div>
                        <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                            <button type="button" onclick="closeModal()" class="btn btn-danger">Cancel</button>
                            <button type="submit" class="btn btn-primary">Next</button>
                        </div>
                    </div>
                </form>
                <form onsubmit="stepFive(event)" id="stepFive" class="hidden">
                    <div class="flex grid grid-cols-3 gap-2">
                        <div class="mb-2">
                            <label for="production_date_modal" class="block text-sm font-medium mb-2">Labor
                                Qty</label>
                            <input type="number" id="labor_qty" class="form-input w-full" required>
                        </div>
                        <div class="mb-2">
                            <label for="production_time_modal" class="block text-sm font-medium mb-2">Labor
                                Scrap</label>
                            <input type="number" id="scrap_qty" class="form-input w-full" disabled>
                        </div>
                        <div class="mb-2">
                            <label for="production_time_modal" class="block text-sm font-medium mb-2">Discrep
                                Qty</label>
                            <input type="number" id="discrep_qty" value="0" class="form-input w-full">
                        </div>
                    </div>
                    <div class="flex grid grid-cols-2 gap-2">
                        <div class="mb-2">
                            <label for="production_date_modal" class="block text-sm font-medium mb-2">Scrap reason
                                code</label>
                            <select id="scrap_reason_code" class="form-select w-full">
                                <option value="">Scrap Reason</option>
                                <option value="SPDC">Material/Part discoloration</option>
                                <option value="SQCC">Material/Part quality changed</option>
                                <option value="SMRJ">Material/Part rejected</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="production_time_modal" class="block text-sm font-medium mb-2">Discrep reason
                                code</label>
                            <select id="discrep_reason_code" class="form-select w-full">
                                <option value="">Select Discrep Reason</option>
                                <option value="SPDC">Material/Part discoloration</option>
                                <option value="SQCC">Material/Part quality changed</option>
                                <option value="SMRJ">Material/Part rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex grid grid-cols-2 gap-2">
                        <div class="mb-2">
                            <label for="production_date_modal" class="block text-sm font-medium mb-2">Group ID</label>
                            <input type="text" id="group_id" class="form-input w-full" readonly>
                        </div>
                        <div class="mb-2">
                            <label for="production_time_modal" class="block text-sm font-medium mb-2">Machine
                                ID</label>
                            <input type="text" id="resource_id" class="form-input w-full" readonly>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="production_time_modal" class="block text-sm font-medium mb-2">Labor note</label>
                        <textarea id="labor_note" rows="2" class="form-textarea mt-1 block w-full"></textarea>
                    </div>
                    <div class="mb-2" id="responseStepFive">

                    </div>
                    <input type="hidden" id="laborHrs">
                    <input type="hidden" id="burdenHrs">
                    <div class="flex justify-between">
                        <div class="text-center">
                            <button type="button" onclick="backButton(4)" class="btn btn-warning">Back</button>
                        </div>
                        <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                            <button type="button" onclick="closeModal()" class="btn btn-danger">Cancel</button>
                            <button type="submit" class="btn btn-primary">Next</button>
                        </div>
                    </div>
                </form>
                <form onsubmit="stepSix(event)" id="stepSix" class="hidden">
                    <div class="flex grid grid-cols-2 gap-2">
                        <div class="mb-2">
                            <label for="production_date_modal" class="block text-sm font-medium mb-2">Labor header
                                ID</label>
                            <input type="text" id="labor_header_id_finish" class="form-input w-full" readonly>
                        </div>
                        <div class="mb-2">
                            <label for="production_time_modal" class="block text-sm font-medium mb-2">Labor detail
                                ID</label>
                            <input type="text" id="labor_detail_id_finish" class="form-input w-full" readonly>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <div class="text-center">
                            <button type="button" onclick="backButton(5)" class="btn btn-warning">Back</button>
                        </div>
                        <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                            <button type="button" onclick="closeModal()" class="btn btn-danger">Cancel</button>
                            <button type="submit" class="btn btn-primary">Next</button>
                        </div>
                    </div>
                </form>
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
            <button type="button" class="btn btn-warning py-2 md:py-1" id="btn_finish" onclick="setFinish('${item.machine_id}')">
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
                            <span class="inline-flex items-center justify-center text-sm md:text-xs lg:text-xs font-medium rounded-full px-2 py-1 bg-gray-500 text-white dark:text-white">
                    <span class="w-2 h-2 md:w-1.5 md:h-1.5 bg-white rounded-full me-2"></span>
                    Unknown
                </span>

                        `;
                        }
                        if ((item.laborEntryMethod === 'T' || item.laborEntryMethod === 'B') && (item
                            .status_downtime === 1 || item.status_downtime === true || item
                                .status_downtime === '1')) {
                            cardDT +=
                                `<button class="btn btn-primary py-2 md:py-1" onclick="downtimeFinish('${item.machine_id}')">Downtime</button>`
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
                    <h1 class="text-base md:text-sm lg:text-2xl font-bold text-black dark:text-white" id="machineID">
                        ${item.machine_id}
                    </h1>
                    <span class="text-base md:text-sm lg:text-xl font-bold text-black dark:text-white">
                        ${item.machine_name}
                    </span>
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
                })
                .catch(error => {
                    console.log(error)
                })
        });

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

        function closeModal() {
            document.getElementById('modalOverlay').classList.add('hidden');
            window.location.reload();
        }

        function beforeFinish(machine_id) {
            const jobNum = document.getElementById(`job_num_input_${machine_id}`).value
            if (jobNum === null) {
                new window.Swal({
                    icon: 'error',
                    text: 'Check Job Number!',
                    padding: '2em',
                    customClass: 'sweet-alerts',
                });
                return false;
            }
            document.getElementById('jobNumTimeEntry').value = jobNum
            const icon = document.getElementById(`finish_icon_${machine_id}`)
            icon.classList.add('hidden')
            const loader = document.getElementById(`finish_loader_${machine_id}`)
            loader.classList.remove('hidden')
            const apiURL = `https://${window.location.host}/api/machine/get-one-machine`
            const data = new URLSearchParams()
            data.append('machine_id', machine_id)
            axios.post(apiURL, data, {
                header: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
                .then(response => {
                    const icon = document.getElementById(`finish_icon_${machine_id}`)
                    icon.classList.remove('hidden')
                    const loader = document.getElementById(`finish_loader_${machine_id}`)
                    loader.classList.add('hidden')
                    const message = response.data.machine
                    const empselect = document.getElementById('selectEmployee')
                    empselect.innerHTML = `<label for="job_number" class="block text-sm font-medium mb-2">Employee</label>
                        <select id="employee_id_modal" class="form-select">
                            <option value="${message.employee_id}">${message.employee_id} - ${message.employee_name}</option>
                        </select>`
                    $('#employee_id_modal').select2({
                        width: '100%',
                        allowClear: true,
                        ajax: {
                            url: `https://${window.location.host}/api/machine/get-employees`,
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
                    document.getElementById('modalOverlay').classList.remove('hidden');
                    document.getElementById('name_form').textContent = 'Form labor & initial shift'
                    document.getElementById('production_date_modal').value = message.production_date
                    const shiftContainer = document.getElementById('shiftContainer')
                    const shiftResponse = response.data.shift_options
                    let shiftOptions = shiftResponse.map(shift => {
                        return `<option value="${shift.id}">${shift.text}</option>`;
                    }).join('');
                    const shiftNumber = message.shift.split(' ')[1];
                    shiftContainer.innerHTML = `<label for="production_date_modal" class="block text-sm font-medium mb-2">Shift</label>
                        <select id="shift_modal" class="form-select">
                            <option value="${shiftNumber}">${message.shift}</option>
                            ${shiftOptions}
                        </select>`
                    const actClockIn = message.started_at.split(' ')[1].substring(0, 5);
                    document.getElementById('actual_clockin_time_modal').value = actClockIn
                    document.getElementById('job_num_three').value = message.job_num
                    document.getElementById('date_three').value = message.production_date
                    document.getElementById('resource_id').value = message.machine_id
                    document.getElementById('labor_qty').value = parseInt(message.qty_actual) || 0;
                    document.getElementById('discrep_qty').value = parseInt(message.qty_ng) || 0;

                })
                .catch(error => {
                    console.log(error)
                })
        }

        function backButton(id) {
            const allSteps = ['stepOne', 'stepTwo', 'stepThree', 'stepFour', 'stepFive', 'stepSix'];
            allSteps.forEach(stepId => {
                const el = document.getElementById(stepId);
                if (el) el.classList.add('hidden');
            });
            const currentStep = document.getElementById(`step${stepName(id)}`);
            if (currentStep) currentStep.classList.remove('hidden');
        }
        //nama setiap level time entry
        function stepName(id) {
            switch (id) {
                case 1:
                    return 'One';
                case 2:
                    return 'Two';
                case 3:
                    return 'Three';
                case 4:
                    return 'Four';
                case 5:
                    return 'Five';
                case 6:
                    return 'Six';
                default:
                    return 'One';
            }
        }
        //Step awal Time entry
        function stepOne(event) {
            event.preventDefault();
            const EpicorUrl = `https://${window.location.host}/api/machine/create-new-header`;
            const data = new URLSearchParams();
            data.append('employeeNum', document.getElementById('employee_id_modal').value);
            data.append('startDate', document.getElementById('production_date_modal').value);
            data.append('nik', document.getElementById('nik_modal').value);
            data.append('password', document.getElementById('password_modal').value);
            axios.post(EpicorUrl, data, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            })
                .then(response => {
                    console.log(response)
                    console.log(document.getElementById('shift_modal').value)
                    const container = document.getElementById('response');
                    container.innerHTML = '';
                    const message = response.data?.data;

                    if (!message || !message.laborHedSeq) {
                        container.innerHTML = `<span class="text-danger">Failed: Incomplate data </span>`;
                        return;
                    }
                    container.innerHTML = `<span class="text-success">Create new successfully</span>`;
                    document.getElementById('laborHedSeq').value = message.laborHedSeq;
                    const EpicorShift = `https://${window.location.host}/api/machine/change-shift`;
                    const data = new URLSearchParams();
                    data.append('laborHedSeq', document.getElementById('laborHedSeq').value);
                    data.append('shift', document.getElementById('shift_modal').value);
                    data.append('nik', document.getElementById('nik_modal').value);
                    data.append('password', document.getElementById('password_modal').value);
                    return axios.post(EpicorShift, data, {
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    });
                })
                .then(shiftResponse => {
                    const responseShift = shiftResponse.data.data
                    document.getElementById('stepOne').classList.add('hidden');
                    document.getElementById('stepTwo').classList.remove('hidden')
                    document.getElementById('name_form').textContent = 'Form validation & update header'
                    const formatDate = (datetime) => datetime?.split('T')[0] || '';
                    const formatTime = (decimalTime) => {
                        if (!decimalTime && decimalTime !== 0) return '';
                        const hours = String(Math.floor(decimalTime)).padStart(2, '0');
                        const minutes = String(Math.round((decimalTime % 1) * 60)).padStart(2, '0');
                        return `${hours}:${minutes}`;
                    }
                    const production_date = document.getElementById('production_date_modal').value
                    const clockInStr = document.getElementById('actual_clockin_time_modal').value;
                    const clockOutStr = getCurrentTimeHHMM();

                    if (clockInStr) {
                        const clockInMinutes = timeStringToMinutes(clockInStr);
                        const clockOutMinutes = timeStringToMinutes(clockOutStr);

                        const diffMinutes = clockOutMinutes - clockInMinutes;
                        const payHoursDecimal = (diffMinutes / 60).toFixed(2);

                        document.getElementById('pay_hours_modal').value = payHoursDecimal;
                    } else {
                        document.getElementById('pay_hours_modal').value = '0';
                    }
                    document.getElementById('work_date_modal').value = formatDate(responseShift.workDate)
                    document.getElementById('plan_clockin_date_modal').value = formatDate(responseShift.clockInDate)
                    document.getElementById('actual_clockin_date_modal').value = production_date
                    document.getElementById('plan_clockin_time_modal').value = formatTime(responseShift.clockInTime)
                    document.getElementById('plan_clockout_time_modal').value = formatTime(responseShift.clockOutTime)
                    document.getElementById('actual_clockout_time_modal').value = getCurrentTimeHHMM()
                    document.getElementById('plan_lunch_out_modal').value = formatTime(responseShift.lunchOutTime)
                    document.getElementById('plan_lunch_in_modal').value = formatTime(responseShift.lunchInTime)
                    document.getElementById('actual_lunch_in_modal').value = formatTime(responseShift.actLunchInTime)
                    document.getElementById('actual_lunch_out_modal').value = formatTime(responseShift.actLunchOutTime)
                    document.getElementById('stepTwo').classList.remove('hidden')
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('response').innerHTML =
                        `<span class="text-danger">Terjadi error saat submit</span>`;
                });

            return false;
        }
        //Perubahan tanggal
        function actualClockInDateChanged() {
            const actualClockInDate = document.getElementById('actual_clockin_date_modal').value;
            document.getElementById('work_date_modal').value = actualClockInDate;
        }

        function actualClockInOut() {
            const clockInStr = document.getElementById('actual_clockin_time_modal').value;
            const clockOutStr = document.getElementById('actual_clockout_time_modal').value;
            if (clockInStr) {
                const clockInMinutes = timeStringToMinutes(clockInStr);
                const clockOutMinutes = timeStringToMinutes(clockOutStr);
                const diffMinutes = clockOutMinutes - clockInMinutes;
                const payHoursDecimal = (diffMinutes / 60).toFixed(2);
                document.getElementById('pay_hours_modal').value = payHoursDecimal;
            } else {
                document.getElementById('pay_hours_modal').value = '0';
            }
        }
        //Langkah kedua Time Entry
        function stepTwo(event) {
            event.preventDefault()
            const Epicor = `https://${window.location.host}/api/machine/update-header`;
            const data = {
                workDate: document.getElementById('work_date_modal').value,
                laborHedSeq: document.getElementById('laborHedSeq').value,
                shift: document.getElementById('shift_modal').value,
                payHours: parseFloat(document.getElementById('pay_hours_modal').value),
                clockInDate: document.getElementById('plan_clockin_date_modal').value,
                actualClockinDate: document.getElementById('actual_clockin_date_modal').value,
                clockInTime: document.getElementById('plan_clockin_time_modal').value,
                actualClockInTime: document.getElementById('actual_clockin_time_modal').value,
                clockOutTime: document.getElementById('plan_clockout_time_modal').value,
                actualClockOutTime: document.getElementById('actual_clockout_time_modal').value,
                lunchOutTime: document.getElementById('plan_lunch_out_modal').value,
                actLunchOutTime: document.getElementById('actual_lunch_out_modal').value,
                lunchInTime: document.getElementById('plan_lunch_in_modal').value,
                actLunchInTime: document.getElementById('actual_lunch_in_modal').value,
                nik: document.getElementById('nik_modal').value,
                password: document.getElementById('password_modal').value
            };
            axios.post(Epicor, data, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            })
                .then(response => {
                    console.log(response)
                    const data = response.data.data
                    const container = document.getElementById('responseStepTwo');
                    if (response.data.code === 200) {
                        axios.post(`https://${window.location.host}/api/machine/get-opr-seq`, {
                            jobNum: document.getElementById('jobNumTimeEntry').value,
                            nik: document.getElementById('nik_modal').value,
                            password: document.getElementById('password_modal').value
                        }, {
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            }
                        }).then(oprSeqResponse => {
                            console.log(oprSeqResponse)
                            document.getElementById('seq_three').value = oprSeqResponse.data.oprSeq;
                        })
                        document.getElementById('stepThree').classList.remove('hidden')
                        document.getElementById('stepTwo').classList.add('hidden')
                        document.getElementById('name_form').textContent = 'New labor detail'
                        document.getElementById('laborHedSeq').value = document.getElementById('laborHedSeq').value
                    } else {
                        container.innerHTML = `<span class="text-danger">Gagal</span>`
                        return
                    }
                })
                .catch(error => {
                    const container = document.getElementById('responseStepTwo');
                    container.innerHTML = `<span class="text-danger">${error}</span>`
                    console.log(error)
                })

        }
        //Langkah ketiga Time entry
        function stepThree(event) {
            event.preventDefault()
            const data = {
                jobNum: document.getElementById('job_num_three').value,
                opSeq: document.getElementById('seq_three').value,
                laborTypePseudo: document.getElementById('labor_type_three').value,
                date: document.getElementById('date_three').value,
                laborHedSeq: document.getElementById('laborHedSeq').value,
                nik: document.getElementById('nik_modal').value,
                password: document.getElementById('password_modal').value,
                resourceGrpID: "",
                resourceID: "",
                indirectCode: "",
                indirectDescription: ""
            };
            console.log(data)
            axios.post(`https://${window.location.host}/api/machine/get-new-labor-dtl`, data, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
                .then(response => {
                    console.log("Raw response:", response)

                    const container = document.getElementById('responseStepThree')

                    let parsed = response.data
                    if (typeof response.data === 'string') {
                        try {
                            parsed = JSON.parse(response.data)
                        } catch (e) {
                            container.innerHTML = `<span class="text-white">Gagal parsing response JSON</span>`
                            return
                        }
                    }

                    if (parsed.code === 200) {
                        document.getElementById('laborHedSeq').value = document.getElementById('laborHedSeq').value
                        document.getElementById('clock_in_four').value = document.getElementById(
                            'actual_clockin_time_modal').value
                        document.getElementById('clock_out_four').value = document.getElementById(
                            'actual_clockout_time_modal').value
                        document.getElementById('laborDtlSeq').value = parsed.data.laborDtlSeq
                        document.getElementById('name_form').textContent = 'Change labor time'
                        document.getElementById('stepFour').classList.remove('hidden')
                        document.getElementById('stepThree').classList.add('hidden')
                    } else {
                        container.innerHTML = `<span class="text-white">Terjadi kesalahan</span>`
                        return
                    }
                })
                .catch(error => {
                    console.log(error)
                })
        }

        function stepFour(event) {
            event.preventDefault()
            const data = {
                'laborHedSeq': document.getElementById('laborHedSeq').value,
                'laborDtlSeq': document.getElementById('laborDtlSeq').value,
                'shift': document.getElementById('shift_modal').value,
                'clockInTime': document.getElementById('clock_in_four').value,
                'clockOutTime': document.getElementById('clock_out_four').value,
                'nik': document.getElementById('nik_modal').value,
                'password': document.getElementById('password_modal').value
            }
            axios.post(`https://${window.location.host}/api/machine/change-labor-time`, data, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            })
                .then(response => {
                    console.log(response)
                    let parsed = response.data
                    if (typeof response.data === 'string') {
                        try {
                            parsed = JSON.parse(response.data)
                        } catch (e) {
                            container.innerHTML = `<span class="text-white">Gagal parsing response JSON</span>`
                            return
                        }
                    }
                    if (parsed.code === 200) {
                        document.getElementById('stepFive').classList.remove('hidden')
                        document.getElementById('stepFour').classList.add('hidden')
                        document.getElementById('name_form').textContent = 'Update labor detail'
                        document.getElementById('laborHrs').value = parsed.data.laborHrs
                        document.getElementById('burdenHrs').value = parsed.data.burdenHrs
                        const pathId = window.location.pathname.split('/')
                        const groupId = pathId[pathId.length - 1]
                        document.getElementById('group_id').value = groupId

                    } else {
                        const container = document.getElementById('responseFour')
                        container.innerHTML = `<span class="text-white">Terjadi kesalahan</span>`
                        return
                    }

                })
                .catch(error => {
                    console.log(error)
                })
        }
        //Langkah ke lima time entry
        function stepFive(event) {
            event.preventDefault()
            const data = {
                'laborHedSeq': document.getElementById('laborHedSeq').value,
                'laborDtlSeq': document.getElementById('laborDtlSeq').value,
                'date': document.getElementById('production_date_modal').value,
                'clockInDate': document.getElementById('actual_clockin_date_modal').value,
                'clockInTime': document.getElementById('actual_clockin_time_modal').value,
                'clockOutTime': document.getElementById('actual_clockout_time_modal').value,
                'laborHrs': document.getElementById('laborHrs').value,
                'burdenHrs': document.getElementById('burdenHrs').value,
                'laborQty': document.getElementById('labor_qty').value || 0,
                'scrapQty': document.getElementById('scrap_qty').value || 0,
                'discrepQty': document.getElementById('discrep_qty').value || 0,
                'discrpRsnCode': document.getElementById('discrep_reason_code').value || "",
                'scrapReasonCode': document.getElementById('scrap_reason_code').value || "",
                'resourceGrpID': document.getElementById('group_id').value,
                'resourceID': document.getElementById('resource_id').value,
                'laborNote': document.getElementById('labor_note').value,
                'rowMod': 'U',
                'nik': document.getElementById('nik_modal').value,
                'password': document.getElementById('password_modal').value
            }
            console.log(data)
            axios.post(`https://${window.location.host}/api/machine/update-dtl`, data, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }).then(response => {
                const container = document.getElementById('responseStepFive');
                let parsed = response.data;

                // Jika response masih string, parse manual
                if (typeof parsed === 'string') {
                    try {
                        parsed = JSON.parse(parsed);
                    } catch (e) {
                        container.innerHTML = `<span class="text-white">Gagal parsing response JSON</span>`;
                        console.error("Gagal parse JSON:", e, "Raw response:", response.data);
                        return;
                    }
                }

                if (parsed.code === 200) {
                    document.getElementById('stepSix').classList.remove('hidden');
                    document.getElementById('stepFive').classList.add('hidden');
                    document.getElementById('name_form').value = 'Submit labor entry';

                    const list = parsed.data?.listData || [];

                    if (list.length > 0) {
                        const message = list[0];
                        document.getElementById('labor_header_id_finish').value = message.laborHedSeq;
                        document.getElementById('labor_detail_id_finish').value = message.laborDtlSeq;
                    } else {
                        console.warn("List kosong");
                    }
                } else {
                    container.innerHTML = `<span class="text-danger">Terjadi kesalahan</span>`;
                }
            })
                .catch(error => {
                    console.error("Terjadi error saat memproses Step 5:", error);
                    const container = document.getElementById('responseStepFive');
                    container.innerHTML = `<span class="text-danger">Terjadi kesalahan</span>`;
                });
        }
        //Langkah terakhir time Entry
        function stepSix(event) {
            event.preventDefault()
            const data = {
                'laborHedSeq': document.getElementById('laborHedSeq').value,
                'laborDtlSeq': document.getElementById('labor_detail_id_finish').value,
                'nik': document.getElementById('nik_modal').value,
                'password': document.getElementById('password_modal').value
            }
            axios.post(`https://${window.location.host}/api/machine/labor/submit`, data, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            })
                .then(response => {
                    console.log(response)
                    setFinish(document.getElementById('resource_id').value)
                    closeModal()
                })
        }

        function timeStringToMinutes(timeStr) {
            const [hours, minutes] = timeStr.split(':').map(Number);
            return hours * 60 + minutes;
        }
        //Menampilkan jam sekarang, iya sekarang
        function getCurrentTimeHHMM() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            return `${hours}:${minutes}`;
        }
        //Finish atau menonaktifkan mesin
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
                    // window.location.reload()
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
                                    `<button type="button" class="btn btn-warning py-2 md:py-1" onclick="setFinishJig('${ToolID}')">
                                                                                    Finish
                                                                                </button>`: ``}
                                <input id="production_date_${ToolID}" value="${item.production_date}" hidden />
                                    <input id="job_num_${ToolID}" value="${item.job_num}" hidden />
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mb-2 mt-2 text-black dark:text-white text-center">
                                <div class='border border-gray-400 dark:border-white' id="part_no_${ToolID}"><strong>Part num</strong><br>${item.part_no ?? '-'}</div>
                                <div class='border border-gray-400 dark:border-white' id='job_num_${ToolID}'><strong>Job num</strong><br>${item.job_num ?? '-'}</div>
                                <div class='border border-gray-400 dark:border-white'><strong>Customer</strong><br>${item.customer ?? '-'}</div>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mb-2 mt-2 text-black dark:text-white text-center">
                                <div class='border border-gray-400 dark:border-white' id="qty_plan_${ToolID}"><strong>Qty Plan</strong><br>${parseFloat(item.qty_plan) || '-'}</div>
                                <div class='border border-gray-400 dark:border-white'><strong>Qty Actual</strong><br><span id="qty_actual_span_tool_${ToolID}">${parseFloat(item.qty_actual) || '-'}</span></div>
                                <div class='border border-gray-400 dark:border-white'><strong>Qty NG</strong><br>${parseFloat(item.qty_ng) || '-'}</div>
                            </div>
                            <div class="border border-gray-400 dark:border-white text-center text-black dark:text-white" id="emp_name_${ToolID}">
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
                        // $('.form-select').select2('destroy');
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
                        getCategory()
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
        const socket = new WebSocket('wss://websocket.summitadyawinsa.co.id');

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
</x-layout.default>