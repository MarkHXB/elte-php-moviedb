<?php
require_once('_init.php');

function getIsValid($get)
{
    $isValid = false;
    $action = getIsAlive("action") ? $get["action"] : '';
    $selected_series = getIsAlive("series") ? $get["series"] : '';
    $selected_episode = getIsAlive("episode") ? $get["episode"] : '';
    if ($action !== '' && $action === 'add' || $action === 'modify' || $action === 'delete') {
        $isValid = true;
    } else {
        $isValid = false;
    }

    if ($selected_series !== '' && filter_var($selected_series, FILTER_DEFAULT)) {
        $isValid = true;
    } else {
        $isValid = false;
    }

    return $isValid;
}

function ascSort($a, $b)
{
    if ($a['year'] == $b['year']) {
        return 0;
    }
    return ($a['year'] < $b['year']) ? -1 : 1;
}

$found_series = $series->getAll();

if (!$auth->authorize(['admin'])) {
    header("Location: home.php");
    die;
} else if (getIsValid($_GET)) {
    $selected_series = getIsAlive("series") ? $_GET["series"] : '';
    $selected_episode = getIsAlive("episode") ? $_GET["episode"] : '';

    //ON SERIES ACTIONS
    if ($_GET['for'] === 'series') {
        switch ($_GET['action']) {
            case 'add':
                header("Location: admin/add-series.php?series={$selected_series}");
                die;
                break;
            case 'modify':
                header("Location: admin/modify-series.php?series={$selected_series}");
                die;
                break;
            case 'delete':
                $series->deleteSeries($selected_series);
                header("Location: admin.php", true, 301);
                die;
                break;
        }
    }
    //ON EPISODE ACTIONS
    else {
        switch ($_GET['action']) {
            case 'add':
                header("Location: admin/add-episode.php?series={$selected_series}");
                die;
                break;
            case 'modify':
                header("Location: admin/modify-episode.php?series={$selected_series}&episode={$selected_episode}");
                die;
                break;
            case 'delete':
                $series->deleteEpisode($selected_series, $selected_episode);
                header("Location: admin.php", true, 301);
                die;
                break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sorozatnézegető</title>
    <script src="https://kit.fontawesome.com/7050e70923.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script data-require="jquery@*" data-semver="2.0.3" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
    <style>
        .error {
            color: red;
        }

        .function-icon {
            max-width: 30px;
        }
    </style>
</head>

<body>
    <?php include("partials/header.php") ?>
    <main class="container p-0">
        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Törlés elfogadása</h4>
                    </div>

                    <div class="modal-body">
                        <p>Biztos vagy benne, hogy törölni szeretnéd a kiválasztott sorozatot?</p>
                        <p class="debug-url"></p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Mégse</button>
                        <a class="btn btn-danger btn-ok">Töröl</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <?php foreach ($found_series as $movie) : ?>
                <div class="card col-xl-4 col-l-10 col-md-8 text-center">
                    <img class="card-img-top" src="<?= $movie['cover'] ?>" alt="Card image cap" style="width:100%">
                    <div class="card-body">
                        <h5 class="card-title"><?= $movie['title'] ?></h5>
                        <div class="d-flex flex-column m-2">
                            <a class="btn btn-warning m-1" href="admin.php?action=modify&for=series&series=<?= $movie['id'] ?>">Sorozat szerkesztése</a>
                            <button class="btn btn-danger m-1" data-href="admin.php?action=delete&for=series&series=<?= $movie['id'] ?>" data-toggle="modal" data-target="#confirm-delete">Sorozat törlése</button>
                        </div>
                        <?php if (count($movie["episodes"]) > 0) : ?>
                            <ol>
                                <? usort($movie['episodes'], 'ascSort'); ?>
                                <?php foreach ($movie["episodes"] as $episode) : ?>
                                    <li>
                                        <div class="row m-1 align-items-center" style="border-bottom:1px rgba(0,0,0,0.3) solid">
                                            <div class="col-6">
                                                <p class="text-left m-0"><?= $episode["title"] ?? '' ?></p>
                                            </div>
                                            <div class="col-6">
                                                <a href="admin.php?action=modify&for=episode&series=<?= $movie['id'] ?>&episode=<?= $episode['id'] ?>"><img class="function-icon" src="./assets/edit.png"></a>
                                                <button class="btn btn-default" data-href="/admin.php?action=delete&for=episode&series=<?= $movie['id'] ?>&episode=<?= $episode['id'] ?>" data-toggle="modal" data-target="#confirm-delete"><img class="function-icon" src="./assets/delete.png"></button>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach ?>
                            </ol>
                        <?php endif ?>
                        <a href="admin.php?action=add&for=episode&series=<?= $movie['id'] ?>" class="btn btn-primary m-1">Új epizód hozzáadása</a>
                        <a href="details.php?id=<?= $movie['id'] ?>" class="btn btn-secondary m-1">Sorozat részletei</a>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
        <div class="row">
            <div class="card col-md-4 m-1 text-center">
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    <a href="admin/add-serie.php" class="btn btn-primary">Új sorozat hozzáadása</a>
                </div>
            </div>
        </div>
    </main>
    <?php include("partials/footer.php") ?>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script>
        $('#confirm-delete').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            $('.debug-url').html('Delete URL: <strong>' + $(this).find('.btn-ok').attr('href') + '</strong>');
        });
    </script>
</body>

</html>