<?php
$db_file = 'proiect.db';

echo "<h1>Populare Baza de Date</h1>";
echo "<a href='index.php'> Mergi la Prima Pagina (Meniu)</a><hr>";

try {
    if (!file_exists($db_file)) {
        throw new Exception("Eroare: Baza de date nu exista! Rulati setup.php o singura data la inceput.");
    }
    $db = new SQLite3($db_file); // Deschide fisierul bazei de date ptr a putea scrie in el

    // 1. Incepem tranzactia
    $db->exec('BEGIN');
    
    // Stergem mai intai NOTELE, apoi STUDENTII pentru a nu avea erori de cheie externa (duplicate)
    $db->exec("DELETE FROM Note");
    $db->exec("DELETE FROM Studenti");

    // Resetam ID-ul notelor, astfel incat prima nota adaugata sa aiba din nou ID-ul 1
    $db->exec("DELETE FROM sqlite_sequence WHERE name='Note'");

    echo "<p style='color:blue;'>Datele vechi au fost șterse. Se introduc datele curate.</p>";

    // 1. Introducem STUDENTII
    $db->exec("INSERT INTO Studenti (nr_legitimatie, nume, prenume) VALUES ('123456', 'Popa', 'Ion')");
    $db->exec("INSERT INTO Studenti (nr_legitimatie, nume, prenume) VALUES ('123457', 'Adam', 'Gheorghe')");
    $db->exec("INSERT INTO Studenti (nr_legitimatie, nume, prenume) VALUES ('123458', 'Pop', 'George')");

    echo "<p>Studentii au fost adaugati.</p>";

    // 2. Introducem NOTELE (Trigger-ul se va activa automat)
    
    // Note Popa Ion
    $db->exec("INSERT INTO Note (nr_legitimatie_stud, disciplina, an_studiu, nr_prezentare, data_prezentarii, nota_obtinuta) 
               VALUES ('123456', 'Matematica', 1, 1, '2003-12-22', 4)");
    $db->exec("INSERT INTO Note (nr_legitimatie_stud, disciplina, an_studiu, nr_prezentare, data_prezentarii, nota_obtinuta) 
               VALUES ('123456', 'Chimie', 2, 1, '2004-03-01', 10)");
    $db->exec("INSERT INTO Note (nr_legitimatie_stud, disciplina, an_studiu, nr_prezentare, data_prezentarii, nota_obtinuta) 
               VALUES ('123456', 'Engleza', 3, 2, '2005-09-02', 9)");

    // Note Adam Gheorghe
    $db->exec("INSERT INTO Note (nr_legitimatie_stud, disciplina, an_studiu, nr_prezentare, data_prezentarii, nota_obtinuta) 
               VALUES ('123457', 'Fizica', 1, 1, '2003-12-12', 9)");

    // Note Pop George
    $db->exec("INSERT INTO Note (nr_legitimatie_stud, disciplina, an_studiu, nr_prezentare, data_prezentarii, nota_obtinuta) 
               VALUES ('123458', 'Matematica', 1, 1, '2002-12-12', 10)");

    echo "<p>Notele au fost adaugate si mediile recalculate.</p>";

    // Validam totul
    $db->exec('COMMIT');
    
    echo "<h2>Baza de date este acum proaspata si fara duplicate!</h2>";

} catch (Exception $e) {
    $db->exec('ROLLBACK');
    echo "<p style='color:red;'>Eroare: " . $e->getMessage() . "</p>";
}
?>