<div class="row justify-content-center">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">Вход для администратора</div>
            <div class="card-body">

                <form action="/admin/login" method="post">

                    <div class="form-group">
                        <label for="username" class="form-label">Имя пользователя:</label>
                        <input type="text" name="username" class="form-control" id="username" required value="<?php if(\app\lib\Flash::is_set('login')) echo \app\lib\Flash::get('login') ?>">
                    </div>
                    <?php if (\app\lib\Flash::is_set('wrong_username')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo \app\lib\Flash::get('wrong_username') ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="password" class="form-label">Пароль:</label>
                        <input type="password" name="password" class="form-control" id="password" required>
                    </div>
                    <?php if (\app\lib\Flash::is_set('wrong_password')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo \app\lib\Flash::get('wrong_password') ?>
                        </div>
                    <?php endif; ?>

                    <input type="submit" class="btn btn-primary" value="Войти">
                </form>
            </div>
        </div>
    </div>
</div>