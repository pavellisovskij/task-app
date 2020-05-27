<form action="/task/store" method="post">
    <div class="row">
        <div class="form-group col-lg-6">
            <label for="username" class="form-label">Имя пользователя:</label>
            <input type="text" name="username" class="form-control" id="username" required value="<?php if (isset($_SESSION['task'])) echo $_SESSION['task']['username'] ?>">
        </div>
        <div class="form-group col-lg-6">
            <label for="email" class="form-label">E-mail:</label>
            <input type="email" name="email" class="form-control" id="email" required value="<?php if (isset($_SESSION['task'])) echo $_SESSION['task']['email'] ?>">
        </div>
    </div>
    <?php if (\app\lib\Flash::is_set('wrong_username')) : ?>
        <div class="alert alert-danger" role="alert">
            <?php echo \app\lib\Flash::get('wrong_username') ?>
        </div>
    <?php endif; ?>
    <div class="form-group">
        <label for="description">Описание задания:</label>
        <textarea class="form-control" id="description" name="description" required><?php if (isset($_SESSION['task'])) echo $_SESSION['task']['description'] ?></textarea>
    </div>
    <input type="submit" class="btn btn-primary" value="Создать">
</form>