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
    <link rel="stylesheet" href="css/style.css">
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