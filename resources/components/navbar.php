<nav class="main-header navbar navbar-expand">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link"><?= ucwords(str_replace('/', '', $_GET['route'] ?? null)) ?? 'Home' ?></a>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto">
    <!-- <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-comments"></i>
        <span class="badge badge-danger navbar-badge">3</span> </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <a href="#" class="dropdown-item">
          <div class="media">
            <img src="https://via.placeholder.com/50/AD1C1C/FFFFFF?text=JD" alt="User Avatar" class="img-size-50 mr-3 img-circle">
            <div class="media-body">
              <h3 class="dropdown-item-title">
                Brad Diesel
                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
              </h3>
              <p class="text-sm">Call me whenever you can...</p>
              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
            </div>
          </div>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <div class="media">
            <img src="https://via.placeholder.com/50/007bff/FFFFFF?text=JP" alt="User Avatar" class="img-size-50 img-circle mr-3">
            <div class="media-body">
              <h3 class="dropdown-item-title">
                John Pierce
                <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
              </h3>
              <p class="text-sm">I got your message bro</p>
              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
            </div>
          </div>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <div class="media">
            <img src="https://via.placeholder.com/50/28a745/FFFFFF?text=NS" alt="User Avatar" class="img-size-50 img-circle mr-3">
            <div class="media-body">
              <h3 class="dropdown-item-title">
                Nora Silvester
                <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
              </h3>
              <p class="text-sm">The subject goes here</p>
              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
            </div>
          </div>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
      </div>
    </li> -->

    <li class="nav-item dropdown" id="notificationComponent">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge" id="notif-counts">{{counts}}</span> </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header" id="notif-header">{{counts}} Ticket Notifications</span>
        <div class="dropdown-divider"></div>
        <div v-for="(item, index) in data" :key="item.id">
          <a href="?route=/tickets&view=new" class="dropdown-item">
            <i class="fas fa-ticket-alt mr-2"></i> {{item.title}}
            <span class="float-right text-muted text-sm">{{item.time}}</span> </a>
        </div>
        <!-- <div class="dropdown-divider"></div> -->
        <!-- <a href="?route=tickets.php&status=replied" class="dropdown-item">
          <i class="fas fa-reply mr-2"></i> 1 ticket updated
          <span class="float-right text-muted text-sm">12 hours ago</span> </a>
        <div class="dropdown-divider"></div>
        <a href="?route=tickets.php&status=pending" class="dropdown-item">
          <i class="fas fa-exclamation-triangle mr-2"></i> 1 pending ticket due soon
          <span class="float-right text-muted text-sm">2 days ago</span> </a>
        <div class="dropdown-divider"></div> -->
        <a href="?route=/tickets" class="dropdown-item dropdown-footer">See All Tickets</a>
      </div>
    </li>


    <li class="nav-item dropdown user-menu">
      <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
        <img src="<?= $session_user->user_profile ?>"
          class="user-image img-circle elevation-2"
          alt="User Image">
        <span class="d-none d-md-inline"><?= $session_user->full_name ?></span> <br>
      </a>
      <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <li class="user-header bg-primary">
          <img src="<?= $session_user->user_profile ?>"
            class="img-circle elevation-2"
            alt="User Image">
          <p>
            <?= $session_user->full_name ?> </p> 
            <span>        <?= $session_user->user_role ?> </span>
            
        </li>
        <!-- <li class="user-body">
          <div class="row">
            <div class="col-4 text-center">
              <a href="#">Followers</a>
            </div>
            <div class="col-4 text-center">
              <a href="#">Sales</a>
            </div>
            <div class="col-4 text-center">
              <a href="#">Friends</a>
            </div>
          </div>
        </li> -->
        <li class="user-footer">
          <a href="?route=/home" class="btn btn-default btn-flat">Profile</a> <a href="api/logout.php" class="btn btn-default btn-flat float-right">Sign out</a>
        </li>
      </ul>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
  </ul>
</nav>






<script>
  new Vue({
    el: '#notificationComponent',
    data: {
      data: [],
      counts: 0,
    },
    methods: {
      fetchNotifications() {
        axios.get('<?= $api ?>/notifications.php?action=index', {
          headers: {
            "X-API-Key": "<?= $api_key ?>"
          }
        }).then(res => {
          this.counts = res.data.length;
          this.data = res.data;
        })
      }
    },
    mounted() {
      this.fetchNotifications();
    }
  })
</script>