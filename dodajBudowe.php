<!DOCTYPE HTML>
<?php
session_start();

if (!isset($_SESSION['zalogowany']) || $_SESSION['stanowisko'] != 1) {
    header('Location: logout.php');
    exit();
}
?>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <link rel="stylesheet" href="CSS/index.css" type="text/css" />
        <link rel="stylesheet" href="CSS/admin.css" type="text/css" />
        <link rel="stylesheet" href="CSS/formularz.css" type="text/css" />
    </head>

    <body>
        <section>
            <nav>
                <a href="dodajUzytkownika.php">Dodaj użytkownika</a>
                <a href="usunUzytkownika.php">Usuń użytkownika</a>
                <a href="dodajBudowe.php">Dodaj budowe</a>
                <a href="logout.php">Wyloguj się</a>
            </nav>
        </section>
        <article>
            <?php
            echo '<h2>Witaj '.$_SESSION['imie'].'!</h2>';
            include "funkcjadodajBudowe.php";
            drukujForm();

            $akcja = filter_input(INPUT_POST, "submit");
            switch ($akcja) {
                case "Dodaj budowe" : dodajBudowe();
            }
            ?>

        </article>
    </body>
    <footer>
        Błażewicz Piotr | Bodziak Agnieszka &copy; Lublin 2021
    </footer>
</html>

