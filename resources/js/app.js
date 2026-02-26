import './bootstrap';

const searchInput = document.getElementById('home-space-search');
const subSpaceTypeCheckboxes = document.querySelectorAll('[data-home-subspace-type]');
const resultsPanel = document.getElementById('home-live-search-results');
const resultsList = document.getElementById('home-live-search-list');
const emptyState = document.getElementById('home-live-search-empty');

if (searchInput && subSpaceTypeCheckboxes.length && resultsPanel && resultsList && emptyState) {
    const endpoint = searchInput.dataset.searchEndpoint;
    let debounceTimer;

    const hideResults = () => {
        resultsPanel.classList.add('hidden');
        resultsList.innerHTML = '';
        emptyState.classList.add('hidden');
    };

    const renderItems = (items) => {
        resultsList.innerHTML = '';

        if (!items.length) {
            emptyState.classList.remove('hidden');
            resultsPanel.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');

        items.forEach((item) => {
            const li = document.createElement('li');
            li.innerHTML = `
                <a href="${item.url}" class="flex items-center gap-3 p-3 hover:bg-gray-50 transition">
                    <img src="${item.image}" alt="${item.title}" class="w-14 h-14 rounded-lg object-cover border border-gray-100">
                    <div class="text-right">
                        <p class="font-semibold text-sm text-gray-900">${item.title}</p>
                        <p class="text-xs text-gray-500">تعداد زیرمجموعه: ${item.sub_spaces_count}</p>
                    </div>
                </a>
            `;
            resultsList.appendChild(li);
        });

        resultsPanel.classList.remove('hidden');
    };

    const fetchResults = async () => {
        const q = searchInput.value.trim();
        const selectedTypes = Array.from(subSpaceTypeCheckboxes)
            .filter((input) => input.checked)
            .map((input) => input.value);

        if (!q && !selectedTypes.length) {
            hideResults();
            return;
        }

        const params = new URLSearchParams();

        if (q) params.set('q', q);
        selectedTypes.forEach((type) => params.append('subspace_types[]', type));

        try {
            const response = await fetch(`${endpoint}?${params.toString()}`, {
                headers: { Accept: 'application/json' },
            });

            if (!response.ok) {
                hideResults();
                return;
            }

            const payload = await response.json();
            renderItems(payload.data ?? []);
        } catch {
            hideResults();
        }
    };

    const scheduleFetch = () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchResults, 300);
    };

    searchInput.addEventListener('input', scheduleFetch);
    subSpaceTypeCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', scheduleFetch);
    });

    document.addEventListener('click', (event) => {
        if (
            !resultsPanel.contains(event.target) &&
            event.target !== searchInput
        ) {
            hideResults();
        }
    });
}
