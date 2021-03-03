<?php

function zaloguj_form() {
    ?>
    <div id="formularz">
        <form method="post" action="logowanieFunkcje.php">
            Login:<input type="text" name="login" /> <br />
            Hasło:<input type="password" name="haslo" /> <br /><br />
            <input class="button" type="submit" value="Zaloguj się" />
        </form>
    </div>
    <?php
    if (isset($_SESSION['blad']))
        echo $_SESSION['blad'];
}
?>

<?php
function pracownik_form() {
    ?>
    <h4>Twoja stawka za 1h: <?php
        $polaczenie = @new mysqli("localhost", "root", "", "inzynierka");
        if ($rezultat = @$polaczenie->query(
                        sprintf("SELECT * FROM pracownik WHERE id='%s'", mysqli_real_escape_string($polaczenie, $_SESSION['id'])))) {
            $ilu_userow = $rezultat->num_rows;
            if ($ilu_userow > 0) {
                $wiersz = $rezultat->fetch_assoc();
                echo $wiersz['stawka_za_godzine'];
            }
        }
        ?> </h4>
    <div id="formularz">
        <form method="post" action="pracownikDzien.php">
            <table>
                <tr> <th>Podaj datę:</th> <th> <input type = "date" name = "data_pracy" /> </th> </tr>
                <tr> <th> Godzina rozpoczęcia pracy: </th> <th> <input type="time" name="godzinaOd"/> </th> </tr>
                <tr> <th> Godzina zakończenia pracy: </th> <th><input type="time" name="godzinaDo"/> </th> </tr>
                <tr> <th>Miejsce pracy:</th> <th> <select class="select" name="miejsce_pracy"><?php
                            if ($rezultat = @$polaczenie->query(
                                            "SELECT * FROM budowa ")) {
                                $liczba_budow = $rezultat->num_rows;
                                if ($liczba_budow > 0) {
                                    for ($i = 1; $i <= $liczba_budow; $i++) {
                                        if ($rezultat1 = @$polaczenie->query(
                                                        "SELECT * FROM budowa WHERE id_budowy='$i'")) {
                                            $wiersz = $rezultat1->fetch_assoc();
                                            ?><option value="<?php echo $i ?>"><?php echo $wiersz['nazwa_budowy']; ?></option><?php
                                            }
                                        }
                                    }
                                }
                                ?>

                        </select></th> </tr>
                <tr> <th>Notatka:</th> <th> <textarea rows="3" cols="20" id="notatka" name="notatka"></textarea> </th> </tr>
            </table>
            <input class="button" type="submit" name="submit" value="Zapisz" />
        </form>
    </div>
    <?php
}
?>
<?php

function dodajDzienPracy() {
    $polaczenie = @new mysqli("localhost", "root", "", "inzynierka");
    $data = $_POST['data_pracy'];
    $godzina_rozpoczecia = $_POST['godzinaOd'];
    $godzina_zakonczenia = $_POST['godzinaDo'];
    $miejsce_pracy = $_POST['miejsce_pracy'];
    $notatka = $_POST['notatka'];
    $id_pracownika = $_SESSION['id'];

    $timeArray1 = explode(':', $godzina_rozpoczecia);
    $dec1 = ($timeArray1[0]) + (($timeArray1[1]) / 60);
    $timeArray2 = explode(':', $godzina_zakonczenia);
    $dec2 = ($timeArray2[0]) + (($timeArray2[1]) / 60);

    $roznica = $dec2 - $dec1;

    if ($dec1 > $dec2) {
        echo 'Błędnie podane godziny pracy!';
    } else {
        if ($rezultat = $polaczenie->query("SELECT * from harmonogram WHERE data_pracy = '$data' AND godzina_rozpoczecia = '$godzina_rozpoczecia' AND godzina_zakonczenia = '$godzina_zakonczenia' AND id_budowy='$miejsce_pracy' AND id_pracownika='$id_pracownika'")) {
            $ile = $rezultat->num_rows;
            if ($ile > 0) {
                $wiersz = $rezultat->fetch_assoc();
                $notatkaBaza = $wiersz['notatka'];
                if ($polaczenie->query("UPDATE harmonogram SET notatka='$notatkaBaza $notatka' WHERE data_pracy = '$data' AND godzina_rozpoczecia = '$godzina_rozpoczecia' AND godzina_zakonczenia = '$godzina_zakonczenia' AND id_budowy='$miejsce_pracy' AND id_pracownika='$id_pracownika' ")) {
                    echo 'Zaktualizowano notatke!';
                }
            } else {
                if ($polaczenie->query("INSERT INTO harmonogram VALUES (NULL, '$data', '$godzina_rozpoczecia', '$godzina_zakonczenia', '$notatka', '$roznica', '$miejsce_pracy', ' $id_pracownika')")) {
                    echo 'Pomyślnie dodano!';
                } else {

                    throw new Exception($polaczenie->error);
                }
            }
        }
    }
}
?>

