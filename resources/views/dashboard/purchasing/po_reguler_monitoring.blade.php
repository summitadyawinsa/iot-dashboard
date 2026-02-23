<x-layout.default>
  <script defer src="/assets/js/apexcharts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
  <style>
    #po_reguler_table tbody tr:hover,
    #po_reguler_ammount_table tbody tr:hover,
    #po_approve_table_reguler tbody tr:hover,
    #po_reguler_gr tbody tr:hover {
      background-color: #727e93;
      cursor: pointer;
    }

    #po_reguler_table tbody tr,
    #po_reguler_ammount_table tbody tr,
    #po_approve_table_reguler tbody tr,
    #po_reguler_gr tbody tr {
      background-color: #4B5563;
      color: whitesmoke;
      cursor: pointer;
      font-size: small;
    }

    #po_reguler_table thead tr,
    #po_reguler_ammount_table thead tr,
    #po_reguler_gr thead tr {
      background-color: #4B5563;
      color: whitesmoke;
    }

    #start_date::-webkit-calendar-picker-indicator,
    #to_date::-webkit-calendar-picker-indicator,
    #dateSelected::-webkit-calendar-picker-indicator,
    #approval_date::-webkit-calendar-picker-indicator,
    #selectMonth::-webkit-calendar-picker-indicator,
    #po_reguler_ammount_table::-webkit-calendar-picker-indicator {
      filter: invert(1);
    }

    select[name="po_reguler_table_length"],
    select[name="po_reguler_ammount_table_length"],
    select[name="po_approve_table_reguler_length"],
    select[name="po_reguler_gr_length"] {
      background-color: #4B5563;
      width: 80px;
    }
  </style>
  <div x-data="analytics">
    <ul class="flex space-x-2 rtl:space-x-reverse">
      <li>
        <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
      </li>
      <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
        <span>Purchasing</span>
      </li>
      <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
        <span>Monitoring PO Reguler</span>
      </li>
    </ul>
    <div class="pt-5">
      <div class="grid sm:grid-cols-2 xl:grid-cols-12 gap-6 mb-6">
        <?php
