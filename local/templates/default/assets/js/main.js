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
                                const modalInput = modalElement.querySelector('input');

                                modalInput.placeholder = element.innerText;
                                modalInput.value = element.innerText;
                                modalInput.disabled = element.dataset?.disabled;
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
            modalForm.querySelector('.text-danger').classList.add('d-none');
            modalForm.querySelector('.text-danger').innerText = '';

            modal.querySelector('.modal-header h1').innerText = '';
            modalForm.reset();
            modalForm.dataset.action = '';
            modalForm.dataset.id = '';

            modal.querySelectorAll('input:not([data-primary])')?.forEach(input => {
                input.disabled = false;
            });

            modal.querySelectorAll('input')?.forEach(input => {
                input.placeholder = '';
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

        let jsonObject = {};
        for (const [key, value] of data.entries()) {
            jsonObject[key] = value;
        }

        let jsonString = JSON.stringify(jsonObject);

        fetch(ajaxPath + action + '.php', {
            method: requestType,
            body: jsonString,
        })
            .then((response) => {
                const textContainer = document.querySelector('.text-danger');
                textContainer.innerText = '';
                textContainer.classList.add('d-none');

                if (!response.ok) {
                    return Promise.reject(response);
                }

                return response.json();
            })
            .then(() => {
                location.reload();
            })
            .catch(response => {
                response?.json().then((json) => {
                    const message = json?.message;
                    if (message) {
                        const textContainer = document.querySelector('.d-none.text-danger');
                        textContainer.innerText = message;
                        textContainer.classList.remove('d-none');
                    }
                });

            });
    }
});
