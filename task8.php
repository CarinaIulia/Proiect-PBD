<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Promovabilitate</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        .result-box { margin-top: 20px; padding: 15px; background: #e8f5e9; border: 1px solid #4caf50; display: inline-block; }
        select, button { padding: 10px; font-size: 16px; }
        .back-link { display: inline-block; margin-bottom: 20px; font-weight: bold; text-decoration: none; color: #007bff; }
    </style>
</head>
<body>

    <a href="index.php" class="back-link">Inapoi la Meniu</a>
    <h1>Calcul Promovabilitate</h1>
    <p>Alegeti o disciplina pentru a vedea rata de promovare:</p>

    <form method="GET">
        <select name="disciplina">
            <option value="Matematica">Matematica</option>
            <option value="Fizica">Fizica</option>
            <option value="Chimie">Chimie</option>
            <option value="Engleza">Engleza</option>
        </select>
        <button type="submit">Calculeaza</button>
    </form>

    <?php
    // DEFINIREA FUNCȚIEI CERUTE (Task 8)
    function calculeazaPromovabilitate($db, $numeDisciplina) {
        // 1. Numărăm câți studenți au dat examenul la acea materie (Total)
        // Folosim prepare() pentru securitate (împotriva SQL Injection)
        $stmtTotal = $db->prepare("SELECT COUNT(*) as total FROM Note WHERE disciplina = :disc");
        $stmtTotal->bindValue(':disc', $numeDisciplina, SQLITE3_TEXT);
        $resTotal = $stmtTotal->execute()->fetchArray(SQLITE3_ASSOC);
        $total = $resTotal['total'];

        if ($total == 0) {
            return "Nu există note pentru această disciplină.";
        }

        // 2. Numărăm câți au promovat (Nota >= 5)
        $stmtPromovati = $db->prepare("SELECT COUNT(*) as promovati FROM Note WHERE disciplina = :disc AND nota_obtinuta >= 5");
        $stmtPromovati->bindValue(':disc', $numeDisciplina, SQLITE3_TEXT);
        $resPromovati = $stmtPromovati->execute()->fetchArray(SQLITE3_ASSOC);
        $promovati = $resPromovati['promovati'];

        // 3. Calculăm procentul
        $procent = ($promovati / $total) * 100;
        
        // Returnăm textul formatat
        return "Rata de promovare la <strong>$numeDisciplina</strong> este: " . number_format($procent, 2) . "% ($promovati din $total studenți).";
    }

    // APLICAREA FUNCȚIEI (Dacă utilizatorul a apăsat butonul)
    if (isset($_GET['disciplina'])) {
        try {
            $db = new SQLite3('proiect.db');
            $disciplinaAleasa = $_GET['disciplina'];
            
            // Apelăm funcția creată mai sus
            $rezultat = calculeazaPromovabilitate($db, $disciplinaAleasa);

            echo "<div class='result-box'>$rezultat</div>";

        } catch (Exception $e) {
            echo "<p style='color:red'>Eroare: " . $e->getMessage() . "</p>";
        }
    }
    ?>

</body>
</html>