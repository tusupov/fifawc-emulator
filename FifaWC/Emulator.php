<?php

namespace FifaWC;

use FifaWC\model\Group;
use FifaWC\model\Match;
use FifaWC\model\Team;

/**
 * Работа с результатами
 * чемпионата
 * @package FifaWC
 */
class Emulator {

    const TEAM_CNT = 32;

    private $teams = [];
    private $groups = [];
    private $groupMatches = [];
    private $playoffMatches = [];

    public function __construct() {

        $bitCount = substr_count(decbin(self::TEAM_CNT), "1");
        if ($bitCount != 1)
            throw new \Exception("Количество команд должно быт степень 2. Сейчас " . self::TEAM_CNT);

        $this->initTeamList();

        $this->initPlayoffMatches();
        $this->initGroupMatches();
        $this->initGroupList();

    }

    /**
     * Список команд
     * @throws \Exception
     */
    private function initTeamList() {

        $this->teams = [];

        $dbResult = Team::get([
            "<=id" => self::TEAM_CNT
        ]);

        //Проверка количество команд
        if (count($dbResult) != self::TEAM_CNT)
            throw new \Exception(
                "Количество команд не ".self::TEAM_CNT.". Не хватает ".(self::TEAM_CNT - count($dbResult)).".",
                400
            );

        $this->teams = $dbResult;

    }

    /**
     * Список групп
     */
    private function initGroupList() {

        $this->groups = [];
        $dbResult = Group::getAll();

        /******************** Проверка количество групп и команд в группах ********************/
        if (count($dbResult) != self::TEAM_CNT)
            return;

        $arResult = [];
        foreach ($dbResult as $group)
            $arResult[$group->name][] = $group->team;

        if (count($arResult) != self::TEAM_CNT / 4)
            return;

        foreach ($arResult as $teams)
            if (count($teams) != 4)
                return;

        /******************** /Проверка количество групп и команд в группах ********************/

        $this->groups = $arResult;

        if ($this->groupMatches) {
            //Есть сыгранные матчи в групповом этапе

            foreach ($this->groups as &$group) {

                foreach ($group as &$team) {

                    $team->win  = 0;
                    $team->draw = 0;
                    $team->lose = 0;

                    $team->match_score  = 0;
                    $team->match_missed = 0;

                    foreach ($this->groupMatches[$team->id] as $match) {

                        if ($match->team_2->id == $team->id) {

                            $tmp = $match->score_1;
                            $match->score_1 = $match->score_2;
                            $match->score_2 = $tmp;

                            $tmp = $match->team_1;
                            $match->team_1 = $match->team_2;
                            $match->team_2 = $tmp;

                        }

                        if ($match->score_1 > $match->score_2) $team->win++;
                        elseif ($match->score_1 < $match->score_2) $team->lose++;
                        else $team->draw++;

                        $team->match_score  += $match->score_1;
                        $team->match_missed += $match->score_2;

                    }

                }
                unset($team);

                //Сортировка по очкам, если очков одинаково,
                //то сортировка идет по разнице мечей
                usort($group, function($a, $b) {

                    $point1 = $a->win * 3 + $a->draw;
                    $point2 = $b->win * 3 + $b->draw;

                    if ($point1 == $point2) {

                        $point1 = $a->match_score - $a->match_missed;
                        $point2 = $b->match_score - $b->match_missed;

                    }

                    return $point1 > $point2 ? -1 : 1;

                });

            }
            unset($group);

        }

    }

    /**
     * Список групповых матчей
     */
    private function initGroupMatches() {

        $this->groupMatches = [];

        $dbResult = Match::get(array(
            "type" => self::TEAM_CNT
        ));

        if (count($dbResult) != self::TEAM_CNT / 4 * 6)
            return;

        $arResult = [];

        foreach ($dbResult as $match) {
            $arResult[$match->team_1->id][] = $match;
            $arResult[$match->team_2->id][] = $match;
        }

        if (count($arResult) != self::TEAM_CNT)
            return;

        foreach ($arResult as $matches)
            if (count($matches) != 3) break;

        $this->groupMatches = $arResult;

    }

    /**
     * Список плей-офф матчей
     */
    private function initPlayoffMatches() {

        $this->playoffMatches = [];

        //Все сыгранные игры кроме групповых
        $dbResult = Match::get(array(
            "<type" => self::TEAM_CNT
        ));

        $dbResult = array_reverse($dbResult);

        $arResult = [];
        foreach ($dbResult as $match)
            $arResult[$match->type][] = $match;

        $this->playoffMatches = $arResult;

    }

    public function getTeamList() {
        $this->initTeamList();
        return $this->teams;
    }

    public function getGroupList() {
        return $this->groups;
    }

    public function getGroupMatchesList() {
        $this->initGroupMatches();
        return $this->groupMatches;
    }

    public function getPlayoffMatchesList() {
        return $this->playoffMatches;
    }

}
