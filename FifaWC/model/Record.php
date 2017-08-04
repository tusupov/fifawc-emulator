<?php

namespace FifaWC\model;

abstract class Record  {

    abstract static function tableName();
    abstract static function tableColumns();

    static function joinTable() {
        return null;
    }

    static function get($filter = array()) {

        global $db;

        $arResult = [];

        $tableName = static::tableName();
        $tableColumns = static::tableColumns();

        $joinConfigs = static::joinTable();

        $select = "";
        $joinSelect = "";

        $join = "";

        foreach ($tableColumns as $column)
            $select .= "t.`{$column}` as `{$tableName}_{$column}`, ";

        if ($joinConfigs) {

            $jointCnt = 1;

            foreach ($joinConfigs as $joinConfig) {

                $joinTableName     = $joinConfig["class"]::tableName();
                $joinTableComlumns = $joinConfig["class"]::tableColumns();

                foreach ($joinTableComlumns as $column)
                    $joinSelect .= "j{$jointCnt}.`{$column}` as `{$joinConfig["name"]}_{$column}`, ";

                $join .= $joinConfig["type"] . " JOIN `{$joinTableName}` as j{$jointCnt} ";

                $joinON = "";
                foreach ($joinConfig["on"] as $key => $value)
                    $joinON .= "t.`{$key}` = j{$jointCnt}.`{$value}` AND ";

                $joinON = trim($joinON, "AND ");

                $join .= $joinON ? " ON {$joinON} " : "";

                $jointCnt++;

            }

        }

        $where = "";
        foreach ($filter as $key => $value) {

            $cmp = "=";
            if (in_array(substr($key, 0, 2), array(">=", "<="))) {
                $cmp = substr($key, 0, 2);
                $key = substr($key, 2);
            } elseif(in_array(substr($key, 0, 1), array(">", "<", "="))) {
                $cmp = substr($key, 0, 1);
                $key = substr($key, 1);
            }

            $where .= "t.`{$key}` {$cmp} '{$value}' AND ";

        }

        $where = rtrim($where, "AND ");
        if ($where) $where = "WHERE {$where}";

        $query = "
            SELECT " . trim($select.$joinSelect, ", ") . " 
            FROM `{$tableName}` as t
            {$join}
            {$where}
            ORDER BY t.`" . array_shift(array_values($tableColumns)) . "` asc
        ";

        $result = $db->query($query);

        while($row = $result->fetch()) {

            $class = static::class;
            $class = new $class();

            foreach ($tableColumns as $column)
                $class->{$column} = $row->{$tableName."_".$column};

            if ($joinConfigs) {

                foreach ($joinConfigs as $joinConfig) {

                    $joinClass = new $joinConfig["class"]();
                    foreach ($joinClass::tableColumns() as $column) {
                        $joinClass->{$column} = $row->{$joinConfig["name"]."_".$column};
                    }

                    $class->{$joinConfig["name"]} = $joinClass;

                }


            }

            $arResult[$class->id] = $class;

        }

        return $arResult;

    }

    static function getAll() {
        return static::get();
    }

    static function delete($filter = array()) {

        global $db;
        $tableName = static::tableName();

        $query = "DELETE FROM `{$tableName}` ";

        $where = "";
        foreach ($filter as $key => $value) {

            $cmp = "=";
            if (in_array(substr($key, 0, 2), array(">=", "<="))) {
                $cmp = substr($key, 0, 2);
                $key = substr($key, 2);
            } elseif(in_array(substr($key, 0, 1), array(">", "<", "="))) {
                $cmp = substr($key, 0, 1);
                $key = substr($key, 1);
            }

            $where .= "`$key` {$cmp} '$value' AND ";

        }

        $where = rtrim($where, "AND ");
        if ($where) $where = "WHERE {$where}";

        $query .= $where;

        $db->query($query);

    }

    static function deleteAll() {
        static::delete();
    }

    function save() {

        global $db;

        $tableName = $this::tableName();
        $tableColumns = $this::tableColumns();

        if ($this->id) {

            $query = "UPDATE `{$tableName}` SET ";

            foreach ($tableColumns as $column)
                if (isset($this->{$column}))
                    $query .= "`{$column}` = '{$this->{$column}}', ";

            $query = rtrim($query, ", ");
            $query .= "WHERE `id` = '{$this->id}'";

            $db->query($query);
            return true;

        } else {

            $query = "INSERT INTO `{$tableName}` (";

            foreach ($tableColumns as $column)
                if (isset($this->{$column}))
                    $query .= "`{$column}`, ";

            $query = rtrim($query, ", ");
            $query .= ") VALUES (";

            foreach ($tableColumns as $column)
                if (isset($this->{$column}))
                    $query .= "'{$this->{$column}}', ";

            $query = rtrim($query, ", ");
            $query .= ");";

            $result = $db->insert($query);
            $this->id = $result->lastInsertId();
            return true;

        }

    }

}
