<?php

namespace app\lib;

use app\core\Db;

class PaginatedTable
{
    const ASC  = 'asc';
    const DESC = 'desc';

    protected $db;

    protected $admin;
    protected $data;
    protected $tableHeaders = [];
    protected $tableName;
    protected $quantity;
    protected $limit;
    protected $numberOfPages;

    public function __construct($table_headers, $table_name, $quantity, $limit, bool $admin = false)
    {
        $this->db = new Db();
        $this->admin = $admin;
        $this->tableName = $table_name;
        $this->quantity = $quantity;
        $this->limit = $limit;
        $this->urlToSession();
        $this->getPartOfData();
        $this->mapHeaders($table_headers);
    }

    /**
     * Методы таблицы *
     */

    public function getTable()
    {
        $this->startTable();
        $this->getTableHeaders();
        $this->getTableBody();
        $this->endTable();
    }

    private function getNumberOfRows()
    {
        $this->numberOfPages = (int)ceil($this->db->query("SELECT COUNT(*) FROM $this->tableName")->fetchColumn() / $this->quantity);
        $this->numberOfPages++;
    }

    private function getPartOfData()
    {
        $this->getNumberOfRows();
        if ($_SESSION['table']['page'] > $this->numberOfPages) $_SESSION['table']['page'] = $this->numberOfPages;

        if (!isset($list)) $list = 0;

        $page = $_SESSION['table']['page'];
        $list =-- $page * $this->quantity;

        $sql = "SELECT * FROM $this->tableName ";
        if ($_SESSION['table']['order_by'] != null) {
            $order_by   = $_SESSION['table']['order_by'];
            $sort       = $_SESSION['table']['sort'];
            $sql .= " ORDER BY $order_by $sort ";
        }
        $sql .= "LIMIT $this->quantity OFFSET $list;";

        $this->data = $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function getTableHeaders()
    {
        echo '<thead>';
        echo '<tr>';

        foreach ($this->tableHeaders as $key => $value) {
            echo '<th scope="col" class="align-middle"><a href="' . $this->getUrlFromSession($key) . '">';
            if ($_SESSION['table']['order_by'] == $key && $_SESSION['table']['sort'] == self::DESC) {
                echo '<img src="../../public/icons/desc.svg" alt="По убыванию">';
            } elseif ($_SESSION['table']['order_by'] == $key && $_SESSION['table']['sort'] == self::ASC) {
                echo '<img src="../../public/icons/asc.svg" alt="По возрастанию">';
            }
            echo $value . '</a></th>';
        }

        if ($this->admin == true) echo '<th scope="col">Управление</th>';

        echo '</tr>';
        echo '</thead>';
    }

    private function getTableBody()
    {
        echo '<tbody>';

        foreach ($this->data as $row) {
            echo '<tr>';

            foreach ($row as $key => $value) {
                if ($key != 'id') {
                    if (($key == 'done' || $key == 'edited') && $value == 0) {
                        echo '<td class="align-middle"></td>';
                    } elseif ($key == 'edited' && $value == 1) {
                        echo '<td class="align-middle text-center"><img src="../../public/icons/check_warning.svg" alt="Отредактировано администратором"></td>';
                    } elseif ($key == 'done' && $value == 1) {
                        echo '<td class="align-middle text-center"><img src="../../public/icons/check_primary.svg" alt="Задача выполнена"></td>';
                    } else {
                        echo '<td class="align-middle">' . $value . '</td>';
                    }
                }
            }

            if ($this->admin == true) echo '<td><a href="/task/' . $row['id'] . '" class="btn btn-primary">Редактировать</a></td>';

            echo '</tr>';
        }

        echo '</tbody>';
    }

    protected function startTable()
    {
        echo '<table class="table">';
    }

    protected function endTable()
    {
        echo '</table>';
    }

    private function mapHeaders($table_headers) {
        $i = 0;
        foreach ($this->data[0] as $header => $value) {
            if ($header != 'id') {
                $this->tableHeaders[$header] = $table_headers[$i];
                $i++;
            }
        }
    }


    /**
     * Методы пагинации
     */

    public function getPagination()
    {
        $this->startPagination();
        $this->getButtons();
        $this->endPagination();
    }

    protected function startPagination()
    {
        echo '<div class="pagination justify-content-center">';
    }

    protected function getButtons()
    {
        // Ссылки "назад" и "на первую страницу"
        if ($_SESSION['table']['page'] >= 2 ) {

            // Значение page= для первой страницы всегда равно единице
            echo '<a href="' . $this->getUrl(1) . '" class="alert alert-light" role="alert"><<</a>';

            // Предыдущая страница page=-1
            echo '<a href="' . $this->getUrl($_SESSION['table']['page'] - 1) . '" class="alert alert-light" role="alert"> < </a>';
        }

        $this_page = $_SESSION['table']['page'];

        // Узнаем с какой ссылки начинать вывод
        $start = $this_page - $this->limit;

        // Узнаем номер последней ссылки для вывода
        $end = $this_page + $this->limit;

        // Выводим ссылки на все страницы
        // Начальное число $j в нашем случае должно равнятся единице, а не нулю
        for ($j = 1; $j < $this->numberOfPages; $j++) {

            // Выводим ссылки только в том случае, если их номер больше или равен
            // начальному значению, и меньше или равен конечному значению
            if ($j >= $start && $j <= $end) {

                // Выделяем ссылку на текущую страницу
                if ($j == ($_SESSION['table']['page'])) {
                    echo '<a href="' . $this->getUrl($j) . '" class="alert alert-info" role="alert">' . $j . '</a>';
                }

                // Ссылки на остальные страницы
                else {
                    echo '<a href="' . $this->getUrl($j) . '" class="alert alert-light" role="alert">' . $j . '</a>';
                }
            }
        }

        // Выводим ссылки "вперед" и "на последнюю страницу"
        if ($j > $_SESSION['table']['page'] && ($_SESSION['table']['page'] + 1) < $j) {
            $next_page = $_SESSION['table']['page'] + 1;
            $last_page = $this->numberOfPages - 1;

            // Следующая страница
            echo '<a href="' . $this->getUrl($next_page) . '" class="alert alert-light" role="alert">></a>';

            // Последняя страница
            echo '<a href="' . $this->getUrl($last_page) . '" class="alert alert-light" role="alert">>></a>';
        }
    }

    protected function endPagination()
    {
        echo '</div>';
    }

    private function getUrl($page)
    {
        if ($_SESSION['table']['order_by'] == null) {
            return '/?page=' . $page;
        } else {
            return '/?page=' . $page . '&order_by=' . $_SESSION['table']['order_by'] . '&sort=' . $_SESSION['table']['sort'];
        }
    }


    /**
     * Остальные методы
     */

    private function urlToSession()
    {
        if (empty($_GET) == true) {
            $_SESSION['table']['page'] = 1;
            $_SESSION['table']['order_by'] = null;
            $_SESSION['table']['sort'] = null;
        } elseif (isset($_GET['page']) && !isset($_GET['order_by']) && !isset($_GET['sort'])) {
            $_SESSION['table']['page'] = $_GET['page'];
            $_SESSION['table']['order_by'] = null;
            $_SESSION['table']['sort'] = null;
        } else {
            if ($_SESSION['table']['page'] != $_GET['page']) {
                $_SESSION['table']['page'] = $_GET['page'];
            }
            if ($_SESSION['table']['order_by'] != $_GET['order_by']) {
                $_SESSION['table']['order_by'] = $_GET['order_by'];
            }
            if ($_SESSION['table']['sort'] != $_GET['sort']) {
                $_SESSION['table']['sort'] = $_GET['sort'];
            }
        }
    }

    private function getUrlFromSession($order_by)
    {
        $url = '/?page=' . $_SESSION['table']['page'] . '&order_by=' . $order_by;
        if ($_SESSION['table']['sort'] == null) {
            $url .= '&sort=' . self::ASC;
        } elseif ($_SESSION['table']['sort'] != self::DESC && $_SESSION['table']['order_by'] == $order_by) {
            $url .= '&sort=' . self::DESC;
        } elseif ($_SESSION['table']['sort'] != self::ASC  && $_SESSION['table']['order_by'] == $order_by) {
            $url .= '&sort=' . self::ASC;
        } else {
            $url .= '&sort=' . self::ASC;
        }
        return $url;
    }
}