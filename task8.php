<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Promovabilitate</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; line-height: 1.6; }
        .result-box { margin-top: 20px; padding: 15px; background: #e7f3fe; border-left: 6px solid #2196F3; display: inline-block; font-weight: bold; }
        select, button { padding: 8px; font-size: 16px; }
        .back-link { display: inline-block; margin-bottom: 20px; font-weight: bold; text-decoration: none; color: #007bff; }
    </style>
</head>
<body>

    <a href="index.php" class="back-link">Inapoi la Meniu</a>
    <h1>Calcul Promovabilitate</h1>
    <p>Alegeti o disciplina pentru a vedea rata de promovare:</p>

    <form method="GET">
        <select name="disciplina">
            <option value="">Selecteaza</option>
            <option value="Matematica" <?php if($disciplinaSelectata == 'Matematica') echo 'selected'; ?>>Matematica</option>
            <option value="Fizica" <?php if($disciplinaSelectata == 'Fizica') echo 'selected'; ?>>Fizica</option>
            <option value="Chimie" <?php if($disciplinaSelectata == 'Chimie') echo 'selected'; ?>>Chimie</option>
            <option value="Engleza" <?php if($disciplinaSelectata == 'Engleza') echo 'selected'; ?>>Engleza</option>
        </select>
        <button type="submit">Calculeaza</button>
    </form>

    <?php
    //Definirea functiei
    function calculeazaPromovabilitate($db, $numeDisciplina) {
        //Numaram cati studenti au dat examenul la acea materie. Folosim prepare() pentru securitate (impotriva SQL Injection)
        $stmtTotal = $db->prepare("SELECT COUNT(*) as total FROM Note WHERE disciplina = :disc"); //:disc este un placeholder folosit ptr secritate, evitand injectarea de cod SQL
        $stmtTotal->bindValue(':disc', $numeDisciplina, SQLITE3_TEXT);
        $resTotal = $stmtTotal->execute()->fetchArray(SQLITE3_ASSOC);
        $total = $resTotal['total'];

        if ($total == 0) {
            return "Nu exista note pentru aceasta disciplina.";
        }

        //Numaram cati studenti au promovat (Nota >= 5)
        $stmtPromovati = $db->prepare("SELECT COUNT(*) as promovati FROM Note WHERE disciplina = :disc AND nota_obtinuta >= 5");
        $stmtPromovati->bindValue(':disc', $numeDisciplina, SQLITE3_TEXT);
        $resPromovati = $stmtPromovati->execute()->fetchArray(SQLITE3_ASSOC);
        $promovati = $resPromovati['promovati'];

        //Calculam procentul
        $procent = ($promovati / $total) * 100;
        
        //Returnam textul formatat
        return "Rata de promovare la <strong>$numeDisciplina</strong> este: " . number_format($procent, 2) . "% ($promovati din $total studenti)."; //fctia number_format($procent, 2) este folosita pentru a afiaa rezultatul cu fix doua zecimale
    }

    //APLICAREA FUNCTIEI (Daca utilizatorul a apasat butonul)
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