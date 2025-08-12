<style>
  /* --- Base Styling and Layout --- */
  .dashboard-content {
    padding: 2rem;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    color: #333;
  }

  /* Year Selector */
  .year-selector {
    position: relative;
    width: 150px;
    margin-bottom: 2rem;
    font-weight: bold;
  }

  .custom-dropdown-trigger {
    background-color: #f7f9fc;
    border: 1px solid #e0e6ed;
    border-radius: 8px;
    padding: 10px 15px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.2s ease;
  }

  .custom-dropdown-trigger.active {
    background-color: #fff;
    border-color: #667eea;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
  }

  .custom-dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    background-color: #fff;
    border: 1px solid #e0e6ed;
    border-radius: 8px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    margin-top: 5px;
    z-index: 100;
    max-height: 200px;
    overflow-y: auto;
  }

  .custom-dropdown-item {
    padding: 10px 15px;
    cursor: pointer;
    transition: background-color 0.2s;
  }

  .custom-dropdown-item:hover {
    background-color: #f7f9fc;
  }

  /* Stats Grid */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
  }

  /* Stat Cards */
  .stat-card {
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    transition: transform 0.2s, box-shadow 0.2s;
  }

  .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  }

  .card-content {
    display: flex;
    align-items: center;
    gap: 1rem;
  }

  .card-icon {
    font-size: 2.5rem;
    color: #6c757d;
  }

  .card-text {
    flex-grow: 1;
  }

  .card-count {
    font-size: 2.5rem;
    font-weight: bold;
    line-height: 1;
  }

  .card-label {
    font-size: 0.9rem;
    color: #6c757d;
    margin-top: 0.25rem;
  }

  .card-link {
    margin-top: 1.5rem;
    text-decoration: none;
    color: #667eea;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .card-link:hover {
    text-decoration: underline;
  }

  /* Custom Colors for Stat Cards */
  .stat-card.new .card-count,
  .stat-card.new .card-link,
  .stat-card.new .card-icon {
    color: #667eea;
  }

  .stat-card.in-progress .card-count,
  .stat-card.in-progress .card-link,
  .stat-card.in-progress .card-icon {
    color: #48bb78;
  }

  .stat-card.on-hold .card-count,
  .stat-card.on-hold .card-link,
  .stat-card.on-hold .card-icon {
    color: #f6ad55;
  }

  .stat-card.resolved .card-count,
  .stat-card.resolved .card-link,
  .stat-card.resolved .card-icon {
    color: #4299e1;
  }

  .stat-card.cancelled .card-count,
  .stat-card.cancelled .card-link,
  .stat-card.cancelled .card-icon {
    color: #a0aec0;
  }

  .stat-card.total .card-count,
  .stat-card.total .card-link,
  .stat-card.total .card-icon {
    color: #9f7aea;
  }

  .stat-card.overdue-3days .card-count,
  .stat-card.overdue-3days .card-link,
  .stat-card.overdue-3days .card-icon {
    color: #e53e3e;
  }

  .stat-card.overdue-7days .card-count,
  .stat-card.overdue-7days .card-link,
  .stat-card.overdue-7days .card-icon {
    color: #e53e3e;
  }

  /* Charts Grid */
  .charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
  }

  .chart-container {
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    padding: 1.5rem;
  }
</style>

