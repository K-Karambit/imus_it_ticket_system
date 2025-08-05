<section class="content" id="ticket-component">

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="d-flex align-items-center mb-2 mb-lg-0">
                            <div class="btn-group" role="group" aria-label="Ticket Actions">
                                <button @click="$('#addTicketModal').modal('show')" class="btn btn-success">
                                    <i class="fa fa-plus"></i> Add Ticket
                                </button>
                                <button @click="$('#fileNameModal').modal('show')" class="btn btn-outline-info" :disabled="tickets.length == 0">
                                    <i class="fa fa-download"></i> Export
                                </button>
                                <button class="btn btn-primary" data-toggle="collapse" href="#filterCollapse" role="button" aria-expanded="false" aria-controls="filterCollapse">
                                    <i class="fa fa-filter"></i> Filters
                                </button>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="text-muted me-2">Show</span>
                            <select class="form-control form-control-sm me-2" style="width: 70px;" v-model="filters.limit" @change.prevent="togglePerPage">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="200">200</option>
                                <option :value="totalTickets">All</option>
                            </select>
                            <span class="text-muted">entries</span>
                        </div>
                    </div>
                </div>

                <div class="collapse" id="filterCollapse">
                    <div class="card-body border-top py-3">
                        <div class="row g-3">
                            <div class="col-lg-6 mb-3">
                                <div class="form-group mb-3">
                                    <label for="searchQuery" class="form-label text-muted">Search Tickets</label>
                                    <input type="text" id="searchQuery" class="form-control" placeholder="Search by description or ID..." @input.prevent="filterTickets" v-model="searchQuery">
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label for="startDate" class="form-label text-muted">Start Date</label>
                                        <input id="startDate" class="form-control" type="date" v-model="startDate">
                                    </div>
                                    <div class="col-6">
                                        <label for="endDate" class="form-label text-muted">End Date</label>
                                        <input id="endDate" class="form-control" type="date" v-model="endDate">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div class="row g-2">
                                    <div class="col-md-6 mb-3">
                                        <label for="department" class="form-label text-muted">Department</label>
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary dropdown-toggle w-100 text-truncate" type="button" data-toggle="dropdown" aria-expanded="false">
                                                Department
                                            </button>
                                            <ul class="dropdown-menu" style="max-height: 250px; overflow-y: auto;">
                                                <li>
                                                    <div class="px-2 py-1"><input type="text" v-model="searchDepartmentsInput" placeholder="Search..." class="form-control form-control-sm"></div>
                                                </li>
                                                <li v-for="department in departments" :key="department.id">
                                                    <a class="dropdown-item" href="#" :hidden="!department.name.toLowerCase().includes(searchDepartmentsInput.toLowerCase())">
                                                        <input type="checkbox" :id="'department-' + department.id" :value="department.id" v-model="selectedDepartments" class="me-2">
                                                        {{ department.name }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="urgency" class="form-label text-muted">Urgency</label>
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary dropdown-toggle w-100 text-truncate" type="button" data-toggle="dropdown" aria-expanded="false">
                                                Urgency
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li v-for="urgencyLevel in urgencyLevels" :key="urgencyLevel">
                                                    <a class="dropdown-item" href="#">
                                                        <input type="checkbox" :id="'urgency-' + urgencyLevel" :value="urgencyLevel" v-model="selectedUrgencies" class="me-2">
                                                        {{ urgencyLevel }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="category" class="form-label text-muted">Category</label>
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary dropdown-toggle w-100 text-truncate" type="button" data-toggle="dropdown" aria-expanded="false">
                                                Category
                                            </button>
                                            <ul class="dropdown-menu" style="max-height: 250px; overflow-y: auto;">
                                                <li>
                                                    <div class="px-2 py-1"><input type="text" v-model="searchCategoryInput" placeholder="Search..." class="form-control form-control-sm"></div>
                                                </li>
                                                <li v-for="category in categories" :key="category.id">
                                                    <a class="dropdown-item" href="#" :hidden="!category.category_name.toLowerCase().includes(searchCategoryInput.toLowerCase())">
                                                        <input type="checkbox" :id="'category-' + category.id" :value="category.id" v-model="selectedCategories" class="me-2">
                                                        {{ category.category_name }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label text-muted">Status</label>
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary dropdown-toggle w-100 text-truncate" type="button" data-toggle="dropdown" aria-expanded="false">
                                                Status
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li v-for="state in states" :key="state">
                                                    <a class="dropdown-item" href="#">
                                                        <input type="checkbox" :id="'status-' + state" :value="state" v-model="selectedStatuses" class="me-2">
                                                        {{ state }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-3 text-end">
                                <button @click="filterTickets" class="btn btn-primary me-2"><i class="fa fa-filter"></i> Apply Filters</button>
                                <button @click="resetFilter" class="btn btn-outline-secondary"><i class="fa fa-undo"></i> Reset</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="tickets-data" class="table table-hover table-striped align-middle text-wrap mb-0">
                            <thead>
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
                                    class="cursor-pointer">
                                    <td class="d-none">{{index+1}}</td>
                                    <th>{{ ticket.ticket_id }}</th>
                                    <td class="limit-lines" v-html="ticket.short_description"></td>
                                    <td>
                                        <span class="badge rounded-pill text-white" :style="urgencyColor(ticket.urgency)">
                                            {{ ticket.urgency }}
                                        </span>
                                    </td>
                                    <td>{{ ticket.assigned ? ticket.assigned.full_name : 'Unassigned' }}</td>
                                    <td>{{ ticket.department ? ticket.department.name : 'No Department' }}</td>
                                    <td>
                                        <span class="badge rounded-pill text-white" :style="statusColor(ticket.status)">
                                            {{ ticket.status }}
                                        </span>
                                    </td>
                                    <td v-html=" additionalInfo(ticket.additional_info)"></td>
                                    <td>{{ticket.group ? ticket.group.group_name : 'No Group'}}</td>
                                    <td>{{ ticket.date_added }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="text-muted small">
                            <b>{{ tickets.current_page ?? 0 }}</b> of <b>{{ totalPage }}</b> pages | Total Tickets <b>{{totalTickets}}</b>
                        </div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
                                    <a class="page-link" href="#" @click.prevent="togglePage(1)">
                                        First Page
                                    </a>
                                </li>
                                <li class="page-item" :class="{ 'disabled': currentPage === totalPage }">
                                    <a class="page-link" href="#" @click.prevent="togglePage(totalPage)">
                                        Last Page
                                    </a>
                                </li>
                                <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
                                    <a class="page-link" href="#" @click.prevent="togglePage(currentPage - 1)">
                                        <i class="fa fa-angle-left"></i> Previous
                                    </a>
                                </li>
                                <li class="page-item" :class="{ 'disabled': currentPage === totalPage }">
                                    <a class="page-link" href="#" @click.prevent="togglePage(currentPage + 1)">
                                        Next <i class="fa fa-angle-right"></i>
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
                    <button @click="exportData" type="button" class="btn btn-primary" :disabled="exportingData">{{exportingData ? 'Exporting...' : 'Save'}}</button>
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
            userGroupId: '<?= $session_user->group_id ?? null ?>',

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
            ticketAdditionalInfo: {},

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
            totalTickets: 0,

            exportingData: false,

            fetchingStatus: false,

            submittingTicket: false,
            submittingStatus: false,
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
                    this.totalTickets = response.data.total_tickets;
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
                this.submittingTicket = true;
                const formdata = new FormData(document.getElementById('addTicketForm'));
                formdata.append('user_group_id', this.users.find(user => user.user_id === this.data.user_id).group_id ?? this.userGroupId);

                if (Object.keys(this.ticketAdditionalInfo).length > 0) {
                    formdata.append('additional_info', JSON.stringify(this.ticketAdditionalInfo));
                }

                axios.post('<?= $api ?>/tickets.php?action=add', formdata, {
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => {
                    const data = res.data;
                    this.submittingTicket = false;
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
                }).catch(err => {
                    this.submittingTicket = false;
                    console.error(err);
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
                this.fetchingStatus = true;
                this.ticketStates = [];
                fetch('<?= $api ?>/states.php?action=get&id=' + id, {
                    method: 'GET',
                    headers: {
                        "X-API-Key": "<?= $api_key ?>"
                    }
                }).then(res => res.json()).then(data => {
                    this.fetchingStatus = false;
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
                this.submittingStatus = true;
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
                    this.submittingStatus = false;
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
                this.exportingData = true;
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
                    const apiUrl = `<?= $api ?>/tickets.php?action=export&urgency=${JSON.stringify(this.selectedUrgencies)}&startDate=${this.startDate}&endDate=${this.endDate}&department=${JSON.stringify(this.selectedDepartments)}&status=${JSON.stringify(this.selectedStatuses)}&category=${JSON.stringify(this.selectedCategories)}&user=${JSON.stringify(this.selectedUsers)}&individual=${this.individual}&searchQuery=${this.searchQuery}`;

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

                    this.exportingData = false;


                    console.log("Excel file downloaded successfully!");
                } catch (error) {
                    console.error("Error downloading the Excel file:", error);
                    this.exportingData = false;
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
            additionalInfo(info, html = null) {
                if (info) {
                    if (html === 'table') {
                        return `
                        <table class="table table-bordered table-sm" id="example1">
                            <tbody>
                                <tr>
                                <th scope="row">Claimant Name</th>
                                <td>${info.claimant_name}</td>
                                </tr>
                                <tr>
                                <th scope="row">Client Name</th>
                                <td>${info.client_name}</td>
                                </tr>
                                <tr>
                                <th scope="row">Contact No.</th>
                                <td>${info.contact_no}</td>
                                </tr>
                                <tr>
                                <th scope="row">Particulars</th>
                                <td>${info.particulars}</td>
                                </tr>
                                <tr>
                                <th scope="row">Amount</th>
                                <td>₱${info.amount}</td>
                                </tr>
                            </tbody>
                        </table>
                        `;
                    } else {
                        return `<span><strong>Claimant Name:</strong> ${info.claimant_name}</span> <br>
                    <span><strong>Client Name:</strong> ${info.client_name}</span> <br>
                    <span><strong>Contact No.:</strong> ${info.contact_no}</span> <br>
                    <span><strong>Particulars:</strong> ${info.particulars}</span> <br>
                    <span><strong>Amount:</strong> ₱${info.amount}</span> `
                    }
                }

                return 'No additional info';
            },
            togglePerPage() {
                this.filterTickets();
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