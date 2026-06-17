<?php
$db = new PDO('sqlite:writable/warmindo.sqlite');
$stmt = $db->query('SELECT id, name FROM categories');
$cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($cats);

// Find duplicates
$seen = [];
foreach ($cats as $cat) {
    if (in_array($cat['name'], $seen)) {
        echo "Deleting duplicate: " . $cat['name'] . " (ID " . $cat['id'] . ")\n";
        $db->exec('DELETE FROM categories WHERE id = ' . $cat['id']);
    } else {
        $seen[] = $cat['name'];
    }
}
echo "Done.\n";
