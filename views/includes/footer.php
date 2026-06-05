<?php
// Bepaal de prefix om terug te keren naar de root-directory
$root_prefix = '';
if (basename(dirname($_SERVER['SCRIPT_FILENAME'])) === 'overzicht voorstellingen') {
    $root_prefix = '../';
}
?>
<!-- ================= FOOTER ================= -->
<footer class="site-footer">

    <div class="footer-inner">

        <!-- Kolom 1: Branding -->
        <div class="footer-brand">
            <div class="footer-logo">
                <span class="footer-logo-main">AURORA</span>
                <span class="footer-logo-sub">Theater</span>
            </div>
            <p class="footer-tagline">
                Beleef theater op z'n mooist. Unieke voorstellingen in het hart van de stad.
            </p>
            <!-- Social media -->
            <div class="footer-socials">
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                <a href="#" aria-label="Twitter/X"><i class="fab fa-x-twitter"></i></a>
            </div>
        </div>

        <!-- Kolom 2: Navigatie -->
        <div class="footer-col">
            <h4 class="footer-col-title">Navigatie</h4>
            <ul>
                <li><a href="<?php echo $root_prefix; ?>homepaginamaken.php">Home</a></li>
                <li><a href="<?php echo $root_prefix; ?>overzicht voorstellingen/overzichtvoorstellingen.php">Voorstellingen</a></li>
                <li><a href="#">Tickets</a></li>
                <li><a href="#">Over Ons</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>

        <!-- Kolom 3: Informatie -->
        <div class="footer-col">
            <h4 class="footer-col-title">Informatie</h4>
            <ul>
                <li><a href="#">Veelgestelde Vragen</a></li>
                <li><a href="#">Algemene Voorwaarden</a></li>
                <li><a href="#">Privacybeleid</a></li>
                <li><a href="#">Toegankelijkheid</a></li>
            </ul>
        </div>

        <!-- Kolom 4: Contact -->
        <div class="footer-col">
            <h4 class="footer-col-title">Contact</h4>
            <ul class="footer-contact-list">
                <li><i class="fas fa-map-marker-alt"></i> Theaterplein 1, Amsterdam</li>
                <li><i class="fas fa-phone"></i> +31 (0)20 123 4567</li>
                <li><i class="fas fa-envelope"></i> info@auroratheater.nl</li>
                <li><i class="fas fa-clock"></i> Ma – Za: 10:00 – 21:00</li>
            </ul>
        </div>

    </div>

    <!-- Footer bottom balk -->
    <div class="footer-bottom">
        <p>&copy; 2025 Aurora Theater &mdash; Alle rechten voorbehouden.</p>
    </div>

</footer>

<!-- JavaScript voor Hamburger Menu -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const menuToggle = document.getElementById('mobile-menu-toggle');
        const navMenu    = document.getElementById('main-nav');
        const navActions = document.getElementById('nav-actions');

        if (menuToggle && navMenu) {
            menuToggle.addEventListener('click', () => {
                navMenu.classList.toggle('active');
                if (navActions) {
                    navActions.classList.toggle('active');
                }

                const icon = menuToggle.querySelector('i');
                if (icon) {
                    icon.className = navMenu.classList.contains('active')
                        ? 'fas fa-times'
                        : 'fas fa-bars';
                }
            });
        }
    });
</script>
