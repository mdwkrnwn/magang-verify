<?php

class ErrorTestController
{
    private function renderErrorPage(string $page): void
    {
        $errorPagePath = dirname(__DIR__) . '/pages/errors/' . $page . '.php';

        if (!file_exists($errorPagePath)) {
            http_response_code(404);
            exit('Error page tidak ditemukan.');
        }

        require $errorPagePath;
        exit;
    }

    public function test403()
    {
        $this->renderErrorPage('403');
    }

    public function test404()
    {
        $this->renderErrorPage('404');
    }

    public function test500()
    {
        $this->renderErrorPage('500');
    }

    public function testMitraInvalid()
    {
        $this->renderErrorPage('mitra-invalid');
    }

    public function testSessionExpired()
    {
        $this->renderErrorPage('session-expired');
    }
}