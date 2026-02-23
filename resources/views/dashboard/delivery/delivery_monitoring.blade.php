<x-layout.default>
  <script defer src="/assets/js/apexcharts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
  <style>
    #delivery_tabel tbody tr:hover,
    #delivery_tabel2 tbody tr:hover,
    #delivery_tabel3 tbody tr:hover {
      background-color: #727e93;
      cursor: pointer;
    }

    #delivery_tabel tbody tr,
    #delivery_tabel2 tbody tr,
    #delivery_tabel3 tbody tr {
      background-color: #4B5563;
      color: whitesmoke;
      cursor: pointer;
      font-size: small;
    }

    #delivery_tabel thead tr,
    #delivery_tabel2 thead tr,
    #delivery_tabel3 thead tr {
      color: whitesmoke;
    }

    #delivery_tabel td,
    #delivery_tabel2 td,
    #delivery_tabel3 td {
      white-space: nowrap !important;
      overflow: hidden;
      text-overflow: ellipsis;
    }


    #month_year::-webkit-calendar-picker-indicator {
      filter: invert(1);
    }
  </style>

  <style>
    select[name="delivery_tabel_length"],
    select[name="delivery_tabel2_length"],
    select[name="delivery_tabel3_length"] {
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
        <span>Delivery Monitoring</span>
      </li>
    </ul>
    <div class="pt-5">
      <div class="grid sm:grid-cols-2 xl:grid-cols-12 gap-6 mb-6">
        <?php
$currentYear = date('Y');
$startYear = $currentYear - 5;
$currentMonth = date('Y-m');
        ?>
        <div class="panel h-full sm:col-span-12 xl:col-span-12">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 dark:text-white-light">
            <div>
              <label for="cust_month">Customer</label>
              <select class="form-input" name="cust_month" id="cust_month">
                <option value="MMKI">MMKI</option>
                <option value="HPM">HPM</option>
                <option value="MMKSI">MMKSI</option>
                <option value="MKM">MKM</option>
                <option value="SIS">SIS</option>
                <option value="SIM">SIM</option>
                <option value="IAMI">IAMI</option>
                <option value="TMMIN">TMMIN</option>
                <option value="UDAMI">UDAMI</option>
              </select>
            </div>
          </div>
          <hr>
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn_update_chart"
                x-on:click="() => ChartRecall1(inpt_data_po.value, inpt_data_del.value, inpt_data_cycle.value)"
                hidden>Recall and Update
              </button>
              <input type="text" id="inpt_data_po" value="" hidden>
              <input type="text" id="inpt_data_del" value="" hidden>
              <input type="text" id="inpt_data_cycle" value="" hidden>
              <div x-ref="BoxChart2" class="overflow-hidden"></div>
            </div>
          </div>
        </div>
        <!-- Table -->
        <div class="panel h-full col-span-6">
          <div class="relative overflow-hidden pt-5">
            <table id="delivery_tabel" class="min-w-full rounded-md shadow-md overflow-hidden">
              <thead>
                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                  <th class="py-3 px-6 text-left">Customer</th>
                  <th class="py-3 px-6 text-left">Part Num</th>
                  <th class="py-3 px-6 text-left">Part Desc</th>
                  <th class="py-3 px-6 text-left">OnHand</th>
                  <th class="py-3 px-6 text-left">PO</th>
                  <th class="py-3 px-6 text-left">ActDel</th>
                  <th class="py-3 px-6 text-left">Cycle</th>
                  <th class="py-3 px-6 text-left">Status</th>
                  <th class="py-3 px-6 text-left">Time</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="panel h-full col-span-6">
          <div class="relative overflow-hidden pt-5">
            <table id="delivery_tabel2" class="min-w-full rounded-md shadow-md overflow-hidden">
              <thead>
                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                  <th class="py-3 px-6 text-left">Customer</th>
                  <th class="py-3 px-6 text-left">Part Num</th>
                  <th class="py-3 px-6 text-left">Part Desc</th>
                  <th class="py-3 px-6 text-left">PO</th>
                  <th class="py-3 px-6 text-left">ActDel</th>
                  <th class="py-3 px-6 text-left">Cycle</th>
                  <th class="py-3 px-6 text-left">Status</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="panel h-full col-span-6">
          <div class="relative overflow-hidden pt-5">
            <table id="delivery_tabel3" class="min-w-full rounded-md shadow-md overflow-hidden">
              <thead>
                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                  <th class="py-3 px-6 text-left">Customer</th>
                  <th class="py-3 px-6 text-left">Part Num</th>
                  <th class="py-3 px-6 text-left">Part Name</th>
                  <th class="py-3 px-6 text-left">OnHand</th>
                  <th class="py-3 px-6 text-left">Min</th>
                  <th class="py-3 px-6 text-left">Max</th>
                  <th class="py-3 px-6 text-left">Status</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="panel h-full sm:col-span-6 xl:col-span-6">
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn_monitoring_update_chart"
                x-on:click="() => ChartRecall2(inpt_data_critikal.value, inpt_data_safe.value, inpt_data_over.value)"
                hidden>Recall and Update
              </button>
              <input type="text" id="inpt_data_critikal" value="" hidden>
              <input type="text" id="inpt_data_safe" value="" hidden>
              <input type="text" id="inpt_data_over" value="" hidden>
              <div x-ref="BoxChart3" class="overflow-hidden"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      function detail_table_delivery() {
        let selectCust = document.getElementById('cust_month')?.value;
        var detailTable = $("#delivery_tabel").DataTable({
          destroy: true,
          processing: true,
          serverSide: true,
          responsive: false,
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
            url: `https://${window.location.host}/api/delivery/get_control_delivery_table/${selectCust}`,
            type: 'POST',
            contentType: "application/json",
            data: function (d) {
              let selectCust = document.getElementById('cust_month').value;
              if (selectCust) {
                let parts = selectCust.split("-");
                d.year = `${parts[0]}`;
              }
              return JSON.stringify(d);
            },
            cache: false,
            dataType: 'json'
          },
          columns: [{
            data: 'Customer'
          },
          {
            data: 'PartNum',
          },
          {
            data: 'PartDesc',
          },
          {
            data: 'OnHand',
          },
          {
            data: 'PO',
          },
          {
            data: 'ActDel',
          },
          {
            data: 'Cycle',
          },
          {
            data: 'Status',
          },
          {
            data: 'Time',
          },
          ],
          createdRow: function (row, data, dataIndex) {
            if (dataIndex % 2 === 0) {
              $(row).css('background-color', '#1D1D1D');
            } else {
              $(row).css('background-color', '#2D2D2D');
            }
          }
        });

        setTimeout(function () {
          detailTable.ajax.reload();
        }, 500);

        return detailTable;
      }

      function detail_table_delivery_summary() {
        let selectCust = document.getElementById('cust_month')?.value;
        var detailTable = $("#delivery_tabel2").DataTable({
          destroy: true,
          processing: true,
          serverSide: true,
          responsive: false,
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
            url: `https://${window.location.host}/api/delivery/get_control_delivery_table_summary/${selectCust}`,
            type: 'POST',
            contentType: "application/json",
            data: function (d) {
              let selectCust = document.getElementById('cust_month').value;
              if (selectCust) {
                let parts = selectCust.split("-");
                d.year = `${parts[0]}`;
              }
              return JSON.stringify(d);
            },
            cache: false,
            dataType: 'json'
          },
          columns: [{
            data: 'Customer'
          },
          {
            data: 'PartNum',
          },
          {
            data: 'PartDesc',
          },
          {
            data: 'PO',
          },
          {
            data: 'ActDel',
          },
          {
            data: 'Cycle',
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
          }
        });

        setTimeout(function () {
          detailTable.ajax.reload();
        }, 500);

        return detailTable;
      }

      function detail_table_delivery_min_max_summary() {
        let selectCust = document.getElementById('cust_month')?.value;
        var detailTable = $("#delivery_tabel3").DataTable({
          destroy: true,
          processing: true,
          serverSide: true,
          responsive: false,
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
            url: `https://${window.location.host}/api/delivery/get_control_delivery_min_max_summary`,
            type: 'POST',
            contentType: "application/json",
            data: function (d) {
              return JSON.stringify(d);
            },
            cache: false,
            dataType: 'json'
          },
          columns: [{
            data: 'Customer'
          },
          {
            data: 'PartNum',
          },
          {
            data: 'PartName',
          },
          {
            data: 'OnHand',
          },
          {
            data: 'Min',
          },
          {
            data: 'Max',
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
          }
        });

        setTimeout(function () {
          detailTable.ajax.reload();
        }, 500);

        return detailTable;
      }

      function setDefaultData() {
        function fetchData(url, callback) {
          axios.get(url)
            .then(response => callback(response.data))
        }
        var currentHost = window.location.host;
        var customer = document.getElementById('cust_month').value;

        const apiUrlMonthly = `https://${currentHost}/api/delivery/det_moitoring_control_delivery/${customer}`;
        axios.get(apiUrlMonthly)
          .then(response => {
            var data_po = response.data.data_po;
            var data_act_del = response.data.data_act_del;
            var data_cycle = response.data.data_cycle;
            document.getElementById('inpt_data_po').value = data_po;
            document.getElementById('inpt_data_del').value = data_act_del;
            document.getElementById('inpt_data_cycle').value = data_cycle;
            document.getElementById('btn_update_chart').click();
          })


        const apiUrl = `https://${currentHost}/api/delivery/get_stok_monitoring`;
        fetchData(apiUrl, data => {
          let dataMonitor = Array.isArray(data) ? data[0] : data;

          document.getElementById('inpt_data_critikal').value = dataMonitor?.data_critikal || 0;
          document.getElementById('inpt_data_over').value = dataMonitor?.data_over || 0;
          document.getElementById('inpt_data_safe').value = dataMonitor?.data_save || 0;
          document.getElementById('btn_monitoring_update_chart').click();
        });

      }
      setDefaultData()
      detail_table_delivery();
      detail_table_delivery_summary();
      detail_table_delivery_min_max_summary();

      document.getElementById('cust_month').addEventListener('change', function () {
        updateDailyChart();
        detail_table_delivery();
        detail_table_delivery_summary();
      });

      function updateDailyChart() {

        var currentHost = window.location.host;
        var yearX = document.getElementById('cust_month').value;

        const apiUrlMonthly = `https://${currentHost}/api/delivery/det_moitoring_control_delivery/${yearX}`;
        axios.get(apiUrlMonthly)
          .then(response => {
            var data_po = response.data.data_po;
            var data_act_del = response.data.data_act_del;
            var data_cycle = response.data.data_cycle;
            document.getElementById('inpt_data_po').value = data_po;
            document.getElementById('inpt_data_del').value = data_act_del;
            document.getElementById('inpt_data_cycle').value = data_cycle;
            document.getElementById('btn_update_chart').click();
          })

        const apiMonitoring = `https://${currentHost}/api/delivery/get_stok_monitoring`;
        axios.get(apiMonitoring)
          .then(response => {
            var data_critikal = response.data.data_critikal;
            var data_over = response.data.data_over;
            var data_save = response.data.data_save;
            document.getElementById('inpt_data_critikal').value = data_critikal;
            document.getElementById('inpt_data_over').value = data_over;
            document.getElementById('inpt_data_safe').value = data_save;
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
        ChartRecall1(dataPO, actDelData, dataCategory) {
          const po = dataPO.split(',').map(Number);
          const actDel = actDelData.split(',').map(Number);
          const category = dataCategory.split(',');

          this.BoxChart2.updateSeries([{
            name: 'PO',
            data: po
          },
          {
            name: 'Act Del',
            data: actDel
          },
          ]);
          this.BoxChart2.updateOptions({
            xaxis: {
              categories: category,
              axisBorder: {
                show: true,
                color: '#3b3f5c'
              },
              axisTicks: {
                show: 200
              }
            },
            dataLabels: {
              enabled: true,
              style: {
                fontSize: '12px'
              },
            },
          });
        },

        ChartRecall2(dataCritical, dataSafe, dataOver) {
          const critical = dataCritical.split(',').map(Number);
          const safe = dataSafe.split(',').map(Number);
          const over = dataOver.split(',').map(Number);


          const actual = [critical[0] || 0, over[0] || 0, safe[0] || 0];

          this.BoxChart3.updateSeries(actual);

        },

        renderCharts() {
          this.BoxChart2 = new ApexCharts(this.$refs.BoxChart2, this.BoxChart2Options);
          this.BoxChart2.render();
          this.BoxChart3 = new ApexCharts(this.$refs.BoxChart3, this.BoxChart3Options);
          this.BoxChart3.render();

        },

        get BoxChart2Options() {
          return {
            series: [{
              name: [],
              type: 'bar',
              data: []
            },
            {
              name: [],
              type: 'bar',
              data: []
            }
            ],
            chart: {
              height: 360,
              fontFamily: 'Nunito, sans-serif',
              toolbar: {
                show: false
              }
            },
            stroke: {
              width: [0, 0],
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
        get BoxChart3Options() {
          return {
            series: [],
            chart: {
              type: 'pie',
              height: 400,
              fontFamily: 'Nunito, sans-serif',
              toolbar: {
                show: false
              },
              offsetY: 50,
              animations: {
                enabled: true,
                easing: 'easeout',
                speed: 800,
                animateGradually: {
                  enabled: true,
                  delay: 300
                },
                dynamicAnimation: {
                  enabled: true,
                  speed: 500
                }
              }
            },
            colors: ['#E63946', '#F4A261', '#2A9D8F'],
            labels: ['Critical', 'Over', 'Safe'],
            stroke: {
              show: false,
            },
            dropShadow: {
              enabled: true,
              blur: 3,
              color: '#515365',
              opacity: 0.4
            },
            legend: {
              position: 'top',
              horizontalAlign: 'center',
              fontSize: '14px',
              itemMargin: {
                horizontal: 15,
                vertical: 30
              }
            },
            tooltip: {
              y: {

              }
            },
            dataLabels: {
              enabled: true,
              style: {
                fontSize: '12px',
              },
              fontSize: '20px',
              formatter: (value) => `${Math.round(value)}%`
            },
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