<?php

function pracownikEdycja_form() {
    ?>
    <div id="formularz">
        <form method="post" action="pracownikEdycja.php">
            <table>
                <tr> <th>Wprowadź obecne hasło:</th> <th> <input type="password" name="obecne_haslo" /> </th> </tr>
                <tr> <th>Wprowadź nowe hasło: </th> <th><input type="password" name="nowe_haslo" /> </th> </tr>
            </table>
            <input class="button" type="submit" value="Zapisz hasło" name="submit"/>
        </form>
    </div>
    <?php
}
?>

<?php

function update() {
    $polaczenie = @new mysqli("localhost", "root", "", "inzynierka");

    $obecne_haslo = $_POST['obecne_haslo'];
    $nowe_haslo = $_POST['nowe_haslo'];

    $haslo_hash = password_hash($nowe_haslo, PASSWORD_DEFAULT);

    if ((strlen($nowe_haslo) >= 8) && (strlen($nowe_haslo) <= 20)) {
        if ($rezultat = @$polaczenie->query(
                        sprintf("SELECT * FROM pracownik WHERE id='%s'", mysqli_real_escape_string($polaczenie, $_SESSION['id'])))) {
            $ilu_userow = $rezultat->num_rows;
            if ($ilu_userow > 0) {
                $wiersz = $rezultat->fetch_assoc();

                if (password_verify($obecne_haslo, $wiersz['haslo'])) {
                    $ID = $wiersz['id'];
                    $polaczenie->query("UPDATE pracownik SET haslo='$haslo_hash' WHERE id='$ID'");
                    echo 'Udana zmiana';

                    $rezultat->free_result();
                } else
                    echo 'Podane hasło jest nieprawidłowe!';
            }
        }
    } else
        echo 'Nowe hasło musi mieć od 8 do 20 znaków!';
}
?>

<?php

function pracownikRaport_form() {
    ?>
    <div id="formularz">
        <form method="post" action="pracownikRaport.php">
            Wprowadź interesujący Cię okres: <br />
            Od: <input type="date" name="dataOD" /> <br />
            Do: <input type="date" name="dataDO" /> <br />
           <!-- <input class="button" type="submit" value="Policz pensję" name="submit" /> -->
            <input class="button" type="submit" value="Wygeneruj historię" name="submit" />
            <input class="button" type="submit" value="Wygeneruj raport" name="submit" />
        </form>
    </div>
    <?php
}
?>

<?php

