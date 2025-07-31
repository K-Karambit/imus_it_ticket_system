<style>
  .card-text {
    text-align: center;
    padding-top: 10px;
    font-size: 22px;
  }

  .card-title {
    font-weight: bold;
    font-size: 20px;
  }

  #monthlyReportChart {
    background: #fff;
  }
</style>



<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<div class="content-wrapper" id="dashboard">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-lg-4 col-md-6">
          <div class="small-box bg-primary">
            <div class="inner">
              <h3>{{counts.new ?? 0}}</h3>
              <p>New Tickets</p>
            </div>
            <div class="icon">
              <i class="fas fa-folder-plus"></i>
            </div>
            <a href="#" @click.prevent="viewTicketModal(newTicket, 'New Ticket')" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>{{counts.in_progress ?? 0}}</h3>
              <p>In Progress</p>
            </div>
            <div class="icon">
              <i class="fas fa-sync-alt"></i>
            </div>
            <a href="#" @click.prevent="viewTicketModal(inProgress, 'In Progress')" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>{{counts.on_hold ?? 0}}</h3>
              <p>On Hold</p>
            </div>
            <div class="icon">
              <i class="fas fa-pause-circle"></i>
            </div>
            <a href="#" @click.prevent="viewTicketModal(onHold, 'On Hold')" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3>{{counts.resolved ?? 0}}</h3>
              <p>Resolved</p>
            </div>
            <div class="icon">
              <i class="fas fa-check-circle"></i>
            </div>
            <a href="#" @click.prevent="viewTicketModal(resolved, 'Resolved')" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="small-box bg-secondary">
            <div class="inner">
              <h3>{{counts.cancelled ?? 0}}</h3>
              <p>Cancelled</p>
            </div>
            <div class="icon">
              <i class="fas fa-times-circle"></i>
            </div>
            <a href="#" @click.prevent="viewTicketModal(cancelled, 'Cancelled')" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="small-box bg-light">
            <div class="inner">
              <h3>{{counts.totalTickets ?? 0}}</h3>
              <p>Total Tickets</p>
            </div>
            <div class="icon">
              <i class="fas fa-ticket-alt"></i>
            </div>
            <a href="#" @click.prevent="viewTicketModal(oneWeekUnresolved, '1 Week Unresolved')" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>{{counts.unresolved3Days ?? 0}}</h3>
              <p>3 Days Unresolved</p>
            </div>
            <div class="icon">
              <i class="fas fa-clock"></i>
            </div>
            <a href="#" @click.prevent="viewTicketModal(threeDaysUnresolved, '3 Days Unresolved')" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>{{counts.unresolved7Days ?? 0}}</h3>
              <p>1 Week Unresolved</p>
            </div>
            <div class="icon">
              <i class="fas fa-clock"></i>
            </div>
            <a href="#" @click.prevent="viewTicketModal(oneWeekUnresolved, '1 Week Unresolved')" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div class="modal fade" id="viewTicketModal" tabindex="-1" role="dialog" aria-labelledby="viewTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewTicketModalLabel">{{title}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="data">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Ticket ID</th>
                  <th>Category</th>
                  <th>Short Description</th>
                  <th>Assigned</th>
                  <th>Urgency</th>
                  <th>Status</th>
                  <th>Timestamp</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(ticket, index) in selectedTickets" :key="index">
                  <td>{{index+1}}</td>
                  <td>{{ticket.ticket_id}}</td>
                  <td>{{ticket.category_name}}</td>
                  <td>{{ticket.short_description}}</td>
                  <td>{{ticket.assigned_user}}</td>
                  <td>{{ticket.urgency}}</td>
                  <td>{{ticket.status}}</td>
                  <td>{{ticket.date_added}}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  new Vue({
    el: '#dashboard',
    data: {
      users: [],
      departments: [],
      tickets: [],
      chartType: 'bar',
      myChart: null,
      counts: [],

      chartData: [],
      chartLabel: [],

      threeDaysUnresolved: 0,
      oneWeekUnresolved: 0,

      userCounts: 0,
      newTicket: 0,
      onHold: 0,
      inProgress: 0,
      resolved: 0,
      cancelled: 0,

      yearFilter: '<?= date('Y') ?>',
      departmentFilter: '',
      userFilter: '',

      startDate: '',
      endDate: '',

      selectedTickets: [],
      title: null,

      timeFilter: '',
    },
    methods: {
      fetchUsers() {
        fetch('<?= $api ?>/user.php?action=all', {
          method: 'GET',
          headers: {
            "X-API-Key": "<?= $api_key ?>"
          }
        }).then(res => res.json()).then(data => {
          this.users = data;
        })
      },
      fetchDepartments() {
        fetch('<?= $api ?>/department.php?action=all', {
          method: 'GET',
          headers: {
            "X-API-Key": "<?= $api_key ?>"
          }
        }).then(res => res.json()).then(data => {
          this.departments = data;
        })
      },
      fetchMonthlyReports() {
        let url = `<?= $api ?>/tickets.php?action=monthly_report&start_date=${this.startDate}&end_date=${this.endDate}`;

        if (<?= $session_user->role ?> != 1) {
          url = "<?= $api ?>/tickets.php?action=monthly_report&user_id=" + '<?= $session_user->user_id ?>';
        }

        fetch(url, {
          method: 'GET',
          headers: {
            "X-API-Key": "<?= $api_key ?>"
          }
        }).then(res => res.json()).then(data => {

          this.chartLabel = data.month;
          this.chartData = data.count;
          this.threeDaysUnresolved = data.three_days_unresolved;
          this.oneWeekUnresolved = data.one_week_unresolved;
          this.monthlyReports();
        })
      },
      monthlyReports() {
        document.querySelector("#chart").innerHTML = '';
        var options = {
          series: [{
            name: "Tickets",
            data: this.chartData
          }],
          chart: {
            height: 350,
            type: 'area',
            zoom: {
              enabled: false
            }
          },
          dataLabels: {
            enabled: false
          },
          stroke: {
            curve: 'straight'
          },
          title: {
            text: 'Tickets Counts by Month',
            align: 'left'
          },
          grid: {
            row: {
              colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
              opacity: 0.5
            },
          },
          xaxis: {
            categories: this.chartLabel
          }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

      },
      selectChartType() {
        this.monthlyReports();
      },
      resetFilter() {
        this.startDate = '';
        this.endDate = '';
        this.fetchMonthlyReports();
      },
      viewTicketModal(data, title) {
        $('#data').DataTable().destroy();
        this.selectedTickets = data;
        this.title = title;
        Vue.nextTick(() => {
          $('#data').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
          });
        });
        $('#viewTicketModal').modal('show');
      },
      fetchDataCounts() {
        axios.get('<?= $api ?>/tickets.php?action=counts', {
          headers: {
            "X-API-Key": "<?= $api_key ?>"
          }
        }).then(response => {
          this.counts = response.data;
        })
      }
    },
    mounted() {
      this.fetchUsers();
      this.fetchMonthlyReports();
      this.fetchDepartments();
      this.fetchDataCounts();
    }
  })
</script>