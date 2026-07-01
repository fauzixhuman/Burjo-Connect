<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=warmindo_connect', 'root', '');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 0;');
$pdo->exec('TRUNCATE TABLE order_items;');
$pdo->exec('TRUNCATE TABLE transactions;');
$pdo->exec('TRUNCATE TABLE orders;');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1;');
echo "TRUNCATE SUCCESS\n";
