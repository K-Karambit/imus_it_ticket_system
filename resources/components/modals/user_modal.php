<div class="modal mb-4 fade" id="editProfileModal" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel"
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


                                <?php if ($session_user->role == '1') : ?>
                                    <div class="col-md-6 mt-3">
                                        <label for="phone" class="form-label">Role <span class="text-danger">*</span></label>
                                        <select class="form-control" name="role" id="role" v-model="role" required>
                                            <option value="">--select user role---</option>
                                            <option :value="role.id" v-for="(role, index) in roles">{{role.name}}</option>
                                        </select>
                                    </div>
                                <?php endif; ?>

                                <?php if ($session_user->role == 1) : ?>
                                    <div class="col-md-6 mt-3">
                                        <label for="phone" class="form-label">Group <span class="text-danger">*</span></label>
                                        <select class="form-control" name="group_id" id="group_id" v-model="group_name" required>
                                            <option value="">--select user role---</option>
                                            <option :value="group.group_id" v-for="(group, index) in groups">{{group.group_name}}</option>
                                        </select>
                                    </div>
                                <?php endif; ?>

                                <?php if ($session_user->role == '1') : ?>
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



                <div class="personal-information">
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
                                    <label for="email" class="form-label">Email </label>
                                    <input type="text" class="form-control" name="email" id="email" v-model="email" placeholder="Enter email" />
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
                            <button class="btn btn-info" type="button">Import excel</button>
                            <button class="btn btn-primary" type="submit">Add User</button>
                        </div>
                    </form>
                </div>






            </div>
        </div>
    </div>
</div>