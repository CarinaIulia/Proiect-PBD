<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Probleme Ani Consecutivi</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:hover { background-color: #fff3e0; } 
        .back-link { display: inline-block; margin-bottom: 20px; font-weight: bold; text-decoration: none; color: #007bff; }
    </style>
</head>
<body>

    <a href="index.php" class="back-link">Inapoi la Meniu</a>

    <h1>Studenti cu probleme majore</h1>
    <p>Studentii care au obtinut <strong>doar note sub 5</strong> (nu au promovat nicio materie) in 2 ani consecutivi:</p>

    <?php
    try {
        $db = new SQLite3('proiect.db');

        // LOGICA SQL:
        // Cautam studenti unde (Nota maxima in Anul X < 5) SI (Nota maxima in Anul X+1 < 5)
        // Folosim UNION pentru a uni cazurile "Anul 1+2" cu "Anul 2+3"
        
        $sql = "
            SELECT S.nume, S.prenume, S.nr_legitimatie, 'Anul 1 și 2' as ani_problema
            FROM Studenti S
            WHERE 
                (SELECT MAX(nota_obtinuta) FROM Note WHERE nr_legitimatie_stud = S.nr_legitimatie AND an_studiu = 1) < 5
                AND
                (SELECT MAX(nota_obtinuta) FROM Note WHERE nr_legitimatie_stud = S.nr_legitimatie AND an_studiu = 2) < 5
            
            UNION

            SELECT S.nume, S.prenume, S.nr_legitimatie, 'Anul 2 și 3' as ani_problema
            FROM Studenti S
            WHERE 
                (SELECT MAX(nota_obtinuta) FROM Note WHERE nr_legitimatie_stud = S.nr_legitimatie AND an_studiu = 2) < 5
                AND
                (SELECT MAX(nota_obtinuta) FROM Note WHERE nr_legitimatie_stud = S.nr_legitimatie AND an_studiu = 3) < 5
        ";

        $result = $db->query($sql);

        // Verificam daca avem rezultate
        $found = false;

        echo "<table>";
        echo "<tr>
                <th>Nume</th>
                <th>Prenume</th>
                <th>Nr. Legitimație</th>
                <th>Ani Consecutivi cu Probleme</th>
              </tr>";

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $found = true;
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['nume']) . "</td>";
            echo "<td>" . htmlspecialchars($row['prenume']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nr_legitimatie']) . "</td>";
            echo "<td><strong style='color:red;'>" . htmlspecialchars($row['ani_problema']) . "</strong></td>";
            echo "</tr>";
        }
        echo "</table>";

        if (!$found) {
            echo "<p><em>Din fericire, niciun student din baza de date curentă nu se află în această situație!</em></p>";
        }

    } catch (Exception $e) {
        echo "<p style='color:red'>Eroare: " . $e->getMessage() . "</p>";
    }
    ?>

</body>
</html>