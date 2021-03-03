<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="CSS/index.css" type="text/css" />
        <link rel="stylesheet" href="CSS/formularz.css" type="text/css" />
    </head>
    <body>
        <?php
        echo '<h2>Strona startowa</h2>';
        echo '<h3>Nazwa firmy</h3>';
        include_once 'funkcje.php';
        zaloguj_form();
        ?>
    </body>
    <footer>
        Błażewicz Piotr | Bodziak Agnieszka &copy; Lublin 2021
    </footer>
</html>
