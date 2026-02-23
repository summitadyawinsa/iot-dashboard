<x-layout.default>
  <script defer src="/assets/js/apexcharts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <style>
    #req_po_status_table tbody tr:hover,
    #req_only_po_status_table tbody tr:hover,
    #metrics_po_table tbody tr:hover,
    #tracking_req_po_table tbody tr:hover,
    #req_under_po_table tbody tr:hover {
      background-color: #727e93;
      cursor: pointer;
    }

    #req_po_status_table tbody tr,
    #req_only_po_status_table tbody tr,
    #tracking_req_po_table tbody tr,
    #metrics_po_table tbody tr,
    #req_under_po_table tbody tr {
      background-color: #4B5563;
      color: whitesmoke;
      cursor: pointer;
      font-size: small;
    }

    #req_po_status_table thead tr,
    #req_only_po_status_table thead tr,
    #tracking_req_po_table thead tr,
    #metrics_po_table thead tr,
    #req_under_po_table thead tr {
      background-color: #4B5563;
      color: whitesmoke;
    }

    #selectMonth::-webkit-calendar-picker-indicator,
    #select_date_matrics::-webkit-calendar-picker-indicator,
    #select_month_under::-webkit-calendar-picker-indicator,
    #select_date_tracking::-webkit-calendar-picker-indicator,
    #select_month_under_po::-webkit-calendar-picker-indicator,
    #select_date::-webkit-calendar-picker-indicator {
      filter: invert(1);
    }

    select[name="req_po_status_table_length"],
    select[name="req_only_po_status_table_length"],
    select[name="tracking_req_po_table_length"],
    select[name="metrics_po_table_length"],
    select[name="req_under_po_table_length"] {
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
        <span>Monitoring PR</span>
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

        <div class="panel h-full sm:col-span-12 xl:col-span-12">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-2">
              <label for="selectMonth" class="text-xl font-bold">Summary Status Requisition</label>
              <input id="selectMonth" type="month" class="form-input" value="<?= $currentMonth ?>"
                style="color: white;" />
            </div>
          </div>
          <hr>
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn_update_chart"
                x-on:click="() => ChartRecall1( inpt_data_close_without_po.value, inpt_data_open_pending_po.value, inpt_data_convert_to_po.value)"
                hidden>Recall and Update
              </button>
              <input type="text" id="inpt_data_close_without_po" value="" hidden>
              <input type="text" id="inpt_data_open_pending_po" value="" hidden>
              <input type="text" id="inpt_data_convert_to_po" value="" hidden>
              <div x-ref="BoxChart2" class="overflow-hidden"></div>
            </div>
          </div>
        </div>

        <div class="panel h-full col-span-6 ">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-2">
              <label for="select_date" class="text-xl font-bold">Requisition PO Status</label>
              <input id="select_date" type="month" class="form-input" value="<?= $currentMonth ?>"
                style="color: white;" />
            </div>
          </div>
          <hr hidden>
          <div class="relative overflow-hidden pt-5">
            <table id="req_po_status_table" class="min-w-full rounded-md shadow-md overflow-hidden">
              <thead>
                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                  <th class="py-3 px-6 text-left">Req Num</th>
                  <th class="py-3 px-6 text-left">Request Date</th>
                  <th class="py-3 px-6 text-left">Req Line</th>
                  <th class="py-3 px-6 text-left">Part Num</th>
                  <th class="py-3 px-6 text-left">Line Desc</th>
                  <th class="py-3 px-6 text-left">Order Qty</th>
                  <th class="py-3 px-6 text-left">Vendor Num</th>
                  <th class="py-3 px-6 text-left">Open Order</th>
                  <th class="py-3 px-6 text-left">Req Status</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="panel h-full col-span-6 ">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-2">
              <label for="select_date" class="text-xl font-bold">Requisition Only PO Status</label>
              <input id="select_date" type="month" class="form-input" value="<?= $currentMonth ?>" style="color: white;"
                hidden />
            </div>
          </div>
          <hr hidden>
          <div class="relative overflow-hidden pt-5">
            <table id="req_only_po_status_table" class="min-w-full rounded-md shadow-md overflow-hidden">
              <thead>
                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                  <th class="py-3 px-6 text-left">POLine</th>
                  <th class="py-3 px-6 text-left">PONum</th>
                  <th class="py-3 px-6 text-left">POLineDesc</th>
                  <th class="py-3 px-6 text-left">PODate</th>
                  <th class="py-3 px-6 text-left">POBuyer</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="panel h-full sm:col-span-12 xl:col-span-12">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-2">
              <label for="select_month_under_po" class="text-xl font-bold">Requisition Under PO</label>
              <input id="select_month_under_po" type="month" class="form-input" value="<?= $currentMonth ?>"
                style="color: white;" />
            </div>
          </div>
          <hr>
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn_update_under"
                x-on:click="() => ChartRecall3( inpt_data_critical.value, inpt_data_warning.value, inpt_data_normal.value)"
                hidden>Recall and Update
              </button>
              <input type="text" id="inpt_data_critical" value="" hidden>
              <input type="text" id="inpt_data_warning" value="" hidden>
              <input type="text" id="inpt_data_normal" value="" hidden>
              <div x-ref="BoxChart4" class="overflow-hidden"></div>
            </div>
          </div>
        </div>
        <div class="panel h-full col-span-12 ">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-3">
              <label for="select_month_under" class="text-xl font-bold">Detail Requisition Under PO</label>
              <span></span>
              <input id="select_month_under" type="month" class="form-input" value="<?= $currentMonth ?>"
                style="color: white;" />
            </div>
          </div>
          <hr hidden>
          <div class="relative overflow-hidden pt-5">
            <table id="req_under_po_table" class="min-w-full rounded-md shadow-md overflow-hidden">
              <thead>
                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                  <th class="py-3 px-6 text-left">Req Num</th>
                  <th class="py-3 px-6 text-left">Request Date</th>
                  <th class="py-3 px-6 text-left">Req Line</th>
                  <th class="py-3 px-6 text-left">Part Num</th>
                  <th class="py-3 px-6 text-left">Line Desc</th>
                  <th class="py-3 px-6 text-left">Order Qty</th>
                  <th class="py-3 px-6 text-left">IUM</th>
                  <th class="py-3 px-6 text-left">Vendor Num</th>
                  <th class="py-3 px-6 text-left">Vendor Name</th>
                  <th class="py-3 px-6 text-left">Days Open</th>
                  <th class="py-3 px-6 text-left">Urgency</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="panel h-full col-span-12 ">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-3">
              <label for="select_date_tracking" class="text-xl font-bold"> Tracking Requisition to PO with PO
                Rel</label>
              <span></span>
              <input id="select_date_tracking" type="month" class="form-input" value="<?= $currentMonth ?>"
                style="color: white;" />
            </div>
          </div>
          <hr hidden>
          <div class="relative overflow-hidden pt-5">
            <table id="tracking_req_po_table" class="min-w-full rounded-md shadow-md overflow-hidden">
              <thead>
                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                  <th class="py-3 px-6 text-left">ReqNum</th>
                  <th class="py-3 px-6 text-left">RequestDate</th>
                  <th class="py-3 px-6 text-left">ReqLine</th>
                  <th class="py-3 px-6 text-left">PartNum</th>
                  <th class="py-3 px-6 text-left">ReqDescription</th>
                  <th class="py-3 px-6 text-left">ReqQty</th>
                  <th class="py-3 px-6 text-left">PONum</th>
                  <th class="py-3 px-6 text-left">PODate</th>
                  <th class="py-3 px-6 text-left">BuyerID</th>
                  <th class="py-3 px-6 text-left">POLine</th>
                  <th class="py-3 px-6 text-left">PODescription</th>
                  <th class="py-3 px-6 text-left">PORelNum</th>
                  <th class="py-3 px-6 text-left">PORelQty</th>
                  <th class="py-3 px-6 text-left">DueDate</th>
                  <th class="py-3 px-6 text-left">OpenRelease</th>
                  <th class="py-3 px-6 text-left">VendorID</th>
                  <th class="py-3 px-6 text-left">VendorName</th>
                  <th class="py-3 px-6 text-left">Status</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="panel h-full col-span-12 ">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-3">
              <label for="select_date_matrics" class="text-xl font-bold"> Performance Metrics - Requisition to
                PO</label>
              <span></span>
              <input id="select_date_matrics" type="month" class="form-input" value="<?= $currentMonth ?>"
                style="color: white;" />
            </div>
          </div>
          <hr hidden>
          <div class="relative overflow-hidden pt-5">
            <table id="metrics_po_table" class="min-w-full rounded-md shadow-md overflow-hidden">
              <thead>
                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                  <th class="py-3 px-6 text-left">Years</th>
                  <th class="py-3 px-6 text-left">Months</th>
                  <th class="py-3 px-6 text-left">TotalRequisitions</th>
                  <th class="py-3 px-6 text-left">ConvertToPO</th>
                  <th class="py-3 px-6 text-left">Pending PO</th>
                  <th class="py-3 px-6 text-left">Conversion Rate</th>
                  <th class="py-3 px-6 text-left">Avg Days To Convert</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="panel h-full sm:col-span-12 xl:col-span-12">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-2">
              <label for="selectMonth" class="text-xl font-bold">Requisition to PO Pipeline</label>
              <span></span>
            </div>
          </div>
          <hr>
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn_update_pipeline"
                x-on:click="() => ChartRecall2(inpt_data_metric.value, inpt_data_value.value)" hidden>Recall and Update
              </button>
              <input type="text" id="inpt_data_metric" value="" hidden>
              <input type="text" id="inpt_data_value" value="" hidden>
              <div x-ref="BoxChart3" class="overflow-hidden"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    let globalSelectedStatus = null;
    let detailTablePOStatus = null;
    let detailTableUnderPO = null;
    let detailTableTrackingPO = null;
    let detailTableMatricsPO = null;

    document.addEventListener("DOMContentLoaded", function () {

      function detail_table_req_po_status() {
        var selectDate = $('#select_date').val();
        if ($.fn.DataTable.isDataTable('#req_po_status_table')) {
          $('#req_po_status_table').DataTable().destroy();
        }

        detailTablePOStatus = $("#req_po_status_table").DataTable({
          destroy: true,
          processing: true,
          scrollX: true,
          serverSide: true,
          responsive: true,
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
            url: `https://${window.location.host}/api/purchasing/get_data_purchasing_req_po_status_table`,
            type: 'POST',
            contentType: "application/json",
            data: function (d) {
              return JSON.stringify({
                ...d,
                date: selectDate,
                status: globalSelectedStatus
              });
            },
            cache: false,
            dataType: 'json'
          },
          columns: [{
            data: 'ReqNum',
            width: '100px'
          },
          {
            data: 'RequestDate',
            width: '150px'
          },
          {
            data: 'ReqLine',
            width: '100px'
          },
          {
            data: 'PartNum',
            width: '120px'
          },
          {
            data: 'LineDesc',
            width: '200px'
          },
          {
            data: 'OrderQty',
            width: '100px'
          },
          {
            data: 'VendorNum',
            width: '120px'
          },
          {
            data: 'OpenOrder',
            width: '100px'
          },
          {
            data: 'ReqStatus',
            width: '120px'
          }
          ],
          createdRow: function (row, data, dataIndex) {
            if (dataIndex % 2 === 0) {
              $(row).css('background-color', '#1D1D1D');
            } else {
              $(row).css('background-color', '#2D2D2D');
            }
          }
        });

        const buttonContainer = document.createElement('div');
        buttonContainer.style.display = 'flex';
        buttonContainer.style.gap = '10px';
        buttonContainer.style.marginTop = '10px';
        buttonContainer.id = 'req_po_status_table_button_container';

        const downloadButton = document.createElement('button');
        downloadButton.id = 'download_req_po_status_table_button';
        downloadButton.className = 'btn btn-primary';
        downloadButton.innerHTML = `
            <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down-fill" viewBox="0 0 16 16" style="margin-right: 8px;">
                <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"/>
            </svg> Download Data
        `;

        buttonContainer.appendChild(downloadButton);

        const tableWrapper = document.querySelector('#req_po_status_table_wrapper');
        if (tableWrapper) {
          const existingContainer = document.getElementById('req_po_status_table_button_container');
          if (existingContainer) existingContainer.remove();
          tableWrapper.insertAdjacentElement('afterend', buttonContainer);
        } else {
          console.error('Table wrapper #req_po_status_table_wrapper not found');
        }

        downloadButton.addEventListener('click', function () {
          createDateRangePopup('req_po_status_table');
        });

        setTimeout(function () {
          detailTablePOStatus.ajax.reload();
        }, 100);

        return detailTablePOStatus;
      }

      function detail_table_req_only_po_status() {
        var selectDate = $('#select_date').val();
        if ($.fn.DataTable.isDataTable('#req_only_po_status_table')) {
          $('#req_only_po_status_table').DataTable().destroy();
        }

        detailTableOnlyPOStatus = $("#req_only_po_status_table").DataTable({
          destroy: true,
          processing: true,
          scrollX: true,
          serverSide: true,
          responsive: true,
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
            url: `https://${window.location.host}/api/purchasing/get_data_purchasing_req_only_po_status_table`,
            type: 'POST',
            contentType: "application/json",
            data: function (d) {
              return JSON.stringify({
                ...d,
                date: selectDate,
                status: globalSelectedStatus
              });
            },
            cache: false,
            dataType: 'json'
          },
          columns: [{
            data: 'POLine',
            width: '80px'
          },
          {
            data: 'PONum',
            width: '100px'
          },
          {
            data: 'POLineDesc',
            width: '300px'
          },
          {
            data: 'PODate',
            width: '150px'
          },
          {
            data: 'POBuyer',
            width: '120px'
          },
          ],
          createdRow: function (row, data, dataIndex) {
            if (dataIndex % 2 === 0) {
              $(row).css('background-color', '#1D1D1D');
            } else {
              $(row).css('background-color', '#2D2D2D');
            }
          },
        });

        setTimeout(function () {
          detailTableOnlyPOStatus.ajax.reload();
        }, 100);

        return detailTableOnlyPOStatus;
      }

      function detail_table_req_under_po() {
        var selectDate = $('#select_month_under').val();
        if ($.fn.DataTable.isDataTable('#req_under_po_table')) {
          $('#req_under_po_table').DataTable().destroy();
        }

        detailTableUnderPO = $("#req_under_po_table").DataTable({
          destroy: true,
          processing: true,
          serverSide: true,
          scrollX: true,
          responsive: true,
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
            url: `https://${window.location.host}/api/purchasing/get_data_purchasing_req_under_po_table`,
            type: 'POST',
            contentType: "application/json",
            data: function (d) {
              return JSON.stringify({
                ...d,
                date: selectDate,
                status: globalSelectedStatus
              });
            },
            cache: false,
            dataType: 'json'
          },
          columns: [{
            data: 'ReqNum',
            width: '100px'
          },
          {
            data: 'RequestDate',
            width: '150px'
          },
          {
            data: 'ReqLine',
            width: '100px'
          },
          {
            data: 'PartNum',
            width: '200px'
          },
          {
            data: 'LineDesc',
            width: '300px'
          },
          {
            data: 'OrderQty',
            width: '100px'
          },
          {
            data: 'IUM',
            width: '100px'
          },
          {
            data: 'VendorNum',
            width: '120px'
          },
          {
            data: 'VendorName',
            width: '300px'
          },
          {
            data: 'DaysOpen',
            width: '100px'
          },
          {
            data: 'Urgency',
            width: '250px'
          },
          ],
          createdRow: function (row, data, dataIndex) {
            if (dataIndex % 2 === 0) {
              $(row).css('background-color', '#1D1D1D');
            } else {
              $(row).css('background-color', '#2D2D2D');
            }
          },
        });

        const buttonContainer = document.createElement('div');
        buttonContainer.style.display = 'flex';
        buttonContainer.style.gap = '10px';
        buttonContainer.style.marginTop = '10px';
        buttonContainer.id = 'req_under_po_table_button_container';

        const downloadButton = document.createElement('button');
        downloadButton.id = 'download_req_under_po_table_button';
        downloadButton.className = 'btn btn-primary';
        downloadButton.innerHTML = `
            <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down-fill" viewBox="0 0 16 16" style="margin-right: 8px;">
                <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"/>
            </svg> Download Data
        `;

        buttonContainer.appendChild(downloadButton);

        const tableWrapper = document.querySelector('#req_under_po_table_wrapper');
        if (tableWrapper) {
          const existingContainer = document.getElementById('req_under_po_table_button_container');
          if (existingContainer) existingContainer.remove();
          tableWrapper.insertAdjacentElement('afterend', buttonContainer);
        } else {
          console.error('Table wrapper #req_under_po_table_wrapper not found');
        }

        downloadButton.addEventListener('click', function () {
          createDateRangePopup('req_under_po_table');
        });

        setTimeout(function () {
          detailTableUnderPO.ajax.reload();
        }, 100);

        return detailTableUnderPO;
      }

      function detail_table_tracking_req_po() {
        var selectDate = $('#select_date_tracking').val();
        if ($.fn.DataTable.isDataTable('#tracking_req_po_table')) {
          $('#tracking_req_po_table').DataTable().destroy();
        }

        detailTableTrackingPO = $("#tracking_req_po_table").DataTable({
          destroy: true,
          processing: true,
          serverSide: true,
          responsive: true,
          scrollX: true,
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
            url: `https://${window.location.host}/api/purchasing/get_data_purchasing_tracking_req_po_table`,
            type: 'POST',
            contentType: "application/json",
            data: function (d) {
              return JSON.stringify({
                ...d,
                date: selectDate
              });
            },
            cache: false,
            dataType: 'json'
          },
          columns: [{
            data: 'ReqNum',
            width: '100px'
          },
          {
            data: 'RequestDate',
            width: '150px'
          },
          {
            data: 'ReqLine',
            width: '100px'
          },
          {
            data: 'PartNum',
            width: '120px'
          },
          {
            data: 'ReqDescription',
            width: '300px'
          },
          {
            data: 'ReqQty',
            width: '100px'
          },
          {
            data: 'PONum',
            width: '120px'
          },
          {
            data: 'PODate',
            width: '150px'
          },
          {
            data: 'BuyerID',
            width: '120px'
          },
          {
            data: 'POLine',
            width: '100px'
          },
          {
            data: 'PODescription',
            width: '300px'
          },
          {
            data: 'PORelNum',
            width: '120px'
          },
          {
            data: 'PORelQty',
            width: '100px'
          },
          {
            data: 'DueDate',
            width: '150px'
          },
          {
            data: 'OpenRelease',
            width: '100px'
          },
          {
            data: 'VendorID',
            width: '120px'
          },
          {
            data: 'VendorName',
            width: '300px'
          },
          {
            data: 'Status',
            width: '120px'
          },
          ],
          createdRow: function (row, data, dataIndex) {
            if (dataIndex % 2 === 0) {
              $(row).css('background-color', '#1D1D1D');
            } else {
              $(row).css('background-color', '#2D2D2D');
            }
          },
        });

        const buttonContainer = document.createElement('div');
        buttonContainer.style.display = 'flex';
        buttonContainer.style.gap = '10px';
        buttonContainer.style.marginTop = '10px';
        buttonContainer.id = 'tracking_req_po_table_button_container';

        const downloadButton = document.createElement('button');
        downloadButton.id = 'download_tracking_req_po_table_button';
        downloadButton.className = 'btn btn-primary';
        downloadButton.innerHTML = `
            <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down-fill" viewBox="0 0 16 16" style="margin-right: 8px;">
                <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"/>
            </svg> Download Data
        `;

        buttonContainer.appendChild(downloadButton);

        const tableWrapper = document.querySelector('#tracking_req_po_table_wrapper');
        if (tableWrapper) {
          const existingContainer = document.getElementById('tracking_req_po_table_button_container');
          if (existingContainer) existingContainer.remove();
          tableWrapper.insertAdjacentElement('afterend', buttonContainer);
        } else {
          console.error('Table wrapper #tracking_req_po_table_wrapper not found');
        }

        downloadButton.addEventListener('click', function () {
          createDateRangePopup('tracking_req_po_table');
        });

        setTimeout(function () {
          detailTableTrackingPO.ajax.reload();
        }, 100);

        return detailTableTrackingPO;
      }
      function detail_table_matrics_po() {
        var selectDate = $('#select_date_matrics').val();
        if ($.fn.DataTable.isDataTable('#metrics_po_table')) {
          $('#metrics_po_table').DataTable().destroy();
        }

        detailTableMatricsPO = $("#metrics_po_table").DataTable({
          destroy: true,
          processing: true,
          serverSide: true,
          responsive: true,
          scrollX: true,
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
            url: `https://${window.location.host}/api/purchasing/get_data_purchasing_matrics_po_table`,
            type: 'POST',
            contentType: "application/json",
            data: function (d) {
              return JSON.stringify({
                ...d,
                date: selectDate
              });
            },
            cache: false,
            dataType: 'json'
          },
          columns: [{
            data: 'Year'
          },
          {
            data: 'Month'
          },
          {
            data: 'TotalRequisitions'
          },
          {
            data: 'ConvertedToPO'
          },
          {
            data: 'PendingPO'
          },
          {
            data: 'ConversionRate'
          },
          {
            data: 'AvgDaysToConvert'
          },
          ],
          createdRow: function (row, data, dataIndex) {
            if (dataIndex % 2 === 0) {
              $(row).css('background-color', '#1D1D1D');
            } else {
              $(row).css('background-color', '#2D2D2D');
            }
          },
        });

        const buttonContainer = document.createElement('div');
        buttonContainer.style.display = 'flex';
        buttonContainer.style.gap = '10px';
        buttonContainer.style.marginTop = '10px';
        buttonContainer.id = 'metrics_po_table_button_container';

        const downloadButton = document.createElement('button');
        downloadButton.id = 'download_metrics_po_table_button';
        downloadButton.className = 'btn btn-primary';
        downloadButton.innerHTML = `
            <svg xmlns="https://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down-fill" viewBox="0 0 16 16" style="margin-right: 8px;">
                <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"/>
            </svg> Download Data
        `;

        buttonContainer.appendChild(downloadButton);

        const tableWrapper = document.querySelector('#metrics_po_table_wrapper');
        if (tableWrapper) {
          const existingContainer = document.getElementById('metrics_po_table_button_container');
          if (existingContainer) existingContainer.remove();
          tableWrapper.insertAdjacentElement('afterend', buttonContainer);
        } else {
          console.error('Table wrapper #metrics_po_table_wrapper not found');
        }

        downloadButton.addEventListener('click', function () {
          createDateRangePopup('metrics_po_table');
        });

        setTimeout(function () {
          detailTableMatricsPO.ajax.reload();
        }, 100);

        return detailTableMatricsPO;
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

          if (tableType === 'req_po_status_table') {
            downloadPOStatusData(startDate, endDate);
          } else if (tableType === 'req_under_po_table') {
            downloadUnderPOData(startDate, endDate);
          } else if (tableType === 'tracking_req_po_table') {
            downloadTrackingPOData(startDate, endDate);
          } else if (tableType === 'metrics_po_table') {
            downloadMetricsPOData(startDate, endDate);
          }
          overlay.remove();
        });
      }

      function showLoadingOverlay() {
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
      }

      function hideLoadingOverlay() {
        const loading = document.getElementById('loadingOverlay');
        if (loading) {
          loading.remove();
        }
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

      function downloadPOStatusData(startDate, endDate) {
        showLoadingOverlay();
        const apiUrl = `https://${window.location.host}/api/purchasing/get_data_purchasing_req_po_status_table_export`;
        const postData = {
          startDate: startDate,
          endDate: endDate,
          status: globalSelectedStatus
        };

        axios.post(apiUrl, postData)
          .then(response => {
            const tableData = response.data.data;
            if (tableData && tableData.length > 0) {
              const excelContent = convertToExcel(tableData);
              const filename = `req_po_status_table_${startDate}_to_${endDate}.xlsx`;
              downloadExcel(excelContent, filename);
            } else {
              alert('No data available for the selected date range.');
            }
          })
          .catch(error => {
            alert('Failed to fetch data for download.');
            console.error('Error:', error);
          })
          .finally(() => {
            hideLoadingOverlay();
          });
      }

      function downloadUnderPOData(startDate, endDate) {
        showLoadingOverlay();
        const apiUrl = `https://${window.location.host}/api/purchasing/get_data_purchasing_req_under_po_table_export`;
        const postData = {
          startDate: startDate,
          endDate: endDate,
          status: globalSelectedStatus
        };

        axios.post(apiUrl, postData)
          .then(response => {
            const tableData = response.data.data;
            if (tableData && tableData.length > 0) {
              const excelContent = convertToExcel(tableData);
              const filename = `req_under_po_table_${startDate}_to_${endDate}.xlsx`;
              downloadExcel(excelContent, filename);
            } else {
              alert('No data available for the selected date range.');
            }
          })
          .catch(error => {
            alert('Failed to fetch data for download.');
            console.error('Error:', error);
          })
          .finally(() => {
            hideLoadingOverlay();
          });
      }

      function downloadTrackingPOData(startDate, endDate) {
        showLoadingOverlay();
        const apiUrl = `https://${window.location.host}/api/purchasing/get_data_purchasing_tracking_req_po_table_export`;
        const postData = {
          startDate: startDate,
          endDate: endDate
        };

        axios.post(apiUrl, postData)
          .then(response => {
            const tableData = response.data.data;
            if (tableData && tableData.length > 0) {
              const excelContent = convertToExcel(tableData);
              const filename = `tracking_req_po_table_${startDate}_to_${endDate}.xlsx`;
              downloadExcel(excelContent, filename);
            } else {
              alert('No data available for the selected date range.');
            }
          })
          .catch(error => {
            alert('Failed to fetch data for download.');
            console.error('Error:', error);
          })
          .finally(() => {
            hideLoadingOverlay();
          });
      }

      function downloadMetricsPOData(startDate, endDate) {
        showLoadingOverlay();
        const apiUrl = `https://${window.location.host}/api/purchasing/get_data_purchasing_matrics_po_table_export`;
        const postData = {
          startDate: startDate,
          endDate: endDate
        };

        axios.post(apiUrl, postData)
          .then(response => {
            const tableData = response.data.data;
            if (tableData && tableData.length > 0) {
              const excelContent = convertToExcel(tableData);
              const filename = `metrics_po_table_${startDate}_to_${endDate}.xlsx`;
              downloadExcel(excelContent, filename);
            } else {
              alert('No data available for the selected date range.');
            }
          })
          .catch(error => {
            alert('Failed to fetch data for download.');
            console.error('Error:', error);
          })
          .finally(() => {
            hideLoadingOverlay();
          });
      }

      function setDefaultData() {
        var currentHost = window.location.host;
        const selectMonth = document.getElementById('selectMonth').value;
        const selectMonthPO = document.getElementById('select_month_under_po').value;

        const apiUrlReceipt = `https://${currentHost}/api/purchasing/get_data_summary_status_req/${selectMonth}`;
        axios.get(apiUrlReceipt)
          .then(response => {
            var closed_without_po = response.data.closed_without_po;
            var open_pending_po = response.data.open_pending_po;
            var converted_to_po = response.data.converted_to_po;
            document.getElementById('inpt_data_close_without_po').value = closed_without_po;
            document.getElementById('inpt_data_open_pending_po').value = open_pending_po;
            document.getElementById('inpt_data_convert_to_po').value = converted_to_po;
            document.getElementById('btn_update_chart').click();
          });

        const UrlPipeline = `https://${currentHost}/api/purchasing/get_data_req_po_pipeline/${selectMonth}`;
        axios.get(UrlPipeline)
          .then(response => {
            var data_matric = response.data.data_matric;
            var data_value = response.data.data_value;
            document.getElementById('inpt_data_metric').value = data_matric;
            document.getElementById('inpt_data_value').value = data_value;
            document.getElementById('btn_update_pipeline').click();
          });

        const urlUnderPO = `https://${currentHost}/api/purchasing/get_data_req_under_po/${selectMonthPO}`;
        axios.get(urlUnderPO)
          .then(response => {
            var data_critical = response.data.data_critical;
            var data_warning = response.data.data_warning;
            var data_normal = response.data.data_normal;
            document.getElementById('inpt_data_critical').value = data_critical;
            document.getElementById('inpt_data_warning').value = data_warning;
            document.getElementById('inpt_data_normal').value = data_normal;
            document.getElementById('btn_update_under').click();
          });
      }

      detail_table_req_po_status();
      detail_table_req_under_po();
      detail_table_req_only_po_status();
      detail_table_tracking_req_po();
      detail_table_matrics_po();
      setDefaultData();

      document.getElementById('select_date').addEventListener('change', function () {
        detail_table_req_po_status();
        detail_table_req_only_po_status();
      });
      document.getElementById('select_date_matrics').addEventListener('change', function () {
        detail_table_matrics_po();
      });
      document.getElementById('select_month_under').addEventListener('change', function () {
        detail_table_req_under_po();
      });
      document.getElementById('select_date_tracking').addEventListener('change', function () {
        detail_table_tracking_req_po();
      });
      document.getElementById('selectMonth').addEventListener('change', function () {
        updateChartStatus();
        updateChartPipeline();
      });
      document.getElementById('select_month_under_po').addEventListener('change', function () {
        updateChartRequisition();
      });
    });

    function updateChartStatus() {
      var currentHost = window.location.host;
      const selectMonth = document.getElementById('selectMonth').value;
      const apiUrlReceipt = `https://${currentHost}/api/purchasing/get_data_summary_status_req/${selectMonth}`;
      axios.get(apiUrlReceipt)
        .then(response => {
          var closed_without_po = response.data.closed_without_po;
          var open_pending_po = response.data.open_pending_po;
          var converted_to_po = response.data.converted_to_po;
          document.getElementById('inpt_data_close_without_po').value = closed_without_po;
          document.getElementById('inpt_data_open_pending_po').value = open_pending_po;
          document.getElementById('inpt_data_convert_to_po').value = converted_to_po;
          document.getElementById('btn_update_chart').click();
        })
        .catch(error => {
          console.error('Error fetching default data:', error);
        });
    }

    function updateChartPipeline() {
      var currentHost = window.location.host;
      const selectMonth = document.getElementById('selectMonth').value;
      const UrlPipeline = `https://${currentHost}/api/purchasing/get_data_req_po_pipeline/${selectMonth}`;
      axios.get(UrlPipeline)
        .then(response => {
          var data_matric = response.data.data_matric;
          var data_value = response.data.data_value;
          document.getElementById('inpt_data_metric').value = data_matric;
          document.getElementById('inpt_data_value').value = data_value;
          document.getElementById('btn_update_pipeline').click();
        });
    }

    function updateChartRequisition() {
      var currentHost = window.location.host;
      const selectMonth = document.getElementById('select_month_under_po').value;
      const urlUnderPO = `https://${currentHost}/api/purchasing/get_data_req_under_po/${selectMonth}`;
      axios.get(urlUnderPO)
        .then(response => {
          var data_critical = response.data.data_critical;
          var data_warning = response.data.data_warning;
          var data_normal = response.data.data_normal;
          document.getElementById('inpt_data_critical').value = data_critical;
          document.getElementById('inpt_data_warning').value = data_warning;
          document.getElementById('inpt_data_normal').value = data_normal;
          document.getElementById('btn_update_under').click();
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
        ChartRecall1(closeData, openData, convertData) {
          const close = closeData.split(',').map(Number);
          const open = openData.split(',').map(Number);
          const convert = convertData.split(',').map(Number);

          this.BoxChart2.updateSeries([{
            name: 'Closed without PO',
            type: 'bar',
            data: close
          },
          {
            name: 'Open - Pending PO',
            type: 'bar',
            data: open
          },
          {
            name: 'Converted to PO',
            type: 'bar',
            data: convert
          },
          ]);

          this.BoxChart2.updateOptions({
            chart: {
              events: {
                dataPointSelection: (event, chartContext, config) => {
                  const seriesName = config.w.config.series[config.seriesIndex].name;
                  globalSelectedStatus = seriesName;
                  if (detailTablePOStatus) {
                    detailTablePOStatus.ajax.reload();
                  } else {
                    console.error('detailTablePOStatus is not initialized');
                  }
                }
              }
            },
          });
        },

        ChartRecall2(dataMetric, dataValue) {
          const matric = dataMetric.split(',');
          const value = dataValue.split(',').map(Number);

          this.BoxChart3.updateSeries([{
            name: 'Total Value',
            type: 'bar',
            data: value
          }]);
          this.BoxChart3.updateOptions({
            xaxis: {
              categories: matric,
            },
          });
        },

        ChartRecall3(dataCritical, dataWarning, dataNormal) {
          const critical = dataCritical.split(',').map(Number);
          const warning = dataWarning.split(',').map(Number);
          const normal = dataNormal.split(',').map(Number);

          this.BoxChart4.updateSeries([{
            name: 'Critical - Over 30 days',
            type: 'bar',
            data: critical
          },
          {
            name: 'Warning - Over 14 days',
            type: 'bar',
            data: warning
          },
          {
            name: 'Normal',
            type: 'bar',
            data: normal
          },
          ]);
          this.BoxChart4.updateOptions({
            chart: {
              events: {
                dataPointSelection: (event, chartContext, config) => {
                  const seriesName = config.w.config.series[config.seriesIndex].name;
                  globalSelectedStatus = seriesName;
                  if (detailTableUnderPO) {
                    detailTableUnderPO.ajax.reload();
                  } else {
                    console.error('detailTableUnderPO is not initialized');
                  }
                }
              }
            },
          });
        },

        renderCharts() {
          this.BoxChart2 = new ApexCharts(this.$refs.BoxChart2, this.BoxChart2Options);
          this.BoxChart2.render();

          this.BoxChart3 = new ApexCharts(this.$refs.BoxChart3, this.BoxChart3Options);
          this.BoxChart3.render();

          this.BoxChart4 = new ApexCharts(this.$refs.BoxChart4, this.BoxChart4Options);
          this.BoxChart4.render();
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
            stroke: {
              width: [0, 0, 0],
              curve: 'smooth'
            },
            dataLabels: {
              enabled: true,
              position: "top",
              style: {
                fontSize: "12px",
              },
            },
            colors: ['#2A9D8F', '#F4A261', '#c026d1'],
            plotOptions: {
              bar: {
                horizontal: false,
                columnWidth: '90%',
                endingShape: 'rounded',
                dataLabels: {
                  position: "top",
                }
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
              categories: ['Total Requisitions'],
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
                formatter: (value) => {
                  if (value >= 1000000) return (Math.floor(value / 100000000) / 10).toFixed(0) + "B";
                  if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(0) + "M";
                  if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(0) + "K";
                  return value;
                }
              },
              forceNiceScale: true
            },
            tooltip: {
              shared: false,
              intersect: false,
              y: {
                formatter: (value) => {
                  return new Intl.NumberFormat('id-ID', {}).format(value);
                }
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
                opacityTo: 0.3,
                stops: [0, 100]
              }
            }
          };
        },
        get BoxChart3Options() {
          return {
            series: [],
            chart: {
              type: 'bar',
              height: 360,
              fontFamily: 'Nunito, sans-serif',
              toolbar: {
                show: false
              },
              zoom: {
                enabled: false
              },
            },
            stroke: {
              curve: 'smooth'
            },
            dataLabels: {
              enabled: true,
              position: "top",
              offsetY: -25,
              style: {
                fontSize: "12px",
              },
            },
            colors: ['#2A9D8F', '#F4A261', '#c026d1'],
            plotOptions: {
              bar: {
                horizontal: false,
                columnWidth: '90%',
                endingShape: 'rounded',
                dataLabels: {
                  position: "top",
                }
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
              categories: [],
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
                formatter: (value) => {
                  if (value >= 1000000) return (Math.floor(value / 100000000) / 10).toFixed(0) + "B";
                  if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(0) + "M";
                  if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(0) + "K";
                  return value;
                }
              },
              forceNiceScale: true
            },
            tooltip: {
              shared: true,
              intersect: false,
              y: {
                formatter: (value) => {
                  return new Intl.NumberFormat('id-ID', {}).format(value);
                }
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
                opacityTo: 0.3,
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
              },
              zoom: {
                enabled: false
              },
            },
            stroke: {
              width: [0, 0, 0],
              curve: 'smooth'
            },
            dataLabels: {
              enabled: true,
              position: "top",
              style: {
                fontSize: "12px",
              },
            },
            colors: ['#2A9D8F', '#F4A261', '#c026d1'],
            plotOptions: {
              bar: {
                horizontal: false,
                columnWidth: '90%',
                endingShape: 'rounded',
                dataLabels: {
                  position: "top",
                }
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
              categories: ['Total Requisitions'],
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
                formatter: (value) => {
                  if (value >= 1000000) return (Math.floor(value / 100000000) / 10).toFixed(0) + "B";
                  if (value >= 1000000) return (Math.floor(value / 100000) / 10).toFixed(0) + "M";
                  if (value >= 1000) return (Math.floor(value / 100) / 10).toFixed(0) + "K";
                  return value;
                }
              },
              forceNiceScale: true
            },
            tooltip: {
              shared: false,
              y: {
                formatter: (value) => {
                  return new Intl.NumberFormat('id-ID', {}).format(value);
                }
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
                opacityTo: 0.3,
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
