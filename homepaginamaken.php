<?php
// Start eventueel een sessie voor loginfunctionaliteit
session_start();
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aurora Theater</title>

    <!-- Koppeling naar CSS bestand -->
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- ================= HEADER ================= -->
    <header>

        <!-- Logo van Aurora Theater -->
        <div class="logo">
            <h1>AURORA</h1>
            <span>Theater</span>
        </div>

        <!-- Navigatiemenu -->
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Voorstellingen</a></li>
                <li><a href="#">Tickets</a></li>
                <li><a href="#">Over Ons</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>

          <!-- Login en registratie knoppen -->
        <div class="buttons">
            <a href="#" class="login-btn">Inloggen</a>
            <a href="#" class="register-btn">Registreren</a>
        </div>

        </header>

    <!-- ================= HERO SECTION ================= -->
    <section class="hero">

        <!-- Overlay maakt tekst beter leesbaar -->
        <div class="overlay">

            <div class="hero-content">

                <h2>Beleef theater <br> op z'n mooist</h2>

                <p>
                    Aurora Theater brengt verhalen tot leven.
                    Ontdek onze indrukwekkende voorstellingen
                    en reserveer jouw tickets vandaag nog.
                </p>

                <a href="#" class="cta-btn">
                    Bekijk Voorstellingen
                </a>

            </div>

        </div>

    </section>

    <!-- ================= VOORSTELLINGEN ================= -->
    <section class="shows">

        <div class="section-title">
            <h2>Aankomende Voorstellingen</h2>
        </div>

        <div class="show-container">

            <!-- Voorstelling 1 -->
            <div class="show-card">

                <img src="images/lesmiserables.jpg" alt="Les Miserables">

                <div class="show-info">
                    <h3>Les Misérables</h3>

                    <p>12 Juni 2025 - 20:00</p>

                    <p>Grote Zaal</p>

                    <h4>Vanaf €29,50</h4>

                    <a href="#">Meer Info</a>
                </div>

            </div>

             <!-- Voorstelling 2 -->
            <div class="show-card">

                <img src="images/lionking.jpg" alt="Lion King">

                <div class="show-info">

                    <h3>The Lion King</h3>

                    <p>14 Juni 2025 - 19:30</p>

                    <p>Kleine Zaal</p>

                    <h4>Vanaf €34,50</h4>

                    <a href="#">Meer Info</a>

                </div>

            </div>

            <!-- Voorstelling 3 -->
            <div class="show-card">

                <img src="images/hamlet.jpg" alt="Hamlet">

                <div class="show-info">

                    <h3>Hamlet</h3>

                    <p>18 Juni 2025 - 20:15</p>

                    <p>Kleine Zaal</p>

                    <h4>Vanaf €22,50</h4>

                    <a href="#">Meer Info</a>

                </div>

            </div>

        </div>

    </section>

     <!-- ================= USP SECTION ================= -->
    <section class="usp">

        <div class="usp-box">
            <h3>Unieke Voorstellingen</h3>
            <p>Van musicals tot toneel en cabaret.</p>
        </div>

        <div class="usp-box">
            <h3>Toplocatie</h3>
            <p>Prachtig theater in het hart van de stad.</p>
        </div>

        <div class="usp-box">
            <h3>Eenvoudig Reserveren</h3>
            <p>Snel en veilig tickets reserveren.</p>
        </div>

        <div class="usp-box">
            <h3>Klantenservice</h3>
            <p>Wij staan voor je klaar.</p>
        </div>

    </section>

      <!-- ================= FOOTER ================= -->
    <footer>

        <div class="footer-logo">
            <h2>AURORA</h2>
        </div>

        <div class="footer-links">

            <a href="#">Over Ons</a>
            <a href="#">Contact</a>
            <a href="#">Veelgestelde Vragen</a>
            <a href="#">Algemene Voorwaarden</a>

        </div>

        <p>&copy; 2025 Aurora Theater</p>

    </footer>

</body>

</html>