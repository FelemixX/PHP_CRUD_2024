document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById('tableActionModal');

    if (modal) {
        const modalForm = modal.querySelector('.modal-body form');

        modalForm.addEventListener('submit', event => {
            event.preventDefault();
            const target = event.target;
            if (!target?.dataset?.action) {
                return;
            }

            if (target.dataset.action !== 'create' && !target.dataset.id) {
                return;
            }

            const dataset = target.dataset;
            const formData = new FormData(target);

            if (dataset?.id) {
                formData.append('ID', dataset.id);
            }
            formData.append('ACTION', dataset.action);

            let requestType = '';
            switch (dataset.action) {
                case 'update':
                    requestType = 'PATCH';
                    break;
                case 'delete':
                    requestType = 'DELETE';
                    break;
                case 'create':
                    requestType = 'POST';
                    break;
            }

            processAjaxRequest(requestType, formData);
        });

        modal.addEventListener('show.bs.modal', event => {
            const actionButton = event.relatedTarget;
            const dataset = actionButton?.dataset;
            if (!dataset || !dataset?.action
                || dataset.action !== 'create' && dataset.action !== 'update'
                && dataset.action !== 'delete'
            ) {
                return;
            }

            if (dataset?.action !== 'create' && !dataset?.id) {
                return;
            }

            modal.querySelector('.modal-header h1').innerText = actionButton?.innerText;
            if (dataset?.action === 'create') {
                modalForm.dataset.action = dataset?.action;
                return;
            }

            modal.querySelector('input:disabled').value = dataset.id;

            const action = dataset.action;
            const entityId = dataset.id;

            modalForm.dataset.action = action;
            modalForm.dataset.id = entityId;

            if (action !== 'delete') {
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

                return;
            }

            modal.querySelectorAll('input:not([data-primary])').forEach(input => {
                input.disabled = true;
            });
        });

        modal.addEventListener('hide.bs.modal', () => {
            modal.querySelector('input:disabled').value = '';
            modal.querySelector('.modal-header h1').innerText = '';
            modalForm.dataset.action = '';
            modalForm.dataset.id = '';

            modal.querySelectorAll('input:not([data-primary])').forEach(input => {
                input.disabled = false;
            });
        });
    }

    function processAjaxRequest(requestType, data) {
        if (!requestType || !data || !ajaxPath) {
            return;
        }

        if (requestType !== 'POST' && requestType !== 'DELETE' && requestType !== 'PATCH') {
            return;
        }

        const action = data.get('ACTION');

        fetch(ajaxPath + action + '.php', {
            method: requestType,
            body: JSON.stringify(data),
        })
            .then((response) => console.log(response))
    }
});
