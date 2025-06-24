<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" id="sidebar-section">
  <!-- Brand Logo -->
  <a href="/dashboard" class="brand-link route-link" title="<?= $page_info->sys_name ?? null ?>">
    <img src="api/storage/it_logo.png" alt="CITRMU Logo" class="brand-image img-circle elevation-3"
      style="opacity: .8">
    <span class="brand-text font-weight-light sys-name"><?= $page_info->sys_name ?? null ?></span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?= $session_user->user_profile ?>" class="img-circle elevation-2"
          alt="User Image">
      </div>
      <div class="info">
        <a href="/home" class="d-block route-link"><?= $session_user->full_name ?></a>
      </div>
    </div>
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <a href="#" onclick="logout()" title="Logout"><i class="fas fa-solid fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>

    <!-- SidebarSearch Form -->


    <!-- <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div> -->



    <!-- Sidebar Menu -->
    <nav class="mt-2">



      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">




        <li class="nav-header">HOME</li>








        <?php if (permission('dashboard', 'r', $session_user->role)) : ?>
          <li class="nav-item">
            <a href="/dashboard" class="nav-link navlink route-link" title="Dashboard">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <!-- <span class="badge badge-info right">2</span> -->
              </p>
            </a>
          </li>
        <?php endif;  ?>









        <?php if (permission('tickets', 'r',  $session_user->role)) : ?>
          <li class="nav-item">
            <a href="/tickets" class="nav-link navlink route-link" title="Tickets">
              <i class="nav-icon fas fa-ticket-alt"></i>
              <p>
                Tickets
                <span class="badge badge-danger right" id="new-ticket-count" title="New Tickets">0</span>
              </p>
            </a>
          </li>
        <?php endif;  ?>






        <?php if (permission('departments', 'r',  $session_user->role)) : ?>
          <li class="nav-item">
            <a href="/departments" class="nav-link navlink route-link" title="Departments">
              <i class="nav-icon fas fa-building"></i>
              <p>
                Departments
                <!-- <span class="badge badge-info right">2</span> -->
              </p>
            </a>
          </li>
        <?php endif;  ?>






        <?php if (permission('category', 'r',   $session_user->role)) : ?>
          <li class="nav-item">
            <a href="/category" class="nav-link navlink route-link" title="Category">
              <i class="nav-icon fas fa-list"></i>
              <p>
                Category
                <!-- <span class="badge badge-info right">2</span> -->
              </p>
            </a>
          </li>
        <?php endif;  ?>









        <?php if (permission('users', 'r',   $session_user->role)) : ?>
          <li class="nav-item">
            <a href="/users" class="nav-link navlink route-link" title="Users">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
                <!-- <span class="badge badge-info right">2</span> -->
              </p>
            </a>
          </li>
        <?php endif;  ?>




        <!-- 
        <?php if (permission('groups', 'r',   $session_user->role)) : ?>
          <li class="nav-item">
            <a href="/groups" class="nav-link navlink route-link" title="Users">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Groups
              </p>
            </a>
          </li>
        <?php endif;  ?>

 -->





        <?php if (permission('activity logs', 'r',   $session_user->role)) : ?>
          <li class="nav-item module-btn">
            <a href="/logs" class="nav-link navlink route-link" title="Activity Logs">
              <i class="nav-icon fas fa-history"></i>
              <p>
                Activity Logs
                <!-- <span class="badge badge-info right">2</span> -->
              </p>
            </a>
          </li>
        <?php endif;  ?>








        <!-- <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon far fa-envelope"></i>
            <p>
              Mailbox
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="../mailbox/mailbox.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Inbox</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../mailbox/compose.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Compose</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../mailbox/read-mail.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Read</p>
              </a>
            </li>
          </ul>
        </li> -->

      </ul>
    </nav>




    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <?php if (!permission('roles', 'r',  $session_user->role) == false && !permission('settings', 'r',  $session_user->role) == false) : ?>
          <li class="nav-header">Configuration</li>
        <?php endif;  ?>



        <?php if (permission('roles', 'r',   $session_user->role)) : ?>
          <li class="nav-item">
            <a href="/roles" class="nav-link navlink route-link" title="Roles">
              <i class="nav-icon fas fa-user-shield"></i>
              <p>
                Roles
                <!-- <span class="badge badge-info right">2</span> -->
              </p>
            </a>
          </li>
        <?php endif;  ?>




      </ul>
    </nav>


  </div>
</aside>







<script type="text/javascript">
  const currentLink = new URL(window.location.href).searchParams.get('route');
  document.querySelectorAll('.route-link').forEach(link => {
    if (link.getAttribute('href') == currentLink) {
      link.classList.add('active');
      document.querySelector('title').textContent = 'CITRMU | ' + link.textContent.replace('0', '');
    }
  });
</script>






<script type="text/javascript">
  const routerLinks = document.querySelectorAll('.route-link');
  routerLinks.forEach((item) => {
    item.addEventListener('click', function(e) {
      e.preventDefault();
      window.location.href = '?route=' + item.getAttribute('href');
    });
  });
</script>






<script>
  function getNewTickets() {
    fetch('api/tickets.php?action=new_tickets_count', {
      method: 'get',
      headers: {
        "X-API-Key": "<?= $api_key ?>"
      }
    }).then(res => res.text()).then(data => {
      document.querySelector('#new-ticket-count').textContent = data;
    }).catch(error => {
      console.error('error', error);
    })
  }
  getNewTickets();
</script>