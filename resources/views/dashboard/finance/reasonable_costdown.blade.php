<x-layout.default>
    <script defer src="/assets/js/apexcharts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

    <style>
        select[name="rcd_sumary_table_length"],
        select[name="rcd_table_year_length"],
        select[name="table_transaction_effect_length"],
        select[name="table_transaction_category_length"],
        select[name="table_transaction_categoryAccum_length"],
        select[name="table_transaction_effectAccum_length"],
        select[name="tabel_activity_length"],
        select[name="table_dept_length"],
        select[name="table_dept_accum_length"],
        select[name="table_summary_month_length"] {
            background-color: #4B5563;
            width: 50px;
            font-size: 9pt;
            margin: 10px;
        }

        .dataTables_paginate {
            margin-top: 10px
        }
    </style>

    <div x-data="analytics">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>Finance</span>
            </li>
            <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
                <span>RCD</span>
            </li>
        </ul>

        <div class="-mt-2">
            <div class="grid sm:grid-cols-2 xl:grid-cols-12 gap-6 mb-6 slide-container">
                <div class="slideshow-container h-full sm:col-span-6 xl:col-span-6 active"></div>
                <div class="slideshow-container-right h-full sm:col-span-6 xl:col-span-6 active"></div>
            </div>

            <div class="grid sm:grid-cols-2 xl:grid-cols-12 gap-6 mb-6">
                <?php
                $currentYear = date('Y');
                $startYear = $currentYear + 5;
                $currentMonth = date('Y-m');
                ?>

                <!-- Summary Per Year -->
                <div class="viewslide panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-3 gap-4 p-5 dark:text-white-light">
                        <span class="font-bold text-xl">Years</span>
                    </div>
                    <hr>
                    <div class="flex-1 overflow-hidden">
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_year"
                                x-on:click="() => ChartRecall1(inpt_val_plan_year.value, inpt_val_actual_year.value, inpt_val_persentase_year.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_plan_year" value="" hidden>
                            <input type="text" id="inpt_val_actual_year" value="" hidden>
                            <input type="text" id="inpt_val_persentase_year" value="" hidden>
                            <div x-ref="BoxChart2" class="overflow-hidden"></div>
                            <div
                                class="relative overflow-x-auto border p-4 border-gray-300 dark:border-gray-700 sm:rounded-lg pt-5">
                                <table id="rcd_table_year"
                                    class="min-w-full text-sm text-left text-gray-800 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                        <tr style="font-size: 0.9674rem;">
                                            <th scope="col" class="px-6 py-3">Year</th>
                                            <th scope="col" class="px-6 py-3">Plan</th>
                                            <th scope="col" class="px-6 py-3">Actual</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category Per Year -->
                <div class="viewslide_right panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class=" grid grid-cols-2">
                            <span class="text-xl font-bold">Year Category</span>
                            <input id="pie_years" type="number" class="form-input" value="<?= $currentYear ?>" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_cat"
                                x-on:click="() => ChartRecall2(inpt_val_plan_cat.value, inpt_val_actual_cat.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_plan_cat" value="" hidden>
                            <input type="text" id="inpt_val_actual_cat" value="" hidden>
                            <div x-ref="BoxChart3" class="overflow-hidden"></div>
                        </div>
                    </div>
                </div>

                <!-- Per Month Accumulation -->
                <div class="viewslide panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class=" grid grid-cols-2">
                            <span class="text-xl font-bold">Month Accumulation</span>
                            <input id="current_year" type="number" class="form-input" value="<?= $currentYear ?>" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_chart"
                                x-on:click="() => ChartRecall3(inpt_val_plan_accum.value, inpt_val_actual_accum.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_plan_accum" value="" hidden>
                            <input type="text" id="inpt_val_actual_accum" value="" hidden>
                            <input id="month_year_table2" type="month" class="form-input" value=""
                                style="color: white;" hidden />
                            <div x-ref="BoxChart5" class="overflow-hidden"></div>
                        </div>
                    </div>
                    <div
                        class="relative overflow-x-auto border p-4 border-gray-300 dark:border-gray-700 sm:rounded-lg pt-5">
                        <table id="rcd_sumary_table"
                            class="min-w-full text-sm text-left text-gray-800 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                <tr style="font-size: 0.9674rem;">
                                    <th scope="col" class="px-6 py-3">Year</th>
                                    <th scope="col" class="px-6 py-3">Month</th>
                                    <th scope="col" class="px-6 py-3">Plan</th>
                                    <th scope="col" class="px-6 py-3">Actual</th>
                                    <th scope="col" class="px-6 py-3">Percent</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- RCD Sumarry Per Month -->
                <div class="viewslide panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="  grid grid-cols-2">
                            <span class="text-xl font-bold">Quarter Summary</span>
                            <input id="summary_year" type="number" class="form-input"
                                value="<?= $currentYear ?>" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="update_summary_month"
                                x-on:click="() => ChartRecall8(inpt_sumarryPlan_month.value, inpt_sumarryActual_month.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_sumarryPlan_month" value="" hidden>
                            <input type="text" id="inpt_sumarryActual_month" value="" hidden>
                            <div x-ref="BoxChart10" class="overflow-hidden"></div>
                        </div>
                    </div>
                    <div
                        class="relative overflow-x-auto border p-4 border-gray-300 dark:border-gray-700 sm:rounded-lg pt-5">
                        <table id="table_summary_month"
                            class="min-w-full text-sm text-left text-gray-800 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                <tr style="font-size: 0.9674rem;">
                                    <th scope="col" class="px-6 py-3">Year</th>
                                    <th scope="col" class="px-6 py-3">Month</th>
                                    <th scope="col" class="px-6 py-3">Plan</th>
                                    <th scope="col" class="px-6 py-3">Actual</th>
                                    <th scope="col" class="px-6 py-3">Percent</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <!-- Transaction Category Accum -->
                <div class="viewslide panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class=" grid grid-cols-2">
                            <span class="text-xl font-bold">Transaction Category Accum</span>
                            <input id="categoryAccum_year" type="month" class="form-input"
                                value="<?= $currentMonth ?>">
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_categoryAccum"
                                x-on:click="() => ChartRecall6(inpt_val_plan_categoryAccum.value, inpt_val_actual_categoryAccum.value, inpt_val_category_categoryAccum.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_plan_categoryAccum" value="" hidden>
                            <input type="text" id="inpt_val_actual_categoryAccum" value="" hidden>
                            <input type="text" id="inpt_val_category_categoryAccum" value="" hidden>
                            <input id="month_year_table5" type="month" class="form-input" value=""
                                style="color: white;" hidden />
                            <div x-ref="BoxChart8" class="overflow-hidden"></div>
                        </div>
                        <div
                            class="relative overflow-x-auto border p-4 border-gray-300 dark:border-gray-700 sm:rounded-lg pt-5">
                            <table id="table_transaction_categoryAccum"
                                class="min-w-full text-sm text-left text-gray-800 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                    <tr style="font-size: 0.9674rem;">
                                        <th scope="col" class="px-6 py-3">Year</th>
                                        <th scope="col" class="px-6 py-3">Month</th>
                                        <th scope="col" class="px-6 py-3">Category</th>
                                        <th scope="col" class="px-6 py-3">Plan</th>
                                        <th scope="col" class="px-6 py-3">Actual</th>
                                        <th scope="col" class="px-6 py-3">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Transaction Effect Category -->
                <div class="viewslide panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class=" grid grid-cols-2">
                            <span class="text-xl font-bold">Transaction Category</span>
                            <input id="categoryEff_year" type="month" class="form-input"
                                value="<?= $currentMonth ?>" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_trancategory"
                                x-on:click="() => ChartRecall5(inpt_val_plan_trancategory.value, inpt_val_actual_trancategory.value, inpt_val_category_trancategory.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_plan_trancategory" value="" hidden>
                            <input type="text" id="inpt_val_actual_trancategory" value="" hidden>
                            <input type="text" id="inpt_val_category_trancategory" value="" hidden>
                            <input id="month_year_table4" type="month" class="form-input" value=""
                                style="color: white;" hidden />
                            <div x-ref="BoxChart7" class="overflow-hidden"></div>
                        </div>

                        <div
                            class="relative overflow-x-auto border p-4 border-gray-300 dark:border-gray-700 sm:rounded-lg pt-5">
                            <table id="table_transaction_category"
                                class="min-w-full text-sm text-left text-gray-800 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                    <tr style="font-size: 0.9674rem;">
                                        <th scope="col" class="px-6 py-3">Year</th>
                                        <th scope="col" class="px-6 py-3">Month</th>
                                        <th scope="col" class="px-6 py-3">Category</th>
                                        <th scope="col" class="px-6 py-3">Plan</th>
                                        <th scope="col" class="px-6 py-3">Actual</th>
                                        <th scope="col" class="px-6 py-3">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Transaction Effect Accum -->
                <div class="viewslide panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class=" grid grid-cols-2">
                            <span class="text-xl font-bold">Transaction Effect Accum</span>
                            <input id="effectAccum_year" type="month" class="form-input"
                                value="<?= $currentMonth ?>" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_effectAccum"
                                x-on:click="() => ChartRecall7(inpt_val_plan_effectAccum.value, inpt_val_actual_effectAccum.value, inpt_val_category_effectAccum.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_plan_effectAccum" value="" hidden>
                            <input type="text" id="inpt_val_actual_effectAccum" value="" hidden>
                            <input type="text" id="inpt_val_category_effectAccum" value="" hidden>
                            <input id="month_year_table6" type="month" class="form-input" value=""
                                style="color: white;" hidden />
                            <div x-ref="BoxChart9" class="overflow-hidden"></div>
                        </div>
                        <div
                            class="relative overflow-x-auto border p-4 border-gray-300 dark:border-gray-700 sm:rounded-lg pt-5">
                            <table id="table_transaction_effectAccum"
                                class="min-w-full text-sm text-left text-gray-800 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                    <tr style="font-size: 0.9674rem;">
                                        <th scope="col" class="px-6 py-3">Year</th>
                                        <th scope="col" class="px-6 py-3">Month</th>
                                        <th scope="col" class="px-6 py-3">Transaction Effects</th>
                                        <th scope="col" class="px-6 py-3">Plan</th>
                                        <th scope="col" class="px-6 py-3">Actual</th>
                                        <th scope="col" class="px-6 py-3">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Transaction Effect -->
                <div class="viewslide panel h-full sm:col-span-6 xl:col-span-6">
                    <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-2">
                            <span class="text-xl font-bold">Transaction Effect</span>
                            <input id="transaction_year" type="month" class="form-input"
                                value="<?= $currentMonth ?>" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="btn_update_effect"
                                x-on:click="() => ChartRecall4(inpt_val_plan_effect.value, inpt_val_actual_effect.value, inpt_val_category_effect.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_plan_effect" value="" hidden>
                            <input type="text" id="inpt_val_actual_effect" value="" hidden>
                            <input type="text" id="inpt_val_category_effect" value="" hidden>

                            <input id="month_year_table3" type="month" class="form-input" value="" hidden />
                            <div x-ref="BoxChart6" class="overflow-hidden"></div>
                        </div>
                    </div>

                    <div
                        class="relative overflow-x-auto border p-4 border-gray-300 dark:border-gray-700 sm:rounded-lg pt-5">
                        <table id="table_transaction_effect"
                            class="min-w-full text-sm text-left text-gray-800 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                <tr style="font-size: 0.9674rem;">
                                    <th scope="col" class="px-6 py-3">Year</th>
                                    <th scope="col" class="px-6 py-3">Month</th>
                                    <th scope="col" class="px-6 py-3">Transaction Effects</th>
                                    <th scope="col" class="px-6 py-3">Plan</th>
                                    <th scope="col" class="px-6 py-3">Actual</th>
                                    <th scope="col" class="px-6 py-3">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <!-- RCD Transaction Activity -->
                <div class="viewslide panel h-full sm:col-span-12 xl:col-span-12">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-4 gap-4">
                            <span class="text-xl font-bold">Transaction Activity</span>
                            <select class="form-input" name="sort_select" id="sort_select">
                                <option value="Plan">Sort Plan</option>
                                <option value="Actual">Sort Actual</option>
                            </select>
                            <select class="form-input" name="category_select" id="category_select">
                                <option value="Cash-Budgeted">Cash - Budgeted</option>
                                <option value="Other Idea - Cash Unbudgeted">Other Idea - Cash Unbudgeted</option>
                                <option value="Cash-Unbudgeted">Cash - Unbudgeted</option>
                                <option value="Potential Cash">Potential Cash</option>
                            </select>
                            <input id="activity_month" type="month" class="form-input"
                                value="<?= $currentMonth ?>">
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="update_activity"
                                x-on:click="() => ChartRecall9(inpt_activity_plan.value, inpt_activity_actual.value, inpt_activity_category.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_activity_plan" value="" hidden>
                            <input type="text" id="inpt_activity_actual" value="" hidden>
                            <input type="text" id="inpt_activity_category" value="" hidden>
                            <div x-ref="BoxChart11" class="overflow-hidden"></div>
                        </div>
                    </div>

                    <div
                        class="relative overflow-x-auto border p-4 border-gray-300 dark:border-gray-700 sm:rounded-lg pt-5">
                        <table id="tabel_activity"
                            class="min-w-full text-sm text-left text-gray-800 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                <tr style="font-size: 0.9674rem;">
                                    <th scope="col" class="px-6 py-3">Year</th>
                                    <th scope="col" class="px-6 py-3">Month</th>
                                    <th scope="col" class="px-6 py-3">RCD Number</th>
                                    <th scope="col" class="px-6 py-3">Description</th>
                                    <th scope="col" class="px-6 py-3">Plan</th>
                                    <th scope="col" class="px-6 py-3">Actual</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- RCD Dept Accumulative -->
                <div class="viewslide panel h-full sm:col-span-12 xl:col-span-12">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-2">
                            <span class="text-xl font-bold">Departement Accumulative</span>
                            <input id="dept_month_accum" type="month" class="form-input"
                                value="<?= $currentMonth ?>" />
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="update_dept_accum"
                                x-on:click="() => ChartRecall11(inpt_val_plan_deptAccum.value, inpt_val_actual_deptAccum.value, inpt_val_category_deptAccum.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_plan_deptAccum" value="" hidden>
                            <input type="text" id="inpt_val_actual_deptAccum" value="" hidden>
                            <input type="text" id="inpt_val_category_deptAccum" value="" hidden>
                            <input id="month_year_dept_accum" type="month" class="form-input" value=""
                                style="color: white;" hidden />
                            <div x-ref="BoxChart13" class="overflow-hidden"></div>
                        </div>
                        <div
                            class="relative overflow-x-auto border p-4 border-gray-300 dark:border-gray-700 sm:rounded-lg pt-5">
                            <table id="table_dept_accum"
                                class="min-w-full text-sm text-left text-gray-800 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                    <tr style="font-size: 0.9674rem;">
                                        <th scope="col" class="px-6 py-3">Year</th>
                                        <th scope="col" class="px-6 py-3">Month</th>
                                        <th scope="col" class="px-6 py-3">Dept</th>
                                        <th scope="col" class="px-6 py-3">Plan</th>
                                        <th scope="col" class="px-6 py-3">Actual</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- RCD Dept Summary -->
                <div class="viewslide panel h-full sm:col-span-12 xl:col-span-12">
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
                        <div class="grid grid-cols-2">
                            <span class="text-xl font-bold">Departement Summary</span>
                            <input id="dept_month" type="month" class="form-input" value="<?= $currentMonth ?>">
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="h-full sm:col-span-6 xl:col-span-2">
                            <button id="update_dept"
                                x-on:click="() => ChartRecall10(inpt_val_plan_dept.value, inpt_val_actual_dept.value, inpt_val_category_dept.value)"
                                hidden>Recall and Update</button>
                            <input type="text" id="inpt_val_plan_dept" value="" hidden>
                            <input type="text" id="inpt_val_actual_dept" value="" hidden>
                            <input type="text" id="inpt_val_category_dept" value="" hidden>
                            <input id="month_year_dept" type="month" class="form-input" value=""
                                style="color: white;" hidden />
                            <div x-ref="BoxChart12" class="overflow-hidden"></div>
                        </div>

                        <div
                            class="relative overflow-x-auto border p-4 border-gray-300 dark:border-gray-700 sm:rounded-lg pt-5">
                            <table id="table_dept"
                                class="min-w-full text-sm text-left text-gray-800 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                    <tr style="font-size: 0.9674rem;">
                                        <th scope="col" class="px-6 py-3">Year</th>
                                        <th scope="col" class="px-6 py-3">Month</th>
                                        <th scope="col" class="px-6 py-3">Dept</th>
                                        <th scope="col" class="px-6 py-3">Plan</th>
                                        <th scope="col" class="px-6 py-3">Actual</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let currentIndexLeft = 0;
            let currentIndexRight = 0;
            let intervalId;

            const slidesLeft = document.querySelectorAll('.viewslide');
            const slidesRight = document.querySelectorAll('.viewslide_right');

            function showSlide(slides, currentIndex) {
                slides.forEach((slide, index) => {
                    slide.classList.toggle('active', index === currentIndex);
                });
            }

            function startSlideshow() {
                showSlide(slidesLeft, currentIndexLeft);
                showSlide(slidesRight, currentIndexRight);

                currentIndexLeft = (currentIndexLeft + 1) % slidesLeft.length;
                currentIndexRight = (currentIndexRight + 1) % slidesRight.length;
            }

            function startInterval() {
                intervalId = setInterval(startSlideshow, 3000);
            }

            function stopInterval() {
                clearInterval(intervalId);
            }

            if (slidesLeft.length > 0 && slidesRight.length > 0) {
                startSlideshow();
                startInterval();

                slidesLeft.forEach(slide => {
                    slide.addEventListener('mouseenter', stopInterval);
                    slide.addEventListener('mouseleave', startInterval);
                });

                slidesRight.forEach(slide => {
                    slide.addEventListener('mouseenter', stopInterval);
                    slide.addEventListener('mouseleave', startInterval);
                });
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            let currentMonth = new Date().toISOString().split('T')[0].slice(0, 7);

            function setDefaultData() {
                const currentHost = window.location.host;

                function fetchData(url, callback) {
                    axios.get(url)
                        .then(response => callback(response.data))
                        .catch(error => console.error(`Error fetching ${url}:`, error));
                }

                fetchData(`https://${currentHost}/api/finance/get_RCDPerYears`, data => {
                    const {
                        data_plan_year,
                        data_actual_year,
                        data_persentase_year
                    } = data;

                    const years = Object.keys(data_plan_year)
                        .map(year => parseInt(year))
                        .sort((a, b) => a - b);

                    document.getElementById('inpt_val_plan_year').value = years.map(year => data_plan_year[
                        year]).join(',');
                    document.getElementById('inpt_val_persentase_year').value = years.map(year =>
                        data_persentase_year[year]).join(',');
                    document.getElementById('inpt_val_actual_year').value = years.map(year =>
                        data_actual_year[year]).join(',');
                    document.getElementById('btn_update_year').click();
                });

                const selectedYear = document.getElementById('current_year').value;
                fetchData(`https://${currentHost}/api/finance/get_RCDPerMonthAccum/${selectedYear}`, data => {
                    document.getElementById('inpt_val_plan_accum').value = (data.data_plan_accum || [])
                        .join(',');
                    document.getElementById('inpt_val_actual_accum').value = (data.data_actual_accum || [])
                        .join(',');

                    document.getElementById('btn_update_chart').click();
                });

                const categoryYear = document.getElementById('pie_years').value;
                fetchData(`https://${currentHost}/api/finance/get_RCDPerYearsCategory/${categoryYear}`, data => {
                    document.getElementById('inpt_val_plan_cat').value = (data.data_plan_cat || []).join(
                        ',');
                    document.getElementById('inpt_val_actual_cat').value = (data.data_actual_cat || [])
                        .join(',');
                    document.getElementById('btn_update_cat').click();
                });

                const transactionEffect = document.getElementById('transaction_year').value;
                fetchData(`https://${currentHost}/api/finance/get_transaction_effect/${transactionEffect}`,
                    data => {
                        document.getElementById('inpt_val_plan_effect').value = (data.data_plan_effect || [])
                            .join(',');
                        document.getElementById('inpt_val_actual_effect').value = (data.data_actual_effect ||
                        []).join(',');
                        document.getElementById('inpt_val_category_effect').value = data.categories;
                        document.getElementById('btn_update_effect').click();
                    });

                const transactionCategory = document.getElementById('categoryEff_year').value;
                fetchData(`https://${currentHost}/api/finance/get_transaction_Category/${transactionCategory}`,
                    data => {
                        document.getElementById('inpt_val_plan_trancategory').value = data.data_plan_Category;
                        document.getElementById('inpt_val_actual_trancategory').value = data
                            .data_actual_Category;
                        document.getElementById('inpt_val_category_trancategory').value = data
                            .categoriesTransaction;
                        document.getElementById('btn_update_trancategory').click();
                    });

                const selectMonthAccum = document.getElementById('categoryAccum_year').value;
                fetchData(`https://${currentHost}/api/finance/get_transaction_CategoryAccum/${selectMonthAccum}`,
                    data => {
                        document.getElementById('inpt_val_plan_categoryAccum').value = data
                            .data_plan_categoryAccum;
                        document.getElementById('inpt_val_actual_categoryAccum').value = data
                            .data_actual_categoryAccum;
                        document.getElementById('inpt_val_category_categoryAccum').value = data.categories;

                        document.getElementById('btn_update_categoryAccum').click();
                    });

                const selectMonthEffect = document.getElementById('effectAccum_year').value;
                fetchData(`https://${currentHost}/api/finance/get_transaction_EffectAccum/${selectMonthEffect}`,
                    data => {
                        document.getElementById('inpt_val_plan_effectAccum').value = data.data_plan_effectAccum;
                        document.getElementById('inpt_val_actual_effectAccum').value = data
                            .data_actual_effectAccum;
                        document.getElementById('inpt_val_category_effectAccum').value = data.categoriesEffect;
                        document.getElementById('btn_update_effectAccum').click();
                    });

                const selectMonthSummary = document.getElementById('summary_year').value;
                fetchData(`https://${currentHost}/api/finance/get_transaction_summaryMonth/${selectMonthSummary}`,
                    data => {
                        document.getElementById('inpt_sumarryPlan_month').value = data.data_plan_summary;
                        document.getElementById('inpt_sumarryActual_month').value = data.data_actual_summary;
                        document.getElementById('update_summary_month').click();
                    });

                const selectYears = document.getElementById('category_select').value;
                const selectSort = document.getElementById('sort_select').value;
                const selectCategory = document.getElementById('activity_month').value;
                fetchData(
                    `https://${currentHost}/api/finance/get_transaction_activity/${selectYears}~${selectCategory}~${selectSort}`,
                    data => {
                        document.getElementById('inpt_activity_plan').value = data.data_plan_activity;
                        document.getElementById('inpt_activity_actual').value = data.data_actual_activity;
                        document.getElementById('inpt_activity_category').value = data.data_category_activity;
                        document.getElementById('update_activity').click();
                    });

                const selectDataDept = document.getElementById('dept_month').value;
                fetchData(`https://${currentHost}/api/finance/get_transaction_dept/${selectDataDept}`, data => {
                    document.getElementById('inpt_val_plan_dept').value = data.data_plan_dept;
                    document.getElementById('inpt_val_actual_dept').value = data.data_actual_dept;
                    document.getElementById('inpt_val_category_dept').value = data.data_category_dept;
                    document.getElementById('update_dept').click();
                });

                const selectDataDeptAccum = document.getElementById('dept_month_accum').value;
                fetchData(`https://${currentHost}/api/finance/get_transaction_deptAccum/${selectDataDeptAccum}`,
                    data => {
                        document.getElementById('inpt_val_plan_deptAccum').value = data.data_plan_deptAccum;
                        document.getElementById('inpt_val_actual_deptAccum').value = data.data_actual_deptAccum;
                        document.getElementById('inpt_val_category_deptAccum').value = data
                            .data_category_deptAccum;
                        document.getElementById('update_dept_accum').click();
                    });
            }

            setDefaultData();
            detail_table_summary();
            detail_table_year();
            detail_table_transaction();
            detail_table_category();
            detail_table_categoryAccum();
            detail_table_effectAccum();
            detail_table_summaryMonth();
            detail_table_activityMonth();
            detail_table_deptSummary();

            function formatNumber(value) {
                if (typeof value !== 'number') return value || '0';

                if (value >= 1_000_000_000) {
                    return (Math.ceil((value / 1_000_000_000) * 10) / 10) + 'B';
                } else if (value >= 1_000_000) {
                    return (Math.ceil((value / 1_000_000) * 10) / 10) + 'M';
                } else if (value >= 1_000) {
                    return (Math.ceil((value / 1_000) * 10) / 10) + 'K';
                } else {
                    return value.toString();
                }
            }



            function getMonthName(monthNumber) {
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                return months[parseInt(monthNumber, 10) - 1] || monthNumber;
            }

            function detail_table_year() {
                var selectYears = $('#current_year').val();
                if ($.fn.DataTable.isDataTable('#rcd_table_year')) {
                    $('#rcd_table_year').DataTable().destroy();
                }

                $('#rcd_table_year').DataTable({
                    destroy: true,
                    processing: true,
                    scrollX: true,
                    responsive: false,
                    paging: true,
                    searching: true,
                    lengthChange: true,
                    language: {
                        'processing': '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
                    },
                    info: false,
                    columnDefs: [{
                        orderable: false,
                        targets: 0
                    }],
                    ajax: {
                        url: "/api/finance/get_rcdYears_summary_table",
                        type: "POST",
                        contentType: "application/json",
                        data: function(d) {
                            return JSON.stringify({
                                year: selectYears
                            });
                        }
                    },
                    columns: [{
                            data: 'Years'
                        },
                        {
                            data: 'Plan',
                            render: function(data) {
                                return formatNumber(data);
                            }
                        },
                        {
                            data: 'Actual',
                            render: function(data) {
                                return formatNumber(data);
                            }
                        }
                    ],

                });
            }

            function detail_table_summary() {
                var selectYears = $('#current_year').val();
                if ($.fn.DataTable.isDataTable('#rcd_sumary_table')) {
                    $('#rcd_sumary_table').DataTable().destroy();
                }

                $('#rcd_sumary_table').DataTable({
                    destroy: true,
                    processing: true,
                    responsive: false,
                    scrollX: true,
                    sort: true,
                    paging: true,
                    searching: true,
                    language: {
                        'processing': '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
                    },
                    info: false,
                    columnDefs: [{
                        orderable: false,
                        targets: 0
                    }],
                    ajax: {
                        url: "/api/finance/get_invoice_summary_table",
                        type: "POST",
                        contentType: "application/json",
                        data: function(d) {
                            return JSON.stringify({
                                year: selectYears
                            });
                        }
                    },
                    columns: [{
                            data: 'Years'
                        },
                        {
                            data: 'Months',
                            render: function(data) {
                                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                                ];
                                return months[data - 1] || data;
                            }
                        },
                        {
                            data: 'AccumulativePlan',
                            render: {
                                display: function(data) {
                                    return formatNumber(data);
                                },
                                sort: function(data) {
                                    return parseFloat(data);
                                }
                            }
                        },
                        {
                            data: 'AccumulativeActual',
                            render: {
                                display: function(data) {
                                    return formatNumber(data);
                                },
                                sort: function(data) {
                                    return parseFloat(data);
                                }
                            }
                        },
                        {
                            data: 'Percents',
                            render: function(data) {
                                return parseFloat(data).toFixed(0) + '%';
                            }
                        }
                    ],

                });
            }

            function detail_table_transaction() {
                const $selectYears = $('#transaction_year');
                const $table = $('#table_transaction_effect');
                const selectYears = $selectYears.val();

                if ($.fn.DataTable.isDataTable($table)) {
                    $table.DataTable().destroy();
                }

                const table = $table.DataTable({
                    destroy: true,
                    scrollX: true,
                    processing: true,
                    responsive: false,
                    paging: true,
                    searching: true,
                    lengthChange: true,
                    language: {
                        'processing': '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
                    },
                    info: false,
                    columnDefs: [{
                        orderable: false,
                        targets: 0
                    }],
                    ajax: {
                        url: "/api/finance/get_transactionEffect_table",
                        type: "POST",
                        contentType: "application/json",
                        data: function(d) {
                            return JSON.stringify({
                                month: selectYears
                            });
                        }
                    },
                    columns: [{
                            data: 'Years'
                        },
                        {
                            data: 'Months',
                            render: function(data) {
                                return getMonthName(data);
                            }
                        },
                        {
                            data: 'TransactionEffects'
                        },
                        {
                            data: 'Plan',
                            render: {
                                display: function(data) {
                                    return formatNumber(data);
                                },
                                sort: function(data) {
                                    return parseFloat(data);
                                }
                            }
                        },
                        {
                            data: 'Actual',
                            render: {
                                display: function(data) {
                                    return formatNumber(data);
                                },
                                sort: function(data) {
                                    return parseFloat(data);
                                }
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `
                                    <button class="btn btn-primary btn-sm btn-detail" data-id="${row.id}" title="Lihat Detail">
                                        <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>
                                `;
                            }
                        }
                    ],

                });
                $table.off('click', '.btn-detail').on('click', '.btn-detail', function() {
                    const data = table.row($(this).parents('tr')).data();
                    if (data) {
                        showDetailTransactionEffect(data);
                    } else {
                        console.error('No data found for the selected row.');
                        alert('Error: Unable to load row data.');
                    }
                });
            }

            function showDetailTransactionEffect(rowData) {
                const sanitize = (str) => {
                    const div = document.createElement('div');
                    div.textContent = str;
                    return div.innerHTML;
                };

                $.ajax({
                    url: '/api/finance/get_transaction_effect_detail',
                    method: 'POST',
                    data: {
                        year: rowData.Years,
                        month: rowData.Months,
                        category: rowData.TransactionEffects,
                        selected_date: rowData.Years + '-' + rowData.Months + '-01'
                    },
                    success: function(response) {
                        if (!response.data || !Array.isArray(response.data) || response.data.length ===
                            0) {
                            Swal.fire({
                                title: 'No Data',
                                text: 'No records found for the selected criteria.',
                                icon: 'info',
                                showConfirmButton: true
                            });
                            return;
                        }

                        const itemsPerPage = 5;
                        let currentPage = 1;
                        const totalItems = response.data.length;
                        const totalPages = Math.ceil(totalItems / itemsPerPage);

                        const renderTable = (page) => {
                            const start = (page - 1) * itemsPerPage;
                            const end = start + itemsPerPage;
                            const paginatedData = response.data.slice(start, end);

                            const tableRows = paginatedData.map((row, index) => `
                <tr class="border-b border-gray-200">
                    <td class="px-3 py-2 text-sm">${sanitize((start + index + 1).toString())}</td>
                    <td class="px-3 py-2 text-sm">${sanitize(row.Years)}</td>
                    <td class="px-3 py-2 text-sm">${sanitize(getMonthName(row.Months))}</td>
                    <td class="px-3 py-2 text-sm">${sanitize(row.Category)}</td>
                    <td class="px-3 py-2 text-sm">${sanitize(row.Departement)}</td>
                    <td class="px-3 py-2 text-sm text-right">${sanitize(formatNumber(row.Plan))}</td>
                    <td class="px-3 py-2 text-sm text-right">${sanitize(formatNumber(row.Actual))}</td>
                </tr>
            `).join('');

                            const paginationControls = `
                <div class="mt-3 flex justify-center items-center gap-3 text-sm text-gray-700">
                    <button id="prev-page" ${page === 1 ? 'disabled' : ''}
                        class="px-3 py-1 border border-gray-300 rounded bg-gray-100 text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-200">
                        Previous
                    </button>
                    <span>Page ${page} of ${totalPages}</span>
                    <button id="next-page" ${page === totalPages ? 'disabled' : ''}
                        class="px-3 py-1 border border-gray-300 rounded bg-gray-100 text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-200">
                        Next
                    </button>
                </div>
            `;

                            const popupContent = `
                <div class="popup-detail bg-white text-gray-800 p-4 rounded-md flex flex-col items-center">
                    <div class="detail-info">
                        <h3 class="text-base font-bold mb-3">
                            Transaction Effects - ${sanitize(rowData.TransactionEffects)}
                        </h3>
                        <table class="w-full text-sm border border-gray-200">
                            <thead class="bg-gray-100 text-gray-800 dark:text-white">
                                <tr>
                                    <th class="px-3 py-2 border border-gray-200">No</th>
                                    <th class="px-3 py-2 border border-gray-200">Years</th>
                                    <th class="px-3 py-2 border border-gray-200">Months</th>
                                    <th class="px-3 py-2 border border-gray-200">Transaction Effects</th>
                                    <th class="px-3 py-2 border border-gray-200">Dept</th>
                                    <th class="px-3 py-2 border border-gray-200 text-right">Plan</th>
                                    <th class="px-3 py-2 border border-gray-200 text-right">Actual</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${tableRows}
                            </tbody>
                        </table>
                        ${totalItems > itemsPerPage ? paginationControls : ''}
                    </div>
                </div>
            `;

                            Swal.fire({
                                html: popupContent,
                                width: 800,
                                background: '#fff',
                                showCloseButton: true,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'detail-popup-class'
                                },
                                didOpen: () => {
                                    if (totalItems > itemsPerPage) {
                                        document.getElementById('prev-page')
                                            .addEventListener('click', () => {
                                                if (currentPage > 1) {
                                                    currentPage--;
                                                    Swal.close();
                                                    renderTable(currentPage);
                                                }
                                            });
                                        document.getElementById('next-page')
                                            .addEventListener('click', () => {
                                                if (currentPage < totalPages) {
                                                    currentPage++;
                                                    Swal.close();
                                                    renderTable(currentPage);
                                                }
                                            });
                                    }
                                }
                            });
                        };

                        renderTable(currentPage);
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Failed to fetch data. Please try again.',
                            icon: 'error',
                            showConfirmButton: true
                        });
                    }
                });

            }

            function detail_table_category() {

                const $selectedYearMonth = $('#categoryEff_year');
                const $table = $('#table_transaction_category');
                const selectedYearMonth = $selectedYearMonth.val();

                if ($.fn.DataTable.isDataTable($table)) {
                    $table.DataTable().destroy();
                }

                const table = $table.DataTable({
                    destroy: true,
                    scrollX: true,
                    processing: true,
                    responsive: true,
                    paging: true,
                    searching: true,
                    lengthChange: true,
                    language: {
                        'processing': '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
                    },
                    info: false,
                    columnDefs: [{
                        orderable: false,
                        targets: 0
                    }],
                    ajax: {
                        url: "/api/finance/get_transactionCategory_table",
                        type: "POST",
                        contentType: "application/json",
                        data: function(d) {
                            return JSON.stringify({
                                year: selectedYearMonth
                            });
                        }
                    },
                    columns: [{
                            data: 'Years'
                        },
                        {
                            data: 'Months',
                            render: function(data) {
                                return getMonthName(data);
                            }
                        },
                        {
                            data: 'Category'
                        },
                        {
                            data: 'Plan',
                            render: {
                                display: function(data) {
                                    return formatNumber(data);
                                },
                                sort: function(data) {
                                    return parseFloat(data);
                                }
                            }
                        },
                        {
                            data: 'Actual',
                            render: {
                                display: function(data) {
                                    return formatNumber(data);
                                },
                                sort: function(data) {
                                    return parseFloat(data);
                                }
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `
                                    <button class="btn btn-primary btn-sm btn-detail" data-id="${row.id}" title="Lihat Detail">
                                        <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>
                                `;
                            }
                        }
                    ],

                });
                $table.off('click', '.btn-detail').on('click', '.btn-detail', function() {
                    const data = table.row($(this).parents('tr')).data();
                    if (data) {
                        showDetailTransactionCatPopUp(data);
                    } else {
                        console.error('No data found for the selected row.');
                        alert('Error: Unable to load row data.');
                    }
                });
            }

            function showDetailTransactionCatPopUp(rowData) {
                const sanitize = (str) => {
                    const div = document.createElement('div');
                    div.textContent = str;
                    return div.innerHTML;
                };

                $.ajax({
                    url: '/api/finance/get_transaction_category_detail',
                    method: 'POST',
                    data: {
                        year: rowData.Years,
                        month: rowData.Months,
                        category: rowData.Category,
                        selected_date: rowData.Years + '-' + rowData.Months + '-01'
                    },
                    success: function(response) {
                        if (!response.data || !Array.isArray(response.data) || response.data.length ===
                            0) {
                            Swal.fire({
                                title: 'No Data',
                                text: 'No records found for the selected criteria.',
                                icon: 'info',
                                showConfirmButton: true
                            });
                            return;
                        }

                        const itemsPerPage = 5;
                        let currentPage = 1;
                        const totalItems = response.data.length;
                        const totalPages = Math.ceil(totalItems / itemsPerPage);

                        const renderTable = (page) => {
                            const start = (page - 1) * itemsPerPage;
                            const end = start + itemsPerPage;
                            const paginatedData = response.data.slice(start, end);

                            const tableRows = paginatedData.map((row, index) => `
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-3 py-2 text-gray-700 text-sm">${sanitize((start + index + 1).toString())}</td>
                    <td class="px-3 py-2 text-gray-700 text-sm">${sanitize(row.Years)}</td>
                    <td class="px-3 py-2 text-gray-700 text-sm">${sanitize(getMonthName(row.Months))}</td>
                    <td class="px-3 py-2 text-gray-700 text-sm">${sanitize(row.Category)}</td>
                    <td class="px-3 py-2 text-gray-700 text-sm">${sanitize(row.Departement)}</td>
                    <td class="px-3 py-2 text-gray-700 text-sm text-right">${sanitize(formatNumber(row.Plan))}</td>
                    <td class="px-3 py-2 text-gray-700 text-sm text-right">${sanitize(formatNumber(row.Actual))}</td>
                </tr>
            `).join('');

                            const paginationControls = `
                <div class="flex justify-center items-center mt-3 space-x-3 text-sm">
                    <button id="prev-page" ${page === 1 ? 'disabled' : ''}
                        class="px-3 py-1 rounded border bg-gray-200 text-gray-700 disabled:opacity-50">
                        Previous
                    </button>
                    <span class="text-gray-600">Page ${page} of ${totalPages}</span>
                    <button id="next-page" ${page === totalPages ? 'disabled' : ''}
                        class="px-3 py-1 rounded border bg-gray-200 text-gray-700 disabled:opacity-50">
                        Next
                    </button>
                </div>
            `;

                            const popupContent = `
                <div class="popup-detail bg-white text-gray-800 p-4 rounded-md flex flex-col items-center">
                    <div class="detail-info">
                        <h3 class="text-lg font-bold mb-3 text-gray-800">
                            Category - ${sanitize(rowData.Category)}
                        </h3>
                        <table class="w-full border border-gray-300 text-sm">
                            <thead class="bg-gray-100 text-gray-700 dark:text-white">
                                <tr>
                                    <th class="px-3 py-2 text-left">No</th>
                                    <th class="px-3 py-2 text-left">Years</th>
                                    <th class="px-3 py-2 text-left">Months</th>
                                    <th class="px-3 py-2 text-left">Category</th>
                                    <th class="px-3 py-2 text-left">Dept</th>
                                    <th class="px-3 py-2 text-right">Plan</th>
                                    <th class="px-3 py-2 text-right">Actual</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${tableRows}
                            </tbody>
                        </table>
                        ${totalItems > itemsPerPage ? paginationControls : ''}
                    </div>
                </div>
            `;

                            Swal.fire({
                                html: popupContent,
                                width: 800,
                                background: '#ffffff',
                                showCloseButton: true,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'detail-popup-class'
                                },
                                didOpen: () => {
                                    if (totalItems > itemsPerPage) {
                                        document.getElementById('prev-page')
                                            .addEventListener('click', () => {
                                                if (currentPage > 1) {
                                                    currentPage--;
                                                    Swal.close();
                                                    renderTable(currentPage);
                                                }
                                            });
                                        document.getElementById('next-page')
                                            .addEventListener('click', () => {
                                                if (currentPage < totalPages) {
                                                    currentPage++;
                                                    Swal.close();
                                                    renderTable(currentPage);
                                                }
                                            });
                                    }
                                }
                            });
                        };

                        renderTable(currentPage);
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Failed to fetch data. Please try again.',
                            icon: 'error',
                            showConfirmButton: true
                        });
                    }
                });

            }


            function detail_table_categoryAccum() {

                const $selectYearMonth = $('#categoryAccum_year');
                const $table = $('#table_transaction_categoryAccum');
                const selectYearMonth = $selectYearMonth.val();


                if ($.fn.DataTable.isDataTable($table)) {
                    $table.DataTable().destroy();
                }

                const table = $table.DataTable({
                    destroy: true,
                    processing: true,
                    responsive: true,
                    scrollX: true,
                    paging: true,
                    searching: true,
                    lengthChange: true,
                    language: {
                        processing: '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
                    },
                    info: false,
                    columnDefs: [{
                        orderable: false,
                        targets: 0
                    }],
                    ajax: {
                        url: '/api/finance/get_transaction_CategoryAccum_table',
                        type: 'POST',
                        contentType: 'application/json',
                        data: function(d) {
                            return JSON.stringify({
                                yearMonth: selectYearMonth
                            });
                        },
                    },
                    columns: [{
                            data: 'Years'
                        },
                        {
                            data: 'Months',
                            render: function(data) {
                                return getMonthName(data);
                            }
                        },
                        {
                            data: 'Category'
                        },
                        {
                            data: 'Plan',
                            render: {
                                display: function(data) {
                                    return formatNumber(data);
                                },
                                sort: function(data) {
                                    return parseFloat(data);
                                }
                            }
                        },
                        {
                            data: 'Actual',
                            render: {
                                display: function(data) {
                                    return formatNumber(data);
                                },
                                sort: function(data) {
                                    return parseFloat(data);
                                }
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `
                                    <button class="btn btn-primary btn-sm btn-detail" data-id="${row.id}" title="Lihat Detail">
                                        <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>
                                `;
                            }
                        }
                    ],

                });

                $table.off('click', '.btn-detail').on('click', '.btn-detail', function() {
                    const data = table.row($(this).parents('tr')).data();
                    if (data) {
                        showDetailPopup(data);
                    } else {
                        console.error('No data found for the selected row.');
                        alert('Error: Unable to load row data.');
                    }
                });
            }

            function showDetailPopup(rowData) {
                const sanitize = (str) => {
                    const div = document.createElement('div');
                    div.textContent = str;
                    return div.innerHTML;
                };

                $.ajax({
                    url: '/api/finance/get_transaction_accum_detail',
                    method: 'POST',
                    data: {
                        year: rowData.Years,
                        month: rowData.Months,
                        category: rowData.Category,
                        selected_date: rowData.Years + '-' + rowData.Months + '-01'
                    },
                    success: function(response) {
                        if (!response.data || !Array.isArray(response.data) || response.data.length ===
                            0) {
                            Swal.fire({
                                title: 'No Data',
                                text: 'No records found for the selected criteria.',
                                icon: 'info',
                                showConfirmButton: true
                            });
                            return;
                        }

                        const itemsPerPage = 5;
                        let currentPage = 1;
                        const totalItems = response.data.length;
                        const totalPages = Math.ceil(totalItems / itemsPerPage);

                        const renderTable = (page) => {
                            const start = (page - 1) * itemsPerPage;
                            const end = start + itemsPerPage;
                            const paginatedData = response.data.slice(start, end);

                            const tableRows = paginatedData.map((row, index) => `
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-3 py-2 text-gray-700 text-sm text-center">${sanitize((start + index + 1).toString())}</td>
                    <td class="px-3 py-2 text-gray-700 text-sm text-center">${sanitize(row.Years)}</td>
                    <td class="px-3 py-2 text-gray-700 text-sm text-center">${sanitize(getMonthName(row.Months))}</td>
                    <td class="px-3 py-2 text-gray-700 text-sm text-center">${sanitize(row.Category)}</td>
                    <td class="px-3 py-2 text-gray-700 text-sm text-center">${sanitize(row.Departement)}</td>
                    <td class="px-3 py-2 text-gray-700 text-sm text-right">${sanitize(formatNumber(row.Plan))}</td>
                    <td class="px-3 py-2 text-gray-700 text-sm text-right">${sanitize(formatNumber(row.Actual))}</td>
                </tr>
            `).join('');

                            const paginationControls = `
                <div class="flex justify-center items-center mt-3 space-x-3 text-sm">
                    <button id="prev-page" ${page === 1 ? 'disabled' : ''}
                        class="px-3 py-1 rounded border bg-gray-200 text-gray-700 disabled:opacity-50">
                        Previous
                    </button>
                    <span class="text-gray-600">Page ${page} of ${totalPages}</span>
                    <button id="next-page" ${page === totalPages ? 'disabled' : ''}
                        class="px-3 py-1 rounded border bg-gray-200 text-gray-700 disabled:opacity-50">
                        Next
                    </button>
                </div>
            `;

                            const popupContent = `
                <div class="popup-detail bg-white text-gray-800 p-6 rounded-md flex flex-col items-center">
                    <div class="detail-info w-full max-w-4xl">
                        <h3 class="text-lg font-bold mb-4 text-gray-800 text-center">
                            Category Accum - ${sanitize(rowData.Category)}
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full border border-gray-300 text-sm text-center">
                                <thead class="bg-gray-100 text-gray-700 dark:text-white">
                                    <tr>
                                        <th class="px-3 py-2">No</th>
                                        <th class="px-3 py-2">Years</th>
                                        <th class="px-3 py-2">Months</th>
                                        <th class="px-3 py-2">Category</th>
                                        <th class="px-3 py-2">Dept</th>
                                        <th class="px-3 py-2 text-right">Plan</th>
                                        <th class="px-3 py-2 text-right">Actual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${tableRows}
                                </tbody>
                            </table>
                        </div>
                        ${totalItems > itemsPerPage ? paginationControls : ''}
                    </div>
                </div>
            `;

                            Swal.fire({
                                html: popupContent,
                                width: 850,
                                background: '#ffffff',
                                showCloseButton: true,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'detail-popup-class'
                                },
                                didOpen: () => {
                                    if (totalItems > itemsPerPage) {
                                        document.getElementById('prev-page')
                                            .addEventListener('click', () => {
                                                if (currentPage > 1) {
                                                    currentPage--;
                                                    Swal.close();
                                                    renderTable(currentPage);
                                                }
                                            });
                                        document.getElementById('next-page')
                                            .addEventListener('click', () => {
                                                if (currentPage < totalPages) {
                                                    currentPage++;
                                                    Swal.close();
                                                    renderTable(currentPage);
                                                }
                                            });
                                    }
                                }
                            });
                        };

                        renderTable(currentPage);
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Failed to fetch data. Please try again.',
                            icon: 'error',
                            showConfirmButton: true
                        });
                    }
                });

            }

            function detail_table_effectAccum() {
                const $selectYearMonth = $('#effectAccum_year');
                const $table = $('#table_transaction_effectAccum');
                const selectYearMonth = $selectYearMonth.val();

                if ($.fn.DataTable.isDataTable($table)) {
                    $table.DataTable().destroy();
                }

                const table = $table.DataTable({
                    destroy: true,
                    processing: true,
                    responsive: true,
                    scrollX: true,
                    paging: true,
                    searching: true,
                    lengthChange: true,
                    language: {
                        'processing': '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
                    },
                    info: false,
                    columnDefs: [{
                        orderable: false,
                        targets: 0
                    }],
                    ajax: {
                        url: "/api/finance/get_transaction_EffectAccum_table",
                        type: "POST",
                        contentType: "application/json",
                        data: function(d) {
                            return JSON.stringify({
                                yearMonth: selectYearMonth
                            });
                        }
                    },
                    columns: [{
                            data: 'Years'
                        },
                        {
                            data: 'Months',
                            render: function(data) {
                                return getMonthName(data);
                            }
                        },
                        {
                            data: 'TransactionEffects'
                        },
                        {
                            data: 'Plan',
                            render: {
                                display: function(data) {
                                    return formatNumber(data);
                                },
                                sort: function(data) {
                                    return parseFloat(data);
                                }
                            }
                        },
                        {
                            data: 'Actual',
                            render: {
                                display: function(data) {
                                    return formatNumber(data);
                                },
                                sort: function(data) {
                                    return parseFloat(data);
                                }
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `
                                    <button class="btn btn-primary btn-sm btn-detail" data-id="${row.id}" title="Lihat Detail">
                                        <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>
                                `;
                            }
                        }


                    ],

                });
                $table.off('click', '.btn-detail').on('click', '.btn-detail', function() {
                    const data = table.row($(this).parents('tr')).data();
                    if (data) {
                        showDetailTransactionEffAccum(data);
                    } else {
                        console.error('No data found for the selected row.');
                        alert('Error: Unable to load row data.');
                    }
                });
            }

            function showDetailTransactionEffAccum(rowData) {
                const sanitize = (str) => {
                    const div = document.createElement('div');
                    div.textContent = str;
                    return div.innerHTML;
                };

                $.ajax({
                    url: '/api/finance/get_transaction_effect_accum_detail',
                    method: 'POST',
                    data: {
                        year: rowData.Years,
                        month: rowData.Months,
                        category: rowData.TransactionEffects,
                        selected_date: rowData.Years + '-' + rowData.Months + '-01'
                    },
                    success: function(response) {
                        if (!response.data || !Array.isArray(response.data) || response.data.length ===
                            0) {
                            Swal.fire({
                                title: 'No Data',
                                text: 'No records found for the selected criteria.',
                                icon: 'info',
                                showConfirmButton: true
                            });
                            return;
                        }

                        const itemsPerPage = 5;
                        let currentPage = 1;
                        const totalItems = response.data.length;
                        const totalPages = Math.ceil(totalItems / itemsPerPage);

                        const renderTable = (page) => {
                            const start = (page - 1) * itemsPerPage;
                            const end = start + itemsPerPage;
                            const paginatedData = response.data.slice(start, end);

                            const tableRows = paginatedData.map((row, index) => `
                                <tr class="border-b border-gray-200">
                                    <td class="px-3 py-2 text-sm">${sanitize((start + index + 1).toString())}</td>
                                    <td class="px-3 py-2 text-sm">${sanitize(row.Years)}</td>
                                    <td class="px-3 py-2 text-sm">${sanitize(getMonthName(row.Months))}</td>
                                    <td class="px-3 py-2 text-sm">${sanitize(row.Category)}</td>
                                    <td class="px-3 py-2 text-sm">${sanitize(row.Departement)}</td>
                                    <td class="px-3 py-2 text-sm text-right">${sanitize(formatNumber(row.Plan))}</td>
                                    <td class="px-3 py-2 text-sm text-right">${sanitize(formatNumber(row.Actual))}</td>
                                </tr>
                            `).join('');

                            const paginationControls = `
                                <div class="mt-3 flex justify-center items-center gap-3 text-sm text-gray-700">
                                    <button id="prev-page" ${page === 1 ? 'disabled' : ''}
                                        class="px-3 py-1 border border-gray-300 rounded bg-gray-100 text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-200">
                                        Previous
                                    </button>
                                    <span>Page ${page} of ${totalPages}</span>
                                    <button id="next-page" ${page === totalPages ? 'disabled' : ''}
                                        class="px-3 py-1 border border-gray-300 rounded bg-gray-100 text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-200">
                                        Next
                                    </button>
                                </div>
                            `;

                            const popupContent = `
                                <div class="popup-detail bg-white text-gray-800 p-4 rounded-md flex flex-col items-center">
                                    <div class="detail-info">
                                        <h3 class="text-base font-bold mb-3">
                                            Transaction Effects - ${sanitize(rowData.TransactionEffects)}
                                        </h3>
                                        <table class="w-full text-sm border border-gray-200">
                                            <thead class="bg-gray-100 text-gray-800 dark:text-white">
                                                <tr>
                                                    <th class="px-3 py-2 border border-gray-200">No</th>
                                                    <th class="px-3 py-2 border border-gray-200">Years</th>
                                                    <th class="px-3 py-2 border border-gray-200">Months</th>
                                                    <th class="px-3 py-2 border border-gray-200">Transaction Effects</th>
                                                    <th class="px-3 py-2 border border-gray-200">Dept</th>
                                                    <th class="px-3 py-2 border border-gray-200 text-right">Plan</th>
                                                    <th class="px-3 py-2 border border-gray-200 text-right">Actual</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${tableRows}
                                            </tbody>
                                        </table>
                                        ${totalItems > itemsPerPage ? paginationControls : ''}
                                    </div>
                                </div>
                            `;

                            Swal.fire({
                                html: popupContent,
                                width: 800,
                                background: '#fff',
                                showCloseButton: true,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'detail-popup-class'
                                },
                                didOpen: () => {
                                    if (totalItems > itemsPerPage) {
                                        document.getElementById('prev-page')
                                            .addEventListener('click', () => {
                                                if (currentPage > 1) {
                                                    currentPage--;
                                                    Swal.close();
                                                    renderTable(currentPage);
                                                }
                                            });
                                        document.getElementById('next-page')
                                            .addEventListener('click', () => {
                                                if (currentPage < totalPages) {
                                                    currentPage++;
                                                    Swal.close();
                                                    renderTable(currentPage);
                                                }
                                            });
                                    }
                                }
                            });
                        };

                        renderTable(currentPage);
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Failed to fetch data. Please try again.',
                            icon: 'error',
                            showConfirmButton: true
                        });
                    }
                });

            }

            function detail_table_summaryMonth() {
                var selectYearMonth = $('#summary_year').val();

                if ($.fn.DataTable.isDataTable('#table_summary_month')) {
                    $('#table_summary_month').DataTable().destroy();
                }

                $('#table_summary_month').DataTable({
                    destroy: true,
                    processing: true,
                    scrollX: true,
                    responsive: false,
                    paging: true,
                    searching: true,
                    lengthChange: true,
                    language: {
                        'processing': '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
                    },
                    info: false,
                    columnDefs: [{
                        orderable: false,
                        targets: 0
                    }],
                    ajax: {
                        url: "/api/finance/get_transaction_summaryMonth_table",
                        type: "POST",
                        contentType: "application/json",
                        data: function(d) {
                            return JSON.stringify({
                                yearMonth: selectYearMonth
                            });
                        }
                    },
                    columns: [{
                            data: 'Years'
                        },
                        {
                            data: 'Months',
                            render: function(data) {
                                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                                ];
                                return months[data - 1] || data;
                            }
                        },
                        {
                            data: 'Plan',
                            render: {
                                display: function(data) {
                                    return formatNumber(data);
                                },
                                sort: function(data) {
                                    return parseFloat(data);
                                }
                            }
                        },
                        {
                            data: 'Actual',
                            render: {
                                display: function(data) {
                                    return formatNumber(data);
                                },
                                sort: function(data) {
                                    return parseFloat(data);
                                }
                            }
                        },
                        {
                            data: 'Percents',
                            render: function(data) {
                                return parseFloat(data).toFixed(0) + '%';
                            }
                        }
                    ],

                });
            }

            function detail_table_activityMonth() {
                var selectYearMonth = $('#activity_month').val();
                var selectCategory = $('#category_select').val();
                var selectSort = $('#sort_select').val();

                if ($.fn.DataTable.isDataTable('#tabel_activity')) {
                    $('#tabel_activity').DataTable().destroy();
                }

                $('#tabel_activity').DataTable({
                    destroy: true,
                    processing: true,
                    scrollX: true,
                    responsive: false,
                    paging: true,
                    searching: true,
                    lengthChange: true,
                    language: {
                        'processing': '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
                    },
                    info: false,
                    columnDefs: [{
                        orderable: false,
                        targets: 0
                    }],
                    ajax: {
                        url: "/api/finance/get_transaction_activity_table",
                        type: "POST",
                        contentType: "application/json",
                        data: function(d) {
                            return JSON.stringify({
                                yearMonth: selectYearMonth,
                                selectCategory: selectCategory,
                                selectSort: selectSort
                            });
                        }
                    },
                    columns: [{
                            data: 'Years'
                        },
                        {
                            data: 'Months',
                            render: function(data) {
                                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                                ];
                                return months[data - 1] || data;
                            }
                        },
                        {
                            data: 'Key2'
                        },
                        {
                            data: 'Description'
                        },
                        {
                            data: 'Plan',
                            render: {
                                display: function(data) {
                                    return formatNumber(data);
                                },
                                sort: function(data) {
                                    return parseFloat(data);
                                }
                            }
                        },
                        {
                            data: 'Actual',
                            render: {
                                display: function(data) {
                                    return formatNumber(data);
                                },
                                sort: function(data) {
                                    return parseFloat(data);
                                }
                            }
                        },
                    ],

                });
            }

            function detail_table_deptSummary() {
                var selectYearMonth = $('#dept_month').val();

                if ($.fn.DataTable.isDataTable('#table_dept')) {
                    $('#table_dept').DataTable().destroy();
                }

                $('#table_dept').DataTable({
                    destroy: true,
                    processing: true,
                    responsive: false,
                    scrollX: true,
                    paging: true,
                    searching: true,
                    lengthChange: true,
                    language: {
                        'processing': '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
                    },
                    info: false,
                    columnDefs: [{
                        orderable: false,
                        targets: 0
                    }],
                    ajax: {
                        url: "/api/finance/get_transaction_dept_table",
                        type: "POST",
                        contentType: "application/json",
                        data: function(d) {
                            return JSON.stringify({
                                yearMonth: selectYearMonth
                            });
                        }
                    },
                    columns: [{
                            data: 'Years'
                        },
                        {
                            data: 'Months',
                            render: function(data) {
                                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                                ];
                                return months[data - 1] || data;
                            }
                        },
                        {
                            data: 'Dept'
                        },
                        {
                            data: 'Plan',
                            render: {
                                display: function(data) {
                                    return formatNumber(data);
                                },
                                sort: function(data) {
                                    return parseFloat(data);
                                }
                            }
                        },
                        {
                            data: 'Actual',
                            render: {
                                display: function(data) {
                                    return formatNumber(data);
                                },
                                sort: function(data) {
                                    return parseFloat(data);
                                }
                            }
                        },
                    ],

                });
            }

            function detail_table_deptAccum() {
                var selectYearMonth = $('#dept_month_accum').val();

                if ($.fn.DataTable.isDataTable('#table_dept_accum')) {
                    $('#table_dept_accum').DataTable().destroy();
                }

                $('#table_dept_accum').DataTable({
                    destroy: true,
                    processing: true,
                    responsive: false,
                    scrollX: true,
                    paging: true,
                    searching: true,
                    lengthChange: true,
                    language: {
                        'processing': '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
                    },
                    info: false,
                    columnDefs: [{
                        orderable: false,
                        targets: 0
                    }],
                    ajax: {
                        url: "/api/finance/get_transaction_deptAccum_table",
                        type: "POST",
                        contentType: "application/json",
                        data: function(d) {
                            return JSON.stringify({
                                yearMonth: selectYearMonth
                            });
                        }
                    },
                    columns: [{
                            data: 'Years'
                        },
                        {
                            data: 'Months',
                            render: function(data) {
                                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                                ];
                                return months[data - 1] || data;
                            }
                        },
                        {
                            data: 'Dept'
                        },
                        {
                            data: 'Plan',
                            render: {
                                display: function(data) {
                                    return formatNumber(data);
                                },
                                sort: function(data) {
                                    return parseFloat(data);
                                }
                            }
                        },
                        {
                            data: 'Actual',
                            render: {
                                display: function(data) {
                                    return formatNumber(data);
                                },
                                sort: function(data) {
                                    return parseFloat(data);
                                }
                            }
                        },
                    ],
                });
            }

            $(document).ready(function() {
                $('#current_year').on('change', function() {
                    detail_table_summary();
                    updateAccumChart();
                });

                $('#pie_years').on('change', function() {
                    updatePieChart();
                });

                $('#transaction_year').on('change', function() {
                    detail_table_transaction();
                    updateEffectChart();
                });

                detail_table_transaction();

                $('#categoryEff_year').on('change', function() {
                    detail_table_category();
                    updateCategoryChart();
                });

                detail_table_category();

                $('#categoryAccum_year').on('change', function() {
                    detail_table_categoryAccum();
                    updateCatAccumChart();
                });

                detail_table_categoryAccum();

                $('#effectAccum_year').on('change', function() {
                    detail_table_effectAccum();
                    updateEffAccumChart();
                });

                detail_table_effectAccum();

                $('#summary_year').on('change', function() {
                    detail_table_summaryMonth();
                    updateSummaryChart();
                });

                detail_table_summaryMonth();

                $('#activity_month').on('change', function() {
                    detail_table_activityMonth();
                    updateActivity();
                });

                $('#category_select').on('change', function() {
                    detail_table_activityMonth();
                    updateActivity();
                });

                $('#sort_select').on('change', function() {
                    detail_table_activityMonth();
                    updateActivity();
                });

                detail_table_activityMonth();

                $('#dept_month').on('change', function() {
                    detail_table_deptSummary();
                    updateDept();
                });

                detail_table_deptAccum();
                $('#dept_month_accum').on('change', function() {
                    detail_table_deptAccum();
                    updateDeptAccum();
                });

                detail_table_deptAccum();
            });
        });

        var currentHost = window.location.host;

        function fetchData(url, callback) {
            axios.get(url)
                .then(response => callback(response.data))
                .catch(error => console.error(`Error fetching ${url}:`, error));
        }

        function updateAccumChart() {
            const yearX = document.getElementById('current_year').value;
            fetchData(`https://${currentHost}/api/finance/get_RCDPerMonthAccum/${yearX}`, data => {
                document.getElementById('inpt_val_plan_accum').value = (data.data_plan_accum || []).join(',');
                document.getElementById('inpt_val_actual_accum').value = (data.data_actual_accum || []).join(',');

                document.getElementById('btn_update_chart').click();
            });

            setTimeout(() => {
                document.getElementById('btn_update_chart').click();
            }, 200);
        }

        function updatePieChart() {
            const categoryYears = document.getElementById('pie_years').value;
            fetchData(`https://${currentHost}/api/finance/get_RCDPerYearsCategory/${categoryYears}`, data => {
                document.getElementById('inpt_val_plan_cat').value = (data.data_plan_cat || []).join(',');
                document.getElementById('inpt_val_actual_cat').value = (data.data_actual_cat || []).join(',');
                document.getElementById('btn_update_cat').click();
            });

            setTimeout(() => {
                document.getElementById('btn_update_chart').click();
            }, 200);
        }

        function updateEffectChart() {
            const transactionEffect = document.getElementById('transaction_year').value;
            fetchData(`https://${currentHost}/api/finance/get_transaction_effect/${transactionEffect}`, data => {
                document.getElementById('inpt_val_plan_effect').value = (data.data_plan_effect || []).join(',');
                document.getElementById('inpt_val_actual_effect').value = (data.data_actual_effect || []).join(',');
                document.getElementById('inpt_val_category_effect').value = data.categories;
                document.getElementById('btn_update_effect').click();
            });
        }

        function updateCategoryChart() {
            const transactionCategory = document.getElementById('categoryEff_year').value;
            fetchData(`https://${currentHost}/api/finance/get_transaction_Category/${transactionCategory}`, data => {
                document.getElementById('inpt_val_plan_trancategory').value = (data.data_plan_Category || []).join(
                    ',');
                document.getElementById('inpt_val_actual_trancategory').value = (data.data_actual_Category || [])
                    .join(',');
                document.getElementById('inpt_val_category_trancategory').value = data.categoriesTransaction;
                document.getElementById('btn_update_trancategory').click();
            });
        }

        function updateCatAccumChart() {
            const selectMonthAccum = document.getElementById('categoryAccum_year').value;
            fetchData(`https://${currentHost}/api/finance/get_transaction_CategoryAccum/${selectMonthAccum}`, data => {
                document.getElementById('inpt_val_plan_categoryAccum').value = data.data_plan_categoryAccum;
                document.getElementById('inpt_val_actual_categoryAccum').value = data.data_actual_categoryAccum;
                document.getElementById('inpt_val_category_categoryAccum').value = data.categories;

                document.getElementById('btn_update_categoryAccum').click();
            });
        }

        function updateEffAccumChart() {
            const selectMonthEffect = document.getElementById('effectAccum_year').value;
            fetchData(`https://${currentHost}/api/finance/get_transaction_EffectAccum/${selectMonthEffect}`, data => {
                document.getElementById('inpt_val_plan_effectAccum').value = data.data_plan_effectAccum;
                document.getElementById('inpt_val_actual_effectAccum').value = data.data_actual_effectAccum;
                document.getElementById('inpt_val_category_effectAccum').value = data.categoriesEffect;
                document.getElementById('btn_update_effectAccum').click();
            });
        }

        function updateSummaryChart() {
            const selectSummaryMonthly = document.getElementById('summary_year').value;
            fetchData(`https://${currentHost}/api/finance/get_transaction_summaryMonth/${selectSummaryMonthly}`, data => {
                document.getElementById('inpt_sumarryPlan_month').value = data.data_plan_summary.join(',');
                document.getElementById('inpt_sumarryActual_month').value = data.data_actual_summary.join(',');
                document.getElementById('update_summary_month').click();
            });
        }

        function updateActivity() {
            const selectYears = document.getElementById('category_select').value;
            const selectSort = document.getElementById('sort_select').value;
            const selectCategory = document.getElementById('activity_month').value;
            fetchData(
                `https://${currentHost}/api/finance/get_transaction_activity/${selectYears}~${selectCategory}~${selectSort}`,
                data => {
                    document.getElementById('inpt_activity_plan').value = data.data_plan_activity;
                    document.getElementById('inpt_activity_actual').value = data.data_actual_activity;
                    document.getElementById('inpt_activity_category').value = data.data_category_activity;
                    document.getElementById('update_activity').click();
                });
        }

        function updateDept() {
            const selectDeptMonth = document.getElementById('dept_month').value;
            fetchData(`https://${currentHost}/api/finance/get_transaction_dept/${selectDeptMonth}`, data => {
                document.getElementById('inpt_val_plan_dept').value = data.data_plan_dept;
                document.getElementById('inpt_val_actual_dept').value = data.data_actual_dept;
                document.getElementById('inpt_val_category_dept').value = data.data_category_dept;
                document.getElementById('update_dept').click();
            });
        }

        function updateDeptAccum() {
            const selectDeptMonth = document.getElementById('dept_month_accum').value;
            fetchData(`https://${currentHost}/api/finance/get_transaction_deptAccum/${selectDeptMonth}`, data => {
                document.getElementById('inpt_val_plan_deptAccum').value = data.data_plan_deptAccum;
                document.getElementById('inpt_val_actual_deptAccum').value = data.data_actual_deptAccum;
                document.getElementById('inpt_val_category_deptAccum').value = data.data_category_deptAccum;
                document.getElementById('update_dept_accum').click();
            });
        }

        document.addEventListener("alpine:init", () => {
            Alpine.data("analytics", () => ({
                data: {
                    analytics: "Initial Data"
                },
                formatNumber(value) {
                    return value;
                },

                ChartRecall1(dataPlan, dataActual, dataPersentase) {
                    const plan = dataPlan.split(',').map(Number);
                    const actual = dataActual.split(',').map(Number);
                    const persentase = dataPersentase.split(',').map(Number);

                    this.BoxChart2.updateSeries([{
                            name: 'Plan',
                            type: 'bar',
                            data: plan
                        },
                        {
                            name: 'Actual',
                            type: 'bar',
                            data: actual
                        },
                        {
                            name: 'Percent',
                            type: 'line',
                            data: persentase
                        },
                    ]);
                    this.BoxChart2.updateOptions({
                        dataLabels: {
                            offsetY: -10,
                            enabled: true,
                            formatter: (value, {
                                seriesIndex
                            }) => {
                                if (seriesIndex === 2) {
                                    return `${value.toFixed(0)}%`;
                                } else {
                                    if (value >= 1e9) return "Rp. " + (value / 1e9).toFixed(
                                        2) + "B";
                                    if (value >= 1e6) return "Rp. " + (value / 1e6).toFixed(
                                        2) + "M";
                                    if (value >= 1e3) return "Rp. " + (value / 1e3).toFixed(
                                        2) + "K";
                                    return "Rp. " + value.toLocaleString();
                                }
                            }
                        }
                    });
                },

                ChartRecall2(planCategory, actualCategory) {
                    const actual = actualCategory.split(',').map(Number);
                    this.BoxChart3.updateSeries(actual);
                    this.BoxChart3.updateOptions({
                        labels: [
                            'Cash-Budgeted',
                            'Cash-Unbudgeted',
                            'Other Idea - Cash Budgeted',
                            'Other Idea - Cash Unbudgeted',
                            'Potential Cash'
                        ]

                    });
                },

                ChartRecall3(dataPlanAccum, dataActualAccum) {
                    const accumPlan = dataPlanAccum.split(',').map(Number);
                    const accumActual = dataActualAccum.split(',').map(Number);

                    const maxPlan = Math.max(...accumPlan);

                    this.BoxChart5.updateSeries([{
                            name: 'Plan Accumulative',
                            data: accumPlan,
                        },
                        {
                            name: 'Actual Accumulative',
                            data: accumActual,
                        }
                    ]);

                    this.BoxChart5.updateOptions({
                        chart: {
                            toolbar: {
                                show: false
                            }
                        },
                        yaxis: [{
                                seriesName: 'Plan Accumulative',
                                max: maxPlan,
                                tickAmount: 5,
                                labels: {
                                    formatter: function(value) {
                                        if (value >= 1e9) return "Rp. " + (value / 1e9)
                                            .toFixed(0) + "B";
                                        if (value >= 1e6) return "Rp. " + (value / 1e6)
                                            .toFixed(0) + "M";
                                        if (value >= 1e3) return "Rp. " + (value / 1e3)
                                            .toFixed(0) + "K";
                                        return "Rp. " + value.toLocaleString('id-ID');
                                    },
                                    style: {
                                        colors: '#333'
                                    }
                                },
                            },
                            {
                                opposite: true,
                                labels: {
                                    formatter: function(value) {
                                        return (value / maxPlan * 100).toFixed(0) + '%';
                                    },
                                    style: {
                                        colors: '#008FFB'
                                    }
                                },
                                title: {
                                    style: {
                                        color: '#008FFB'
                                    }
                                },
                                min: 0,
                                tickAmount: 5,
                                max: maxPlan,
                            }
                        ],
                        grid: {
                            padding: {
                                top: 40
                            },
                            borderColor: '#89898930',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        markers: {
                            size: 2,
                            shape: "circle",
                        },
                    });
                },

                ChartRecall4(planEffectData, actualEffectData, categoryEffectData) {
                    const planEffect = planEffectData.split(',').map(Number);
                    const actualEffect = actualEffectData.split(',').map(Number);
                    const categories = categoryEffectData.split(',');

                    this.BoxChart6.updateSeries([{
                            name: 'Plan',
                            data: planEffect
                        },
                        {
                            name: 'Actual',
                            data: actualEffect
                        },
                    ]);

                    this.BoxChart6.updateOptions({
                        xaxis: {
                            categories: categories,
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
                        },
                    });

                    this.BoxChart6.updateOptions({
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '11px',
                                colors: ['#2A9D8F', '#008ffb'],
                                fontWeight: 'bold',
                            },
                            background: {
                                enabled: true,
                                foreColor: '#fff',
                                borderRadius: 4,
                                padding: 5,
                                opacity: 0.9,
                            },
                            formatter: (value) => {
                                if (value >= 1e9) return (value / 1e9).toFixed(2) + " B";
                                if (value >= 1e6) return (value / 1e6).toFixed(2) + " M";
                                if (value >= 1e3) return (value / 1e3).toFixed(2) + " K";
                                return value;
                            }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                borderRadius: 10,
                                borderRadiusApplication: 'end',
                                dataLabels: {
                                    position: 'top'
                                },
                            }
                        }
                    });
                },

                ChartRecall5(planEffectData, actualEffectData, categoryEffectData) {
                    const planEffect = planEffectData.split(',').map(Number);
                    const actualEffect = actualEffectData.split(',').map(Number);
                    const categories = categoryEffectData.split(',');


                    this.BoxChart7.updateOptions({
                        xaxis: {
                            categories: categories,
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
                        },
                    });

                    this.BoxChart7.updateSeries([{
                            name: 'Plan',
                            data: planEffect
                        },
                        {
                            name: 'Actual',
                            data: actualEffect
                        },
                    ]);

                    this.BoxChart7.updateOptions({
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '11px',
                                colors: ['#2A9D8F', '#008ffb'],
                                fontWeight: 'bold',
                            },
                            background: {
                                enabled: true,
                                foreColor: '#fff',
                                borderRadius: 4,
                                padding: 5,
                                opacity: 0.9,
                            },
                            formatter: (value) => {
                                if (value >= 1e9) return (value / 1e9).toFixed(2) + " B";
                                if (value >= 1e6) return (value / 1e6).toFixed(2) + " M";
                                if (value >= 1e3) return (value / 1e3).toFixed(2) + " K";
                                return value;
                            }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                borderRadius: 10,
                                borderRadiusApplication: 'end',
                                dataLabels: {
                                    position: 'top'
                                },
                            }
                        }
                    });
                },

                ChartRecall6(planAccumulative, actualAcumulative, categoryAccumulative) {
                    const planAccum = planAccumulative.split(',').map(Number);
                    const actualAccum = actualAcumulative.split(',').map(Number);
                    const categories = categoryAccumulative.split(',');

                    this.BoxChart8.updateOptions({
                        xaxis: {
                            categories: categories,
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
                        },
                    });

                    this.BoxChart8.updateSeries([{
                            name: 'Plan',
                            data: planAccum
                        },
                        {
                            name: 'Actual',
                            data: actualAccum
                        },
                    ]);

                    this.BoxChart8.updateOptions({
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontWeight: 'bold',
                                fontSize: '11px',
                                colors: ['#2A9D8F', '#008ffb'],
                            },
                            background: {
                                enabled: true,
                                foreColor: '#fff',
                                borderRadius: 4,
                                padding: 5,
                                opacity: 0.9,
                            },
                            formatter: (value) => {
                                if (value >= 1e9) return (value / 1e9).toFixed(2) + " B";
                                if (value >= 1e6) return (value / 1e6).toFixed(2) + " M";
                                if (value >= 1e3) return (value / 1e3).toFixed(2) + " K";
                                return value;
                            }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                borderRadius: 10,
                                borderRadiusApplication: 'end',
                                dataLabels: {
                                    position: 'top'
                                },
                            }
                        }
                    });
                },

                ChartRecall7(planAccumulative, actualAcumulative, categoryAccumulative) {
                    const planAccum = planAccumulative.split(',').map(Number);
                    const actualAccum = actualAcumulative.split(',').map(Number);
                    const categories = categoryAccumulative.split(',');

                    this.BoxChart9.updateOptions({
                        xaxis: {
                            categories: categories,
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
                        },
                    });

                    this.BoxChart9.updateSeries([{
                            name: 'Plan',
                            data: planAccum
                        },
                        {
                            name: 'Actual',
                            data: actualAccum
                        },
                    ]);

                    this.BoxChart9.updateOptions({
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '11px',
                                colors: ['#2A9D8F', '#008ffb'],
                                fontWeight: 'bold',
                            },
                            background: {
                                enabled: true,
                                foreColor: '#fff',
                                borderRadius: 4,
                                padding: 5,
                                opacity: 0.9,
                            },
                            formatter: (value) => {
                                if (value >= 1e9) return (value / 1e9).toFixed(2) + " B";
                                if (value >= 1e6) return (value / 1e6).toFixed(2) + " M";
                                if (value >= 1e3) return (value / 1e3).toFixed(2) + " K";
                                return value;
                            }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                borderRadius: 10,
                                borderRadiusApplication: 'end',
                                dataLabels: {
                                    position: 'top'
                                },
                            }
                        }
                    });
                },

                ChartRecall8(planSummary, actualSummary) {
                    const planSumm = planSummary.split(',').map(Number);
                    const actualSumm = actualSummary.split(',').map(Number);

                    this.BoxChart10.updateSeries([{
                            name: 'Plan',
                            data: planSumm
                        },
                        {
                            name: 'Actual',
                            data: actualSumm
                        },
                    ]);

                    this.BoxChart10.updateOptions({
                        chart: {
                            events: {
                                dataPointSelection: (event, chartContext, config) => {
                                    const monthIndex = config.dataPointIndex;
                                    const selectedYear = document.getElementById(
                                        'summary_year').value;
                                    const selectedMonth = (monthIndex + 1).toString()
                                        .padStart(2, '0');
                                    const selectedDate = `${selectedYear}-${selectedMonth}`;

                                    const monthInputs = [
                                        'transaction_year',
                                        'categoryEff_year',
                                        'categoryAccum_year',
                                        'effectAccum_year',
                                        'activity_month',
                                        'dept_month',
                                        'dept_month_accum'
                                    ];

                                    monthInputs.forEach(inputId => {
                                        const input = document.getElementById(
                                            inputId);
                                        if (input) {
                                            input.value = selectedDate;
                                            input.dispatchEvent(new Event(
                                                'change'));
                                        }
                                    });
                                }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '11px',
                                colors: ['#2A9D8F', '#008ffb'],
                                fontWeight: 'bold',
                            },
                            background: {
                                enabled: true,
                                foreColor: '#fff',
                                borderRadius: 4,
                                padding: 5,
                                opacity: 0.9,
                            },
                            formatter: (value) => {
                                if (value >= 1e9) return (value / 1e9).toFixed(2) + " B";
                                if (value >= 1e6) return (value / 1e6).toFixed(2) + " M";
                                if (value >= 1e3) return (value / 1e3).toFixed(2) + " K";
                                return value;
                            }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                borderRadius: 10,
                                borderRadiusApplication: 'end'
                            },
                        },
                    });
                },
                ChartRecall9(planSummary, actualSummary, categorySummary) {
                    const planSumm = planSummary.split(',').map(Number);
                    const actualSumm = actualSummary.split(',').map(Number);
                    const categoriesSumm = categorySummary.split(',');

                    this.BoxChart11.updateOptions({
                        xaxis: {
                            categories: categoriesSumm,
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
                        },
                    });


                    this.BoxChart11.updateSeries([{
                            name: 'Plan',
                            data: planSumm
                        },
                        {
                            name: 'Actual',
                            data: actualSumm
                        },
                    ]);

                    this.BoxChart11.updateOptions({
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '11px',
                                colors: ['#2A9D8F', '#008ffb'],
                            },
                            background: {
                                enabled: true,
                                foreColor: '#fff',
                                borderRadius: 4,
                                padding: 5,
                                opacity: 0.9,
                            },
                            formatter: (value) => {
                                if (value >= 1e9) return (value / 1e9).toFixed(2) + " B";
                                if (value >= 1e6) return (value / 1e6).toFixed(2) + " M";
                                if (value >= 1e3) return (value / 1e3).toFixed(2) + " K";
                                return value;
                            }
                        },

                        plotOptions: {
                            bar: {
                                horizontal: true,
                                columnWidth: '40%',
                                borderRadius: 5,
                                borderRadiusApplication: 'end',
                                dataLabels: {
                                    position: 'top'

                                },
                            }
                        }
                    });
                },
                ChartRecall10(deptPlan, deptActual, deptCategory) {
                    const planDept = deptPlan.split(',').map(Number);
                    const actualDept = deptActual.split(',').map(Number);
                    const categoriesDept = deptCategory.split(',');
                    this.BoxChart12.updateOptions({
                        xaxis: {
                            categories: categoriesDept,
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
                        },
                    });
                    this.BoxChart12.updateSeries([{
                            name: 'Plan',
                            data: planDept
                        },
                        {
                            name: 'Actual',
                            data: actualDept
                        },
                    ]);

                    this.BoxChart12.updateOptions({
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '11px',
                                colors: ['#2A9D8F', '#008ffb'],
                                fontWeight: 'bold',
                            },
                            background: {
                                enabled: true,
                                foreColor: '#fff',
                                borderRadius: 4,
                                padding: 5,
                                opacity: 0.9,
                            },
                            formatter: (value) => {
                                if (value >= 1e9) return (value / 1e9).toFixed(2) + " B";
                                if (value >= 1e6) return (value / 1e6).toFixed(2) + " M";
                                if (value >= 1e3) return (value / 1e3).toFixed(2) + " K";
                                return value;
                            }
                        },
                        plotOptions: {
                            horizontal: false,
                            borderRadius: 10,
                            borderRadiusApplication: 'end',
                            bar: {
                                dataLabels: {
                                    position: 'top'
                                },
                            }
                        }
                    });
                },

                ChartRecall11(deptPlanAccum, deptActualAccum, deptCategoryAccum) {
                    const planDeptAccum = deptPlanAccum.split(',').map(Number);
                    const actualDeptAccum = deptActualAccum.split(',').map(Number);
                    const categoriesDeptAccum = deptCategoryAccum.split(',');
                    this.BoxChart13.updateOptions({
                        xaxis: {
                            categories: categoriesDeptAccum,
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
                        },
                    });
                    this.BoxChart13.updateSeries([{
                            name: 'Plan',
                            data: planDeptAccum
                        },
                        {
                            name: 'Actual',
                            data: actualDeptAccum
                        },
                    ]);

                    this.BoxChart13.updateOptions({
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '11px',
                                colors: ['#2A9D8F', '#008ffb'],
                                fontWeight: 'bold',
                            },
                            background: {
                                enabled: true,
                                foreColor: '#fff',
                                borderRadius: 4,
                                padding: 5,
                                opacity: 0.9,
                            },
                            formatter: (value) => {
                                if (value >= 1e9) return (value / 1e9).toFixed(2) + " B";
                                if (value >= 1e6) return (value / 1e6).toFixed(2) + " M";
                                if (value >= 1e3) return (value / 1e3).toFixed(2) + " K";
                                return value;
                            }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                borderRadius: 10,
                                borderRadiusApplication: 'end',
                                dataLabels: {
                                    position: 'top'
                                },
                            }
                        }
                    });
                },

                renderCharts() {
                    this.BoxChart2 = new ApexCharts(this.$refs.BoxChart2, this.BoxChart2Options);
                    this.BoxChart2.render();

                    this.BoxChart3 = new ApexCharts(this.$refs.BoxChart3, this.BoxChart3Options);
                    this.BoxChart3.render();

                    this.BoxChart5 = new ApexCharts(this.$refs.BoxChart5, this.BoxChart5Options);
                    this.BoxChart5.render();

                    this.BoxChart6 = new ApexCharts(this.$refs.BoxChart6, this.BoxChart6Options);
                    this.BoxChart6.render();

                    this.BoxChart7 = new ApexCharts(this.$refs.BoxChart7, this.BoxChart7Options);
                    this.BoxChart7.render();

                    this.BoxChart8 = new ApexCharts(this.$refs.BoxChart8, this.BoxChart8Options);
                    this.BoxChart8.render();

                    this.BoxChart9 = new ApexCharts(this.$refs.BoxChart9, this.BoxChart9Options);
                    this.BoxChart9.render();

                    this.BoxChart10 = new ApexCharts(this.$refs.BoxChart10, this.BoxChart10Options);
                    this.BoxChart10.render();

                    this.BoxChart11 = new ApexCharts(this.$refs.BoxChart11, this.BoxChart11Options);
                    this.BoxChart11.render();

                    this.BoxChart12 = new ApexCharts(this.$refs.BoxChart12, this.BoxChart12Options);
                    this.BoxChart12.render();

                    this.BoxChart13 = new ApexCharts(this.$refs.BoxChart13, this.BoxChart13Options);
                    this.BoxChart13.render();

                },

                get BoxChart2Options() {
                    return {
                        series: [],
                        chart: {
                            height: 270,
                            type: 'line',
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                            animations: {
                                enabled: true,
                                easing: 'easeinout',
                                speed: 200
                            }
                        },
                        stroke: {
                            width: [0, 0, 2],
                            curve: 'smooth'
                        },
                        colors: ['#2A9D8F', '#008ffb', '#F4A261'],
                        dropShadow: {
                            enabled: true,
                            blur: 3,
                            color: '#515365',
                            opacity: 0.2
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
                            borderColor: '#89898930',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: ['2025', '2026'],
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
                        },



                        yaxis: [{
                                seriesName: 'Plan',
                                labels: {
                                    formatter: (value) => {
                                        if (value >= 1e9) return "Rp. " + (value / 1e9)
                                            .toFixed(0) + "B";
                                        if (value >= 1e6) return "Rp. " + (value / 1e6)
                                            .toFixed(0) + "M";
                                        if (value >= 1e3) return "Rp. " + (value / 1e3)
                                            .toFixed(0) + "K";
                                        return "Rp. " + value.toLocaleString();
                                    }
                                },
                            },
                            {
                                seriesName: 'Plan',
                                show: false
                            },
                            {
                                opposite: true,
                                seriesName: 'Percent',
                                max: 100,
                                tickAmount: 4,
                                labels: {
                                    formatter: (value) => Math.round(value) + "%"
                                }
                            },
                        ],
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value, {
                                    seriesIndex
                                }) => {
                                    if (seriesIndex === 2) {
                                        return value.toFixed(1) + "%";
                                    }
                                    return "Rp. " + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        fill: {
                            type: ['solid', 'solid', 'solid'],
                            gradient: {
                                shade: 'dark',
                                type: 'vertical',
                                shadeIntensity: 0.3,
                                inverseColors: false,
                                opacityFrom: 1,
                                opacityTo: 0.8,
                                stops: [0, 100, 0]
                            }
                        }
                    };
                },

                get BoxChart3Options() {
                    return {
                        series: [0, 0, 0, 0],
                        chart: {
                            type: 'pie',
                            height: 400,
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                            offsetY: 10
                        },
                        labels: [
                            'Cash-Budgeted',
                            'Cash-Unbudgeted',
                            'Other Idea - Cash Unbudgeted',
                            'Potential Cash'
                        ],
                        stroke: {
                            show: false
                        },
                        dropShadow: {
                            enabled: true,
                            blur: 3,
                            color: '#515365',
                            opacity: 0.5
                        },
                        colors: ['#F4A261', '#008FFB', '#00E396', '#775DD0', '#FF4560', '#FEB019'],
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            itemMargin: {
                                horizontal: 8,
                                vertical: 8
                            }
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return "Rp. " + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '16px',
                            },
                            formatter: (value) => `${Math.round(value)}%`
                        },
                    };
                },

                get BoxChart5Options() {
                    return {
                        series: [{
                                name: 'Plan Accumulative',
                                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                                type: 'line'
                            },
                            {
                                name: 'Actual Accumulative',
                                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                                type: 'bar'
                            },

                        ],
                        chart: {
                            height: 400,
                            type: 'line',
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                            animations: {
                                enabled: true,
                                easing: 'easeinout',
                                speed: 800
                            }
                        },
                        stroke: {
                            width: [2, 2],
                            curve: 'smooth'
                        },
                        colors: ['#2A9D8F', '#008ffb'],
                        dropShadow: {
                            enabled: true,
                            blur: 3,
                            color: '#515365',
                            opacity: 0.2
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
                            borderColor: '#89898930',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug',
                                'Sep', 'Oct', 'Nov', 'Dec'
                            ],
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
                        },
                        tooltip: {
                            y: {
                                formatter: (value, {
                                    seriesIndex
                                }) => {
                                    if (seriesIndex === 2) {
                                        return value.toFixed(1) + "%";
                                    }
                                    return "Rp. " + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        fill: {
                            type: ['solid', 'solid', 'solid'],
                            gradient: {
                                shade: 'dark',
                                type: 'vertical',
                                shadeIntensity: 0.3,
                                inverseColors: false,
                                opacityFrom: 1,
                                opacityTo: 0.8,
                                stops: [0, 100]
                            }
                        }
                    };
                },

                get BoxChart6Options() {
                    return {
                        series: [{
                                name: 'Plan',
                                data: [],
                            },
                            {
                                name: 'Actual',
                                data: [],
                            },
                        ],
                        chart: {
                            height: 400,
                            type: 'bar',
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                        },
                        stroke: {
                            width: [0, 0],
                            curve: 'smooth'
                        },
                        colors: ['#2A9D8F', '#008ffb'],
                        dropShadow: {
                            enabled: true,
                            blur: 2,
                            color: '#515365',
                            opacity: 0.2
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
                            borderColor: '#89898930',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: [],
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
                        },
                        yaxis: {
                            labels: {
                                formatter: (value) => {
                                    if (value >= 1e9) return "Rp. " + (value / 1e9).toFixed(0) +
                                        "B";
                                    if (value >= 1e6) return "Rp. " + (value / 1e6).toFixed(0) +
                                        "M";
                                    if (value >= 1e3) return "Rp. " + (value / 1e3).toFixed(0) +
                                        "K";
                                    return "Rp. " + value.toLocaleString();
                                }
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return "Rp. " + value.toLocaleString('id-ID');
                                }
                            },
                        },
                        fill: {
                            type: ['solid', 'solid'],
                            gradient: {
                                shade: 'dark',
                                type: 'vertical',
                                shadeIntensity: 0.3,
                                inverseColors: false,
                                opacityFrom: 1,
                                opacityTo: 0.8,
                                stops: [0, 100]
                            }
                        }
                    };
                },

                get BoxChart7Options() {
                    return {
                        series: [{
                                name: 'Plan',
                                data: [],
                            },
                            {
                                name: 'Actual',
                                data: [],
                            },
                        ],
                        chart: {
                            height: 400,
                            type: 'bar',
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                        },
                        stroke: {
                            width: [0, 0],
                            curve: 'smooth'
                        },
                        colors: ['#2A9D8F', '#008ffb'],
                        dropShadow: {
                            enabled: true,
                            blur: 2,
                            color: '#515365',
                            opacity: 0.2
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
                            borderColor: '#89898930',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: [],
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
                        },
                        yaxis: {
                            labels: {
                                formatter: (value) => {
                                    if (value >= 1e9) return "Rp. " + (value / 1e9).toFixed(0) +
                                        "B";
                                    if (value >= 1e6) return "Rp. " + (value / 1e6).toFixed(0) +
                                        "M";
                                    if (value >= 1e3) return "Rp. " + (value / 1e3).toFixed(0) +
                                        "K";
                                    return "Rp. " + value.toLocaleString();
                                }
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return "Rp. " + value.toLocaleString('id-ID');
                                }
                            },
                        },
                        fill: {
                            type: ['solid', 'solid'],
                            gradient: {
                                shade: 'dark',
                                type: 'vertical',
                                shadeIntensity: 0.3,
                                inverseColors: false,
                                opacityFrom: 1,
                                opacityTo: 0.8,
                                stops: [0, 100]
                            }
                        }
                    };
                },

                get BoxChart8Options() {
                    return {
                        series: [{
                                name: 'Plan',
                                data: [],
                            },
                            {
                                name: 'Actual',
                                data: [],
                            },
                        ],
                        chart: {
                            height: 400,
                            type: 'bar',
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                        },
                        stroke: {
                            width: [0, 0],
                            curve: 'smooth'
                        },
                        colors: ['#2A9D8F', '#008ffb'],
                        dropShadow: {
                            enabled: true,
                            blur: 2,
                            color: '#515365',
                            opacity: 0.2
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
                            borderColor: '#89898930',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: [],
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
                        },
                        yaxis: {
                            labels: {
                                formatter: (value) => {
                                    if (value >= 1e9) return "Rp. " + (value / 1e9).toFixed(0) +
                                        "B";
                                    if (value >= 1e6) return "Rp. " + (value / 1e6).toFixed(0) +
                                        "M";
                                    if (value >= 1e3) return "Rp. " + (value / 1e3).toFixed(0) +
                                        "K";
                                    return "Rp. " + value.toLocaleString();
                                }
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return "Rp. " + value.toLocaleString('id-ID');
                                }
                            },
                        },
                        fill: {
                            type: ['solid', 'solid'],
                            gradient: {
                                shade: 'dark',
                                type: 'vertical',
                                shadeIntensity: 0.3,
                                inverseColors: false,
                                opacityFrom: 1,
                                opacityTo: 0.8,
                                stops: [0, 100]
                            }
                        }
                    };
                },

                get BoxChart9Options() {
                    return {
                        series: [{
                                name: 'Plan',
                                data: [],
                            },
                            {
                                name: 'Actual',
                                data: [],
                            },
                        ],
                        chart: {
                            height: 400,
                            type: 'bar',
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                        },
                        stroke: {
                            width: [0, 0],
                            curve: 'smooth'
                        },
                        colors: ['#2A9D8F', '#008ffb'],
                        dropShadow: {
                            enabled: true,
                            blur: 2,
                            color: '#515365',
                            opacity: 0.2
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
                            borderColor: '#89898930',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: [],
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
                        },
                        yaxis: {
                            labels: {
                                formatter: (value) => {
                                    if (value >= 1e9) return "Rp. " + (value / 1e9).toFixed(0) +
                                        "B";
                                    if (value >= 1e6) return "Rp. " + (value / 1e6).toFixed(0) +
                                        "M";
                                    if (value >= 1e3) return "Rp. " + (value / 1e3).toFixed(0) +
                                        "K";
                                    return "Rp. " + value.toLocaleString();
                                }
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return "Rp. " + value.toLocaleString('id-ID');
                                }
                            },
                        },
                        fill: {
                            type: ['solid', 'solid'],
                            gradient: {
                                shade: 'dark',
                                type: 'vertical',
                                shadeIntensity: 0.3,
                                inverseColors: false,
                                opacityFrom: 1,
                                opacityTo: 0.8,
                                stops: [0, 100]
                            }
                        }
                    };
                },

                get BoxChart10Options() {
                    return {
                        series: [{
                                name: 'Plan',
                                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            },
                            {
                                name: 'Actual',
                                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            },
                        ],
                        chart: {
                            height: 400,
                            type: 'bar',
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                        },
                        stroke: {
                            width: [0, 0],
                            curve: 'smooth'
                        },
                        colors: ['#2A9D8F', '#008ffb'],
                        dropShadow: {
                            enabled: true,
                            blur: 2,
                            color: '#515365',
                            opacity: 0.2
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
                            borderColor: '#89898930',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: ['Jan - Mar', 'Apr - Jun', 'Jul - Sep', 'Oct - Dec'],
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
                        },
                        yaxis: {
                            labels: {
                                formatter: (value) => {
                                    if (value >= 1e9) return "Rp. " + (value / 1e9).toFixed(0) +
                                        "B";
                                    if (value >= 1e6) return "Rp. " + (value / 1e6).toFixed(0) +
                                        "M";
                                    if (value >= 1e3) return "Rp. " + (value / 1e3).toFixed(0) +
                                        "K";
                                    return "Rp. " + value.toLocaleString();
                                }
                            },
                        },
                        tooltip: {

                            y: {
                                formatter: (value) => {
                                    return "Rp. " + value.toLocaleString('id-ID');
                                }
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '12px'
                            },
                            formatter: (value) => {
                                if (value >= 1e9) return (value / 1e9).toFixed(2) + " B";
                                if (value >= 1e6) return (value / 1e6).toFixed(2) + " M";
                                if (value >= 1e3) return (value / 1e3).toFixed(2) + " K";
                                return value;
                            }
                        },
                        plotOptions: {
                            bar: {
                                dataLabels: {
                                    position: 'top',
                                },
                            }
                        },
                        fill: {
                            type: ['solid', 'solid'],
                            gradient: {
                                shade: 'dark',
                                type: 'vertical',
                                shadeIntensity: 0.3,
                                inverseColors: false,
                                opacityFrom: 1,
                                opacityTo: 0.8,
                                stops: [0, 100]
                            }
                        }
                    };
                },

                get BoxChart11Options() {
                    return {
                        series: [{
                                name: 'Plan',
                                data: [],
                            },
                            {
                                name: 'Actual',
                                data: [],
                            },
                        ],
                        chart: {
                            height: 600,
                            type: 'bar',
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                        },
                        stroke: {
                            width: [0, 0],
                            curve: 'smooth'
                        },
                        colors: ['#2A9D8F', '#008ffb'],
                        dropShadow: {
                            enabled: true,
                            blur: 2,
                            color: '#515365',
                            opacity: 0.2
                        },
                        legend: {
                            position: 'right',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            itemMargin: {
                                horizontal: 8,
                                vertical: 8
                            }
                        },
                        grid: {
                            borderColor: '#89898930',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: [],
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: false
                            },
                            labels: {
                                formatter: (value) => {
                                    if (value >= 1e9) return "Rp. " + (value / 1e9).toFixed(0) +
                                        "B";
                                    if (value >= 1e6) return "Rp. " + (value / 1e6).toFixed(0) +
                                        "M";
                                    if (value >= 1e3) return "Rp. " + (value / 1e3).toFixed(0) +
                                        "K";
                                    return "Rp. " + value.toLocaleString();
                                }
                            },
                        },
                        tooltip: {
                            y: {
                                formatter: (value) => {
                                    return "Rp. " + value.toLocaleString('id-ID');
                                }
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            offsetX: -5,
                            style: {
                                fontSize: '9px'
                            },
                        },
                        plotOptions: {
                            bar: {
                                horizontal: true,
                                columnWidth: '75%',
                            }
                        },
                        fill: {
                            type: ['solid', 'solid'],
                            gradient: {
                                shade: 'dark',
                                type: 'vertical',
                                shadeIntensity: 0.3,
                                inverseColors: false,
                                opacityFrom: 1,
                                opacityTo: 0.8,
                                stops: [0, 100]
                            }
                        }
                    };
                },

                get BoxChart12Options() {
                    return {
                        series: [{
                                name: 'Plan',
                                data: [],
                            },
                            {
                                name: 'Actual',
                                data: [],
                            },
                        ],
                        chart: {
                            height: 400,
                            type: 'bar',
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                        },
                        colors: ['#2A9D8F', '#008ffb'],
                        dropShadow: {
                            enabled: true,
                            blur: 2,
                            color: '#515365',
                            opacity: 0.2
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
                            borderColor: '#89898930',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: [],
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
                        },
                        yaxis: {
                            labels: {
                                formatter: (value) => {
                                    if (value >= 1e9) return "Rp. " + (value / 1e9).toFixed(0) +
                                        "B";
                                    if (value >= 1e6) return "Rp. " + (value / 1e6).toFixed(0) +
                                        "M";
                                    if (value >= 1e3) return "Rp. " + (value / 1e3).toFixed(0) +
                                        "K";
                                    return "Rp. " + value.toLocaleString();
                                }
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return "Rp. " + value.toLocaleString('id-ID');
                                }
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            offsetY: -5,
                            style: {
                                fontSize: '10px'
                            },
                            formatter: (value) => {
                                if (value >= 1e9) return (value / 1e9).toFixed(2) + " B";
                                if (value >= 1e6) return (value / 1e6).toFixed(2) + " M";
                                if (value >= 1e3) return (value / 1e3).toFixed(2) + " K";
                                return value;
                            }
                        },
                        plotOptions: {
                            bar: {
                                dataLabels: {
                                    position: 'top'
                                },
                            }
                        },
                        fill: {
                            type: ['solid', 'solid'],
                            gradient: {
                                shade: 'dark',
                                type: 'vertical',
                                shadeIntensity: 0.3,
                                inverseColors: false,
                                opacityFrom: 1,
                                opacityTo: 0.8,
                                stops: [0, 100]
                            }
                        }
                    };
                },

                get BoxChart13Options() {
                    return {
                        series: [{
                                name: 'Plan',
                                data: [],
                            },
                            {
                                name: 'Actual',
                                data: [],
                            },
                        ],
                        chart: {
                            height: 400,
                            type: 'bar',
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: {
                                show: false
                            },
                        },
                        colors: ['#2A9D8F', '#008ffb'],
                        dropShadow: {
                            enabled: true,
                            blur: 2,
                            color: '#515365',
                            opacity: 0.2
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
                            borderColor: '#89898930',
                            padding: {
                                left: 20,
                                right: 20
                            }
                        },
                        xaxis: {
                            categories: [],
                            axisBorder: {
                                show: true,
                                color: '#89898930'
                            },
                            axisTicks: {
                                show: 200
                            }
                        },
                        yaxis: {
                            labels: {
                                formatter: (value) => {
                                    if (value >= 1e9) return "Rp. " + (value / 1e9).toFixed(0) +
                                        "B";
                                    if (value >= 1e6) return "Rp. " + (value / 1e6).toFixed(0) +
                                        "M";
                                    if (value >= 1e3) return "Rp. " + (value / 1e3).toFixed(0) +
                                        "K";
                                    return "Rp. " + value.toLocaleString();
                                }
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: (value) => {
                                    return "Rp. " + value.toLocaleString('id-ID');
                                }
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            offsetY: -5,
                            style: {
                                fontSize: '10px'
                            },
                            formatter: (value) => {
                                if (value >= 1e9) return (value / 1e9).toFixed(2) + " B";
                                if (value >= 1e6) return (value / 1e6).toFixed(2) + " M";
                                if (value >= 1e3) return (value / 1e3).toFixed(2) + " K";
                                return value;
                            }
                        },
                        plotOptions: {
                            bar: {
                                dataLabels: {
                                    position: 'top'
                                },
                            }
                        },
                        fill: {
                            type: ['solid', 'solid'],
                            gradient: {
                                shade: 'dark',
                                type: 'vertical',
                                shadeIntensity: 0.3,
                                inverseColors: false,
                                opacityFrom: 1,
                                opacityTo: 0.8,
                                stops: [0, 100]
                            }
                        }
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
