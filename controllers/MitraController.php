<?php

require_once __DIR__ . '/../data/landingPage/Mitra.php';

class MitraController
{
    public function index($params = [])
    {
        global $mitra;

        require __DIR__ . '/../pages/landingPage/mitra/index.php';
    }
}