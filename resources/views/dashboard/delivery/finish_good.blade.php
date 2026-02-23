<x-layout.default>
  <script defer src="/assets/js/apexcharts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
  <style>
    #stock_finish_good_table tbody tr:hover {
      background-color: #727e93;
      cursor: pointer;
    }

    #stock_finish_good_table tbody tr {
      background-color: #4B5563;
      color: whitesmoke;
      cursor: pointer;
      font-size: small;
    }

    #stock_finish_good_table thead tr {
      color: whitesmoke;
    }

    #stock_finish_good_table::-webkit-calendar-picker-indicator {
      filter: invert(1);
    }

    select[name="stock_finish_good_table_length"] {
      width: 80px;
    }
  </style>
  <div x-data="analytics">
    <ul class="flex space-x-2 rtl:space-x-reverse">
      <li>
        <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
      </li>
      <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
        <span>Delivery</span>
      </li>
      <li class="before:content-['/'] before:mr-1 rtl:before:ml-1">
        <span>Finish Good Monitoring</span>
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
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-3">
              <label for="monitring_stock_good" class="text-xl font-bold">Monitoring Stock All Customer
              </label>
              <span></span>
            </div>
          </div>
          <hr>
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn_update_chart"
                x-on:click="() => ChartRecall1(inpt_data_customer.value, inpt_data_critical_stock.value, inpt_data_safe_stock.value, inpt_data_over_stock.value)"
                hidden>Recall and Update</button>
              <input type="text" id="inpt_data_customer" value="" hidden>
              <input type="text" id="inpt_data_critical_stock" value="" hidden>
              <input type="text" id="inpt_data_safe_stock" value="" hidden>
              <input type="text" id="inpt_data_over_stock" value="" hidden>
              <div x-ref="BoxChart1" class="overflow-hidden"></div>
            </div>
          </div>
        </div>
        <div class="panel h-full col-span-12 ">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-5 dark:text-white-light">
            <div>
              <label for="month_year_table" hidden>Month</label>
              <input id="month_year_table" type="date" class="form-input" value="2025-02-26" style="color: white;"
                hidden>
              <input type="text" id="selected_warehouse" value="" hidden>
            </div>
          </div>
          <hr hidden>
          <div class="relative overflow-hidden pt-5">
            <table id="stock_finish_good_table" class="min-w-full rounded-md shadow-md overflow-hidden">
              <thead>
                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                  <th class="py-3 px-6 text-left">Customer</th>
                  <th class="py-3 px-6 text-left">Critical Stock</th>
                  <th class="py-3 px-6 text-left">Safe Stock</th>
                  <th class="py-3 px-6 text-left">Over Stock</th>
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
        detailTable = $("#stock_finish_good_table").DataTable({
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
            url: `https://${window.location.host}/api/delivery/get_stock_monitoring_table`,
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
            data: 'CriticalStock',
          },
          {
            data: 'SafeStock',
          },
          {
            data: 'OverStock',
          },
          ],
          createdRow: function (row, data, dataIndex) {
            $(row).css('background-color', dataIndex % 2 === 0 ? '#1D1D1D' : '#2D2D2D');

            var api = this.api();
            var columnIndex = api.columns().indexes().filter(function (index) {
              return api.column(index).header().textContent.trim() === 'Customer';
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

        const apiUrlStock = `https://${currentHost}/api/delivery/get_data_finish_good`;
        axios.get(apiUrlStock)
          .then(response => {
            var data_customer = response.data.data_customer;
            var data_critical_stock = response.data.data_critical_stock;
            var data_safe_stock = response.data.data_safe_stock;
            var data_over_stock = response.data.data_over_stock;
            document.getElementById('inpt_data_customer').value = data_customer;
            document.getElementById('inpt_data_critical_stock').value = data_critical_stock;
            document.getElementById('inpt_data_safe_stock').value = data_safe_stock;
            document.getElementById('inpt_data_over_stock').value = data_over_stock;
            document.getElementById('btn_update_chart').click();
          })
      }
      setDefaultData();
    });

    var currentHost = window.location.host;

    function fetchData(url, callback) {
      axios.get(url)
        .then(response => callback(response.data))
    }

    document.addEventListener("alpine:init", () => {
      Alpine.data("analytics", () => ({
        data: {
          analytics: "Initial Data"
        },
        formatNumber(value) {
          return value;
        },
        ChartRecall1(dataCustomer, dataCriticalStock, dataSafeStock, dataOverStock) {

          const customer = dataCustomer.split(',');
          const criticalStock = dataCriticalStock.split(',').map(Number);
          const safe = dataSafeStock.split(',').map(Number);
          const over = dataOverStock.split(',').map(Number);
          this.BoxChart1.updateSeries([{
            name: 'Critical Stock',
            type: 'bar',
            data: criticalStock
          },
          {
            name: 'Safe Stock',
            type: 'bar',
            data: safe
          },
          {
            name: 'Over Stock',
            type: 'bar',
            data: over
          }
          ]);
          this.BoxChart1.updateOptions({
            xaxis: {
              categories: customer,
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

          this.BoxChart1 = new ApexCharts(this.$refs.BoxChart1, this.BoxChart1Options);
          this.BoxChart1.render();
        },

        get BoxChart1Options() {
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
              colors: ['#E63946', '#2A9D8F', '#F4A261'],
            },
            colors: ['#E63946', '#2A9D8F', '#F4A261'],
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
