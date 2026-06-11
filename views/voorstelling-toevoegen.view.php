<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voorstelling Toevoegen - Aurora Theater</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Gedeelde stijlen (navbar / footer) -->
    <link rel="stylesheet" href="../style.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ===== PAGINA ACHTERGROND ===== */
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1a2744 50%, #0d2137 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        /* ===== CONTAINER ===== */
        .form-wrapper {
            max-width: 760px;
            margin: 50px auto 80px auto;
            padding: 0 20px;
        }

        /* ===== TERUG KNOP ===== */
        .terug-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 28px;
            transition: color 0.2s ease;
        }

        .terug-link:hover {
            color: #e2e8f0;
        }

        .terug-link i {
            font-size: 13px;
        }

        /* ===== CARD ===== */
        .form-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 48px 52px;
            box-shadow:
                0 25px 60px rgba(0, 0, 0, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.07);
        }

        /* ===== HEADER ===== */
        .form-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .form-header .icon-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #16a34a, #22c55e);
            border-radius: 16px;
            font-size: 28px;
            margin-bottom: 18px;
            box-shadow: 0 8px 24px rgba(34, 197, 94, 0.35);
        }

        .form-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #f1f5f9;
            margin: 0 0 8px 0;
            letter-spacing: -0.5px;
        }

        .form-header p {
            color: #64748b;
            font-size: 15px;
            margin: 0;
        }

        /* ===== FLASH BERICHTEN ===== */
        .flash {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .flash-error {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

        .flash-success {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
        }

        /* ===== FORMULIER GRID ===== */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        /* ===== LABELS ===== */
        label {
            font-size: 13px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        label .required {
            color: #f87171;
            margin-left: 3px;
        }

        /* ===== INPUTS ===== */
        input[type="text"],
        input[type="date"],
        input[type="time"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 13px 16px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            color: #f1f5f9;
            transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
            box-sizing: border-box;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
        }

        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator {
            filter: invert(1) opacity(0.5);
            cursor: pointer;
        }

        input::placeholder,
        textarea::placeholder {
            color: #475569;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: rgba(34, 197, 94, 0.6);
            background: rgba(255, 255, 255, 0.09);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.12);
        }

        textarea {
            resize: vertical;
            min-height: 110px;
        }

        select option {
            background: #1e293b;
            color: #f1f5f9;
        }

        /* ===== DIVIDER ===== */
        .divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.07);
            margin: 32px 0;
        }

        /* ===== ACTIE KNOPPEN ===== */
        .form-actions {
            display: flex;
            gap: 14px;
            justify-content: flex-end;
            margin-top: 36px;
        }

        /* Annuleer knop */
        .btn-annuleer {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 26px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #94a3b8;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-annuleer:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #e2e8f0;
            border-color: rgba(255, 255, 255, 0.18);
        }

        /* Opslaan knop - GROEN */
        .btn-opslaan {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 13px 32px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            border: none;
            cursor: pointer;
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
            color: #ffffff;
            box-shadow:
                0 4px 20px rgba(34, 197, 94, 0.45),
                0 2px 6px rgba(0, 0, 0, 0.2);
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-opslaan::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.4s ease;
        }

        .btn-opslaan:hover {
            transform: translateY(-2px);
            box-shadow:
                0 8px 30px rgba(34, 197, 94, 0.55),
                0 4px 12px rgba(0, 0, 0, 0.25);
        }

        .btn-opslaan:hover::before {
            left: 100%;
        }

        .btn-opslaan:active {
            transform: translateY(0);
            box-shadow:
                0 3px 12px rgba(34, 197, 94, 0.35),
                0 1px 4px rgba(0, 0, 0, 0.2);
        }

        /* ===== TIPS SECTIE ===== */
        .tips-box {
            background: rgba(34, 197, 94, 0.06);
            border: 1px solid rgba(34, 197, 94, 0.15);
            border-radius: 12px;
            padding: 18px 20px;
            margin-top: 28px;
        }

        .tips-box .tips-title {
            font-size: 13px;
            font-weight: 700;
            color: #4ade80;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 7px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .tips-box ul {
            margin: 0;
            padding-left: 20px;
        }

        .tips-box ul li {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 5px;
            line-height: 1.5;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 600px) {
            .form-card {
                padding: 30px 24px;
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
                justify-content: center;
            }
        }
    </style>
