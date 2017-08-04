<?php

namespace FifaWC\model;

/**
 * Команды
 * @package FifaWC\model
 */
class Team extends Record {

    /**
     * Название таблицы
     * @return string
     */
    static function tableName() {
        return "team";
    }

    /**
     * Параметры таблицы
     * @return array
     */
    static function tableColumns() {
        return array(
            "id",
            "name",
            "games",
            "score",
            "missed"
        );
    }

    /**
     * Сила атаки команды
     * @return float|int
     */
    function getPowerAttack() {

        if ($this->games > 0 && $this->score > 0)
            $power = ($this->score + $this->match_score) / ($this->games + $this->win + $this->draw + $this->lose);
        else $power = 1;

        return $power;

    }

    /**
     * Пропуская силы защиты
     * @return float|int
     */
    function getPowerDefense() {

        if ($this->games > 0 && $power = $this->missed)
            $power = ($this->missed + $this->match_missed) / ($this->games + $this->win + $this->draw + $this->lose);
        else $power = 1;

        return $power;

    }

    /**
     * Эмуляция матча между 2 команд
     * @param $team1 - Первая команда
     * @param $team2 - Вторая команда
     * @param bool $notDraw - Учитывать ничью (по умолчанию - нет)
     * @return Match
     */
    static function calculateMath($team1, $team2, $notDraw = false) {

        $match = new Match();

        $match->team_1 = $team1->id;
        $match->team_2 = $team2->id;

        do {

            $match->score_1  = rand(
                0,
                ceil (
                    4 * ( $team1->getPowerAttack() * $team2->getPowerDefense() ) / ( $team2->getPowerAttack() * $team1->getPowerDefense() )
                ) ?: 1
            );

            $match->score_2  = rand(
                0,
                ceil (
                    4 * ( $team2->getPowerAttack() * $team1->getPowerDefense() ) / ( $team1->getPowerAttack() * $team2->getPowerDefense() )
                ) ?: 1
            );

            //Если учесть ничью, то заново генерировать счет матча
        } while ($notDraw && $match->score_1 == $match->score_2);

        return $match;

    }

}
