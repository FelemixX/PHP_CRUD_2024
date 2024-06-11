document.addEventListener('DOMContentLoaded', () => {
    const searchForm = document.querySelector('form.search-form');
    const tableSelector = searchForm?.querySelector('select.table-selector');
    const columnSelector = searchForm?.querySelector('select.column-selector');
    const searchInput = searchForm?.querySelector('input.search-input');

    const tableContainer = document.querySelector('.table-responsive');
    const resultTable = tableContainer?.querySelector('table');

    if (!searchForm && !searchInput && !tableSelector && !columnSelector && !resultTable) {
        return;
    }

    let tableSelectorBuffValue = '';
    let columnSelectorBuffValue = '';

    searchForm.addEventListener('change', event => {
        const target = event.target;

        if (target === tableSelector) {
            if (target?.value?.length) {
                if (target.value === tableSelectorBuffValue) {
                    columnSelector.closest('.mb-3.d-none')?.classList.remove('d-none');
                    searchInput.closest('.mb-3.d-none')?.classList.remove('d-none');

                    return;
                }

                tableSelectorBuffValue = target.value;
                this.getTableColumns(target.value);
            } else {
                columnSelector.closest('.mb-3')?.classList.add('d-none');
                columnSelector.value = '';

                searchInput.closest('.mb-3')?.classList.add('d-none');
                searchInput.value = '';
            }
        }

        if (target === columnSelector) {
            if (target?.value?.length) {
                columnSelectorBuffValue = target.value;
                searchInput.closest('.mb-3.d-none')?.classList.remove('d-none');
            } else {
                searchInput.closest('.mb-3')?.classList.add('d-none');
            }
        }
    });

    searchForm.addEventListener('submit', event => {
        event.preventDefault();

        if (!event?.currentTarget?.checkValidity()) {
            return;
        }

        const theadRows = resultTable.querySelector('.table-head__rows');
        const tbodyRows = resultTable.querySelector('.table-body');

        theadRows.querySelectorAll('td')?.forEach(row => {
            row.remove();
        });

        tbodyRows.querySelectorAll('td')?.forEach(rowBody => {
            rowBody.remove();
        });

        tableContainer?.classList.add('d-none');

        this.executeSearch(tableSelector?.value, columnSelector?.value, searchInput?.value);
    });

    getTableColumns = (tableName) => {
        if (!tableName?.length) {
            return;
        }

        fetch('/search/actions/get_table_columns.php?' + new URLSearchParams({
            tableName: tableName,
        })).then(response => {
            if (!response.ok) {
                return Promise.reject(response);
            }

            return response.json();
        }).then(json => {
            const columns = json.data;
            columnSelector.querySelectorAll('option:not([value=""])')?.forEach((option) => {
                option.remove();
            });

            columns?.forEach((column) => {
                let option = document.createElement('option');
                option.value = column;
                option.text = column;

                columnSelector.appendChild(option);
            });

            columnSelector.closest('.mb-3.d-none')?.classList.remove('d-none');
        }).catch(response => {
            let message = '';
            response?.json()?.then((json) => {
                message = json?.message;
            });

            console.error(message);
        })
    }

    executeSearch = (tableName, tableColumn, query) => {
        if (!tableName && !tableColumn && !query) {
            return;
        }

        fetch('/search/actions/search.php?' + new URLSearchParams({
            tableName: tableName,
            tableColumn: tableColumn,
            query: query,
        })).then(response => {
            if (!response.ok) {
                return Promise.reject(response);
            }

            return response.json();
        }).then(json => {
            const data = json?.data;
            const tableColumns = data?.columns;
            const tableItems = data?.items;
            const theadRows = resultTable.querySelector('.table-head__rows');
            const tbody = resultTable.querySelector('.table-body');

            if (!theadRows && !tbody || !tableItems?.length && !tableColumns?.length) {
                return;
            }

            tableColumns.forEach(item => {
                let td = document.createElement('td');
                td.classList.add('border');
                td.classList.add('border-success');
                td.classList.add('fw-bold');
                td.innerHTML = item;

                theadRows.appendChild(td);
            });

            tableItems.forEach(item => {
                let tr = document.createElement('tr');

                for (const [key, value] of Object.entries(item)) {
                    let td = document.createElement('td');

                    td.classList.add('border');
                    td.classList.add('border-success');
                    td.classList.add('fw-bold');
                    td.innerHTML = value;

                    tr.appendChild(td);
                }

                tbody.appendChild(tr)
            });

            tableContainer.classList.remove('d-none');
        }).catch(response => {
            let message = '';
            response?.json()?.then((json) => {
                message = json?.message;
            });

            console.error(message);
        })
    }

    searchForm.addEventListener('submit', event => {
        event.preventDefault();
    });
});