<!-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<div class="content-wrapper">

  <section class="dashboard-content">
    <div class="dashboard" id="dashboard">
      <!-- <div class="dashboard-header">
        <div class="year-selector">
          <div
            class="custom-dropdown-trigger"
            @click="toggleDropdown"
            :class="{ 'active': isDropdownOpen }">
            {{ selectedYear }}
            <i class="fas fa-caret-down"></i>
          </div>
          <div class="custom-dropdown-menu" v-if="isDropdownOpen">
            <div
              v-for="year in yearList"
              :key="year"
              class="custom-dropdown-item"
              @click="selectYear(year)">
              {{ year }}
            </div>
          </div>
        </div>
      </div> -->

      <div class="stats-grid">
        <div class="stat-card new">
          <div class="card-content">
            <span class="card-icon"><i class="fas fa-folder-plus"></i></span>
            <div class="card-text">
              <div class="card-count">{{counts.new ?? 0}}</div>
              <div class="card-label">New Tickets</div>
            </div>
          </div>
          <a href="#" @click.prevent="viewTicketModal(newTicket, 'New Ticket')" class="card-link">View details <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="stat-card in-progress">
          <div class="card-content">
            <span class="card-icon"><i class="fas fa-sync-alt"></i></span>
            <div class="card-text">
              <div class="card-count">{{counts.in_progress ?? 0}}</div>
              <div class="card-label">In Progress</div>
            </div>
          </div>
          <a href="#" @click.prevent="viewTicketModal(inProgress, 'In Progress')" class="card-link">View details <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="stat-card on-hold">
          <div class="card-content">
            <span class="card-icon"><i class="fas fa-pause-circle"></i></span>
            <div class="card-text">
              <div class="card-count">{{counts.on_hold ?? 0}}</div>
              <div class="card-label">On Hold</div>
            </div>
          </div>
          <a href="#" @click.prevent="viewTicketModal(onHold, 'On Hold')" class="card-link">View details <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="stat-card resolved">
          <div class="card-content">
            <span class="card-icon"><i class="fas fa-check-circle"></i></span>
            <div class="card-text">
              <div class="card-count">{{counts.resolved ?? 0}}</div>
              <div class="card-label">Resolved</div>
            </div>
          </div>
          <a href="#" @click.prevent="viewTicketModal(resolved, 'Resolved')" class="card-link">View details <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="stat-card cancelled">
          <div class="card-content">
            <span class="card-icon"><i class="fas fa-times-circle"></i></span>
            <div class="card-text">
              <div class="card-count">{{counts.cancelled ?? 0}}</div>
              <div class="card-label">Cancelled</div>
            </div>
          </div>
          <a href="#" @click.prevent="viewTicketModal(cancelled, 'Cancelled')" class="card-link">View details <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="stat-card total">
          <div class="card-content">
            <span class="card-icon"><i class="fas fa-ticket-alt"></i></span>
            <div class="card-text">
              <div class="card-count">{{counts.totalTickets ?? 0}}</div>
              <div class="card-label">Total Tickets</div>
            </div>
          </div>
          <a href="#" @click.prevent="viewTicketModal(oneWeekUnresolved, '1 Week Unresolved')" class="card-link">View details <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="stat-card overdue-3days">
          <div class="card-content">
            <span class="card-icon"><i class="fas fa-clock"></i></span>
            <div class="card-text">
              <div class="card-count">{{counts.unresolved3Days ?? 0}}</div>
              <div class="card-label">3 Days Unresolved</div>
            </div>
          </div>
          
          <a href="#" @click.prevent="viewTicketModal(threeDaysUnresolved, '3 Days Unresolved')" class="card-link">View details <i class="fas fa-arrow-right"></i></a>
          
        </div>

        <div class="stat-card overdue-7days">
          <div class="card-content">
            <span class="card-icon"><i class="fas fa-clock"></i></span>
            <div class="card-text">
              <div class="card-count">{{counts.unresolved7Days ?? 0}}</div>
              <div class="card-label">1 Week Unresolved</div>
            </div>
          </div>
          <a href="#" @click.prevent="viewTicketModal(oneWeekUnresolved, '1 Week Unresolved')" class="card-link">View details <i class="fas fa-arrow-right"></i></a>
        </div>

      </div>

      <div class="charts-grid">
        <div class="chart-container">
          <canvas id="ticketsChart"></canvas>
        </div>
        <div class="chart-container">
          <canvas id="ticketStatusChart"></canvas>
        </div>
      </div>
  </section>
</div>

<script>
  new Vue({
    el: '#dashboard',
    data: {
      counts: {},
      selectedYear: new Date().getFullYear(),
      yearList: [],
      isDropdownOpen: false,

      chart1: null,
      chart2: null,
    },
    methods: {
      fetchDataCounts() {
        axios.get(`<?= $api ?>/tickets.php?action=counts&y=${this.selectedYear}`, {
          headers: {
            "X-API-Key": "<?= $api_key ?>"
          }
        }).then(response => {
          this.counts = response.data;
        })
      },
      getMonthlyReports() {
        axios.get(`<?= $api ?>/tickets.php?action=monthlyReports&y=${this.selectedYear}`, {
          headers: {
            "X-API-Key": "<?= $api_key ?>"
          }
        }).then(response => {
          console.table(response.data);
          const label1 = response.data.ticketLabels;
          const data1 = response.data.ticketData;
          const label2 = response.data.statusCountsLabels;
          const data2 = response.data.statusCountsData;

          // Correct way to destroy a chart instance
          if (this.chart1) {
            this.chart1.destroy();
          }
          if (this.chart2) {
            this.chart2.destroy();
          }

          this.initTicketChart(label1, data1);
          this.initTicketStatusChart(label2, data2, response.data.statusColors);
        })
      },
      initTicketChart(label, data) {
        const ctx = document.getElementById('ticketsChart');
        this.chart1 = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: label,
            datasets: [{
              label: 'Tickets',
              data: data,
              borderWidth: 1
            }, ]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
      initTicketStatusChart(label, data, color = null) {
        const ctx = document.getElementById('ticketStatusChart');
        this.chart2 = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: label,
            datasets: [{
              label: 'Ticket Status',
              data: data,
              backgroundColor: color,
              borderWidth: 1
            }, ]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      },
      generateYearList() {
        const currentYear = new Date().getFullYear();
        const startYear = currentYear - 1; // e.g., show 10 years in the past
        const endYear = currentYear; // e.g., show 10 years in the future

        for (let year = startYear; year <= endYear; year++) {
          this.yearList.push(year);
        }
      },
      toggleDropdown() {
        this.isDropdownOpen = !this.isDropdownOpen;
      },
      selectYear(year) {
        if (this.selectedYear !== year) {
          this.selectedYear = year;
          this.isDropdownOpen = false; // Close the dropdown after selection
          this.fetchDataCounts();
          this.getMonthlyReports();
        }
      }
    },
    mounted() {
      this.fetchDataCounts();
      this.getMonthlyReports();
      this.generateYearList();

      // You can then use it like this
      const swapy = Swapy.createSwapy(container)


      setTimeout(() => {
        this.fetchDataCounts();
        this.getMonthlyReports();
      }, 60000);
    }
  })
</script>