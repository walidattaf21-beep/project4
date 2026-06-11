<?php

class Show {

    public static function getActiveShows() {
        global $pdo;

        // Include the config file to initialize the $pdo variable if it's not already set
        if (!isset($pdo)) {
            require_once __DIR__ . '/../database/config.php';
        }

        $sql = "
            SELECT
                Naam,
                Beschrijving,
                Datum,
                Tijd,
                MaxAantalTickets,
                Beschikbaarheid
            FROM Voorstelling
            WHERE IsActief = 1
            ORDER BY Datum ASC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(array $data): bool {
        global $pdo;

        if (!isset($pdo)) {
            require_once __DIR__ . '/../database/config.php';
        }

        // Gebruik MedewerkerId = 1 als standaard (later vervangen door ingelogde medewerker)
        $medewerkerId = 1;

        $sql = "
            INSERT INTO Voorstelling
                (MedewerkerId, Naam, Beschrijving, Datum, Tijd, MaxAantalTickets, Beschikbaarheid, IsActief)
            VALUES
                (:medewerker_id, :naam, :beschrijving, :datum, :tijd, :max_tickets, :beschikbaarheid, 1)
        ";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':medewerker_id'   => $medewerkerId,
                ':naam'            => $data['naam'],
                ':beschrijving'    => $data['beschrijving'],
                ':datum'           => $data['datum'],
                ':tijd'            => $data['tijd'],
                ':max_tickets'     => $data['max_tickets'],
                ':beschikbaarheid' => $data['beschikbaarheid'],
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
