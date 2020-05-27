<?php

namespace app\controllers;

use app\core\Controller;
use app\core\View;
use app\lib\Flash;
use app\lib\PaginatedTable;
use app\models\Task;

class TaskController extends Controller
{
    public function indexAction()
    {
        $model = new Task();
        if ($model->getNumberOfTasks() == 0) {
            Flash::set('empty', 'Список пуст. Добавьте первую задачу.');
            $this->view->render('Задачи');
        } else {
            $pagination = new PaginatedTable([
                'Пользователь',
                'Email',
                'Описание задачи',
                'Выполнено',
                'Отредактировано'
            ], $model->table, 3, 3, isset($_SESSION['admin']));
            $this->view->render('Задачи', ['pagination' => $pagination]);
        }
    }

    public function createAction()
    {
        $this->view->render('Новая задача');
        unset($_SESSION['task']);
    }

    public function storeAction()
    {
        if (!empty($_POST)) {
            $result = Task::validateString($_POST['username'], true, 4, 20);

            if ($result === true) {
                $task = new Task();
                $result = $task->insert([
                    'username'      => htmlspecialchars($_POST['username'], ENT_QUOTES),
                    'email'         => htmlspecialchars($_POST['email'], ENT_QUOTES),
                    'description'   => htmlspecialchars($_POST['description'],ENT_QUOTES),
                    'edited'        => 0,
                    'done'          => 0
                ]);
                if (isset($result)) {
                    Flash::set('success', 'Задача успешно добавлена');
                    $this->view->redirect('/');
                }
            } else {
                $_SESSION['task'] = [
                    'username'      => $_POST['username'],
                    'email'         => $_POST['email'],
                    'description'   => $_POST['description']
                ];
                Flash::set('wrong_username', 'Поле "Имя пользователя": ' . $result);
                $this->view->redirect('/task/create');
            }
        }
    }

    public function editAction() {
        if (isset($_SESSION['admin'])) {
            $url = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
            $id = ['id' => $url[1]];
            $model = new Task();
            $task = $model->getTask($id);
            $this->view->render('Редактирование задачи', ['task' => $task]);
        }
        else {
            View::errorCode(403);
        }
    }

    public function updateAction() {
        if (isset($_SESSION['admin'])) {
            if (!empty($_POST)) {
                $data = [];
                $url = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
                if (isset($_POST['description']) && isset($_POST['edited'])) {
                    $data['description'] = htmlspecialchars($_POST['description'], ENT_QUOTES);
                    $data['edited'] = $_POST['edited'];
                }
                if (isset($_POST['done'])) {
                    $data['done'] = $_POST['done'];
                } else {
                    $data['done'] = 0;
                }
                $model = new Task();

                if ($model->updateTask($url[1], $data) !== false) {
                    Flash::set('success', 'Задача отредактирована');
                    $this->view->redirect('/');
                } else {
                    Flash::set('error', 'Что-то пошло не так');
                    $this->view->redirect('/task/' . $url[1]);
                }
            }
        }
        else {
            View::errorCode(403);
        }
    }
}