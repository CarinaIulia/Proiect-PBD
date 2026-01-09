<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Raport Detaliat Note</title>
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

    <h1>Raport Detaliat Note Promovate</h1>
    <p>Lista disciplinelor promovate, ordonata conform cerintei:</p>

    <?php
    try {
        $db = new SQLite3('proiect.db');

        // 1. JOIN intre Studenti și Note pentru a avea numele studentului langa nota
        // 2. WHERE N.nota_obtinuta >= 5 -> Eliminam din lista orice nota sub 5
        // 3. ORDER BY -> Codul sorteaza datele mai intai dupa Nume, apoi dupa Prenume, apoi cronologic dupa Anul de studiu si in final alfabetic dupa Disciplina
        $sql = "SELECT S.nume, S.prenume, S.nr_legitimatie, N.disciplina, N.nota_obtinuta, N.an_studiu
                FROM Note N
                JOIN Studenti S ON N.nr_legitimatie_stud = S.nr_legitimatie
                WHERE N.nota_obtinuta >= 5
                ORDER BY S.nume ASC, S.prenume ASC, N.an_studiu ASC, N.disciplina ASC";

        $result = $db->query($sql);

        echo "<table>";
        echo "<tr>
                <th>Nume</th>
                <th>Prenume</th>
                <th>Nr. Legitimatie</th>
                <th>An Studiu</th>
                <th>Disciplina</th>
                <th>Nota</th>
              </tr>";

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['nume']) . "</td>";
            echo "<td>" . htmlspecialchars($row['prenume']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nr_legitimatie']) . "</td>";
            echo "<td>" . htmlspecialchars($row['an_studiu']) . "</td>";
            echo "<td>" . htmlspecialchars($row['disciplina']) . "</td>";
            echo "<td><strong>" . htmlspecialchars($row['nota_obtinuta']) . "</strong></td>";
            echo "</tr>";
        }
        echo "</table>";

    } catch (Exception $e) {
        echo "<p style='color:red'>Eroare: " . $e->getMessage() . "</p>";
    }
    ?>

</body>
</html>