</head>

<body>

    <!-- ================= NAVBAR ================= -->
    <?php require_once __DIR__ . '/../views/includes/navbar.php'; ?>

    <!-- ================= INHOUD ================= -->
    <div class="form-wrapper">

        <!-- Terug knop -->
        <a href="overzichtvoorstellingen.php" class="terug-link">
            <i class="fas fa-arrow-left"></i> Terug naar overzicht
        </a>

        <div class="form-card">

            <!-- Header -->
            <div class="form-header">
                <div class="icon-badge">🎭</div>
                <h1>Nieuwe Voorstelling</h1>
                <p>Vul de gegevens in om een nieuwe voorstelling aan te maken</p>
            </div>

            <!-- Flash berichten -->
            <?php if (!empty($_SESSION['flash_error'])): ?>
                <div class="flash flash-error">
                    <i class="fas fa-circle-exclamation"></i>
                    <?= htmlspecialchars($_SESSION['flash_error']); ?>
                </div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <!-- Formulier -->
            <form method="POST" action="voorstelling-toevoegen.php" novalidate>

                <div class="form-grid">

                    <!-- Naam -->
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

                    <!-- Beschrijving -->
                    <div class="form-group full-width">
                        <label for="beschrijving">Beschrijving</label>
                        <textarea
                            id="beschrijving"
                            name="beschrijving"
                            placeholder="Korte omschrijving van de voorstelling..."
                            maxlength="1000"
                        ><?= htmlspecialchars($_POST['beschrijving'] ?? ''); ?></textarea>
                    </div>

                    <!-- Datum -->
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

                    <!-- Tijd -->
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

                    <!-- Max tickets -->
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

                    <!-- Beschikbaarheid -->
                    <div class="form-group">
                        <label for="beschikbaarheid">Status / Beschikbaarheid</label>
                        <select id="beschikbaarheid" name="beschikbaarheid">
                            <option value="Ingepland" <?= (($_POST['beschikbaarheid'] ?? 'Ingepland') === 'Ingepland') ? 'selected' : ''; ?>>
                                ✅ Ingepland
                            </option>
                            <option value="Uitverkocht" <?= (($_POST['beschikbaarheid'] ?? '') === 'Uitverkocht') ? 'selected' : ''; ?>>
                                🟠 Uitverkocht
                            </option>
                            <option value="Geannuleerd" <?= (($_POST['beschikbaarheid'] ?? '') === 'Geannuleerd') ? 'selected' : ''; ?>>
                                🔴 Geannuleerd
                            </option>
                        </select>
                    </div>

                </div>

                <div class="divider"></div>

                <!-- Actie knoppen -->
                <div class="form-actions">
                    <a href="overzichtvoorstellingen.php" class="btn-annuleer">
                        <i class="fas fa-xmark"></i> Annuleren
                    </a>
                    <button type="submit" class="btn-opslaan" id="btn-voorstelling-opslaan">
                        <i class="fas fa-plus-circle"></i> Voorstelling aanmaken
                    </button>
                </div>

            </form>

            <!-- Tips sectie -->
            <div class="tips-box">
                <div class="tips-title">
                    <i class="fas fa-lightbulb"></i> Tips
                </div>
                <ul>
                    <li>De datum moet vandaag of later zijn.</li>
                    <li>De status kan later nog worden aangepast in het beheerdashboard.</li>
                    <li>Maximaal 9.999 tickets per voorstelling.</li>
                </ul>
            </div>

        </div>
    </div>

    <!-- ================= FOOTER ================= -->
    <?php require_once __DIR__ . '/../views/includes/footer.php'; ?>

</body>
</html>
