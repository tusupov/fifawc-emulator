<?php

namespace FifaWC;

use FifaWC\model\Group;
use FifaWC\model\Match;
use FifaWC\model\Team;

/**
 * Генератор жеребьевки
 * и матчей
 * @package FifaWC
 */
class Generator {

    private static $emulator = null;

    /**
     * Жеребьевка команд по группам
     */
    static function generateGroups() {

        if (!self::$emulator)
            self::$emulator = new Emulator();

        Match::deleteAll();
        Group::deleteAll();

        $teams = self::$emulator->getTeamList();

        //Смешать список команд
        shuffle($teams);

        //Добавляем в БД
        $cnt = 0;
        foreach ($teams as $team) {
            $group = new Group();
            $group->name = chr((int)(($cnt++) / 4) + 65);
            $group->team_id = $team->id;
            $group->save();
        }

    }

    /**
     * Генератор групповых матчей
     */
    static function generateGroupMatches() {

        if (!self::$emulator)
            self::$emulator = new Emulator();

        //Удаляем их БД все сыгранные матчи
        Match::deleteAll();

        $groups = self::$emulator->getGroupList();

        foreach ($groups as $teams) {

            $teams = array_values($teams);

            for ($i = 0; $i < count($teams) - 1; $i++) {

                for ($j = $i + 1; $j < count($teams); $j++) {

                    //Эмуляция матча между 2 команд и сохранение в БД
                    $match = Team::calculateMath($teams[$i], $teams[$j]);
                    $match->type = Emulator::TEAM_CNT;
                    $match->save();

                }

            }

        }

    }

    /**
     * Генератор плей-офф матчей
     * @param $step - этап финала
     */
    static function generatePlayoffMatches($step) {

        if (!self::$emulator)
            self::$emulator = new Emulator();

        $step = (int) $step;
        if ($step < 1) $step = 1;

        //Очищаем БД матчей после текущего матча
        Match::delete([
            "<=type" => Emulator::TEAM_CNT / pow(2, $step)
        ]);

        if ($step == 1) {
            //Если первая игра после групповых игр

            $groups = self::$emulator->getGroupList();
            $groups = array_values($groups);

            for ($i = 0; $i < count($groups); $i += 2) {

                //Первой место в первом группе и второе место во втором группе
                $match = Team::calculateMath($groups[$i][0], $groups[$i + 1][1], true);
                $match->type   = Emulator::TEAM_CNT / 2;
                $match->save();

                //Второе место в первом группе и первое место во втором группе
                $match = Team::calculateMath($groups[$i + 1][0], $groups[$i][1], true);
                $match->type   = Emulator::TEAM_CNT / 2;
                $match->save();

            }

        } else {

            $prevType = Emulator::TEAM_CNT / pow(2, $step - 1);
            $curType  = $prevType / 2;

            Match::delete(array(
                "<type" => $prevType
            ));

            //Получаем матчи перед текущем этапом
            $matches = Match::get(array(
                "type" => $prevType
            ));

            if (count($matches) != $curType || $curType < 2)
                return;

            $teams = [];

            foreach ($matches as $match) {

                //Берем только выигранные команды
                if ($match->score_1 > $match->score_2) $teams[] = $match->team_1;
                else $teams[] = $match->team_2;

            }


            for ($i = 0; $i < count($teams); $i+=2) {

                //Эмуляция матча между двумя командами
                $match = Team::calculateMath($teams[$i], $teams[$i + 1], true);
                $match->type = $curType;
                $match->save();

            }

        }

    }

}