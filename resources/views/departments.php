<div class="content-wrapper" id="departments-main">
    <section class="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <?php if (permission('departments', 'w', $session_user->role)) : ?>
                                <div class="mb-3">
                                    <button data-target="#addDepartmentModal" data-toggle="modal" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add Department
                                    </button>
                                    <button class="btn btn-secondary" @click="exportDepartments">
                                        <i class="fas fa-download"></i> Export
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <table id="departments" class="table table-bordered  table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="d-none">#</th>
                                        <th>Name</th>
                                        <th>Date Created:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(department, index) in departments" :key="department.id" @click.prevent="editDepartment(index)" style="cursor:pointer;">
                                        <td class="d-none">{{ index + 1 }}</td>
                                        <td>
                                            {{department.name}}
                                        </td>
                                        <td>
                                            {{department.date_added}}
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
    <?php include __DIR__ . '/../components/modals/department_modal.php';  ?>
</div>






<script>
    new Vue({
        el: '#departments-main',
        data: {
            departments: [],
            department: {},
        },
        methods: {
            fetchDepartments() {
                fetch('<?= $api ?>/department.php?action=all', {
                    method: 'GET',
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    $('#departments').DataTable().destroy();
                    this.departments = data;
                    Vue.nextTick(() => {
                        $('#departments').DataTable({
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
            editDepartment(index) {
                if ('<?php echo permission('departments', 'w', $session_user->role) ?>') {
                    this.department = this.departments[index];
                    $('#editDepartmentModal').modal('show');
                }
            },
            submitAddDepartmentForm() {
                const formdata = new FormData(document.getElementById('addDepartmentForm'));
                fetch('<?= $api ?>/department.php?action=store', {
                    method: 'post',
                    body: formdata,
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    if (data.status === "success") {
                        $('#addDepartmentModal').modal('hide');
                        this.fetchDepartments();
                        toastr.success(data.message);
                        this.clearInputs();
                        return;
                    }
                    toastr.error(data.message);
                })
            },
            submitEditDepartmentForm() {
                const formdata = new FormData(document.getElementById('editDepartmentForm'));
                formdata.append('department_id', this.department.id);
                fetch('<?= $api ?>/department.php?action=update', {
                    method: 'post',
                    body: formdata,
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    if (data.status === "success") {
                        $('#editDepartmentModal').modal('hide');
                        this.fetchDepartments();
                        toastr.success(data.message);
                        return;
                    }
                    toastr.error(data.message);
                })
            },
            deleteDepartment(id) {
                if (!confirm('Are you sure you want to delete this department?')) {
                    return;
                }

                fetch('<?= $api ?>/department.php?action=delete&id=' + id, {
                    method: 'GET',
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    if (data.status === "success") {
                        $('#editDepartmentModal').modal('hide');
                        this.fetchDepartments();
                        toastr.success(data.message);
                        return;
                    }
                    toastr.error(data.message);
                })
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
            exportDepartments() {
                const apiUrl = "<?= htmlspecialchars($api) ?>";
                const apiKey = "<?= htmlspecialchars($api_key) ?>";
                const url = `${apiUrl}/department.php?export&X-API-Key=${encodeURIComponent(apiKey)}`;
                window.open(url);
            }

        },
        mounted() {
            this.fetchDepartments();
        }
    })
</script>