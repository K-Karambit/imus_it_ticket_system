<style>
  .ghibli-header {
    background: linear-gradient(to right, #a8edea, #fed6e3);
    color: #4a4a4a;
    font-family: 'Segoe UI', sans-serif;
  }

  .ghibli-body {
    background-color: #fefaf6;
    font-family: 'Georgia', serif;
    color: #333;
  }

  .ghibli-btn {
    background-color: #a0d8ef;
    border: none;
    color: #fff;
    transition: background-color 0.3s ease;
  }

  .ghibli-btn:hover {
    background-color: #90c2e7;
  }

  .form-check-label {
    font-weight: 500;
  }

  .card-ghibli {
    border: 1px solid #e0dede;
    border-radius: 12px;
    padding: 20px;
    background-color: #ffffff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
  }

  .modal-content {
    border-radius: 16px;
    overflow: hidden;
  }

  input.form-control,
  select.form-control,
  textarea.form-control {
    border-radius: 8px;
  }
</style>

<div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content card-ghibli">

      <!-- Modal Header -->
      <div class="modal-header ghibli-header">
        <h5 class="modal-title">🌸 Update Status - Ticket #{{ ticket.ticket_id }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body ghibli-body">
        <form id="updateStatusForm" @submit.prevent="submitUpdateStatusForm">

          <!-- Status Selection -->
          <div class="form-group">
            <label class="font-weight-bold">🌱 Choose Status <span class="text-danger">*</span></label>
            <div v-if="states.length">
              <div v-for="state in states" :key="state" class="form-check">
                <input
                  type="radio"
                  class="form-check-input"
                  :id="'status-' + state"
                  :value="state"
                  v-model="stateStatus"
                  required
                />
                <label :for="'status-' + state" class="form-check-label">
                  {{ state }}
                </label>
              </div>
            </div>
          </div>

          <!-- Reassign Logic -->
          <div class="form-group" v-if="stateStatus === 'Reassign'">
            <label class="font-weight-bold">🎐 Reassign To <span class="text-danger">*</span></label>
            <div class="mb-2">
              <a href="#" @click.prevent="triggerSearch('user')">
                <i class="fa fa-search"></i> {{!enableSearchUsers ? 'Search' : 'Hide'}}
              </a>
            </div>

            <!-- Search Input -->
            <input
              v-if="enableSearchUsers"
              type="text"
              class="form-control mb-2"
              v-model="searchUsersInput"
              @input="triggerSearchResults()"
              placeholder="Search user...">

            <!-- Search Results -->
            <ul class="pl-3" style="list-style: none;" v-if="searchUsersResult.length && searchUsersInput">
              <li v-for="(item, index) in searchUsersResult" :key="index">
                <a href="#" @click.prevent="selectResult(item.user_id)">
                  {{ item.full_name }}
                </a>
              </li>
            </ul>

            <!-- Dropdown -->
            <select class="form-control mt-2" v-model="data.user_id" required>
              <option value="">Select user</option>
              <option v-for="(user, index) in users" :value="user.user_id">{{ user.full_name }}</option>
            </select>
          </div>

          <!-- Details -->
          <div class="form-group">
            <label class="font-weight-bold">📝 Details <span class="text-danger">*</span></label>
            <textarea
              class="form-control"
              v-model="stateDetails"
              id="note"
              rows="5"
              placeholder="Describe the update in a kind, clear way..."
              required>
            </textarea>
          </div>

          <!-- Footer -->
          <div class="modal-footer">
            <button type="submit" class="btn ghibli-btn">
              <i class="fa fa-paper-plane mr-1"></i> Submit
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
