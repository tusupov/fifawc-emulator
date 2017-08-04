<div class="row">

    <?$groupCnt = 1?>
    <?foreach ($groupList as $key => $teams):?>

        <div class="col-md-6">

            <h2>Группа <?=$key?></h2>

            <table class="table table-striped">
                <tr>
                    <th>№</th>
                    <th>Команда</th>
                    <th>И</th>
                    <th>В</th>
                    <th>Н</th>
                    <th>П</th>
                    <th>Голы</th>
                    <th>+/-</th>
                    <th>О</th>
                </tr>
                <?$cnt = 1?>
                <?foreach ($teams as $team):?>
                    <tr>
                        <td><?=$cnt++?></td>
                        <td width="210" class="">
                            <span class="glyphicon glyphicon-flag"></span>
                            <?=$team->name?>
                        </td>
                        <td><?=$team->win + $team->draw + $team->lose?></td>
                        <td class="text-center"><?=+$team->win?></td>
                        <td class="text-center"><?=+$team->draw?></td>
                        <td class="text-center"><?=+$team->lose?></td>
                        <td class="text-center"><?=$team->match_score?> - <?=$team->match_missed?></td>
                        <td class="text-center"><?=$team->match_score - $team->match_missed?></td>
                        <td class="text-center"><?=$team->win * 3 + $team->draw?></td>
                    </tr>
                    <?$cnt++?>
                <?endforeach;?>
            </table>

        </div>

        <?if($groupCnt++ % 2 == 0):?>
            </div><div class="row">
        <?endif;?>

    <?endforeach;?>

</div>

