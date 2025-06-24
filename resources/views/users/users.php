<div class="content-wrapper" id="users" style="overflow: scroll;">

  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Users</h1> -->
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Users</li>
          </ol>
        </div>
      </div>
    </div>
  </section>


  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header" permission="write">
              <h3 class="card-title">
                <button @click.prevent="$('#addUserModal').modal('show')" class="btn btn-primary">
                  <i class="fas fa-user-plus"></i> Add User
                </button>
                <button class="btn btn-secondary" @click="exportUsers">
                  <i class="fas fa-download"></i> Export
                </button>
              </h3>
            </div>
            <div class="card-body">
              <table id="example2" class="table table-bordered table-hover table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Group</th>
                    <th>Date Created</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(user, index) in users" :key="index" style="cursor:pointer;"
                    @click.prevent="window.location.href=`?route=/profile&id=${user.user_id}`">

                    <td class="align-content-center">{{index + 1}}</td>

                    <td>
                      <img class="p-0" :src="user.user_profile" width="80" alt="">
                      <span class="btn btn-link ml-2">{{user.full_name}}</span>
                    </td>
                    <td class="align-content-center">{{user.email}}</td>
                    <td class="align-content-center">{{user.user_role}}</td>

                    <td class="align-content-center">
                      <button v-if="user.status === 'inactive'" type="button" class="badge badge-danger border-0">
                        Inactive
                      </button>
                      <button v-if="user.status === 'active'" type="button" class="badge badge-success border-0">
                        Active
                      </button>
                    </td>
                    <td class="align-content-center">{{user.group_name}}</td>
                    <td class="align-content-center">{{user.date_added}}</td>

                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>











  <div class="modal mb-4" id="addUserModal" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">



          <div class="personal-information" v-if="field === 'single' ">
            <h6>Personal Information</h6>
            <form class="needs-validation" id="addUserForm" @submit.prevent="submitAddUserForm" enctype="multipart/form-data">
              <div class="card-body">



                <div class="row mb-5 d-flex justify-content-center">
                  <div class="col-md-6 mt-3">
                    <label for="profile" class="d-flex justify-content-center">
                      <img :src="imageData" class="rounded-circle" width="200" height="200">
                    </label>
                    <input type="file" class="form-control" @change="previewImage" name="profile" id="profile" accept="image/*" />
                  </div>
                </div>


                <div class="row g-3">
                  <div class="col-md-6 mt-3">
                    <label for="first_name" class="form-label">First name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="first_name" id="first_name" v-model="first_name"
                      placeholder="Enter first name" required />
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6 mt-3">
                    <label for="middle_name" class="form-label">Middle name</label>
                    <input type="text" class="form-control" name="middle_name" id="middle_name" v-model="middle_name" placeholder="Enter middle name" />
                  </div>
                  <div class="col-md-6 mt-3">
                    <label for="last_name" class="form-label">Last name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="last_name" id="last_name" v-model="last_name"
                      placeholder="Enter last name" required />
                  </div>
                  <div class="col-md-6 mt-3">
                    <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="username" id="usernamae" v-model="username"
                      placeholder="Enter username" required />
                  </div>
                  <div class="col-md-6 mt-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" name="email" id="email" v-model="email" placeholder="Enter email" required />
                  </div>
                  <div class="col-md-6 mt-3">
                    <label for="phone" class="form-label">Phone </label>
                    <input type="text" class="form-control" name="phone" id="phone" v-model="phone" placeholder="Enter phone" />
                  </div>
                  <div class="col-md-6 mt-3">
                    <label for="phone" class="form-label">Role <span class="text-danger">*</span></label>
                    <select class="form-control" name="role" id="role" v-model="role" required>
                      <option value="">--select user role---</option>
                      <option :value="role.id" v-for="(role, index) in roles">{{role.name}}</option>
                    </select>
                  </div>
                  <div class="col-md-6 mt-3">
                    <label for="phone" class="form-label">Group <span class="text-danger">*</span></label>
                    <select class="form-control" name="group_id" id="group_id" v-model="group_name" required>
                      <option value="">--select user role---</option>
                      <option :value="group.group_id" v-for="(group, index) in groups">{{group.group_name}}</option>
                    </select>
                  </div>
                  <div class="col-md-6 mt-3">
                    <label for="phone" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-control" name="status" id="status" v-model="status" required>
                      <option value="">--select user status---</option>
                      <option value="active">Active</option>
                      <option value="inactive">Inactive</option>
                    </select>
                  </div>
                  <div class="col-md-6 mt-3">
                    <label for="new_password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="new_password" v-model="new_password" placeholder="Enter password" required />
                  </div>
                  <div class="col-md-6 mt-3">
                    <label for="confirm_password" class="form-label">Confirm Password <span
                        class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="confirm_password" v-model="confirm_password" placeholder="Confirm password" required />
                  </div>
                </div>
              </div>
              <div class="card-footer text-right">
                <button class="btn btn-link" type="button" @click="field = 'group' ">Import excel or csv</button>
                <button class="btn btn-info" type="submit">Add User</button>
              </div>
            </form>
          </div>


          <div id="import-excel" v-if="field === 'group' ">
            <form class="needs-validation" id="excelFileForm" @submit.prevent="submitExcelFile" enctype="multipart/form-data">
              <div class="card-body">
                <div class="row g-3">
                  <div class="col-md-6 mt-3">
                    <label for="excel_file" class="form-label">Import excel or csv <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" name="excel_file" id="excel_file" v-model="file" required />
                    <div class="mt-2"><small><span class="text-danger">*</span> Please refer to our Excel template. <a href="public/assets/file/excel_template_2025.xlsx" download="">Download</a></small></div>
                  </div>
                </div>
              </div>
              <div class="card-footer text-right">
                <button class="btn btn-link" type="button" @click="field = 'single' ">Back</button>
                <button class="btn btn-info" type="submit">Import</button>
              </div>
            </form>
          </div>






        </div>
      </div>
    </div>
  </div>















