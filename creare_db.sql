DROP TABLE IF EXISTS Note;
DROP TABLE IF EXISTS Studenti;

-- TASK 1 & 2 : Crearea structurii bazei de date:

CREATE TABLE Studenti(
    nr_legitimatie CHAR(6) PRIMARY KEY,
    nume VARCHAR(15) NOT NULL,
    prenume VARCHAR(20) NOT NULL,
    medie_generala DECIMAL(4,2) DEFAULT 0.00,
    media_anul1 DECIMAL(4,2) DEFAULT 0.00,
    media_anul2 DECIMAL(4,2) DEFAULT 0.00,
    media_anul3 DECIMAL(4,2) DEFAULT 0.00
);

CREATE TABLE Note(
    id_nota INTEGER PRIMARY KEY AUTOINCREMENT,
    nr_legitimatie_stud CHAR(6) NOT NULL,
    disciplina VARCHAR(50) NOT NULL,
    an_studiu INTEGER NOT NULL,
    nr_prezentare INTEGER NOT NULL,
    data_prezentarii DATE NOT NULL,
    nota_obtinuta INTEGER NOT NULL,

    -- Cheia externa (legatura intre tabele):
    FOREIGN KEY (nr_legitimatie_stud) REFERENCES Studenti(nr_legitimatie)
        ON DELETE CASCADE --Daca stergem studentul, i se sterg si notele
        ON UPDATE CASCADE --Daca se schimba legitimatia studentului, se actualizeaza si aici
);

-- TASK 7: Trigger-ul ptr calcului mediei:

CREATE TRIGGER trigger_calcul_medii
AFTER INSERT ON Note
FOR EACH ROW
BEGIN

    -- PASUL 1: Actualizăm media pe anul specific
    -- (Folosind o sub-interogare în loc de WITH)
    UPDATE Studenti
    SET 
        media_anul1 = CASE 
            WHEN NEW.an_studiu = 1 THEN (
                SELECT AVG(nota_maxima) 
                FROM (
                    SELECT MAX(nota_obtinuta) AS nota_maxima
                    FROM Note
                    WHERE nr_legitimatie_stud = NEW.nr_legitimatie_stud AND an_studiu = 1
                    GROUP BY disciplina
                )
            )
            ELSE media_anul1 -- Lăsăm neschimbat
        END,

        media_anul2 = CASE 
            WHEN NEW.an_studiu = 2 THEN (
                SELECT AVG(nota_maxima) 
                FROM (
                    SELECT MAX(nota_obtinuta) AS nota_maxima
                    FROM Note
                    WHERE nr_legitimatie_stud = NEW.nr_legitimatie_stud AND an_studiu = 2
                    GROUP BY disciplina
                )
            )
            ELSE media_anul2
        END,

        media_anul3 = CASE 
            WHEN NEW.an_studiu = 3 THEN (
                SELECT AVG(nota_maxima) 
                FROM (
                    SELECT MAX(nota_obtinuta) AS nota_maxima
                    FROM Note
                    WHERE nr_legitimatie_stud = NEW.nr_legitimatie_stud AND an_studiu = 3
                    GROUP BY disciplina
                )
            )
            ELSE media_anul3
        END
    WHERE nr_legitimatie = NEW.nr_legitimatie_stud;

    -- PASUL 2: Actualizăm media generală
    -- (O facem într-un al doilea UPDATE pentru a folosi valorile proaspăt calculate)
    UPDATE Studenti
    SET medie_generala = (
        SELECT AVG(medie) 
        FROM (
            SELECT media_anul1 AS medie FROM Studenti WHERE nr_legitimatie = NEW.nr_legitimatie_stud AND media_anul1 > 0
            UNION ALL
            SELECT media_anul2 AS medie FROM Studenti WHERE nr_legitimatie = NEW.nr_legitimatie_stud AND media_anul2 > 0
            UNION ALL
            SELECT media_anul3 AS medie FROM Studenti WHERE nr_legitimatie = NEW.nr_legitimatie_stud AND media_anul3 > 0
        )
    )
    WHERE nr_legitimatie = NEW.nr_legitimatie_stud;

END;
