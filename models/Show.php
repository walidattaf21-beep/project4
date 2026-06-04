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
}
