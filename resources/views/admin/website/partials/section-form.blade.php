{{--
    Reusable Section Edit Form Partial
    Variables expected:
        $sec          - PageSection model
        $updateRoute  - POST route for saving
--}}
<div class="section-card">
    <div class="section-card-header">
        <div style="flex:1;">
            <h3>{{ $sec->section_name }}</h3>
            <small>ID #{{ $sec->id }} · Sort: {{ $sec->sort_order }}</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge section-status-badge {{ $sec->is_active ? 'bg-success' : 'bg-secondary' }}"
                  data-section-id="{{ $sec->id }}">
                {{ $sec->is_active ? 'Active' : 'Disabled' }}
            </span>
            {{-- AJAX Toggle --}}
            <div class="form-check form-switch live-toggle mb-0" title="Toggle section on/off instantly">
                <input class="form-check-input section-toggle-input"
                       type="checkbox"
                       data-section-id="{{ $sec->id }}"
                       {{ $sec->is_active ? 'checked' : '' }}>
            </div>
        </div>
    </div>

    <form class="section-ajax-form" action="{{ $updateRoute }}" method="POST">
        @csrf
        {{-- Hidden is_active (controlled by toggle, not form checkbox) --}}
        <input type="hidden" name="is_active" class="is-active-hidden" value="{{ $sec->is_active ? '1' : '0' }}">
        <input type="hidden" name="sort_order" value="{{ $sec->sort_order }}">

        <div class="section-card-body">
            <div class="row g-2">
                {{-- Title --}}
                <div class="col-12">
                    <label class="field-label">Heading / Title</label>
                    <input type="text" name="title" value="{{ $sec->title }}"
                           class="form-control form-control-sm"
                           placeholder="Section heading…">
                </div>

                {{-- Subtitle --}}
                <div class="col-12">
                    <label class="field-label">Eyebrow / Subtitle</label>
                    <input type="text" name="subtitle" value="{{ $sec->subtitle }}"
                           class="form-control form-control-sm"
                           placeholder="e.g. OUR LEGACY">
                </div>

                {{-- Content --}}
                <div class="col-12">
                    <label class="field-label">Body Copy</label>
                    <textarea name="content" class="form-control form-control-sm" rows="3"
                              placeholder="Description text…">{{ $sec->content }}</textarea>
                </div>

                {{-- Image --}}
                <div class="col-12">
                    <label class="field-label">Image URL</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="image" id="img_{{ $sec->id }}"
                               value="{{ $sec->image }}" class="form-control"
                               placeholder="https://…">
                        <button type="button" class="btn btn-outline-secondary"
                                onclick="openMediaPicker('img_{{ $sec->id }}')">
                            <i class="bi bi-images"></i>
                        </button>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="col-6">
                    <label class="field-label">Button Text</label>
                    <input type="text" name="button_text" value="{{ $sec->button_text }}"
                           class="form-control form-control-sm" placeholder="e.g. Explore">
                </div>
                <div class="col-6">
                    <label class="field-label">Button URL</label>
                    <input type="text" name="button_url" value="{{ $sec->button_url }}"
                           class="form-control form-control-sm font-monospace" placeholder="/contact">
                </div>
                <div class="col-6">
                    <label class="field-label">Secondary Button</label>
                    <input type="text" name="secondary_button_text" value="{{ $sec->secondary_button_text }}"
                           class="form-control form-control-sm" placeholder="e.g. Learn More">
                </div>
                <div class="col-6">
                    <label class="field-label">Secondary URL</label>
                    <input type="text" name="secondary_button_url" value="{{ $sec->secondary_button_url }}"
                           class="form-control form-control-sm font-monospace" placeholder="/about">
                </div>

                {{-- Dynamic Options (if any) --}}
                @if($sec->options && is_array($sec->options))
                    <div class="col-12">
                        <label class="field-label">Advanced Options (JSON)</label>
                        <details class="border rounded-2 p-2 bg-light">
                            <summary class="small fw-semibold text-muted" style="cursor:pointer;">
                                {{ count($sec->options) }} option(s) — click to expand
                            </summary>
                            <div class="mt-2">
                                @foreach($sec->options as $optKey => $optVal)
                                    @if(is_string($optVal) || is_numeric($optVal))
                                        <div class="mb-2">
                                            <label class="field-label">{{ $optKey }}</label>
                                            <input type="text"
                                                   name="options[{{ $optKey }}]"
                                                   value="{{ $optVal }}"
                                                   class="form-control form-control-sm">
                                        </div>
                                    @elseif(is_array($optVal))
                                        @foreach($optVal as $i => $item)
                                            @if(is_array($item))
                                                <div class="p-2 bg-white rounded border mb-2">
                                                    <div class="field-label mb-1">{{ $optKey }}[{{ $i }}]</div>
                                                    @foreach($item as $k => $v)
                                                        @if(is_string($v) || is_numeric($v))
                                                            <div class="mb-1">
                                                                <label class="field-label" style="font-size:10px;">{{ $k }}</label>
                                                                @if(strlen((string)$v) > 60)
                                                                    <textarea name="options[{{ $optKey }}][{{ $i }}][{{ $k }}]"
                                                                              class="form-control form-control-sm" rows="2">{{ $v }}</textarea>
                                                                @else
                                                                    <input type="text"
                                                                           name="options[{{ $optKey }}][{{ $i }}][{{ $k }}]"
                                                                           value="{{ $v }}"
                                                                           class="form-control form-control-sm">
                                                                @endif
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @elseif(is_string($item) || is_numeric($item))
                                                <div class="mb-1">
                                                    <label class="field-label" style="font-size:10px;">{{ $optKey }}[{{ $i }}]</label>
                                                    <input type="text"
                                                           name="options[{{ $optKey }}][{{ $i }}]"
                                                           value="{{ $item }}"
                                                           class="form-control form-control-sm">
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            </div>
                        </details>
                    </div>
                @endif
            </div>
        </div>

        <div class="section-card-footer">
            <span class="small text-muted">Changes saved to DB + preview auto-refreshes</span>
            <button type="submit" class="btn btn-sm btn-save-section"
                    style="background:#f35b25;color:white;font-size:12px;font-weight:600;">
                <span class="save-text"><i class="bi bi-check2 me-1"></i>Save</span>
                <span class="save-spinner spinner-border spinner-border-sm" role="status"></span>
            </button>
        </div>
    </form>
</div>

<script>
// Keep hidden is_active in sync with the toggle
(function() {
    const toggle = document.querySelector('.section-toggle-input[data-section-id="{{ $sec->id }}"]');
    const hidden = toggle?.closest('.section-card')?.querySelector('.is-active-hidden');
    if (toggle && hidden) {
        toggle.addEventListener('change', () => hidden.value = toggle.checked ? '1' : '0');
    }
})();
</script>
