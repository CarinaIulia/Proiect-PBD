<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Proiect Baze de Date</title>
    <style>
        body { font-family: sans-serif; margin: 40px; line-height: 1.6; }
        h1 { color: #2c3e50; }
        ul { list-style-type: none; padding: 0; }
        li { margin-bottom: 10px; }
        a { text-decoration: none; color: #007bff; font-weight: bold; font-size: 1.1em; }
        a:hover { text-decoration: underline; color: #0056b3; }
        .admin-zone { margin-top: 40px; padding-top: 20px; border-top: 1px solid #ccc; color: #666; }
        .admin-zone a { color: #666; font-weight: normal; font-size: 0.9em; }
    </style>
</head>
<body>

    <h1>Evidenta Studenti - Proiect BD</h1>
    <p>Selectati un raport pentru a vizualiza datele:</p>

    <ul>
        <li><a href="task4.php">Task 4: Studenti cu restante</a></li>
        <li><a href="task5.php">Task 5: Raport general studenti (Anul maxim)</a></li>
        <li><a href="task6.php">Task 6: Raport detaliat note</a></li>
        <li><a href="task8.php">Task 8: Calcul promovabilitate disciplina (Functie)</a></li>
        <li><a href="task9.php">Task 9: Studenti cu note sub 5 (2 ani consecutivi)</a></li>
        <li><a href="task10.php">Task 10: Studentul cu cele mai multe prezentari</a></li>
    </ul>

    <div class="admin-zone">
        <h3>Zona Administrativa (Setup)</h3>
        <ul>
            <li><a href="setup.php">Re-initializare Baza de Date (Sterge tot!)</a></li>
            <li><a href="populare.php">Populare cu date de test</a></li>
        </ul>
    </div>

</body>
</html>