<?php

namespace FifaWC\model;

/**
 * Группы
 * @package FifaWC\model
 */
class Group extends Record {

    /**
     * Название таблицы
     * @return string
     */
    static function tableName() {

        return "group";

    }

    /**
     * Параметры таблицы
     * @return array
     */
    static function tableColumns() {

        return array(
            "id",
            "name",
            "team_id"
        );

    }

    /**
     * Привязанные параметры таблицы
     * @return array
     */
    static function joinTable() {

        return array(
            array(
                "name"  => "team",
                "class" => Team::class,
                "on" => array(
                    "team_id" => "id"
                ),
                "type" => "inner"
            )
        );

    }

}
