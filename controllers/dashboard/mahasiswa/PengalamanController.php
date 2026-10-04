<?php

require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/Pengalaman.php';

class PengalamanController
{
    private Pengalaman $model;

    public function __construct() { $this->model = new Pengalaman(); }

    private function userId(): int {
        AuthMiddleware::handle('mahasiswa');
        $id = $_SESSION['user']['id'] ?? null;
        if (!is_numeric($id) || (int)$id < 1) { http_response_code(401); exit('Sesi pengguna tidak valid.'); }
        return (int)$id;
    }

    private function redirect(string $path): never { header('Location: '.url($path)); exit; }

    private function dataFromPost(): array
    {
        $data = [
            'jenis'=>trim($_POST['jenis'] ?? ''), 'posisi'=>trim($_POST['posisi'] ?? ''),
            'instansi'=>trim($_POST['instansi'] ?? ''), 'lokasi'=>trim($_POST['lokasi'] ?? ''),
            'deskripsi'=>trim($_POST['deskripsi'] ?? ''), 'tanggal_mulai'=>trim($_POST['tanggal_mulai'] ?? ''),
            'tanggal_selesai'=>trim($_POST['tanggal_selesai'] ?? ''),
        ];
        if (!in_array($data['jenis'], ['pekerjaan','organisasi','freelance','proyek','lainnya'], true)) { $data['jenis']='lainnya'; }
        if ($data['posisi']==='' || mb_strlen($data['posisi'])>200) throw new InvalidArgumentException('Posisi wajib diisi dan maksimal 200 karakter.');
        if ($data['instansi']==='' || mb_strlen($data['instansi'])>200) throw new InvalidArgumentException('Instansi wajib diisi dan maksimal 200 karakter.');
        foreach (['tanggal_mulai','tanggal_selesai'] as $f) {
            if ($data[$f] !== '') { $d=DateTime::createFromFormat('Y-m-d',$data[$f]); if (!$d || $d->format('Y-m-d')!==$data[$f]) throw new InvalidArgumentException('Tanggal tidak valid.'); }
        }
        if ($data['tanggal_mulai']!=='' && $data['tanggal_selesai']!=='' && $data['tanggal_selesai'] < $data['tanggal_mulai']) throw new InvalidArgumentException('Tanggal selesai tidak boleh mendahului tanggal mulai.');
        return $data;
    }

    public function index(): void { $id=$this->userId(); $pengalaman=$this->model->getAll($id); require __DIR__.'/../../../pages/dashboard/mahasiswa/pengalaman/index.php'; }

    public function create(): void { $this->userId(); $pengalaman=[]; $mode='create'; $item=null; require __DIR__.'/../../../pages/dashboard/mahasiswa/pengalaman/form.php'; }

    public function store(): void {
        $id=$this->userId();
        if ($_SERVER['REQUEST_METHOD']!=='POST' || !verifyCsrfToken()) { http_response_code(403); exit('Permintaan tidak valid.'); }
        try { $this->model->create($id,$this->dataFromPost()); $this->redirect('/dashboard/mahasiswa/pengalaman'); } catch (Throwable $e) { http_response_code(422); exit(e($e->getMessage())); }
    }

    public function edit($params=[]): void { $id=$this->userId(); $item=$this->model->getById((int)($params['id']??0),$id); if(!$item){http_response_code(404); exit('Pengalaman tidak ditemukan.');} $mode='edit'; require __DIR__.'/../../../pages/dashboard/mahasiswa/pengalaman/form.php'; }

    public function update($params=[]): void {
        $id=$this->userId();
        if ($_SERVER['REQUEST_METHOD']!=='POST' || !verifyCsrfToken()) { http_response_code(403); exit('Permintaan tidak valid.'); }
        try { $this->model->update((int)($params['id']??0),$id,$this->dataFromPost()); $this->redirect('/dashboard/mahasiswa/pengalaman'); } catch(Throwable $e){http_response_code(422); exit(e($e->getMessage()));}
    }

    public function delete($params=[]): void {
        $id=$this->userId();
        if ($_SERVER['REQUEST_METHOD']!=='POST' || !verifyCsrfToken()) { http_response_code(403); exit('Permintaan tidak valid.'); }
        $this->model->delete((int)($params['id']??0),$id); $this->redirect('/dashboard/mahasiswa/pengalaman');
    }
}
