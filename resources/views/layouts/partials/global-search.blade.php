<script>
document.querySelectorAll('.bill-global-search').forEach(function (container) {
    var input = container.querySelector('input');
    var results = container.querySelector('.global-search-results');
    var timer;
    var requestId = 0;

    function hideResults() {
        results.hidden = true;
        results.replaceChildren();
    }

    function addItem(result) {
        var link = document.createElement('a');
        link.className = 'global-search-result';
        link.href = result.url;

        var icon = document.createElement('i');
        icon.className = 'mdi ' + result.icon;
        var text = document.createElement('span');
        var title = document.createElement('strong');
        title.textContent = result.title;
        var meta = document.createElement('small');
        meta.textContent = result.meta;
        text.append(title, meta);
        link.append(icon, text);
        results.append(link);
    }

    input.addEventListener('input', function () {
        var query = input.value.trim();
        clearTimeout(timer);

        if (query.length < 2) {
            hideResults();
            return;
        }

        timer = setTimeout(function () {
            var currentRequest = ++requestId;
            fetch(container.dataset.searchUrl + '?q=' + encodeURIComponent(query), {
                headers: { 'Accept': 'application/json' }
            })
            .then(function (response) { return response.ok ? response.json() : { results: [] }; })
            .then(function (payload) {
                if (currentRequest !== requestId) return;
                results.replaceChildren();

                if (!payload.results.length) {
                    var empty = document.createElement('div');
                    empty.className = 'global-search-empty';
                    empty.textContent = 'No matching records found';
                    results.append(empty);
                } else {
                    payload.results.forEach(addItem);
                }

                results.hidden = false;
            })
            .catch(hideResults);
        }, 280);
    });

    document.addEventListener('click', function (event) {
        if (!container.contains(event.target)) hideResults();
    });
});
</script>
