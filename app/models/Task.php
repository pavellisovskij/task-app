<?php

namespace app\models;

use app\core\Model;

class Task extends Model
{
    public $table = 'tasks';

    public function get()
    {
        return $this->db->row('SELECT * FROM ' . $this->table);
    }

    public function getTask($id)
    {
        return $this->db->query("SELECT * FROM $this->table WHERE id = :id", $id)->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateTask($id, $data)
    {
        return $this->db->row("UPDATE $this->table SET description = :description, edited = :edited, done = :done WHERE id = :id", [
            'id'            => (int) $id,
            'description'   => $data['description'],
            'edited'        => (int) $data['edited'],
            'done'          => (int) $data['done']
        ]);
    }

    public function getNumberOfTasks()
    {
        return $this->db->query("SELECT COUNT(*) FROM $this->table")->fetchColumn();
    }

    public function getPartOfTasks($quantity, $list)
    {
        return $this->db->query("SELECT * FROM $this->table LIMIT $quantity OFFSET $list")->fetchAll(\PDO::FETCH_ASSOC);
    }
}