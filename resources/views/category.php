<div class="content-wrapper" id="category-main">
    <section class="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <?php if (permission('category', 'w', $session_user->role)) : ?>
                                <div class="mb-3">
                                    <button data-target="#addCategoryModal" data-toggle="modal" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add Category
                                    </button>
                                    <button @click="exportCategories" class="btn btn-secondary">
                                        <i class="fas fa-download"></i> Export
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <table id="data" class="table table-bordered  table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="d-none">#</th>
                                        <th>Name</th>
                                        <th>Date Created:</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(row, index) in categories" :key="index" style="cursor:pointer;">
                                        <td class="d-none">{{ index + 1 }}</td>
                                        <td>
                                            {{row.category_name}}
                                        </td>
                                        <td>
                                            {{row.date_added}}
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary" type="button" data-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="fa fa-cog"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#" @click.prevent="editCategory(index)">Edit</a>
                                                    <a class="dropdown-item" href="#" @click.prevent="deleteCategory(row.id)">Delete</a>
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






    <?php if (permission('category', 'w', $session_user->role)) : ?>
        <div class="modal" id="addCategoryModal" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="addCategory" id="addCategoryForm">
                            <div class="form-group">
                                <label for="name">Category <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="category_name" id="category_name"
                                    placeholder="Enter category name" required />
                            </div>
                            <div class="manage-buttons float-right">
                                <button type="submit" class="btn btn-primary">Add Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>





    <?php if (permission('category', 'w', $session_user->role)) : ?>
        <div class="modal" id="editCategoryModal" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="updateCategory" id="editCategoryForm">
                            <input type="hidden" name="id" v-model="selected.id" required />
                            <div class="form-group">
                                <label for="name">Category <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="category_name" id="category_name"
                                    placeholder="Enter category name" v-model="selected.category_name" required />
                            </div>
                            <div class="manage-buttons float-right">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>







</div>






<script>
    new Vue({
        el: '#category-main',
        data: {
            categories: [],
            selected: [],
        },
        methods: {
            fetchCategory() {
                fetch('<?= $api ?>/category.php?action=all', {
                    method: 'GET',
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    $('#data').DataTable().destroy();
                    this.categories = data;
                    Vue.nextTick(() => {
                        $('#data').DataTable({
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
            addCategory() {
                const formdata = new FormData(document.getElementById('addCategoryForm'));
                fetch('<?= $api ?>/category.php?action=store', {
                    method: 'post',
                    body: formdata,
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    if (data.status === 'success') {
                        toastr.success(data.message);
                        this.fetchCategory();
                        this.clearInputs();
                        $('#addCategoryModal').modal('hide');
                        return;
                    }
                    toastr.error(data.message);
                }).catch(error => {
                    console.error(error);
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
            deleteCategory(id) {
                if (!confirm('Are you sure you want to delete this category?')) {
                    return;
                }

                fetch('<?= $api ?>/category.php?action=delete&id=' + id, {
                    method: 'get',
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    if (data.status === 'success') {
                        toastr.success(data.message);
                        this.fetchCategory();
                        return;
                    }
                    toastr.error(data.message);
                }).catch(error => {
                    console.error(error);
                })
            },
            editCategory(index) {
                this.selected = this.categories[index];
                $('#editCategoryModal').modal('show');
            },
            updateCategory() {
                const formdata = new FormData(document.getElementById('editCategoryForm'));
                fetch('<?= $api ?>/category.php?action=update', {
                    method: 'post',
                    body: formdata,
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    if (data.status === 'success') {
                        toastr.success(data.message);
                        this.fetchCategory();
                        $('#editCategoryModal').modal('hide');
                        return;
                    }
                    toastr.error(data.message);
                }).catch(error => {
                    console.error(error);
                })
            },
            exportCategories() {
                const apiUrl = "<?= htmlspecialchars($api) ?>";
                const apiKey = "<?= htmlspecialchars($api_key) ?>";
                const url = `${apiUrl}/category.php?export&X-API-Key=${encodeURIComponent(apiKey)}`;
                window.open(url);
            }
        },
        mounted() {
            this.fetchCategory();
        }
    })
</script>