<?php

require_once __DIR__ . '/../models/Show.php';

class ShowController {

    public function index() {
        $voorstellingen = Show::getActiveShows();
        require_once __DIR__ . '/../views/shows.view.php';
    }

    public function create() {
        // Toon het formulier
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
        } else {
            require_once __DIR__ . '/../views/voorstelling-toevoegen.view.php';
        }
    }

    private function store() {
        $naam         = trim($_POST['naam'] ?? '');
        $beschrijving = trim($_POST['beschrijving'] ?? '');
        $datum        = $_POST['datum'] ?? '';
        $tijd         = $_POST['tijd'] ?? '';
        $maxTickets   = (int)($_POST['max_tickets'] ?? 0);
        $beschikbaarheid = $_POST['beschikbaarheid'] ?? 'Ingepland';

        // Eenvoudige validatie
        if (empty($naam) || empty($datum) || empty($tijd) || $maxTickets <= 0) {
            $_SESSION['flash_error'] = 'Vul alle verplichte velden correct in.';
            require_once __DIR__ . '/../views/voorstelling-toevoegen.view.php';
            return;
        }

        try {
            $success = Show::create([
                'naam'            => $naam,
                'beschrijving'    => $beschrijving,
                'datum'           => $datum,
                'tijd'            => $tijd,
                'max_tickets'     => $maxTickets,
                'beschikbaarheid' => $beschikbaarheid,
            ]);
        } catch (RuntimeException $e) {
            // Scenario 4: Database verbindingsfout (Unhappy Flow)
            // Voorstelling NIET opgeslagen – toon foutmelding met "Probeer opnieuw" knop
            $_SESSION['flash_db_error'] = 'Systeemfout: Kan geen verbinding maken met de database';
            require_once __DIR__ . '/../views/voorstelling-toevoegen.view.php';
            return;
        }

        if ($success) {
            $_SESSION['flash_success'] = 'Voorstelling "' . htmlspecialchars($naam) . '" is succesvol aangemaakt!';
        } else {
            $_SESSION['flash_error'] = 'Er is een fout opgetreden bij het opslaan. Probeer het opnieuw.';
        }

        header('Location: ../overzicht voorstellingen/overzichtvoorstellingen.php');
        exit;
    }

    public function edit() {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            header('Location: ../overzicht voorstellingen/overzichtvoorstellingen.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id);
        } else {
            $voorstelling = Show::getById($id);
            if (!$voorstelling) {
                $_SESSION['flash_error'] = 'Voorstelling niet gevonden.';
                header('Location: ../overzicht voorstellingen/overzichtvoorstellingen.php');
                exit;
            }
            require_once __DIR__ . '/../views/voorstelling-wijzigen.view.php';
        }
    }

    private function update($id) {
        $naam         = trim($_POST['naam'] ?? '');
        $beschrijving = trim($_POST['beschrijving'] ?? '');
        $datum        = $_POST['datum'] ?? '';
        $tijd         = $_POST['tijd'] ?? '';
        $maxTickets   = (int)($_POST['max_tickets'] ?? 0);
        $beschikbaarheid = $_POST['beschikbaarheid'] ?? 'Ingepland';

        if (empty($naam) || empty($datum) || empty($tijd) || $maxTickets <= 0) {
            $_SESSION['flash_error'] = 'Vul alle verplichte velden correct in.';
            $voorstelling = Show::getById($id);
            require_once __DIR__ . '/../views/voorstelling-wijzigen.view.php';
            return;
        }

        try {
            $success = Show::update($id, [
                'naam'            => $naam,
                'beschrijving'    => $beschrijving,
                'datum'           => $datum,
                'tijd'            => $tijd,
                'max_tickets'     => $maxTickets,
                'beschikbaarheid' => $beschikbaarheid,
            ]);
        } catch (RuntimeException $e) {
            $_SESSION['flash_db_error'] = 'Systeemfout: Kan geen verbinding maken met de database';
            $voorstelling = Show::getById($id);
            require_once __DIR__ . '/../views/voorstelling-wijzigen.view.php';
            return;
        }

        if ($success) {
            $_SESSION['flash_success'] = 'Voorstelling "' . htmlspecialchars($naam) . '" is succesvol gewijzigd!';
        } else {
            $_SESSION['flash_error'] = 'Er is een fout opgetreden bij het opslaan. Probeer het opnieuw.';
        }

        header('Location: ../overzicht voorstellingen/overzichtvoorstellingen.php');
        exit;
    }

    public function delete() {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            header('Location: ../overzicht voorstellingen/overzichtvoorstellingen.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Bevestigd: verwijder de voorstelling
            $actie = $_POST['actie'] ?? '';

            if ($actie === 'annuleren') {
                // Scenario: Verwijderen geannuleerd
                $_SESSION['flash_info'] = 'Verwijderen geannuleerd.';
                header('Location: ../overzicht voorstellingen/overzichtvoorstellingen.php');
                exit;
            }

            try {
                $success = Show::delete($id);
            } catch (RuntimeException $e) {
                $_SESSION['flash_error'] = 'Systeemfout: Kan geen verbinding maken met de database.';
                header('Location: ../overzicht voorstellingen/overzichtvoorstellingen.php');
                exit;
            }

            if ($success) {
                $_SESSION['flash_success'] = 'Voorstelling succesvol verwijderd';
            } else {
                $_SESSION['flash_error'] = 'Voorstelling kon niet worden verwijderd. Probeer het opnieuw.';
            }

            header('Location: ../overzicht voorstellingen/overzichtvoorstellingen.php');
            exit;

        } else {
            // GET: toon bevestigingspagina
            $voorstelling = Show::getById($id);

            if (!$voorstelling) {
                $_SESSION['flash_error'] = 'Voorstelling niet gevonden.';
                header('Location: ../overzicht voorstellingen/overzichtvoorstellingen.php');
                exit;
            }

            require_once __DIR__ . '/../views/voorstelling-verwijderen.view.php';
        }
    }
}
