<?php if (permission('departments', 'w', $session_user->role)) : ?>
    <div class="modal" id="editDepartmentModal" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Department</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editDepartmentForm" @submit.prevent="submitEditDepartmentForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Department Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" v-model="department.name" name="name" id="name"
                                placeholder="Enter department name" required />
                        </div>
                    </div>
                    <div class="card-footer float-right">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <?php if (permission('departments', 'd', $session_user->role)) : ?>
                            <button class="btn btn-danger" type="button" @click="deleteDepartment(department.id)">Delete</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>





<?php if (permission('departments', 'w', $session_user->role)) : ?>
    <div class="modal" id="addDepartmentModal" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Department</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addDepartmentForm" @submit.prevent="submitAddDepartmentForm">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="name">Department Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="name"
                                placeholder="Enter department name" required />
                        </div>


                    </div>
                    <div class="card-footer float-right">
                        <button type="submit" class="btn btn-primary">Add Department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>