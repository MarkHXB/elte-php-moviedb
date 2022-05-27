<?php

require_once('_init.php');


$data = [];
$errors = [];

if (count($_POST) !== 0) {
    if (!postKeyIsValid("username")) {
        $errors[] = "Nem adtál meg felhasználónevet!";
    } else if (!postKeyIsValid("password")) {
        $errors[] = "Nem adtál meg jelszót!";
    } else if (!$auth->authenticate($_POST["username"], $_POST["password"])) {
        $errors[] = "Helytelen felhasználónév vagy jelszó!";
    } else {
        $data["username"] = $_POST["username"];
        $data["password"] = $_POST["password"];
    }

    if (count($errors) === 0) {
        $current_user = $auth->authenticate($data["username"], $data["password"]);
        echo "nem";
        if ($current_user) {
            $auth->login($current_user);
            header("Location: home.php");
            die;
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <?php include("partials/header.php") ?>

    <main role="main">
        <section class="vh-100" style="background-color: #eee;">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-lg-12 col-xl-11">
                        <div class="card text-black" style="border-radius: 25px;">
                            <div class="card-body p-md-5">
                                <div class="row justify-content-center">
                                    <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                                        <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Bejelentkezés</p>
                                        <div class="d-flex flex-row flex-fill align-items-center justify-content-center mb-0">
                                            <?php foreach ($errors as $error) : ?>
                                                <p class="error"><?= $error ?? '' ?></p>
                                            <?php endforeach ?>
                                        </div>
                                        <form method="post" class="mx-1 mx-md-4" novalidate>

                                            <div class="d-flex flex-row align-items-center mb-4">
                                                <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                                <div class="form-outline flex-fill mb-0">
                                                    <input type="text" name="username" id="form-username" class="form-control" value="<?= $_POST['username'] ?? '' ?>" />
                                                    <label class="form-label" for="form-username">Felhasználónév</label>
                                                </div>
                                            </div>


                                            <div class="d-flex flex-row align-items-center mb-4">
                                                <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                                <div class="form-outline flex-fill mb-0">
                                                    <input type="password" name="password" id="form-password" class="form-control" value="<?= $_POST['password'] ?? '' ?>" />
                                                    <label class="form-label" for="form-password">Jelszó</label>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                                <a href="register.php">Ha még nincs felhasználói fiókod, itt tudsz regisztrálni egy újat</a>
                                            </div>

                                            <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                                <button type="submit" class="btn btn-primary btn-lg">Bejelentkezés</button>
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

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>