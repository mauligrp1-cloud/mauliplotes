<!-- Reusable Media Picker Modal -->
<div class="modal fade" id="mediaPickerModal" tabindex="-1" aria-labelledby="mediaPickerModalLabel" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom py-3 px-4">
                <div>
                    <h5 class="modal-title fw-bold text-dark mb-0" id="mediaPickerModalLabel">
                        <i class="bi bi-images me-2 text-brand"></i> Select Media Asset
                    </h5>
                    <span class="text-muted small">Choose an existing uploaded file or upload a new one directly.</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Nav Tabs: Browse vs Upload -->
            <div class="px-4 pt-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                <ul class="nav nav-tabs border-0" id="mediaPickerTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-semibold" id="browse-tab" data-bs-toggle="tab" data-bs-target="#browse-tab-pane" type="button" role="tab" aria-selected="true">
                            <i class="bi bi-grid me-1"></i> Browse Library
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold" id="quick-upload-tab" data-bs-toggle="tab" data-bs-target="#quick-upload-pane" type="button" role="tab" aria-selected="false">
                            <i class="bi bi-cloud-arrow-up me-1"></i> Quick Upload
                        </button>
                    </li>
                </ul>

                <!-- Live Search inside Modal -->
                <div class="input-group input-group-sm mb-2" style="max-width: 250px;">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" id="modalMediaSearchInput" class="form-control" placeholder="Search media...">
                </div>
            </div>

            <div class="modal-body p-4">
                <div class="tab-content" id="mediaPickerTabContent">
                    <!-- Browse Tab -->
                    <div class="tab-pane fade show active" id="browse-tab-pane" role="tabpanel" tabindex="0">
                        <div id="modalMediaLoading" class="text-center py-5">
                            <div class="spinner-border text-brand" role="status">
                                <span class="visually-hidden">Loading media...</span>
                            </div>
                            <div class="text-muted small mt-2">Loading library assets...</div>
                        </div>

                        <!-- Media Grid inside Modal -->
                        <div class="row g-3 d-none" id="modalMediaGrid"></div>

                        <div id="modalMediaEmpty" class="text-center py-5 text-muted d-none">
                            <i class="bi bi-images fs-1 d-block opacity-50 mb-2"></i>
                            <p class="small mb-0">No media assets found matching your query.</p>
                        </div>
                    </div>

                    <!-- Quick Upload Tab -->
                    <div class="tab-pane fade" id="quick-upload-pane" role="tabpanel" tabindex="0">
                        <div class="p-4 text-center border border-2 border-dashed rounded-3 bg-light">
                            <i class="bi bi-cloud-arrow-up display-4 text-brand mb-2 d-block"></i>
                            <h6 class="fw-bold mb-1">Select file to upload to Central Library</h6>
                            <p class="text-muted small mb-3">Supported formats: JPG, PNG, WebP, SVG, PDF, MP4 (Max 200MB)</p>
                            <input type="file" id="modalQuickFileInput" class="form-control w-50 mx-auto mb-3" accept="image/*,.pdf,.mp4,.mov">
                            <button type="button" id="modalQuickUploadBtn" class="btn btn-brand btn-sm px-4">
                                <span id="modalUploadBtnText"><i class="bi bi-upload me-1"></i> Upload & Select</span>
                                <span id="modalUploadSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-top py-2 px-4 bg-light d-flex justify-content-between">
                <span class="text-muted small" id="modalSelectedFileNotice">No file selected. Click an item to select it.</span>
                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    let activeMediaTargetInput = null;
    let activeMediaPreviewTarget = null;

    function openMediaPicker(targetInputId, previewTargetId = null) {
        activeMediaTargetInput = document.getElementById(targetInputId);
        activeMediaPreviewTarget = previewTargetId ? document.getElementById(previewTargetId) : null;

        const modalEl = document.getElementById('mediaPickerModal');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();

        loadModalMedia();
    }

    function loadModalMedia(searchTerm = '') {
        const loading = document.getElementById('modalMediaLoading');
        const grid = document.getElementById('modalMediaGrid');
        const empty = document.getElementById('modalMediaEmpty');

        loading.classList.remove('d-none');
        grid.classList.add('d-none');
        empty.classList.add('d-none');

        const url = new URL("{{ route('admin.media.index') }}", window.location.origin);
        if (searchTerm) {
            url.searchParams.set('search', searchTerm);
        }

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(json => {
            loading.classList.add('d-none');
            grid.innerHTML = '';

            if (json.data && json.data.length > 0) {
                grid.classList.remove('d-none');
                json.data.forEach(item => {
                    const col = document.createElement('div');
                    col.className = 'col-4 col-md-3 col-lg-2';
                    
                    let thumbHtml = '';
                    if (item.is_image) {
                        thumbHtml = `<img src="${item.url}" alt="${item.alt_text || ''}" class="w-100 h-100 object-fit-cover rounded-2">`;
                    } else if (item.type === 'pdf') {
                        thumbHtml = `<div class="d-flex flex-column align-items-center justify-content-center text-danger h-100"><i class="bi bi-file-earmark-pdf-fill fs-2"></i><span style="font-size:0.65rem;">PDF</span></div>`;
                    } else {
                        thumbHtml = `<div class="d-flex flex-column align-items-center justify-content-center text-secondary h-100"><i class="bi bi-file-earmark-text-fill fs-2"></i></div>`;
                    }

                    col.innerHTML = `
                        <div class="card card-panel h-100 border p-1 text-center cursor-pointer media-select-card" data-url="${item.url}" data-title="${item.title || item.original_filename}" style="cursor: pointer;">
                            <div class="ratio ratio-1x1 bg-light rounded-2 overflow-hidden mb-1">
                                ${thumbHtml}
                            </div>
                            <div class="small text-truncate text-dark px-1" style="font-size: 0.72rem;" title="${item.title || item.original_filename}">
                                ${item.title || item.original_filename}
                            </div>
                        </div>
                    `;

                    col.querySelector('.media-select-card').addEventListener('click', function() {
                        const selectedUrl = this.getAttribute('data-url');
                        applySelectedMedia(selectedUrl);
                    });

                    grid.appendChild(col);
                });
            } else {
                empty.classList.remove('d-none');
            }
        })
        .catch(err => {
            loading.classList.add('d-none');
            empty.classList.remove('d-none');
        });
    }

    function applySelectedMedia(url) {
        if (url && (url.includes('/storage/'))) {
            // Normalize any accidental http://localhost or http://127.0.0.1:8000 prefix
            url = url.replace(/^https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?\/storage\//, '/storage/');
        }
        if (activeMediaTargetInput) {
            activeMediaTargetInput.value = url;
            activeMediaTargetInput.dispatchEvent(new Event('change'));
            activeMediaTargetInput.dispatchEvent(new Event('input'));
        }
        if (activeMediaPreviewTarget) {
            activeMediaPreviewTarget.src = url;
            activeMediaPreviewTarget.classList.remove('d-none');
        }

        const modalEl = document.getElementById('mediaPickerModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) {
            modal.hide();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('modalMediaSearchInput');
        if (searchInput) {
            let debounceTimer;
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    loadModalMedia(this.value.trim());
                }, 350);
            });
        }

        // Quick upload handling
        const quickUploadBtn = document.getElementById('modalQuickUploadBtn');
        const quickFileInput = document.getElementById('modalQuickFileInput');

        if (quickUploadBtn && quickFileInput) {
            quickUploadBtn.addEventListener('click', function() {
                if (!quickFileInput.files || quickFileInput.files.length === 0) {
                    alert('Please choose a file to upload first.');
                    return;
                }

                const formData = new FormData();
                formData.append('file', quickFileInput.files[0]);
                formData.append('_token', '{{ csrf_token() }}');

                const btnText = document.getElementById('modalUploadBtnText');
                const spinner = document.getElementById('modalUploadSpinner');
                btnText.classList.add('d-none');
                spinner.classList.remove('d-none');
                quickUploadBtn.disabled = true;

                fetch("{{ route('admin.media.store') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(json => {
                    btnText.classList.remove('d-none');
                    spinner.classList.add('d-none');
                    quickUploadBtn.disabled = false;

                    if (json.success && json.media && json.media.length > 0) {
                        applySelectedMedia(json.media[0].url);
                    } else {
                        alert(json.message || 'Upload failed.');
                    }
                })
                .catch(err => {
                    btnText.classList.remove('d-none');
                    spinner.classList.add('d-none');
                    quickUploadBtn.disabled = false;
                    alert('An error occurred during upload.');
                });
            });
        }
    });
</script>