</div>


<script>
  new Vue({
    el: '#users',
    data: {
      users: [],
      roles: [],
      groups: [],
      first_name: null,
      middle_name: null,
      last_name: null,
      username: null,
      email: null,
      phone: null,
      imageData: '<?= $helper->storage_url('default-profile.jpg') ?>',
      role: '',
      status: 'active',
      file: null,
      field: 'single',
      group_name: '',

      new_password: null,
      confirm_password: null,
    },
    methods: {
      fetchUsers() {
        fetch('<?= $api ?>/user?action=all', {
          method: 'GET',
          headers: {
            "X-API-Key": "<?= $api_key ?>"
          }
        }).then(res => res.json()).then(data => {
          $('#example2').DataTable().destroy();
          this.users = data;
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
      submitAddUserForm() {
        if (this.new_password !== this.confirm_password) {
          toastr.error('Password does not match! Please try again!');
          return;
        }

        if (this.new_password.length < 8) {
          toastr.error('Password too short. Minimum if 8 characters.');
          return;
        }
        this.loading();

        const formData = new FormData(document.getElementById('addUserForm'));
        formData.append('password', this.new_password);
        fetch('api/user?action=add', {
          method: 'post',
          body: formData,
          headers: {
            "X-API-Key": "<?= $api_key ?>"
          }
        }).then(res => res.json()).then(data => {
          this.loading(false);
          if (data.status === 'success') {
            this.fetchUsers();
            $('#addUserModal').modal('hide');
            toastr.success(data.message);
            this.clearInputs();
            return;
          }
          toastr.error(data.message)
        }).catch(error => {
          console.error('Error', error);
        })
      },
      submitExcelFile() {
        if (!this.file) {
          toastr.error('Please select your file.');
          return;
        }

        const excelFileForm = new FormData(document.getElementById('excelFileForm'));
        fetch('api/user.php?action=excel_import', {
          method: 'post',
          body: excelFileForm,
          headers: {
            "X-API-Key": "<?= $api_key ?>"
          }
        }).then(res => res.json()).then(data => {
          if (data.status === 'success') {
            this.fetchUsers();
            $('#addUserModal').modal('hide');
            toastr.success(data.message);
            return;
          }
          toastr.error(data.message)
        })
      },
      fetchRoles() {
        fetch('<?= $api ?>/role?action=all', {
          method: 'GET',
          headers: {
            "X-API-Key": "<?= $api_key ?>"
          }
        }).then(res => res.json()).then(data => {
          this.roles = data;
        })
      },
      previewImage(event) {
        const file = event.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = (e) => {
            this.imageData = e.target.result;
          };
          reader.readAsDataURL(file);
        } else {
          this.imageData = null;
        }
      },
      clearInputs() {
        const selects = document.querySelectorAll('select');
        const inputs = document.querySelectorAll('input');
        const textareas = document.querySelectorAll('textarea');
        selects.forEach((item) => {
          if (item && item.name !== '_token') {
            item.value = '';
          }
        });
        inputs.forEach((item) => {
          if (item) {
            item.value = '';
          }
        });
        textareas.forEach((item) => {
          if (item) {
            item.value = '';
          }
        });
      },
      loading(action = true) {
        if (action) {
          Swal.fire({
            title: 'Processing...',
            text: 'Please wait while we process your request.',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });
        } else {
          Swal.close();
        }
      },
      exportUsers() {
        const apiUrl = "<?= htmlspecialchars($api) ?>";
        const apiKey = "<?= htmlspecialchars($api_key) ?>";
        const url = `${apiUrl}/user.php?export&X-API-Key=${encodeURIComponent(apiKey)}`;
        window.open(url);
      },
      fetchGroups() {
        fetch('<?= $api ?>/groups?action=all', {
          method: 'GET',
          headers: {
            "X-API-Key": "<?= $api_key ?>"
          }
        }).then(res => res.json()).then(data => {
          this.groups = data;
        })
      },
    },
    mounted() {
      this.fetchUsers();
      this.fetchRoles();
      this.fetchGroups();
    }
  })
</script>