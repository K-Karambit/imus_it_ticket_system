<style>
    .input-container {
        position: relative;
        width: 100%;
    }

    .input-container input[type="text"] {
        position: relative;
        z-index: 2;
        background-color: transparent;
        color: #000;
    }

    .input-container .ghost {
        position: absolute;
        top: 0;
        left: 0;
        color: #ccc;
        padding: 8px 12px;
        font-size: 1rem;
        line-height: 1.5;
        z-index: 1;
        width: 100%;
        pointer-events: none;
        font-family: inherit;
    }
</style>


<div class="modal mb-4 fade" id="addTicketModal" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Ticket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addTicketForm" @submit.prevent="submitTicketForm">

                <div class="modal-body">




                    <div class="form-group">
                        <label for="subject">Short Description <span class="text-danger">*</span></label>
                        <div class="input-container">
                            <input type="text" class="form-control" v-model="data.subject" name="subject" id="subject"
                                placeholder="Enter a brief description" autocomplete="off" required />
                            <div class="ghost" id="ghostText"></div>
                        </div>
                    </div>



                    <div class="mb-3 position-relative">
                        <div class="d-flex justify-content-between">
                            <label for="description" class="form-label">
                                Description <span class="text-danger">*</span>
                            </label>
                        </div>

                        <textarea class="form-control"
                            v-model="data.description"
                            name="description"
                            id="description"
                            placeholder="Provide a detailed description"
                            rows="5"
                            required></textarea>

                        <div class="mt-2 d-flex justify-content-between">
                            <!-- <button type="button"
                                class="btn btn-sm btn-outline-secondary mb-1"
                                id="generate-button"
                                @click.prevent="ai('generate')">
                                <i class="fa fa-sync-alt me-1"></i> Generate Description
                            </button> -->

                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button"
                                    class="btn btn-outline-secondary"
                                    id="translate-button"
                                    @click.prevent="ai('translate')">
                                    <i class="fa fa-language me-1"></i> Translate
                                </button>
                                <button type="button"
                                    class="btn btn-outline-secondary"
                                    id="rephrase-button"
                                    @click.prevent="ai('rephrase')">
                                    <i class="fa fa-sync-alt me-1"></i> Paraphrase
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr>






                    <div class="row">



                        <div class="form-group col">
                            <label for="urgency">Urgency <span class="text-danger">*</span></label>
                            <select class="form-control" v-model="data.urgency" name="urgency" id="urgency" required>
                                <option value="">select ticket urgency</option>
                                <option value="Critical">Critical</option>
                                <option value="High">High</option>
                                <option value="Moderate">Moderate</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>






                        <div class="form-group col">
                            <label for="urgency">Category <span class="text-danger">*</span></label>
                            <select class="form-control" v-model="data.category" name="category" id="category" required>
                                <option value="">select ticket category</option>
                                <option v-for="(row,index) in categories" :value="row.id">{{row.category_name}}</option>
                            </select>
                        </div>


                    </div>








                    <div class="row">
                        <div class="form-group col">
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





                        <div class="form-group col">
                            <label for="department">Department <span class="text-danger">*</span></label>
                            <br>

                            <div class="mb-1">
                                <a href="#" @click.prevent="triggerSearch('department')"><i class="fa fa-search"></i> {{!enableSearchDepartments ? 'Search' : 'Hide'}}</a>
                                <input @input.prevent="triggerSearchResults('department')" v-model="searchDepartmentsInput" v-if="enableSearchDepartments" type="text" placeholder="search department">

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









                    </div>







                    <div v-if="data.department === 'PUBLIC' ">
                        <p class="fw-bold">Additional Fields</p>
                        <div class="form-group">
                            <label for="subject">Claimant Name <span class="text-danger">*</span></label>
                            <div class="input-container">
                                <input type="text" class="form-control" v-model="data.claimant_name" name="claimant_name" id="claimant_name"
                                    placeholder="Enter claiment name" autocomplete="off" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject">Cient Name <span class="text-danger">*</span></label>
                            <div class="input-container">
                                <input type="text" class="form-control" v-model="data.client_name" name="client_name" id="client_name"
                                    placeholder="Enter client name" autocomplete="off" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject">Amount <span class="text-danger">*</span></label>
                            <div class="input-container">
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="text" class="form-control" v-model="data.amount" name="amount" id="amount"
                                        placeholder="Enter amount" @change.prevent="handleFormatCash" autocomplete="off" required />
                                </div>
                            </div>
                        </div>
                    </div>







                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                    <button type="submit" class="btn btn-primary float-right">Add Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById("subject");
        const ghost = document.getElementById("ghostText");
        let apiSuggestions = [];

        function fetchAndProcessSuggestions(query) {
            const effectiveQuery = query || (this.data && this.data.subject ? this.data.subject : '');

            if (!effectiveQuery) {
                apiSuggestions = []; 
                ghost.textContent = ""; 
                return;
            }

            fetch(`https://api.datamuse.com/sug?s=${effectiveQuery}`)
                .then(res => res.json())
                .then(data => {
                    apiSuggestions = data.map(item => item.word);
                    updateGhostText();
                })
                .catch(error => {
                    console.error("Error fetching suggestions:", error);
                    apiSuggestions = []; 
                    ghost.textContent = ""; 
                });
        }

        function updateGhostText() {
            const value = input.value.toLowerCase();
            const match = apiSuggestions.find(word => word.startsWith(value) && word.toLowerCase() !== value);
            ghost.textContent = match ? value + match.slice(value.length) : "";
        }

        if (input && ghost) {
            input.addEventListener("input", () => {
                updateGhostText();
                clearTimeout(input.debounceTimer);
                input.debounceTimer = setTimeout(() => {
                    fetchAndProcessSuggestions(input.value);
                }, 300);
            });

            input.addEventListener("keydown", (e) => {
                if (e.key === "Tab") {
                    const ghostValue = ghost.textContent;
                    if (ghostValue) {
                        e.preventDefault();
                        input.value = ghostValue;
                        ghost.textContent = "";
                        fetchAndProcessSuggestions(input.value);
                    }
                }
            });
        } else {
            console.warn("Input or ghostText elements not found. Make sure their IDs are correct.");
        }
    })
</script> -->