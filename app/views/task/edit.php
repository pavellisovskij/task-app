<?php if (\app\lib\Flash::is_set('error')) : ?>
    <div class="alert alert-danger" role="alert">
        <?php echo \app\lib\Flash::get('error') ?>
    </div>
<?php endif; ?>

<form action="/task/<?php echo $task['id'] ?>/update" method="post">
    <div class="row">
        <div class="form-group col-lg-6">
            <label for="username" class="form-label">Имя пользователя:</label>
            <input type="text" name="username" class="form-control" id="username" disabled value="<?php echo $task['username'] ?>">
        </div>
        <div class="form-group col-lg-6">
            <label for="email" class="form-label">E-mail:</label>
            <input type="email" name="email" class="form-control" id="email" disabled value="<?php echo $task['email'] ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="description">Описание задания:</label>
        <textarea class="form-control" id="description" name="description" onchange="addHiddenCheckbox()"><?php echo $task['description'] ?></textarea>
    </div>

    <div id="hidden" hidden>
        <input class="form-check-input" type="checkbox" id="edited" name="edited" <?php if ($task['edited'] == 1) echo 'checked' ?> value="1">
    </div>

    <div class="form-check" style="margin-bottom: 15px">
        <label class="form-check-label" for="done">
            <input class="form-check-input" type="checkbox" id="done" name="done" <?php if ($task['done'] == 1) echo 'checked' ?> value="1">
            Выполнено
        </label>
    </div>
    <input type="submit" class="btn btn-primary" value="Принять">
</form>

<script>
    function addHiddenCheckbox() {
        //document.getElementById('hidden').innerHTML='<input type="checkbox" name="edited" value="2" checked />';
        let edited = document.getElementById('edited');
        edited.checked = true;
    }
</script>