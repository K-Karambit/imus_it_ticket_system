<div class="modal mb-4" id="addTicketModal" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Ticket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addTicketForm" @submit.prevent="submitTicketForm">




                    <div class="form-group">
                        <label for="user_id">Assign to <span class="text-danger">*</span></label>
                        <br>


                        <div class="mb-1">
                            <a href="#" @click.prevent="triggerSearch('user')"><i class="fa fa-search"></i> {{!enableSearchUsers ? 'Search' : 'Hide'}}</a>

                            <input v-model="searchUsersInput" @input="triggerSearchResults()" v-if="enableSearchUsers" type="text" placeholder="search user">

                            <div v-if="searchUsersResult.length > 0 && searchUsersInput !== '' " class="ml-3">
                                <ul class="mt-2" style="list-style: none;">
                                    <li v-for="(item, index) in searchUsersResult" :key="index">
                                        <a href="#" @click.prevent="selectResult(item.user_id)">{{item.full_name}}</a>
                                    </li>
                                </ul>
                            </div>


                        </div>


                        <select class="form-control form-select" v-model="data.user_id" name="user_id" id="user_id" required>
                            <option value="">select user</option>
                            <option v-for="(user,index) in users" :value="user.user_id">{{user.full_name}}</option>
                        </select>
                    </div>






                    <div class="form-group">
                        <label for="department">Department <span class="text-danger">*</span></label>
                        <br>

                        <div class="mb-1">
                            <a href="#" @click.prevent="triggerSearch('department')"><i class="fa fa-search"></i> {{!enableSearchDepartments ? 'Search' : 'Hide'}}</a>
                            <input @input="triggerSearchResults('department')" v-model="searchDepartmentsInput" v-if="enableSearchDepartments" type="text" placeholder="search department">

                            <div v-if="searchDepartmentsResult.length > 0 && searchDepartmentsInput" class="ml-3 mt-2">
                                <ul style="list-style: none;">
                                    <li v-for="(item, index) in searchDepartmentsResult" :key="index">
                                        <a href="#" @click.prevent="selectResult(item.name, 'department')">{{item.name}}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <select class="form-control form-select" v-model="data.department" name="department" id="department"
                            required>
                            <option value="">select department</option>
                            <option v-for="(department, index) in departments" :value="department.name">{{department.name}}</option>
                        </select>
                        <!-- <input type="text" class="form-control" name="department" id="department" list="departments" v-model="departmentInput" placeholder="-- select department --" required>
                        <datalist id="departments">
                            <option v-for="(department, index) in departments" :value="department.name">
                        </datalist> -->
                    </div>




                    <div class="form-group">
                        <label for="urgency">Urgency <span class="text-danger">*</span></label>
                        <select class="form-control" v-model="data.urgency" name="urgency" id="urgency" required>
                            <option value="">select ticket urgency</option>
                            <option value="Critical">Critical</option>
                            <option value="High">High</option>
                            <option value="Moderate">Moderate</option>
                            <option value="Low">Low</option>
                        </select>
                    </div>








                    <div class="form-group">
                        <label for="urgency">Category <span class="text-danger">*</span></label>
                        <select class="form-control" v-model="data.category" name="category" id="category" required>
                            <option value="">select ticket category</option>
                            <option v-for="(row,index) in categories" :value="row.id">{{row.category_name}}</option>
                        </select>
                    </div>



                    <div class="form-group">
                        <label for="subject">Short Description <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" v-model="data.subject" name="subject" id="subject"
                            placeholder="Enter a brief description" @input="searchSuggestions" required />
                    </div>



                    <div class="form-group">
                        <label for="description">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" v-model="data.description" name="description" id="description"
                            placeholder="Provide a detailed description" rows="5" required></textarea>
                    </div>



                    <button type="submit" class="btn btn-primary float-right">Add Ticket</button>



                </form>
            </div>
        </div>
    </div>
</div>