<?php
require_once('_init.php');

$started_series_ids = $history->getUserStartedSeries($auth->authenticated_user());
$started_series = $series->getSeriesById($started_series_ids);

$not_started_series = $series->getNotStartedSeries($started_series);
?>

<!DOCTYPE html>
<html lang="hu">

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

    <main role="main">
        <!-- Main jumbotron for a primary marketing message or call to action -->
        <div class="jumbotron">
            <div class="container" id="bootstrap-overrides">
                <h1 class="display-3">IMovie</h1>
                <p>Ki ne szeretne sorozatokat nézni? Egyetemistaként azonban talán erre kevesebb idő jut, mint amennyit szeretnénk, így mire legközelebb a képernyő elé jutunk, már el is felejtettük, hogy hol tartottunk... kivéve, ha van egy olyan webes alkalmazásunk, ahol követni tudjuk a sorozatnézési folyamatot.</p>
                <p><a class="btn btn-primary btn-lg" href="#" role="button">Legutóbb nézett sorozat</a></p>
            </div>
        </div>
        <div class="container">
            <? if ($auth->is_authenticated() && count($started_series) > 0) : ?>
                <h3>Már elkezdett sorozatok</h3>
                <div class="row">
                    <?php foreach ($started_series as $serie) : ?>
                        <div class="card col-sm text-center">
                            <h5 class="card-title"><?= $serie['title'] ?></h5>
                            <img class="card-img-top" src="<?= $serie['cover'] ?>" alt="Card image cap" style="width:80%;margin: 0 auto">
                            <div class="card-body">
                                <p><img src="assets/star.png" alt="star image" style="max-width: 30px;"> <?= $series->getTheMostPopularRating($serie) ?>/10 értékelés</p>
                                <details>
                                    <summary>Részletek</summary>
                                    <p>Epizódok száma: <?= count($serie['episodes']) ?></p>
                                    <p>Korábbi sugárzás dátuma: <?= $series->getTheRecentEpisodeDate($serie) ?></p>
                                    <a href="details.php?id=<?= $serie['id'] ?>" class="btn btn-secondary">További részletek</a>
                                </details>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            <? endif ?>
            <hr>
            <h3>Még el nem kezdett sorozatok</h3>
            <div class="row">
                <?php foreach ($not_started_series as $serie) : ?>
                    <div class="card col-xl-4 col-md-6 col-sm-12 col-sm text-center">
                        <h5 class="card-title"><?= $serie['title'] ?></h5>
                        <img class="card-img-top" src="<?= $serie['cover'] ?>" alt="Card image cap" style="width:80%;margin: 0 auto">
                        <div class="card-body">
                            <p><img src="assets/star.png" alt="star image" style="max-width: 30px;"> <?= $series->getTheMostPopularRating($serie) ?>/10 értékelés</p>
                            <details>
                                <summary>Részletek</summary>
                                <p>Epizódok száma: <?= count($serie['episodes']) ?></p>
                                <p>Korábbi sugárzás dátuma: <?= $series->getTheRecentEpisodeDate($serie) ?></p>
                                <a href="details.php?id=<?= $serie['id'] ?>" class="btn btn-secondary">További részletek</a>
                            </details>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </main>

    <?php include("partials/footer.php") ?>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>