$currentYear = date('Y');
$startYear = $currentYear - 5;
$currentMonth = date('Y-m');
$currentDate = date('Y-m-d');
$startDate = date('Y-m-d');
$toDate = date('Y-m-d');
        ?>

        <div class="panel h-full sm:col-span-6 xl:col-span-6">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-3">
              <span class="text-xl font-bold">PO Reguler</span>
              <span></span>
            </div>
          </div>
          <hr>
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn_update_chart"
                x-on:click="() => ChartRecall1(inpt_data_total_oke.value, inpt_data_total_under.value, inpt_data_total_over.value)"
                hidden>Recall and Update
              </button>
              <input type="text" id="inpt_data_total_oke" value="" hidden>
              <input type="text" id="inpt_data_total_under" value="" hidden>
              <input type="text" id="inpt_data_total_over" value="" hidden>
              <div x-ref="BoxChart2" class="overflow-hidden"></div>
            </div>
          </div>
        </div>
        <div class="panel h-full sm:col-span-6 xl:col-span-6">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-3">
              <label for="selectMonth" class="text-xl font-bold">Receipt PO Reguler</label>
              <span></span>
              <input id="selectMonth" type="month" class="form-input" value="<?= $currentMonth ?>"
                style="color: white;" />
            </div>
          </div>
          <hr>
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn_update_receipt"
                x-on:click="() => ChartRecall2(inpt_data_fully_received.value, inpt_data_partially_received.value, inpt_data_data_open.value)"
                hidden>Recall and Update
              </button>
              <input type="text" id="inpt_data_fully_received" value="" hidden>
              <input type="text" id="inpt_data_partially_received" value="" hidden>
              <input type="text" id="inpt_data_data_open" value="" hidden>
              <div x-ref="BoxChart3" class="overflow-hidden"></div>
            </div>
          </div>
        </div>
        <!-- PO Reguler Table -->
        <div class="panel h-full col-span-12 ">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-3">
              <span class="text-xl font-bold">PO Reguler Table</span>
              <span></span>
            </div>
          </div>
          <hr hidden>
          <div class="relative overflow-hidden pt-5">
            <table id="po_reguler_gr" class="min-w-full rounded-md shadow-md overflow-hidden">
              <thead>
                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                  <th class="py-3 px-6 text-left">PO Number</th>
                  <th class="py-3 px-6 text-left">PO Line</th>
                  <th class="py-3 px-6 text-left">PO Rel</th>
                  <th class="py-3 px-6 text-left">Part Number</th>
                  <th class="py-3 px-6 text-left">Part Description</th>
                  <th class="py-3 px-6 text-left">Total Qty PO</th>
                  <th class="py-3 px-6 text-left">Total GR</th>
                  <th class="py-3 px-6 text-left">Total Invoiced</th>
                  <th class="py-3 px-6 text-left">Status</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="panel h-full sm:col-span-6 xl:col-span-6">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-3">
              <span class="text-xl font-bold">Aging PO Reguler</span>
              <span></span>
            </div>
          </div>
          <hr>
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn_update_aging"
                x-on:click="() => ChartRecall3(inpt_data_aging_bucket.value, inpt_data_totalpo.value)" hidden>Recall and
                Update
              </button>
              <input type="text" id="inpt_data_aging_bucket" value="" hidden>
              <input type="text" id="inpt_data_totalpo" value="" hidden>
              <div x-ref="BoxChart4" class="overflow-hidden"></div>
            </div>
          </div>
        </div>
        <div class="panel h-full sm:col-span-6 xl:col-span-6">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-3">
              <label for="approval_date" class="text-xl font-bold">PO Approval Reguler</label>
              <span></span>
              <input id="approval_date" type="month" class="form-input" value="<?= $currentMonth ?>"
                style="color: white;" />
            </div>
          </div>
          <hr>
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn_update_approval"
                x-on:click="() => ChartRecall4(inpt_data_check.value, inpt_data_approval.value, inpt_data_legal.value)"
                hidden>Recall and Update
              </button>
              <input type="text" id="inpt_data_check" value="" hidden>
              <input type="text" id="inpt_data_approval" value="" hidden>
              <input type="text" id="inpt_data_legal" value="" hidden>
              <div x-ref="BoxChart5" class="overflow-hidden"></div>
            </div>
          </div>
        </div>
        <div class="panel h-full col-span-12 ">
          <div class="grid grid-cols-1 p-5 dark:text-white-light">
            <div class="flex justify-between items-start gap-5 flex-wrap">
              <label class="text-xl font-bold">PO Approval Table</label>
              <div class="flex flex-col gap-3">
                <div class="flex items-center gap-3">
                  <input id="dateSelected" type="month" class="form-input w-48" value="<?= $currentMonth ?>"
                    style="color: white;">
                </div>
              </div>
            </div>
          </div>
          <hr hidden>
          <div class="relative overflow-hidden pt-5">
            <table id="po_approve_table_reguler" class="min-w-full rounded-md shadow-md overflow-hidden">
              <thead>
                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                  <th class="py-3 px-6 text-left">PO Num</th>
                  <th class="py-3 px-6 text-left">Doc Num</th>
                  <th class="py-3 px-6 text-left">Order Date</th>
                  <th class="py-3 px-6 text-left">Amount</th>
                  <th class="py-3 px-6 text-left">Buyer ID</th>
                  <th class="py-3 px-6 text-left">Check</th>
                  <th class="py-3 px-6 text-left">Approve</th>
                  <th class="py-3 px-6 text-left">Legalize</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="panel h-full col-span-6 ">
          <div class="grid grid-cols-1 p-5 dark:text-white-light">
            <div class="flex justify-between items-start gap-5 flex-wrap">
              <label class="text-xl font-bold">PO Reguler By Days</label>
              <div class="flex flex-col gap-3">
                <div class="flex items-center gap-3">
                  <label for="start_date" class="text-xl font-bold w-16">From</label>
                  <input id="start_date" type="date" class="form-input w-48" value="<?= $startDate ?>"
                    style="color: white;">
                </div>
                <div class="flex items-center gap-3">
                  <label for="to_date" class="text-xl font-bold w-16">To</label>
                  <input id="to_date" type="date" class="form-input w-48" value="<?= $toDate ?>" style="color: white;">
                </div>
              </div>
            </div>
          </div>
          <hr hidden>
          <div class="relative overflow-hidden pt-5">
            <table id="po_reguler_table" class="min-w-full rounded-md shadow-md overflow-hidden">
              <thead>
                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                  <th class="py-3 px-6 text-left">Order Date</th>
                  <th class="py-3 px-6 text-left">PO Line</th>
                  <th class="py-3 px-6 text-left">PO Number</th>
                  <th class="py-3 px-6 text-left">Rel Qty</th>
                  <th class="py-3 px-6 text-left">Total GR</th>
                  <th class="py-3 px-6 text-left">Outstanding Qty</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <!-- Ammount -->
        <div class="panel h-full col-span-6 ">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-2">
              <label for="select_date" class="text-xl font-bold">PO Reguler Ammount Days</label>
              <input id="select_date" type="date" class="form-input" value="<?= $currentDate ?>" style="color: white;"
                hidden>
            </div>
          </div>
          <hr hidden>
          <div class="relative overflow-hidden pt-5">
            <table id="po_reguler_ammount_table" class="min-w-full rounded-md shadow-md overflow-hidden">
              <thead>
                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                  <th class="py-3 px-6 text-left">PO Number</th>
                  <th class="py-3 px-6 text-left">PO Line</th>
                  <th class="py-3 px-6 text-left">Release Cost</th>
                  <th class="py-3 px-6 text-left">Total Invoiced</th>
                  <th class="py-3 px-6 text-left">Balance Remaining</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script>
    let globalSelectedStatus = null;
    let detailTablePOReguler = null;

    document.addEventListener("DOMContentLoaded", function () {
      function detail_table() {
        var detailTable = $("#po_reguler_table").DataTable({
          destroy: true,
          processing: true,
          serverSide: true,
          responsive: false,
          deferLoading: 57,
          language: {
            'processing': '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
          },
          info: false,
          order: [],
          columnDefs: [{
            orderable: false,
            targets: 0
          }],
          ajax: {
            url: `https://${window.location.host}/api/purchasing/get_purchase_po_reguler`,
            type: 'POST',
            contentType: "application/json",
            data: function (d) {
              let startDate = document.getElementById("start_date").value;
              let toDate = document.getElementById("to_date").value;
              if (startDate) {
                d.start_date = startDate;
              }
              if (toDate) {
                d.to_date = toDate;
              }
              return JSON.stringify(d);
            },
            cache: false,
            dataType: 'json',
            error: function (xhr, error, thrown) {
              console.error('AJAX Error (detail_table):', error, thrown, xhr.responseText);
            }
          },
          columns: [{
            data: 'OrderDate'
          },
          {
            data: 'POLine',
          },
          {
            data: 'PONum',
          },
          {
            data: 'RelQty',
          },
          {
            data: 'TotalGR',
          },
          {
            data: 'OutstandingQty',
          },
          ],
          createdRow: function (row, data, dataIndex) {
            $(row).css('background-color', dataIndex % 2 === 0 ? '#1D1D1D' : '#2D2D2D');
          }
        });

        const buttonContainer = document.createElement('div');
        buttonContainer.style.display = 'flex';
        buttonContainer.style.gap = '10px';
        buttonContainer.style.marginTop = '10px';
        buttonContainer.id = 'po_reguler_button_container';

        const downloadButton = document.createElement('button');
        downloadButton.id = 'download_po_reguler_button';
        downloadButton.className = 'btn btn-primary';
        downloadButton.innerHTML = `
        <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down-fill" viewBox="0 0 16 16" style="margin-right: 8px;">
            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"/>
        </svg> Download Data
    `;

        buttonContainer.appendChild(downloadButton);

        const tableWrapper = document.querySelector('#po_reguler_table_wrapper');
        if (tableWrapper) {
          const existingContainer = document.getElementById('po_reguler_button_container');
          if (existingContainer) existingContainer.remove();
          tableWrapper.insertAdjacentElement('afterend', buttonContainer);
        } else {
          console.error('Table wrapper #po_reguler_table_wrapper not found');
        }

        downloadButton.addEventListener('click', function () {
          createDateRangePopup('po_reguler');
        });

        setTimeout(function () {
          detailTable.ajax.reload();
        }, 100);

        return detailTable;
      }

      function detail_table_po_approve_reguler() {
        var selectYear = $('#dateSelected').val();

        detailTablePOApproveReguler = $("#po_approve_table_reguler").DataTable({
          destroy: true,
          processing: true,
          serverSide: true,
          responsive: false,
          deferLoading: 57,
          language: {
            'processing': '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
          },
          info: false,
          order: [],
          columnDefs: [{
            orderable: false,
            targets: 0
          }],
          ajax: {
            url: `https://${window.location.host}/api/purchasing/get_data_po_approval_reguler`,
            type: 'POST',
            contentType: "application/json",
            data: function (d) {
              return JSON.stringify({
                ...d,
                date: selectYear,
              });
            },
            cache: false,
            dataType: 'json'
          },
          columns: [{
            data: 'PONum'
          },
          {
            data: 'DocNum',
          },
          {
            data: 'OrderDate',
          },
          {
            data: 'Amount',
            render: function (data, type, row) {
              return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
            }
          },
          {
            data: 'BuyerID',
          },
          {
            data: 'StatusChecker',
          },
          {
            data: 'StatusApprover',
          },
          {
            data: 'StatusLegalizer',
          },
          ],
          createdRow: function (row, data, dataIndex) {
            if (dataIndex % 2 === 0) {
              $(row).css('background-color', '#1D1D1D');
            } else {
              $(row).css('background-color', '#2D2D2D');
            }

            const columnMap = {
              StatusChecker: 5,
              StatusApprover: 6,
              StatusLegalizer: 7,
            };

            ['StatusChecker', 'StatusApprover', 'StatusLegalizer'].forEach(function (column) {
              const index = columnMap[column];
              const cell = $('td', row).eq(index);
              const value = data[column];

              if (value === 'APPROVED') {
                cell.html('<span class="badge badge-outline-success">APPROVED</span>');
              } else if (value === 'Pending') {
                cell.html('<span class="badge badge-outline-warning">PENDING</span>');
              } else {
                cell.html(value ?? '');
              }
            });
          }

        });

        function getColumnIndex(columnName) {
          var columns = ['PONum', 'POLine', 'PORel', 'PartNumber', 'PartDescription', 'TotalQtyPO', 'TotalGR', 'TotalInvoiced', 'Status'];
          return columns.indexOf(columnName);
        }

        const buttonContainer = document.createElement('div');
        buttonContainer.style.display = 'flex';
        buttonContainer.style.gap = '10px';
        buttonContainer.style.marginTop = '10px';
        buttonContainer.id = 'po_approve_table_reguler_button_container';

        const downloadButton = document.createElement('button');
        downloadButton.id = 'download_po_approve_table_reguler_button';
        downloadButton.className = 'btn btn-primary';
        downloadButton.innerHTML = `
            <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down-fill" viewBox="0 0 16 16" style="margin-right: 8px;">
                <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"/>
            </svg> Download Data
        `;

        buttonContainer.appendChild(downloadButton);

        const tableWrapper = document.querySelector('#po_approve_table_reguler_wrapper');
        if (tableWrapper) {
          const existingContainer = document.getElementById('po_approve_table_reguler_button_container');
          if (existingContainer) existingContainer.remove();
          tableWrapper.insertAdjacentElement('afterend', buttonContainer);
        } else {
          console.error('Table wrapper #po_approve_table_reguler_wrapper not found');
        }

        downloadButton.addEventListener('click', function () {
          createDateRangePopup('po_approve_table_reguler');
        });

        setTimeout(function () {
          detailTablePOApproveReguler.ajax.reload();
        }, 100);

        return detailTablePOApproveReguler;
      }

      function detail_data_table_ammount() {
        var detailTableAmmount = $("#po_reguler_ammount_table").DataTable({
          destroy: true,
          processing: true,
          serverSide: true,
          responsive: false,
          deferLoading: 57,
          language: {
            'processing': '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
          },
          info: false,
          order: [],
          columnDefs: [{
            orderable: false,
            targets: 0
          }],
          ajax: {
            url: `https://${window.location.host}/api/purchasing/get_purchase_data_po_reguler_ammount`,
            type: 'POST',
            contentType: "application/json",
            data: function (d) {
              let startDate = document.getElementById("start_date").value;
              let toDate = document.getElementById("to_date").value;
              if (startDate) {
                d.start_date = startDate;
              }
              if (toDate) {
                d.to_date = toDate;
              }
              return JSON.stringify(d);
            },
            cache: false,
            dataType: 'json',
            error: function (xhr, error, thrown) {
              console.error('AJAX Error (detail_data_table_ammount):', error, thrown, xhr.responseText);
            }
          },
          columns: [{
            data: 'PONum'
          },
          {
            data: 'POLine'
          },
          {
            data: 'ReleaseCost',
            render: function (data, type, row) {
              return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
            }
          },
          {
            data: 'TotalInvoiced',
            render: function (data, type, row) {
              return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
            }
          },
          {
            data: 'BalanceRemaining',
            render: function (data, type, row) {
              return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
            }
          },
          ],
          createdRow: function (row, data, dataIndex) {
            $(row).css('background-color', dataIndex % 2 === 0 ? '#1D1D1D' : '#2D2D2D');
          }
        });

        const buttonContainer = document.createElement('div');
        buttonContainer.style.display = 'flex';
        buttonContainer.style.gap = '10px';
        buttonContainer.style.marginTop = '10px';
        buttonContainer.id = 'po_reguler_ammount_button_container';

        const downloadButton = document.createElement('button');
        downloadButton.id = 'download_po_reguler_ammount_button';
        downloadButton.className = 'btn btn-primary';
        downloadButton.innerHTML = `
        <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down-fill" viewBox="0 0 16 16" style="margin-right: 8px;">
            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"/>
        </svg> Download Data
    `;

        buttonContainer.appendChild(downloadButton);

        const tableWrapper = document.querySelector('#po_reguler_ammount_table_wrapper');
        if (tableWrapper) {
          const existingContainer = document.getElementById('po_reguler_ammount_button_container');
          if (existingContainer) existingContainer.remove();
          tableWrapper.insertAdjacentElement('afterend', buttonContainer);
        } else {
          console.error('Table wrapper #po_reguler_ammount_table_wrapper not found');
        }

        downloadButton.addEventListener('click', function () {
          createDateRangePopup('po_reguler_ammount');
        });

        setTimeout(function () {
          detailTableAmmount.ajax.reload();
        }, 100);

        return detailTableAmmount;
      }

      function detail_table_po_reguler() {
        detailTablePOReguler = $("#po_reguler_gr").DataTable({
          destroy: true,
          processing: true,
          serverSide: true,
          responsive: false,
          deferLoading: 57,
          language: {
            'processing': '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'
          },
          info: false,
          order: [],
          columnDefs: [{
            orderable: false,
            targets: 0
          }],
          ajax: {
            url: `https://${window.location.host}/api/purchasing/get_data_purchasing_po_reguler_table`,
            type: 'POST',
            contentType: "application/json",
            data: function (d) {
              return JSON.stringify({
                ...d,
                status: globalSelectedStatus
              });
            },
            cache: false,
            dataType: 'json',
            error: function (xhr, error, thrown) {
              console.error('AJAX Error (detail_table_po_reguler):', error, thrown, xhr.responseText);
            }
          },
          columns: [{
            data: 'PONum'
          },
          {
            data: 'POLine',
          },
          {
            data: 'PORel',
          },
          {
            data: 'PartNumber',
          },
          {
            data: 'PartDescription',
          },
          {
            data: 'TotalQtyPO',
          },
          {
            data: 'TotalGR',
          },
          {
            data: 'TotalInvoiced',
            render: function (data, type, row) {
              return 'Rp. ' + parseFloat(data).toLocaleString('id-ID');
            }
          },
          {
            data: 'Status',
          },
          ],
          createdRow: function (row, data, dataIndex) {
            if (dataIndex % 2 === 0) {
              $(row).css('background-color', '#1D1D1D');
            } else {
              $(row).css('background-color', '#2D2D2D');
            }

            ['Status'].forEach(function (column) {
              var cell = $('td', row).eq(getColumnIndex(column));

              if (data[column] === 'OK') {
                cell.html('<span class="badge badge-outline-success">OK</span>');
              } else if (data[column] === 'Under GR') {
                cell.html('<span class="badge badge-outline-warning">Under GR</span>');
              } else if (data[column] === 'Over GR') {
                cell.html('<span class="badge badge-outline-danger">Over GR</span>');
              }
            });
          },
        });

        function getColumnIndex(columnName) {
          var columns = ['PONum', 'POLine', 'PORel', 'PartNumber', 'PartDescription', 'TotalQtyPO', 'TotalGR', 'TotalInvoiced', 'Status'];
          return columns.indexOf(columnName);
        }
        const buttonContainer = document.createElement('div');
        buttonContainer.style.display = 'flex';
        buttonContainer.style.gap = '10px';
        buttonContainer.style.marginTop = '10px';
        buttonContainer.id = 'po_reguler_gr_button_container';

        const downloadButton = document.createElement('button');
        downloadButton.id = 'download_po_reguler_gr_button';
        downloadButton.className = 'btn btn-primary';
        downloadButton.innerHTML = `
        <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down-fill" viewBox="0 0 16 16" style="margin-right: 8px;">
            <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"/>
        </svg> Download Data
    `;

        buttonContainer.appendChild(downloadButton);

        const tableWrapper = document.querySelector('#po_reguler_gr_wrapper');
        if (tableWrapper) {
          const existingContainer = document.getElementById('po_reguler_gr_button_container');
          if (existingContainer) existingContainer.remove();
          tableWrapper.insertAdjacentElement('afterend', buttonContainer);
        } else {
          console.error('Table wrapper #po_reguler_gr_wrapper not found');
        }

        downloadButton.addEventListener('click', function () {
          if (!globalSelectedStatus) {
            alert('Please select a status before downloading.');
            return;
          }
          downloadPOGRData(globalSelectedStatus);
        });

        setTimeout(function () {
          detailTablePOReguler.ajax.reload();
        }, 100);

        return detailTablePOReguler;
      }

      function createDateRangePopup(tableType) {
        const existingPopup = document.getElementById('dateRangePopup');
        if (existingPopup) {
          existingPopup.remove();
        }

        const overlay = document.createElement('div');
        overlay.id = 'dateRangePopup';
        overlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        overlay.style.zIndex = '9999';

        const popup = document.createElement('div');
        popup.className = 'bg-white rounded-lg shadow-xl p-6 w-96 max-w-md mx-4';

        popup.innerHTML = `
        <div class="flex items-center mb-6">
            <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-1">Export Data Range</h3>
                <p class="text-sm text-gray-500">Select the time period for your data export</p>
            </div>
        </div>
        <div class="space-y-5">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">From Date</label>
                    <input type="date" id="popupStartDate" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-700 font-medium">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">To Date</label>
                    <input type="date" id="popupEndDate" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-700 font-medium">
                </div>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-800">Export Information</p>
                        <p class="text-xs text-blue-600 mt-1">Data will be exported in Excel format (.xlsx) and may take a few moments depending on the date range selected.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200">
            <div class="text-xs text-gray-500">
                <span class="inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Secure export
                </span>
            </div>
            <div class="flex space-x-3">
                <button id="popupCancelBtn" class="px-6 py-2.5 text-gray-700 bg-white border-2 border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 font-medium">
                    Cancel
                </button>
                <button id="popupDownloadBtn" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Data
                    </span>
                </button>
            </div>
        </div>
    `;

        overlay.appendChild(popup);
        document.body.appendChild(overlay);

        const todayDate = new Date();
        const oneWeekAgo = new Date(todayDate.getTime() - 7 * 24 * 60 * 60 * 1000);

        document.getElementById('popupStartDate').value = oneWeekAgo.toISOString().split('T')[0];
        document.getElementById('popupEndDate').value = todayDate.toISOString().split('T')[0];

        document.getElementById('popupCancelBtn').addEventListener('click', function () {
          overlay.remove();
        });

        overlay.addEventListener('click', function (e) {
          if (e.target === overlay) {
            overlay.remove();
          }
        });

        document.getElementById('popupDownloadBtn').addEventListener('click', function () {
          const startDate = document.getElementById('popupStartDate').value;
          const endDate = document.getElementById('popupEndDate').value;

          if (!startDate || !endDate) {
            alert('Please select both start and end dates.');
            return;
          }

          if (new Date(startDate) > new Date(endDate)) {
            alert('Start date cannot be after end date.');
            return;
          }

          if (tableType === 'po_reguler') {
            downloadPOData(startDate, endDate);
          } else if (tableType === 'po_reguler_ammount') {
            downloadPOAmountData(startDate, endDate);
          } else if (tableType === 'po_approve_table_reguler') {
            downloadPOApprovalData(startDate, endDate);
          }

          overlay.remove();
        });
      }

      function downloadPOData(startDate, endDate) {
        const loadingDiv = document.createElement('div');
        loadingDiv.id = 'loadingOverlay';
        loadingDiv.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center';
        loadingDiv.style.zIndex = '10000';
        loadingDiv.innerHTML = `
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-sm mx-auto border border-gray-100">
            <div class="flex flex-col items-center space-y-4">
                <div class="relative">
                    <div class="w-12 h-12 border-4 border-gray-200 rounded-full animate-spin border-t-blue-600"></div>
                    <div class="absolute inset-0 w-12 h-12 border-4 border-transparent rounded-full animate-pulse border-t-blue-400 opacity-75"></div>
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-800 mb-1">Downloading Data</h3>
                    <p class="text-sm text-gray-500">Please wait while we prepare your file...</p>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div class="bg-blue-600 h-1.5 rounded-full animate-pulse" style="width: 60%"></div>
                </div>
            </div>
        </div>
    `;
        document.body.appendChild(loadingDiv);

        const apiUrl = `https://${window.location.host}/api/purchasing/get_purchase_po_reguler_export`;
        const postData = {
          startDate: startDate,
          endDate: endDate
        };

        axios.post(apiUrl, postData)
          .then(response => {
            console.log('API Response (po_reguler):', response);
            const tableData = response.data.data;
            if (tableData && tableData.length > 0) {
              const excelContent = convertToExcel(tableData);
              const filename = `po_reguler_data_${startDate}_to_${endDate}.xlsx`;
              downloadExcel(excelContent, filename);
            } else {
              alert('No data available for the selected date range.');
            }
          })
          .catch(error => {
            console.error('Error fetching data (po_reguler):', error);
            console.error('Error details:', error.response);
            alert('Failed to fetch data for download.');
          })
          .finally(() => {
            const loading = document.getElementById('loadingOverlay');
            if (loading) {
              loading.remove();
            }
          });
      }

      function downloadPOAmountData(startDate, endDate) {
        const loadingDiv = document.createElement('div');
        loadingDiv.id = 'loadingOverlay';
        loadingDiv.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center';
        loadingDiv.style.zIndex = '10000';
        loadingDiv.innerHTML = `
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-sm mx-auto border border-gray-100">
            <div class="flex flex-col items-center space-y-4">
                <div class="relative">
                    <div class="w-12 h-12 border-4 border-gray-200 rounded-full animate-spin border-t-blue-600"></div>
                    <div class="absolute inset-0 w-12 h-12 border-4 border-transparent rounded-full animate-pulse border-t-blue-400 opacity-75"></div>
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-800 mb-1">Downloading Data</h3>
                    <p class="text-sm text-gray-500">Please wait while we prepare your file...</p>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div class="bg-blue-600 h-1.5 rounded-full animate-pulse" style="width: 60%"></div>
                </div>
            </div>
        </div>
    `;
        document.body.appendChild(loadingDiv);

        const apiUrl = `https://${window.location.host}/api/purchasing/get_purchase_data_po_reguler_ammount_export`;
        const postData = {
          startDate: startDate,
          endDate: endDate
        };

        axios.post(apiUrl, postData)
          .then(response => {
            console.log('API Response (po_reguler_ammount):', response);
            const tableData = response.data.data;
            if (tableData && tableData.length > 0) {
              const excelContent = convertToExcel(tableData);
              const filename = `po_reguler_ammount_data_${startDate}_to_${endDate}.xlsx`;
              downloadExcel(excelContent, filename);
            } else {
              alert('No data available for the selected date range.');
            }
          })
          .catch(error => {
            console.error('Error fetching data (po_reguler_ammount):', error);
            console.error('Error details:', error.response);
            alert('Failed to fetch data for download.');
          })
          .finally(() => {
            const loading = document.getElementById('loadingOverlay');
            if (loading) {
              loading.remove();
            }
          });
      }

      function downloadPOApprovalData(startDate, endDate) {
        const loadingDiv = document.createElement('div');
        loadingDiv.id = 'loadingOverlay';
        loadingDiv.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center';
        loadingDiv.style.zIndex = '10000';
        loadingDiv.innerHTML = `
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-sm mx-auto border border-gray-100">
            <div class="flex flex-col items-center space-y-4">
                <div class="relative">
                    <div class="w-12 h-12 border-4 border-gray-200 rounded-full animate-spin border-t-blue-600"></div>
                    <div class="absolute inset-0 w-12 h-12 border-4 border-transparent rounded-full animate-pulse border-t-blue-400 opacity-75"></div>
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-800 mb-1">Downloading Data</h3>
                    <p class="text-sm text-gray-500">Please wait while we prepare your file...</p>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div class="bg-blue-600 h-1.5 rounded-full animate-pulse" style="width: 60%"></div>
                </div>
            </div>
        </div>
    `;
        document.body.appendChild(loadingDiv);

        const apiUrl = `https://${window.location.host}/api/purchasing/get_data_po_approval_reguler_export`;
        const postData = {
          startDate: startDate,
          endDate: endDate
        };

        axios.post(apiUrl, postData)
          .then(response => {
            console.log('API Response (po_approve_table_reguler):', response);
            const tableData = response.data.data;
            if (tableData && tableData.length > 0) {
              const excelContent = convertToExcel(tableData);
              const filename = `po_approve_table_reguler_${startDate}_to_${endDate}.xlsx`;
              downloadExcel(excelContent, filename);
            } else {
              alert('No data available for the selected date range.');
            }
          })
          .catch(error => {
            console.error('Error fetching data (po_approve_table_reguler):', error);
            console.error('Error details:', error.response);
            alert('Failed to fetch data for download.');
          })
          .finally(() => {
            const loading = document.getElementById('loadingOverlay');
            if (loading) {
              loading.remove();
            }
          });
      }

      function downloadPOGRData(status) {
        console.log('Status:', status);
        const loadingDiv = document.createElement('div');
        loadingDiv.id = 'loadingOverlay';
        loadingDiv.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center';
        loadingDiv.style.zIndex = '10000';
        loadingDiv.innerHTML = `
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-sm mx-auto border border-gray-100">
            <div class="flex flex-col items-center space-y-4">
                <div class="relative">
                    <div class="w-12 h-12 border-4 border-gray-200 rounded-full animate-spin border-t-blue-600"></div>
                    <div class="absolute inset-0 w-12 h-12 border-4 border-transparent rounded-full animate-pulse border-t-blue-400 opacity-75"></div>
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-800 mb-1">Downloading Data</h3>
                    <p class="text-sm text-gray-500">Please wait while we prepare your file...</p>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div class="bg-blue-600 h-1.5 rounded-full animate-pulse" style="width: 60%"></div>
                </div>
            </div>
        </div>
    `;
        document.body.appendChild(loadingDiv);

        const apiUrl = `https://${window.location.host}/api/purchasing/get_data_purchasing_po_reguler_table_export`;
        const postData = {
          status: status
        };

        axios.post(apiUrl, postData)
          .then(response => {
            console.log('API Response (po_reguler_gr):', response);
            const tableData = response.data.data;
            if (tableData && tableData.length > 0) {
              const excelContent = convertToExcel(tableData);
              const filename = `po_reguler_gr_data_${status.replace(/\s+/g, '_')}.xlsx`;
              downloadExcel(excelContent, filename);
            } else {
              alert('No data available for the selected status.');
            }
          })
          .catch(error => {
            console.error('Error fetching data (po_reguler_gr):', error);
            console.error('Error details:', error.response);
            alert('Failed to fetch data for download.');
          })
          .finally(() => {
            const loading = document.getElementById('loadingOverlay');
            if (loading) {
              loading.remove();
            }
          });
      }

      function convertToExcel(data) {
        let workbook = XLSX.utils.book_new();
        let worksheet = XLSX.utils.json_to_sheet(data);
        XLSX.utils.book_append_sheet(workbook, worksheet, "Data");
        return workbook;
      }

      function downloadExcel(workbook, filename) {
        XLSX.writeFile(workbook, filename);
      }

      function setDefaultData() {
        var currentHost = window.location.host;
        const apiUrlDaily = `https://${currentHost}/api/purchasing/get_data_purchase_po_reguler`;
        axios.get(apiUrlDaily)
          .then(response => {
            var data_total_oke = response.data.data_total_oke;
            var data_total_under = response.data.data_total_under;
            var data_total_over = response.data.data_total_over;
            document.getElementById('inpt_data_total_oke').value = data_total_oke;
            document.getElementById('inpt_data_total_under').value = data_total_under;
            document.getElementById('inpt_data_total_over').value = data_total_over;
            document.getElementById('btn_update_chart').click();
          })
          .catch(error => {
            console.error('Error fetching default data:', error);
          });

        const selectMonth = document.getElementById('selectMonth').value;
        const apiUrlReceipt = `https://${currentHost}/api/purchasing/get_data_po_reguler_receipt/${selectMonth}`;
        axios.get(apiUrlReceipt)
          .then(response => {
            var data_fully_received = response.data.data_fully_received;
            var data_partially_received = response.data.data_partially_received;
            var data_open = response.data.data_open;
            document.getElementById('inpt_data_fully_received').value = data_fully_received;
            document.getElementById('inpt_data_partially_received').value = data_partially_received;
            document.getElementById('inpt_data_data_open').value = data_open;
            document.getElementById('btn_update_receipt').click();
          })
          .catch(error => {
            console.error('Error fetching default data:', error);
          });

        const apiUrlAging = `https://${currentHost}/api/purchasing/get_data_po_reguler_aging`;
        axios.get(apiUrlAging)
          .then(response => {
            var data_aging_bucket = response.data.data_aging_bucket;
            var data_total_po = response.data.data_total_po;
            document.getElementById('inpt_data_aging_bucket').value = data_aging_bucket;
            document.getElementById('inpt_data_totalpo').value = data_total_po;
            document.getElementById('btn_update_aging').click();
          })
          .catch(error => {
            console.error('Error fetching default data:', error);
          });

        const date = document.getElementById('approval_date').value;
        const apiApproval = `https://${currentHost}/api/purchasing/get_data_po_reguler_approval/${date}`;
        axios.get(apiApproval)
          .then(response => {
            var data_check = response.data.data_check;
            var data_approve = response.data.data_approve;
            var data_legal = response.data.data_legal;
            document.getElementById('inpt_data_check').value = data_check;
            document.getElementById('inpt_data_approval').value = data_approve;
            document.getElementById('inpt_data_legal').value = data_legal;
            document.getElementById('btn_update_approval').click();
          })
          .catch(error => {
            console.error('Error fetching default data:', error);
          });
      }

      detail_table();
      detail_data_table_ammount();
      detail_table_po_approve_reguler();
      detail_table_po_reguler();
      setDefaultData();

      document.getElementById('start_date').addEventListener('change', function () {
        detail_table();
        detail_data_table_ammount();
      });

      document.getElementById('to_date').addEventListener('change', function () {
        detail_table();
        detail_data_table_ammount();
      });

      document.getElementById('selectMonth').addEventListener('change', function () {
        updateChartReceipt();
      });

      document.getElementById('dateSelected').addEventListener('change', function () {
        detail_table_po_approve_reguler();
      });

      document.getElementById('approval_date').addEventListener('change', function () {
        updateApprovalChart();
      })
    });


    function updateApprovalChart() {
      var currentHost = window.location.host;
      const date = document.getElementById('approval_date').value;
      const apiApproval = `https://${currentHost}/api/purchasing/get_data_po_reguler_approval/${date}`;
      axios.get(apiApproval)
        .then(response => {
          var data_check = response.data.data_check;
          var data_approve = response.data.data_approve;
          var data_legal = response.data.data_legal;
          document.getElementById('inpt_data_check').value = data_check;
          document.getElementById('inpt_data_approval').value = data_approve;
          document.getElementById('inpt_data_legal').value = data_legal;
          document.getElementById('btn_update_approval').click();
        })
        .catch(error => {
          console.error('Error fetching default data:', error);
        });
    }


    function updateChartReceipt() {
      var currentHost = window.location.host;
      const selectMonth = document.getElementById('selectMonth').value;
      const apiUrlReceipt = `https://${currentHost}/api/purchasing/get_data_po_reguler_receipt/${selectMonth}`;
      axios.get(apiUrlReceipt)
        .then(response => {
          var data_fully_received = response.data.data_fully_received;
          var data_partially_received = response.data.data_partially_received;
          var data_open = response.data.data_open;
          document.getElementById('inpt_data_fully_received').value = data_fully_received;
          document.getElementById('inpt_data_partially_received').value = data_partially_received;
          document.getElementById('inpt_data_data_open').value = data_open;
          document.getElementById('btn_update_receipt').click();
        })
        .catch(error => {
          console.error('Error fetching default data:', error);
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

        ChartRecall1(dataDate, dataTotalShip, dataShipComplete) {
          const date = dataDate.split(',').map(Number);
          const totalship = dataTotalShip.split(',').map(Number);
          const shipcomplete = dataShipComplete.split(',').map(Number);

          this.BoxChart2.updateSeries([{
            name: 'OK',
            type: 'bar',
            data: date
          },
          {
            name: 'Under GR',
            type: 'bar',
            data: totalship
          },
          {
            name: 'Over GR',
            type: 'bar',
            data: shipcomplete
          },
          ]);
          this.BoxChart2.updateOptions({
            chart: {
              height: 360,
              fontFamily: 'Nunito, sans-serif',
              toolbar: {
                show: false
              },
              zoom: {
                enabled: false
              },
              events: {
                dataPointSelection: (event, chartContext, config) => {
                  const seriesName = config.w.config.series[config.seriesIndex].name;
                  globalSelectedStatus = seriesName;
                  if (typeof detailTablePOReguler !== 'undefined') {
                    detailTablePOReguler.ajax.reload();
                  }
                }
              }
            },
          });
        },

        ChartRecall2(dataFully, dataPartially, dataOpen) {
          const datafully = dataFully.split(',').map(Number);
          const datapartially = dataPartially.split(',').map(Number);
          const dataopen = dataOpen.split(',').map(Number);

          this.BoxChart3.updateSeries([{
            name: 'Fully Received',
            data: datafully,
            type: 'bar',
          },
          {
            name: 'Partially Received',
            data: datapartially,
            type: 'bar',
          },
          {
            name: 'Open',
            data: dataopen,
            type: 'bar',
          },
          ]);
        },

        ChartRecall3(dataBucket, dataTotalPO) {
          const bucket = dataBucket.split(',');
          const totalpo = dataTotalPO.split(',').map(Number);

          this.BoxChart4.updateSeries([{
            name: 'Total PO',
            data: totalpo,
            type: 'bar',
          },]);
          this.BoxChart4.updateOptions({
            xaxis: {
              categories: bucket,
            },
          });
        },

        ChartRecall4(dataCheck, dataApproval, dataLegal) {
          const check = dataCheck.split(',').map(Number);
          const approve = dataApproval.split(',').map(Number);
          const legal = dataLegal.split(',').map(Number);

          this.BoxChart5.updateSeries([{
            name: 'Checker',
            data: check,
            type: 'bar',
          },
          {
            name: 'Approve',
            data: approve,
            type: 'bar',
          },
          {
            name: 'Legalize',
            data: legal,
            type: 'bar',
          }
          ]);
        },

        renderCharts() {
          this.BoxChart2 = new ApexCharts(this.$refs.BoxChart2, this.BoxChart2Options);
          this.BoxChart2.render();

          this.BoxChart3 = new ApexCharts(this.$refs.BoxChart3, this.BoxChart3Options);
          this.BoxChart3.render();

          this.BoxChart4 = new ApexCharts(this.$refs.BoxChart4, this.BoxChart4Options);
          this.BoxChart4.render();

          this.BoxChart5 = new ApexCharts(this.$refs.BoxChart5, this.BoxChart5Options);
          this.BoxChart5.render();
        },
        get BoxChart2Options() {
          return {
            series: [],
            chart: {
              height: 360,
              fontFamily: 'Nunito, sans-serif',
              toolbar: {
                show: false
              },
              zoom: {
                enabled: false
              },
            },
            dataLabels: {
              enabled: true,
              formatter: (value) => this.formatNumber(value)
            },
            stroke: {
              width: 0,
              colors: ['#2A9D8F', '#F4A261', '#E63946'],
            },
            colors: ['#2A9D8F', '#F4A261', '#E63946'],
            dropShadow: {
              enabled: true,
              blur: 3,
              color: '#515365',
              opacity: 0.4
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
              categories: ['Status PO Number'],
              axisBorder: {
                show: true,
                color: '#3b3f5c'
              },
              axisTicks: {
                show: false
              }
            },
            yaxis: {
              labels: {
                show: true,
                offsetX: 0,
                formatter: (value) => this.formatNumber(value)
              },
              forceNiceScale: true
            },
            tooltip: {
              shared: false,
              colors: ['#2A9D8F', '#F4A261', '#E63946'],
              y: {
                formatter: (value) => this.formatNumber(value)
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
              }
            }
          };
        },

        get BoxChart3Options() {
          return {
            series: [],
            chart: {
              height: 360,
              fontFamily: 'Nunito, sans-serif',
              toolbar: {
                show: false
              },
              zoom: {
                enabled: false
              },
            },
            dataLabels: {
              enabled: true,
              formatter: (value) => this.formatNumber(value)
            },
            stroke: {
              width: 0,
              colors: ['#2A9D8F', '#F4A261', '#E63946'],
            },
            colors: ['#2A9D8F', '#F4A261', '#E63946'],
            dropShadow: {
              enabled: true,
              blur: 3,
              color: '#515365',
              opacity: 0.4
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
              categories: ['Total PO'],
              axisBorder: {
                show: true,
                color: '#3b3f5c'
              },
              axisTicks: {
                show: false
              }
            },
            yaxis: {
              labels: {
                show: true,
                offsetX: 0,
                formatter: (value) => this.formatNumber(value)
              },
              forceNiceScale: true
            },
            tooltip: {
              shared: false,
              colors: ['#2A9D8F', '#F4A261', '#E63946'],

              y: {
                formatter: (value) => this.formatNumber(value)
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
              }
            }
          };
        },

        get BoxChart4Options() {
          return {
            series: [],
            chart: {
              height: 360,
              fontFamily: 'Nunito, sans-serif',
              toolbar: {
                show: false
              }
            },
            dataLabels: {
              enabled: true,
            },
            stroke: {
              width: [0],
              curve: 'smooth'
            },
            colors: ['#F4A261', '#2a9d8f', '#E63946', '#2A9D8F'],
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
              height: 360,
              fontFamily: 'Nunito, sans-serif',
              toolbar: {
                show: false
              },
              zoom: {
                enabled: false
              },
            },
            dataLabels: {
              enabled: true,
              formatter: (value) => this.formatNumber(value)
            },
            stroke: {
              width: 0,
              colors: ['#2A9D8F', '#F4A261', '#9157d4'],
            },
            colors: ['#2A9D8F', '#F4A261', '#9157d4'],
            dropShadow: {
              enabled: true,
              blur: 3,
              color: '#515365',
              opacity: 0.4
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
              categories: ['Status PO Number'],
              axisBorder: {
                show: true,
                color: '#3b3f5c'
              },
              axisTicks: {
                show: false
              }
            },
            yaxis: {
              labels: {
                show: true,
                offsetX: 0,
                formatter: (value) => this.formatNumber(value)
              },
              forceNiceScale: true
            },
            tooltip: {
              shared: false,
              colors: ['#2A9D8F', '#F4A261', '#9157d4'],
              y: {
                formatter: (value) => this.formatNumber(value)
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
