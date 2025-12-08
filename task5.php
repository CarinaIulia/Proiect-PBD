<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Raport An Studiu</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:hover { background-color: #f9f9f9; }
        .back-link { display: inline-block; margin-bottom: 20px; font-weight: bold; text-decoration: none; color: #007bff; }
    </style>
</head>
<body>

    <a href="index.php" class="back-link">Inapoi la Meniu</a>

    <h1>Raport General Studenti</h1>
    <p>Afiseaza anul curent de studiu (calculat ca anul maxim in care studentul are note):</p>

    <?php
    try {
        $db = new SQLite3('proiect.db');

        // Interogarea SQL
        // 1. Selectăm datele studentului
        // 2. Folosim MAX(N.an_studiu) pentru a găsi cel mai mare an
        // 3. Facem GROUP BY pentru a comprima toate notele unui student într-un singur rând
        $sql = "SELECT S.nume, S.prenume, S.nr_legitimatie, MAX(N.an_studiu) as an_curent
                FROM Studenti S
                JOIN Note N ON S.nr_legitimatie = N.nr_legitimatie_stud
                GROUP BY S.nr_legitimatie, S.nume, S.prenume
                ORDER BY S.nume ASC";

        $result = $db->query($sql);

        echo "<table>";
        echo "<tr>
                <th>Nume</th>
                <th>Prenume</th>
                <th>Nr. Legitimație</th>
                <th>Anul de Studiu (Calculat)</th>
              </tr>";

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['nume']) . "</td>";
            echo "<td>" . htmlspecialchars($row['prenume']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nr_legitimatie']) . "</td>";
            
            // Afișăm anul maxim găsit
            echo "<td><strong>Anul " . htmlspecialchars($row['an_curent']) . "</strong></td>";
            echo "</tr>";
        }
        echo "</table>";

    } catch (Exception $e) {
        echo "<p style='color:red'>Eroare: " . $e->getMessage() . "</p>";
    }
    ?>

</body>
</html>