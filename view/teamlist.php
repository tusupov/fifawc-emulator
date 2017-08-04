<h2>Список команд</h2>

<table class="table table-striped">

    <tr>
        <th>№</th>
        <th>Команда</th>
        <th>Сыграно</th>
        <th>Мячи</th>
    </tr>

    <?$cnt = 1?>
    <?foreach ($teamList as $team):?>
        <tr>
            <td><?=$cnt++?></td>
            <td><?=$team->name?></td>
            <td><?=$team->games?></td>
            <td><?=$team->score?> - <?=$team->missed?></td>
        </tr>
    <?endforeach;?>

</table>