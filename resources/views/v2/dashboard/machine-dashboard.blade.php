<x-layout.default>
    <script defer src="/assets/js/apexcharts.js"></script>
    <div id="analytics">
        <span id="inpt_machine" hidden></span>
        <div class="pt-5 -mt-5">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div
                    class="panel overflow-hidden before:absolute before:-right-44 before:top-0 before:bottom-0 before:m-auto before:rounded-full before:w-96 before:h-96 content-between gap-6">
                    <div class="text-center text-white-light z-[7] gap-4 mb-3">
                        <div class="align-center xl:text-4xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2">
                            <p class="align-center text-white-light p-1 xl:text-lg sm:text-sm">
                                Machine
                            </p>
                            <span id="inpt_machine_num"></span>
                        </div>
                    </div>

                    <div class="text-center text-white-light z-[7]">
                        <div class="align-center xl:text-4xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2">
                            <p class="align-center text-white-light p-1 xl:text-lg sm:text-sm">
                                Part No.
                            </p>
                            <span id="inpt_jo_part_no"></span>
                        </div>
                    </div>
                </div>

                <div
                    class="panel h-full overflow-hidden before:absolute before:-right-44 before:top-0 before:bottom-0 before:m-auto before:rounded-full before:w-96 before:h-96 content-between gap-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4  mb-3">
                        <div class="text-center text-white-light z-[7]">
                            <div class="align-center xl:text-4xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2">
                                <p class="align-center text-white-light p-1 xl:text-lg sm:text-sm">
                                    Planning
                                </p>
                                <span id="inpt_planning_stroke">0</span>
                            </div>
                        </div>
                        <div class="text-center text-white-light z-[7]">
                            <div class="align-center xl:text-4xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2">
                                <p class="align-center text-white-light p-1 xl:text-lg sm:text-sm">
                                    Actual
                                </p>
                                <span id="inpt_actual_stroke">0</span>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
                        <div class="text-center text-white-light z-[7]">
                            <div class="align-center text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2">
                                <div class="flex items-center justify-between">
                                    <div
                                        class="w-full rounded-full h-5 p-1 bg-dark-light overflow-hidden shadow-3xl dark:shadow-none dark:bg-dark-light/10 xl:mb-7 xl:mt-7 sm:mb-4 sm:mt-5">
                                        <div id="bar_progress"
                                            class="bg-gradient-to-r from-[#e7515a] to-[#00ab55] w-full h-full rounded-full relative before:absolute before:inset-y-0 ltr:before:right-0.5 rtl:before:left-0.5 before:bg-white before:w-2 before:h-2 before:rounded-full before:m-auto"
                                            style="width: 0%;"></div>
                                    </div>
                                    <span class="ltr:ml-5 rtl:mr-5 xl:text-3xl sm:text-sm dark:text-white-light"
                                        id="inpt_set_progress"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="panel h-full overflow-hidden before:absolute before:-right-44 before:top-0 before:bottom-0 before:m-auto before:rounded-full before:w-96 before:h-96 content-between gap-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-3">
                        <div class="text-center text-white-light z-[7]">
                            <div class="align-center xl:text-4xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2">
                                <p class="align-center text-white-light p-1 xl:text-lg sm:text-sm"
                                    id="label_input_gsph">

                                </p>
                                <span id="inpt_current_gsph">0</span>
                            </div>
                        </div>
                        <div class="text-center text-white-light z-[7]">
                            <div class="align-center xl:text-4xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2">
                                <p class="align-center text-white-light p-1 xl:text-lg sm:text-sm">
                                    Cycle Time (AVG)
                                </p>
                                <span id="inpt_average_ct">0</span>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
                        <div class="text-center text-white-light z-[7]">
                            <div class="align-center text-xl rounded p-2">
                                <button id="btn_update_ct_chart"
                                    x-on:click="() => cycleTimeRecall(inpt_ct_log_detail.value)" hidden></button>
                                <input type="text" id="inpt_ct_log_detail" value="" hidden>
                                <div x-ref="cycleTimeChart" class="overflow-hidden"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-6 gap-6 mb-6">
                <div class="panel h-full sm:col-span-6 xl:col-span-2">
                    <div class="flex p-5 border-b  border-[#e0e6ed] dark:border-[#1b2e4b]">
                        <div
                            class="shrink-0 bg-danger/10 text-primary rounded-xl w-11 h-11 flex justify-center items-center dark:bg-danger dark:text-white-light">
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
                            <p class="text-xl dark:text-white-light">Downtime</p>
                            <h5 class="text-[#506690] text-xs">Group By Category</h5>
                            <button id="btn_update_dt_chart"
                                x-on:click="() => downtimeChartRecall(inpt_val_chart_downtime.value)" hidden>Recall and
                                Update</button>
                            <input type="text" id="inpt_val_chart_downtime" value="" hidden>
                        </div>
                    </div>
                    <div x-ref="downtimeChart" class="bg-white dark:bg-black"></div>
                </div>

                <div class="panel h-full sm:col-span-6 xl:col-span-2">
                    <div class="flex p-5 border-b  border-[#e0e6ed] dark:border-[#1b2e4b]">
                        <div
                            class="shrink-0 bg-success/10 text-primary rounded-xl w-11 h-11 flex justify-center items-center dark:bg-success dark:text-white-light">
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
                            <p class="text-xl dark:text-white-light" id='label_gsph_monitoring'></p>
                            <h5 class="text-[#506690] text-xs">Job Per Hour</h5>
                            <button id="btn_update_chart"
                                x-on:click="() => gsphChartRecall(inpt_val_stroke_chart.value)" hidden>Recall and
                                Update</button>
                            <input type="text" id="inpt_val_stroke_chart" value="" hidden>
                        </div>
                    </div>
                    <div x-ref="gsphChart" class="overflow-hidden"></div>
                </div>

                <div class="panel h-full sm:col-span-6 xl:col-span-2">
                    <div class="flex p-5 border-b  border-[#e0e6ed] dark:border-[#1b2e4b]">
                        <div
                            class="shrink-0 bg-primary/10 text-danger rounded-xl w-11 h-11 flex justify-center items-center dark:bg-primary dark:text-white-light ">
                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="https://www.w3.org/2000/svg">
                                <path
                                    d="M6.94028 2C7.35614 2 7.69326 2.32421 7.69326 2.72414V4.18487C8.36117 4.17241 9.10983 4.17241 9.95219 4.17241H13.9681C14.8104 4.17241 15.5591 4.17241 16.227 4.18487V2.72414C16.227 2.32421 16.5641 2 16.98 2C17.3958 2 17.733 2.32421 17.733 2.72414V4.24894C19.178 4.36022 20.1267 4.63333 20.8236 5.30359C21.5206 5.97385 21.8046 6.88616 21.9203 8.27586L22 9H2.92456H2V8.27586C2.11571 6.88616 2.3997 5.97385 3.09665 5.30359C3.79361 4.63333 4.74226 4.36022 6.1873 4.24894V2.72414C6.1873 2.32421 6.52442 2 6.94028 2Z"
                                    fill="currentColor"></path>
                                <path opacity="0.5"
                                    d="M21.9995 14.0001V12.0001C21.9995 11.161 21.9963 9.66527 21.9834 9H2.00917C1.99626 9.66527 1.99953 11.161 1.99953 12.0001V14.0001C1.99953 17.7713 1.99953 19.6569 3.1711 20.8285C4.34267 22.0001 6.22829 22.0001 9.99953 22.0001H13.9995C17.7708 22.0001 19.6564 22.0001 20.828 20.8285C21.9995 19.6569 21.9995 17.7713 21.9995 14.0001Z"
                                    fill="currentColor"></path>
                            </svg>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3 font-semibold">
                            <p class="text-xl dark:text-white-light">Job Accumulate</p>
                            <h5 class="text-[#506690] text-xs">Accumulate By Hour</h5>
                        </div>
                    </div>

                    <div class="relative overflow-hidden">
                        <button id="btn_update_chart_accumulate"
                            x-on:click="() => accumulateRecall(inpt_val_chart_accumulate.value)" hidden>Recall and
                            Update</button>
                        <input type="text" id="inpt_val_chart_accumulate" value="" hidden>
                        <div x-ref="accumulateChart" class="bg-white dark:bg-black rounded-lg"></div>
                    </div>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 xl:grid-cols-6 gap-6 mb-3">
                <div class="panel h-full xl:col-span-2">
                    <div class="flex p-5 border-b  border-[#e0e6ed] dark:border-[#1b2e4b]">
                        <div
                            class="shrink-0 bg-primary/10 text-danger rounded-xl w-11 h-11 flex justify-center items-center dark:bg-primary dark:text-white-light ">
                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="https://www.w3.org/2000/svg">
                                <path
                                    d="M6.94028 2C7.35614 2 7.69326 2.32421 7.69326 2.72414V4.18487C8.36117 4.17241 9.10983 4.17241 9.95219 4.17241H13.9681C14.8104 4.17241 15.5591 4.17241 16.227 4.18487V2.72414C16.227 2.32421 16.5641 2 16.98 2C17.3958 2 17.733 2.32421 17.733 2.72414V4.24894C19.178 4.36022 20.1267 4.63333 20.8236 5.30359C21.5206 5.97385 21.8046 6.88616 21.9203 8.27586L22 9H2.92456H2V8.27586C2.11571 6.88616 2.3997 5.97385 3.09665 5.30359C3.79361 4.63333 4.74226 4.36022 6.1873 4.24894V2.72414C6.1873 2.32421 6.52442 2 6.94028 2Z"
                                    fill="currentColor"></path>
                                <path opacity="0.5"
                                    d="M21.9995 14.0001V12.0001C21.9995 11.161 21.9963 9.66527 21.9834 9H2.00917C1.99626 9.66527 1.99953 11.161 1.99953 12.0001V14.0001C1.99953 17.7713 1.99953 19.6569 3.1711 20.8285C4.34267 22.0001 6.22829 22.0001 9.99953 22.0001H13.9995C17.7708 22.0001 19.6564 22.0001 20.828 20.8285C21.9995 19.6569 21.9995 17.7713 21.9995 14.0001Z"
                                    fill="currentColor"></path>
                            </svg>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3 font-semibold">
                            <p class="text-xl dark:text-white-light">Next Schedule</p>
                            <h5 class="text-[#506690] text-xs">.</h5>
                        </div>
                    </div>
                    <div class="flex flex-col space-y-5 p-5">
                        <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 mb-3" id="inpt_sch_production"></div>
                    </div>

                </div>

                <div class="panel h-full xl:col-span-2">
                    <div class="flex p-5 border-b  border-[#e0e6ed] dark:border-[#1b2e4b]">
                        <div
                            class="shrink-0 bg-warning/10 text-danger rounded-xl w-11 h-11 flex justify-center items-center dark:bg-warning dark:text-white-light">
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
                            <p class="text-xl dark:text-white-light">Activity Log</p>
                            <h5 class="text-[#506690] text-xs">Group By Shift</h5>
                        </div>
                    </div>
                    <div class="perfect-scrollbar relative h-[200px] pr-3 -mr-3 p-5">
                        <div class="space-y-7" id="inpt_activity"></div>
                    </div>

                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 pt-5 gap-4  border-t  border-[#e0e6ed] dark:border-[#1b2e4b]">
                        <div class="text-center text-white-light z-[7]">
                            <div class="align-center xl:text-xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-1">
                                <p class="align-center text-white-light  xl:text-lg sm:text-sm">
                                    Total Dresser
                                </p>
                                <span id="inpt_dresser_count">0</span>
                            </div>
                        </div>

                        <div class="text-center text-white-light z-[7]">
                            <div class="align-center xl:text-xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-1">
                                <p class="align-center text-white-light  xl:text-lg sm:text-sm">
                                    Total Spot
                                </p>
                                <span id="inpt_spot_count">0</span>
                            </div>
                        </div>
                    </div>
                    <!-- <span id="inpt_dresser_count" hidden>0</span> -->

                </div>

                <div class="panel h-full sm:col-span-3 xl:col-span-1">
                    <div class="flex items-center justify-center p-6">
                        <canvas class="p-2" data-type="radial-gauge" id="oeeCanvas" data-height="250"
                            data-units="OEE" data-title="false" data-value="0" data-min-value="0"
                            data-max-value="100" data-major-ticks="0,10,20,30,40,50,60,70,80,90,100"
                            data-minor-ticks="0" data-stroke-ticks="false"
                            data-highlights='[
                                    { "from": 0, "to": 60, "color": "rgb(255,0,0)" },
                                    { "from": 61, "to": 70, "color": "rgb(255,255,0)" },
                                    { "from": 71, "to": 90, "color": "rgb(0,128,0)" },
                                    { "from": 91, "to": 100, "color": "rgb(0,100,0)" }
                                ]'
                            data-color-plate="#222" data-color-major-ticks="#f5f5f5" data-color-minor-ticks="#ddd"
                            data-color-title="#fff" data-color-units="#ccc" data-color-numbers="#eee"
                            data-color-needle-start="rgba(240, 128, 128, 1)"
                            data-color-needle-end="rgba(255, 160, 122, .9)" data-value-box="true" data-value-dec="0"
                            data-animation-rule="bounce" data-animation-duration="3000" data-font-value="Led"
                            data-animated-value="true"></canvas>
                    </div>

                    <div
                        class="grid grid-cols-1 sm:grid-cols-3 pt-5 gap-4  border-t  border-[#e0e6ed] dark:border-[#1b2e4b]">
                        <div class="text-center text-white-light z-[7]">
                            <div class="align-center xl:text-xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-1">
                                <p class="align-center text-white-light  xl:text-lg sm:text-sm">
                                    P
                                </p>
                                <span id="inpt_oee_performance">0</span>
                            </div>
                        </div>
                        <div class="text-center text-white-light z-[7]">
                            <div class="align-center xl:text-xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-1">
                                <p class="align-center text-white-light  xl:text-lg sm:text-sm">
                                    A
                                </p>
                                <span id="inpt_oee_availability">0</span>
                            </div>
                        </div>
                        <div class="text-center text-white-light z-[7]">
                            <div class="align-center xl:text-xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-1">
                                <p class="align-center text-white-light xl:text-lg sm:text-sm">
                                    Q
                                </p>
                                <span id="inpt_oee_quality">0</span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="panel h-full sm:col-span-3 xl:col-span-1">

                    <div class="flex items-center justify-center p-6">
                        <canvas class="p-2" data-type="radial-gauge" id="gsphCanvas" data-height="250"
                            data-units="JPH" data-title="false" data-value="0" data-min-value="0"
                            data-max-value="100" data-major-ticks="0,10,20,30,40,50,60,70,80,90,100"
                            data-minor-ticks="2" data-stroke-ticks="false"
                            data-highlights='[
                                { "from": 0, "to": 60, "color": "rgb(255,0,0)" },
                                    { "from": 61, "to": 70, "color": "rgb(255,255,0)" },
                                    { "from": 71, "to": 90, "color": "rgb(0,128,0)" },
                                    { "from": 91, "to": 100, "color": "rgb(0,100,0)" }
                            ]'
                            data-color-plate="#222" data-color-major-ticks="#f5f5f5" data-color-minor-ticks="#ddd"
                            data-color-title="#fff" data-color-units="#ccc" data-color-numbers="#eee"
                            data-color-needle-start="rgba(240, 128, 128, 1)"
                            data-color-needle-end="rgba(255, 160, 122, .9)" data-value-box="true" data-value-dec="0"
                            data-animation-rule="bounce" data-animation-duration="3000" data-font-value="Led"
                            data-animated-value="true"></canvas>
                    </div>

                    <div class="flex p-5 border-t  border-[#e0e6ed] dark:border-[#1b2e4b]">
                        <div
                            class="shrink-0 bg-success/10 text-success rounded-xl w-11 h-11 flex justify-center items-center dark:bg-success dark:text-white-light">
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
                            <p class="text-xl dark:text-white-light">JPH</p>
                            <h5 class="text-[#506690] text-xs">Job Per Hour</h5>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const segments = window.location.pathname.split('/');
            const machineID = segments[segments.length - 1];
            const line = segments[1]
            if (machineID === 'assy') {
                document.getElementById('label_input_gsph').innerText = 'GSPH';
                document.getElementById('label_gsph_monitoring').innerText = 'GSPH Monitoring';
            } else {
                document.getElementById('label_input_gsph').innerText = 'JPH';
                document.getElementById('label_gsph_monitoring').innerText = 'JPH Monitoring';
            }
            const data = new URLSearchParams();
            data.append('machineID', machineID);
            axios.post(`https://${window.location.host}/api/dashboard-machine`, data, {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                .then(response => {

                }).catch(error => {
                    console.error('Error fetching machine dashboard data:', error);
                });
        })
    </script>
</x-layout.default>
