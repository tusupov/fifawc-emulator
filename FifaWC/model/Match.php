<?php

namespace FifaWC\model;

/**
 * Матчи
 * @package FifaWC\model
 */
class Match extends Record {

    /**
     * Название таблицы
     * @return string
     */
    static function tableName() {

        return "matches";

    }

    /**
     * Параметры таблицы
     * @return array
     */
    static function tableColumns() {

        return array(
            "id",
            "type",
            "team_1",
            "team_2",
            "score_1",
            "score_2"
        );

    }

    /**
     * Привязанные параметры таблицы
     * @return array
     */
    static function joinTable() {

        return array(
            array(
                "name"  => "team_1",
                "class" => Team::class,
                "on" => array(
                    "team_1" => "id"
                ),
                "type" => "inner"
            ),
            array(
                "name"  => "team_2",
                "class" => Team::class,
                "on" => array(
                    "team_2" => "id"
                ),
                "type" => "inner"
            )
        );

    }

}
