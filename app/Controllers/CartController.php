<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\TransactionModel;

/**
 * CartController - Controller untuk mengelola keranjang belanja
 *
 * Controller ini menggunakan library CodeIgniterCart untuk mengelola
 * keranjang belanja pelanggan, termasuk menambah, mengubah, menghapus
 * item, serta proses checkout pesanan.
 *
 * @package App\Controllers
 */
class CartController extends BaseController
{
    /**
     * Instance library keranjang belanja
     *
     * @var \CodeIgniterCart\Cart
     */
    protected $cart;

    /**
     * Konstruktor - Inisialisasi instance keranjang belanja
     */
    public function __construct()
    {
        $this->cart = \Config\Services::cart();
    }

    // -----------------------------------------------------------------------

    /**
     * Tampilkan halaman keranjang belanja
     *
     * Mengambil seluruh isi keranjang, total harga, dan jumlah item
     * kemudian mengirimkannya ke view customer/cart.
     *
     * @return string Halaman view keranjang belanja
     */
    public function index()
    {
        $data = [
            'title'      => 'Keranjang - Burjo Connect',
            'pageTitle'  => 'Keranjang Belanja',
            'activePage' => 'cart',
            'cartItems'  => $this->cart->contents(),
            'total'      => $this->cart->total(),
            'totalItems' => $this->cart->totalItems(),
        ];

        return view('customer/cart', $data);
    }

    // -----------------------------------------------------------------------

    /**
     * Tambahkan item ke keranjang belanja
     *
     * Menerima data produk (product_id, name, price) dari form POST,
     * lalu menyisipkannya ke dalam keranjang dengan jumlah awal 1.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirect ke halaman utama
     */
    public function insert()
    {
        // Ambil data produk dari form POST
        $productId = $this->request->getPost('product_id');
        $name      = $this->request->getPost('name');
        $price     = $this->request->getPost('price');
        $image     = $this->request->getPost('image_path');

        // Siapkan data item untuk dimasukkan ke keranjang
        $item = [
            'id'    => $productId,
            'qty'   => 1,
            'price' => $price,
            'name'  => $name,
            'image' => $image,
        ];

        // Masukkan item ke keranjang
        $result = $this->cart->insert($item);

        if ($result) {
            session()->setFlashdata('success', 'Produk berhasil ditambahkan ke keranjang.');
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan produk ke keranjang.');
        }

        return redirect()->to('/');
    }

    // -----------------------------------------------------------------------

    /**
     * Perbarui jumlah item di keranjang belanja
     *
     * Menerima rowid dan qty dari form POST, lalu memperbarui jumlah
     * item yang sesuai. Jika qty diatur ke 0, item akan dihapus.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirect ke halaman keranjang
     */
    public function update()
    {
        // Ambil data dari form POST
        $rowid = $this->request->getPost('rowid');
        $qty   = $this->request->getPost('qty');

        // Batasi qty minimal 0 (tidak boleh minus)
        $qty = max(0, (int)$qty);

        // Perbarui item di keranjang
        $result = $this->cart->update([
            'rowid' => $rowid,
            'qty'   => $qty,
        ]);

        if ($result) {
            session()->setFlashdata('success', 'Keranjang berhasil diperbarui.');
        } else {
            session()->setFlashdata('error', 'Gagal memperbarui keranjang.');
        }

        return redirect()->to('/cart');
    }

    // -----------------------------------------------------------------------

    /**
     * Hapus item tertentu dari keranjang belanja
     *
     * Menghapus item berdasarkan rowid yang diberikan melalui parameter URL.
     *
     * @param string $rowid ID baris unik item di keranjang
     * @return \CodeIgniter\HTTP\RedirectResponse Redirect ke halaman keranjang
     */
    public function remove($rowid)
    {
        $this->cart->remove($rowid);

        session()->setFlashdata('success', 'Item berhasil dihapus dari keranjang.');

        return redirect()->to('/cart');
    }

    // -----------------------------------------------------------------------

    /**
     * Kosongkan seluruh isi keranjang belanja
     *
     * Menghapus semua item dari keranjang dan mengarahkan kembali
     * ke halaman utama.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirect ke halaman utama
     */
    public function destroy()
    {
        $this->cart->destroy();

        session()->setFlashdata('success', 'Keranjang berhasil dikosongkan.');

        return redirect()->to('/');
    }

    // -----------------------------------------------------------------------

    /**
     * Proses checkout pesanan
     *
     * Membuat pesanan (order) baru beserta item pesanan (order_items)
     * dan catatan transaksi (transaction) di database berdasarkan isi
     * keranjang saat ini. Setelah berhasil, keranjang dikosongkan dan
     * pengguna diarahkan ke halaman pelacakan pesanan.
     *
     * Proses:
     * 1. Ambil isi keranjang dan validasi tidak kosong
     * 2. Buat record order dengan status 'pending'
     * 3. Buat record order_items untuk setiap item di keranjang
     * 4. Buat record transaction dengan status 'unpaid'
     * 5. Kosongkan keranjang
     * 6. Redirect ke halaman tracking
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirect ke halaman tracking pesanan
     */
    public function checkout()
    {
        // Ambil isi keranjang
        $cartItems = $this->cart->contents();
        $total     = $this->cart->total();

        // Validasi keranjang tidak kosong
        if (empty($cartItems)) {
            session()->setFlashdata('error', 'Keranjang belanja kosong. Silakan tambahkan produk terlebih dahulu.');
            return redirect()->to('/');
        }

        // Ambil metode pembayaran dari form POST (default: cash)
        $paymentMethod = $this->request->getPost('payment_method') ?? 'cash';

        // Inisialisasi model yang dibutuhkan
        $orderModel       = new OrderModel();
        $orderItemModel   = new OrderItemModel();
        $transactionModel = new TransactionModel();

        // Gunakan database transaction untuk menjaga konsistensi data
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Buat record pesanan (order)
        $orderData = [
            'table_number' => '1',
            'status'       => 'pending',
            'total_amount' => $total,
        ];
        $orderModel->insert($orderData);
        $orderId = $orderModel->getInsertID();

        // 2. Buat record transaksi pembayaran
        $transactionData = [
            'order_id'       => $orderId,
            'payment_method' => $paymentMethod,
            'payment_mode'   => 'single',
            'amount'         => $total,
            'status'         => 'unpaid',
        ];
        $transactionModel->insert($transactionData);
        $transactionId = $transactionModel->getInsertID();

        // 3. Buat record item pesanan (order_items) untuk setiap item di keranjang
        foreach ($cartItems as $item) {
            $orderItemData = [
                'order_id'       => $orderId,
                'transaction_id' => $transactionId,
                'menu_id'        => $item['id'],
                'qty'            => $item['qty'],
                'price_at_order' => $item['price'],
            ];
            $orderItemModel->insert($orderItemData);
        }

        // Selesaikan database transaction
        $db->transComplete();

        // Periksa apakah transaksi database berhasil
        if ($db->transStatus() === false) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
            return redirect()->to('/cart');
        }

        // Kosongkan keranjang setelah checkout berhasil
        $this->cart->destroy();

        session()->setFlashdata('success', 'Pesanan berhasil dibuat! Silakan tunggu pesanan Anda diproses.');

        return redirect()->to('/track/' . $orderId);
    }
}
