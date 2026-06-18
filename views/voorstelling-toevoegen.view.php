<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voorstelling Toevoegen - Aurora Theater</title>

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
            max-width: 700px;
            margin: 40px auto 60px auto;
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

        .form-card {
            background: #ffffff;
            border-radius: 8px;
            padding: 35px 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-card h1 {
            font-size: 22px;
            color: #0f0818;
            margin-bottom: 25px;
            padding-bottom: 12px;
            border-bottom: 2px solid #d4a75d;
        }

        .flash {
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .flash-error {
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        .flash-success {
            background-color: #d1fae5;
            border: 1px solid #6ee7b7;
            color: #065f46;
        }

        .flash-db-error {
            background-color: #fff3cd;
            border: 1px solid #f59e0b;
            color: #92400e;
            border-radius: 6px;
            padding: 14px 16px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .flash-db-error .db-error-icon {
            font-size: 20px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .flash-db-error .db-error-text {
            flex: 1;
        }

        .flash-db-error .db-error-text strong {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .btn-retry {
            display: inline-block;
            margin-top: 4px;
            padding: 7px 16px;
            border-radius: 5px;
            font-size: 13px;
            font-weight: 600;
            font-family: Arial, sans-serif;
            border: 1px solid #d97706;
            background-color: #f59e0b;
            color: #ffffff;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .btn-retry:hover {
            background-color: #d97706;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
        }

        label .required {
            color: #dc2626;
            margin-left: 2px;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            padding: 10px 12px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            color: #1f2937;
            background-color: #ffffff;
            box-sizing: border-box;
            outline: none;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #d4a75d;
            box-shadow: 0 0 0 2px rgba(212, 167, 93, 0.2);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-divider {
            height: 1px;
            background: #e5e7eb;
            margin: 25px 0;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn-annuleer {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            color: #374151;
            cursor: pointer;
        }

        .btn-annuleer:hover {
            background-color: #e5e7eb;
        }

        .btn-opslaan {
            display: inline-block;
            padding: 10px 24px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            font-family: Arial, sans-serif;
            border: none;
            cursor: pointer;
            background-color: #d4a75d;
            color: #ffffff;
            transition: background-color 0.2s ease;
        }

        .btn-opslaan:hover {
            background-color: #b8895a;
        }

        @media (max-width: 600px) {
            .form-card {
                padding: 25px 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full-width {
                grid-column: 1;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .btn-opslaan,
            .btn-annuleer {
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

        <div class="form-card">

            <h1>Nieuwe Voorstelling Toevoegen</h1>

            <?php if (!empty($_SESSION['flash_db_error'])): ?>
                <div class="flash-db-error" id="db-error-banner" role="alert">
                    <span class="db-error-icon">⚠️</span>
                    <div class="db-error-text">
                        <strong><?= htmlspecialchars($_SESSION['flash_db_error']); ?></strong>
                        <button type="button" class="btn-retry" id="btn-probeer-opnieuw"
                            onclick="document.getElementById('voorstelling-form').submit();">
                            Probeer opnieuw
                        </button>
                    </div>
                </div>
                <?php unset($_SESSION['flash_db_error']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['flash_error'])): ?>
                <div class="flash flash-error">
                    <?= htmlspecialchars($_SESSION['flash_error']); ?>
                </div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <form method="POST" action="voorstelling-toevoegen.php" id="voorstelling-form">

                <div class="form-grid">

                    <div class="form-group full-width">
                        <label for="naam">Naam van de voorstelling <span class="required">*</span></label>
                        <input
                            type="text"
                            id="naam"
                            name="naam"
                            placeholder="Bijv. De Betoverde Tuin"
                            value="<?= htmlspecialchars($_POST['naam'] ?? ''); ?>"
                            required
                            maxlength="100"
                        >
                    </div>

                    <div class="form-group full-width">
                        <label for="beschrijving">Beschrijving</label>
                        <textarea
                            id="beschrijving"
                            name="beschrijving"
                            placeholder="Korte omschrijving van de voorstelling..."
                            maxlength="1000"
                        ><?= htmlspecialchars($_POST['beschrijving'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="datum">Datum <span class="required">*</span></label>
                        <input
                            type="date"
                            id="datum"
                            name="datum"
                            value="<?= htmlspecialchars($_POST['datum'] ?? ''); ?>"
                            min="<?= date('Y-m-d'); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="tijd">Aanvangstijd <span class="required">*</span></label>
                        <input
                            type="time"
                            id="tijd"
                            name="tijd"
                            value="<?= htmlspecialchars($_POST['tijd'] ?? ''); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="max_tickets">Max. aantal tickets <span class="required">*</span></label>
                        <input
                            type="number"
                            id="max_tickets"
                            name="max_tickets"
                            placeholder="Bijv. 200"
                            value="<?= htmlspecialchars($_POST['max_tickets'] ?? ''); ?>"
                            min="1"
                            max="9999"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="beschikbaarheid">Status</label>
                        <select id="beschikbaarheid" name="beschikbaarheid">
                            <option value="Ingepland" <?= (($_POST['beschikbaarheid'] ?? 'Ingepland') === 'Ingepland') ? 'selected' : ''; ?>>Ingepland</option>
                            <option value="Uitverkocht" <?= (($_POST['beschikbaarheid'] ?? '') === 'Uitverkocht') ? 'selected' : ''; ?>>Uitverkocht</option>
                            <option value="Geannuleerd" <?= (($_POST['beschikbaarheid'] ?? '') === 'Geannuleerd') ? 'selected' : ''; ?>>Geannuleerd</option>
                        </select>
                    </div>

                </div>

                <div class="form-divider"></div>

                <div class="form-actions">
                    <a href="overzichtvoorstellingen.php" class="btn-annuleer">Annuleren</a>
                    <button type="submit" class="btn-opslaan" id="btn-voorstelling-opslaan">
                        Voorstelling toevoegen
                    </button>
                </div>

            </form>

        </div>
    </div>

    <?php require_once __DIR__ . '/../views/includes/footer.php'; ?>

</body>
</html>
