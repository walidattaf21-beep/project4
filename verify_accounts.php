<?php
require __DIR__ . '/database/config.php';

$stmt = $pdo->prepare(
    'SELECT c.Email, r.Naam AS RolNaam
     FROM Contact c
     JOIN Gebruiker g ON g.Id = c.GebruikerId
     LEFT JOIN Rol r ON r.GebruikerId = g.Id AND r.IsActief = 1
     WHERE c.Email IN (:a, :b, :c)'
);
$stmt->execute([
    ':a' => 'admin@aurora.nl',
    ':b' => 'medewerker@aurora.nl',
    ':c' => 'klant@aurora.nl'
]);

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo $row['Email'] . ' | ' . ($row['RolNaam'] ?? 'Geen rol') . PHP_EOL;
}
