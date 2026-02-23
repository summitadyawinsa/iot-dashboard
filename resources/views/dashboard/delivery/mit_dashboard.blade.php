<x-layout.default>
  <script defer src="/assets/js/apexcharts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
  <style>
    #received_project_table tbody tr:hover {
      background-color: #727e93;
      cursor: pointer;
    }

    #received_project_table tbody tr {
      background-color: #4B5563;
      color: whitesmoke;
      cursor: pointer;
      font-size: small;
    }

    #received_project_table thead tr {
      color: whitesmoke;
    }

    #select_years::-webkit-calendar-picker-indicator,
    #select_date::-webkit-calendar-picker-indicator {
      filter: invert(1);
    }
  </style>

  <style>
    select[name="received_project_table_length"] {
      width: 80px;
    }
  </style>
  <div x-data="analytics()">
    <ul class="flex space-x-2 rtl:space-x-reverse">
      <li>
        <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
      </li>
      <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
        <span>Delivery</span>
      </li>
      <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
        <span>MIT Dashboard</span>
      </li>
    </ul>
    <div class="pt-5">
      <div class="grid sm:grid-cols-2 xl:grid-cols-12 gap-6 mb-6">
        <?php
$currentYear = date('Y');
$startYear = $currentYear - 5;
$currentMonth = date('Y-m');
$currentDate = date('Y-m-d');
        ?>

        <div class="panel h-full sm:col-span-12 xl:col-span-12">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-3">
              <span class="text-xl font-bold">MIT Dashboard</span>
              <span></span>
              <input id="select_years" type="month" class="form-input" value="<?= $currentMonth ?>"
                style="color: white;" />
            </div>
          </div>
          <hr>
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn_update_chart"
                x-on:click="() => ChartRecall1(inpt_data_date.value, inpt_data_released.value, inpt_data_received.value)"
                hidden>Recall and Update
              </button>
              <input type="text" id="inpt_data_date" value="" hidden>
              <input type="text" id="inpt_data_released" value="" hidden>
              <input type="text" id="inpt_data_received" value="" hidden>
              <div x-ref="BoxChart2" class="overflow-hidden"></div>
            </div>
          </div>
        </div>
        <div class="panel h-full col-span-12 ">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-3">
              <label for="select_date" class="text-xl font-bold">MIT Dashboard</label>
              <span></span>
              <input id="select_date" type="date" class="form-input" value="<?= $currentDate ?>" style="color: white;">
            </div>
          </div>
          <hr hidden>
          <div class="relative overflow-hidden pt-5">
            <table id="received_project_table" class="min-w-full rounded-md shadow-md overflow-hidden">
              <thead>
                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                  <th class="py-3 px-6 text-left">Date</th>
                  <th class="py-3 px-6 text-left">MIT</th>
                  <th class="py-3 px-6 text-left">Part Number</th>
                  <th class="py-3 px-6 text-left">Qty</th>
                  <th class="py-3 px-6 text-left">Class ID</th>
                  <th class="py-3 px-6 text-left">Received</th>
                  <th class="py-3 px-6 text-left">Status</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function () {



      function detail_table() {
        detailTable = $("#received_project_table").DataTable({
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
            url: `https://${window.location.host}/api/delivery/get_data_mit_dashboard_table`,
            type: 'POST',
            contentType: "application/json",
            data: function (d) {
              let selectedDate = document.getElementById('select_date').value;
              if (selectedDate) {
                d.date_select = selectedDate;
              }
              return JSON.stringify(d);
            },
            cache: false,
            dataType: 'json'
          },
          columns: [{
            data: 'Date'
          },
          {
            data: 'MIT',
          },
          {
            data: 'PartNumber',
          },
          {
            data: 'Qty',
          },
          {
            data: 'ClassID',
          },
          {
            data: 'Received',
          },
          {
            data: 'Status',
          },
          ],
          createdRow: function (row, data, dataIndex) {
            $(row).css('background-color', dataIndex % 2 === 0 ? '#1D1D1D' : '#2D2D2D');

            var api = this.api();
            var columnIndex = api.columns().indexes().filter(function (index) {
              return api.column(index).header().textContent.trim() === 'OrderDate';
            })[0];
          }
        });
        setTimeout(function () {
          detailTable.ajax.reload();
        }, 100);

        return detailTable;
      }
      detail_table();

      function setDefaultData() {
        var currentHost = window.location.host;
        var years = document.getElementById('select_years').value;
        const apiUrlDaily = `https://${currentHost}/api/delivery/get_data_mit_dashboard/${years}`;
        axios.get(apiUrlDaily)
          .then(response => {
            var data_date = response.data.data_date;
            var data_released = response.data.data_released;
            var data_received = response.data.data_received;
            document.getElementById('inpt_data_date').value = data_date;
            document.getElementById('inpt_data_released').value = data_released;
            document.getElementById('inpt_data_received').value = data_received;
            document.getElementById('btn_update_chart').click();
          })
      }
      setDefaultData()

      document.getElementById('select_years').addEventListener('change', function () {
        updateDailyChart();
      });
      document.getElementById('select_date').addEventListener('change', function () {
        detail_table();
      });

      function updateDailyChart() {
        var currentHost = window.location.host;
        var years = document.getElementById('select_years').value;
        const apiUrlMonthly = `https://${currentHost}/api/delivery/get_data_mit_dashboard/${years}`;
        axios.get(apiUrlMonthly)
          .then(response => {
            var data_date = response.data.data_date;
            var data_released = response.data.data_released;
            var data_received = response.data.data_received;
            document.getElementById('inpt_data_date').value = data_date;
            document.getElementById('inpt_data_released').value = data_released;
            document.getElementById('inpt_data_received').value = data_received;
            document.getElementById('btn_update_chart').click();
          })
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
        ChartRecall1(dataDate, dataReleased, dataReceived) {
          const date = dataDate.split(',').map(Number);
          const released = dataReleased.split(',').map(Number);
          const received = dataReceived.split(',').map(Number);

          this.BoxChart2.updateSeries([{
            name: 'Released',
            type: 'bar',
            data: released
          },
          {
            name: 'Received',
            type: 'bar',
            data: received
          },
          ]);
          this.BoxChart2.updateOptions({
            xaxis: {
              categories: date,
              axisBorder: {
                show: true,
                color: '#3b3f5c'
              },
              axisTicks: {
                show: 200
              }
            },
          });
        },

        renderCharts() {
          this.BoxChart2 = new ApexCharts(this.$refs.BoxChart2, this.BoxChart2Options);
          this.BoxChart2.render();
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
              colors: ['#2a9d8f', '#f4a261'],
            },
            colors: ['#2a9d8f', '#f4a261'],
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
                formatter: (value) => this.formatNumber(value)
              },
              forceNiceScale: true
            },
            tooltip: {
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
