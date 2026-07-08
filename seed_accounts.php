<?php
require __DIR__ . '/database/config.php';

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec("DELETE FROM Contact WHERE Email IN ('admin@aurora.nl', 'medewerker@aurora.nl', 'klant@aurora.nl')");
$pdo->exec("DELETE FROM Rol WHERE GebruikerId IN (SELECT Id FROM Gebruiker WHERE Gebruikersnaam IN ('admin.aurora', 'medewerker.aurora', 'klant.aurora'))");
$pdo->exec("DELETE FROM Bezoeker WHERE GebruikerId IN (SELECT Id FROM Gebruiker WHERE Gebruikersnaam IN ('admin.aurora', 'medewerker.aurora', 'klant.aurora'))");
$pdo->exec("DELETE FROM Medewerker WHERE GebruikerId IN (SELECT Id FROM Gebruiker WHERE Gebruikersnaam IN ('admin.aurora', 'medewerker.aurora', 'klant.aurora'))");
$pdo->exec("DELETE FROM Gebruiker WHERE Gebruikersnaam IN ('admin.aurora', 'medewerker.aurora', 'klant.aurora')");
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$adminHash = password_hash('Admin123!', PASSWORD_DEFAULT);
$employeeHash = password_hash('Medewerker123!', PASSWORD_DEFAULT);
$customerHash = password_hash('Klant123!', PASSWORD_DEFAULT);

$pdo->beginTransaction();

$insertUser = $pdo->prepare(
    'INSERT INTO Gebruiker (Voornaam, Tussenvoegsel, Achternaam, Gebruikersnaam, Wachtwoord, IsIngelogd, IsActief)
     VALUES (:voornaam, :tussenvoegsel, :achternaam, :gebruikersnaam, :wachtwoord, 0, 1)'
);

$insertContact = $pdo->prepare(
    'INSERT INTO Contact (GebruikerId, Email, Mobiel, IsActief) VALUES (:gebruiker_id, :email, :mobiel, 1)'
);
$insertRole = $pdo->prepare(
    'INSERT INTO Rol (GebruikerId, Naam, IsActief) VALUES (:gebruiker_id, :naam, 1)'
);
$insertEmployee = $pdo->prepare(
    'INSERT INTO Medewerker (GebruikerId, Nummer, Medewerkersoort, IsActief) VALUES (:gebruiker_id, :nummer, :medewerkersoort, 1)'
);
$insertVisitor = $pdo->prepare(
    'INSERT INTO Bezoeker (GebruikerId, Relatienummer, IsActief) VALUES (:gebruiker_id, :relatienummer, 1)'
);

$insertUser->execute([
    ':voornaam' => 'Admin',
    ':tussenvoegsel' => null,
    ':achternaam' => 'Aurora',
    ':gebruikersnaam' => 'admin.aurora',
    ':wachtwoord' => $adminHash,
]);
$adminId = (int)$pdo->lastInsertId();

$insertUser->execute([
    ':voornaam' => 'Medewerker',
    ':tussenvoegsel' => null,
    ':achternaam' => 'Aurora',
    ':gebruikersnaam' => 'medewerker.aurora',
    ':wachtwoord' => $employeeHash,
]);
$employeeId = (int)$pdo->lastInsertId();

$insertUser->execute([
    ':voornaam' => 'Klant',
    ':tussenvoegsel' => null,
    ':achternaam' => 'Aurora',
    ':gebruikersnaam' => 'klant.aurora',
    ':wachtwoord' => $customerHash,
]);
$customerId = (int)$pdo->lastInsertId();

$insertContact->execute([':gebruiker_id' => $adminId, ':email' => 'admin@aurora.nl', ':mobiel' => '+31600000001']);
$insertContact->execute([':gebruiker_id' => $employeeId, ':email' => 'medewerker@aurora.nl', ':mobiel' => '+31600000003']);
$insertContact->execute([':gebruiker_id' => $customerId, ':email' => 'klant@aurora.nl', ':mobiel' => '+31600000002']);

$insertRole->execute([':gebruiker_id' => $adminId, ':naam' => 'Administrator']);
$insertRole->execute([':gebruiker_id' => $employeeId, ':naam' => 'Medewerker']);
$insertRole->execute([':gebruiker_id' => $customerId, ':naam' => 'Bezoeker']);

$insertEmployee->execute([':gebruiker_id' => $adminId, ':nummer' => 99999, ':medewerkersoort' => 'Beheerder']);
$insertEmployee->execute([':gebruiker_id' => $employeeId, ':nummer' => 99998, ':medewerkersoort' => 'Ticketcontroleur']);
$insertVisitor->execute([':gebruiker_id' => $customerId, ':relatienummer' => 88888]);

$pdo->commit();
echo "Accounts created successfully.";
