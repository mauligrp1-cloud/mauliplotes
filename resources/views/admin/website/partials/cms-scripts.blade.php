<script>
const previewFrame = document.getElementById('preview-iframe');

function reloadPreview() {
    if (previewFrame) previewFrame.src = previewFrame.src;
}
function setPreviewWidth(w) {
    if (!previewFrame) return;
    previewFrame.style.width = w;
    const wrap = previewFrame.closest('.preview-frame-wrap');
    if (wrap) wrap.style.justifyContent = w !== '100%' ? 'center' : 'flex-start';
}
function showToast(msg, type = 'success') {
    const t = document.getElementById('cms-toast');
    const txt = document.getElementById('cms-toast-msg');
    if (!t || !txt) return;
    txt.textContent = msg;
    t.querySelector('.alert').className = `alert alert-${type} shadow-lg border-0 d-flex align-items-center gap-2 mb-0 py-2`;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3200);
}

// AJAX Toggle
document.querySelectorAll('.section-toggle-input').forEach(input => {
    input.addEventListener('change', function () {
        const sectionId = this.dataset.sectionId;
        const badge = document.querySelector(`.section-status-badge[data-section-id="${sectionId}"]`);
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        fetch(`/admin/website/section/${sectionId}/toggle`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                if (badge) {
                    badge.textContent = data.is_active ? 'Active' : 'Disabled';
                    badge.className = `badge section-status-badge ${data.is_active ? 'bg-success' : 'bg-secondary'}`;
                }
                showToast(data.message);
                setTimeout(reloadPreview, 400);
            }
        })
        .catch(() => showToast('Toggle failed.', 'danger'));
    });
});

// AJAX Form Save
document.querySelectorAll('.section-ajax-form').forEach(form => {
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const btn = this.querySelector('.btn-save-section');
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        btn.classList.add('saving');
        btn.disabled = true;
        fetch(this.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: new FormData(this),
        })
        .then(r => r.json())
        .then(data => {
            showToast(data.message || 'Section saved!');
            setTimeout(reloadPreview, 600);
        })
        .catch(() => { this.submit(); })
        .finally(() => { btn.classList.remove('saving'); btn.disabled = false; });
    });
});
</script>
