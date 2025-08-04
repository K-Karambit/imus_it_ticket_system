<div class="content-wrapper" id="roles">



  <section class="">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">

            <?php if (permission('roles', 'w', $session_user->role)) : ?>
              <div class="card-header">
                <h3 class="card-title"><button
                    @click.prevent="$('#addRoleModal').modal('show')"
                    class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Add Role
                  </button></h3>
              </div>
            <?php endif; ?>

            <div class="card-body">
              <table id="example2" class="table table-bordered table-hover  table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Role Name</th>
                    <th>Description</th>
                    <th>Date Created</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(role, index) in roles" :key="index">
                    <td>{{index + 1}}</td>
                    <td>{{role.name}}
                    <td>{{role.description == '' || role.description == null ? 'No description' : role.description}}
                    <td>{{role.date_added}}</td>
                    <td>

                      <?php if (permission('roles', 'w', $session_user->role)) : ?>
                        <button permission="write" @click.prevent="editRole(index)" class="btn btn-sm btn-primary">Edit</button>
                      <?php endif; ?>

                      <?php if (permission('roles', 'd', $session_user->role)) : ?>
                        <button permission="delete" @click.prevent="deleteRole(role.id)" class="btn btn-sm btn-danger">Delete
                        </button>
                      <?php endif; ?>

                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php include __DIR__ . '/../components/modals/role_modal.php';  ?>
</div>


<script>
  let navLinks = ['Dashboard', 'Tickets', 'Departments', 'Roles', 'Users', 'Activity Logs', 'Settings', 'Category', 'Groups'];
  // const navItems = document.querySelectorAll('.navlink');
  // navItems.forEach((item) => {
  //   navLinks.push(item.textContent.trim())
  // });
</script>



<script>
  new Vue({
    el: '#roles',
    data: {
      roles: [],
      role: {},
      role_id: '',
      role_name: '',
      role_description: '',
      navLinksArray: navLinks,
      permissions: []
    },
    methods: {
      fetchRoles() {
        fetch('<?= $api ?>/role.php?action=all', {
          method: 'GET',
          headers: {
            "X-API-Key": "<?= $api_key ?>"
          }
        }).then(res => res.json()).then(data => {
          $('#example2').DataTable().destroy();
          this.roles = data;
          Vue.nextTick(() => {
            $('#example2').DataTable({
              "paging": true,
              "lengthChange": true,
              "searching": true,
              "ordering": true,
              "info": true,
              "autoWidth": false,
              "responsive": true,
            });
          });
        })
      },
      fetchPermissions() {
        fetch('<?= $api ?>/permissions.php?action=all', {
          method: 'GET',
          headers: {
            "X-API-Key": "<?= $api_key ?>"
          }
        }).then(res => res.json()).then(data => {
          this.permissions = data;
        })
      },
      submitAddRole() {
        const formData = new FormData(document.getElementById('addRoleForm'));
        fetch('<?= $api ?>/role.php?action=add', {
          method: 'post',
          body: formData,
          headers: {
            "X-API-Key": "<?= $api_key ?>"
          }
        }).then(res => res.json()).then(data => {
          if (data.status === 'success') {
            this.fetchRoles();
            $('#addRoleModal').modal('hide');
            toastr.success(data.message);
            return;
          }

          toastr.error(data.message);
        })
      },
      deleteRole(id) {
        if (!confirm('Are you sure you want to delete this role?')) {
          return;
        }
        const formData = new FormData();
        formData.append('role_id', id);
        fetch('<?= $api ?>/role.php?action=delete', {
          method: 'post',
          body: formData,
          headers: {
            "X-API-Key": "<?= $api_key ?>"
          }
        }).then(res => res.json()).then(data => {
          if (data.status === 'success') {
            this.fetchRoles();
            toastr.success(data.message);
            return;
          }
          toastr.error(data.message);
        })
      },
      editRole(index) {
        $('#editRoleModal').modal('show');
        const data = this.roles[index];
        this.role = data;
      },
      rolePermissions(module, access) {
        // Filter permissions for the current role and module
        const result = this.permissions.filter(item => item.role_id == this.role.id && item.module == module);

        // Find the matching permission for the given access type (e.g., 'read', 'write')
        return result.find(item => item[access] == 1) ? 1 : 0;
      },
      submitUpdateRole() {
        const formData = new FormData(document.getElementById('editRoleForm'));
        formData.append('role_id', this.role.id);
        fetch('<?= $api ?>/role.php?action=update', {
          method: 'post',
          body: formData,
          headers: {
            "X-API-Key": "<?= $api_key ?>"
          }
        }).then(res => res.json()).then(data => {
          if (data.status === 'success') {
            this.fetchRoles();
            $('#editRoleModal').modal('hide');
            toastr.success(data.message);
            return;
          }

          toastr.error(data.message);
        })
      }
    },
    mounted() {
      this.fetchRoles();
      this.fetchPermissions();
    }
  })
</script>