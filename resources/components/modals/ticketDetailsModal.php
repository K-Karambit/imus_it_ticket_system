<div class="modal fade" id="ticketDetailsModal" tabindex="-1" role="dialog" aria-labelledby="ticketDetailsModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="ticketDetailsModalLabel">Ticket #{{ ticket.ticket_id }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <!-- Left Section -->
                    <div class="col-lg-6 mb-4">
                        <div class="">
                            <!-- <h6 class="text-primary">Ticket Info</h6> -->

                            <p><strong>Short Description:</strong> <span v-html="ticket.short_description"></span></p>
                            <p><strong>Description:</strong> <span class="" v-html="ticket.description"></span></p>
                            <p v-if="ticket.additional_info" v-html="additionalInfo(ticket.additional_info, 'table')"></p>

                            <!-- Department -->
                            <div class="mb-3">
                                <strong>Department:</strong>
                                <div class="d-flex align-items-center">
                                    <span class="mr-2">{{ ticket.department_name }}</span>
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-link dropdown-toggle" href="#" data-toggle="dropdown">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <div class="dropdown-menu p-2">
                                            <input type="text" class="form-control mb-2" v-model="searchCategoryInput" placeholder="Search department">
                                            <a class="dropdown-item" href="#" v-for="item in departments" :key="item.id"
                                                v-if="item.name.toLowerCase().includes(searchCategoryInput.toLowerCase())"
                                                @click.prevent="updateTicketDepartment(ticket.id, item.id)">
                                                {{ item.name }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="mb-3">
                                <strong>Category:</strong>
                                <div class="d-flex align-items-center">
                                    <span class="mr-2">{{ ticket.category_name }}</span>
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-link dropdown-toggle" href="#" data-toggle="dropdown">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <div class="dropdown-menu p-2">
                                            <input type="text" class="form-control mb-2" v-model="searchCategoryInput" placeholder="Search category">
                                            <a class="dropdown-item" href="#" v-for="item in categories" :key="item.id"
                                                v-if="item.category_name.toLowerCase().includes(searchCategoryInput.toLowerCase()) && item.category_name !== ticket.category_name"
                                                @click.prevent="updateTicketCategory(ticket.id, item.id)">
                                                {{ item.category_name }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Urgency -->
                            <div class="mb-3">
                                <strong>Urgency:</strong>
                                <div class="d-flex align-items-center">
                                    <span class="badge mr-2" :style="urgencyColor(ticket.urgency)">{{ ticket.urgency }}</span>
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-link dropdown-toggle" href="#" data-toggle="dropdown">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#" v-for="i in urgencyLevels" :key="i"
                                                v-if="i !== ticket.urgency" @click.prevent="updateUrgency(ticket.id, i)">
                                                {{ i }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status & Others -->
                            <p><strong>Status:</strong> <span class="badge text-white" :style="statusColor(ticket.status)">{{ ticket.status }}</span></p>
                            <p><strong>Assigned to:</strong> {{ ticket.assigned_user }}</p>
                            <p><strong>Created by:</strong> {{ ticket.added_by_name }}</p>
                            <p><strong>Date Created:</strong> {{ ticket.date_added }}</p>
                        </div>
                        <hr>
                        <!-- <button class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                        <?php if (permission('tickets', 'w', $session_user->role)) : ?>
                            <div v-if="!['Cancelled'].includes(ticket.status)">
                                <button class="btn btn-primary" href="#updateStatusModal" data-toggle="modal" @click="toggleUpdateStatusModal">
                                    Update
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Right Section -->
                    <div class="col-lg-6">
                        <div class="p-0">
                            <!-- <h6 class="text-primary">Status History</h6> -->

                            <!-- <div v-if="ticketStates.length == 0" class="alert alert-secondary">
                                No status history available.
                            </div> -->

                            <div class="d-flex justify-content-center" v-if="fetchingStatus">
                                <img src="<?= $helper->public_url('assets/img/loading.gif') ?>" height="40" width="40" alt="Please wait...">
                            </div>


                            <table class="table table-sm table-bordered table-hover" id="status-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="d-none">#</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                        <th>Timestamp</th>
                                        <th>Submitted</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(row, index) in ticketStates" :key="index">
                                        <td class="d-none">{{ index + 1 }}</td>
                                        <td class="font-weight-bold" :style="statusColor(row.status)">{{ row.status }}</td>

                                        <td v-html="row.details"></td>
                                        <td>{{ row.date_added }}</td>
                                        <td>{{ row.updated_by_name }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="modal-footer">

            </div> -->
        </div>
    </div>
</div>