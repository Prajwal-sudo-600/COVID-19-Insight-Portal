// main.js

document.addEventListener('DOMContentLoaded', () => {
    // Form validation logic if form exists
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', (e) => {
            let isValid = true;
            const inputs = form.querySelectorAll('input, textarea, select');
            
            inputs.forEach(input => {
                if (input.dataset.validate) {
                    const fieldName = input.dataset.validate;
                    const value = input.value;
                    const errorSpan = document.getElementById(input.id + '-error');
                    
                    if (window.validateField) {
                        if (!validateField(fieldName, value)) {
                            isValid = false;
                            if (errorSpan) errorSpan.style.display = 'block';
                            input.style.borderColor = 'red';
                        } else {
                            if (errorSpan) errorSpan.style.display = 'none';
                            input.style.borderColor = '#ccc';
                        }
                    }
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    // Table sorting and filtering
    const searchInput = document.getElementById('tableSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', () => {
            const filter = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll('#dataTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }

    const headers = document.querySelectorAll('#dataTable th');
    headers.forEach((header, index) => {
        header.addEventListener('click', () => {
            const table = header.closest('table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const isAsc = header.classList.contains('asc');
            
            rows.sort((a, b) => {
                const aText = a.children[index].textContent;
                const bText = b.children[index].textContent;
                
                return isAsc 
                    ? bText.localeCompare(aText, undefined, {numeric: true}) 
                    : aText.localeCompare(bText, undefined, {numeric: true});
            });
            
            headers.forEach(h => h.classList.remove('asc', 'desc'));
            header.classList.add(isAsc ? 'desc' : 'asc');
            
            rows.forEach(row => tbody.appendChild(row));
        });
    });
});
