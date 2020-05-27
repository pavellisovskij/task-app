<?php if (\app\lib\Flash::is_set('success')) : ?>
    <div class="alert alert-success" role="alert">
        <?php echo \app\lib\Flash::get('success') ?>
    </div>
<?php endif; ?>

<?php if (\app\lib\Flash::is_set('empty')) : ?>
    <div class="alert alert-secondary" role="alert">
        <?php echo \app\lib\Flash::get('empty') ?>
    </div>
<?php else: ?>
    <?php echo $pagination->getTable() ?>
    <?php echo $pagination->getPagination() ?>
<?php endif; ?>