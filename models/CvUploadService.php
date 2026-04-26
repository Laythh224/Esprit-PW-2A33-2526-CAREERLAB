<?php

class CvUploadService
{
    private string $uploadDir;
    private string $publicBasePath;

    public function __construct(?string $uploadDir = null, string $publicBasePath = 'Views/assets/uploads/cv')
    {
        $this->uploadDir = $uploadDir ?? dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'cv';
        $this->publicBasePath = trim($publicBasePath, '/');
    }

    public function uploadPdf(array $file): array
    {
        if ((int) ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Le CV est obligatoire et doit être au format PDF.');
        }

        $name = (string) ($file['name'] ?? '');
        if (strtolower(pathinfo($name, PATHINFO_EXTENSION)) !== 'pdf') {
            throw new RuntimeException('Le CV doit être un fichier PDF.');
        }

        if (!is_dir($this->uploadDir) && !mkdir($this->uploadDir, 0755, true)) {
            throw new RuntimeException("Impossible de preparer le dossier d'upload.");
        }

        $storedFileName = 'cv_' . bin2hex(random_bytes(8)) . '.pdf';
        $storedDiskPath = $this->uploadDir . DIRECTORY_SEPARATOR . $storedFileName;

        if (!move_uploaded_file((string) ($file['tmp_name'] ?? ''), $storedDiskPath)) {
            throw new RuntimeException("Erreur lors de l'upload du CV.");
        }

        return [
            'diskPath' => $storedDiskPath,
            'publicPath' => $this->publicBasePath . '/' . $storedFileName,
        ];
    }

    public function deleteFile(?string $diskPath): void
    {
        if ($diskPath !== null && $diskPath !== '' && is_file($diskPath)) {
            unlink($diskPath);
        }
    }
}
