<div class="card mb-4" id="profile-component">
    <div class="card-body text-center">
        <img :src="data.user_profile" alt="Profile Image" class="rounded-circle img-fluid mb-3 shadow"
            style="width: 150px; height: 150px;">

        <h4 class="card-text">{{data.full_name}}</h4>
        <p class="card-text">{{data.user_role ?? 'No Information'}}</p>
        <p class="card-text"><strong>Username:</strong> {{data.username ?? 'No Information'}}</p>
        <p class="card-text"><strong>Group:</strong> {{data.group_name ?? 'No Information'}}</p>
        <p class="card-text"><strong>Email:</strong> {{data.email ?? 'No Information'}}</p>
        <p class="card-text"><strong>Phone:</strong> {{data.phone ?? 'No Information'}}</p>
        <p class="card-text"><strong>Date Added:</strong> {{data.date_added ?? 'No Information'}}</p>


        <div class="mt-3">
            <?php if (permission('users', 'w', $session_user->role)): ?>
                <button
                    type="button"
                    permission="write"
                    data-toggle="modal"
                    data-target="#editProfileModal"
                    class="btn btn-primary text-white">
                    <i class="fas fa-edit"></i> Edit
                </button>
            <?php endif; ?>
<?php if (permission('users', 'd', $session_user->role)): ?>
                <button permission="delete" @click.prevent="deleteUser" type="button" permission="delete" class="btn btn-danger">
                    <i class="fas fa-trash"></i>
                    Delete
                </button>
            <?php endif; ?>



        </div>
    </div>

    <div class="modal mb-4" id="editProfileModal" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">



                    <div class="personal-information" v-if="showPersonalInfo">
                        <h6>Personal Information</h6>
                        <form class="needs-validation" id="updateUserProfileForm" @submit.prevent="updateUserProfileForm"
                            enctype="multipart/form-data" novalidate>
                            <div class="card-body">

                                <div class="row mb-5 d-flex justify-content-center">
                                    <div class="col-md-6 mt-3">
                                        <label for="profile" class="d-flex justify-content-center">
                                            <img v-if="!imageData" class="rounded-circle shadow" :src="profile" :title="profile" :alt="profile" width="200" height="200">
                                            <img v-else :src="imageData" class="rounded-circle shadow-sm" width="200" height="200">
                                        </label>
                                        <input type="file" class="form-control" @change="previewImage" name="profile" id="profile" accept="image/*" />
                                    </div>
                                </div>


                                <div class="row g-3">
                                    <div class="col-md-6 mt-3">
                                        <label for="first_name" class="form-label">First name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="first_name" id="first_name" v-model="first_name"
                                            required />
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label for="middle_name" class="form-label">Middle name</label>
                                        <input type="text" class="form-control" name="middle_name" id="middle_name" v-model="middle_name" />
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label for="last_name" class="form-label">Last name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="last_name" id="last_name" v-model="last_name"
                                            required />
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="username" id="usernamae" v-model="username"
                                            required />
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label for="email" class="form-label">Email </label>
                                        <input type="text" class="form-control" name="email" id="email" v-model="email" />
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label for="phone" class="form-label">Phone </label>
                                        <input type="text" class="form-control" name="phone" id="phone" v-model="phone" />
                                    </div>


                                    <?php if ($session_user->role == '1'): ?>
                                        <div class="col-md-6 mt-3">
                                            <label for="phone" class="form-label">Role <span class="text-danger">*</span></label>
                                            <select class="form-control" name="role" id="role" v-model="role" required>
                                                <option value="">--select user role---</option>
                                                <option :value="role.id" v-for="(role, index) in roles">{{role.name}}</option>
                                            </select>
                                        </div>
                                    <?php endif; ?>


                                    <?php if ($session_user->role == '1'): ?>
                                        <div class="col-md-6 mt-3">
                                            <label for="phone" class="form-label">Group <span class="text-danger">*</span></label>
                                            <select class="form-control" name="group_id" id="group_id" v-model="group_id" required>
                                                <option value="">--select user role---</option>
                                                <option :value="group.group_id" v-for="(group, index) in groups">{{group.group_name}}</option>
                                            </select>
                                        </div>
                                    <?php endif; ?>


                                    <?php if ($session_user->role == '1'): ?>
                                        <div class="col-md-6 mt-3">
                                            <label for="phone" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" name="status" id="status" v-model="status" required>
                                                <option value="">--select user status---</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                    <?php endif; ?>


                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a class="btn btn-link" @click="showPersonalInfo = false" type="button">Change Password</a>
                                <button class="btn btn-info" type="submit">Save Changes</button>
                            </div>
                        </form>
                    </div>




                    <div class="user-password" v-if="!showPersonalInfo">
                        <h6>User Credentials</h6>
                        <form class="needs-validation" @submit.prevent="updateUserPassword" id="updatePasswordForm" novalidate>
                            <div class="card-body">
                                <div class="row g-3">

                                    <?php if ($session_user->role != 1): ?>
                                        <div class="col-md-6 mt-1">
                                            <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="current_password" id="current_password" required />
                                        </div>
                                    <?php endif; ?>

                                    <div class="col-md-6 mt-1">
                                        <label for="new_password" class="form-label">New Password <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="new_password" v-model="new_password" required />
                                    </div>
                                    <div class="col-md-6 mt-1">
                                        <label for="confirm_password" class="form-label">Confirm Password <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="confirm_password" v-model="confirm_password" required />
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a class="btn btn-link" @click="showPersonalInfo = true" type="button">Update Personal
                                    Information</a>
                                <button class="btn btn-info" type="submit">Save Changes</button>
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
                        "Content-Type": "multipart/form-data",
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