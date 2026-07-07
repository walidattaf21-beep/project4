<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voorstelling Verwijderen - Aurora Theater</title>

    <!-- Gedeelde stijlen -->
    <link rel="stylesheet" href="../style.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .form-wrapper {
            max-width: 600px;
            margin: 60px auto 60px auto;
            padding: 0 20px;
        }

        .terug-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #0f0818;
            text-decoration: none;
            font-size: 14px;
        }

        .terug-link:hover {
            text-decoration: underline;
        }

        .confirm-card {
            background: #ffffff;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .confirm-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .confirm-card h1 {
            font-size: 22px;
            color: #0f0818;
            margin-bottom: 12px;
        }

        .confirm-card p {
            color: #6b7280;
            font-size: 15px;
            margin-bottom: 8px;
            line-height: 1.6;
        }

        .voorstelling-naam {
            font-weight: 700;
            color: #0f0818;
            font-size: 17px;
        }

        .confirm-details {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 16px 20px;
            margin: 20px 0 28px 0;
            text-align: left;
        }

        .confirm-details p {
            margin: 6px 0;
            font-size: 14px;
            color: #374151;
        }

        .confirm-details span {
            font-weight: 600;
            color: #0f0818;
        }

        .waarschuwing {
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 24px;
            font-size: 14px;
            color: #991b1b;
            text-align: left;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .btn-annuleer {
            display: inline-block;
            padding: 11px 24px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            color: #374151;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .btn-annuleer:hover {
            background-color: #e5e7eb;
            text-decoration: none;
            color: #374151;
        }

        .btn-verwijderen {
            display: inline-block;
            padding: 11px 24px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            font-family: Arial, sans-serif;
            border: none;
            cursor: pointer;
            background-color: red;
            color: #ffffff;
            transition: background-color 0.2s ease;
        }

        .btn-verwijderen:hover {
            background-color: darkred;
        }

        @media (max-width: 500px) {
            .confirm-card {
                padding: 28px 20px;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .btn-annuleer,
            .btn-verwijderen {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>

    <?php require_once __DIR__ . '/../views/includes/navbar.php'; ?>

    <div class="form-wrapper">

        <a href="overzichtvoorstellingen.php" class="terug-link">
            &larr; Terug naar overzicht
        </a>

        <div class="confirm-card">

            <div class="confirm-icon">🗑️</div>

            <h1>Voorstelling verwijderen</h1>

            <p>Weet je zeker dat je de volgende voorstelling wilt verwijderen?</p>

            <div class="confirm-details">
                <p><span>Naam:</span> <?= htmlspecialchars($voorstelling['Naam']); ?></p>
                <p><span>Datum:</span> <?= date('d-m-Y', strtotime($voorstelling['Datum'])); ?></p>
                <p><span>Tijd:</span> <?= substr($voorstelling['Tijd'], 0, 5); ?></p>
                <p><span>Capaciteit:</span> <?= (int)$voorstelling['MaxAantalTickets']; ?> plaatsen</p>
                <p><span>Status:</span> <?= htmlspecialchars($voorstelling['Beschikbaarheid']); ?></p>
            </div>

            <div class="waarschuwing">
                ⚠️ Deze actie kan niet ongedaan worden gemaakt.
            </div>

            <!-- Formulier met twee knoppen: Annuleren of Verwijderen -->
            <form method="POST" action="voorstelling-verwijderen.php?id=<?= (int)$voorstelling['Id']; ?>" id="verwijder-form">

                <div class="form-actions">

                    <!-- Scenario: Verwijderen geannuleerd -->
                    <button type="submit"
                            name="actie"
                            value="annuleren"
                            class="btn-annuleer"
                            id="btn-verwijderen-annuleren">
                        Annuleren
                    </button>

                    <!-- Scenario: Succesvol verwijderen -->
                    <button type="submit"
                            name="actie"
                            value="bevestigen"
                            class="btn-verwijderen"
                            id="btn-verwijderen-bevestigen">
                        Ja, verwijderen
                    </button>

                </div>

            </form>

        </div>
    </div>

    <?php require_once __DIR__ . '/../views/includes/footer.php'; ?>

</body>
</html>
