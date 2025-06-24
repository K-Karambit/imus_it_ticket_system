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


<div class="content-wrapper" id="dashboard" style="overflow: scroll;">
  <div class="main-content">
    <section class="content m-5">
      <div class="container-fluid">
        <div class="row">
          <div class="container-fluid">
            <div class="card mb-3">

              <div class="card-header">
                <!-- <h5 class="text-center mb-5">Monthly Ticket Summary</h5>
 -->

                <div class="d-flex">

                  <input class="form-control" type="date" name="start_date" id="start_date" v-model="startDate">

                  <input class="form-control" type="date" name="end_date" id="end_date" v-model="endDate">


                  <button @click.prevent="fetchMonthlyReports" class="btn btn-primary">Filter</button>


                  <button @click.prevent="resetFilter" class="btn btn-danger">Reset</button>

                  <!-- <div class="form-group col">
                    <label for="">Year</label>
                    <select @change="fetchMonthlyReports" class="form-control" v-model="yearFilter">
                      <?php
                      for ($i = 2024; $i <= 3000; $i++) {
                        echo "<option value='$i'>$i</option>";
                      }
                      ?>
                    </select>
                  </div> -->

                  <!-- <div class="form-group col">
                    <label for="">User</label>
                    <select @change="fetchMonthlyReports" class="form-control" v-model="userFilter">
                      <option value="">All</option>
                      <option v-for="(user, index) in users" :value="user.user_id">{{user.full_name}}</option>
                    </select>
                  </div>

                  <div class="form-group col">
                    <label for="">Departments</label>
                    <select @change="fetchMonthlyReports" class="form-control" v-model="departmentFilter">
                      <option value="">All</option>
                      <option v-for="(department, index) in departments" :value="department.id">{{department.name}}</option>
                    </select>
                  </div> -->


                  <!-- <label for="chart-type">Chart Type</label>
                  <select @change="selectChartType" id="chart-type" v-model="chartType">
                    <option value="bar">Bar</option>
                    <option value="line">Line</option>
                  </select> -->

                </div>
              </div>

              <div class="card-body">

                <div id="chart" width="100%" height="20"></div>

              </div>
            </div>
          </div>
        </div>


        <hr>


        <div class="row">
          <div class="col-lg-4">
            <div class="card mb-3" data-status="new">
              <div class="card-header">
                <h5 class="card-title">3 days unresolved</h5>
              </div>
              <div class="card-body">
                <p class="card-text">{{threeDaysUnresolved.length}}</p>
              </div>
              <div class="card-footer text-right">
                <a class="btn btn-primary" href="#" @click.prevent="viewTicketModal(threeDaysUnresolved, '3 Days Unresolved')"><i class="fa fa-eye"></i></a>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card mb-3" data-status="new">
              <div class="card-header">
                <h5 class="card-title">1 week unresolved</h5>
              </div>
              <div class="card-body">
                <p class="card-text">{{oneWeekUnresolved.length}}</p>
              </div>
              <div class="card-footer text-right">
                <a class="btn btn-primary" href="#" @click.prevent="viewTicketModal(oneWeekUnresolved, '1 Week Unresolved')"><i class="fa fa-eye"></i></a>
              </div>
            </div>
          </div>
        </div>


        <hr>


        <div class="row">
          <div class="col-lg-4">
            <div class="card mb-3" data-status="new">
              <div class="card-header">
                <h5 class="card-title">New</h5>
              </div>
              <div class="card-body">
                <p class="card-text">{{newTicket.length}}</p>
              </div>
              <div class="card-footer text-right">
                <a class="btn btn-primary" href="#" @click.prevent="viewTicketModal(newTicket, 'New Ticket')"><i class="fa fa-eye"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="card mb-3" data-status="in-progress">
              <div class="card-header">
                <h5 class="card-title">In Progress</h5>
              </div>
              <div class="card-body">
                <p class="card-text">{{inProgress.length}}</p>
              </div>
              <div class="card-footer text-right">
                <a class="btn btn-primary" href="#" @click.prevent="viewTicketModal(inProgress, 'In Progress')"><i class="fa fa-eye"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="card mb-3" data-status="on-hold">
              <div class="card-header">
                <h5 class="card-title">On Hold</h5>
              </div>
              <div class="card-body">
                <p class="card-text">{{onHold.length}}</p>
              </div>
              <div class="card-footer text-right">
                <a class="btn btn-primary" href="#" @click.prevent="viewTicketModal(onHold, 'On Hold')"><i class="fa fa-eye"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="card mb-3" data-status="resolved">
              <div class="card-header">
                <h5 class="card-title">Resolved</h5>
              </div>
              <div class="card-body">
                <p class="card-text">{{resolved.length}}</p>
              </div>
              <div class="card-footer text-right">
                <a class="btn btn-primary" href="#" @click.prevent="viewTicketModal(resolved, 'Resolved')"><i class="fa fa-eye"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="card mb-3" data-status="cancelled">
              <div class="card-header">
                <h5 class="card-title">Cancelled</h5>
              </div>
              <div class="card-body">
                <p class="card-text">{{cancelled.length}}</p>
              </div>
              <div class="card-footer text-right">
                <a class="btn btn-primary" href="#" @click.prevent="viewTicketModal(cancelled, 'Cancelled')"><i class="fa fa-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <?php if (permission('users', 'r',   $session_user->role)) : ?>
          <hr>


        <?php endif; ?>

      </div>
    </section>
  </div>





  <div class="modal fade" id="viewTicketModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{title}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table" id="data">
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
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
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
      title: null
    },
    methods: {
      fetchTickets() {
        fetch(`<?= $api ?>/tickets.php?action=all&start_date=${this.startDate}&end_date=${this.endDate}`, {
          method: 'GET',
          headers: {
            "X-API-Key": "<?= $api_key ?>"
          }
        }).then(res => res.json()).then(data => {

          if (<?= $session_user->role ?> == 1) {
            this.newTicket = data.filter(item => item.status === 'New');
            this.onHold = data.filter(item => item.status === 'On Hold');
            this.inProgress = data.filter(item => item.status === 'In Progress');
            this.resolved = data.filter(item => item.status === 'Resolved');
            this.cancelled = data.filter(item => item.status === 'Cancelled');
          } else {
            this.newTicket = data.filter(item => item.status === 'New' && item.user_id == '<?= $session_user->user_id ?>');
            this.onHold = data.filter(item => item.status === 'On Hold' && item.user_id == '<?= $session_user->user_id ?>');
            this.inProgress = data.filter(item => item.status === 'In Progress' && item.user_id == '<?= $session_user->user_id ?>');
            this.resolved = data.filter(item => item.status === 'Resolved' && item.user_id == '<?= $session_user->user_id ?>');
            this.cancelled = data.filter(item => item.status === 'Cancelled' && item.user_id == '<?= $session_user->user_id ?>');
          }


        })
      },
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
      }
    },
    mounted() {
      this.fetchTickets();
      this.fetchUsers();
      this.fetchMonthlyReports();
      this.fetchDepartments();
    }
  })
</script>