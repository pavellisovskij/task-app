<?php

namespace app\core;

abstract class Model
{
    public $db;
    public $table;

    public function __construct()
    {
        $this->db = new Db();
    }

    public function insert($values)
    {
        return $this->db->insert($this->table, $values);
    }

    public static function validateString(string $str, bool $starts_from_letter = true, int $min = 0, int $max = 255)
    {
        $lenght = null;
        $number = null;

        if ($starts_from_letter == true) {
            $regexp = '#[^a-zA-Z]#';
            if (preg_match($regexp, $str[0]) == 1) {
                $number = 'Поле должно начинаться с латинской буквы';
            } else {
                $number = false;
            }
        }

        $options = [
            'options' => [
                'min_range' => $min,
                'max_range' => $max
            ]
        ];

        if (filter_var(strlen($str), FILTER_VALIDATE_INT, $options) !== false) {
            $lenght = true;
        } else {
            $lenght = 'Длина поля не должна быть меньше ' . $min;
            if ($max != null) {
                $lenght .= ' и больше ' . $max;
            }
            $lenght .= ' символов';
        }

        if ($number === false && $lenght === true) {
            return true;
        } elseif ($lenght !== true) {
            return $lenght;
        } elseif ($number !== false) {
            return $number;
        }
    }
}