function generujHistorie() {
    $polaczenie = @new mysqli("localhost", "root", "", "inzynierka");
    $dataOd = $_POST['dataOD'];
    $dataDo = $_POST['dataDO'];
    $id = $_SESSION['id'];

    if ($dataOd != null && $dataDo != null) {

        $stawka = $polaczenie->query("SELECT stawka_za_godzine FROM pracownik WHERE id='$id'");
        $wiersz1 = $stawka->fetch_assoc();

        $rezultat = $polaczenie->query("SELECT harmonogram.data_pracy, harmonogram.godzina_rozpoczecia, harmonogram.godzina_zakonczenia, harmonogram.notatka, budowa.nazwa_budowy FROM harmonogram JOIN  budowa ON  harmonogram.id_budowy=budowa.id_budowy WHERE id_pracownika='$id' AND data_pracy BETWEEN '$dataOd' AND '$dataDo'");

        $ile_znalezionych = $rezultat->num_rows;

        $i = 0;
        while ($i < $ile_znalezionych) {
            $wiersz = $rezultat->fetch_assoc();
            echo $wiersz['data_pracy'] . '<br/>';
            echo $wiersz['nazwa_budowy'] . '<br/>';
            echo $wiersz['notatka'] . '<br/>';
            echo $wiersz['godzina_rozpoczecia'] . '<br/>';
            echo $wiersz['godzina_zakonczenia'] . '<br/>';
            echo '<br/>';
            $i++;
        }
        $liczba_godzin = $polaczenie->query("SELECT SUM(przepracowane_godziny) FROM harmonogram WHERE id_pracownika='$id' AND data_pracy BETWEEN '$dataOd' AND '$dataDo'");
        $wiersz2 = $liczba_godzin->fetch_assoc();

        $pensja = $wiersz1['stawka_za_godzine'] * $wiersz2['SUM(przepracowane_godziny)'];
        echo 'Pensja za powyższy okres: ' . round($pensja, 2) . 'zł';
        echo '<br/><br/>';
    } else {
        echo 'Podaj obie daty!';
    }
}
?>
<?php

function generujRaport() {
    $polaczenie = @new mysqli("localhost", "root", "", "inzynierka");
    $dataOd = $_POST['dataOD'];
    $dataDo = $_POST['dataDO'];
    $id = $_SESSION['id'];

    if ($dataOd != null && $dataDo != null) {

        //liczenie pensji
        $stawka = $polaczenie->query("SELECT stawka_za_godzine FROM pracownik WHERE id='$id'");
        $wiersz1 = $stawka->fetch_assoc();
        $liczba_godzin = $polaczenie->query("SELECT SUM(przepracowane_godziny) FROM harmonogram WHERE id_pracownika='$id' AND data_pracy BETWEEN '$dataOd' AND '$dataDo'");
        $wiersz2 = $liczba_godzin->fetch_assoc();

        $pensja = $wiersz1['stawka_za_godzine'] * $wiersz2['SUM(przepracowane_godziny)'];


        $rezultat = $polaczenie->query("SELECT harmonogram.data_pracy, harmonogram.godzina_rozpoczecia, harmonogram.godzina_zakonczenia, harmonogram.notatka, budowa.nazwa_budowy FROM harmonogram JOIN  budowa ON  harmonogram.id_budowy=budowa.id_budowy WHERE id_pracownika='$id' AND data_pracy BETWEEN '$dataOd' AND '$dataDo'");

        $ile_znalezionych = $rezultat->num_rows;
        $dane = "";

        $i = 0;
        while ($i < $ile_znalezionych) {
            $wiersz = $rezultat->fetch_assoc();
            $dane .= $wiersz['data_pracy'] . "\r\n";
            $dane .= $wiersz['nazwa_budowy'] . "\r\n";
            $dane .= $wiersz['notatka'] . "\r\n";
            $dane .= $wiersz['godzina_rozpoczecia'] . "\r\n";
            $dane .= $wiersz['godzina_zakonczenia'] . "\r\n";
            $dane .= "\r\n";
            $i++;
        }
        $dane .= round($pensja, 2);

        $file = $dataOd . " - " . $dataDo . ".txt";

        $fp = fopen($file, "a");

        flock($fp, 2);

        fwrite($fp, $dane);

        flock($fp, 3);

        fclose($fp);
        
        echo 'Raport wygenerowany!';
    } else {
        echo 'Podaj obie daty!';
    }
}
?>