<x-layout.default>
  <script defer src="/assets/js/apexcharts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
  <style>
    #table_po_year tbody tr:hover,
    #table_po_month tbody tr:hover,
    #table_category_year tbody tr:hover,
    #table_category_month tbody tr:hover,
    #table_category_month_stack tbody tr:hover,
    #table_stack_bymonth tbody tr:hover {
      background-color: #727e93;
      cursor: pointer;
    }

    #table_po_year tbody tr,
    #table_po_month tbody tr,
    #table_category_year tbody tr,
    #table_category_month tbody tr,
    #table_category_month_stack tbody tr,
    #table_stack_bymonth tbody tr {
      background-color: #4B5563;
      color: whitesmoke;
      cursor: pointer;
      font-size: small;
    }

    #table_po_year thead tr,
    #table_po_month thead tr,
    #table_category_year thead tr,
    #table_category_month thead tr,
    #table_category_month_stack thead tr,
    #table_stack_bymonth thead tr {
      color: whitesmoke;
    }

    #categorymonth::-webkit-calendar-picker-indicator,
    #stackbymonth::-webkit-calendar-picker-indicator {
      filter: invert(1);
    }

    #month_year_table::-webkit-calendar-picker-indicator {
      filter: invert(1);
    }

    select[name="table_po_year_length"],
    select[name="table_po_month_length"],
    select[name="table_category_year_length"],
    select[name="table_category_month_length"],
    select[name="table_category_month_stack_length"],
    select[name="table_stack_bymonth_length"] {
      width: 80px;
    }
  </style>

  <div x-data="analytics()">
    <ul class="flex space-x-2 rtl:space-x-reverse">
      <li>
        <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
      </li>
      <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
        <span>Purchasing</span>
      </li>
      <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
        <span>Purchase Report</span>
      </li>
    </ul>
    <div class="pt-5">
      <div class="grid sm:grid-cols-2 xl:grid-cols-12 gap-6 mb-6">
        <?php
