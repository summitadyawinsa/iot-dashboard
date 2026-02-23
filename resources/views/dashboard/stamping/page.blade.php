<x-layout.default> 
    <script defer src="/assets/js/apexcharts.js"></script>
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script> 
        Pusher.logToConsole = false; 
        var pusher = new Pusher('99d7cb1b939ca3a6bb80', { cluster: 'ap1' }); 
        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) { 
            refeshUi(data);
        });
    </script>
    
    <div x-data="analytics()"> 
        <span id="inpt_machine" hidden></span>
        <div class="pt-5 -mt-5" > 
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6"> 
                <div class="panel overflow-hidden before:absolute before:-right-44 before:top-0 before:bottom-0 before:m-auto before:rounded-full before:w-96 before:h-96 content-between gap-6"> 
                        <div class="text-center text-white-light z-[7] gap-4 mb-3">  
                            <div class="align-center xl:text-4xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2"> 
                                <p class="align-center text-white-light p-1 xl:text-lg sm:text-sm">
                                    Job Number
                                </p> 
                                <span id="inpt_job_num"></span>
                            </div>
                        </div> 

                    <div class="text-center text-white-light z-[7]">  
                        <div class="align-center xl:text-4xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2"> 
                            <p class="align-center text-white-light p-1 xl:text-lg sm:text-sm">
                                Part No.
                            </p> 
                            <span  id="inpt_jo_part_no"></span>
                        </div>
                    </div>  
                </div>

                <div class="panel h-full overflow-hidden before:absolute before:-right-44 before:top-0 before:bottom-0 before:m-auto before:rounded-full before:w-96 before:h-96 content-between gap-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4  mb-3">
                        <div class="text-center text-white-light z-[7]">  
                            <div class="align-center xl:text-4xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2"> 
                                <p class="align-center text-white-light p-1 xl:text-lg sm:text-sm">
                                    Planning
                                </p>
                                <span  id="inpt_planning_stroke">0</span>
                            </div>
                        </div>
                        <div class="text-center text-white-light z-[7]">  
                            <div class="align-center xl:text-4xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2"> 
                                <p class="align-center text-white-light p-1 xl:text-lg sm:text-sm">
                                    Actual
                                </p>
                                <span  id="inpt_actual_stroke">0</span>
                            </div>
                        </div> 
                    </div> 
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
                        <div class="text-center text-white-light z-[7]">  
                            <div class="align-center text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2">  
                                <div class="flex items-center justify-between">
                                    <div class="w-full rounded-full h-5 p-1 bg-dark-light overflow-hidden shadow-3xl dark:shadow-none dark:bg-dark-light/10 xl:mb-7 xl:mt-7 sm:mb-4 sm:mt-5">
                                        <div id="bar_progress" class="bg-gradient-to-r from-[#e7515a] to-[#00ab55] w-full h-full rounded-full relative before:absolute before:inset-y-0 ltr:before:right-0.5 rtl:before:left-0.5 before:bg-white before:w-2 before:h-2 before:rounded-full before:m-auto"
                                            style="width: 0%;"></div>
                                    </div>
                                    <span class="ltr:ml-5 rtl:mr-5 xl:text-3xl sm:text-sm dark:text-white-light" id="inpt_set_progress"></span>
                                </div>
                            </div> 
                        </div>   
                    </div>  
                </div>

                <div class="panel h-full overflow-hidden before:absolute before:-right-44 before:top-0 before:bottom-0 before:m-auto before:rounded-full before:w-96 before:h-96 content-between gap-6"> 
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-3">
                        <div class="text-center text-white-light z-[7]">  
                            <div class="align-center xl:text-4xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2"> 
                                <p class="align-center text-white-light p-1 xl:text-lg sm:text-sm">
                                    GSPH
                                </p>
                                <span  id="inpt_current_gsph">0</span>
                            </div>
                        </div> 
                        <div class="text-center text-white-light z-[7]">  
                            <div class="align-center xl:text-4xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2"> 
                                <p class="align-center text-white-light p-1 xl:text-lg sm:text-sm">
                                    Cycle Time (AVG)
                                </p>
                                <span  id="inpt_average_ct">0</span>
                            </div>
                        </div> 
                    </div>  
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
                        <div class="text-center text-white-light z-[7]">  
                            <div class="align-center text-xl rounded p-2">  
                                <button id="btn_update_ct_chart" x-on:click="() => cycleTimeRecall(inpt_ct_log_detail.value)" hidden></button>
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
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z" fill="currentColor"></path>
                                <path opacity="0.5" d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z" fill="currentColor"></path>
                            </svg>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3 font-semibold">
                            <p class="text-xl dark:text-white-light">Downtime</p>
                            <h5 class="text-[#506690] text-xs">Group By Category</h5>
                            <button id="btn_update_dt_chart" x-on:click="() => downtimeChartRecall(inpt_val_chart_downtime.value)" hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_chart_downtime" value="" hidden>
                        </div>
                    </div> 
                        <div x-ref="downtimeChart" class="bg-white dark:bg-black"></div> 
                </div>

                <div class="panel h-full sm:col-span-6 xl:col-span-2">
                    <div class="flex p-5 border-b  border-[#e0e6ed] dark:border-[#1b2e4b]">
                        <div
                            class="shrink-0 bg-success/10 text-primary rounded-xl w-11 h-11 flex justify-center items-center dark:bg-success dark:text-white-light">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z" fill="currentColor"></path>
                                <path opacity="0.5" d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z" fill="currentColor"></path>
                            </svg>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3 font-semibold">
                            <p class="text-xl dark:text-white-light">GSPH Monitoring</p>
                            <h5 class="text-[#506690] text-xs">Gross Stroke Per Hour</h5>
                            <button id="btn_update_chart" x-on:click="() => gsphChartRecall(inpt_val_stroke_chart.value)" hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_stroke_chart" value="" hidden>
                        </div>
                    </div>  
                        <div x-ref="gsphChart" class="overflow-hidden"></div>
                </div> 
 
                <div class="panel h-full sm:col-span-6 xl:col-span-2">
                    <div class="flex p-5 border-b  border-[#e0e6ed] dark:border-[#1b2e4b]">
                        <div
                            class="shrink-0 bg-primary/10 text-danger rounded-xl w-11 h-11 flex justify-center items-center dark:bg-primary dark:text-white-light ">
                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.94028 2C7.35614 2 7.69326 2.32421 7.69326 2.72414V4.18487C8.36117 4.17241 9.10983 4.17241 9.95219 4.17241H13.9681C14.8104 4.17241 15.5591 4.17241 16.227 4.18487V2.72414C16.227 2.32421 16.5641 2 16.98 2C17.3958 2 17.733 2.32421 17.733 2.72414V4.24894C19.178 4.36022 20.1267 4.63333 20.8236 5.30359C21.5206 5.97385 21.8046 6.88616 21.9203 8.27586L22 9H2.92456H2V8.27586C2.11571 6.88616 2.3997 5.97385 3.09665 5.30359C3.79361 4.63333 4.74226 4.36022 6.1873 4.24894V2.72414C6.1873 2.32421 6.52442 2 6.94028 2Z" fill="currentColor"></path>
                                <path opacity="0.5" d="M21.9995 14.0001V12.0001C21.9995 11.161 21.9963 9.66527 21.9834 9H2.00917C1.99626 9.66527 1.99953 11.161 1.99953 12.0001V14.0001C1.99953 17.7713 1.99953 19.6569 3.1711 20.8285C4.34267 22.0001 6.22829 22.0001 9.99953 22.0001H13.9995C17.7708 22.0001 19.6564 22.0001 20.828 20.8285C21.9995 19.6569 21.9995 17.7713 21.9995 14.0001Z" fill="currentColor"></path>
                            </svg>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3 font-semibold">
                            <p class="text-xl dark:text-white-light">Job Accumulate</p>
                            <h5 class="text-[#506690] text-xs">Accumulate By Hour</h5>
                        </div>
                    </div>  
                     
                    <div class="relative overflow-hidden">
                        <button id="btn_update_chart_accumulate" x-on:click="() => accumulateRecall(inpt_val_chart_accumulate.value)" hidden>Recall and Update</button>
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
                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.94028 2C7.35614 2 7.69326 2.32421 7.69326 2.72414V4.18487C8.36117 4.17241 9.10983 4.17241 9.95219 4.17241H13.9681C14.8104 4.17241 15.5591 4.17241 16.227 4.18487V2.72414C16.227 2.32421 16.5641 2 16.98 2C17.3958 2 17.733 2.32421 17.733 2.72414V4.24894C19.178 4.36022 20.1267 4.63333 20.8236 5.30359C21.5206 5.97385 21.8046 6.88616 21.9203 8.27586L22 9H2.92456H2V8.27586C2.11571 6.88616 2.3997 5.97385 3.09665 5.30359C3.79361 4.63333 4.74226 4.36022 6.1873 4.24894V2.72414C6.1873 2.32421 6.52442 2 6.94028 2Z" fill="currentColor"></path>
                                <path opacity="0.5" d="M21.9995 14.0001V12.0001C21.9995 11.161 21.9963 9.66527 21.9834 9H2.00917C1.99626 9.66527 1.99953 11.161 1.99953 12.0001V14.0001C1.99953 17.7713 1.99953 19.6569 3.1711 20.8285C4.34267 22.0001 6.22829 22.0001 9.99953 22.0001H13.9995C17.7708 22.0001 19.6564 22.0001 20.828 20.8285C21.9995 19.6569 21.9995 17.7713 21.9995 14.0001Z" fill="currentColor"></path>
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
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z" fill="currentColor"></path>
                                <path opacity="0.5" d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z" fill="currentColor"></path>
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
                </div>

                <div class="panel h-full sm:col-span-3 xl:col-span-1"> 
                    <div class="flex items-center justify-center p-6">
                        <canvas class="p-2" data-type="radial-gauge" id="oeeCanvas" 
                                data-height="250"
                                data-units="OEE"
                                data-title="false"
                                data-value="0"
                                data-min-value="0"
                                data-max-value="100"
                                data-major-ticks="0,10,20,30,40,50,60,70,80,90,100"
                                data-minor-ticks="0"
                                data-stroke-ticks="false"
                                data-highlights='[
                                    { "from": 0, "to": 60, "color": "rgb(255,0,0)" }, 
                                    { "from": 61, "to": 70, "color": "rgb(255,255,0)" },
                                    { "from": 71, "to": 90, "color": "rgb(0,128,0)" },
                                    { "from": 91, "to": 100, "color": "rgb(0,100,0)" }
                                ]'
                                data-color-plate="#222"
                                data-color-major-ticks="#f5f5f5"
                                data-color-minor-ticks="#ddd"
                                data-color-title="#fff"
                                data-color-units="#ccc"
                                data-color-numbers="#eee"
                                data-color-needle-start="rgba(240, 128, 128, 1)"
                                data-color-needle-end="rgba(255, 160, 122, .9)"
                                data-value-box="true"
                                data-value-dec="0"
                                data-animation-rule="bounce"
                                data-animation-duration="3000"
                                data-font-value="Led"
                                data-animated-value="true"
                        ></canvas>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 pt-5 gap-4  border-t  border-[#e0e6ed] dark:border-[#1b2e4b]">
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
                                <span  id="inpt_oee_availability">0</span>
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
                        <canvas class="p-2" data-type="radial-gauge" id="gsphCanvas"
                            data-height="250"
                            data-units="JPH"
                            data-title="false"
                            data-value="0"
                            data-min-value="0"
                            data-max-value="100"
                            data-major-ticks="0,10,20,30,40,50,60,70,80,90,100"
                            data-minor-ticks="2"
                            data-stroke-ticks="false"
                            data-highlights='[
                                { "from": 0, "to": 60, "color": "rgb(255,0,0)" }, 
                                    { "from": 61, "to": 70, "color": "rgb(255,255,0)" },
                                    { "from": 71, "to": 90, "color": "rgb(0,128,0)" },
                                    { "from": 91, "to": 100, "color": "rgb(0,100,0)" }
                            ]'
                            data-color-plate="#222"
                            data-color-major-ticks="#f5f5f5"
                            data-color-minor-ticks="#ddd"
                            data-color-title="#fff"
                            data-color-units="#ccc"
                            data-color-numbers="#eee"
                            data-color-needle-start="rgba(240, 128, 128, 1)"
                            data-color-needle-end="rgba(255, 160, 122, .9)"
                            data-value-box="true"
                            data-value-dec="0" 
                            data-animation-rule="bounce"
                            data-animation-duration="3000"
                            data-font-value="Led"
                            data-animated-value="true"
                    ></canvas>
                    </div>

                    <div class="flex p-5 border-t  border-[#e0e6ed] dark:border-[#1b2e4b]">
                        <div
                            class="shrink-0 bg-success/10 text-success rounded-xl w-11 h-11 flex justify-center items-center dark:bg-success dark:text-white-light">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z" fill="currentColor"></path>
                                <path opacity="0.5" d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z" fill="currentColor"></path>
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
    
    <input type="number" id="inpt_standard_sph" hidden readonly>
    <script>  
        document.addEventListener("DOMContentLoaded", function() {  
            function setDefaultData(machineId) {  
                    var currentHost = window.location.host;   
                    const apiUrl =  `https://` + currentHost + `/api/machine/${machineId}`; 
                    axios.get(apiUrl)
                        .then(response => {  
                            var data = response.data.message; 
                            

                            var qty_actual = data.qty_actual; 
                            var job_num = data.job_num; 
                            var part_no = data.part_no; 
                            var sch_production = data.sch_production; 
                            var qty_plan = data.qty_plan;  
                            var shift = data.shift; 
                            var current_gsph = data.current_gsph;  
                            var current_gsph_persen = data.current_gsph_persen;  
                            var bar_progress = data.bar_progress;  
                            var average_ct = data.average_ct; 
                            var activity = data.activity;  
                            var data_chart_gsph = data.data_chart_gsph;  
                            var data_chart_downtime = data.data_chart_downtime;  
                            var ct_log_detail = data.ct_log_detail;   
                            var machine_code = data.machine_code;   
                            var oee_quality = data.oee_quality;   
                            var oee_performance = data.oee_performance;   
                            var oee_availability = data.oee_availability;   
                            var oee_value = data.oee_value;    
                            var data_chart_accumulate = data.data_chart_accumulate;  
                            var standard_sph = data.standard_sph;  
                             
                            document.getElementById('inpt_sch_production').innerHTML = `${sch_production}` ; 
                            document.getElementById('inpt_oee_quality').textContent = `${oee_quality}`;
                            document.getElementById('inpt_oee_performance').textContent = `${oee_performance}`;
                            document.getElementById('inpt_oee_availability').textContent = `${oee_availability}`;
                            document.getElementById('inpt_machine_code').textContent = ' | LINE : ' + `${machine_code}`;
                            document.getElementById('inpt_machine').textContent = `${machine_code}`;
                            document.getElementById('inpt_actual_stroke').textContent = `${qty_actual}`;
                            document.getElementById('inpt_job_num').textContent = `${job_num}` ; 
                            document.getElementById('inpt_jo_part_no').textContent = `${part_no}` ; 
                            document.getElementById('inpt_planning_stroke').textContent = `${qty_plan}` ;    
                            document.getElementById('inpt_shift').textContent = `Shift : ${shift}` +  " | " ;  
                            document.getElementById('inpt_current_gsph').textContent = `${current_gsph}` ;   
                            document.getElementById('inpt_average_ct').textContent = `${average_ct}` ;  
                            document.getElementById('inpt_set_progress').textContent = `${bar_progress}%` ;  
                            document.getElementById('inpt_activity').innerHTML = `${activity}` ;   
                            document.getElementById('bar_progress').style.width = bar_progress + '%';   
                            document.getElementById('inpt_val_stroke_chart').value = data_chart_gsph  ;   
                            document.getElementById('inpt_val_chart_downtime').value = data_chart_downtime  ;   
                            document.getElementById('inpt_ct_log_detail').value = ct_log_detail  ;    
                            document.getElementById('inpt_standard_sph').value = standard_sph  ;    
                            document.getElementById('inpt_val_chart_accumulate').value = data_chart_accumulate  ;  
                            document.getElementById('btn_update_chart_accumulate').click() ;   
                            document.getElementById('btn_update_dt_chart').click() ;  
                            document.getElementById('btn_update_ct_chart').click() ;  
                            document.getElementById('btn_update_chart').click() ;    
                            var gsphCanvas = document.getElementById('gsphCanvas');
                            gsphCanvas.setAttribute('data-value', current_gsph_persen);
                            var oeeCanvas = document.getElementById('oeeCanvas');
                            oeeCanvas.setAttribute('data-value', oee_value);  
                           
                        })
                        .catch(error => { 
                            console.error('Error fetching data:', error);
                        });
                        
                    }
                    
                    const targetMachineId = '{{ $id }}' ;  
                    setDefaultData(targetMachineId);
                });
  
                function addNewDetailActivity(newActivity) { 
                    var container = document.getElementById("inpt_activity"); 
                    var newDetailActivity = document.createElement("div");
                    newDetailActivity.className = "flex";
                    newDetailActivity.id = "detail_activity_" + Date.now();   
                    newDetailActivity.innerHTML = newActivity ;
                    var firstChild = container.firstChild; 
                    container.insertBefore(newDetailActivity, firstChild);
                } 
                

                function refeshUi(id) {
                    const targetMachineId = '{{ $id }}' ;   
                    var responseData = id ;
                    // console.log(responseData.message);
                    var machine_id = responseData.message.machine_id;  
                    var topic = responseData.message.topic;   
                    // console.log(topic+ " : " +machine_id);
                    if (machine_id == targetMachineId) { 
                        if (topic == 'set_finish') {  
                            location.reload();
                        } else
                        if (topic == 'set_job_number') {  
                            location.reload();
                        } else  
                        if (topic == 'set_downtime') {  
                            var data_chart_downtime = responseData.message.data_chart_downtime;   
                            document.getElementById('inpt_val_chart_downtime').value = data_chart_downtime  ; 
                            document.getElementById('btn_update_dt_chart').click() ;   
                            if ( responseData.message.status_action > 1) {
                                var activityText = responseData.message.activity; 
                                addNewDetailActivity(activityText);   
                            } 
                        } else 
                        if (topic == 'log_activity') {
                            var activityText = responseData.message.activity; 
                            addNewDetailActivity(activityText);  
                        } else { 

                            var job_num = responseData.message.job_num; 
                            var qty_plan = responseData.message.qty_plan; 
                            var part_no = responseData.message.part_no; 
                            var qty_actual = responseData.message.qty_actual; 
                            var current_gsph = responseData.message.current_gsph; 
                            var current_gsph_persen = responseData.message.current_gsph_persen; 
                            var bar_progress = responseData.message.bar_progress;  
                            var average_ct = responseData.message.average_ct;  
                            var data_chart_gsph = responseData.message.data_chart_gsph;
                            var ct_log_detail = responseData.message.ct_log_detail;  
                            var data_chart_downtime = responseData.message.data_chart_downtime;  
                            var oee_quality = responseData.message.oee_quality;   
                            var oee_performance = responseData.message.oee_performance;   
                            var oee_availability = responseData.message.oee_availability;   
                            var oee_value = responseData.message.oee_value;   
                            var data_chart_accumulate = responseData.message.data_chart_accumulate ;   

                            document.getElementById('inpt_oee_quality').textContent = `${oee_quality}`;
                            document.getElementById('inpt_oee_performance').textContent = `${oee_performance}`;
                            document.getElementById('inpt_oee_availability').textContent = `${oee_availability}`;
                            document.getElementById('inpt_job_num').textContent = `${job_num}`; 
                            document.getElementById('inpt_planning_stroke').textContent = `${qty_plan}`; 
                            document.getElementById('inpt_jo_part_no').textContent = `${part_no}`;    
                            document.getElementById('inpt_actual_stroke').textContent = `${qty_actual}`; 
                            document.getElementById('inpt_current_gsph').textContent = `${current_gsph}`; 
                            document.getElementById('inpt_set_progress').textContent = `${bar_progress}%` ;  
                            document.getElementById('bar_progress').style.width = bar_progress + '%'; 
                            document.getElementById('inpt_average_ct').textContent = `${average_ct}` ;  
                            document.getElementById('inpt_val_stroke_chart').value = data_chart_gsph  ;   
                            document.getElementById('inpt_ct_log_detail').value = ct_log_detail  ;   
                            document.getElementById('inpt_val_chart_downtime').value = data_chart_downtime  ;   
                            document.getElementById('inpt_val_chart_accumulate').value = data_chart_accumulate ;   
                            document.getElementById('btn_update_chart_accumulate').click() ;  
                            document.getElementById('btn_update_ct_chart').click() ;
                            document.getElementById('btn_update_chart').click() ;
                            document.getElementById('btn_update_dt_chart').click() ; 

                            var gsphCanvas = document.getElementById('gsphCanvas');
                            gsphCanvas.setAttribute('data-value', current_gsph_persen); 
                            var oeeCanvas = document.getElementById('oeeCanvas');
                            oeeCanvas.setAttribute('data-value', oee_value);  

                        }
                    } else {
                        // console.log('0');
                    }
                }
    </script> 

