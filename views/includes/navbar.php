<?php
// Bepaal de prefix om terug te keren naar de root-directory
$root_prefix = '';
if (basename(dirname($_SERVER['SCRIPT_FILENAME'])) === 'overzicht voorstellingen') {
    $root_prefix = '../';
}
?>
<!-- ================= HEADER / NAVBAR ================= -->
<header>

    <!-- Logo van Aurora Theater -->
    <a href="<?php echo $root_prefix; ?>homepaginamaken.php" style="text-decoration: none; color: inherit;">
        <div class="logo">
            <h1>AURORA</h1>
            <span>Theater</span>
        </div>
    </a>

    <!-- Navigatiemenu -->
    <nav>
        <ul>
            <li><a href="<?php echo $root_prefix; ?>homepaginamaken.php">Home</a></li>
            <li><a href="<?php echo $root_prefix; ?>overzicht voorstellingen/overzichtvoorstellingen.php">Voorstellingen</a></li>
            <li><a href="#">Tickets</a></li>
            <li><a href="#">Over Ons</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="<?php echo $root_prefix; ?>beheerdashboard.php" class="beheer-btn">Beheerdashboard</a></li>
        </ul>
    </nav>

    <!-- Login en registratie knoppen (Registreren is verwijderd) -->
    <div class="buttons">
        <a href="<?php echo $root_prefix; ?>inloggen.php" class="login-btn">Inloggen</a>
    </div>

    <!-- Hamburger Menu Toggle voor Mobiel -->
    <button class="menu-toggle" id="mobile-menu-toggle" aria-label="Open navigatiemenu">
        <i class="fas fa-bars"></i>
    </button>

</header>
