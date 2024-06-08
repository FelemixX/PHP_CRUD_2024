document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById('tableActionModal');

    if (modal) {
        modal.addEventListener('show.bs.modal', event => {
            const actionButton = event.relatedTarget;
            console.log(actionButton);
        });
    }
});
