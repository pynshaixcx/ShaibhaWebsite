<?php
// Create a script to download and store Pexels images locally
$image_urls = [
    "https://images.pexels.com/photos/1536619/pexels-photo-1536619.jpeg",
    "https://images.pexels.com/photos/996329/pexels-photo-996329.jpeg",
    "https://images.pexels.com/photos/1043474/pexels-photo-1043474.jpeg"
];

// Create directories if they don't exist
$directories = [
    'images/products',
    'images/thumbnails'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Download images
foreach ($image_urls as $index => $url) {
    $image_data = file_get_contents($url);
    $filename = 'product_' . ($index + 1) . '.jpg';
    file_put_contents('images/products/' . $filename, $image_data);
    
    // Create thumbnail
    file_put_contents('images/thumbnails/' . $filename, $image_data);
    
    echo "Downloaded: $filename\n";
}

// Update database to use local images
require_once 'config/database.php';

// Insert image records into product_images table
for ($i = 1; $i <= 3; $i++) {
    $product_id = $i;
    $image_path = 'images/products/product_' . $i . '.jpg';
    $is_primary = 1;
    
    // Check if image record exists
    $existing = fetchOne("SELECT id FROM product_images WHERE product_id = ? AND is_primary = 1", [$product_id]);
    
    if ($existing) {
        // Update existing record
        executeQuery("UPDATE product_images SET image_path = ? WHERE product_id = ? AND is_primary = 1", 
                    [$image_path, $product_id]);
    } else {
        // Insert new record
        executeQuery("INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, ?)", 
                    [$product_id, $image_path, $is_primary]);
    }
    
    echo "Updated image for product $product_id\n";
}

echo "Image download and database update complete!\n";
?>