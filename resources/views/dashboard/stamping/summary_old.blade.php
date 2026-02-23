<x-layout.default> 
    <script defer src="/assets/js/apexcharts.js"></script>
    
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>Stamping</span>
            </li>  
        </ul> 
        <div class="pt-5">   
            <div class="grid sm:grid-cols-2 xl:grid-cols-8 gap-6 mb-6"> 
            <?php 
                $bcolor_1 = 'primary' ; 
                $bcolor_2 = 'success' ; 
                $bcolor_3 = 'warning' ; 
                $bcolor_4 = 'danger' ; 
                $bcolor_5 = 'primary' ; 
                $bcolor_6 = 'success' ; 
                $bcolor_7 = 'warning' ; 
                $bcolor_8 = 'danger' ; 
                $i=1;
            ?>  
            @foreach ($db AS $row) 
                <div class="panel h-full sm:col-span-3 xl:col-span-2">
                    <div class="flex p-5 border-b  border-[#e0e6ed] dark:border-[#1b2e4b]">
                        <div class="shrink-0 bg-{{ ${'bcolor_'.$i} }}/10 text-danger rounded-xl w-11 h-11 flex justify-center items-center dark:bg-{{ ${'bcolor_'.$i} }} dark:text-white-light ">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-{{ ${'bcolor_'.$i} }} shrink-0">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z" fill="currentColor"></path>
                                <path opacity="0.5" d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z" fill="currentColor"></path>
                            </svg>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3 font-semibold">
                            <p class="text-xl dark:text-white-light">{{ $row['mc_code'] }}</p>
                            <h5 class="text-[#506690] text-xs">Monitoring System</h5>
                        </div>
                    </div> 

                    <div class="flex items-center justify-center pt-3 gap-4 mb-3">  
                        <div class="text-center text-white-light z-[7] w-full">  
                            <div class="align-center xl:text-4xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2"> 
                                <p class="align-center text-white-light p-1 xl:text-lg sm:text-sm">
                                    Plan
                                </p>
                                <span  id="inpt_planning_stroke_{{ $row['machine_id'] }}">{{ number_format($row['plan'],0) }}</span>
                            </div>
                        </div>
                        <div class="text-center text-white-light z-[7]  w-full">  
                            <div class="align-center xl:text-4xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2"> 
                                <p class="align-center text-white-light p-1 xl:text-lg sm:text-sm">
                                    Act 
                                </p>
                                <span  id="inpt_actual_stroke_{{ $row['machine_id'] }}">{{ number_format($row['actual'],0) }}</span>
                            </div>
                        </div>  
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-1">
                        <div class="text-center text-white-light z-[7]">  
                            <div class="align-center text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2">   
                                    <div class="w-full rounded-full h-5 p-1 bg-dark-light overflow-hidden shadow-3xl dark:shadow-none dark:bg-dark-light/10 xl:mb-5 xl:mt-5 sm:mb-4 sm:mt-5">
                                        <div id="bar_progress_{{ $row['machine_id'] }}" class="bg-gradient-to-r from-[#e7515a] to-[#00ab55] w-full h-full rounded-full relative before:absolute before:inset-y-0 ltr:before:right-0.5 rtl:before:left-0.5 before:bg-white before:w-2 before:h-2 before:rounded-full before:m-auto"
                                            style="width: {{ $row['bar_progress'] }}%;"></div>
                                    </div> 
                            </div> 
                        </div>   
                    </div> 

                    <div class="grid grid-cols-1 sm:grid-cols-2 pt-3 gap-3 mb-3">
                        <div class="text-center text-white-light z-[7]">  
                            <div class="align-center xl:text-4xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2"> 
                                <p class="align-center text-white-light p-1 xl:text-lg sm:text-sm">
                                    GSPH
                                </p>
                                <span  id="inpt_current_gsph_{{ $row['machine_id'] }}">{{ number_format($row['sph'],0) }}</span>
                            </div>
                        </div> 
                        <div class="text-center text-white-light z-[7]">  
                            <div class="align-center xl:text-4xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2"> 
                                <p class="align-center text-white-light p-1 xl:text-lg sm:text-sm">
                                   CT
                                </p>
                                <span  id="inpt_average_ct_{{ $row['machine_id'] }}">{{ number_format($row['ct'],0) }}</span>
                            </div>
                        </div> 
                    </div>   
                </div> 
            <?php $i++ ?>
            @endforeach
                
            </div> 
            
            
        </div> 
    </div>  
    
    <script>  
        document.addEventListener("DOMContentLoaded", function() {  
            function setDefaultData(machineId) {  
                    var currentHost = window.location.host;   
                    const apiUrl =  `https://` + currentHost + `/api/summary_by_line/${machineId}`; 
                    axios.get(apiUrl)
                        .then(response => {  
                            // var data = response.data.message;   
                            // document.getElementById('inpt_ct_log_detail').value = data  ;   
                            // document.getElementById('btn_update_dt_chart').click() ;    
                        })
                        .catch(error => { 
                            console.error('Error fetching data:', error);
                        });
                        
                    }
                    
                    const targetMachineId = {{ $id }} ;  
                    setDefaultData(targetMachineId);
                }); 
                

                function refeshUi(id) {  
                    const targetMachineId = {{ $id }} ;   
                    var responseData = id ;
                    var machine_id = responseData.message.machine_id;  
                    var topic = responseData.message.topic;    
                    if (topic != 'set_finish' || topic != 'set_job_number' || topic == 'set_downtime' || topic == 'log_activity') {   
                        var qty_plan = responseData.message.qty_plan;  
                        var qty_actual = responseData.message.qty_actual; 
                        var current_gsph = responseData.message.current_gsph;  
                        var bar_progress = responseData.message.bar_progress;  
                        var average_ct = responseData.message.average_ct;  
                        document.getElementById('inpt_planning_stroke_' + machine_id).textContent = `${qty_plan}`;   
                        document.getElementById('inpt_actual_stroke_' + machine_id).textContent = `${qty_actual}`; 
                        document.getElementById('inpt_current_gsph_' + machine_id).textContent = `${current_gsph}`;  
                        document.getElementById('bar_progress_' + machine_id).style.width = bar_progress + '%';   
                        document.getElementById('inpt_average_ct_' + machine_id).textContent = `${average_ct}` ;   
                    } else {
                        console.log(false);
                    } 
                }
    </script>  

</x-layout.default>
