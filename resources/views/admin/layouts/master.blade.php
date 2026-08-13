<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>BittGold Admin - @stack('title')</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/jvectormap/jquery-jvectormap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/owl-carousel-2/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/owl-carousel-2/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css?v=' . time()) }}">
    <link rel="icon" type="image/png" href="{{ asset('siteadmin/images/titel2.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('siteadmin/images/titel2.png') }}">
    @stack('styles')
</head>
<body class="admin-dashboard">
    <div id="global-loader" class="global-loader" role="status" aria-live="polite" aria-label="Loading">
        <div class="loader-card"><span class="loader-mark"><i class="mdi mdi-chart-line"></i></span><span>Loading...</span></div>
    </div>
    <div id="toast-container" class="toast-container" aria-live="polite" aria-atomic="true"></div>
    <div class="container-scroller">
        
        @include('admin.layouts.pages.sidebar')
        <div class="container-fluid page-body-wrapper">
            @include('admin.layouts.pages.header')
            <div class="main-panel">
                @yield('content')
                
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © BittGold 2026. All rights reserved.</span>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmActionModal" tabindex="-1" aria-labelledby="confirmActionModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content bg-dark text-white gold-card border border-warning shadow" style="border-width: 1px !important;">
                <div class="modal-header border-bottom border-secondary py-2 px-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-warning text-dark rounded-circle p-2"><i class="mdi mdi-alert-circle-outline"></i></span>
                        <div>
                            <h5 class="modal-title mb-0 fs-6" id="confirmActionModalTitle">Confirm Action</h5>
                            <small class="text-muted">Admin approval confirmation</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3 px-3">
                    <p id="confirmActionModalBody" class="mb-0 text-white-50"></p>
                </div>
                <div class="modal-footer border-top border-secondary py-2 px-3 justify-content-end">
                    <button type="button" class="btn btn-outline-light btn-sm me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmActionModalSubmit" class="btn btn-sm btn-gold">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="userDashboardPreviewModal" tabindex="-1" aria-labelledby="userDashboardPreviewTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content bg-dark text-white gold-card border border-warning shadow">
                <div class="modal-header border-bottom border-secondary py-2 px-3">
                    <h5 class="modal-title mb-0 fs-6" id="userDashboardPreviewTitle"><i class="mdi mdi-eye-outline text-warning me-2"></i>Open User Dashboard</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3 px-3"><p class="mb-0 text-white-50">Open this member's dashboard in preview mode? You will remain logged in as admin.</p></div>
                <div class="modal-footer border-top border-secondary py-2 px-3">
                    <button type="button" class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-gold btn-sm" id="userDashboardPreviewConfirm">Open Dashboard</button>
                </div>
            </div>
        </div>
    </div>

@include('admin.layouts.pages.script')
<script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/bill-gold-ui.js') }}"></script>
@stack('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2 elements
        $('.js-select2').select2();
          $('select').select2?.();

        var confirmModalElement = document.getElementById('confirmActionModal');
        var confirmModal = new bootstrap.Modal(confirmModalElement);
        var confirmForm = null;
        var confirmButton = document.getElementById('confirmActionModalSubmit');

        function setupLiveValidation(form) {
            var fields = form.querySelectorAll('input[required], textarea[required], select[required]');
            var actionButtons = form.querySelectorAll('button[data-confirm-action], button[onclick*="confirmAddition"]');

            function validateField(field) {
                var value = field.value.trim();
                var message = '';
                if (!value) {
                    message = field.dataset.validationMessage || 'This field is required.';
                } else if (field.type === 'number' && (!Number.isFinite(Number(value)) || Number(value) < Number(field.min || 0))) {
                    message = field.dataset.validationMessage || 'Please enter a valid amount.';
                }

                field.setCustomValidity(message);
                var showMessage = field.dataset.touched === 'true';
                field.classList.toggle('is-invalid', Boolean(message) && showMessage);
                var feedback = field.parentElement.querySelector('.invalid-feedback');
                if (feedback) feedback.textContent = showMessage ? message : '';
                return !message && field.checkValidity();
            }

            function validateForm() {
                var valid = true;
                fields.forEach(function(field) { valid = validateField(field) && valid; });
                actionButtons.forEach(function(button) { button.disabled = !valid; });
                return valid;
            }

            fields.forEach(function(field) {
                field.addEventListener('input', function() { field.dataset.touched = 'true'; validateForm(); });
                field.addEventListener('change', function() { field.dataset.touched = 'true'; validateForm(); });
            });
            form.addEventListener('submit', function(event) {
                if (!validateForm()) event.preventDefault();
            });
            validateForm();
        }

        document.querySelectorAll('form[data-live-validation]').forEach(setupLiveValidation);

        document.querySelectorAll('[data-confirm-action]').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                confirmForm = this.closest('form');

                if (!confirmForm) {
                    return;
                }
                if (!confirmForm.checkValidity()) {
                    confirmForm.reportValidity();
                    return;
                }

                var title = this.dataset.confirmTitle || 'Please confirm';
                var text = this.dataset.confirmText || 'Are you sure you want to continue?';
                var submitText = this.dataset.confirmButton || 'Confirm';
                var isDanger = this.classList.contains('btn-danger');

                document.getElementById('confirmActionModalTitle').textContent = title;
                document.getElementById('confirmActionModalBody').textContent = text;
                confirmButton.textContent = submitText;
                confirmButton.className = 'btn btn-sm ' + (isDanger ? 'btn-danger' : 'btn-success');

                confirmModal.show();
            });
        });

        confirmButton.addEventListener('click', function() {
            if (confirmForm) {
                confirmModal.hide();
                if (confirmForm.checkValidity()) confirmForm.submit();
            }
        });

        var previewModal = new bootstrap.Modal(document.getElementById('userDashboardPreviewModal'));
        var previewUrl = null;
        document.querySelectorAll('.js-user-dashboard-preview').forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                previewUrl = this.href;
                previewModal.show();
            });
        });
        document.getElementById('userDashboardPreviewConfirm').addEventListener('click', function() {
            if (previewUrl) {
                window.open(previewUrl, '_blank', 'noopener');
                previewModal.hide();
            }
        });
    });

    @if (session('success'))
        BillGold.success(@json(session('success')));
    @elseif (session('error'))
        BillGold.error(@json(session('error')));
    @elseif (session('warning'))
        BillGold.toast('warning', @json(session('warning')));
    @elseif (session('info'))
        BillGold.toast('info', @json(session('info')));
    @endif
</script>
</html>

