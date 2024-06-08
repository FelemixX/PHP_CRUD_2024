document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById('tableActionModal');

    if (modal) {
        const modalForm = modal.querySelector('.modal-body form');

        modalForm.addEventListener('submit', event => {
            event.preventDefault();
        });

        modal.addEventListener('show.bs.modal', event => {
            const actionButton = event.relatedTarget;
            const dataset = actionButton?.dataset;
            if (!dataset || !dataset?.id || !dataset?.action
                || dataset.action !== 'create' && dataset.action !== 'update'
                && dataset.action !== 'delete'
            ) {
                return;
            }

            modal.querySelector('.modal-header h1').innerText = actionButton?.innerText;
            modal.querySelector('input:disabled').value = dataset.id;

            const action = dataset.action;
            const entityId = dataset.id;

            modalForm.dataset.action = action;
            modalForm.dataset.id = entityId;

            const rowsValues = actionButton?.closest('tr')?.querySelectorAll('[data-value-row-number]');

            if (rowsValues) {
                const rowsValuesModal = modal.querySelectorAll('[data-value-row-number]');

                rowsValues.forEach((element) => {
                    rowsValuesModal.forEach((modalElement) => {
                        if (modalElement.dataset.valueRowNumber === element.dataset.valueRowNumber) {
                            modalElement.querySelector('input').placeholder = element.innerText;
                        }
                    });
                });
            }
        });

        modal.addEventListener('hide.bs.modal', () => {
            modal.querySelector('input:disabled').value = '';
            modal.querySelector('.modal-header h1').innerText = '';
            modalForm.dataset.action = '';
            modalForm.dataset.id = '';
        });
    }

    function processAjaxRequest(requestType, action, data) {
        if (!requestType || action || data || !ajaxPath) {
            return;
        }

        if (requestType !== 'POST' || requestType !== 'DELETE' || requestType !== 'PATCH') {
            return;
        }

        fetch(ajaxPath + action + '.php', {
            method: requestType,
            body: JSON.stringify(data),
            headers: {
                "Content-Type": "application/json; charset=UTF-8",
            }
        })
            .then((response) => response.json())
            .then((data) => console.log(data))
    }
});
