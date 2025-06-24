<div class="content-wrapper" id="groups" style="overflow: scroll;">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- <h1>Users</h1> -->
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Groups</li>
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
                                <button @click.prevent="$('#addGroupModal').modal('show')" class="btn btn-primary">
                                    <i class="fas fa-user-plus"></i> Add Group
                                </button>
                                <!-- <button class="btn btn-secondary">
                                    <i class="fas fa-download"></i> Export
                                </button> -->
                            </h3>
                        </div>
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Group name</th>
                                        <th>Date Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(group, index) in groups" :key="index" style="cursor:pointer;">
                                        <td class="align-content-center">{{index + 1}}</td>
                                        <td class="align-content-center">{{group.group_name}}</td>
                                        <td class="align-content-center">{{group.created_at}}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary" type="button" data-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="fa fa-cog"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#" @click.prevent="toggleEditGroupModal(index)">Edit</a>
                                                    <a class="dropdown-item" href="#" @click.prevent="deleteGroup(group.id)">Delete</a>
                                                </div>
                                            </div>
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





    <!-- Modal -->
    <div class="modal fade" id="addGroupModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="submitAddGroupForm" id="add-group-form">
                    <div class="modal-body">
                        <div class="col-md-6 mt-3">
                            <label for="group_name" class="form-label">Group name</label>
                            <input type="text" class="form-control" name="group_name" id="group_name" v-model="groupName" placeholder="Enter group name" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Group</button>
                    </div>
                </form>
            </div>
        </div>
    </div>





    <!-- Modal -->
    <div class="modal fade" id="editGroupModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="submitEditGroupForm" id="edit-group-form">
                    <div class="modal-body">
                        <div class="col-md-6 mt-3">
                            <label for="group_name" class="form-label">Group name</label>
                            <input type="text" class="form-control" name="group_name" id="group_name" v-model="group.group_name" placeholder="Enter group name" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>







</div>


<script>
    new Vue({
        el: '#groups',
        data: {
            groups: [],
            groupName: null,
            group: {}
        },
        methods: {
            fetchGroups() {
                fetch('<?= $api ?>/groups.php?action=all', {
                    method: 'GET',
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    $('#example2').DataTable().destroy();
                    this.groups = data;
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
            submitAddGroupForm() {
                const formData = new FormData(document.getElementById('add-group-form'));

                fetch('<?= $api ?>/groups.php?action=store', {
                    method: 'post',
                    body: formData,
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    if (data.status === 'success') {
                        toastr.success(data.message);
                        this.fetchGroups();
                        this.groupName = null;
                        $('#addGroupModal').modal('hide');
                        return;
                    }
                    toastr.error(data.message);
                }).catch(error => {
                    console.error(error);
                })
            },
            toggleEditGroupModal(index) {
                this.group = this.groups[index];
                $('#editGroupModal').modal('show');
            },

            submitEditGroupForm() {
                const formData = new FormData(document.getElementById('edit-group-form'));
                formData.append('id', this.group.id);
                fetch('<?= $api ?>/groups.php?action=update', {
                    method: 'post',
                    body: formData,
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    if (data.status === 'success') {
                        toastr.success(data.message);
                        this.fetchGroups();
                        this.groupName = null;
                        $('#editGroupModal').modal('hide');
                        return;
                    }
                    toastr.error(data.message);
                }).catch(error => {
                    console.error(error);
                })
            },
            deleteGroup(id) {
                if (!confirm('Are you sure you want to delete this group?')) return;

                fetch('<?= $api ?>/groups.php?action=delete&id=' + id, {
                    method: 'get',
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    if (data.status === 'success') {
                        toastr.success(data.message);
                        this.fetchGroups();
                        this.groupName = null;
                        $('#addGroupModal').modal('hide');
                        return;
                    }
                    toastr.error(data.message);
                }).catch(error => {
                    console.error(error);
                })
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
        },
        mounted() {
            this.fetchGroups();
        }
    })
</script>