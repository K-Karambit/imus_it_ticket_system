<div class="card mb-4" id="profile-component">

    <div class="card-body">
        <div class="d-flex align-items-center mb-4">
            <img :src="data.user_profile ?? 'api/storage/default-profile.jpg'" alt="Profile Image" class="rounded-circle mr-3" style="width: 100px; height: 100px;">
            <div>
                <h4 class="mb-0">{{ data.full_name }}</h4>
                <p class="text-muted mb-1">{{ data.user_role ?? 'No Information' }}</p>
                <p class="text-muted small">Added on {{ data.date_added ?? 'No Information' }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-2">
                <p class="mb-1"><strong class="text-muted">Username:</strong> {{ data.username ?? 'No Information' }}</p>
            </div>
            <div class="col-md-6 mb-2">
                <p class="mb-1"><strong class="text-muted">Group:</strong> {{ data.group_name ?? 'No Information' }}</p>
            </div>
            <div class="col-md-6 mb-2">
                <p class="mb-1"><strong class="text-muted">Email:</strong> {{ data.email ?? 'No Information' }}</p>
            </div>
            <div class="col-md-6 mb-2">
                <p class="mb-1"><strong class="text-muted">Phone:</strong> {{ data.phone ?? 'No Information' }}</p>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top">
            <?php if (permission('users', 'w', $session_user->role)): ?>
                <button type="button" permission="write" data-toggle="modal" data-target="#editProfileModal" class="btn btn-primary text-white mr-2">
                    <i class="fas fa-edit"></i> Edit Profile
                </button>
            <?php endif; ?>
            <?php if (permission('users', 'd', $session_user->role)): ?>
                <button permission="delete" @click.prevent="deleteUser" type="button" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete User
                </button>
            <?php endif; ?>
        </div>

    </div>

    <div class="modal fade" id="editProfileModal" data-backdrop="static" tabindex="-1" aria-labelledby="editProfileLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editProfileLabel">
                        <i class="fas fa-user-edit mr-2"></i> Edit Profile
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body bg-light">
                    <div class="row">
                        <div class="col-md-4 d-flex flex-column align-items-center justify-content-center mb-4">
                            <label for="profile" class="mb-2">
                                <img v-if="!imageData" class="rounded-circle shadow" :src="profile" :title="profile" :alt="profile" width="140" height="140">
                                <img v-else :src="imageData" class="rounded-circle shadow-sm" width="140" height="140">
                            </label>
                            <input type="file" class="form-control-file mt-2" @change="previewImage" name="profile" id="profile" accept="image/*" />
                        </div>
                        <div class="col-md-8">
                            <h6 class="mb-3 text-secondary font-weight-bold">Personal Information</h6>
                            <form class="needs-validation" id="updateUserProfileForm" @submit.prevent="updateUserProfileForm" enctype="multipart/form-data" novalidate>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="first_name">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="first_name" id="first_name" v-model="first_name" required />
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="middle_name">Middle Name</label>
                                        <input type="text" class="form-control" name="middle_name" id="middle_name" v-model="middle_name" />
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="last_name" id="last_name" v-model="last_name" required />
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="username">Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="username" id="username" v-model="username" required />
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" name="email" id="email" v-model="email" />
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" name="phone" id="phone" v-model="phone" />
                                    </div>
                                    <?php if ($session_user->role == '1'): ?>
                                        <div class="form-group col-md-6">
                                            <label for="role">Role <span class="text-danger">*</span></label>
                                            <select class="form-control" name="role" id="role" v-model="role" required>
                                                <option value="">--select user role---</option>
                                                <option :value="role.id" v-for="(role, index) in roles">{{role.name}}</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="group_id">Group <span class="text-danger">*</span></label>
                                            <select class="form-control" name="group_id" id="group_id" v-model="group_id" required>
                                                <option value="">--select user group---</option>
                                                <option :value="group.group_id" v-for="(group, index) in groups">{{group.group_name}}</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="status">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" name="status" id="status" v-model="status" required>
                                                <option value="">--select user status---</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <a class="btn btn-link" @click="showPersonalInfo = false" type="button">Change Password</a>
                                    <button class="btn btn-info px-4" type="submit">
                                        <i class="fas fa-save mr-1"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="user-password mt-4" v-if="!showPersonalInfo">
                        <h6 class="mb-3 text-secondary font-weight-bold">User Credentials</h6>
                        <form class="needs-validation" @submit.prevent="updateUserPassword" id="updatePasswordForm" novalidate>
                            <div class="form-row">
                                <?php if ($session_user->role != 1): ?>
                                    <div class="form-group col-md-6">
                                        <label for="current_password">Current Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="current_password" id="current_password" required />
                                    </div>
                                <?php endif; ?>
                                <div class="form-group col-md-6">
                                    <label for="new_password">New Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="new_password" v-model="new_password" required />
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="confirm_password" v-model="confirm_password" required />
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a class="btn btn-link" @click="showPersonalInfo = true" type="button">Update Personal Information</a>
                                <button class="btn btn-info px-4" type="submit">
                                    <i class="fas fa-save mr-1"></i> Save Changes
                                </button>
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
        el: '#profile-component',
        data: {
            data: [],
            roles: [],
            users: [],
            groups: [],

            userId: null,
            first_name: null,
            middle_name: null,
            last_name: null,
            username: null,
            email: null,
            phone: null,
            role: null,
            status: null,
            imageData: null,
            profile: null,

            new_password: null,
            confirm_password: null,

            showPersonalInfo: true,
            group_id: '',

            total_pending: 0,
            tickets_today: 0,
            open_tickets: 0,
        },
        methods: {
            fetchProfileInformation() {
                const params = new URLSearchParams(window.location.search);
                const id = params.get('id') ?? '<?php echo $session_user->user_id ?>';
                this.userId = id;

                fetch('api/user.php?action=one&id=' + id, {
                    method: 'GET',
                    headers: {
                        "X-API-Key": "<?php echo $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    this.data = data;
                    this.fetchRoles();
                    if (!data) {
                        history.back();
                    }

                    this.first_name = data.first_name;
                    this.middle_name = data.middle_name;
                    this.last_name = data.last_name;
                    this.username = data.username;
                    this.email = data.email;
                    this.phone = data.phone;
                    this.role = data.role;
                    this.status = data.status;
                    this.profile = data.user_profile;
                    this.group_id = data.group_id;
                })
            },
            updateUserProfileForm() {
                this.loading();
                const formData = new FormData(document.getElementById('updateUserProfileForm'));
                formData.append('user_id', this.data.user_id);
                fetch('<?php echo $api ?>user.php?action=update', {
                    method: 'post',
                    body: formData,
                    headers: {
                        //   "Content-Type": "multipart/form-data",
                        "X-API-Key": "<?php echo $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    this.loading(false);
                    if (data.status === 'success') {
                        this.fetchProfileInformation();
                        $('#editProfileModal').modal('hide');
                        toastr.success(data.message);
                        return;
                    }
                    toastr.error(data.message)
                }).catch(error => {
                    console.error('Error', error);
                })
            },
            updateUserPassword() {
                if (this.new_password !== this.confirm_password) {
                    toastr.error('Password does not match! Please try again!');
                    return;
                }
                if (this.new_password.length < 8) {
                    toastr.error('Password too short. Minimum if 8 characters.');
                    return;
                }
                this.loading();
                const formData = new FormData(document.getElementById('updatePasswordForm'));
                formData.append('user_id', this.data.user_id);
                formData.append('password', this.confirm_password);
                fetch('<?php echo $api ?>/user.php?action=update_password', {
                    method: 'post',
                    body: formData,
                    headers: {
                        "X-API-Key": "<?php echo $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    this.loading(false);
                    if (data.status === 'success') {
                        this.fetchProfileInformation();
                        $('#editProfileModal').modal('hide');
                        toastr.success(data.message);
                        return;
                    }
                    toastr.error(data.message)
                }).catch(error => {
                    console.error('Error', error);
                })
            },
            deleteUser() {
                if (!confirm('Are you sure you want to delete this user?')) {
                    return;
                }
                const formData = new FormData();
                formData.append('user_id', this.data.user_id);
                fetch('<?php echo $api ?>/user.php?action=delete', {
                    method: 'post',
                    body: formData,
                    headers: {
                        "X-API-Key": "<?php echo $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    if (data.status === 'success') {
                        toastr.success(data.message);

                        setTimeout(() => {
                            window.location.href = '?route=/users';
                        }, 2000);

                        return;
                    }
                    toastr.error(data.message)
                }).catch(error => {
                    console.error('Error', error);
                })
            },
            searchSuggestions() {
                fetch(`https://api.datamuse.com/sug?s=${this.data.subject}`).then(res => res.json()).then(data => {
                    console.log(data[0].word);
                })
            },
            fetchRoles() {
                fetch('<?php echo $api ?>/role.php?action=all', {
                    method: 'GET',
                    headers: {
                        "X-API-Key": "<?php echo $api_key ?>"
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
            fetchGroups() {
                fetch('<?php echo $api ?>/groups.php?action=all', {
                    method: 'GET',
                    headers: {
                        "X-API-Key": "<?php echo $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    this.groups = data;
                })
            },
        },
        mounted() {
            this.fetchProfileInformation();
            this.fetchGroups();
        }
    })
</script>

<style>
    #editProfileModal .modal-content {
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.15);
        background: #f8f9fa;
    }
    #editProfileModal .modal-header {
        border-bottom: 1px solid #e3e3e3;
        padding: 1rem 1.5rem;
    }
    #editProfileModal .modal-title {
        font-weight: 600;
        font-size: 1.25rem;
    }
    #editProfileModal .form-label {
        font-weight: 500;
    }
    #editProfileModal .form-control, #editProfileModal .form-control-file, #editProfileModal select {
        border-radius: 6px;
        box-shadow: none;
        border: 1px solid #ced4da;
    }
    #editProfileModal .btn-info {
        background: #007bff;
        border: none;
        font-weight: 500;
    }
    #editProfileModal .btn-link {
        color: #007bff;
        text-decoration: underline;
    }
    #editProfileModal .rounded-circle {
        border: 3px solid #e3e3e3;
    }
    @media (max-width: 767px) {
        #editProfileModal .modal-dialog {
            max-width: 98vw;
        }
        #editProfileModal .col-md-4, #editProfileModal .col-md-8 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>