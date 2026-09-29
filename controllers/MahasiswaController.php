<?php

require_once __DIR__ . '/../data/landingPage/Mahasiswa.php';

class MahasiswaController
{
    public function index($params = [])
    {
        global $mahasiswa;

        require __DIR__ . '/../pages/landingPage/mahasiswa/index.php';
    }
}