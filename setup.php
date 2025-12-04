<?php
// Numele fișierului bazei de date (va fi creat în același folder)
$db_file = 'proiect.db';

// Numele fișierului cu structura SQL
$sql_file = 'creare_db.sql';

echo "<h1> Setup Proiect Baze de Date</h1>";

try {
    // 1. Verificăm dacă fișierul SQL există
    if (!file_exists($sql_file)) {
        throw new Exception("Eroare: Fisierul <strong>$sql_file</strong> nu a fost gasit.");
    }

    // 2. Creăm (sau ne conectăm la) baza de date
    // Această comandă creează fișierul .db dacă nu există
    $db = new SQLite3($db_file);
    echo "<p style='color:green;'> Conectat cu succes la baza de date (fisierul <strong>$db_file</strong> a fost creat/deschis).</p>";

    // 3. Citim tot conținutul fișierului SQL
    $sql_commands = file_get_contents($sql_file);
    if ($sql_commands === false) {
        throw new Exception("Eroare: Nu s-a putut citi fisierul <strong>$sql_file</strong>.");
    }

    // 4. Executăm comenzile SQL (CREATE TABLE, CREATE TRIGGER)
    // Folosim exec() deoarece avem mai multe comenzi în fișierul .sql
    if ($db->exec($sql_commands)) {
        echo "<p style='color:green;'> Structura bazei de date (tabele si trigger) a fost creata cu succes!</p>";
    } else {
        throw new Exception("Eroare la executarea SQL: " . $db->lastErrorMsg());
    }

    echo "<h2> Setup-ul este complet!</h2>";

} catch (Exception $e) {
    // Afișăm orice eroare apare
    echo "<p style='color:red; font-weight:bold;'> A APARUT O PROBLEMA:</p>";
    echo "<p style='color:red;'>" . $e->getMessage() . "</p>";
} finally {
    // Închidem conexiunea la baza de date
    if (isset($db)) {
        $db->close();
    }
}

?>