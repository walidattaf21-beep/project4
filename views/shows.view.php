<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voorstellingen - Aurora Theater</title>

    <!-- Gedeelde stijlen (navbar / footer) -->
    <link rel="stylesheet" href="../style.css">

    <!-- Font Awesome (hamburger menu iconen) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Pagina-specifieke stijlen -->
    <link rel="stylesheet" href="overzichtvoorstellingen.css">
</head>

<body>

    <!-- ================= NAVBAR ================= -->
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <!-- ================= INHOUD ================= -->
    <div class="container">

        <h1>Aankomende Voorstellingen</h1>

        <!-- Knop: voorstelling toevoegen -->
        <div class="acties-balk">
            <a href="../overzicht voorstellingen/voorstelling-toevoegen.php" class="btn-toevoegen" id="btn-nieuwe-voorstelling">
                <span class="btn-pulse-ring"></span>
                <i class="fas fa-plus"></i>
                <span class="btn-label">Nieuwe Voorstelling</span>
            </a>
        </div>


        <!-- Flash berichten -->
        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="flash flash-success"><?= htmlspecialchars($_SESSION['flash_success']); ?></div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>
        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="flash flash-error"><?= htmlspecialchars($_SESSION['flash_error']); ?></div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <?php if (empty($voorstellingen)): ?>

            <p class="geen-resultaten">Er zijn momenteel geen voorstellingen gepland.</p>

        <?php else: ?>

        <table>

            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Beschrijving</th>
                    <th>Datum</th>
                    <th>Tijd</th>
                    <th>Capaciteit</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

            <?php foreach ($voorstellingen as $voorstelling): ?>

                <tr>
                    <td data-label="Naam"><?= htmlspecialchars($voorstelling['Naam']); ?></td>
                    <td data-label="Beschrijving"><?= htmlspecialchars($voorstelling['Beschrijving']); ?></td>
                    <td data-label="Datum"><?= date('d-m-Y', strtotime($voorstelling['Datum'])); ?></td>
                    <td data-label="Tijd"><?= substr($voorstelling['Tijd'], 0, 5); ?></td>
                    <td data-label="Capaciteit"><?= (int) $voorstelling['MaxAantalTickets']; ?> plaatsen</td>
                    <td data-label="Status">
                        <?php if ($voorstelling['Beschikbaarheid'] === 'Ingepland'): ?>
                            <span class="status groen">Ingepland</span>
                        <?php elseif ($voorstelling['Beschikbaarheid'] === 'Uitverkocht'): ?>
                            <span class="status oranje">Uitverkocht</span>
                        <?php else: ?>
                            <span class="status rood">Geannuleerd</span>
                        <?php endif; ?>
                    </td>
                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

        <?php endif; ?>

    </div>

    <!-- ================= FOOTER ================= -->
    <?php require_once __DIR__ . '/includes/footer.php'; ?>

</body>
</html>
