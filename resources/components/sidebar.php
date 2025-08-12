<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light elevation-4" id="sidebar-section">
  <!-- Brand Logo -->
  <a href="/dashboard" class="brand-link route-link" title="<?= $page_info->sys_name ?? '' ?>">
    <img src="api/storage/it_logo.png" alt="CITRMU Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light sys-name"><?= $page_info->sys_name ?? '' ?></span>
  </a>

  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" role="menu" data-accordion="false">

        <li class="nav-header">MAIN</li>

        <?php if (permission('dashboard', 'r', $session_user->role)) : ?>
          <li class="nav-item">
            <a href="/dashboard" class="nav-link route-link" title="Dashboard">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <span>Dashboard</span>
            </a>
          </li>
        <?php endif; ?>

        <?php if (permission('tickets', 'r', $session_user->role)) : ?>
          <li class="nav-item">
            <a href="/tickets" class="nav-link route-link" title="Tickets">
              <i class="nav-icon fas fa-ticket-alt"></i>
              <span>Tickets</span>
              <span class="badge badge-danger right" id="new-ticket-count" title="New Tickets">0</span>
            </a>
          </li>
        <?php endif; ?>

        <?php if (permission('departments', 'r', $session_user->role)) : ?>
          <li class="nav-item">
            <a href="/departments" class="nav-link route-link" title="Departments">
              <i class="nav-icon fas fa-building"></i>
              <span>Departments</span>
            </a>
          </li>
        <?php endif; ?>

        <?php if (permission('category', 'r', $session_user->role)) : ?>
          <li class="nav-item">
            <a href="/category" class="nav-link route-link" title="Category">
              <i class="nav-icon fas fa-list"></i>
              <span>Category</span>
            </a>
          </li>
        <?php endif; ?>

        <?php if (permission('users', 'r', $session_user->role)) : ?>
          <li class="nav-item">
            <a href="/users" class="nav-link route-link" title="Users">
              <i class="nav-icon fas fa-users"></i>
              <span>Users</span>
            </a>
          </li>
        <?php endif; ?>

        <?php if (permission('groups', 'r', $session_user->role)) : ?>
          <li class="nav-item">
            <a href="/groups" class="nav-link route-link" title="Groups">
              <i class="nav-icon fas fa-users"></i>
              <span>Groups</span>
            </a>
          </li>
        <?php endif; ?>

        <?php if (permission('activity logs', 'r', $session_user->role)) : ?>
          <li class="nav-item">
            <a href="/logs" class="nav-link route-link" title="Activity Logs">
              <i class="nav-icon fas fa-history"></i>
              <span>Activity Logs</span>
            </a>
          </li>
        <?php endif; ?>

        <?php if (permission('roles', 'r', $session_user->role) || permission('settings', 'r', $session_user->role)) : ?>
          <li class="nav-header mt-3">CONFIGURATION</li>
        <?php endif; ?>

        <?php if (permission('roles', 'r', $session_user->role)) : ?>
          <li class="nav-item">
            <a href="/roles" class="nav-link route-link" title="Roles">
              <i class="nav-icon fas fa-user-shield"></i>
              <span>Roles</span>
            </a>
          </li>
        <?php endif; ?>

        <?php if (permission('settings', 'r', $session_user->role)) : ?>
          <li class="nav-item">
            <a href="/settings" class="nav-link route-link" title="Settings">
              <i class="nav-icon fas fa-cogs"></i>
              <span>Settings</span>
            </a>
          </li>
        <?php endif; ?>

      </ul>
    </nav>
  </div>
</aside>

<style>
  .nav-link.active, .nav-link:hover {
    background: #007bff !important;
    color: #fff !important;
  }
  .nav-header {
    font-size: 1rem;
    font-weight: bold;
    color: #343a40;
    margin-top: 1.5em;
    margin-bottom: .5em;
    padding-left: 1em;
  }
  .nav-link span {
    margin-left: 8px;
  }
</style>

<script type="text/javascript">
  // Highlight active link based on route
  const currentRoute = new URL(window.location.href).searchParams.get('route') || '/dashboard';
  document.querySelectorAll('.route-link').forEach(link => {
    if (link.getAttribute('href') === currentRoute) {
      link.classList.add('active');
      document.title = 'CITRMU | ' + link.textContent.trim();
    }
  });

  // SPA-like navigation
  document.querySelectorAll('.route-link').forEach(item => {
    item.addEventListener('click', function(e) {
      e.preventDefault();
      window.location.href = '?route=' + item.getAttribute('href');
    });
  });

  // Update new ticket count badge
  function getNewTickets() {
    fetch('<?= $api ?>/tickets.php?action=counts', {
      method: 'get',
      headers: { "X-API-Key": "<?= $api_key ?>" }
    })
    .then(res => res.json())
    .then(data => {
      document.querySelector('#new-ticket-count').textContent = data.new;
    })
    .catch(error => console.error('error', error));
  }
  getNewTickets();
</script>