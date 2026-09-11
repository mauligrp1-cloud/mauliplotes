<style>
.cms-split-layout {
    display: grid;
    grid-template-columns: 420px 1fr;
    gap: 0;
    height: calc(100vh - 180px);
    min-height: 600px;
}
.cms-edit-panel {
    overflow-y: auto;
    border-right: 1px solid #e2e8f0;
    background: #f8fafc;
}
.cms-preview-panel {
    position: relative;
    background: #0f172a;
    display: flex;
    flex-direction: column;
}
.cms-preview-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    background: #1e293b;
    color: #94a3b8;
    font-size: 12px;
    gap: 8px;
    flex-shrink: 0;
}
.preview-url-badge {
    background: #0f172a;
    border: 1px solid #334155;
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 11px;
    color: #64748b;
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.section-nav { background: white; border-bottom: 1px solid #e2e8f0; }
.section-nav .nav-link {
    border-radius: 0;
    border-bottom: 2px solid transparent;
    font-size: 11.5px;
    font-weight: 600;
    padding: 9px 12px;
    white-space: nowrap;
    color: #64748b;
}
.section-nav .nav-link.active { border-bottom-color: #f35b25; color: #f35b25; background: #fff7f4; }
.section-card { background: white; border: 1px solid #e2e8f0; border-radius: 10px; margin: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
.section-card-header { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #f8fafc; border-bottom: 1px solid #f1f5f9; gap: 8px; }
.section-card-header h3 { font-size: 13px; font-weight: 700; margin: 0; color: #1e293b; flex: 1; }
.section-card-header small { font-size: 11px; color: #94a3b8; display: block; margin-top: 1px; }
.section-card-body { padding: 14px 16px; }
.section-card-footer { padding: 10px 16px; background: #fafafa; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
.live-toggle .form-check-input { width: 38px; height: 20px; cursor: pointer; }
.field-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 4px; }
.save-spinner { display: none; }
.saving .save-spinner { display: inline-block; }
.saving .save-text { display: none; }
.cms-toast { position: fixed; bottom: 20px; right: 20px; z-index: 9999; min-width: 260px; transform: translateY(80px); opacity: 0; transition: all 0.3s ease; }
.cms-toast.show { transform: translateY(0); opacity: 1; }
@media (max-width: 991px) {
    .cms-split-layout { grid-template-columns: 1fr; height: auto; }
    .cms-edit-panel { height: auto; overflow: visible; }
    .cms-preview-panel { height: 450px; }
}
</style>
