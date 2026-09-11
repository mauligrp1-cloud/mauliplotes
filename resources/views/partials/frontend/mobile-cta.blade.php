@php
    $whatsapp = \App\Models\Setting::get('whatsapp') ?: '+91 87880 74549';
@endphp

<!-- Floating Corner WhatsApp Widget -->
<div class="position-fixed bottom-0 end-0 p-3 p-md-4" style="z-index: 1050;">
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}?text={{ urlencode('Hello Mauli Infra, I am interested in exploring plotted layouts in Nagpur.') }}" target="_blank" rel="noopener noreferrer" class="btn btn-success rounded-circle shadow-lg p-0 d-flex align-items-center justify-content-center hover-lift" style="width: 54px; height: 54px; background-color: #25D366; border: 2px solid #ffffff;" title="Chat with us on WhatsApp">
        <i class="bi bi-whatsapp fs-3 text-white"></i>
    </a>
</div>
