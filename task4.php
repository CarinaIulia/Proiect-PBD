<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Studenti Restantieri</title>
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

    <h1>Studenti cu discipline nepromovate</h1>
    <p>Lista studentilor care au obtinut note mai mici de 5:</p>

    <?php
    try {
        // 1. Conectare la baza de date
        $db = new SQLite3('proiect.db');

        // 2. Interogarea SQL. Selectăm Numele, Prenumele, Disciplina și Nota. Legam tabela Note de Studenți prin ID-ul legitimației
        $sql = "SELECT S.nume, S.prenume, N.disciplina, N.nota_obtinuta, N.data_prezentarii 
                FROM Note N
                JOIN Studenti S ON N.nr_legitimatie_stud = S.nr_legitimatie
                WHERE N.nota_obtinuta < 5
                ORDER BY S.nume ASC";

        $result = $db->query($sql);

        // 3. Afișarea Tabelului
        echo "<table>";
        echo "<tr>
                <th>Nume</th>
                <th>Prenume</th>
                <th>Disciplina</th>
                <th>Nota</th>
                <th>Data</th>
              </tr>";

        // Parcurgem rezultatele rând cu rând
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['nume']) . "</td>";
            echo "<td>" . htmlspecialchars($row['prenume']) . "</td>";
            echo "<td>" . htmlspecialchars($row['disciplina']) . "</td>";
            echo "<td><strong style='color:red;'>" . htmlspecialchars($row['nota_obtinuta']) . "</strong></td>";
            echo "<td>" . htmlspecialchars($row['data_prezentarii']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";

    } catch (Exception $e) {
        echo "<p style='color:red'>Eroare: " . $e->getMessage() . "</p>";
    }
    ?>

</body>
</html>