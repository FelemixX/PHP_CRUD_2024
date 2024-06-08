<?php /** array $modalRows */ ?>
<?php if (!empty($modalRows)): ?>
    <noindex>
        <div class="modal fade" id="tableActionModal" tabindex="-1" aria-labelledby="tableActionModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="tableActionModalTitle"></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <?php foreach ($modalRows as $idx => $row): ?>
                                <div class="mb-3" data-value-row-number="<?= $idx ?>">
                                    <label for="<?= $row ?>-table-value" class="col-form-label">
                                        <?= $row ?>:
                                    </label>
                                    <input type="text" placeholder="" class="form-control" id="<?= $row ?>-table-value" <?= $row == 'ID' ? 'disabled' : '' ?>>
                                </div>
                            <?php endforeach; ?>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">Применить</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    </noindex>
<?php endif; ?>
