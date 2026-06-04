<?php
// Aurora Theater – Beheerdashboard
// Verbind hier je database logica
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
 
    <!-- Database simulatieknop -->
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
 
const meldingen = [
  { id:1, gebruiker:"Sophie Martens", onderwerp:"Technische storing lichten",  bericht:"Tijdens de repetitie van gisteren werkten de spotlights op scène 3 niet. Graag zo snel mogelijk repareren.", datum:"03-06-2025", status:"nieuw"    },
  { id:2, gebruiker:"Lars de Vries",  onderwerp:"Roostering aanpassing",       bericht:"Ik ben helaas verhinderd op 15 juni. Kan iemand mijn dienst overnemen?",                                      datum:"01-06-2025", status:"open"     },
  { id:3, gebruiker:"Noor Jansen",    onderwerp:"Materiaal bestelling",         bericht:"De stoffen voor de nieuwe kostuums zijn nog niet binnen. Kunnen we de leverancier contacteren?",               datum:"29-05-2025", status:"open"     },
  { id:4, gebruiker:"Daan Bakker",    onderwerp:"Software update nodig",        bericht:"Het lichtbeheersysteem vraagt om een update. Dit vereist een korte downtime van ca. 30 minuten.",              datum:"28-05-2025", status:"gesloten" },
  { id:5, gebruiker:"Emma van Loon",  onderwerp:"Aanvraag extra repetitie",     bericht:"Graag een extra repetitieruimte reserveren voor de cast van Hamlet op 20 juni.",                               datum:"25-05-2025", status:"nieuw"    },
];
 
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
  if (el) el.classList.add('active');
  if (naam === 'medewerkers') renderMedewerkers();
  if (naam === 'meldingen')   renderMeldingen();
  sluitSidebar(); // sidebar sluiten op mobiel na klik
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