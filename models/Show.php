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
                Id,
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
            // Detecteer database-verbindingsfouten (SQLSTATE 08xxx of specifieke codes)
            $sqlState = $e->getCode();
            if (
                str_starts_with((string)$sqlState, '08') ||
                str_contains($e->getMessage(), 'Connection refused') ||
                str_contains($e->getMessage(), 'No connection') ||
                str_contains($e->getMessage(), 'could not connect') ||
                str_contains($e->getMessage(), 'SQLSTATE[HY000] [2002]') ||
                str_contains($e->getMessage(), 'php_network_getaddresses')
            ) {
                throw new RuntimeException('db_connection_error', 0, $e);
            }
            return false;
        }
    }

    public static function getById(int $id): ?array {
        global $pdo;

        if (!isset($pdo)) {
            require_once __DIR__ . '/../database/config.php';
        }

        $sql = "
            SELECT
                Id,
                Naam,
                Beschrijving,
                Datum,
                Tijd,
                MaxAantalTickets,
                Beschikbaarheid
            FROM Voorstelling
            WHERE Id = :id AND IsActief = 1
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function update(int $id, array $data): bool {
        global $pdo;

        if (!isset($pdo)) {
            require_once __DIR__ . '/../database/config.php';
        }

        $sql = "
            UPDATE Voorstelling
            SET
                Naam             = :naam,
                Beschrijving     = :beschrijving,
                Datum            = :datum,
                Tijd             = :tijd,
                MaxAantalTickets = :max_tickets,
                Beschikbaarheid  = :beschikbaarheid
            WHERE Id = :id AND IsActief = 1
        ";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':naam'            => $data['naam'],
                ':beschrijving'    => $data['beschrijving'],
                ':datum'           => $data['datum'],
                ':tijd'            => $data['tijd'],
                ':max_tickets'     => $data['max_tickets'],
                ':beschikbaarheid' => $data['beschikbaarheid'],
                ':id'              => $id,
            ]);
            return true;
        } catch (PDOException $e) {
            $sqlState = $e->getCode();
            if (
                str_starts_with((string)$sqlState, '08') ||
                str_contains($e->getMessage(), 'Connection refused') ||
                str_contains($e->getMessage(), 'No connection') ||
                str_contains($e->getMessage(), 'could not connect') ||
                str_contains($e->getMessage(), 'SQLSTATE[HY000] [2002]') ||
                str_contains($e->getMessage(), 'php_network_getaddresses')
            ) {
                throw new RuntimeException('db_connection_error', 0, $e);
            }
            return false;
        }
    }
}
