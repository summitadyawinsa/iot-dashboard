<x-layout.default>
  <script defer src="/assets/js/apexcharts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
  <style>
    #delivery_tabel tbody tr:hover {
      background-color: #727e93;
      cursor: pointer;
    }

    #delivery_tabel tbody tr {
      background-color: #4B5563;
      color: whitesmoke;
      cursor: pointer;
      font-size: small;
    }

    #delivery_tabel thead tr {
      color: whitesmoke;
    }

    #select_month::-webkit-calendar-picker-indicator,
    #month_year_table::-webkit-calendar-picker-indicator,
    #day_select::-webkit-calendar-picker-indicator {
      filter: invert(1);
    }
  </style>

  <style>
    select[name="delivery_tabel_length"] {
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
        <span>Job Monitoring</span>
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
        <div class="panel h-full sm:col-span-6 xl:col-span-6">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-2">
              <span class="text-xl font-bold">Job For FG Monthly</span>
              <input id="select_month" type="month" class="form-input" value="<?= $currentMonth ?>"
                style="color: white;" />
            </div>
          </div>
          <hr>
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn_update_months"
                x-on:click="() => ChartRecall2(inpt_data_job_month_open.value, inpt_data_job_month_close.value)"
                hidden>Recall and Update
              </button>
              <input type="text" id="inpt_data_job_month_open" value="" hidden>
              <input type="text" id="inpt_data_job_month_close" value="" hidden>
              <div x-ref="BoxChart3" class="overflow-hidden"></div>
            </div>
          </div>
        </div>
        <!-- Persentase -->
        <div class="panel h-full sm:col-span-6 xl:col-span-6">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-2">
              <span class="text-xl font-bold">Job For FG Monthly Percent</span>
              <input id="select_month" type="month" class="form-input" value="<?= $currentMonth ?>"
                style="color: white;" / hidden>
            </div>
          </div>
          <hr>
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn_update_months_per"
                x-on:click="() => ChartRecall3(inpt_data_job_month_open_per.value, inpt_data_job_month_close_per.value)"
                hidden>Recall and Update
              </button>
              <input type="text" id="inpt_data_job_month_open_per" value="" hidden>
              <input type="text" id="inpt_data_job_month_close_per" value="" hidden>
              <div x-ref="BoxChart4" class="overflow-hidden"></div>
            </div>
          </div>
        </div>
        <!-- FG Days -->
        <div class="panel h-full sm:col-span-6 xl:col-span-6">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-2">
              <span class="text-xl font-bold">Job For FG Today</span>
              <input id="day_select" type="date" class="form-input" value="<?= $currentDate ?>" style="color: white;">
            </div>
          </div>
          <hr>
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn_update_chart_day"
                x-on:click="() => ChartRecall4(inpt_data_job_complate_day.value, inpt_data_received_job_day.value)"
                hidden>Recall and Update
              </button>
              <input type="text" id="inpt_data_job_complate_day" value="" hidden>
              <input type="text" id="inpt_data_received_job_day" value="" hidden>
              <div x-ref="BoxChart5" class="overflow-hidden"></div>
            </div>
          </div>
        </div>
        <!-- FG Days Percent -->
        <div class="panel h-full sm:col-span-6 xl:col-span-6">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-2">
              <span class="text-xl font-bold">Job For FG Today Percent</span>
            </div>
          </div>
          <hr>
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn_update_chart_day_per"
                x-on:click="() => ChartRecall5(inpt_data_job_complate_day_per.value, inpt_data_received_job_day_per.value)"
                hidden>Recall and Update
              </button>
              <input type="text" id="inpt_data_job_complate_day_per" value="" hidden>
              <input type="text" id="inpt_data_received_job_day_per" value="" hidden>
              <div x-ref="BoxChart6" class="overflow-hidden"></div>
            </div>
          </div>
        </div>

        <div class="panel h-full sm:col-span-12 xl:col-span-12">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-2">
              <span class="text-xl font-bold">Qty Job</span>
            </div>
          </div>
          <hr>
          <div>
            <div class="h-full sm:col-span-6 xl:col-span-2">
              <button id="btn_update_chart"
                x-on:click="() => ChartRecall1(inpt_data_job_date.value, inpt_data_job_complate.value, inpt_data_received_job.value)"
                hidden>Recall and Update
              </button>
              <input type="text" id="inpt_data_job_date" value="" hidden>
              <input type="text" id="inpt_data_job_complate" value="" hidden>
              <input type="text" id="inpt_data_received_job" value="" hidden>
              <div x-ref="BoxChart2" class="overflow-hidden"></div>
            </div>
          </div>
        </div>
        <!-- Table -->
        <div class="panel h-full col-span-12">
          <div class="grid grid-cols-1 gap-4 p-5 dark:text-white-light">
            <div class="grid grid-cols-4">
              <span class="text-xl font-bold">Monitoring Job Daily</span>
              <span></span>
              <span></span>
              <input id="month_year_table" type="date" class="form-input" value="<?= $currentDate ?>"
                style="color: white;" />
            </div>
          </div>
          <hr>
          <div class="relative overflow-hidden pt-5">
            <table id="delivery_tabel" class="min-w-full rounded-md shadow-md overflow-hidden">
              <thead>
                <tr class="bg-gray-600 text-white text-sm leading-normal" style="font-size: 0.9674rem;">
                  <th class="py-3 px-6 text-left">Days</th>
                  <th class="py-3 px-6 text-left">Months</th>
                  <th class="py-3 px-6 text-left">Years</th>
                  <th class="py-3 px-6 text-left">Job Number</th>
                  <th class="py-3 px-6 text-left">Job Status</th>
                  <th class="py-3 px-6 text-left">Part Number</th>
                  <th class="py-3 px-6 text-left">Plan</th>
                  <th class="py-3 px-6 text-left">Actual</th>
                  <th class="py-3 px-6 text-left">Received</th>
                  <th class="py-3 px-6 text-left">Issue</th>
                  <th class="py-3 px-6 text-left">Prod</th>
                  <th class="py-3 px-6 text-left">Receipt</th>
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
        var detailTable = $("#delivery_tabel").DataTable({
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
            url: `https://${window.location.host}/api/delivery/get_delivery_job_monitoring_table`,
            type: 'POST',
            contentType: "application/json",
            data: function (d) {
              let selectedMonth = document.getElementById('month_year_table').value;
              if (selectedMonth) {
                let parts = selectedMonth.split("-");
                d.year = `${parts[0]}-${parts[1]}-${parts[2]}`;
              }
              return JSON.stringify(d);
            },
            cache: false,
            dataType: 'json'
          },
          columns: [{
            data: 'Days'
          },
          {
            data: 'Months',
          },
          {
            data: 'Years',
          },
          {
            data: 'JobNumber',
          },
          {
            data: 'JobStatus',
          },
          {
            data: 'PartNumber',
          },
          {
            data: 'Plan',
          },
          {
            data: 'Actual',
          },
          {
            data: 'Received',
          },
          {
            data: 'Issue',
          },
          {
            data: 'Prod',
          },
          {
            data: 'Receipt',
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
        var currentHost = window.location.host;

        const apiUrlDaily = `https://${currentHost}/api/delivery/get_data_job_daily`;
        axios.get(apiUrlDaily)
          .then(response => {
            var data_job_date = response.data.data_job_date;
            var data_job_complate = response.data.data_job_complate;
            var data_received_job = response.data.data_received_job;
            document.getElementById('inpt_data_job_date').value = data_job_date;
            document.getElementById('inpt_data_job_complate').value = data_job_complate;
            document.getElementById('inpt_data_received_job').value = data_received_job;
            document.getElementById('btn_update_chart').click();
          })

        var month = document.getElementById('select_month').value;
        const apiUrlMonthly = `https://${currentHost}/api/delivery/get_data_job_monthly/${month}`;
        axios.get(apiUrlMonthly)
          .then(response => {
            var data_month_open_job = response.data.data_month_open_job;
            var data_month_close_job = response.data.data_month_close_job;
            document.getElementById('inpt_data_job_month_open').value = data_month_open_job;
            document.getElementById('inpt_data_job_month_close').value = data_month_close_job;
            document.getElementById('btn_update_months').click();
          })

        function fetchData(url, callback) {
          axios.get(url)
            .then(response => callback(response.data))
        }
        const apiUrl = `https://${currentHost}/api/delivery/get_data_job_monthly/${month}`;
        fetchData(apiUrl, data => {
          let percentData = Array.isArray(data) ? data[0] : data;
          document.getElementById('inpt_data_job_month_open_per').value = percentData?.data_month_open_job || 0;
          document.getElementById('inpt_data_job_month_close_per').value = percentData?.data_month_close_job || 0;
          document.getElementById('btn_update_months_per').click();
        });

        var day = document.getElementById('day_select').value;
        const apiDailyFG = `https://${currentHost}/api/delivery/get_data_job_daily_select/${day}`;
        axios.get(apiDailyFG)
          .then(response => {
            var data_day_job_complate = response.data.data_day_job_complate;
            var data_day_received_job = response.data.data_day_received_job;
            document.getElementById('inpt_data_job_complate_day').value = data_day_job_complate;
            document.getElementById('inpt_data_received_job_day').value = data_day_received_job;
            document.getElementById('btn_update_chart_day').click();
          })

        function fetchData(url, callback) {
          axios.get(url)
            .then(response => callback(response.data))
        }
        const FGDaysPercent = `https://${currentHost}/api/delivery/get_data_job_daily_select/${day}`;
        fetchData(FGDaysPercent, data => {
          let percentData = Array.isArray(data) ? data[0] : data;
          document.getElementById('inpt_data_job_complate_day_per').value = percentData?.data_day_job_complate || 0;
          document.getElementById('inpt_data_received_job_day_per').value = percentData?.data_day_received_job || 0;
          document.getElementById('btn_update_chart_day_per').click();
        });
      }
      setDefaultData()
      detail_table();


      document.getElementById('select_month').addEventListener('change', function () {
        updateMonthChart();
        updateMonthPercent();
      });
      document.getElementById('day_select').addEventListener('change', function () {
        updateDayFG();
        updateDayFGPercent();
      });


      document.getElementById('month_year_table').addEventListener('input', function () {
        if ($.fn.DataTable.isDataTable('#delivery_tabel')) {
          $('#delivery_tabel').DataTable().ajax.reload(null, false);
        }
      });

      function updateDailyChart() {
        var currentHost = window.location.host;
        const apiUrlMonthly = `https://${currentHost}/api/delivery/get_data_job_daily`;
        axios.get(apiUrlMonthly)
          .then(response => {
            var data_job_date = response.data.data_job_date;
            var data_job_complate = response.data.data_job_complate;
            var data_received_job = response.data.data_received_job;
            var data_cust_date = response.data.data_val_date;
            document.getElementById('inpt_data_job_date').value = data_job_date;
            document.getElementById('inpt_data_job_complate').value = data_job_complate;
            document.getElementById('inpt_data_received_job').value = data_received_job;
            document.getElementById('btn_update_chart').click();
          })
      }

      function updateMonthChart() {
        var currentHost = window.location.host;
        var month = document.getElementById('select_month').value;
        const apiUrlMonthly = `https://${currentHost}/api/delivery/get_data_job_monthly/${month}`;
        axios.get(apiUrlMonthly)
          .then(response => {
            var data_month_open_job = response.data.data_month_open_job;
            var data_month_close_job = response.data.data_month_close_job;
            document.getElementById('inpt_data_job_month_open').value = data_month_open_job;
            document.getElementById('inpt_data_job_month_close').value = data_month_close_job;
            document.getElementById('btn_update_months').click();
          })
      }

      function updateMonthPercent() {
        function fetchData(url, callback) {
          axios.get(url)
            .then(response => callback(response.data))
        }
        var currentHost = window.location.host;
        const month = document.getElementById('select_month').value;
        const apiUrl = `https://${currentHost}/api/delivery/get_data_job_monthly/${month}`;
        fetchData(apiUrl, data => {
          let percentData = Array.isArray(data) ? data[0] : data;
          document.getElementById('inpt_data_job_month_open_per').value = percentData?.data_month_open_job || 0;
          document.getElementById('inpt_data_job_month_close_per').value = percentData?.data_month_close_job || 0;
          document.getElementById('btn_update_months_per').click();
        });
      }

      function updateDayFG() {
        var currentHost = window.location.host;
        var day = document.getElementById('day_select').value;
        const apiDailyFG = `https://${currentHost}/api/delivery/get_data_job_daily_select/${day}`;
        axios.get(apiDailyFG)
          .then(response => {
            var data_day_job_complate = response.data.data_day_job_complate;
            var data_day_received_job = response.data.data_day_received_job;
            document.getElementById('inpt_data_job_complate_day').value = data_day_job_complate;
            document.getElementById('inpt_data_received_job_day').value = data_day_received_job;
            document.getElementById('btn_update_chart_day').click();
          })
      }

      function updateDayFGPercent() {
        function fetchData(url, callback) {
          axios.get(url)
            .then(response => callback(response.data))
        }
        var currentHost = window.location.host;
        const day = document.getElementById('day_select').value;
        const apiUrl = `https://${currentHost}/api/delivery/get_data_job_daily_select/${day}`;
        fetchData(apiUrl, data => {
          let percentData = Array.isArray(data) ? data[0] : data;
          document.getElementById('inpt_data_job_complate_day_per').value = percentData?.data_day_job_complate || 0;
          document.getElementById('inpt_data_received_job_day_per').value = percentData?.data_day_received_job || 0;
          document.getElementById('btn_update_chart_day_per').click();
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
        ChartRecall1(dateJob, jobComplate, jobReceived) {
          const dataTanggal = dateJob.split(',');
          const complate = jobComplate.split(',').map(Number);
          const received = jobReceived.split(',').map(Number);

          this.BoxChart2.updateSeries([{
            name: 'Job Complete',
            type: 'bar',
            data: complate
          },
          {
            name: 'Received Job',
            type: 'bar',
            data: received
          },
          ]);
          this.BoxChart2.updateOptions({
            xaxis: {
              categories: dataTanggal,
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

        ChartRecall2(JobOpen, JobCLose) {
          const open = JobOpen.split(',').map(Number);
          const close = JobCLose.split(',').map(Number);

          this.BoxChart3.updateSeries([{
            name: 'Job Open',
            type: 'bar',
            data: open
          },
          {
            name: 'Job Close',
            type: 'bar',
            data: close
          },
          ]);
        },

        ChartRecall3(OpenPercent, ClosePercent) {
          const open = OpenPercent.split(',').map(Number);
          const close = ClosePercent.split(',').map(Number);

          const actual = [open[0] || 0, close[0] || 0,];
          this.BoxChart4.updateSeries(actual);
          this.BoxChart4.updateOptions({
            labels: ['Job Open', 'Job Close'],
          });
        },

        ChartRecall4(JobOpen, JobCLose) {
          const complate = JobOpen.split(',').map(Number);
          const received = JobCLose.split(',').map(Number);

          this.BoxChart5.updateSeries([{
            name: 'Job Complate',
            type: 'bar',
            data: complate
          },
          {
            name: 'Received Job',
            type: 'bar',
            data: received
          },
          ]);
        },
        ChartRecall5(complatePercent, receivedPercent) {
          const complate = complatePercent.split(',').map(Number);
          const received = receivedPercent.split(',').map(Number);

          const actual = [complate[0] || 0, received[0] || 0,];
          this.BoxChart6.updateSeries(actual);
          this.BoxChart6.updateOptions({
            labels: ['Job Complate', 'Job Received'],
          });
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

          this.BoxChart6 = new ApexCharts(this.$refs.BoxChart6, this.BoxChart6Options);
          this.BoxChart6.render();

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
        get BoxChart4Options() {
          return {
            series: [],
            chart: {
              type: 'pie',
              height: 360,
              fontFamily: 'Nunito, sans-serif',
              toolbar: {
                show: false
              },
              offsetY: 30,
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
            colors: ['#2A9D8F', '#F4A261'],
            labels: [],
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
              position: 'bottom',
              horizontalAlign: 'center',
              fontSize: '14px',
              itemMargin: {
                horizontal: 15,
                vertical: 30
              }
            },
            tooltip: {
              y: {}
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
        get BoxChart6Options() {
          return {
            series: [],
            chart: {
              type: 'pie',
              height: 360,
              fontFamily: 'Nunito, sans-serif',
              toolbar: {
                show: false
              },
              offsetY: 30,
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
            colors: ['#2A9D8F', '#F4A261'],
            labels: [],
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
              position: 'bottom',
              horizontalAlign: 'center',
              fontSize: '14px',
              itemMargin: {
                horizontal: 15,
                vertical: 30
              }
            },
            tooltip: {
              y: {}
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
