<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Menu</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Katalog Menu Burjo Connect</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Nama Menu</th>
                <th>Deskripsi</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach($menus as $m): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= $m['category_name'] ?></td>
                <td><?= $m['name'] ?></td>
                <td><?= $m['description'] ?></td>
                <td>Rp <?= number_format($m['price'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
