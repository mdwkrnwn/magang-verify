
<?php

class MitraController
{
    public function index($params = [])
    {
        require __DIR__ . '/../data/landingPage/Mitra.php';

        require __DIR__ . '/../pages/landingPage/mitra/index.php';
    }
}
