<?php

// Database verbinding
$host = "localhost";
$dbname = "Aurora";
$username = "root";
$password = "";

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {

    die("Database verbinding mislukt.");
}


/*
|--------------------------------------------------------------------------
| Ophalen van alle actieve voorstellingen
|--------------------------------------------------------------------------
|
| We halen alle gegevens op uit de tabel Voorstelling.
| Alleen actieve voorstellingen worden getoond.
|
*/

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

$voorstellingen = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="nl">

<head>

    <meta charset="UTF-8">

    <title>Overzicht Voorstellingen</title>

    <link rel="stylesheet" href="css/voorstellingen.css">

</head>

<body>

<div class="container">

    <h1>Overzicht Voorstellingen</h1>

    <a href="voorstelling-toevoegen.php" class="btn-toevoegen">
        Nieuwe voorstelling toevoegen
    </a>

    <table>

        <thead>

            <tr>

                <th>ID</th>
                <th>Naam</th>
                <th>Beschrijving</th>
                <th>Datum</th>
                <th>Tijd</th>
                <th>Capaciteit</th>
                <th>Status</th>
                <th>Acties</th>

            </tr>

        </thead>

        <tbody>

        <?php foreach ($voorstellingen as $voorstelling): ?>

            <tr>

                <td>
                    <?= $voorstelling['Id']; ?>
                </td>

                <td>
                    <?= htmlspecialchars($voorstelling['Naam']); ?>
                </td>

                <td>
                    <?= htmlspecialchars($voorstelling['Beschrijving']); ?>
                </td>

                <td>
                    <?= $voorstelling['Datum']; ?>
                </td>

                <td>
                    <?= $voorstelling['Tijd']; ?>
                </td>

                <td>
                    <?= $voorstelling['MaxAantalTickets']; ?>
                </td>

                <td>

                    <?php

                    /*
                    |--------------------------------------------------------------------------
                    | Status tonen
                    |--------------------------------------------------------------------------
                    */

                    if($voorstelling['Beschikbaarheid'] == 'Ingepland')
                    {
                        echo "<span class='groen'>Ingepland</span>";
                    }

                    elseif($voorstelling['Beschikbaarheid'] == 'Uitverkocht')
                    {
                        echo "<span class='oranje'>Uitverkocht</span>";
                    }

                    else
                    {
                        echo "<span class='rood'>Geannuleerd</span>";
                    }

                    ?>

                </td>

                <td>

                    <a
                        href="voorstelling-wijzigen.php?id=<?= $voorstelling['Id']; ?>"
                        class="btn-wijzig"
                    >
                        Wijzigen
                    </a>

                    <a
                        href="voorstelling-verwijderen.php?id=<?= $voorstelling['Id']; ?>"
                        class="btn-verwijder"
                    >
                        Verwijderen
                    </a>

                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

</div>

</body>
</html>