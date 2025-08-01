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
                            <label for="department" class="form-label">Search</label>
                            <div class="dropdown">
                                <input type="text" class="form-control" placeholder="Search..." @input.prevent="filterTickets" v-model="searchQuery">
                            </div>
                        </div>


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
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div>
                            <button @click="$('#addTicketModal').modal('show')" class="btn btn-primary">
                                <i class="fa fa-plus me-1"></i> Add Ticket
                            </button>
                            <button @click="$('#fileNameModal').modal('show')" class="btn btn-outline-info" :disabled="tickets.length == 0">
                                <i class="fa fa-save me-1"></i> Export
                            </button>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p class="mt-2">entries</p>
                            </div>
                            <div>
                                <select class="form-control" v-model="filters.limit" @change.prevent="filterTickets">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="20">50</option>
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Ticket Table -->
                    <div class="table-responsive">
                        <table id="tickets-data" class="table table-bordered table-hover align-middle text-wrap">
                            <thead class="">
                                <tr>
                                    <th class="d-none">#</th>
                                    <th>ID</th>
                                    <th>Short Description</th>
                                    <th>Urgency</th>
                                    <th>Assigned To</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th>Additional Info</th>
                                    <th>Group</th>
                                    <th>Date Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(ticket, index) in tickets.data" :key="ticket.id"
                                    @click.prevent="ticketDetails(index)"
                                    class="cursor-pointer table-row-hover"
                                    style="cursor: pointer;">
                                    <td class="d-none">{{index+1}}</td>
                                    <th>{{ ticket.ticket_id }}</th>
                                    <td class="limit-lines" v-html="ticket.short_description"></td>
                                    <td>
                                        <span class="badge rounded-pill text-bg-light" :style="urgencyColor(ticket.urgency)">
                                            {{ ticket.urgency }}
                                        </span>
                                    </td>
                                    <td>{{ ticket.assigned ? ticket.assigned.full_name : ''  }}</td>
                                    <td>{{ ticket.department ? ticket.department.name : 'No Department' }}</td>
                                    <td>
                                        <span class="badge rounded-pill text-white" :style="statusColor(ticket.status)">
                                            {{ ticket.status }}
                                        </span>
                                    </td>
                                    <td v-html=" additionalInfo(ticket)"></td>
                                    <td>{{ticket.group ? ticket.group.group_name : 'No Group'}}</td>
                                    <td>{{ ticket.date_added }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap">
                        <div class="text-dark"><b>{{ tickets.current_page ?? 0 }}/{{ totalPage  }}</b></div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination mb-0">
                                <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
                                    <a class="page-link" href="#" @click.prevent="togglePage(currentPage - 1)">
                                        <i class="fa fa-angle-left me-1"></i> Previous
                                    </a>
                                </li>
                                <li class="page-item" :class="{ 'disabled': currentPage === totalPage }">
                                    <a class="page-link" href="#" @click.prevent="togglePage(currentPage + 1)">
                                        Next <i class="fa fa-angle-right ms-1"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
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
            api: '<?= $api ?>',
            tickets: [],
            users: [],
            comment: {},
            departments: [],
            groups: [],

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
                category: '',
                reassign_to_group_id: '',
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
            searchQuery: '',


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

            searchGroupsInput: '',
            selectedAssign: 'user',
            enableSearchGroup: false,

            searchUsersResult: [],
            searchDepartmentsResult: [],

            individual: '',
            currentURL: '',

            filters: {
                action: 'filter',
                limit: 10,
            },
            currentURL: 'tickets.php',
            page: 1,


            responseMessage: '',
            totalPage: 0,
            currentPage: 1,
        },
        methods: {
            togglePage(newPage) {
                if (newPage < 1 || newPage > this.totalPage) return;
                this.currentPage = newPage;
                this.filterTickets(); // Or whatever method loads your data
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

                axios.post(`${this.api}/tickets.php`, formData, {
                    params: {
                        ...this.filters,
                        department: JSON.stringify(this.selectedDepartments),
                        urgency: JSON.stringify(this.selectedUrgencies),
                        category: JSON.stringify(this.selectedCategories),
                        status: JSON.stringify(this.selectedStatuses),
                        user: JSON.stringify(this.selectedUsers),
                        startDate: this.startDate,
                        endDate: this.endDate,
                        page: this.currentPage,
                        searchQuery: this.searchQuery,
                    },
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(response => {
                    this.loading(false);
                    $('#tickets-data').DataTable().destroy();
                    this.tickets = response.data;
                    this.totalPage = response.data.total_page;
                    this.currentPage = response.data.current_page;
                    this.curre
                    Vue.nextTick(() => {
                        $('#tickets-data').DataTable({
                            "paging": false,
                            "lengthChange": true,
                            "searching": false,
                            "ordering": true,
                            "info": false,
                            "autoWidth": false,
                            "responsive": true,
                        });
                    });
                }).catch(error => {
                    // Handle error
                    console.error(error);
                });
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
                fetch('<?= $api ?>/user.php?action=all_user_in_system', {
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
                        this.filterTickets();

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
                this.ticket = this.tickets.data[index];
                this.fetchTicketStates(this.ticket.ticket_id);
                this.filteredGroups = this.filteredGroups.filter(item => item.group_id !== this.ticket.group_id);
                console.log(this.filteredGroups)
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
                    formdata.append('reassigned_user', this.data.user_id ?? null);
                    formdata.append('assign_by', this.selectedAssign ?? null);

                    if (this.selectedAssign === 'group') {
                        formdata.append('assigned_group_id', this.data.reassign_to_group_id ?? null);
                        formdata.append('assigned_group_name', this.groups.find(group => group.group_id === this.data.reassign_to_group_id).group_name ?? null);
                    }
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
                this.searchQuery = '';
                this.filterTickets();
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
                Pace.start();
                // if (action) {
                //     Swal.fire({
                //         title: 'Processing...',
                //         text: 'Please wait while we process your request.',
                //         allowOutsideClick: false,
                //         didOpen: () => {
                //             Swal.showLoading();
                //         }
                //     });
                // } else {
                //     Swal.close();
                // }
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
                        item.full_name.toLowerCase().includes(this.searchUsersInput.toLowerCase()) || item.email.toLowerCase().includes(this.searchUsersInput.toLowerCase())
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
            // togglePage(url) {
            //     this.page = url.replace('/?page=', '');
            //     this.filterTickets();
            // },
            ai(action = null, status = null) {

                if (!this.data.description && !this.stateDetails) {
                    toastr.error('Please provide your description');
                    return;
                }

                const actionButtonId = status ? action + '-' + 'status-button' : action + '-button';
                const actionButton = document.getElementById(actionButtonId);

                const newForm = new FormData();
                const defaultLabel = actionButton.innerHTML;

                newForm.append('text', status ? this.stateDetails : this.data.description);
                newForm.append('action', action);
                newForm.append('short_description', this.data.subject);

                actionButton.disabled = true;
                actionButton.textContent = 'Please wait...';

                axios.post(`<?= $api ?>/ai.php`, newForm, {
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => {
                    if (status) {
                        this.data.description = null;
                        this.stateDetails = res.data.result;
                    } else {
                        this.data.description = res.data.result;
                        this.stateDetails = null;
                    }

                    actionButton.disabled = false;
                    actionButton.innerHTML = defaultLabel;
                    this.responseMessage = res.data.message;

                    // setTimeout(() => {
                    //     this.responseMessage = '';
                    // }, 2000);
                })
            },
            fetchGroups() {
                axios.get('<?= $api ?>/groups.php?action=all', {
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => {
                    this.groups = res.data;
                })
            },
            selectedGroup(id) {
                this.data.reassign_to_group_id = id;
                this.enableSearchGroup = false;
                this.searchGroupsInput = '';
            },
            formatCash() {
                let amount = this.data.amount;

                if (typeof amount !== 'number') {
                    // Attempt to convert to number if it's a string, or return invalid
                    const num = parseFloat(amount);
                    if (isNaN(num)) {
                        return '';
                    }
                    amount = num;
                }

                const isNegative = amount < 0;
                let absoluteAmount = Math.abs(amount);

                // Round to 2 decimal places to avoid floating point issues
                absoluteAmount = Math.round(absoluteAmount * 100) / 100;

                // Convert to string and split into integer and decimal parts
                let [integerPart, decimalPart] = absoluteAmount.toFixed(2).split('.');

                // Add commas to the integer part
                integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

                return (isNegative ? '-' : '') + integerPart + '.' + decimalPart;
            },
            handleFormatCash() {
                this.data.amount = this.formatCash();
            },
            additionalInfo(ticket) {
                if (ticket.claimant_name) {
                    return `
                    <span><strong>Claimant Name:</strong> ${ticket.claimant_name}</span> <br>
                    <span><strong>Client Name:</strong> ${ticket.client_name}</span> <br>
                    <span><strong>Amount:</strong> ₱${ticket.amount}</span> 
                    `;
                }

                return 'No additional info';
            }
        },
        mounted() {
            this.filterTickets();
            this.fetchUsers();
            this.fetchDepartments();
            this.fetchCategories();
            this.fetchGroups();
        },
        computed: {
            filteredGroups() {
                return this.groups.filter(group => group.group_name.toLowerCase().includes(this.searchGroupsInput) && group.group_id !== this.ticket.group_id);
            }
        }
    })
</script>