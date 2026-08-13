<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>BittGold - @stack('title')</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css?v=' . time()) }}">
     <link rel="icon" type="image/png" href="{{ asset('siteadmin/images/titel2.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('siteadmin/images/titel2.png') }}">
    @stack('styles')
</head>
<body class="admin-dashboard user-dashboard">
    <div id="global-loader" class="global-loader" role="status" aria-live="polite"><div class="loader-card"><span class="loader-mark"><i class="mdi mdi-chart-line"></i></span><span>Loading...</span></div></div>
    <div id="toast-container" class="toast-container" aria-live="polite" aria-atomic="true"></div>
    <div class="container-scroller">
        @include('user.layouts.pages.sidebar')
        <div class="container-fluid page-body-wrapper">
            @include('user.layouts.pages.header')
            <div class="main-panel">
                @yield('content')
                <footer class="footer"><div class="d-sm-flex justify-content-center justify-content-sm-between"><span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © BittGold 2026. All rights reserved.</span></div></footer>
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
                            <small class="text-muted">Please confirm</small>
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

    @include('user.layouts.pages.script')
    @stack('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2 if present
            $('.js-select2').select2?.();
             $('select').select2?.();

            var confirmModalElement = document.getElementById('confirmActionModal');
            var confirmModal = new bootstrap.Modal(confirmModalElement);
            var confirmForm = null;
            var confirmButton = document.getElementById('confirmActionModalSubmit');

            document.querySelectorAll('[data-confirm-action]').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    confirmForm = this.closest('form');

                    if (!confirmForm) return;

                    var title = this.dataset.confirmTitle || 'Please confirm';
                    var text = this.dataset.confirmText || 'Are you sure you want to continue?';
                    var submitText = this.dataset.confirmButton || 'Confirm';
                    var isDanger = this.classList.contains('btn-danger');

                    document.getElementById('confirmActionModalTitle').textContent = title;
                    document.getElementById('confirmActionModalBody').textContent = text;
                    confirmButton.textContent = submitText;
                    confirmButton.className = 'btn btn-sm ' + (isDanger ? 'btn-danger' : 'btn-gold');

                    confirmModal.show();
                });
            });

            confirmButton.addEventListener('click', function() {
                if (confirmForm) {
                    confirmModal.hide();
                    confirmForm.submit();
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
</body>
</html>
