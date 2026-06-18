<?php

require_once __DIR__ . '/../models/Show.php';

class ShowController {
    public function index() {
        $voorstellingen = Show::getActiveShows();
        require_once __DIR__ . '/../views/shows.view.php';
    }
}
