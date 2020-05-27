<?php

namespace app\controllers;

use app\core\Controller;
use app\lib\Flash;
use app\models\User;

class AdminController extends Controller
{
    public function indexAction()
    {
        $this->view->render('Вход для администратора');
    }

    public function loginAction()
    {
        if (isset($_POST['username'])) {
            $model = new User();
            $admin = $model->getAdmin(htmlspecialchars($_POST['username']));
            if ($admin == false) {
                Flash::set('wrong_username', 'Не существует пользователя с таким именем');
                Flash::set('login', $_POST['username']);
                return $this->view->redirect('/admin');
            }
            else {
                if (!password_verify(htmlspecialchars($_POST['password']), $admin['password'])) {
                    Flash::set('wrong_password', 'Не верный пароль');
                    Flash::set('login', $_POST['username']);;
                    return $this->view->redirect('/admin');
                }
                else {
                    $_SESSION['admin'] = $admin;
                    return $this->view->redirect('/');
                }
            }

        }
    }

    public function logoutAction() {
        $_SESSION['admin'] = null;
        $this->view->redirect('/');
    }
}