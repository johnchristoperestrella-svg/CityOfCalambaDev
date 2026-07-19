<?php
require 'config/helpers.php';
require 'config/database.php';
require 'app/Models/Document.php';

use App\Models\Document;

$doc = new Document();
echo "=== Document Model Test ===\n";
echo "Total Documents: " . $doc->getTotalCount() . "\n";
echo "Total Categories: " . $doc->getTotalCategories() . "\n";

$all = $doc->getAll();
echo "Documents returned: " . count($all) . "\n";
if (count($all) > 0) {
    echo "First document: " . $all[0]['title'] . "\n";
}

$cats = $doc->getByCategory();
echo "Categories returned: " . count($cats) . "\n";
if (count($cats) > 0) {
    echo "First category: " . $cats[0]['category'] . " (count: " . $cats[0]['count'] . ")\n";
}
?>
