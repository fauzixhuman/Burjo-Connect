<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Root div for React App -->
<div id="react-checkout-root"></div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php 
// Include Vite build files
$manifestPath = FCPATH . 'dist/.vite/manifest.json';
if (is_file($manifestPath)) {
    $manifest = json_decode(file_get_contents($manifestPath), true);
    if (isset($manifest['src/checkout.jsx'])) {
        $entry = $manifest['src/checkout.jsx'];
        echo '<script type="module" src="' . base_url('dist/' . $entry['file']) . '"></script>';
        if (isset($entry['css'])) {
            foreach ($entry['css'] as $cssFile) {
                echo '<link rel="stylesheet" href="' . base_url('dist/' . $cssFile) . '">';
            }
        }
    }
} else {
    // Development mode (Vite server must be running on port 5173)
    echo '<script type="module" src="http://localhost:5173/@vite/client"></script>';
    echo '<script type="module" src="http://localhost:5173/src/checkout.jsx"></script>';
}
?>
<?= $this->endSection() ?>
