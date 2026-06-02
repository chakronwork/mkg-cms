<?php

declare(strict_types=1);

final class MediaController extends Controller
{
    private MediaModel $media;

    public function __construct()
    {
        parent::__construct();
        Auth::requireAdmin();
        $this->media = new MediaModel();
    }

    public function index(): void
    {
        $this->view('admin/media/index', [
            'title' => 'คลังสื่อ',
            'mediaItems' => $this->media->all(),
            'errors' => [],
            'csrfToken' => Csrf::generate(),
        ]);
    }

    public function upload(): void
    {
        $this->verifyCsrf();
        $errors = $this->handleUpload();
        if ($errors === []) {
            $this->redirect(admin_url('media'));
        }

        $this->view('admin/media/index', [
            'title' => 'คลังสื่อ',
            'mediaItems' => $this->media->all(),
            'errors' => $errors,
            'csrfToken' => Csrf::generate(),
        ]);
    }

    public function alt(string $id): void
    {
        $this->verifyCsrf();
        $this->media->updateAlt((int) $id, trim((string) ($_POST['alt_text'] ?? '')));
        $this->redirect(admin_url('media'));
    }

    public function delete(string $id): void
    {
        $this->verifyCsrf();
        $this->media->softDelete((int) $id);
        $this->redirect(admin_url('media'));
    }

    private function handleUpload(): array
    {
        if (empty($_FILES['file']) || !is_array($_FILES['file'])) {
            return ['Please choose an image file.'];
        }

        $file = $_FILES['file'];
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return ['Upload failed. Please try again.'];
        }

        if ((int) $file['size'] > (int) app_config('max_upload_bytes')) {
            return ['File size must not exceed 5 MB.'];
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file((string) $file['tmp_name']) ?: '';
        $allowed = app_config('allowed_upload_mimes', []);
        if (!isset($allowed[$mime])) {
            return ['Only JPEG, PNG, GIF, and WebP images are allowed.'];
        }

        $year = date('Y');
        $month = date('m');
        $relativeDir = $year . '/' . $month;
        $targetDir = rtrim((string) app_config('upload_dir'), '/\\') . DIRECTORY_SEPARATOR . $relativeDir;
        if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
            return ['Could not create upload directory.'];
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
        $destination = $targetDir . DIRECTORY_SEPARATOR . $filename;
        if (!move_uploaded_file((string) $file['tmp_name'], $destination)) {
            return ['Could not store uploaded file.'];
        }

        $this->media->create([
            'filename' => (string) $file['name'],
            'filepath' => $relativeDir . '/' . $filename,
            'mime_type' => $mime,
            'alt_text' => trim((string) ($_POST['alt_text'] ?? '')),
            'file_size' => (int) $file['size'],
        ]);

        return [];
    }
}
