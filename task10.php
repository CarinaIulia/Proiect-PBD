<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Top Prezente</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        .winner-card { border: 2px solid #ffc107; background: #fffde7; padding: 20px; border-radius: 8px; max-width: 600px; }
        h2 { margin-top: 0; color: #ff8f00; }
        .stat { font-size: 1.2em; margin: 10px 0; }
        .back-link { display: inline-block; margin-bottom: 20px; font-weight: bold; text-decoration: none; color: #007bff; }
    </style>
</head>
<body>

    <a href="index.php" class="back-link">Inapoi la Meniu</a>

    <h1>Studentul cu cele mai multe prezentari</h1>

    <?php
    try {
        $db = new SQLite3('proiect.db');

        //Gasim studentul cu cele mai multe randuri in tabela Note
        $sqlTop = "SELECT nr_legitimatie_stud, COUNT(*) as total_prezentari 
                   FROM Note 
                   GROUP BY nr_legitimatie_stud 
                   ORDER BY total_prezentari DESC 
                   LIMIT 1";
        
        $resTop = $db->querySingle($sqlTop, true);

        if (!$resTop) {
            echo "<p>Nu există date în baza de date.</p>";
        } else {
            $legitimatie = $resTop['nr_legitimatie_stud'];
            $totalPrezentari = $resTop['total_prezentari'];

            //Aflam detaliile studentului (Nume, Prenume)
            $stmtDetalii = $db->prepare("SELECT nume, prenume FROM Studenti WHERE nr_legitimatie = :id");
            $stmtDetalii->bindValue(':id', $legitimatie, SQLITE3_TEXT);
            $detalii = $stmtDetalii->execute()->fetchArray(SQLITE3_ASSOC);

            //Calculam rata de promovabilitate pentru ACEST student (Examene cu nota >= 5 impartit la Total Prezentari)
            $stmtPromovate = $db->prepare("SELECT COUNT(*) as reusite FROM Note WHERE nr_legitimatie_stud = :id AND nota_obtinuta >= 5");
            $stmtPromovate->bindValue(':id', $legitimatie, SQLITE3_TEXT);
            $resPromovate = $stmtPromovate->execute()->fetchArray(SQLITE3_ASSOC);
            
            $reusite = $resPromovate['reusite'];
            $procent = ($totalPrezentari > 0) ? ($reusite / $totalPrezentari) * 100 : 0;

            //Afisam rezultatul
            echo "<div class='winner-card'>";
            echo "<h2>" . htmlspecialchars($detalii['nume'] . " " . $detalii['prenume']) . "</h2>";
            echo "<div class='stat'>Legitimatie: <strong>" . htmlspecialchars($legitimatie) . "</strong></div>";
            echo "<div class='stat'>Numar total prezentari: <strong>" . $totalPrezentari . "</strong></div>";
            echo "<div class='stat'>Rata de promovabilitate: <strong>" . number_format($procent, 2) . "%</strong> ($reusite examene luate)</div>";
            echo "</div>";
        }

    } catch (Exception $e) {
        echo "<p style='color:red'>Eroare: " . $e->getMessage() . "</p>";
    }
    ?>

</body>
</html>