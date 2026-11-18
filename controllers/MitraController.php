
<?php

class MitraController
{
    public function index($params = [])
    {
        require_once __DIR__ . '/../models/MitraPublik.php';

        $mitra = (new MitraPublik())->getAllForLanding();

        // Fallback ke data dummy bila belum ada mitra aktif di database.
        if (!$mitra) {
            require __DIR__ . '/../data/landingPage/Mitra.php';
        }

        require __DIR__ . '/../pages/landingPage/mitra/index.php';
    }
}
