<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/database/config.php';

if (!canAccessDashboard()) {
  flash('error', 'Je hebt geen toegang tot het beheerdashboard.');
  header('Location: inloggen.php');
  exit;
}

function h($value) {
  return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$flashMessages = $_SESSION['flash_messages'] ?? [];
unset($_SESSION['flash_messages']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_employee'])) {
  $voornaam = trim($_POST['voornaam'] ?? '');
  $achternaam = trim($_POST['achternaam'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $wachtwoord = $_POST['wachtwoord'] ?? '';
  $rol = trim($_POST['rol'] ?? '');
  $status = $_POST['status'] ?? 'actief';

  if ($voornaam === '' || $achternaam === '' || $email === '' || $wachtwoord === '' || $rol === '') {
    $_SESSION['flash_messages']['medewerkers'] = ['type' => 'error', 'text' => 'Vul de verplichte velden in.'];
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['flash_messages']['medewerkers'] = ['type' => 'error', 'text' => 'Vul een geldig e-mailadres in.'];
  } else {
    $stmt = $pdo->prepare('SELECT Id FROM Contact WHERE Email = :email');
    $stmt->execute([':email' => $email]);

    if ($stmt->fetch()) {
      $_SESSION['flash_messages']['medewerkers'] = ['type' => 'error', 'text' => 'E-mailadres bestaat al.'];
    } else {
      try {
        $pdo->beginTransaction();

        $gebruikersnaam = strtolower(str_replace(' ', '', $voornaam . '.' . $achternaam . '.' . time()));
        $hashedPassword = password_hash($wachtwoord, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
          'INSERT INTO Gebruiker (Voornaam, Tussenvoegsel, Achternaam, Gebruikersnaam, Wachtwoord, IsIngelogd, IsActief) VALUES (:voornaam, :tussenvoegsel, :achternaam, :gebruikersnaam, :wachtwoord, 0, :is_actief)'
        );
        $stmt->execute([
          ':voornaam' => $voornaam,
          ':tussenvoegsel' => null,
          ':achternaam' => $achternaam,
          ':gebruikersnaam' => $gebruikersnaam,
          ':wachtwoord' => $hashedPassword,
          ':is_actief' => ($status === 'actief' ? 1 : 0),
        ]);
        $gebruikerId = (int)$pdo->lastInsertId();

        $stmt = $pdo->prepare(
          'INSERT INTO Contact (GebruikerId, Email, Mobiel, IsActief) VALUES (:gebruiker_id, :email, :mobiel, 1)'
        );
        $stmt->execute([
          ':gebruiker_id' => $gebruikerId,
          ':email' => $email,
          ':mobiel' => '+31600000000'
        ]);

        $stmt = $pdo->prepare(
          'INSERT INTO Rol (GebruikerId, Naam, IsActief) VALUES (:gebruiker_id, :naam, 1)'
        );
        $stmt->execute([
          ':gebruiker_id' => $gebruikerId,
          ':naam' => $rol
        ]);

        $stmt = $pdo->prepare(
          'INSERT INTO Medewerker (GebruikerId, Nummer, Medewerkersoort, IsActief) VALUES (:gebruiker_id, :nummer, :medewerkersoort, :is_actief)'
        );
        $stmt->execute([
          ':gebruiker_id' => $gebruikerId,
          ':nummer' => 100000 + $gebruikerId,
          ':medewerkersoort' => $rol,
          ':is_actief' => ($status === 'actief' ? 1 : 0)
        ]);

        $pdo->commit();
        $_SESSION['flash_messages']['medewerkers'] = ['type' => 'success', 'text' => 'Medewerker is succesvol toegevoegd.'];
      } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['flash_messages']['medewerkers'] = ['type' => 'error', 'text' => 'Er is iets misgegaan bij het opslaan.'];
      }
    }
  }

  header('Location: beheerdashboard.php?section=medewerkers');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_message'])) {
  $onderwerp = trim($_POST['onderwerp'] ?? '');
  $bericht = trim($_POST['bericht'] ?? '');

  if ($onderwerp === '' || $bericht === '') {
    $_SESSION['flash_messages']['meldingen'] = ['type' => 'error', 'text' => 'Vul de verplichte velden in.'];
  } else {
    try {
      $pdo->beginTransaction();
      $nextNumber = (int)$pdo->query('SELECT COALESCE(MAX(Nummer), 0) + 1 FROM Melding')->fetchColumn();

      $stmt = $pdo->prepare(
        'INSERT INTO Melding (BezoekerId, MedewerkerId, Nummer, Type, Bericht, IsActief, Opmerking) VALUES (:bezoeker_id, :medewerker_id, :nummer, :type, :bericht, 1, :opmerking)'
      );
      $stmt->execute([
        ':bezoeker_id' => null,
        ':medewerker_id' => null,
        ':nummer' => $nextNumber,
        ':type' => 'Notificatie',
        ':bericht' => $bericht,
        ':opmerking' => $onderwerp,
      ]);

      $pdo->commit();
      $_SESSION['flash_messages']['meldingen'] = ['type' => 'success', 'text' => 'Melding is succesvol verstuurd.'];
    } catch (Exception $e) {
      $pdo->rollBack();
      $_SESSION['flash_messages']['meldingen'] = ['type' => 'error', 'text' => 'Er is iets misgegaan bij het versturen.'];
    }
  }

  header('Location: beheerdashboard.php?section=meldingen');
  exit;
}

$stmt = $pdo->query(
  'SELECT g.Id, g.Voornaam, g.Achternaam, g.IsActief, c.Email, r.Naam AS RolNaam FROM Gebruiker g LEFT JOIN Contact c ON c.GebruikerId = g.Id LEFT JOIN Rol r ON r.GebruikerId = g.Id AND r.IsActief = 1 ORDER BY g.Id DESC'
);
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

$medewerkers = array_map(function ($row) {
  $naam = trim($row['Voornaam'] . ' ' . $row['Achternaam']);
  $rol = !empty($row['RolNaam']) ? $row['RolNaam'] : 'Onbekend';
  $status = ((int)$row['IsActief'] === 1) ? 'actief' : 'inactief';
  $init = strtoupper(substr($row['Voornaam'], 0, 1) . substr($row['Achternaam'], 0, 1));
  return [
    'id' => (int)$row['Id'],
    'naam' => $naam,
    'rol' => $rol,
    'init' => $init,
    'av' => ['av-gold', 'av-purple', 'av-teal', 'av-blue'][((int)$row['Id'] - 1) % 4],
    'status' => $status,
  ];
}, $employees);

$stmt = $pdo->query(
  'SELECT m.Id, m.Nummer, m.Type, m.Bericht, m.Opmerking, m.DatumAangemaakt, m.IsActief FROM Melding m ORDER BY m.Id DESC'
);
$meldingRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$meldingen = array_map(function ($row) {
  return [
    'id' => (int)$row['Id'],
    'gebruiker' => !empty($row['Opmerking']) ? $row['Opmerking'] : 'Systeem',
    'onderwerp' => !empty($row['Opmerking']) ? $row['Opmerking'] : 'Melding',
    'bericht' => $row['Bericht'],
    'datum' => date('d-m-Y', strtotime($row['DatumAangemaakt'])),
    'status' => ((int)$row['IsActief'] === 1) ? 'nieuw' : 'gesloten'
  ];
}, $meldingRows);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aurora Theater – Beheerdashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>
 
<!-- ===== NAVIGATIEBALK ===== -->
<nav>
  <div>
    <div class="logo-title">Aurora</div>
    <div class="logo-sub">Theater</div>
  </div>
  <button class="hamburger" id="hamburger" onclick="toggleSidebar()" aria-label="Menu openen">
    <span></span><span></span><span></span>
  </button>
</nav>
 
<!-- Overlay voor mobiel sidebar -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="sluitSidebar()"></div>
 
<div class="layout">
 
  <!-- ===== ZIJBALK ===== -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-section">Overzicht</div>
    <div class="sidebar-item active" onclick="toonSectie('dashboard', this)">
      <i class="ti ti-layout-dashboard"></i> Dashboard
    </div>
 
    <div class="sidebar-section">Beheer</div>
    <div class="sidebar-item" onclick="toonSectie('medewerkers', this)">
      <i class="ti ti-users"></i> Medewerkers
    </div>
    <div class="sidebar-item" onclick="toonSectie('meldingen', this)">
      <i class="ti ti-bell"></i> Meldingen
      <span class="side-badge" id="sideBadge">3</span>
    </div>
 
    <div class="sidebar-section">Systeem</div>
    <div class="sidebar-item" onclick="toonSectie('instellingen', this)">
      <i class="ti ti-settings"></i> Instellingen
    </div>
  </aside>
 
  <!-- ===== HOOFDINHOUD ===== -->
  <main class="content">
 
    <!-- Database knop -->
    <div style="display:flex;justify-content:flex-end;margin-bottom:1.25rem">
      <button class="db-btn" onclick="toggleDB()">
        <span class="db-dot" id="dbDot"></span>
        <span id="dbLabel">Database: online</span>
      </button>
    </div>
 
    <!-- ============================
         SECTIE: DASHBOARD
    ============================= -->
    <div id="s-dashboard" class="section active">
      <h1 class="page-title">Dashboard</h1>
      <p class="page-sub">Welkom terug, beheerder. Hier is een overzicht van vandaag.</p>
 
      <div class="stats-grid">
        <div class="stat-card">
          <i class="ti ti-users stat-icon"></i>
          <div class="stat-label">Medewerkers</div>
          <div class="stat-value">8</div>
          <div class="stat-sub">6 actief</div>
        </div>
        <div class="stat-card">
          <i class="ti ti-bell stat-icon"></i>
          <div class="stat-label">Openstaande meldingen</div>
          <div class="stat-value">3</div>
          <div class="stat-sub">2 nieuw</div>
        </div>
        <div class="stat-card">
          <i class="ti ti-theater stat-icon"></i>
          <div class="stat-label">Voorstellingen</div>
          <div class="stat-value">5</div>
          <div class="stat-sub">deze maand</div>
        </div>
        <div class="stat-card">
          <i class="ti ti-ticket stat-icon"></i>
          <div class="stat-label">Tickets verkocht</div>
          <div class="stat-value">342</div>
          <div class="stat-sub">deze week</div>
        </div>
      </div>
 
      <div style="margin-bottom:1rem">
        <div style="font-size:15px;color:var(--gold);font-family:sans-serif;margin-bottom:0.75rem;display:flex;align-items:center;gap:8px;">
          <i class="ti ti-bell"></i> Recente meldingen
        </div>
        <div class="tabel-wrap">
          <table>
            <thead>
              <tr>
                <th>Gebruiker</th>
                <th>Onderwerp</th>
                <th>Datum</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr onclick="toonSectie('meldingen', document.querySelector('[onclick*=meldingen]'))">
                <td class="td-bold">Sophie Martens</td>
                <td>Technische storing lichten</td>
                <td class="td-muted">03-06-2025</td>
                <td><span class="sbadge s-nieuw">Nieuw</span></td>
              </tr>
              <tr onclick="toonSectie('meldingen', document.querySelector('[onclick*=meldingen]'))">
                <td class="td-bold">Lars de Vries</td>
                <td>Roostering aanpassing</td>
                <td class="td-muted">01-06-2025</td>
                <td><span class="sbadge s-open">Open</span></td>
              </tr>
              <tr onclick="toonSectie('meldingen', document.querySelector('[onclick*=meldingen]'))">
                <td class="td-bold">Emma van Loon</td>
                <td>Aanvraag extra repetitie</td>
                <td class="td-muted">25-05-2025</td>
                <td><span class="sbadge s-nieuw">Nieuw</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
 
    <!-- ============================
         SECTIE: MEDEWERKERS
    ============================= -->
    <div id="s-medewerkers" class="section">
      <h1 class="page-title">Medewerkers</h1>
      <p class="page-sub">Beheer alle medewerkers van Aurora Theater.</p>
 
      <div class="form-card">
        <div class="form-title"><i class="ti ti-user-plus"></i> Nieuwe medewerker toevoegen</div>

        <?php $employeeFlash = $flashMessages['medewerkers'] ?? null; ?>
        <?php if ($employeeFlash) : ?>
          <div class="<?= $employeeFlash['type'] === 'success' ? 'success-banner' : 'error-banner visible' ?> auto-hide" data-auto-hide="true" style="margin-bottom:1rem;opacity:1;transition:opacity 0.5s ease;">
            <?php if ($employeeFlash['type'] === 'error') : ?>
              <i class="ti ti-alert-circle"></i>
            <?php endif; ?>
            <div><?= h($employeeFlash['text']) ?></div>
          </div>
        <?php endif; ?>

        <form method="POST" action="beheerdashboard.php">
          <input type="hidden" name="add_employee" value="1">
          <div class="form-grid">
            <div class="form-group">
              <label for="mwVoornaam">Voornaam</label>
              <input type="text" id="mwVoornaam" name="voornaam" placeholder="Voornaam">
            </div>
            <div class="form-group">
              <label for="mwAchternaam">Achternaam</label>
              <input type="text" id="mwAchternaam" name="achternaam" placeholder="Achternaam">
            </div>
            <div class="form-group">
              <label for="mwEmail">E-mailadres</label>
              <input type="email" id="mwEmail" name="email" placeholder="naam@voorbeeld.nl">
            </div>
            <div class="form-group">
              <label for="mwWachtwoord">Wachtwoord</label>
              <input type="password" id="mwWachtwoord" name="wachtwoord" placeholder="••••••••">
            </div>
            <div class="form-group">
              <label for="mwRol">Rol / Functie</label>
              <input type="text" id="mwRol" name="rol" placeholder="bijv. Acteur, Technicus...">
            </div>
            <div class="form-group">
              <label for="mwStatus">Status</label>
              <select id="mwStatus" name="status">
                <option value="actief">Actief</option>
                <option value="inactief">Inactief</option>
              </select>
            </div>
          </div>
          <button type="submit" class="form-submit">
            <i class="ti ti-plus"></i> Opslaan
          </button>
        </form>
      </div>
 
      <div class="toolbar">
        <div class="toolbar-left">
          <div class="search">
            <i class="ti ti-search"></i>
            <input type="text" id="mwZoek" placeholder="Zoek naam of rol..." oninput="filterMedewerkers()">
          </div>
          <select class="filter-select" id="mwFilter" onchange="filterMedewerkers()">
            <option value="">Alle statussen</option>
            <option value="actief">Actief</option>
            <option value="verlof">Verlof</option>
            <option value="inactief">Inactief</option>
          </select>
        </div>
        <span class="count-text" id="mwCount">8 medewerkers</span>
      </div>
 
      <div class="error-banner" id="errMw">
        <i class="ti ti-database-off"></i>
        <div>
          <strong style="display:block;margin-bottom:3px">Geen verbinding mogelijk</strong>
          Er kan momenteel geen verbinding worden gemaakt met de database. Probeer het later opnieuw.
        </div>
      </div>
 
      <div class="med-grid" id="mwGrid"></div>
      <div class="empty" id="mwEmpty" style="display:none">
        <i class="ti ti-user-off"></i>Geen medewerkers gevonden.
      </div>
    </div>
 
    <!-- ============================
         SECTIE: MELDINGEN
    ============================= -->
    <div id="s-meldingen" class="section">
      <h1 class="page-title">Meldingen</h1>
      <p class="page-sub">Bekijk en beheer alle binnengekomen meldingen.</p>

      <div class="form-card">
        <div class="form-title"><i class="ti ti-send"></i> Nieuwe melding versturen</div>

        <?php $messageFlash = $flashMessages['meldingen'] ?? null; ?>
        <?php if ($messageFlash) : ?>
          <div class="<?= $messageFlash['type'] === 'success' ? 'success-banner' : 'error-banner visible' ?> auto-hide" data-auto-hide="true" style="margin-bottom:1rem;opacity:1;transition:opacity 0.5s ease;">
            <?php if ($messageFlash['type'] === 'error') : ?>
              <i class="ti ti-alert-circle"></i>
            <?php endif; ?>
            <div><?= h($messageFlash['text']) ?></div>
          </div>
        <?php endif; ?>

        <form method="POST" action="beheerdashboard.php">
          <input type="hidden" name="add_message" value="1">
          <div class="form-grid">
            <div class="form-group full">
              <label for="mlOnderwerp">Onderwerp</label>
              <input type="text" id="mlOnderwerp" name="onderwerp" placeholder="Bijv. Technische storing">
            </div>
            <div class="form-group full">
              <label for="mlBericht">Bericht</label>
              <textarea id="mlBericht" name="bericht" rows="5" placeholder="Beschrijf je melding..."></textarea>
            </div>
          </div>
          <button type="submit" class="form-submit">
            <i class="ti ti-send"></i> Versturen
          </button>
        </form>
      </div>
 
      <div class="toolbar">
        <div class="toolbar-left">
          <div class="search">
            <i class="ti ti-search"></i>
            <input type="text" id="mlZoek" placeholder="Zoek naam of onderwerp..." oninput="filterMeldingen()">
          </div>
          <select class="filter-select" id="mlFilter" onchange="filterMeldingen()">
            <option value="">Alle statussen</option>
            <option value="nieuw">Nieuw</option>
            <option value="open">Open</option>
            <option value="gesloten">Gesloten</option>
          </select>
        </div>
        <span class="count-text" id="mlCount">5 meldingen</span>
      </div>
 
      <div class="error-banner" id="errMl">
        <i class="ti ti-database-off"></i>
        <div>
          <strong style="display:block;margin-bottom:3px">Meldingen kunnen momenteel niet geladen worden</strong>
          Er is geen verbinding met de database. Probeer het later opnieuw.
        </div>
      </div>
 
      <div class="tabel-wrap">
        <table id="mlTabel">
          <thead>
            <tr>
              <th>Gebruiker</th>
              <th>Onderwerp</th>
              <th>Bericht preview</th>
              <th>Datum</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="mlBody"></tbody>
        </table>
        <div class="empty" id="mlEmpty" style="display:none">
          <i class="ti ti-bell-off"></i>Geen meldingen gevonden.
        </div>
      </div>
    </div>
 
    <!-- ============================
         SECTIE: INSTELLINGEN
    ============================= -->
    <div id="s-instellingen" class="section">
      <h1 class="page-title">Instellingen</h1>
      <p class="page-sub">Algemene instellingen van het Aurora Theater systeem.</p>
 
      <div class="form-card">
        <div class="form-title"><i class="ti ti-building"></i> Theaterinformatie</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Theaternaam</label>
            <input type="text" value="Aurora Theater">
          </div>
          <div class="form-group">
            <label>E-mailadres</label>
            <input type="email" value="info@auroratheater.nl">
          </div>
          <div class="form-group">
            <label>Telefoonnummer</label>
            <input type="tel" value="+31 20 123 4567">
          </div>
          <div class="form-group">
            <label>Stad</label>
            <input type="text" value="Amsterdam">
          </div>
          <div class="form-group full">
            <label>Adres</label>
            <input type="text" value="Theaterstraat 12, 1011 AB Amsterdam">
          </div>
        </div>
        <button class="form-submit">Opslaan</button>
      </div>
 
      <div class="form-card">
        <div class="form-title"><i class="ti ti-lock"></i> Wachtwoord wijzigen</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Huidig wachtwoord</label>
            <input type="password" placeholder="••••••••">
          </div>
          <div class="form-group">
            <label>Nieuw wachtwoord</label>
            <input type="password" placeholder="••••••••">
          </div>
        </div>
        <button class="form-submit">Wachtwoord bijwerken</button>
      </div>
    </div>
 
  </main>
</div>
 
<!-- ===== MELDING DETAIL MODAL ===== -->
<div class="modal-bg" id="modalBg" onclick="sluitModal(event)">
  <div class="modal">
    <button class="modal-x" onclick="sluitModalDirect()"><i class="ti ti-x"></i></button>
    <div class="modal-lbl">Melding detail</div>
    <div class="modal-h" id="mOnderwerp"></div>
    <div class="modal-lbl">Gebruiker</div>
    <div class="modal-val" id="mGebruiker"></div>
    <div class="modal-lbl">Bericht</div>
    <div class="modal-val" id="mBericht"></div>
    <div class="modal-lbl">Datum</div>
    <div class="modal-val" id="mDatum"></div>
    <div class="modal-lbl">Status</div>
    <div class="modal-val" id="mStatus"></div>
    <button class="modal-btn" onclick="sluitMelding()">Melding sluiten</button>
  </div>
</div>
 
<script>
/* ===================================================
   DATA
=================================================== */
const medewerkers = <?= json_encode($medewerkers, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
 
const meldingen = <?= json_encode($meldingen, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
 
let dbOnline     = true;
let activeMelding = null;
const avKleuren  = ["av-gold","av-purple","av-teal","av-blue"];
 
/* ===================================================
   SIDEBAR MOBIEL
=================================================== */
function toggleSidebar() {
  const sb  = document.getElementById('sidebar');
  const ov  = document.getElementById('sidebarOverlay');
  const hb  = document.getElementById('hamburger');
  sb.classList.toggle('open');
  ov.classList.toggle('visible');
  hb.classList.toggle('open');
}
 
function sluitSidebar() {
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('sidebarOverlay').classList.remove('visible');
  document.getElementById('hamburger').classList.remove('open');
}
 
/* ===================================================
   NAVIGATIE
=================================================== */
function toonSectie(naam, el) {
  document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
  document.getElementById('s-' + naam).classList.add('active');
  document.querySelectorAll('.sidebar-item').forEach(s => s.classList.remove('active'));
  if (el) {
    el.classList.add('active');
  } else {
    const match = Array.from(document.querySelectorAll('.sidebar-item')).find(item =>
      item.textContent.includes(naam.charAt(0).toUpperCase() + naam.slice(1))
    );
    if (match) match.classList.add('active');
  }
  if (naam === 'medewerkers') renderMedewerkers();
  if (naam === 'meldingen')   renderMeldingen();
  sluitSidebar();
}
 
/* ===================================================
   DATABASE SIMULATIE
=================================================== */
function toggleDB() {
  dbOnline = !dbOnline;
  document.getElementById('dbDot').classList.toggle('offline', !dbOnline);
  document.getElementById('dbLabel').textContent = dbOnline ? 'Database: online' : 'Database: offline';
  const actief = document.querySelector('.section.active').id.replace('s-','');
  toonSectie(actief, null);
}
 
/* ===================================================
   MEDEWERKERS
=================================================== */
function renderMedewerkers(lijst) {
  const grid = document.getElementById('mwGrid');
  const err  = document.getElementById('errMw');
  const leeg = document.getElementById('mwEmpty');
  const cnt  = document.getElementById('mwCount');
 
  err.classList.toggle('visible', !dbOnline);
  grid.style.display = dbOnline ? 'grid' : 'none';
  if (!dbOnline) { cnt.textContent = ''; return; }
 
  const data = lijst !== undefined ? lijst : medewerkers;
  cnt.textContent = data.length + ' medewerker' + (data.length !== 1 ? 's' : '');
  leeg.style.display = data.length === 0 ? 'block' : 'none';
  grid.style.display = data.length > 0   ? 'grid'  : 'none';
 
  grid.innerHTML = data.map(m => `
    <div class="med-card">
      <div class="avatar ${m.av}">${m.init}</div>
      <div>
        <p class="med-naam">${m.naam}</p>
        <p class="med-rol">${m.rol}</p>
      </div>
      <span class="badge b-${m.status}">${cap(m.status)}</span>
    </div>
  `).join('');
}
 
function filterMedewerkers() {
  const z = document.getElementById('mwZoek').value.toLowerCase();
  const s = document.getElementById('mwFilter').value;
  renderMedewerkers(medewerkers.filter(m =>
    (m.naam.toLowerCase().includes(z) || m.rol.toLowerCase().includes(z)) &&
    (s === '' || m.status === s)
  ));
}
 
function voegMedewerkerToe() {
  const voor = document.getElementById('mwVoornaam').value.trim();
  const acht = document.getElementById('mwAchternaam').value.trim();
  const rol  = document.getElementById('mwRol').value.trim();
  const stat = document.getElementById('mwStatus').value;
 
  if (!voor || !acht || !rol) { alert('Vul alle verplichte velden in.'); return; }
 
  const init = (voor[0] + acht[0]).toUpperCase();
  const av   = avKleuren[medewerkers.length % avKleuren.length];
  medewerkers.push({ id: Date.now(), naam: voor + ' ' + acht, rol, init, av, status: stat });
 
  document.getElementById('mwVoornaam').value  = '';
  document.getElementById('mwAchternaam').value = '';
  document.getElementById('mwRol').value        = '';
 
  renderMedewerkers();
}
 
/* ===================================================
   MELDINGEN
=================================================== */
function renderMeldingen(lijst) {
  const body  = document.getElementById('mlBody');
  const tabel = document.getElementById('mlTabel');
  const err   = document.getElementById('errMl');
  const leeg  = document.getElementById('mlEmpty');
  const cnt   = document.getElementById('mlCount');
 
  err.classList.toggle('visible', !dbOnline);
  tabel.style.display = dbOnline ? 'table' : 'none';
  if (!dbOnline) { cnt.textContent = ''; return; }
 
  const data = lijst !== undefined ? lijst : meldingen;
  cnt.textContent = data.length + ' melding' + (data.length !== 1 ? 'en' : '');
  leeg.style.display  = data.length === 0 ? 'block' : 'none';
  tabel.style.display = data.length > 0   ? 'table' : 'none';
 
  body.innerHTML = data.map(m => `
    <tr onclick="openMelding(${m.id})">
      <td class="td-bold">${m.gebruiker}</td>
      <td>${m.onderwerp}</td>
      <td class="td-preview">${m.bericht}</td>
      <td class="td-muted">${m.datum}</td>
      <td><span class="sbadge s-${m.status}">${cap(m.status)}</span></td>
      <td style="text-align:right"><i class="ti ti-chevron-right" style="color:#777;font-size:15px"></i></td>
    </tr>
  `).join('');
 
  const openAantal = meldingen.filter(m => m.status !== 'gesloten').length;
  document.getElementById('sideBadge').textContent = openAantal;
}
 
function filterMeldingen() {
  const z = document.getElementById('mlZoek').value.toLowerCase();
  const s = document.getElementById('mlFilter').value;
  renderMeldingen(meldingen.filter(m =>
    (m.gebruiker.toLowerCase().includes(z) || m.onderwerp.toLowerCase().includes(z)) &&
    (s === '' || m.status === s)
  ));
}
 
function openMelding(id) {
  const m = meldingen.find(x => x.id === id);
  if (!m) return;
  activeMelding = m;
  document.getElementById('mOnderwerp').textContent = m.onderwerp;
  document.getElementById('mGebruiker').textContent = m.gebruiker;
  document.getElementById('mBericht').textContent   = m.bericht;
  document.getElementById('mDatum').textContent     = m.datum;
  document.getElementById('mStatus').innerHTML = `<span class="sbadge s-${m.status}" style="font-size:13px">${cap(m.status)}</span>`;
  document.getElementById('modalBg').classList.add('visible');
}
 
function sluitModal(e)    { if (e.target.id === 'modalBg') sluitModalDirect(); }
function sluitModalDirect() { document.getElementById('modalBg').classList.remove('visible'); activeMelding = null; }
 
function sluitMelding() {
  if (!activeMelding) return;
  const m = meldingen.find(x => x.id === activeMelding.id);
  if (m) m.status = 'gesloten';
  sluitModalDirect();
  renderMeldingen();
}
 
/* ===================================================
   HULPFUNCTIEs
=================================================== */
function cap(s) { return s.charAt(0).toUpperCase() + s.slice(1); }
 
/* ===================================================
   INITIALISATIE
=================================================== */
const params = new URLSearchParams(window.location.search);
const requestedSection = params.get('section');

if (requestedSection === 'medewerkers') {
  toonSectie('medewerkers', null);
} else if (requestedSection === 'meldingen') {
  toonSectie('meldingen', null);
} else {
  toonSectie('dashboard', null);
}

setTimeout(() => {
  document.querySelectorAll('.auto-hide').forEach(el => {
    el.style.opacity = '0';
    setTimeout(() => {
      el.style.display = 'none';
    }, 500);
  });
}, 4000);
</script>
</body>
</html>