<?$type = 0?>

<?if($_GET["playoff"] == "Y" && (int)$_GET["step"] > 0 && \FifaWC\Emulator::TEAM_CNT / pow(2, (int)$_GET["step"]) > 2):?>

    <div class="text-center">
        <a href="/?playoff=Y&step=<?=(int)$_GET["step"] + 1?>" class="btn btn-success">Далее</a>
    </div>

<?endif?>

<?foreach ($playoffMatches as $type => $matches):?>

    <h2><?=$type > 2 ? "1/" . ($type / 2) :""?> Финал</h2>

    <div class="row">

        <?$matchesCnt = 1?>
        <?foreach ($matches as $match):?>

            <div class="col-md-6">

                <table class="table table-striped">
                    <tr>
                        <th width="70">№</th>
                        <th width="410">Команда</th>
                        <th>Счет</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td><?=$match->team_1->name?></td>
                        <td><?=$match->score_1?></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><?=$match->team_2->name?></td>
                        <td><?=$match->score_2?></td>
                    </tr>
                </table>

            </div>

            <?if($matchesCnt++ % 2 == 0):?>
                </div><div class="row">
            <?endif;?>

        <?endforeach;?>

    </div>

<?endforeach;?>

