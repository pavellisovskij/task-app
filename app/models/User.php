<?php

namespace app\models;

use app\core\Model;

class User extends Model
{
    public $table = 'admin';

    public function getAdmin($login) {
        return $this->db->query('SELECT * FROM ' . $this->table . ' WHERE username = :login', ['login' => $login])
            ->fetch(\PDO::FETCH_ASSOC);
    }
}