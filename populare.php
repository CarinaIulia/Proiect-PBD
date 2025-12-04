<?php
$db_file = 'proiect.db';

echo "<h1>Populare Bază de Date</h1>";
echo "<a href='index.php'>🏠 Mergi la Prima Pagină (Meniu)</a><hr>";

try {
    // 1. Ne conectăm la baza de date existentă
    if (!file_exists($db_file)) {
        throw new Exception("Eroare: Baza de date nu există! Rulați mai întâi setup.php.");
    }
    $db = new SQLite3($db_file);

    // Începem o "tranzacție" (totul sau nimic) pentru siguranță
    $db->exec('BEGIN');

    // ETAPA 1: Introducem STUDENȚII (fără medii, se calculează singure)

    
    // 1. Popa Ion
    $db->exec("INSERT OR IGNORE INTO Studenti (nr_legitimatie, nume, prenume) VALUES ('123456', 'Popa', 'Ion')");

    // 2. Adam Gheorghe
    $db->exec("INSERT OR IGNORE INTO Studenti (nr_legitimatie, nume, prenume) VALUES ('123457', 'Adam', 'Gheorghe')");

    // 3. Pop George
    $db->exec("INSERT OR IGNORE INTO Studenti (nr_legitimatie, nume, prenume) VALUES ('123458', 'Pop', 'George')");

    echo "<p>✅ Studenții au fost adăugați.</p>";

    // ETAPA 2: Introducem NOTELE (Aici se activează Trigger-ul!)

    // Note pentru Popa Ion 
    // Matematica, An 1, Nota 4
    $db->exec("INSERT INTO Note (nr_legitimatie_stud, disciplina, an_studiu, nr_prezentare, data_prezentarii, nota_obtinuta) VALUES ('123456', 'Matematica', 1, 1, '2003-12-22', 4)");
    
    // Chimie, An 2, Nota 10
    $db->exec("INSERT INTO Note (nr_legitimatie_stud, disciplina, an_studiu, nr_prezentare, data_prezentarii, nota_obtinuta) VALUES ('123456', 'Chimie', 2, 1, '2004-03-01', 10)");

    // Engleza, An 3, Nota 9
    $db->exec("INSERT INTO Note (nr_legitimatie_stud, disciplina, an_studiu, nr_prezentare, data_prezentarii, nota_obtinuta) VALUES ('123456', 'Engleza', 3, 2, '2005-09-02', 9)");


    // Note pentru Adam Gheorghe 
    // Fizica, An 1, Nota 9
    $db->exec("INSERT INTO Note (nr_legitimatie_stud, disciplina, an_studiu, nr_prezentare, data_prezentarii, nota_obtinuta) VALUES ('123457', 'Fizica', 1, 1, '2003-12-12', 9)");


    // Note pentru Pop George 
    // Matematica, An 1, Nota 10
    $db->exec("INSERT INTO Note (nr_legitimatie_stud, disciplina, an_studiu, nr_prezentare, data_prezentarii, nota_obtinuta) VALUES ('123458', 'Matematica', 1, 1, '2002-12-12', 10)");

    echo "<p>✅ Notele au fost adăugate și mediile au fost recalculate automat de Trigger.</p>";

    // Validăm tranzacția
    $db->exec('COMMIT');
    
    echo "<h2>Popularea cu date s-a încheiat cu succes!</h2>";

} catch (Exception $e) {
    // Dacă apare o eroare, anulăm tot ce am făcut în acest script
    $db->exec('ROLLBACK');
    echo "<p style='color:red;'>Eroare: " . $e->getMessage() . "</p>";
}
?>