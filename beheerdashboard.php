<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aurora Theater – Beheerdashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <style>
    /* ===== KLEURVARIABELEN – exact gebaseerd op Aurora Theater screenshot ===== */
    :root {
      --gold:      #e8b923;   /* geel/goud accent */
      --nav:       #2b2b2b;   /* donkere navigatiebalk */
      --bg:        #4a4a4a;   /* hoofdachtergrond grijs */
      --card:      #3d3d3d;   /* kaarten iets donkerder */
      --surface:   #333333;   /* diepste oppervlak */
      --border:    #5a5a5a;   /* randkleur */
      --text:      #f0f0f0;   /* primaire tekst */
      --muted:     #b0b0b0;   /* gedempte tekst */
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Georgia', serif;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
    }

    /* ===== NAVIGATIEBALK ===== */
    nav {
      background: var(--nav);
      height: 60px;
      padding: 0 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .logo-title { font-size: 20px; font-weight: 700; color: var(--gold); letter-spacing: 2px; text-transform: uppercase; }
    .logo-sub   { font-size: 11px; color: #999; letter-spacing: 1px; }

    .nav-links { display: flex; gap: 1.5rem; align-items: center; }
    .nav-links a { color: #ddd; text-decoration: none; font-size: 13px; font-family: sans-serif; transition: color 0.2s; }
    .nav-links a:hover, .nav-links a.active { color: var(--gold); }

    .btn-login    { background: none; border: 1px solid #777; color: #ddd; padding: 5px 14px; border-radius: 4px; font-size: 13px; font-family: sans-serif; cursor: pointer; transition: all 0.2s; }
    .btn-register { background: var(--gold); border: none; color: #1a1a1a; padding: 5px 14px; border-radius: 4px; font-size: 13px; font-family: sans-serif; font-weight: 700; cursor: pointer; transition: opacity 0.2s; }
    .btn-login:hover { border-color: #bbb; color: #fff; }
    .btn-register:hover { opacity: 0.85; }

    /* ===== MAIN LAYOUT ===== */
    .layout { display: flex; min-height: calc(100vh - 60px); }

    /* ===== ZIJBALK ===== */
    .sidebar {
      width: 220px;
      background: var(--surface);
      border-right: 1px solid var(--border);
      padding: 1.5rem 0;
      flex-shrink: 0;
    }

    .sidebar-section {
      font-size: 10px;
      font-family: sans-serif;
      color: #777;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      padding: 0 1.25rem;
      margin: 1.25rem 0 0.5rem;
    }

    .sidebar-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 9px 1.25rem;
      font-size: 13px;
      font-family: sans-serif;
      color: var(--muted);
      cursor: pointer;
      transition: all 0.15s;
      border-left: 3px solid transparent;
    }

    .sidebar-item:hover { background: var(--card); color: var(--text); }
    .sidebar-item.active { background: var(--card); color: var(--gold); border-left-color: var(--gold); }
    .sidebar-item i { font-size: 17px; }

    /* Badge in sidebar */
    .side-badge {
      margin-left: auto;
      background: #5a2020;
      color: #f08080;
      font-size: 10px;
      padding: 1px 6px;
      border-radius: 8px;
    }

    /* ===== INHOUDSGEBIED ===== */
    .content { flex: 1; padding: 2rem 1.75rem; overflow-x: hidden; }

    /* ===== PAGINATITEL ===== */
    .page-title { font-size: 24px; color: var(--gold); letter-spacing: 1px; margin-bottom: 0.3rem; }
    .page-sub   { font-size: 13px; color: var(--muted); font-family: sans-serif; margin-bottom: 1.75rem; }

    /* ===== STATISTIEKKAARTEN (bovenaan) ===== */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 1rem 1.25rem;
    }

    .stat-label { font-size: 11px; font-family: sans-serif; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px; }
    .stat-value { font-size: 28px; font-weight: 700; font-family: sans-serif; color: var(--text); line-height: 1; }
    .stat-sub   { font-size: 11px; font-family: sans-serif; color: var(--muted); margin-top: 4px; }

    .stat-icon {
      float: right;
      font-size: 24px;
      color: var(--gold);
      opacity: 0.7;
      margin-top: -2px;
    }

    /* ===== SECTIE WISSELEN ===== */
    .section { display: none; }
    .section.active { display: block; }

    /* ===== TABBLADEN ===== */
    .tabs {
      display: flex;
      border-bottom: 2px solid var(--border);
      margin-bottom: 1.5rem;
    }

    .tab-btn {
      background: none;
      border: none;
      border-bottom: 3px solid transparent;
      margin-bottom: -2px;
      padding: 0.6rem 1.25rem;
      cursor: pointer;
      font-size: 13px;
      font-family: sans-serif;
      color: var(--muted);
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s;
    }

    .tab-btn:hover { color: var(--gold); }
    .tab-btn.active { color: var(--gold); border-bottom-color: var(--gold); }

    .tab-badge {
      background: #5a2020;
      color: #f08080;
      font-size: 10px;
      padding: 1px 6px;
      border-radius: 8px;
    }

    /* ===== TOOLBAR (zoeken + filters) ===== */
    .toolbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 0.75rem;
      margin-bottom: 1.25rem;
    }

    .toolbar-left { display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center; }

    .count-text { font-size: 13px; color: var(--muted); font-family: sans-serif; }

    /* Zoekbalk */
    .search {
      display: flex;
      align-items: center;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 0 10px;
      gap: 7px;
      height: 36px;
      min-width: 200px;
    }

    .search i { color: #777; font-size: 15px; }
    .search input { background: none; border: none; color: var(--text); font-size: 13px; font-family: sans-serif; outline: none; width: 100%; }
    .search input::placeholder { color: #666; }

    /* Filter select */
    .filter-select {
      background: var(--surface);
      border: 1px solid var(--border);
      color: #ccc;
      height: 36px;
      padding: 0 10px;
      border-radius: 6px;
      font-size: 13px;
      font-family: sans-serif;
      outline: none;
      cursor: pointer;
    }

    /* DB simulatieknop */
    .db-btn {
      display: flex;
      align-items: center;
      gap: 7px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 0 12px;
      height: 36px;
      cursor: pointer;
      font-size: 12px;
      font-family: sans-serif;
      color: var(--muted);
      transition: all 0.2s;
    }

    .db-btn:hover { border-color: #888; color: var(--text); }
    .db-dot { width: 8px; height: 8px; border-radius: 50%; background: #6abf80; transition: background 0.3s; }
    .db-dot.offline { background: #e24b4a; }

    /* ===== FOUTMELDING BANNER ===== */
    .error-banner {
      display: none;
      background: #4a2020;
      border: 1px solid #7a3030;
      border-radius: 8px;
      padding: 1rem 1.25rem;
      margin-bottom: 1.25rem;
      color: #f0a0a0;
      font-family: sans-serif;
      font-size: 13px;
      align-items: center;
      gap: 10px;
    }

    .error-banner.visible { display: flex; }
    .error-banner i { font-size: 20px; color: #e24b4a; flex-shrink: 0; }

    /* ===== MEDEWERKER KAARTENGRID ===== */
    .med-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 1rem;
    }

    .med-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 1.25rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      gap: 0.6rem;
      transition: border-color 0.2s, transform 0.15s;
    }

    .med-card:hover { border-color: var(--gold); transform: translateY(-2px); }

    /* Avatar */
    .avatar {
      width: 50px; height: 50px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 16px; font-weight: 700; font-family: sans-serif;
    }

    .av-gold   { background: #5a3e00; color: var(--gold); }
    .av-purple { background: #3e2460; color: #c4a0ff; }
    .av-teal   { background: #1a3e38; color: #5ecfc4; }
    .av-blue   { background: #1a2e4a; color: #7ab8f0; }

    .med-naam { font-size: 14px; font-weight: 600; color: #efefef; font-family: sans-serif; }
    .med-rol  { font-size: 12px; color: var(--muted); font-family: sans-serif; }

    .badge {
      font-size: 11px; padding: 3px 10px; border-radius: 20px;
      font-family: sans-serif; font-weight: 600; letter-spacing: 0.5px;
    }

    .b-actief   { background: #1a3e28; color: #6abf80; border: 1px solid #2a6a44; }
    .b-verlof   { background: #3e3000; color: #e8b923; border: 1px solid #6a5200; }
    .b-inactief { background: #3e1a1a; color: #e07070; border: 1px solid #6a2a2a; }

    /* ===== TABEL (meldingen + voorstellingen) ===== */
    .tabel-wrap {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 10px;
      overflow: hidden;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-family: sans-serif;
      font-size: 13px;
    }

    table th {
      background: #2a2a2a;
      color: var(--gold);
      font-size: 10px;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      padding: 10px 14px;
      text-align: left;
      font-weight: 600;
      border-bottom: 1px solid var(--border);
    }

    table td {
      padding: 11px 14px;
      border-bottom: 1px solid #444;
      color: #ccc;
      vertical-align: middle;
    }

    table tr:last-child td { border-bottom: none; }
    table tbody tr:hover td { background: var(--card); cursor: pointer; }

    .td-bold    { color: #efefef; font-weight: 500; }
    .td-muted   { color: #888; font-size: 12px; white-space: nowrap; }
    .td-preview { max-width: 160px; color: #aaa; font-size: 12px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

    /* Status badges */
    .sbadge {
      font-size: 11px; padding: 3px 8px; border-radius: 12px;
      font-weight: 600; white-space: nowrap;
    }

    .s-nieuw    { background: #1a2e4a; color: #7ab8f0; border: 1px solid #2a4a7a; }
    .s-open     { background: #3e3000; color: #e8b923; border: 1px solid #6a5200; }
    .s-gesloten { background: #1a3e28; color: #6abf80; border: 1px solid #2a6a44; }
    .s-actief   { background: #1a3e28; color: #6abf80; border: 1px solid #2a6a44; }
    .s-geannuleerd { background: #3e1a1a; color: #e07070; border: 1px solid #6a2a2a; }
    .s-uitverkocht { background: #3e3000; color: #e8b923; border: 1px solid #6a5200; }

    /* ===== LEGE TOESTAND ===== */
    .empty {
      text-align: center;
      padding: 2.5rem 1rem;
      color: #666;
      font-family: sans-serif;
      font-size: 13px;
    }

    .empty i { font-size: 32px; display: block; margin-bottom: 0.6rem; }

    /* ===== MODAL ===== */
    .modal-bg {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.72);
      z-index: 500;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }

    .modal-bg.visible { display: flex; }

    .modal {
      background: var(--card);
      border: 1px solid var(--gold);
      border-radius: 12px;
      padding: 1.75rem;
      max-width: 500px;
      width: 100%;
      position: relative;
    }

    .modal-x {
      position: absolute; top: 14px; right: 14px;
      background: none; border: none; color: #888;
      cursor: pointer; font-size: 20px;
    }

    .modal-x:hover { color: var(--gold); }
    .modal-lbl { font-size: 10px; color: #888; font-family: sans-serif; text-transform: uppercase; letter-spacing: 1px; margin: 0.9rem 0 3px; }
    .modal-val { font-family: sans-serif; font-size: 14px; color: #ddd; line-height: 1.6; }
    .modal-h   { font-size: 18px; color: var(--gold); }

    .modal-btn {
      margin-top: 1.25rem;
      background: var(--gold); color: #1a1a1a;
      border: none; padding: 8px 20px; border-radius: 6px;
      font-family: sans-serif; font-size: 13px; font-weight: 700;
      cursor: pointer; transition: opacity 0.2s;
    }

    .modal-btn:hover { opacity: 0.85; }

    /* ===== FORMULIER (nieuw toevoegen) ===== */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
      margin-bottom: 1.25rem;
    }

    .form-group { display: flex; flex-direction: column; gap: 5px; }
    .form-group.full { grid-column: 1 / -1; }

    .form-group label { font-size: 12px; font-family: sans-serif; color: var(--muted); }

    .form-group input,
    .form-group select,
    .form-group textarea {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 6px;
      color: var(--text);
      font-size: 13px;
      font-family: sans-serif;
      padding: 8px 10px;
      outline: none;
      transition: border-color 0.2s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus { border-color: var(--gold); }

    .form-group textarea { resize: vertical; min-height: 80px; }

    .form-submit {
      background: var(--gold); color: #1a1a1a;
      border: none; padding: 9px 22px; border-radius: 6px;
      font-family: sans-serif; font-size: 13px; font-weight: 700;
      cursor: pointer; transition: opacity 0.2s;
    }

    .form-submit:hover { opacity: 0.85; }

    .form-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 1.5rem;
      margin-bottom: 2rem;
    }

    .form-title { font-size: 15px; color: var(--gold); font-family: sans-serif; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
      .sidebar { display: none; }
      .nav-links { display: none; }
      .form-grid { grid-template-columns: 1fr; }
      table th:nth-child(3), table td:nth-child(3),
      table th:nth-child(4), table td:nth-child(4) { display: none; }
    }
  </style>
</head>
<body>

<!-- ===== NAVIGATIEBALK ===== -->
<nav>
  <div>
    <div class="logo-title">Aurora</div>
    <div class="logo-sub">Theater</div>
  </div>
</nav>

<div class="layout">

  <!-- ===== ZIJBALK ===== -->
  <aside class="sidebar">
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

    <!-- Database simulatieknop (test Gherkin offline-scenario's) -->
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

      <!-- Statistieken bovenaan -->
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

      <!-- Recente meldingen tabel op dashboard -->
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

      <!-- Formulier: nieuwe medewerker toevoegen -->
      <div class="form-card">
        <div class="form-title"><i class="ti ti-user-plus"></i> Nieuwe medewerker toevoegen</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Voornaam</label>
            <input type="text" id="mwVoornaam" placeholder="Voornaam">
          </div>
          <div class="form-group">
            <label>Achternaam</label>
            <input type="text" id="mwAchternaam" placeholder="Achternaam">
          </div>
          <div class="form-group">
            <label>Rol / Functie</label>
            <input type="text" id="mwRol" placeholder="bijv. Acteur, Technicus...">
          </div>
          <div class="form-group">
            <label>Status</label>
            <select id="mwStatus">
              <option value="actief">Actief</option>
              <option value="verlof">Verlof</option>
              <option value="inactief">Inactief</option>
            </select>
          </div>
        </div>
        <button class="form-submit" onclick="voegMedewerkerToe()">
          <i class="ti ti-plus"></i> Toevoegen
        </button>
      </div>

      <!-- Toolbar: zoeken + filter -->
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

      <!-- Foutmelding database offline -->
      <div class="error-banner" id="errMw">
        <i class="ti ti-database-off"></i>
        <div>
          <strong style="display:block;margin-bottom:3px">Geen verbinding mogelijk</strong>
          Er kan momenteel geen verbinding worden gemaakt met de database. Probeer het later opnieuw.
        </div>
      </div>

      <!-- Medewerker kaartengrid -->
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

      <!-- Toolbar -->
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

      <!-- Foutmelding database offline -->
      <div class="error-banner" id="errMl">
        <i class="ti ti-database-off"></i>
        <div>
          <strong style="display:block;margin-bottom:3px">Meldingen kunnen momenteel niet geladen worden</strong>
          Er is geen verbinding met de database. Probeer het later opnieuw.
        </div>
      </div>

      <!-- Meldingen tabel -->
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
         SECTIE: VOORSTELLINGEN
    ============================= -->
    <div id="s-voorstellingen" class="section">
      <h1 class="page-title">Voorstellingen</h1>
      <p class="page-sub">Beheer alle geplande en afgelopen voorstellingen.</p>

      <!-- Formulier: nieuwe voorstelling toevoegen -->
      <div class="form-card">
        <div class="form-title"><i class="ti ti-theater"></i> Nieuwe voorstelling toevoegen</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Naam voorstelling</label>
            <input type="text" id="vsNaam" placeholder="bijv. Hamlet">
          </div>
          <div class="form-group">
            <label>Datum</label>
            <input type="date" id="vsDatum">
          </div>
          <div class="form-group">
            <label>Tijd</label>
            <input type="time" id="vsTijd">
          </div>
          <div class="form-group">
            <label>Status</label>
            <select id="vsStatus">
              <option value="actief">Actief</option>
              <option value="uitverkocht">Uitverkocht</option>
              <option value="geannuleerd">Geannuleerd</option>
            </select>
          </div>
          <div class="form-group full">
            <label>Beschrijving</label>
            <textarea id="vsBeschrijving" placeholder="Korte omschrijving..."></textarea>
          </div>
        </div>
        <button class="form-submit" onclick="voegVoorstellingToe()">
          <i class="ti ti-plus"></i> Toevoegen
        </button>
      </div>

      <!-- Foutmelding database offline -->
      <div class="error-banner" id="errVs">
        <i class="ti ti-database-off"></i>
        <div>
          <strong style="display:block;margin-bottom:3px">Geen verbinding mogelijk</strong>
          Voorstellingen kunnen momenteel niet geladen worden. Probeer het later opnieuw.
        </div>
      </div>

      <div class="tabel-wrap">
        <table id="vsTabel">
          <thead>
            <tr>
              <th>Naam</th>
              <th>Datum</th>
              <th>Tijd</th>
              <th>Beschrijving</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="vsBody"></tbody>
        </table>
        <div class="empty" id="vsEmpty" style="display:none">
          <i class="ti ti-theater"></i>Geen voorstellingen gevonden.
        </div>
      </div>
    </div>

    <!-- ============================
         SECTIE: TICKETS
    ============================= -->
    <div id="s-tickets" class="section">
      <h1 class="page-title">Tickets</h1>
      <p class="page-sub">Overzicht van alle verkochte en gereserveerde tickets.</p>

      <!-- Foutmelding database offline -->
      <div class="error-banner" id="errTk">
        <i class="ti ti-database-off"></i>
        <div>
          <strong style="display:block;margin-bottom:3px">Geen verbinding mogelijk</strong>
          Tickets kunnen momenteel niet geladen worden. Probeer het later opnieuw.
        </div>
      </div>

      <div class="toolbar">
        <div class="toolbar-left">
          <div class="search">
            <i class="ti ti-search"></i>
            <input type="text" id="tkZoek" placeholder="Zoek op naam of voorstelling..." oninput="filterTickets()">
          </div>
        </div>
        <span class="count-text" id="tkCount">6 tickets</span>
      </div>

      <div class="tabel-wrap">
        <table id="tkTabel">
          <thead>
            <tr>
              <th>Naam klant</th>
              <th>Voorstelling</th>
              <th>Datum</th>
              <th>Stoel</th>
              <th>Prijs</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody id="tkBody"></tbody>
        </table>
        <div class="empty" id="tkEmpty" style="display:none">
          <i class="ti ti-ticket"></i>Geen tickets gevonden.
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
</div><!-- einde layout -->

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
     DATA – in productie via PHP/API uit de database
  =================================================== */

  /* Medewerkers */
  const medewerkers = [
    { id:1, naam:"Sophie Martens",  rol:"Theaterregisseur",  init:"SM", av:"av-gold",   status:"actief"   },
    { id:2, naam:"Lars de Vries",   rol:"Acteur",            init:"LV", av:"av-purple", status:"actief"   },
    { id:3, naam:"Noor Jansen",     rol:"Kostuumontwerper",  init:"NJ", av:"av-teal",   status:"verlof"   },
    { id:4, naam:"Daan Bakker",     rol:"Lichtontwerper",    init:"DB", av:"av-blue",   status:"actief"   },
    { id:5, naam:"Emma van Loon",   rol:"Actrice",           init:"EV", av:"av-gold",   status:"actief"   },
    { id:6, naam:"Thijs Smit",      rol:"Geluidstechnicus",  init:"TS", av:"av-purple", status:"inactief" },
    { id:7, naam:"Lisa Hoekstra",   rol:"Toneelmeester",     init:"LH", av:"av-teal",   status:"actief"   },
    { id:8, naam:"Roel Peters",     rol:"Kaartverkoper",     init:"RP", av:"av-blue",   status:"verlof"   },
  ];

  /* Meldingen */
  const meldingen = [
    { id:1, gebruiker:"Sophie Martens", onderwerp:"Technische storing lichten",  bericht:"Tijdens de repetitie van gisteren werkten de spotlights op scène 3 niet. Graag zo snel mogelijk repareren.", datum:"03-06-2025", status:"nieuw"    },
    { id:2, gebruiker:"Lars de Vries",  onderwerp:"Roostering aanpassing",       bericht:"Ik ben helaas verhinderd op 15 juni. Kan iemand mijn dienst overnemen?",                                      datum:"01-06-2025", status:"open"     },
    { id:3, gebruiker:"Noor Jansen",    onderwerp:"Materiaal bestelling",         bericht:"De stoffen voor de nieuwe kostuums zijn nog niet binnen. Kunnen we de leverancier contacteren?",               datum:"29-05-2025", status:"open"     },
    { id:4, gebruiker:"Daan Bakker",    onderwerp:"Software update nodig",        bericht:"Het lichtbeheersysteem vraagt om een update. Dit vereist een korte downtime van ca. 30 minuten.",              datum:"28-05-2025", status:"gesloten" },
    { id:5, gebruiker:"Emma van Loon",  onderwerp:"Aanvraag extra repetitie",     bericht:"Graag een extra repetitieruimte reserveren voor de cast van Hamlet op 20 juni.",                               datum:"25-05-2025", status:"nieuw"    },
  ];

  /* Voorstellingen */
  const voorstellingen = [
    { id:1, naam:"Hamlet",           datum:"2025-06-15", tijd:"20:00", beschrijving:"De klassieke tragedie van Shakespeare.",             status:"actief"     },
    { id:2, naam:"De Getemde Feeks", datum:"2025-06-22", tijd:"19:30", beschrijving:"Komische komedie vol verrassingen.",                  status:"uitverkocht"},
    { id:3, naam:"Macbeth",          datum:"2025-07-01", tijd:"20:30", beschrijving:"Een donker verhaal over macht en verraad.",           status:"actief"     },
    { id:4, naam:"Midzomernacht",    datum:"2025-07-10", tijd:"21:00", beschrijving:"Een magische zomeravond vol illusies.",               status:"actief"     },
    { id:5, naam:"De Storm",         datum:"2025-05-01", tijd:"19:00", beschrijving:"Al afgelopen. Geweldige ontvangst door het publiek.", status:"geannuleerd"},
  ];

  /* Tickets */
  const tickets = [
    { id:1, klant:"Jan de Boer",      voorstelling:"Hamlet",           datum:"2025-06-15", stoel:"A12", prijs:"€22,50", status:"actief" },
    { id:2, klant:"Maria Visser",     voorstelling:"Hamlet",           datum:"2025-06-15", stoel:"B07", prijs:"€22,50", status:"actief" },
    { id:3, klant:"Pieter Willems",   voorstelling:"De Getemde Feeks", datum:"2025-06-22", stoel:"C03", prijs:"€18,00", status:"actief" },
    { id:4, klant:"Anna Kok",         voorstelling:"Macbeth",          datum:"2025-07-01", stoel:"D15", prijs:"€25,00", status:"actief" },
    { id:5, klant:"Sander Hendriks",  voorstelling:"Midzomernacht",    datum:"2025-07-10", stoel:"A01", prijs:"€20,00", status:"actief" },
    { id:6, klant:"Fatima El Amrani", voorstelling:"De Storm",         datum:"2025-05-01", stoel:"B11", prijs:"€18,00", status:"gesloten"},
  ];

  /* Statussen */
  let dbOnline = true;
  let activeMelding = null;
  const avKleuren = ["av-gold","av-purple","av-teal","av-blue"];

  /* ===================================================
     NAVIGATIE: wissel tussen secties
  =================================================== */
  function toonSectie(naam, el) {
    /* Verberg alle secties */
    document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
    /* Toon gewenste sectie */
    document.getElementById('s-' + naam).classList.add('active');

    /* Sidebar actief item */
    document.querySelectorAll('.sidebar-item').forEach(s => s.classList.remove('active'));
    if (el) el.classList.add('active');

    /* Render de juiste inhoud */
    if (naam === 'medewerkers')    renderMedewerkers();
    if (naam === 'meldingen')      renderMeldingen();
  }

  /* ===================================================
     DATABASE SIMULATIE (Gherkin testscenario's)
  =================================================== */
  function toggleDB() {
    dbOnline = !dbOnline;
    document.getElementById('dbDot').classList.toggle('offline', !dbOnline);
    document.getElementById('dbLabel').textContent = dbOnline ? 'Database: online' : 'Database: offline';
    /* Herrender actieve sectie */
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

  /* Nieuwe medewerker toevoegen via formulier */
  function voegMedewerkerToe() {
    const voor = document.getElementById('mwVoornaam').value.trim();
    const acht = document.getElementById('mwAchternaam').value.trim();
    const rol  = document.getElementById('mwRol').value.trim();
    const stat = document.getElementById('mwStatus').value;

    if (!voor || !acht || !rol) { alert('Vul alle verplichte velden in.'); return; }

    const init = (voor[0] + acht[0]).toUpperCase();
    const av   = avKleuren[medewerkers.length % avKleuren.length];

    medewerkers.push({ id: Date.now(), naam: voor + ' ' + acht, rol, init, av, status: stat });

    /* Reset formulier */
    document.getElementById('mwVoornaam').value = '';
    document.getElementById('mwAchternaam').value = '';
    document.getElementById('mwRol').value = '';

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

    /* Badge bijwerken */
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

  function sluitModal(e) { if (e.target.id === 'modalBg') sluitModalDirect(); }
  function sluitModalDirect() { document.getElementById('modalBg').classList.remove('visible'); activeMelding = null; }

  function sluitMelding() {
    if (!activeMelding) return;
    const m = meldingen.find(x => x.id === activeMelding.id);
    if (m) m.status = 'gesloten';
    sluitModalDirect();
    renderMeldingen();
  }

  /* ===================================================
     VOORSTELLINGEN
  =================================================== */
  function renderVoorstellingen() {
    const body  = document.getElementById('vsBody');
    const tabel = document.getElementById('vsTabel');
    const err   = document.getElementById('errVs');
    const leeg  = document.getElementById('vsEmpty');

    err.classList.toggle('visible', !dbOnline);
    tabel.style.display = dbOnline ? 'table' : 'none';
    if (!dbOnline) return;

    leeg.style.display  = voorstellingen.length === 0 ? 'block' : 'none';
    tabel.style.display = voorstellingen.length > 0   ? 'table' : 'none';

    body.innerHTML = voorstellingen.map(v => `
      <tr>
        <td class="td-bold">${v.naam}</td>
        <td>${v.datum}</td>
        <td>${v.tijd}</td>
        <td class="td-preview">${v.beschrijving}</td>
        <td><span class="sbadge s-${v.status}">${cap(v.status)}</span></td>
        <td style="text-align:right">
          <i class="ti ti-trash" style="color:#888;font-size:15px;cursor:pointer" onclick="verwijderVoorstelling(${v.id})"></i>
        </td>
      </tr>
    `).join('');
  }

  function voegVoorstellingToe() {
    const naam  = document.getElementById('vsNaam').value.trim();
    const datum = document.getElementById('vsDatum').value;
    const tijd  = document.getElementById('vsTijd').value;
    const stat  = document.getElementById('vsStatus').value;
    const bes   = document.getElementById('vsBeschrijving').value.trim();

    if (!naam || !datum || !tijd) { alert('Vul naam, datum en tijd in.'); return; }

    voorstellingen.push({ id: Date.now(), naam, datum, tijd, beschrijving: bes || '—', status: stat });

    document.getElementById('vsNaam').value = '';
    document.getElementById('vsDatum').value = '';
    document.getElementById('vsTijd').value = '';
    document.getElementById('vsBeschrijving').value = '';

    renderVoorstellingen();
  }

  function verwijderVoorstelling(id) {
    if (!confirm('Weet je zeker dat je deze voorstelling wilt verwijderen?')) return;
    const i = voorstellingen.findIndex(v => v.id === id);
    if (i > -1) voorstellingen.splice(i, 1);
    renderVoorstellingen();
  }

  /* ===================================================
     TICKETS
  =================================================== */
  function renderTickets(lijst) {
    const body  = document.getElementById('tkBody');
    const tabel = document.getElementById('tkTabel');
    const err   = document.getElementById('errTk');
    const leeg  = document.getElementById('tkEmpty');
    const cnt   = document.getElementById('tkCount');

    err.classList.toggle('visible', !dbOnline);
    tabel.style.display = dbOnline ? 'table' : 'none';
    if (!dbOnline) { cnt.textContent = ''; return; }

    const data = lijst !== undefined ? lijst : tickets;
    cnt.textContent = data.length + ' ticket' + (data.length !== 1 ? 's' : '');
    leeg.style.display  = data.length === 0 ? 'block' : 'none';
    tabel.style.display = data.length > 0   ? 'table' : 'none';

    body.innerHTML = data.map(t => `
      <tr>
        <td class="td-bold">${t.klant}</td>
        <td>${t.voorstelling}</td>
        <td class="td-muted">${t.datum}</td>
        <td>${t.stoel}</td>
        <td>${t.prijs}</td>
        <td><span class="sbadge s-${t.status}">${cap(t.status)}</span></td>
      </tr>
    `).join('');
  }

  function filterTickets() {
    const z = document.getElementById('tkZoek').value.toLowerCase();
    renderTickets(tickets.filter(t =>
      t.klant.toLowerCase().includes(z) || t.voorstelling.toLowerCase().includes(z)
    ));
  }

  /* ===================================================
     HULPFUNCTIE
  =================================================== */
  function cap(s) { return s.charAt(0).toUpperCase() + s.slice(1); }

  /* ===================================================
     INITIALISATIE
  =================================================== */
  renderMedewerkers();
</script>
</body>
</html>