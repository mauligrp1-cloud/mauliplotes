<!-- Confirmation Delete Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-2 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <h5 class="modal-title fw-bold text-dark mb-2" id="deleteConfirmModalLabel">Confirm Deletion</h5>
                <p class="text-secondary small mb-0" id="deleteConfirmModalMessage">
                    Are you sure you want to permanently delete this item? This action cannot be reversed.
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteConfirmForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm px-3 d-inline-flex align-items-center gap-1">
                        <i class="bi bi-trash"></i>
                        <span>Delete Record</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('deleteConfirmModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const actionUrl = button.getAttribute('data-action');
                const itemName = button.getAttribute('data-title') || 'this item';
                
                const form = deleteModal.querySelector('#deleteConfirmForm');
                const message = deleteModal.querySelector('#deleteConfirmModalMessage');
                
                if (actionUrl) {
                    form.action = actionUrl;
                }
                if (message) {
                    message.textContent = `Are you sure you want to delete "${itemName}"? This action cannot be reversed.`;
                }
            });
        }
    });
</script>
