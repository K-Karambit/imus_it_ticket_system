<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title d-flex align-items-center" id="addRoleModalLabel">
                    <i class="fa fa-user-plus mr-2"></i> Add New Role
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="addRoleForm" @submit.prevent="submitAddRole" novalidate>
                    <!-- Role Name -->
                    <div class="form-group">
                        <label for="role_name" class="font-weight-bold">Role Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="role_name" id="role_name" placeholder="Enter role name" required />
                    </div>

                    <!-- Role Description -->
                    <div class="form-group">
                        <label for="role_description" class="font-weight-bold">Role Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="role_description" id="role_description" placeholder="Brief description about the role"></textarea>
                    </div>

                    <!-- Permissions Section -->
                    <div class="permissions-section">
                        <h6 class="font-weight-bold mb-3">Permissions <span class="text-danger">*</span></h6>
                        <div id="permissionsAccordion">
                            <div v-for="(item, index) in navLinksArray" :key="index" class="card">
                                <div class="card-header" :id="`heading-${index}`">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link text-primary font-weight-bold" type="button"
                                            data-toggle="collapse" :data-target="`#collapse-${index}`"
                                            :aria-expanded="index === 0" :aria-controls="`collapse-${index}`">
                                            {{ item }}
                                        </button>
                                    </h5>
                                </div>
                                <div :id="`collapse-${index}`" class="collapse" :class="{show: index === 0}"
                                    :aria-labelledby="`heading-${index}`" data-parent="#permissionsAccordion">
                                    <div class="card-body">
                                        <input type="hidden" :value="item" name="module[]">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" :id="`${item.toLowerCase()}-read`" :name="`read-${item.toLowerCase()}`" :value="1">
                                            <label class="form-check-label" :for="`${item.toLowerCase()}-read`"><i class="fa fa-eye"></i> Read</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" :id="`${item.toLowerCase()}-write`" :name="`write-${item.toLowerCase()}`" :value="1">
                                            <label class="form-check-label" :for="`${item.toLowerCase()}-write`"><i class="fa fa-pencil-alt"></i> Write</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" :id="`${item.toLowerCase()}-delete`" :name="`delete-${item.toLowerCase()}`" :value="1">
                                            <label class="form-check-label" :for="`${item.toLowerCase()}-delete`"><i class="fa fa-trash"></i> Delete</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-right mt-4">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check-circle mr-2"></i> Add Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


















<div class="modal" id="editRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title d-flex align-items-center" id="addRoleModalLabel">
                    <i class="fa fa-user-plus mr-2"></i> Add New Role
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="editRoleForm" @submit.prevent="submitUpdateRole" novalidate>
                    <!-- Role Name -->
                    <div class="form-group">
                        <label for="role_name" class="font-weight-bold">Role Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" v-model="role.name" name="role_name" id="role_name" placeholder="Enter role name" required />
                    </div>

                    <!-- Role Description -->
                    <div class="form-group">
                        <label for="role_description" class="font-weight-bold">Role Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" v-model="role.description" name="role_description" id="role_description" placeholder="Brief description about the role"></textarea>
                    </div>

                    <!-- Permissions Section -->
                    <div class="permissions-section">
                        <h6 class="font-weight-bold mb-3">Permissions <span class="text-danger">*</span></h6>
                        <div id="permissionsAccordion">
                            <div v-for="(item, index) in navLinksArray" :key="index" class="card">
                                <div class="card-header" :id="`heading-${index}`">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link text-primary font-weight-bold" type="button"
                                            data-toggle="collapse" :data-target="`#collapse-${index}`"
                                            :aria-expanded="index === 0" :aria-controls="`collapse-${index}`">
                                            {{ item }}
                                        </button>
                                    </h5>
                                </div>
                                <div :id="`collapse-${index}`" class="collapse" :class="{show: index === 0}"
                                    :aria-labelledby="`heading-${index}`" data-parent="#permissionsAccordion">
                                    <div class="card-body">
                                        <input type="hidden" :value="item" name="module[]">
                                        <div class="form-check">
                                            <input :checked="rolePermissions(item, 'read_access') == 1" class="form-check-input" type="checkbox" :id="`${item.toLowerCase()}-read-${index}`" :name="`read-${btoa(item.toLowerCase())}`" :value="1">
                                            <label class="form-check-label" :for="`${item.toLowerCase()}-read-${index}`">Read</label>
                                        </div>
                                        <div class="form-check">
                                            <input :checked="rolePermissions(item, 'write_access') == 1" class="form-check-input" type="checkbox" :id="`${item.toLowerCase()}-write-${index}`" :name="`write-${btoa(item.toLowerCase())}`" :value="1">
                                            <label class="form-check-label" :for="`${item.toLowerCase()}-write-${index}`">Write</label>
                                        </div>
                                        <div class="form-check">
                                            <input :checked="rolePermissions(item, 'delete_access') == 1" class="form-check-input" type="checkbox" :id="`${item.toLowerCase()}-delete-${index}`" :name="`delete-${btoa(item.toLowerCase())}`" :value="1">
                                            <label class="form-check-label" :for="`${item.toLowerCase()}-delete-${index}`">Delete</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-right mt-4">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check-circle mr-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>