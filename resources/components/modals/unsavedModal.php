<div class="modal fade" id="addTicketUnsavedModal" tabindex="-1" role="dialog" aria-labelledby="unsavedModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="unsavedModalLabel">Unsaved Changes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                You have unsaved changes. Do you want to continue?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" @click.prevent="toggleContinueUnsavedChanges('no', 'addTicket')">No</button>
                <button type="button" class="btn btn-danger" id="confirmLeave" @click.prevent="toggleContinueUnsavedChanges('yes',  'addTicket')">Yes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="updateStatusUnsavedModal" tabindex="-1" role="dialog" aria-labelledby="unsavedModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="unsavedModalLabel">Unsaved Changes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                You have unsaved changes. Do you want to continue?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" @click.prevent="toggleContinueUnsavedChanges('no', 'updateStatus')">No</button>
                <button type="button" class="btn btn-danger" id="confirmLeave" @click.prevent="toggleContinueUnsavedChanges('yes', 'updateStatus')">Yes</button>
            </div>
        </div>
    </div>
</div>