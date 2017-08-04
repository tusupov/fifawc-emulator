<?php

include_once "config.php";

if ($_GET["group"] == "Y")
    \FifaWC\Generator::generateGroups();

if ($_GET["group_match"] == "Y")
    \FifaWC\Generator::generateGroupMatches();

if ($_GET["playoff"] == "Y")
    \FifaWC\Generator::generatePlayoffMatches((int) $_GET["step"]);

$emulator = new \FifaWC\Emulator();

$teamList       = $emulator->getTeamList();
$groupList      = $emulator->getGroupList();
$groupMatches   = $emulator->getGroupMatchesList();
$playoffMatches = $emulator->getPlayoffMatchesList();

?>
<html>
<head>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
</head>
<body>

    <nav class="navbar navbar-inverse">

        <div class="container">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">Эмулятор</a>
            </div>

        </div>

    </nav>

    <div class="container">

        <h1 class="text-center">Эмулятор чемпионата мира по футболу</h1>

        <div>
            <a href="/?group=Y" class="btn btn-success">Запустить жеребьевку</a>
            <a href="/?group_match=Y" class="btn <?=$groupList ? " btn-success" : " btn-default disabled"?>">Запустить групповые матчи</a>
            <a href="/?playoff=Y&step=1" class="btn <?=$groupMatches ? " btn-success" : " btn-default disabled"?>">Запустить плей-офф</a>
        </div>

        <br />

        <?if($playoffMatches):?>
            <?include "view/playofflist.php"?>
        <?endif?>

        <?if($groupList):?>
            <?include "view/grouplist.php"?>
        <?endif?>

        <?if($teamList):?>
            <?include "view/teamlist.php"?>
        <?endif;?>

    </div>

</body>
</html>
