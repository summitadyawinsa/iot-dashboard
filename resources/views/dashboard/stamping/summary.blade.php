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
            @php
                $colors = ['primary', 'success', 'warning', 'danger'];
                $i = 0;
            @endphp
            <div class="grid sm:grid-cols-2 xl:grid-cols-8 gap-6 mb-6" id="machine-cards-container">
            </div>
        </div>
    </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const targetMachineId = "{{ $id }}";
            const apiUrl = `https://${window.location.host}/api/summary_by_line/${targetMachineId}`;

            axios.get(apiUrl)
                .then(response => {
                    refreshUi(response.data.result);
                })
                .catch(error => {
                    console.error('❌ Error fetching data:', error);
                });
        });

        function refreshUi(results) {
            const colors = ['primary', 'success', 'warning', 'danger'];
            const container = document.getElementById('machine-cards-container');
            container.innerHTML = '';
            results.forEach((row, index) => {
                const color = colors[index % colors.length];
                const machine_id = row.machine_id;

                const cardHTML = `
            <div class="panel h-full sm:col-span-3 xl:col-span-2">
                <div class="flex p-5 border-b border-[#e0e6ed] dark:border-[#1b2e4b]">
                    <div class="shrink-0 bg-${color}/10 text-${color} rounded-xl w-11 h-11 flex justify-center items-center dark:bg-${color} dark:text-white-light">
                        <svg class="text-current" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd"  fill="currentColor" />
                            <path opacity="0.5"  fill="currentColor" />
                        </svg>
                    </div>
                    <div class="ltr:ml-3 rtl:mr-3 font-semibold">
                        <p class="text-xl dark:text-white-light">${row.mc_code}</p>
                        <h5 class="text-[#506690] text-xs">Monitoring System</h5>
                    </div>
                </div>

                <div class="flex items-center justify-center pt-3 gap-4 mb-3">
                    <div class="text-center text-white-light z-[7] w-full">
                        <div class="shadow rounded p-2 xl:text-4xl sm:text-xl">
                            <p class="p-1 xl:text-lg sm:text-sm">Plan</p>
                            <span id="inpt_planning_stroke_${machine_id}">${row.plan}</span>
                        </div>
                    </div>
                    <div class="text-center text-white-light z-[7] w-full">
                        <div class="shadow rounded p-2 xl:text-4xl sm:text-xl">
                            <p class="p-1 xl:text-lg sm:text-sm">Act</p>
                            <span id="inpt_actual_stroke_${machine_id}">${row.actual}</span>
                        </div>
                    </div>
                </div>

                <div class="text-center text-white-light z-[7]">
                    <div class="shadow rounded p-2">
                        <div class="w-full h-5 bg-dark-light rounded-full p-1 overflow-hidden">
                            <div id="bar_progress_${machine_id}"
                                class="bg-gradient-to-r from-[#e7515a] to-[#00ab55] w-full h-full rounded-full relative before:absolute before:inset-y-0 ltr:before:right-0.5 rtl:before:left-0.5 before:bg-white before:w-2 before:h-2 before:rounded-full before:m-auto"
                                style="width: ${row.bar_progress}%;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 pt-3 gap-3 mb-3">
                    <div class="text-center text-white-light z-[7]">
                        <div class="shadow rounded p-2 xl:text-4xl sm:text-xl">
                            <p class="p-1 xl:text-lg sm:text-sm">GSPH</p>
                            <span id="inpt_current_gsph_${machine_id}">${row.sph}</span>
                        </div>
                    </div>
                    <div class="text-center text-white-light z-[7]">
                        <div class="shadow rounded p-2 xl:text-4xl sm:text-xl">
                            <p class="p-1 xl:text-lg sm:text-sm">CT</p>
                            <span id="inpt_average_ct_${machine_id}">${row.ct}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;

                container.insertAdjacentHTML('beforeend', cardHTML);
            });
        }
    </script>


</x-layout.default>