$currentYear = date('Y');
$startYear = $currentYear - 5;
$currentMonth = date('Y-m');
        ?>
        <!-- Actual Years -->
        <div class="panel h-full sm:col-span-6 xl:col-span-6">
          <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-3">
              <label for="current_year" class="text-xl font-bold">Actual Year</label>
              <span></span>
              <input id="current_year" type="number" class="form-input" value="<?= $currentYear ?>"
                style="color: white;" hidden />
            </div>
          </div>

          <hr>
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn_update_chart" x-on:click="() => ChartRecall1(inpt_val_po.value, inpt_val_sales.value)"
                hidden>Recall and Update</button>
              <input type="text" id="inpt_val_po" value="" hidden> <input type="text" id="inpt_val_sales" value=""
                hidden>
              <div x-ref="BoxChart1" class="overflow-hidden"></div>
              <div class="relative overflow-hidden pt-5">
                <table id="table_po_year" class="min-w-full rounded-md shadow-md overflow-hidden">
                  <thead>
                    <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                      <th class="py-3 px-6 text-left">Year</th>
                      <td class="py-3 px-6 text-left">PO Amount</td>
                      <td class="py-3 px-6 text-left">Sales Amount</td>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- Actual Months -->
        <div class="panel h-full sm:col-span-6 xl:col-span-6">
          <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-3">
              <label for="actualmonth_year" class="text-xl font-bold">Actual Month</label>
              <span></span>
              <input id="actualmonth_year" type="number" class="form-input" value="<?= $currentYear ?>"
                style="color: white;" />
            </div>
          </div>
          <hr>
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn_update_month"
                x-on:click="() => ChartRecall2(inpt_val_po_month.value, inpt_val_sales_month.value)" hidden>Recall and
                Update</button>
              <input type="text" id="inpt_val_po_month" value="" hidden>
              <input type="text" id="inpt_val_sales_month" value="" hidden>
              <div x-ref="BoxChart2" class="overflow-hidden"></div>
              <div class="relative overflow-hidden pt-5">
                <table id="table_po_month" class="min-w-full rounded-md shadow-md overflow-hidden">
                  <thead>
                    <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                      <th class="py-3 px-6 text-left">Year</th>
                      <td class="py-3 px-6 text-left">Month</td>
                      <td class="py-3 px-6 text-left">PO Amount</td>
                      <td class="py-3 px-6 text-left">Sales Amount</td>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- Category Years -->
        <div class="panel h-full sm:col-span-6 xl:col-span-6">
          <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-3">
              <label for="categoryyears" class="text-xl font-bold">Category Years</label>
              <span></span>
              <input id="categoryyears" type="number" class="form-input" value="<?= $currentYear ?>"
                style="color: white;" />
            </div>
          </div>

          <hr>
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn_update_categoryyear"
                x-on:click="() => ChartRecall3(inpt_val_poactual_category.value, inpt_val_category_category.value)"
                hidden>Recall and Update</button>
              <input type="text" id="inpt_val_poactual_category" value="" hidden>
              <input type="text" id="inpt_val_category_category" value="" hidden>
              <div x-ref="BoxChart3" class="overflow-hidden"></div>
              <div class="relative overflow-hidden pt-5">
                <table id="table_category_year" class="min-w-full rounded-md shadow-md overflow-hidden">
                  <thead>
                    <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                      <th class="py-3 px-6 text-left">Year</th>
                      <td class="py-3 px-6 text-left">Category</td>
                      <td class="py-3 px-6 text-left">PO Amount</td>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- Category Month -->
        <div class="panel h-full sm:col-span-6 xl:col-span-6">
          <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-3">
              <label for="categorymonth" class="text-xl font-bold">Category Month</label>
              <span></span>
              <input id="categorymonth" type="month" class="form-input" value="<?= $currentMonth ?>"
                style="color: white;" />
            </div>
          </div>

          <hr>
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn_update_categorymonth"
                x-on:click="() => ChartRecall4(inpt_val_poactual_categorymonth.value, inpt_val_category_categorymonth.value)"
                hidden>Recall and Update</button>
              <input type="text" id="inpt_val_poactual_categorymonth" value="" hidden>
              <input type="text" id="inpt_val_category_categorymonth" value="" hidden>
              <div x-ref="BoxChart4" class="overflow-hidden"></div>
              <div class="relative overflow-hidden pt-5">
                <table id="table_category_month" class="min-w-full rounded-md shadow-md overflow-hidden">
                  <thead>
                    <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                      <th class="py-3 px-6 text-left">Year</th>
                      <th class="py-3 px-6 text-left">Month</th>
                      <td class="py-3 px-6 text-left">Category</td>
                      <td class="py-3 px-6 text-left">PO Amount</td>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- Stack Category Month -->
        <div class="panel h-full sm:col-span-6 xl:col-span-6">
          <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-3">
              <label for="cetagoryMonthStack" class="text-xl font-bold">Category Month Stack</label>
              <span></span>
              <input id="cetagorymonthstack" type="number" class="form-input" value="<?= $currentYear ?>"
                style="color: white;" />
            </div>
          </div>

          <hr>
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn-update-stack"
                x-on:click="() => ChartRecall5(inpt_val_months_categorymonth_stack.value, inpt_val_category_categorymonth_stack.value, inpt_val_poamount_categorymonth_stack.value)"
                hidden>Recall and Update</button>
              <input type="text" id="inpt_val_months_categorymonth_stack" value="" hidden>
              <input type="text" id="inpt_val_category_categorymonth_stack" value="" hidden>
              <input type="text" id="inpt_val_poamount_categorymonth_stack" value="" hidden>
              <div x-ref="BoxChart5" class="overflow-hidden"></div>
              <div class="relative overflow-hidden pt-5">
                <table id="table_category_month_stack" class="min-w-full rounded-md shadow-md overflow-hidden">
                  <thead>
                    <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                      <th class="py-3 px-6 text-left">Year</th>
                      <th class="py-3 px-6 text-left">Month</th>
                      <td class="py-3 px-6 text-left">Category</td>
                      <td class="py-3 px-6 text-left">PO Amount</td>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- Stack By Month -->
        <div class="panel h-full sm:col-span-6 xl:col-span-6">
          <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-3">
              <label for="stackbymonth" class="text-xl font-bold">Category By Month Stack</label>
              <span></span>
              <input id="stackbymonth" type="month" class="form-input" value="<?= $currentMonth ?>"
                style="color: white;" />
            </div>
          </div>

          <hr>
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn-update-stack-byMonth"
                x-on:click="() => ChartRecall6(inpt_val_months_stack_byMonth.value, inpt_val_category_stack_byMonth.value, inpt_val_poamount_stack_byMonth.value)"
                hidden>Recall and Update</button>
              <input type="text" id="inpt_val_months_stack_byMonth" value="" hidden>
              <input type="text" id="inpt_val_category_stack_byMonth" value="" hidden>
              <input type="text" id="inpt_val_poamount_stack_byMonth" value="" hidden>
              <div x-ref="BoxChart6" class="overflow-hidden"></div>
              <div class="relative overflow-hidden pt-5">
                <table id="table_stack_bymonth" class="min-w-full rounded-md shadow-md overflow-hidden">
                  <thead>
                    <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                      <th class="py-3 px-6 text-left">Year</th>
                      <th class="py-3 px-6 text-left">Month</th>
                      <td class="py-3 px-6 text-left">Category</td>
                      <td class="py-3 px-6 text-left">PO Amount</td>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>

    document.addEventListener("DOMContentLoaded", function () {
      let today = new Date().toISOString().split('T')[0];

      function formatNumber(value) {
        if (value >= 1000000000) {
          return (value / 1000000000).toFixed(0) + ' B';
        } else if (value >= 1000000) {
          return (value / 1000000).toFixed(0) + ' M';
        } else if (value >= 1000) {
          return (value / 1000).toFixed(0) + ' K';
        }
        return value;
      }

      function detail_table_po_month() {
        var selectYearMonth = $('#actualmonth_year').val();

        if ($.fn.DataTable.isDataTable('#table_po_month')) {
          $('#table_po_month').DataTable().destroy();
        }

        $('#table_po_month').DataTable({
          destroy: true,
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
            url: "/api/purchasing/get_purchase_po_month",
            type: "POST",
            contentType: "application/json",
            data: function (d) {
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
            render: function (data) {
              const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
              ];
              return months[data - 1] || data;
            }
          },
          {
            data: 'POAmount',
            render: function (data) {
              return formatNumber(data);
            }
          },
          {
            data: 'SalesAmount',
            render: function (data) {
              return formatNumber(data);
            }
          }
          ],
          createdRow: function (row, data, dataIndex) {
            $(row).css('background-color', dataIndex % 2 === 0 ? '#1D1D1D' : '#2D2D2D');
          }
        });
      }

      function detail_table_po_year() {

        if ($.fn.DataTable.isDataTable('#table_po_year')) {
          $('#table_po_year').DataTable().destroy();
        }

        $('#table_po_year').DataTable({
          destroy: true,
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
            url: "/api/purchasing/get_purchase_po_year",
            type: "POST",
            contentType: "application/json",
            data: function (d) {
              return JSON.stringify({});
            }
          },
          columns: [{
            data: 'Years'
          },
          {
            data: 'POAmount',
            render: function (data) {
              return formatNumber(data);
            }
          },
          {
            data: 'SalesAmount',
            render: function (data) {
              return formatNumber(data);
            }
          }
          ],
          createdRow: function (row, data, dataIndex) {
            $(row).css('background-color', dataIndex % 2 === 0 ? '#1D1D1D' : '#2D2D2D');
          }
        });
      }

      function detail_table_category_year() {
        var selectYearMonth = $('#categoryyears').val();

        if ($.fn.DataTable.isDataTable('#table_category_year')) {
          $('#table_category_year').DataTable().destroy();
        }

        $('#table_category_year').DataTable({
          destroy: true,
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
            url: "/api/purchasing/get_purchase_category_year",
            type: "POST",
            contentType: "application/json",
            data: function (d) {
              return JSON.stringify({
                yearMonth: selectYearMonth
              });
            }
          },
          columns: [{
            data: 'Years'
          },
          {
            data: 'Category',
          },
          {
            data: 'POAmount',
            render: function (data) {
              return formatNumber(data);
            }
          }
          ],
          createdRow: function (row, data, dataIndex) {
            $(row).css('background-color', dataIndex % 2 === 0 ? '#1D1D1D' : '#2D2D2D');
          }
        });
      }

      function detail_table_category_month() {
        var selectYearMonth = $('#categorymonth').val();

        if ($.fn.DataTable.isDataTable('#table_category_month')) {
          $('#table_category_month').DataTable().destroy();
        }

        $('#table_category_month').DataTable({
          destroy: true,
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
            url: "/api/purchasing/get_purchase_category_month",
            type: "POST",
            contentType: "application/json",
            data: function (d) {
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
            render: function (data) {
              const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
              ];
              return months[data - 1] || data;
            }
          },
          {
            data: 'Category',
          },
          {
            data: 'POAmount',
            render: function (data) {
              return formatNumber(data);
            }
          }
          ],
          createdRow: function (row, data, dataIndex) {
            $(row).css('background-color', dataIndex % 2 === 0 ? '#1D1D1D' : '#2D2D2D');
          }
        });
      }

      function detail_table_month_stack() {
        var selectYear = $('#cetagorymonthstack').val();

        if ($.fn.DataTable.isDataTable('#table_category_month_stack')) {
          $('#table_category_month_stack').DataTable().destroy();
        }

        $('#table_category_month_stack').DataTable({
          destroy: true,
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
            url: "/api/purchasing/get_table_month_stack",
            type: "POST",
            contentType: "application/json",
            data: function (d) {
              return JSON.stringify({
                yearMonth: selectYear
              });
            }
          },
          columns: [{
            data: 'Years'
          },
          {
            data: 'Months',
            render: function (data) {
              const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
              ];
              return months[data - 1] || data;
            }
          },
          {
            data: 'Category',
          },
          {
            data: 'POAmount',
            render: function (data) {
              return formatNumber(data);
            }
          }
          ],
          createdRow: function (row, data, dataIndex) {
            $(row).css('background-color', dataIndex % 2 === 0 ? '#1D1D1D' : '#2D2D2D');
          }
        });
      }

      function detail_table_stack_bymonth() {
        var selectYear = $('#stackbymonth').val();

        if ($.fn.DataTable.isDataTable('#table_stack_bymonth')) {
          $('#table_stack_bymonth').DataTable().destroy();
        }

        $('#table_stack_bymonth').DataTable({
          destroy: true,
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
            url: "/api/purchasing/get_purchase_stack_bymonth",
            type: "POST",
            contentType: "application/json",
            data: function (d) {
              return JSON.stringify({
                yearMonth: selectYear
              });
            }
          },
          columns: [{
            data: 'Years'
          },
          {
            data: 'Months',
            render: function (data) {
              const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
              ];
              return months[data - 1] || data;
            }
          },
          {
            data: 'Category',
          },
          {
            data: 'POAmount',
            render: function (data) {
              return formatNumber(data);
            }
          }
          ],
          createdRow: function (row, data, dataIndex) {
            $(row).css('background-color', dataIndex % 2 === 0 ? '#1D1D1D' : '#2D2D2D');
          }
        });
      }


      function setDefaultData() {

        const currentHost = window.location.host;

        function fetchData(url, callback) {
          axios.get(url)
            .then(response => callback(response.data))
            .catch(error => console.error(`Error fetching ${url}:`, error));
        }

        const apiYears = document.getElementById('current_year').value;
        fetchData(`https://${currentHost}/api/purchasing/get_purchasing_actualYears/${apiYears}`, data => {
          document.getElementById('inpt_val_po').value = data.data_poamount;
          document.getElementById('inpt_val_sales').value = data.data_salesamount;
          document.getElementById('btn_update_chart').click();
        });

        const apiMonth = document.getElementById('actualmonth_year').value;
        fetchData(`https://${currentHost}/api/purchasing/get_purchasing_actualMonth/${apiMonth}`, data => {
          document.getElementById('inpt_val_po_month').value = data.data_poamount_month;
          document.getElementById('inpt_val_sales_month').value = data.data_salesamount_month;
          document.getElementById('btn_update_month').click();
        });

        const categoryYear = document.getElementById('categoryyears').value;
        fetchData(`https://${currentHost}/api/purchasing/get_purchasing_pocategoryYear/${categoryYear}`, data => {
          document.getElementById('inpt_val_poactual_category').value = data.data_poamount_categoryyear.join(',');
          document.getElementById('inpt_val_category_category').value = data.data_category_categoryyear.join(',');
          document.getElementById('btn_update_categoryyear').click();
        });

        const categoryMonth = document.getElementById('categorymonth').value;
        fetchData(`https://${currentHost}/api/purchasing/get_purchasing_pocategoryMonth/${categoryMonth}`, data => {
          document.getElementById('inpt_val_poactual_categorymonth').value = data.data_category_categorymonth.join(',');
          document.getElementById('inpt_val_category_categorymonth').value = data.data_poamount_categorymonth.join(',');
          document.getElementById('btn_update_categorymonth').click();
        });

        const stackMonthCategory = document.getElementById('cetagorymonthstack').value;
        fetchData(`https://${currentHost}/api/purchasing/get_purchasing_pocategoryMonth_Stack/${stackMonthCategory}`, data => {
          document.getElementById('inpt_val_months_categorymonth_stack').value = data.data_months_categorymonthstack.join(',');
          document.getElementById('inpt_val_category_categorymonth_stack').value = data.data_category_categorymonthstack.join(',');
          document.getElementById('inpt_val_poamount_categorymonth_stack').value = data.data_poamount_categorymonthstack.join(',');
          document.getElementById('btn-update-stack').click();
        });

        const stackByMonth = document.getElementById('stackbymonth').value;
        fetchData(`https://${currentHost}/api/purchasing/get_purchasing_pocategoryByMonth/${stackByMonth}`, data => {
          document.getElementById('inpt_val_months_stack_byMonth').value = data.data_months_bystack.join(',');
          document.getElementById('inpt_val_category_stack_byMonth').value = data.data_category_bystack.join(',');
          document.getElementById('inpt_val_poamount_stack_byMonth').value = data.data_poamount_bystack.join(',');
          document.getElementById('btn-update-stack-byMonth').click();
        });

      }
      setDefaultData();
      detail_table_po_month();
      detail_table_po_year();
      detail_table_category_year();
      detail_table_category_month();
      detail_table_month_stack();
      detail_table_stack_bymonth();



      $('#actualmonth_year').on('change', function () {
        detail_table_po_month();
        updatePOActualMonth();
      });

      $('#current_year').on('change', function () {
        detail_table_po_year();
        updateDailyChart();
      });

      $('#categoryyears').on('change', function () {
        detail_table_category_year();
        updatePOCategoryYears();
      });

      $('#categorymonth').on('change', function () {
        detail_table_category_month();
        updatePOCategoryMonth();
      });
      $('#cetagorymonthstack').on('change', function () {
        detail_table_month_stack();
        updatePOCategoryMonthStack();
      });
      $('#stackbymonth').on('change', function () {
        detail_table_stack_bymonth();
        updatePOCategoryByMonthStack();
      });

      function fetchData(url, callback) {
        axios.get(url)
          .then(response => callback(response.data))
          .catch(error => console.error(`Error fetching ${url}:`, error));
      }

      function updateDailyChart() {
        const currentHost = window.location.host;
        const selectYears = document.getElementById('current_year').value;
        fetchData(`https://${currentHost}/api/purchasing/get_purchasing_actualYears/${selectYears}`, data => {
          document.getElementById('inpt_val_po').value = data.data_poamount;
          document.getElementById('inpt_val_sales').value = data.data_salesamount;
          document.getElementById('btn_update_chart').click();
        });
      }

      function updatePOActualMonth() {
        const currentHost = window.location.host;
        const apiMonth = document.getElementById('actualmonth_year').value;
        fetchData(`https://${currentHost}/api/purchasing/get_purchasing_actualMonth/${apiMonth}`, data => {
          document.getElementById('inpt_val_po_month').value = data.data_poamount_month;
          document.getElementById('inpt_val_sales_month').value = data.data_salesamount_month;
          document.getElementById('btn_update_month').click();
        });
      }

      function updatePOCategoryYears() {
        const currentHost = window.location.host;
        const categoryYear = document.getElementById('categoryyears').value;
        fetchData(`https://${currentHost}/api/purchasing/get_purchasing_pocategoryYear/${categoryYear}`, data => {
          document.getElementById('inpt_val_poactual_category').value = data.data_poamount_categoryyear.join(',');
          document.getElementById('inpt_val_category_category').value = data.data_category_categoryyear.join(',');
          document.getElementById('btn_update_categoryyear').click();
        });
      }

      function updatePOCategoryMonth() {
        const currentHost = window.location.host;
        const categoryYear = document.getElementById('categorymonth').value;
        fetchData(`https://${currentHost}/api/purchasing/get_purchasing_pocategoryMonth/${categoryYear}`, data => {
          document.getElementById('inpt_val_poactual_categorymonth').value = data.data_category_categorymonth.join(',');
          document.getElementById('inpt_val_category_categorymonth').value = data.data_poamount_categorymonth.join(',');
          document.getElementById('btn_update_categorymonth').click();
        });
      }

      function updatePOCategoryMonthStack() {
        const currentHost = window.location.host;
        const categoryYear = document.getElementById('cetagorymonthstack').value;
        fetchData(`https://${currentHost}/api/purchasing/get_purchasing_pocategoryMonth_Stack/${categoryYear}`, data => {
          document.getElementById('inpt_val_months_categorymonth_stack').value = data.data_months_categorymonthstack.join(',');
          document.getElementById('inpt_val_category_categorymonth_stack').value = data.data_category_categorymonthstack.join(',');
          document.getElementById('inpt_val_poamount_categorymonth_stack').value = data.data_poamount_categorymonthstack.join(',');
          document.getElementById('btn-update-stack').click();
        });
      }

      function updatePOCategoryByMonthStack() {
        const currentHost = window.location.host;
        const categoryYear = document.getElementById('stackbymonth').value;
        fetchData(`https://${currentHost}/api/purchasing/get_purchasing_pocategoryByMonth/${categoryYear}`, data => {
          document.getElementById('inpt_val_months_stack_byMonth').value = data.data_months_bystack.join(',');
          document.getElementById('inpt_val_category_stack_byMonth').value = data.data_category_bystack.join(',');
          document.getElementById('inpt_val_poamount_stack_byMonth').value = data.data_poamount_bystack.join(',');
          document.getElementById('btn-update-stack-byMonth').click();
        });
      }

    });

    document.addEventListener("alpine:init", () => {
      Alpine.data("analytics", () => ({
        data: {
          analytics: "Initial Data"
        },
        formatNumber(value) {
          return value;
        },
        ChartRecall1(poAmount, salesAmount) {
          const poamount = poAmount.split(',').map(Number);
          const salesamount = salesAmount.split(',').map(Number);

          this.BoxChart1.updateSeries([{
            name: 'PO Amount',
            data: poamount
          },
          {
            name: 'Sales Amount',
            data: salesamount
          },
          ]);
        },

        ChartRecall2(monthPOAmount, monthSalesAmount) {
          const monthpoamount = monthPOAmount.split(',').map(Number);
          const monthsalesamount = monthSalesAmount.split(',').map(Number);

          this.BoxChart2.updateSeries([{
            name: 'PO Amount',
            data: monthpoamount
          },
          {
            name: 'Sales Amount',
            data: monthsalesamount
          },
          ]);
        },

        ChartRecall3(categoryPOAmount, categoryCategoryYears) {
          const POAmount = categoryPOAmount.split(',').map(Number);
          const categoryYears = categoryCategoryYears.split(',');

          this.BoxChart3.updateSeries([{
            name: 'PO Amount',
            data: POAmount
          },]);
          this.BoxChart3.updateOptions({
            xaxis: {
              categories: categoryYears,
            },
          });
        },

        ChartRecall4(categoryMonthCategory, categoryMonthPOAmount) {
          const categoryMonth = categoryMonthCategory.split(',');
          const POAmount = categoryMonthPOAmount.split(',').map(Number);

          this.BoxChart4.updateSeries([{
            name: 'PO Amount',
            data: POAmount
          },]);
          this.BoxChart4.updateOptions({
            xaxis: {
              categories: categoryMonth,
            },
          });
        },


        ChartRecall5(categoryMonthSelect, categoryMonthCategory, categoryMonthPOAmount) {
          const monthNames = {
            "1": "Jan",
            "2": "Feb",
            "3": "Mar",
            "4": "Apr",
            "5": "May",
            "6": "Jun",
            "7": "Jul",
            "8": "Aug",
            "9": "Sep",
            "10": "Oct",
            "11": "Nov",
            "12": "Dec"
          };

          const monthNumbers = categoryMonthSelect.split(',');
          const months = monthNumbers.map(m => monthNames[m] || m);
          const categories = categoryMonthCategory.split(',');
          const poAmounts = categoryMonthPOAmount.split(',').map(Number);

          const dataByMonth = {};

          monthNumbers.forEach((monthNum, index) => {
            const monthName = monthNames[monthNum];
            const category = categories[index];
            const amount = poAmounts[index];

            if (!dataByMonth[monthName]) {
              dataByMonth[monthName] = {};
            }

            if (!dataByMonth[monthName][category]) {
              dataByMonth[monthName][category] = 0;
            }

            dataByMonth[monthName][category] += amount;
          });

          const uniqueMonths = Object.keys(dataByMonth).sort((a, b) => {
            const monthOrder = Object.values(monthNames);
            return monthOrder.indexOf(a) - monthOrder.indexOf(b);
          });

          const uniqueCategories = [...new Set(categories)];

          const series = uniqueCategories.map(category => {
            return {
              name: category,
              data: uniqueMonths.map(month =>
                dataByMonth[month] && dataByMonth[month][category] ? dataByMonth[month][category] : 0
              )
            };
          });

          this.BoxChart5.updateOptions({
            colors: ['#2A9D8F', '#008ffb', '#F4A261', '#9718c9'],
            dataLabels: {
              enabled: true,
              position: 'top',
              formatter: (value) => {
                if (value >= 1000000000) return (Math.floor(value / 100000000) / 10).toFixed(0) + " B";
                if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(0) + " M";
                if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(0) + " K";
                return value;
              }
            },
            xaxis: {
              categories: uniqueMonths,
              labels: {
                formatter: (value) => {
                  if (value >= 1000000000) return (Math.floor(value / 100000000) / 10).toFixed(0) + "B";
                  if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(0) + "M";
                  if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(0) + "K";
                  return value;
                }
              }
            },
          });
          this.BoxChart5.updateSeries(series);
        },

        ChartRecall6(categoryMonthSelect, categoryMonthCategory, categoryMonthPOAmount) {
          const monthNames = {
            "1": "Jan",
            "2": "Feb",
            "3": "Mar",
            "4": "Apr",
            "5": "May",
            "6": "Jun",
            "7": "Jul",
            "8": "Aug",
            "9": "Sep",
            "10": "Oct",
            "11": "Nov",
            "12": "Dec"
          };

          const monthNumbers = categoryMonthSelect.split(',');
          const months = monthNumbers.map(m => monthNames[m] || m);
          const categories = categoryMonthCategory.split(',');
          const poAmounts = categoryMonthPOAmount.split(',').map(Number);

          const dataByMonth = {};

          monthNumbers.forEach((monthNum, index) => {
            const monthName = monthNames[monthNum];
            const category = categories[index];
            const amount = poAmounts[index];

            if (!dataByMonth[monthName]) {
              dataByMonth[monthName] = {};
            }

            if (!dataByMonth[monthName][category]) {
              dataByMonth[monthName][category] = 0;
            }

            dataByMonth[monthName][category] += amount;
          });

          const uniqueMonths = Object.keys(dataByMonth).sort((a, b) => {
            const monthOrder = Object.values(monthNames);
            return monthOrder.indexOf(a) - monthOrder.indexOf(b);
          });

          const uniqueCategories = [...new Set(categories)];

          const series = uniqueCategories.map(category => {
            return {
              name: category,
              data: uniqueMonths.map(month =>
                dataByMonth[month] && dataByMonth[month][category] ? dataByMonth[month][category] : 0
              )
            };
          });

          this.BoxChart6.updateOptions({
            colors: ['#2A9D8F', '#008ffb', '#F4A261', '#9718c9'],
            dataLabels: {
              enabled: true,
              position: 'top',
              formatter: (value) => {
                if (value >= 1000000000) return (Math.floor(value / 100000000) / 10).toFixed(0) + " B";
                if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(0) + " M";
                if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(0) + " K";
                return value;
              }
            },
            xaxis: {
              categories: uniqueMonths,
              labels: {
                formatter: (value) => {
                  if (value >= 1000000000) return (Math.floor(value / 100000000) / 10).toFixed(0) + "B";
                  if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(0) + "M";
                  if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(0) + "K";
                  return value;
                }
              }
            },
          });
          this.BoxChart6.updateSeries(series);
        },

        renderCharts() {
          this.BoxChart1 = new ApexCharts(this.$refs.BoxChart1, this.BoxChart1Options);
          this.BoxChart1.render();

          this.BoxChart2 = new ApexCharts(this.$refs.BoxChart2, this.BoxChart2Options);
          this.BoxChart2.render();

          this.BoxChart3 = new ApexCharts(this.$refs.BoxChart3, this.BoxChart3Options);
          this.BoxChart3.render();

          this.BoxChart4 = new ApexCharts(this.$refs.BoxChart4, this.BoxChart4Options);
          this.BoxChart4.render();

          this.BoxChart5 = new ApexCharts(this.$refs.BoxChart5, this.BoxChart5Options);
          this.BoxChart5.render();

          this.BoxChart6 = new ApexCharts(this.$refs.BoxChart6, this.BoxChart6Options);
          this.BoxChart6.render();
        },


        get BoxChart1Options() {
          return {
            series: [{
              name: 'PO Amount',
              type: 'bar',
              data: [0, 0, 0]
            },
            {
              name: 'Sales Amount',
              type: 'bar',
              data: [0, 0, 0]
            }
            ],
            chart: {
              height: 360,
              fontFamily: 'Nunito, sans-serif',
              toolbar: {
                show: false
              }
            },
            dataLabels: {
              enabled: true,
              formatter: (value) => {
                if (value >= 1000000000) return (Math.floor(value / 100000000) / 10).toFixed(0) + " B";
                if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(0) + " M";
                if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(0) + "   K";
                return value;
              }
            },
            stroke: {
              width: [2, 0],
              curve: 'smooth'
            },
            colors: ['#F4A261', '#2a9d8f'],
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
              borderColor: '#191e3a',
              padding: {
                left: 20,
                right: 20
              }
            },
            yaxis: {
              labels: {
                formatter: (value) => {
                  if (value >= 1000000000) return (Math.floor(value / 100000000) / 10).toFixed(0) + " B";
                  if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(0) + " M";
                  if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(0) + " K";
                  return value;
                }
              }
            },
            xaxis: {
              categories: ['2023', '2024', '2025', '2026', '2027', '2028', '2029', '2030', '2031', '2032', '2033', '2034'],
              axisBorder: {
                show: true,
                color: '#3b3f5c'
              },
              axisTicks: {
                show: false
              }
            },
            tooltip: {
              y: {
                formatter: (value) => {
                  return new Intl.NumberFormat('id-ID', {}).format(value);
                }
              }
            },
            fill: {
              type: ['solid', 'gradient'],
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
        get BoxChart2Options() {
          return {
            series: [{
              name: 'PO Amount',
              type: 'bar',
              data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            },
            {
              name: 'Sales Amount',
              type: 'bar',
              data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            }
            ],
            chart: {
              height: 360,
              fontFamily: 'Nunito, sans-serif',
              toolbar: {
                show: false
              }
            },
            dataLabels: {
              enabled: true,
              formatter: (value) => {
                if (value >= 1000000000) return (Math.floor(value / 100000000) / 10).toFixed(0) + " B";
                if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(0) + " M";
                if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(0) + " K";
                return value;
              }
            },
            stroke: {
              width: [2, 0],
              curve: 'smooth'
            },
            colors: ['#F4A261', '#2a9d8f'],
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
              borderColor: '#191e3a',
              padding: {
                left: 20,
                right: 20
              }
            },
            yaxis: {
              labels: {
                formatter: (value) => {
                  if (value >= 1000000000) return (Math.floor(value / 100000000) / 10).toFixed(0) + " B";
                  if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(0) + " M";
                  if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(0) + " K";
                  return value;
                }
              }
            },
            xaxis: {
              categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
              axisBorder: {
                show: true,
                color: '#3b3f5c'
              },
              axisTicks: {
                show: false
              }
            },
            tooltip: {
              y: {
                formatter: (value) => {
                  return new Intl.NumberFormat('id-ID', {}).format(value);
                }
              }
            },
            fill: {
              type: ['solid', 'gradient'],
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
        get BoxChart3Options() {
          return {
            series: [{
              name: 'PO Amount',
              type: 'bar',
              data: []
            },],
            chart: {
              height: 360,
              fontFamily: 'Nunito, sans-serif',
              toolbar: {
                show: false
              }
            },
            dataLabels: {
              enabled: true,
              formatter: (value) => {
                if (value >= 1000000000) return (Math.floor(value / 100000000) / 10).toFixed(0) + " B";
                if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(0) + " M";
                if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(0) + " K";
                return value;
              }
            },
            stroke: {
              width: [0, 0],
              curve: 'smooth'
            },
            colors: ['#F4A261'],
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
              borderColor: '#191e3a',
              padding: {
                left: 20,
                right: 20
              }
            },
            yaxis: {
              labels: {
                formatter: (value) => {
                  if (value >= 1000000000) return (Math.floor(value / 100000000) / 10).toFixed(0) + "B";
                  if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(0) + "M";
                  if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(0) + "K";
                  return value;
                }
              }
            },
            xaxis: {
              categories: [],
              axisBorder: {
                show: true,
                color: '#3b3f5c'
              },
              axisTicks: {
                show: false
              }
            },
            tooltip: {
              y: {
                formatter: (value) => {
                  return new Intl.NumberFormat('id-ID', {}).format(value);
                }
              }
            },
            fill: {
              type: ['solid', 'gradient'],
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

        get BoxChart4Options() {
          return {
            series: [{
              name: 'PO Amount',
              type: 'bar',
              data: []
            },],
            chart: {
              height: 360,
              fontFamily: 'Nunito, sans-serif',
              toolbar: {
                show: false
              }
            },
            dataLabels: {
              enabled: true,
              formatter: (value) => {
                if (value >= 1000000000) return (Math.floor(value / 100000000) / 10).toFixed(0) + " B";
                if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(0) + " M";
                if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(0) + " K";
                return value;
              }
            },
            stroke: {
              width: [0, 0],
              curve: 'smooth'
            },
            colors: ['#F4A261'],
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
              borderColor: '#191e3a',
              padding: {
                left: 20,
                right: 20
              }
            },
            yaxis: {
              labels: {
                formatter: (value) => {
                  if (value >= 1000000000) return (Math.floor(value / 100000000) / 10).toFixed(0) + "B";
                  if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(0) + "M";
                  if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(0) + "K";
                  return value;
                }
              }
            },
            xaxis: {
              categories: [],
              axisBorder: {
                show: true,
                color: '#3b3f5c'
              },
              axisTicks: {
                show: false
              }
            },
            tooltip: {
              y: {
                formatter: (value) => {
                  return new Intl.NumberFormat('id-ID', {}).format(value);
                }
              }
            },
            fill: {
              type: ['solid', 'gradient'],
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
        get BoxChart5Options() {
          return {
            series: [],
            chart: {
              height: 660,
              type: 'bar',
              fontFamily: 'Nunito, sans-serif',
              stacked: true,
              toolbar: {
                show: false
              }
            },
            plotOptions: {
              bar: {
                horizontal: true
              }
            },
            stroke: {
              width: [0, 0],
              curve: 'smooth'
            },
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
              borderColor: '#191e3a',
              padding: {
                left: 20,
                right: 20
              }
            },
            yaxis: {
              labels: {
                formatter: (value) => {
                  if (value >= 1000000000) return (Math.floor(value / 100000000) / 10).toFixed(0) + "B";
                  if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(0) + "M";
                  if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(0) + "K";
                  return value;
                }
              }
            },
            tooltip: {
              y: {
                formatter: (value) => {
                  return new Intl.NumberFormat('id-ID', {}).format(value);
                }
              }
            },
            fill: {
              type: ['solid'],
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
            series: [],
            chart: {
              height: 660,
              type: 'bar',
              fontFamily: 'Nunito, sans-serif',
              stacked: true,
              toolbar: {
                show: false
              }
            },
            plotOptions: {
              bar: {
                horizontal: true
              }
            },
            stroke: {
              width: [0, 0],
              curve: 'smooth'
            },
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
              borderColor: '#191e3a',
              padding: {
                left: 20,
                right: 20
              }
            },
            yaxis: {
              labels: {
                formatter: (value) => {
                  if (value >= 1000000000) return (Math.floor(value / 100000000) / 10).toFixed(0) + "B";
                  if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(0) + "M";
                  if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(0) + "K";
                  return value;
                }
              }
            },
            tooltip: {
              y: {
                formatter: (value) => {
                  return new Intl.NumberFormat('id-ID', {}).format(value);
                }
              }
            },
            fill: {
              type: ['solid'],
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
      }))
    });
  </script>
</x-layout.default>
