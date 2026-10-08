// main.js

document.addEventListener('DOMContentLoaded', () => {
    // ---- Form validation (uses validateField from validation.js) ----
    const form = document.querySelector('main form');

    function checkInput(input) {
        const errorSpan = document.getElementById(input.id + '-error');
        const ok = validateField(input.dataset.validate, input.value.trim());
        if (errorSpan) errorSpan.style.display = ok ? 'none' : 'block';
        input.classList.toggle('invalid', !ok);
        input.setAttribute('aria-invalid', ok ? 'false' : 'true');
        return ok;
    }

    if (form && window.validateField) {
        const inputs = form.querySelectorAll('[data-validate]');

        // Validate a field when the user leaves it, and re-check as they correct it
        inputs.forEach(input => {
            input.addEventListener('blur', () => checkInput(input));
            input.addEventListener('input', () => {
                if (input.classList.contains('invalid')) checkInput(input);
            });
        });

        form.addEventListener('submit', (e) => {
            let isValid = true;
            inputs.forEach(input => { if (!checkInput(input)) isValid = false; });
            if (!isValid) {
                e.preventDefault();
                const firstBad = form.querySelector('.invalid');
                if (firstBad) firstBad.focus();
            }
        });
    }

    // ---- Table search ----
    const searchInput = document.getElementById('tableSearch');
    const rowCount = document.getElementById('rowCount');
    const allRows = () => document.querySelectorAll('#dataTable tbody tr');

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const filter = searchInput.value.toLowerCase();
            let shown = 0;
            allRows().forEach(row => {
                const match = row.textContent.toLowerCase().includes(filter);
                row.style.display = match ? '' : 'none';
                if (match) shown++;
            });
            if (rowCount) rowCount.textContent = shown + (shown === 1 ? ' record' : ' records');
        });
    }

    // ---- Table sorting ----
    const headers = document.querySelectorAll('#dataTable th');
    const cellValue = (row, index) => {
        const text = row.children[index].textContent.trim();
        const num = Number(text.replace(/,/g, ''));
        return text !== '' && !isNaN(num) ? num : text.toLowerCase();
    };

    headers.forEach((header, index) => {
        header.tabIndex = 0;
        header.setAttribute('role', 'columnheader');
        const sort = () => {
            const tbody = header.closest('table').querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const asc = !header.classList.contains('asc');

            rows.sort((a, b) => {
                const x = cellValue(a, index), y = cellValue(b, index);
                const cmp = (typeof x === 'number' && typeof y === 'number')
                    ? x - y
                    : String(x).localeCompare(String(y));
                return asc ? cmp : -cmp;
            });

            headers.forEach(h => { h.classList.remove('asc', 'desc'); h.removeAttribute('aria-sort'); });
            header.classList.add(asc ? 'asc' : 'desc');
            header.setAttribute('aria-sort', asc ? 'ascending' : 'descending');
            rows.forEach(row => tbody.appendChild(row));
        };
        header.addEventListener('click', sort);
        header.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); sort(); }
        });
    });
});
