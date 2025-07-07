<section class="content" id="ticket-component">

    <!-- Card Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <!-- Filter & Add Ticket Section -->

                <div class="card-header">
                    <div class="row align-items-center">

                        <!-- Department Dropdown -->
                        <div class="col-lg-3">
                            <label for="department" class="form-label">Department</label>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="departmentDropdown" data-toggle="dropdown" aria-expanded="false">
                                    Department
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="departmentDropdown" style="overflow: scroll; height:300px;">
                                    <li><a class="dropdown-item" href="#"><input type="text" v-model="searchDepartmentsInput" placeholder="search department" class="form-control"></a> </li>
                                    <li v-for="department in departments" :key="department.id">
                                        <a class="dropdown-item" href="#" :hidden="!department.name.toLowerCase().includes(searchDepartmentsInput.toLowerCase())">
                                            <input type="checkbox" :id="'department-' + department.id" :value="department.id" v-model="selectedDepartments">
                                            {{ department.name }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Urgency Dropdown -->
                        <div class="col-lg-3">
                            <label for="urgency" class="form-label">Urgency</label>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="urgencyDropdown" data-toggle="dropdown" aria-expanded="false">
                                    Urgency
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="urgencyDropdown">
                                    <li v-for="urgencyLevel in urgencyLevels" :key="urgencyLevel">
                                        <a class="dropdown-item" href="#">
                                            <input type="checkbox" :id="'urgency-' + urgencyLevel" :value="urgencyLevel" v-model="selectedUrgencies">
                                            {{ urgencyLevel }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Category Dropdown -->
                        <div class="col-lg-3">
                            <label for="category" class="form-label">Category</label>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="categoryDropdown" data-toggle="dropdown" aria-expanded="false">
                                    Category
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <input type="text" v-model="searchCategoryInput" placeholder="search category" class="form-control"></a>
                                    </li>
                                    <li v-for="category in categories" :key="category.id">
                                        <a class="dropdown-item" href="#" :hidden="!category.category_name.toLowerCase().includes(searchCategoryInput.toLowerCase())">
                                            <input type="checkbox" :id="'category-' + category.id" :value="category.id" v-model="selectedCategories">
                                            {{ category.category_name }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Status Dropdown -->
                        <div class="col-lg-3">
                            <label for="status" class="form-label">Status</label>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="statusDropdown" data-toggle="dropdown" aria-expanded="false">
                                    Status
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                                    <li v-for="state in states" :key="state">
                                        <a class="dropdown-item" href="#">
                                            <input type="checkbox" :id="'status-' + state" :value="state" v-model="selectedStatuses">
                                            {{ state }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>


                        <?php if ($_GET['route'] !== '/profile' && $_GET['route'] !== '/home'):  ?>
                            <div class="col-lg-3">
                                <label for="status" class="form-label">Users</label>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="statusDropdown" data-toggle="dropdown" aria-expanded="false">
                                        User
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                                        <li><a class="dropdown-item" href="#"><input type="text" v-model="searchUsersInput" placeholder="search user" class="form-control"></a> </li>
                                        <li v-for="user in users" :key="user">
                                            <a class="dropdown-item" href="#" :hidden="!user.full_name.toLowerCase().includes(searchUsersInput.toLowerCase())">
                                                <input type="checkbox" :id="'user-' + user.user_id" :value="user.user_id" v-model="selectedUsers">
                                                {{ user.full_name }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        <?php endif;  ?>

                        <!-- Start Date -->
                        <div class="col-lg-3">
                            <label for="startDate" class="form-label">Start Date</label>
                            <input id="startDate" class="form-control" type="date" v-model="startDate">
                        </div>

                        <!-- End Date -->
                        <div class="col-lg-3">
                            <label for="endDate" class="form-label">End Date</label>
                            <input id="endDate" class="form-control" type="date" v-model="endDate">
                        </div>

                        <!-- Filter Button -->
                        <div class="col-lg-3">
                            <label for="filter" class="form-label">&nbsp;</label>
                            <button @click="filterTickets" id="filter" class="btn btn-primary w-100"><i class="fa fa-filter"></i> Filter</button>
                        </div>

                        <!-- Reset Button -->
                        <div class="col-lg-3">
                            <label for="reset" class="form-label">&nbsp;</label>
                            <button @click="resetFilter" id="reset" class="btn btn-danger w-100"><i class="fa fa-undo"></i> Reset</button>
                        </div>

                    </div>
                </div>


                <!-- Tickets Table -->
                <div class="card-body">
                    <div class="mb-3">
                        <button @click="$('#addTicketModal').modal('show')" class="btn btn-primary"><i class="fa fa-plus"></i> Add Ticket</button>
                        <button @click="$('#fileNameModal').modal('show')" class="btn btn-info" :disabled="tickets.length==0"><i class="fa fa-save"></i> Export</button>
                    </div>
                    <table id="tickets-data" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <!-- <th>#</th> -->
                                <th>ID</th>
                                <th>Category</th>
                                <th>Short Description</th>
                                <th>Urgency</th>
                                <th>Assigned To</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Date Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(ticket, index) in tickets" :key="ticket.id" @click.prevent="ticketDetails(index)" style="cursor: pointer;">
                                <!-- <td>{{ index + 1 }}</td> -->
                                <td>{{ ticket.ticket_id }}</td>
                                <td>{{ ticket.category_name }}</td>
                                <td class="limit-lines" v-html="ticket.short_description"></td>
                                <td>
                                    <span class="badge" :style="urgencyColor(ticket.urgency)">
                                        {{ ticket.urgency }}
                                    </span>
                                </td>
                                <td>{{ ticket.assigned_user }}</td>
                                <td>{{ ticket.department_name }}</td>
                                <td>
                                    <span class="badge text-white" :style="statusColor(ticket.status)">
                                        {{ ticket.status }}
                                    </span>
                                </td>
                                <td>{{ ticket.date_added }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../components/modals/addTicketModal.php';  ?>
    <?php include __DIR__ . '/../components/modals/updateTicketStatusModal.php';  ?>
    <?php include __DIR__ . '/../components/modals/ticketDetailsModal.php';  ?>


    <div class="modal" id="fileNameModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="fileName">File Name <span class="text-danger">*</span></label>
                        <input
                            class="form-control"
                            type="text"
                            id="fileName"
                            v-model="fileName" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                    <button @click="exportData" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>


</section>



<script>
    new Vue({
        el: '#ticket-component',
        data: {
            tickets: [],
            users: [],
            comment: {},
            departments: [],

            categories: [],

            ticketStates: [],

            states: ['New', 'In Progress', 'On Hold', 'Resolved', 'Cancelled', 'Reassign'],
            urgencyLevels: ['Low', 'Moderate', 'High', 'Critical'],

            ticket: {},
            data: {
                department: '',
                urgency: '',
                user_id: '<?= $_GET['route'] === '/home' ? $session_user->user_id : $_GET['id'] ?? null ?>',
                subject: '',
                description: '',
                category: ''
            },

            stateDetails: '',
            stateStatus: '',
            urgency: '',
            startDate: '',
            endDate: '',
            department: '',
            status: '',
            fileName: '',
            departmentInput: '',
            category: '',


            selectedDepartments: [], // Array to store selected department ids
            selectedUrgencies: [], // Array to store selected urgency levels
            selectedCategories: [], // Array to store selected category ids
            selectedStatuses: ['New', 'In Progress', 'On Hold'], // Array to store selected status values
            selectedUsers: [],

            startDate: '',
            endDate: '',

            enableSearchUsers: false,
            enableSearchDepartments: false,

            searchUsersInput: '',
            searchDepartmentsInput: '',
            searchCategoryInput: '',

            searchUsersResult: [],
            searchDepartmentsResult: [],

            individual: '',
        },
        methods: {
            fetchTickets() {
                const params = new URLSearchParams(window.location.search);
                const route = params.get('route');
                const formData = new FormData();

                if (route !== '/tickets') {
                    const id = params.get('id') ?? '<?= $session_user->user_id ?>';
                    formData.append('user_id', id);
                    this.individual = id;
                }

                this.loading();
                fetch('<?= $api ?>/tickets.php?action=all', {
                    method: 'post',
                    body: formData,
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    this.loading(false);
                    $('#tickets-data').DataTable().destroy();
                    this.tickets = data;
                    Vue.nextTick(() => {
                        $('#tickets-data').DataTable({
                            "paging": true,
                            "lengthChange": true,
                            "searching": true,
                            "ordering": true,
                            "info": true,
                            "autoWidth": true,
                            "responsive": true,
                        });
                    });
                })
            },
            filterTickets() {
                const params = new URLSearchParams(window.location.search);
                const route = params.get('route');
                const formData = new FormData();

                if (route !== '/tickets') {
                    const id = params.get('id') ?? '<?= $session_user->user_id ?>';
                    formData.append('user_id', id);
                }

                this.loading();
                fetch(`<?= $api ?>/tickets.php?action=filter&urgency=${JSON.stringify(this.selectedUrgencies)}&startDate=${this.startDate}&endDate=${this.endDate}&department=${JSON.stringify(this.selectedDepartments)}&status=${JSON.stringify(this.selectedStatuses)}&category=${JSON.stringify(this.selectedCategories)}&user=${JSON.stringify(this.selectedUsers)}`, {
                    method: 'post',
                    body: formData,
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    this.loading(false);
                    $('#tickets-data').DataTable().destroy();
                    this.tickets = data;
                    Vue.nextTick(() => {
                        $('#tickets-data').DataTable({
                            "paging": true,
                            "lengthChange": false,
                            "searching": true,
                            "ordering": true,
                            "info": true,
                            "autoWidth": false,
                            "responsive": true,
                        });
                    });
                })
            },
            fetchDepartments() {
                fetch('<?= $api ?>/department.php?action=all', {
                    method: 'GET',
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    this.departments = data;
                })
            },
            fetchUsers() {
                fetch('<?= $api ?>/user.php?action=all', {
                    method: 'GET',
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    this.users = data;
                })
            },
            submitTicketForm() {
                this.loading();
                const formdata = new FormData(document.getElementById('addTicketForm'));
                //formdata.append('department', this.departmentInput);

                fetch('<?= $api ?>/tickets.php?action=add', {
                    method: 'POST',
                    body: formdata,
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    this.loading(false);
                    if (data.status === 'success') {
                        toastr.success(data.message);
                        this.fetchTickets();

                        $('#addTicketModal').modal('hide');
                        this.data = {
                            department: '',
                            urgency: '',
                            user_id: '',
                            subject: '',
                            description: '',
                            category: ''
                        };
                        this.departmentInput = '';

                        return;
                    }
                    toastr.error(data.message);
                })
            },
            statusColor(status) {
                if (status === 'New') {
                    return style = 'background:#007bff; color: #fff;';
                }
                if (status === 'On Hold') {
                    return style = 'background:#ffc107; color: #fff;';
                }
                if (status === 'Resolved') {
                    return style = 'background:#28a745; color: #fff;';
                }
                if (status === 'Cancelled') {
                    return style = 'background:#dc3545; color: #fff;';
                }
                if (status === 'In Progress') {
                    return style = 'background:#17a2b8; color: #fff;';
                }
                return style = 'background:#6c757d; color: #fff;';
            },
            urgencyColor(urgency) {
                switch (urgency) {
                    case 'Critical':
                        return `background: #dc3545; color: #fff`;
                    case 'High':
                        return `background: #fd7e14; color: #fff`;
                    case 'Moderate':
                        return `background: #ffc107; color: #fff`;
                    case 'Low':
                        return `background: #28a745; color: #fff`;
                    default:
                        return `background: #6c757d; color: #fff`;
                }
            },
            ticketDetails(index) {
                $('#ticketDetailsModal').modal('show');
                this.ticket = this.tickets[index];
                this.fetchTicketStates(this.ticket.ticket_id);
            },
            fetchTicketStates(id) {
                fetch('<?= $api ?>/states.php?action=get&id=' + id, {
                    method: 'GET',
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    $('#status-table').DataTable().destroy();
                    this.ticketStates = data;
                    Vue.nextTick(() => {
                        $('#status-table').DataTable({
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
            submitUpdateStatusForm() {
                this.loading();
                if (!this.stateStatus) {
                    toastr.error('No status selected');
                    return;
                }

                const formdata = new FormData();

                formdata.append('ticket_id', this.ticket.ticket_id);
                formdata.append('details', this.stateDetails);
                formdata.append('status', this.stateStatus);

                if (this.stateStatus === 'Reassign') {
                    formdata.append('reassigned_user', this.data.user_id);
                }

                fetch('<?= $api ?>/states.php?action=submit', {
                    method: 'post',
                    body: formdata,
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    this.loading(false);
                    if (data.status === 'success') {
                        this.fetchTicketStates(this.ticket.ticket_id);
                        this.clearInputs();
                        this.stateStatus = '';
                        this.stateDetails = '';
                        $('#updateStatusModal').modal('hide');
                    }
                })
            },
            resetFilter() {
                this.selectedDepartments = [];
                this.selectedUrgencies = [];
                this.selectedCategories = [];
                this.selectedStatuses = [];
                this.selectedUsers = [];
                this.fetchTickets();
            },
            searchSuggestions() {
                fetch(`https://api.datamuse.com/sug?s=${this.data.subject}`).then(res => res.json()).then(data => {
                    console.log(data[0].word);
                })
            },
            async exportData() {
                const params = new URLSearchParams(window.location.search);
                const route = params.get('route');
                const formData = new FormData();

                if (route !== '/tickets') {
                    const id = params.get('id') ?? '<?= $session_user->user_id ?>';
                    this.user_id = id;
                }

                if (!this.fileName) {
                    toastr.error('Invalid file name');
                    return;
                }

                try {
                    // API endpoint
                    const apiUrl = `<?= $api ?>/tickets.php?action=export&urgency=${JSON.stringify(this.selectedUrgencies)}&startDate=${this.startDate}&endDate=${this.endDate}&department=${JSON.stringify(this.selectedDepartments)}&status=${JSON.stringify(this.selectedStatuses)}&category=${JSON.stringify(this.selectedCategories)}&user=${JSON.stringify(this.selectedUsers)}&individual=${this.individual}`;

                    // Fetch the Excel file from the API
                    const response = await fetch(apiUrl, {
                        method: "GET",
                        headers: {
                            "X-API-Key": "<?= $api_key ?>"
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`Error: ${response.status} ${response.statusText}`);
                    }

                    // Get the response as a Blob
                    const blob = await response.blob();

                    // Create a URL for the Blob
                    const url = window.URL.createObjectURL(blob);

                    // Create a temporary link element
                    const link = document.createElement("a");
                    link.href = url;

                    // Set the download attribute with a default file name
                    link.download = `${this.fileName}.xlsx`;

                    // Trigger the download
                    document.body.appendChild(link);
                    link.click();

                    // Clean up: Remove the link and revoke the object URL
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(url);

                    $('#fileNameModal').modal('hide');
                    this.fileName = null;


                    console.log("Excel file downloaded successfully!");
                } catch (error) {
                    console.error("Error downloading the Excel file:", error);
                }
            },
            fetchCategories() {
                fetch('<?= $api ?>/category.php?action=all', {
                    method: 'GET',
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    this.categories = data;
                })
            },
            clearInputs() {
                const selects = document.querySelectorAll('select');
                const inputs = document.querySelectorAll('input');
                const textareas = document.querySelectorAll('textarea');
                selects.forEach((item) => {
                    if (item) {
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
            updateUrgency(id, urgency) {
                if (!confirm('Are you sure you want to update this ticket ugency?')) {
                    return;
                }

                this.loading();

                fetch(`<?= $api ?>/tickets.php?action=update_urgency&urgency=${urgency}&id=${id}`, {
                    method: 'post',
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    this.loading(false);
                    if (data.status === 'success') {
                        this.ticket.urgency = urgency;
                        toastr.success(data.message);
                        this.fetchTickets();
                        return;
                    }
                    toastr.error(data.message);
                })
            },
            updateTicketCategory(id, category) {
                if (!confirm('Are you sure you want to update this ticket category?')) {
                    return;
                }
                this.loading();
                fetch(`<?= $api ?>/tickets.php?action=update_category&id=${id}&category=${category}`, {
                    method: 'post',
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    this.loading(false);
                    if (data.status === 'success') {
                        this.ticket.category_name = this.categories.find(item => item.id == category).category_name;
                        toastr.success(data.message);
                        this.fetchTickets();
                        return;
                    }
                    toastr.error(data.message);
                })
            },
            updateTicketDepartment(id, department) {
                if (!confirm('Are you sure you want to update this ticket department?')) {
                    return;
                }
                this.loading();
                fetch(`<?= $api ?>/tickets.php?action=update_department&id=${id}&department=${department}`, {
                    method: 'post',
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    this.loading(false);
                    if (data.status === 'success') {
                        this.ticket.department_name = this.departments.find(item => item.id == department).name;
                        toastr.success(data.message);
                        this.fetchTickets();
                        return;
                    }
                    toastr.error(data.message);
                })
            },
            triggerSearch(input = 'user') {
                if (input === 'department') {
                    this.enableSearchDepartments = this.enableSearchDepartments ? false : true;
                } else {
                    this.enableSearchUsers = this.enableSearchUsers ? false : true;
                }
            },
            triggerSearchResults(input = 'user') {
                this.searchUsersResult = [];
                // const url = input === 'user' ? `<?= $api ?>/users.php?action=search&query=${this.searchUsersInput}` : `<?= $api ?>/departments.php?action=search&query=${this.searchDepartmentsInput}`;
                // fetch(url, {
                //     method: 'get',
                //     headers: {
                //         "X-API-Key": "<?= $api_key ?>"
                //     }
                // }).then(res => res.json()).then(data => {

                // }).catch(error => {
                //     console.error(error);
                // })

                if (input === 'user') {
                    this.searchUsersResult = this.users.filter(item =>
                        item.full_name.toLowerCase().includes(this.searchUsersInput.toLowerCase())
                    );
                } else {
                    this.searchDepartmentsResult = this.departments.filter(item =>
                        item.name.toLowerCase().includes(this.searchDepartmentsInput.toLowerCase())
                    );
                }
            },
            selectResult(value, input = 'user') {
                if (input === 'user') {
                    this.data.user_id = value;
                    this.searchUsersInput = '';
                    this.searchUsersResult = [];
                    this.enableSearchUsers = false;
                } else {
                    this.data.department = value;
                    this.searchDepartmentsInput = '';
                    this.searchDepartmentsResult = [];
                    this.enableSearchDepartments = false;
                }
            },
        },
        mounted() {
            this.filterTickets();
            this.fetchUsers();
            this.fetchDepartments();
            this.fetchCategories();

        }
    })
</script>