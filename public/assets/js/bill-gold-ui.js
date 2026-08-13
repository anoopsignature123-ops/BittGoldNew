(function (window, document, $) {
    'use strict';

    var loader = document.getElementById('global-loader');
    var toastContainer = document.getElementById('toast-container');

    function showLoader() { if (loader) loader.classList.add('is-visible'); }
    function hideLoader() { if (loader) loader.classList.remove('is-visible'); }

    function toast(type, message, title) {
        if (!toastContainer) return;
        var safeType = ['success', 'error', 'info', 'warning'].indexOf(type) > -1 ? type : 'info';
        var icon = { success: 'check-circle', error: 'close-circle', warning: 'alert', info: 'information' }[safeType];
        var item = document.createElement('div');
        item.className = 'bill-toast bill-toast-' + safeType;
        item.innerHTML = '<i class="mdi mdi-' + icon + '"></i><div><strong>' + (title || safeType.charAt(0).toUpperCase() + safeType.slice(1)) + '</strong><span></span></div><button type="button" aria-label="Close notification"><i class="mdi mdi-close"></i></button>';
        item.querySelector('span').textContent = message;
        item.querySelector('button').addEventListener('click', function () { item.remove(); });
        toastContainer.appendChild(item);
        window.setTimeout(function () { item.classList.add('is-leaving'); window.setTimeout(function () { item.remove(); }, 220); }, 4200);
    }

    function initSelect2(context) {
        if (!$ || !$.fn.select2) return;
        $(context || document).find('.js-select2').each(function () {
            var $select = $(this);
            if ($select.hasClass('select2-hidden-accessible')) return;
            $select.select2({ width: '100%', minimumResultsForSearch: 8, placeholder: $select.data('placeholder') || 'Select an option' });
        });
    }

    window.BillGold = { showLoader: showLoader, hideLoader: hideLoader, toast: toast, success: function (message, title) { toast('success', message, title); }, error: function (message, title) { toast('error', message, title); }, initSelect2: initSelect2 };

    document.addEventListener('DOMContentLoaded', function () {
        initSelect2();
        document.querySelectorAll('[data-toast]').forEach(function (button) {
            button.addEventListener('click', function () { toast(button.dataset.toast, button.dataset.toastMessage || 'Action completed successfully.'); });
        });
        document.querySelectorAll('form').forEach(function (form) { form.addEventListener('submit', showLoader); });
        document.querySelectorAll('a[href]').forEach(function (link) {
            link.addEventListener('click', function () {
                var href = link.getAttribute('href');
                if (href && href.charAt(0) !== '#' && !link.hasAttribute('data-no-loader') && !link.target) showLoader();
            });
        });
    });
    window.addEventListener('load', hideLoader);
})(window, document, window.jQuery);
