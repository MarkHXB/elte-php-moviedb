<?php
require_once('_init.php');
?>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Főoldal <span class="sr-only">(current)</span></a>
                </li>
                <?php if ($auth->authorize(["admin"])) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin.php">Admin felület</a>
                    </li>
                <?php endif; ?>
                <?php if (isAuthenticated() === true) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Kijelentkezés</a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../login.php">Bejelentkezés</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../register.php">Regisztráció</a>
                    </li>
                <?php endif; ?>
                
            </ul>
        </div>
    </nav>
</header>