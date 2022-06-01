<?php
require_once('_init.php');
function getIsValid($get)
{
    $isValid = false;
    $selected_series = getIsAlive("id") ? $get["id"] : '';

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

function validateEpisodeWatch($get)
{
    $errors = 0;

    if (!isset($get['action'])) $errors++;
    else if ($get['action'] !== "set") $errors++;

    if (!isset($get['for'])) $errors++;
    else if ($get['for'] === "") $errors++;

    if (!isset($get['series'])) $errors++;
    else if (strlen(trim($get['series'])) === 0) $errors++;

    if (!isset($get['episode'])) $errors++;
    else if (strlen(trim($get['episode'])) === 0) $errors++;

    if ($errors === 0) {
        return true;
    } else {
        return false;
    }
}

$user = $auth->authenticated_user();
$next_episode = null;

if ($auth->is_authenticated()) {
    // next episode
}

if (isset($_GET['id'])) {
    $found_serie = $series->getSerieById($_GET['id']);
} else if (validateEpisodeWatch($_GET)) {
    if ($auth->is_authenticated()) {
        $history->setUserSawThisEpisode($user, $_GET['series'], $_GET['episode']);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die;
    }
} else {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    die;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sorozatnézegető</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <?php include("partials/header.php") ?>

    <section class="vh-100" style="background-color: #eee;">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-75">
                <div class="col-lg-12 col-xl-12">
                    <div class="card text-black" style="border-radius: 25px;">
                        <div class="card-body p-md-5">
                            <div class="row justify-content-center">
                                <div class="col-xl-6 order-2 order-lg-1">
                                    <img class="rounded mx-auto d-block" src="<?= $found_serie['cover'] ?? '' ?>" alt="kép" style="max-width:300px">
                                </div>
                                <div class="col-xl-6 order-2 order-lg-1">
                                    <h1><?= $found_serie['title'] ?? '' ?></h1>
                                    <p><?= $found_serie['plot'] ?? '' ?></p>
                                    <p> <strong>Sugárzás kezdete: </strong> <?= $found_serie['year'] ?? '' ?></p>
                                    <p> <strong>Epizódok száma: </strong> <?= count($found_serie['episodes']) ?? '' ?></p>
                                    <h3><a href="#episodes" class="badge badge-info">Epizódok</a></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-xl-12" id="episodes">
                    <div class="card text-black" style="border-radius: 25px;">
                        <div class="card-body p-md-5">
                            <div class="row">
                                <? if (!is_null($next_episode)) : ?>
                                    <div class="col">
                                        <!-- TODO: a soron következő epizód -->
                                        <!--
                                    <? $seen_episode = $history->getUserSawThisEpisode($user, $found_serie['id'], $episode['id']); ?>
                                    <div class="row d-flex align-items-center justify-content-between list-group-item <?= $seen_episode ? "disabled" : "" ?>" style="border:0;">
                                        <h5><?= $i++ ?>. rész</h5>
                                        <h6><?= $episode['title'] ?></h6>
                                        <div>
                                            <li style="list-style-type: none;">
                                                <p><img src="assets/star.png" alt="star image" style="max-width: 30px;"><?= $episode['rating'] ?>/10 értékelés</p>
                                                <p><?= $episode['plot'] ?></p>
                                            </li>
                                        </div>
                                        <? if ($auth->is_authenticated()) : ?>
                                            <a href="details.php?action=set&for=episode&series=<?= $found_serie['id'] ?>&episode=<?= $episode['id'] ?>"><?= $seen_episode ? "Megtekintve" : "Megtekint" ?></a>
                                        <? else : ?>
                                            <a href="login.php">Megtekint</a>
                                        <? endif ?>

                                    </div>-->
                                    <? endif ?>
                                    </div>
                                    <div class="col-xl-12 order-1 order-lg-1">
                                        <h3>Epizódok</h3>
                                        <ul class="list-group">
                                            <? $i = 1; ?>
                                            <? usort($found_serie['episodes'], 'ascSort'); ?>
                                            <?php foreach ($found_serie['episodes'] as $episode) : ?>
                                                <? $seen_episode = $history->getUserSawThisEpisode($user, $found_serie['id'], $episode['id']); ?>
                                                <div class="row d-flex align-items-center justify-content-between list-group-item <?= $seen_episode ? "disabled" : "" ?>" style="border:0;">
                                                    <h5><?= $i++ ?>. rész</h5>
                                                    <h6><?= $episode['title'] ?></h6>
                                                    <div>
                                                        <li style="list-style-type: none;">
                                                            <p><img src="assets/star.png" alt="star image" style="max-width: 30px;"><?= $episode['rating'] ?>/10 értékelés</p>
                                                            <p><?= $episode['plot'] ?></p>
                                                        </li>
                                                    </div>
                                                    <? if ($auth->is_authenticated()) : ?>
                                                        <a href="details.php?action=set&for=episode&series=<?= $found_serie['id'] ?>&episode=<?= $episode['id'] ?>"><?= $seen_episode ? "Megtekintve" : "Megtekint" ?></a>
                                                    <? else : ?>
                                                        <a href="login.php">Megtekint</a>
                                                    <? endif ?>

                                                </div>
                                            <?php endforeach ?>

                                        </ul>

                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
    </section>

    <?php include("partials/footer.php") ?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>