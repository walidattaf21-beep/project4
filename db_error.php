<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technische Storing - Aurora Theater</title>
    <!-- Koppeling naar CSS bestand -->
    <link rel="stylesheet" href="style.css">
</head>

<body class="db-error-body">

    <div class="db-error-container">
        <div class="db-error-card">
            
            <!-- Waarschuwings-icoon (Theatrale spotlight-storing) -->
            <div class="stage-light-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <!-- Beugel/Ophangsysteem -->
                    <path d="M50 10 L50 22 M35 32 C35 20, 65 20, 65 32" stroke="#d4a75d" stroke-width="3" />
                    <!-- Spotlight behuizing -->
                    <rect x="38" y="32" width="24" height="24" rx="4" fill="#0f0818" stroke="#d4a75d" stroke-width="2.5" />
                    <!-- Lens / Lichtopening -->
                    <path d="M32 56 L68 56 L72 63 L28 63 Z" fill="rgba(212, 167, 93, 0.4)" stroke="#d4a75d" stroke-width="2.5" />
                    <!-- Interne lichtbron -->
                    <circle cx="50" cy="44" r="3" fill="#ffe8c2" stroke="#ffe8c2" />
                    <!-- Lichtstralen -->
                    <line x1="35" y1="70" x2="20" y2="90" stroke="#d4a75d" stroke-width="2.5" stroke-dasharray="3 3" />
                    <line x1="50" y1="70" x2="50" y2="93" stroke="#d4a75d" stroke-width="2.5" stroke-dasharray="3 3" />
                    <line x1="65" y1="70" x2="80" y2="90" stroke="#d4a75d" stroke-width="2.5" stroke-dasharray="3 3" />
                </svg>
            </div>
            
            <h2>Het licht is gedimd...</h2>
            
            <p class="error-intro">
                Onze excuses voor het ongemak. De systemen van het Aurora Theater ondervinden momenteel een tijdelijke storing. 
                We kunnen geen stabiele verbinding maken met onze database.
            </p>
            
            <p class="error-action-text">
                Onze technici zijn op de hoogte en werken eraan om het doek snel weer te laten stijgen. 
                Probeer de pagina over enkele ogenblikken opnieuw te laden.
            </p>

            <div class="db-error-actions">
                <button onclick="window.location.reload();" class="retry-btn">
                    Probeer Opnieuw
                </button>
            </div>

            <?php if (isset($db_error_message) && !empty($db_error_message)): ?>
                <!-- Debug details voor ontwikkelaars/beheerders -->
                <details class="debug-details">
                    <summary>Toon foutdetails (voor beheerders)</summary>
                    <div class="debug-content">
                        <code><?php echo htmlspecialchars($db_error_message); ?></code>
                    </div>
                </details>
            <?php endif; ?>
            
        </div>
        
        <div class="db-error-footer">
            <p>&copy; 2025 Aurora Theater - Technische Dienst</p>
        </div>
    </div>

</body>
</html>
