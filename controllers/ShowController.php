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
            $this->updateShow($id);
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

    private function updateShow(int $id) {
        $naam         = trim($_POST['naam'] ?? '');
        $beschrijving = trim($_POST['beschrijving'] ?? '');
        $datum        = $_POST['datum'] ?? '';
        $tijd         = $_POST['tijd'] ?? '';
        $maxTickets   = (int)($_POST['max_tickets'] ?? 0);
        $beschikbaarheid = $_POST['beschikbaarheid'] ?? 'Ingepland';

        // Validatie: verplichte velden
        if (empty($naam) || empty($datum) || empty($tijd) || $maxTickets <= 0) {
            $_SESSION['flash_error'] = 'Niet alle verplichte velden zijn ingevuld';
            $voorstelling = [
                'Id'               => $id,
                'Naam'             => $naam,
                'Beschrijving'     => $beschrijving,
                'Datum'            => $datum,
                'Tijd'             => $tijd,
                'MaxAantalTickets' => $maxTickets,
                'Beschikbaarheid'  => $beschikbaarheid,
            ];
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
            $voorstelling = [
                'Id'               => $id,
                'Naam'             => $naam,
                'Beschrijving'     => $beschrijving,
                'Datum'            => $datum,
                'Tijd'             => $tijd,
                'MaxAantalTickets' => $maxTickets,
                'Beschikbaarheid'  => $beschikbaarheid,
            ];
            require_once __DIR__ . '/../views/voorstelling-wijzigen.view.php';
            return;
        }

        if ($success) {
            $_SESSION['flash_success'] = 'Voorstelling succesvol gewijzigd';
        } else {
            $_SESSION['flash_error'] = 'Er is een fout opgetreden bij het opslaan. Probeer het opnieuw.';
        }

        header('Location: ../overzicht voorstellingen/overzichtvoorstellingen.php');
        exit;
    }
}
