<?php
require_once('_init.php');

$found_episode = null;
$errors = [];
$back_link = "";

function getIsValid($get)
{
    $isValid = false;
    $selected_series = getIsAlive("series") ? $get["series"] : '';

    if ($selected_series !== '' && filter_var($selected_series, FILTER_DEFAULT)) {
        $isValid = true;
    } else {
        $isValid = false;
    }

    return $isValid;
}

if (!$auth->authorize(['admin'])) {
    header("Location: home.php");
    die;
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (getIsValid($_GET)) {
            $found_episode = $series->getEpisodeById($_GET['series'], $_GET['episode']);
        } else {
            header("Location: admin.php");
            die;
        }
    } else {
        $found_episode = $series->getEpisodeById($_GET['series'], $_GET['episode']);
        if (count($_POST) !== 0) {
            //year
            if (!postKeyIsValid("year")) {
                $errors[] = "Nem adtad meg, hogy mikori a sorozat!";
            } else if (strlen(trim($_POST['year'])) === 0) {
                $errors[] = "Nem lehet üres a sorozat éve!";
            } else if (!filter_var($_POST['year'], FILTER_VALIDATE_INT)) {
                $errors[] = "A sorozat évének szám formátumúnak kell lennie!";
            } else if ($_POST['year'] < 1900 || $_POST['year'] > date('Y')) {
                $errors[] = "Az epizód éve minimum 1900 és maximum " . date('Y') . " között kell lennie!";
            } else {
                $found_episode['year'] = $_POST['year'];
            }

            //title
            if (!postKeyIsValid("title")) {
                $errors[] = "Nem adtad meg a sorozat címét!";
            } else if (strlen(trim($_POST['title'])) === 0) {
                $errors[] = "Nem lehet üres a sorozat címe!";
            } else {
                $found_episode['title'] = $_POST['title'];
            }

            //plot
            if (!postKeyIsValid("plot")) {
                $errors[] = "Nem adtad meg a sorozat egy rövid ismertető szövegét!";
            } else if (strlen(trim($_POST['plot'])) === 0) {
                $errors[] = "Nem lehet üres a sorozat imsertetője!";
            } else if (strlen($_POST['plot']) > 1000) {
                $errors[] = "Az ismertető szöveg maximum 1000 karakter hosszú lehet!";
            } else {
                $found_episode['plot'] = $_POST['plot'];
            }

            //rating
            if (!postKeyIsValid("rating")) {
                $errors[] = "Nem adtál meg értékelést!";
            } else if (strlen(trim($_POST['rating'])) === 0) {
                $errors[] = "Nem lehet üres az értékelés!";
            } else if (!filter_var($_POST['rating'], FILTER_VALIDATE_FLOAT)) {
                $errors[] = "Az értékelésnek szám formátumúnak kell lennie!";
            } else {
                $found_episode['rating'] = $_POST['rating'];
            }

            if (count($errors) === 0) {
                //update db
                $series->updateEpisode($_GET['series'], $found_episode);
                header("Location: ../admin.php", true, 301);
                die;
            }
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
        <section class="vh-100" style="background-color: #eee;">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-lg-12 col-xl-11">
                        <div class="card text-black" style="border-radius: 25px;">
                            <div class="card-body p-md-5">
                                <div class="row justify-content-center">
                                    <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                                        <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Epizód adatainak módosítása</p>
                                        <div class="">
                                            <?php foreach ($errors as $error) : ?>
                                                <p class="error"><?= $error ?? '' ?></p>
                                            <?php endforeach ?>
                                        </div>

                                        <form method="post" class="mx-1 mx-md-4" novalidate>
                                            <div class="d-flex flex-row align-items-center mb-4">
                                                <div class="form-outline flex-fill mb-0">
                                                    <input type="text" name="year" id="form-year" class="form-control" value="<?= $_POST['year'] ?? $found_episode['year'] ?? '' ?>" />
                                                    <label class="form-label" for="form-year">Mikor készült</label>
                                                </div>
                                            </div>


                                            <div class="d-flex flex-row align-items-center mb-4">
                                                <div class="form-outline flex-fill mb-0">
                                                    <input type="text" name="title" id="form-title" class="form-control" value="<?= $_POST['title'] ?? $found_episode['title'] ?? '' ?>" />
                                                    <label class="form-label" for="form-title">Címe</label>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-row align-items-center mb-4">
                                                <div class="form-outline flex-fill mb-0">
                                                    <textarea class="form-control" name="plot" id="form-plot" cols="30" rows="10"><?= $_POST['plot'] ??  $found_episode['plot'] ?? '' ?></textarea>
                                                    <label class="form-label" for="form-plot">Ismertető</label>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-row align-items-center mb-4">
                                                <div class="form-outline flex-fill mb-0">
                                                    <input type="range" name="rating" id="form-rating" class="form-control" min="0" max="10" step="0.1" onchange="updateRatingValue(this.value);" value="<?= $_POST['rating'] ?? $found_episode['rating'] ??  '5' ?>" />
                                                    <div class="d-flex flex-row align-items-center">
                                                        <p id="ratingValue" class="m-0 mr-2 ml-2"><?= $_POST['rating'] ?? $found_episode['rating'] ?? '5' ?> / 10</p>
                                                        <img src="../assets/star.png" alt="" style="max-width:25px">
                                                    </div>
                                                    <label class="form-label" for="form-rating">Értékelés</label>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                                <a href="register.php">Ha a sorozat epizódjait szeretnéd módosítani/szerkeszteni, kattints ide</a>
                                            </div>

                                            <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                                <a class="btn btn-secondary m-1" href="../admin.php">Vissza</a>
                                                <button type="submit" class="btn btn-primary btn-lg m-1">Mentés</button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include("partials/footer.php") ?>

    <script src="scripts/rating.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>