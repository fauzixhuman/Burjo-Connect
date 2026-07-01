<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan #<?= str_pad($order['id'], 4, '0', STR_PAD_LEFT) ?></title>
    <style>
        @page { margin: 0; }
        body { 
            font-family: 'Courier New', Courier, monospace; 
            margin: 0; 
            padding: 10px; 
            width: 80mm; 
            background: #fff;
            color: #000;
        }
        .header { text-align: center; margin-bottom: 10px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 12px; }
        .divider { border-top: 1px dashed #000; margin: 5px 0; }
        .info { font-size: 12px; margin-bottom: 5px; }
        .info table { width: 100%; font-size: 12px; }
        .info table td { padding: 1px 0; }
        .items { width: 100%; font-size: 12px; border-collapse: collapse; margin-bottom: 5px; }
        .items th, .items td { text-align: left; vertical-align: top; padding: 2px 0; }
        .items .right { text-align: right; }
        .items .center { text-align: center; }
        .total-section { font-size: 12px; margin-top: 5px; }
        .total-section table { width: 100%; font-size: 12px; }
        .total-section table td { padding: 1px 0; }
        .total-section .right { text-align: right; }
        .total-section .bold { font-weight: bold; font-size: 14px; }
        .footer { text-align: center; font-size: 11px; margin-top: 10px; }
        
        @media print {
            .no-print, button { display: none !important; }
        }
        
        .print-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background: #EAB308;
            color: #000;
            border: none;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 15px;
            cursor: pointer;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">🖨️ CETAK STRUK</button>
    <button class="print-btn no-print" onclick="window.close()" style="background:#f3f4f6; margin-bottom:20px;">TUTUP</button>

    <div class="header">
        <h1>BURJO CONNECT</h1>
        <p>Jl. Contoh Jalan No. 123</p>
        <p>Telp: 081234567890</p>
    </div>

    <div class="divider"></div>

    <div class="info">
        <table>
            <tr>
                <td>Order</td>
                <td>: #<?= str_pad($order['id'], 4, '0', STR_PAD_LEFT) ?></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
            </tr>
            <tr>
                <td>Meja</td>
                <td>: <?= esc($order['table_number']) ?> <?= $order['session_id'] ? '(' . $order['session_id'] . ')' : '' ?></td>
            </tr>
            <tr>
                <td>Metode Pembayaran</td>
                <td>: <?= $paymentMethod ?></td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    <table class="items">
        <?php foreach ($items as $item): ?>
        <tr>
            <td colspan="3"><?= esc($item['name']) ?></td>
        </tr>
        <tr>
            <td style="width: 25%; padding-left:10px;"><?= $item['qty'] ?> x</td>
            <td style="width: 35%;"><?= number_format($item['price'], 0, ',', '.') ?></td>
            <td class="right" style="width: 40%;"><?= number_format($item['subtotal'], 0, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="divider"></div>

    <div class="total-section">
        <table>
            <tr>
                <td class="bold">TOTAL</td>
                <td class="right bold">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    <div class="footer">
        <p>Terima Kasih</p>
        <p>Atas Kunjungan Anda</p>
        <p>-- LUNAS --</p>
    </div>
    
    <script>
        // Auto print dialog when opened
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
