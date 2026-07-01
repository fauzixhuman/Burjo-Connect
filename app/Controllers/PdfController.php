<?php

namespace App\Controllers;

use App\Models\MenuModel;
use Dompdf\Dompdf;

class PdfController extends BaseController
{
    public function exportMenu()
    {
        $menuModel = new MenuModel();
        $menus = $menuModel->select('menus.*, categories.name as category_name')
                           ->join('categories', 'categories.id = menus.category_id')
                           ->where('menus.is_available', true)
                           ->findAll();

        $data = [
            'title' => 'Katalog Menu Burjo',
            'menus' => $menus
        ];

        // Buat view khusus untuk PDF, atau gunakan view yang ada lalu ubah formatnya
        $html = view('pdf/menu_pdf', $data);

        try {
            // Load dompdf manual dari ThirdParty (karena composer user bermasalah)
            $dompdfAutoload = APPPATH . 'ThirdParty/dompdf/autoload.inc.php';
            if (file_exists($dompdfAutoload)) {
                require_once $dompdfAutoload;
            }

            if (!class_exists('Dompdf\Dompdf')) {
                throw new \Exception("Library Dompdf belum terinstal.");
            }
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            
            // (Opsional) Setup ukuran kertas dan orientasi
            $dompdf->setPaper('A4', 'portrait');

            // Render PDF
            $dompdf->render();

            // Output file (stream untuk didownload atau ditampilkan)
            $dompdf->stream("katalog_menu_burjo.pdf", ["Attachment" => false]);
        } catch (\Exception $e) {
            echo "<h1>Gagal Memuat PDF</h1>";
            echo "<p>Maaf, fitur PDF belum bisa digunakan karena library Dompdf belum terinstal sempurna di server ini akibat kendala SSL Certificate.</p>";
            echo "<p>Silakan buka terminal/CMD Anda, dan jalankan perintah: <b>composer require dompdf/dompdf</b></p>";
            echo "<p>Jika Anda mengalami error <i>curl error 60 SSL certificate problem</i>, harap perbaiki konfigurasi sertifikat SSL pada instalasi PHP (php.ini) Anda.</p>";
        }
    }
}
