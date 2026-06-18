<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aurora Theater</title>

    <!-- Koppeling naar CSS bestand -->
    <link rel="stylesheet" href="style.css">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <!-- ================= HEADER ================= -->
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

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

                <a href="overzicht voorstellingen/overzichtvoorstellingen.php" class="cta-btn">
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
    <?php require_once __DIR__ . '/includes/footer.php'; ?>

</body>

</html>
