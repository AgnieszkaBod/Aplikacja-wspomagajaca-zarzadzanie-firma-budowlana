<?php

function zobaczGeneruj_brygadzista() {
    ?>
    <div id="formularz">
        <form method="post" action="brygadzistaRaport.php">
            Wybierz budowe:<br/>
            <select class="select" name="budowa"><?php
                $polaczenie = @new mysqli("localhost", "root", "", "inzynierka");
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
            </select><br /><br />
            Wprowadź interesujący Cię okres: <br />
            Od: <input type="date" name="dataOD" /> <br />
            Do: <input type="date" name="dataDO" /> <br />
            <input class="button" type="submit" name="submit" value="Wygeneruj raport" />
            <input class="button" type="submit" name="submit" value="Zobacz historię" />

        </form> 
    </div>
    <?php
}
?>
<?php

function zobaczHistorie() {
    $dataOD = $_POST['dataOD'];
    $dataDO = $_POST['dataDO'];
    $nazwa_budowy = $_POST['budowa'];
    $polaczenie = @new mysqli("localhost", "root", "", "inzynierka");

    if ($dataOD != null && $dataDO != null) {

        $rezultat = $polaczenie->query("SELECT budowa.nazwa_budowy, pracownik.imie, pracownik.nazwisko, 
                                    harmonogram.godzina_rozpoczecia, harmonogram.godzina_zakonczenia, 
                                    harmonogram.data_pracy FROM  harmonogram JOIN pracownik  ON pracownik.id = 
                                    harmonogram.id_pracownika
                                    JOIN budowa ON harmonogram.id_budowy=budowa.id_budowy
                                    WHERE budowa.id_budowy='$nazwa_budowy' AND harmonogram.data_pracy BETWEEN '$dataOD' AND '$dataDO'
                                    ORDER BY harmonogram.data_pracy DESC, pracownik.id;");

        $ile_znalezionych = $rezultat->num_rows;

        $i = 0;
        while ($i < $ile_znalezionych) {
            $wiersz = $rezultat->fetch_assoc();
            if ($i == 0) {
                echo '<h3>' . $wiersz['nazwa_budowy'] . '</h3>';
                echo '<h4>Dnia ' . $wiersz['data_pracy'] . ' pracowali: </h4>';
                $data_poprzednia = $wiersz['data_pracy'];
            }

            if ($data_poprzednia != $wiersz['data_pracy']) {
                echo '<h4>Dnia ' . $wiersz['data_pracy'] . ' pracowali: </h4>';
                $data_poprzednia = $wiersz['data_pracy'];
            }

            echo $wiersz['imie'] . ' ';
            echo $wiersz['nazwisko'] . '<br/>';
            echo $wiersz['godzina_rozpoczecia'] . ' - ';
            echo $wiersz['godzina_zakonczenia'] . '<br/>';
            echo '<br/><br/>';
            $i++;
        }
    } else {
        echo 'Podaj obie daty!';
    }
}
?>
<?php

function generujRaport() {
    $polaczenie = @new mysqli("localhost", "root", "", "inzynierka");
    $dataOD = $_POST['dataOD'];
    $dataDO = $_POST['dataDO'];
    $id_budowy = $_POST['budowa'];
    $polaczenie = @new mysqli("localhost", "root", "", "inzynierka");

    if ($dataOD != null && $dataDO != null) {

        $rezultat = $polaczenie->query("SELECT budowa.nazwa_budowy, pracownik.imie, pracownik.nazwisko, 
                                    harmonogram.godzina_rozpoczecia, harmonogram.godzina_zakonczenia, 
                                    harmonogram.data_pracy FROM  harmonogram JOIN pracownik  ON pracownik.id = 
                                    harmonogram.id_pracownika
                                    JOIN budowa ON harmonogram.id_budowy=budowa.id_budowy
                                    WHERE budowa.id_budowy='$id_budowy' AND harmonogram.data_pracy BETWEEN '$dataOD' AND '$dataDO'
                                    ORDER BY harmonogram.data_pracy DESC, pracownik.id;");

        $ile_znalezionych = $rezultat->num_rows;
        ///zmienic bo wywala $nazwa_budowy=  $polaczenie -> query("SELECT nazwa_budowy from budowa WHERE id_budowy='$id_budowy';");
        $dane = "";
        $i = 0;
        while ($i < $ile_znalezionych) {
            $wiersz = $rezultat->fetch_assoc();
            if ($i == 0) {
                $dane .= $wiersz['nazwa_budowy'] . "\r\n" . "\r\n";
                $dane .= 'Dnia ' . $wiersz['data_pracy'] . ' pracowali:' . "\r\n";
                $data_poprzednia = $wiersz['data_pracy'];
            }

            if ($data_poprzednia != $wiersz['data_pracy']) {
                $dane .= 'Dnia ' . $wiersz['data_pracy'] . ' pracowali:' . "\r\n";
                $data_poprzednia = $wiersz['data_pracy'];
            }

            $dane .= $wiersz['imie'] . ' ';
            $dane .= $wiersz['nazwisko'] . "\r\n";
            $dane .= $wiersz['godzina_rozpoczecia'] . ' - ';
            $dane .= $wiersz['godzina_zakonczenia'] . "\r\n";
            $dane .= "\r\n";
            $i++;
        }

        $file = $dataOD . " - " . $dataDO . ".txt";

        $fp = fopen($file, "a");

        flock($fp, 2);

        fwrite($fp, $dane);

        flock($fp, 3);

        fclose($fp);
    } else {
        echo 'Podaj obie daty!';
    }
}
?>
<?php

function edytujBudowe_brygadzista() {
    ?>
    <div id="formularz">
        <form method="post" action="brygadzistaEdytujBudowe.php">
            Wybierz budowe:<br/>
            <select class="select" name="budowa"><?php
    $polaczenie = @new mysqli("localhost", "root", "", "inzynierka");
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

            </select><br /><br />
            Status budowy: <br />
            <select class="select" name="status_budowy"><?php
            if ($rezultat = @$polaczenie->query(
                            "SELECT * FROM status_budowy ")) {
                $liczba_budow = $rezultat->num_rows;
                if ($liczba_budow > 0) {
                    for ($i = 1; $i <= $liczba_budow; $i++) {
                        if ($rezultat1 = @$polaczenie->query(
                                        "SELECT * FROM status_budowy WHERE id_statusu_budowy='$i'")) {
                            $wiersz = $rezultat1->fetch_assoc();
                                ?><option value="<?php echo $i ?>"><?php echo $wiersz['nazwa_statusu_budowy']; ?></option><?php
                            }
                        }
                    }
                }
                ?>
            </select><br /><br />
            Wprowadź: <br />
            Data rozpoczęcia budowy: <input type="date" name="dataRozpoczecia" /> <br />
            Data zakończenia budowy: <input type="date" name="dataZakonczenia" /> <br /><br />

            <input class="button" name="submit" type="submit" value="Zatwierdz" />

        </form>
    </div>
    <?php
}
?>
<?php

function zmianaStatusu() {

    $polaczenie = @new mysqli("localhost", "root", "", "inzynierka");
    $status = $_POST['status_budowy'];
    $budowa = $_POST['budowa'];
    $dataRozpoczecia = $_POST['dataRozpoczecia'];
    $dataZakonczenia = $_POST['dataZakonczenia'];

    if ($dataZakonczenia != null) {
        if ($dataRozpoczecia != null) {
            if ($dataRozpoczecia > $dataZakonczenia) {
                echo 'BLAD!';
            } else {
                //roz=1 zak=1
                $polaczenie->query("UPDATE budowa SET data_rozpoczecia_budowy='$dataRozpoczecia', data_zakonczenia_budowy='$dataZakonczenia', id_statusu_budowy='$status' WHERE id_budowy='$budowa'");
                echo 'Zaktualizowano pomyślnie!';
            }
        } else {
            //roz=0 zak=1
            $polaczenie->query("UPDATE budowa SET  data_zakonczenia_budowy='$dataZakonczenia', id_statusu_budowy='$status' WHERE id_budowy='$budowa'");
            echo 'Zaktualizowano status i date zakonczenia!';
        }
    } else {
        if ($dataRozpoczecia != null) {
            //roz=1 zak=0
            $polaczenie->query("UPDATE budowa SET data_rozpoczecia_budowy='$dataRozpoczecia', id_statusu_budowy='$status' WHERE id_budowy='$budowa'");
            echo 'Zaktualizowano status i date rozpoczecia!';
        } else {
            //roz=0 zak=0
            $polaczenie->query("UPDATE budowa SET id_statusu_budowy='$status' WHERE id_budowy='$budowa'");
            echo 'Zaktualizowano pomyślnie status!';
        }
    }
}
?>
<?php

function brygadzistaEdycja_konta() {
    ?>
    <div id="formularz">
        <form method="post" action="brygadzistaEdycjaKonta.php">
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