<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("analytics", () => ({ 
            data: {
                analytics: "Initial Data"
            },   

            downtimeChartRecall(parameter) { 
                const c_get = parameter ;   
                c = c_get.split(',').map(Number); 
                const dummyData =  c ; 
                    this.downtimeChart.updateSeries([{
                    data: dummyData
                }]); 
            }, 

            gsphChartRecall(parameter) { 
                const c_get = parameter ;   
                c = c_get.split(',').map(Number); 
                const dummyData =  c ; 
                    this.gsphChart.updateSeries([{
                    data: dummyData
                }]); 
            }, 

            cycleTimeRecall(parameter) { 
                const c_get = parameter ;    
                c = c_get.split(',').map(Number); 
                const dummyData =  c ; 
                    this.cycleTimeChart.updateSeries([{
                    data: dummyData
                }]); 
            },

            accumulateRecall(parameter) { 
                const c_get = parameter ;   
                const standard_sph =  document.getElementById('inpt_standard_sph').value ;
                c = c_get.split(',').map(Number); 
                const dummyData =  c ; 
                    this.accumulateChart.updateSeries([
                        {
                            name: 'Plan',
                            data: [0, (standard_sph * 1), (standard_sph * 2), (standard_sph * 3), (standard_sph * 4), (standard_sph * 5), (standard_sph * 6), (standard_sph * 7), (standard_sph * 8), (standard_sph * 9), (standard_sph * 10)], 
                        },
                        {
                            name: 'Actual',
                            data: dummyData,
                        }
                    ]); 
            },
 
            renderCharts() { 
                this.gsphChart = new ApexCharts(this.$refs.gsphChart, this.gsphChartOptions);
                this.gsphChart.render();

                this.cycleTimeChart = new ApexCharts(this.$refs.cycleTimeChart, this.cycleTimeOptions);
                this.cycleTimeChart.render();

                this.downtimeChart = new ApexCharts(this.$refs.downtimeChart, this.downtimeChartOptions);
                this.downtimeChart.render();

                this.accumulateChart = new ApexCharts(this.$refs.accumulateChart, this.accumulateChartOptions);
                this.accumulateChart.render(); 
            },

            get accumulateChartOptions() {
                    return {
                        series: [{
                                name: 'Plan',
                                data: [0, 19, 38, 57, 76, 95, 114, 133, 152, 171, 190], 
                            },
                            {
                                name: 'Actual',
                                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                            }
                        ],
                        chart: {
                            height: 160,
                            type: "line",
                            fontFamily: 'Nunito, sans-serif',
                            zoom: {
                                enabled: false
                            },
                            toolbar: {
                                show: false
                            },
                        },
                        dataLabels: {
                            enabled: false,
                            style: {
                                colors: ['transparent', '#00ab55'],
                                fontColors: ['transparent', '#00ab55'],
                                borderColor: ['transparent', '#00ab55'] // Set default colors for dataLabels
                            }
                        },
                        stroke: { 
                            width: [2, 2],  
                            curve: ['straight', 'straight'],
                            dashArray: [5, 0]
                        },  
                        colors: ['#2196f3', '#00ab55'], 
                        labels: ['', '07:30', '08:30', '09:30', '10:30', '11:30', '12:30', '13:30', '14:30', '15:30', '16:30'],
                        xaxis: {
                            axisBorder: {
                                show: true,
                                color: '#3b3f5c' 
                            },
                            axisTicks: {
                                show: false
                            },
                            crosshairs: {
                                show: true
                            }, 
                        },
                        yaxis: {
                            axisBorder: {
                                show: true,
                                color: '#3b3f5c' 
                            },
                            tickAmount: 3, 
                            opposite: false,
                        },
                        grid: {
                            borderColor: '#3b3f5c',
                            strokeDashArray: 5,  
                            xaxis: {
                                lines: {
                                    show: false,  
                                }
                            },
                            yaxis: {
                                lines: {
                                    show: true, 
                                }
                            }, 
                        },
                        legend: {
                            show: false,
                            position: 'top', 
                            offsetY: 20,
                            fontSize: '16px',
                            markers: {
                                width: 10,
                                height: 10,
                                offsetX: -2,
                            },
                            itemMargin: {
                                horizontal: 10,
                                vertical: 10,
                            },
                        },
                        tooltip: {
                            marker: {
                                show: true
                            },
                            x: {
                                show: true
                            }
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                inverseColors: !1,
                                opacityFrom: 1,
                                opacityTo: 0.05,
                                stops: [100, 100],
                            },
                        },
                    }
                },

            get downtimeChartOptions() {
                    return {
                        series: [
                            {
                                name: 'Second',
                                data: [0, 0, 0, 0, 0]
                            },
                        ],
                        chart: {
                            height: 160,
                            type: 'bar',
                            fontFamily: 'Nunito, sans-serif',
                            fontSize: '18px', // Ubah ukuran font di sini
                            toolbar: {
                                show: false
                            }
                        },
                        dataLabels: {
                            enabled: true
                        },
                        stroke: {
                            width: 2,
                            colors: ['transparent']
                        },  
                        colors: ['#4361ee', '#2196fe', '#00Ab55', '#e7515a', '#3b3f5c'],
                        dropShadow: {
                            enabled: true,
                            blur: 3,
                            color: '#515365',
                            opacity: 0.4,
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '55%',
                                borderRadius: 10
                            }
                        },
                        legend: {
                            enabled: true,
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '18px', // Ubah ukuran font di sini
                            itemMargin: {
                                horizontal: 8,
                                vertical: 8
                            }
                        },
                        grid: {
                            borderColor: '#191e3a',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: ['LDK', 'DB', 'MB', 'QC', 'Etc'],
                            axisBorder: {
                                show: true,
                                color: '#3b3f5c' 
                            },
                            labels: {
                                style: {
                                    fontSize: '16px' // Ubah ukuran font di sini
                                }
                            }
                        },
                        yaxis: {
                            tickAmount: 3,
                            opposite: false,
                            labels: {
                                offsetX:  0,
                                style: {
                                    fontSize: '16px' // Ubah ukuran font di sini
                                }
                            }
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shade: 'dark' ,
                                type: 'vertical',
                                shadeIntensity: 0.3,
                                inverseColors: false,
                                opacityFrom: 1,
                                opacityTo: 0.8,
                                stops: [0, 100]
                            },
                        },
                        tooltip: {
                            marker: {
                                show: true,
                            },
                            y: {
                                formatter: (val) => {
                                    return val;
                                },
                            },
                        },
                    };
                },

            get cycleTimeOptions() {
                    return {
                        series: [{ 
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                        }],
                        chart: {
                            height: 75,
                            type: 'area',
                            fontFamily: 'Nunito, sans-serif',
                            sparkline: {
                                enabled: true
                            },
                            dropShadow: {
                                enabled: true,
                                blur: 3,
                                color: '#009688',
                                opacity: 0.4
                            }
                        },
                        dataLabels: {
                            enabled: false,  
                        }, 
                        stroke: {
                            curve: 'smooth',
                            width: 2
                        },
                        colors: ['#009688'],
                        grid: {
                            padding: {
                                top: 5,
                                bottom: 5,
                                left: 5,
                                right: 5
                            }
                        }, 
                        tooltip: {
                            x: {
                                show: false,
                            },
                            y: {
                                title: {
                                    formatter: formatter = () => {
                                        return '(Second) ';
                                    },
                                },
                            },
                        },
                    }
                },
 
            get gsphChartOptions() {
                return {
                    series: [{
                        name: 'Stroke Per Hour',
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    }],
                        chart: {
                            height: 160,
                            type: 'area',
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                            
                        },
                        dataLabels: {
                            enabled: true
                        },
                        stroke: {
                            width: 2,
                            colors: ['transparent']
                        },
                        colors: ['#2196f3'],
                        dropShadow: {
                            enabled: true,
                            blur: 3,
                            color: '#515365',
                            opacity: 0.4,
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '55%',
                                borderRadius: 10
                            }
                        },
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            itemMargin: {
                                horizontal: 8,
                                vertical: 8
                            }
                        },
                        grid: {
                            borderColor: '#191e3a',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: [ '', '07:30', '08:30', '09:30', '10:30', '11:30', '12:30', '13:30', '14:30', '15:30', '16:30'],
                            axisBorder: {
                                show: true,
                                color: '#3b3f5c'
                            },
                            axisTicks: {
                                show: false
                            },
                        },
                        yaxis: {
                            tickAmount: 3,
                            opposite: false, 
                            labels: {
                                show: true,
                                offsetX: 0,
                            }
                        },
                        
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shade: 'dark',
                                type: 'vertical',
                                shadeIntensity: 0.3,
                                inverseColors: false,
                                opacityFrom: 1,
                                opacityTo: 0.8,
                                stops: [0, 100]
                            },
                        }, 
                        
                };
            },
 
            init() { 
                this.data.analytics = "Initial Data";
                this.renderCharts();
            }
        }));
    });
</script> 

</x-layout.default>
