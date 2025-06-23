<?php
// Performance optimization script

// Enable output buffering
ob_start();

// Enable gzip compression
if (extension_loaded('zlib') && !ini_get('zlib.output_compression')) {
    ini_set('zlib.output_compression', 'On');
    ini_set('zlib.output_compression_level', '5');
}

// Set cache headers for static assets
function setCacheHeaders() {
    $uri = $_SERVER['REQUEST_URI'];
    $ext = pathinfo($uri, PATHINFO_EXTENSION);
    
    $cacheable_types = ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
    
    if (in_array($ext, $cacheable_types)) {
        $cache_time = 60 * 60 * 24 * 7; // 1 week
        header("Cache-Control: public, max-age=$cache_time");
        header("Expires: " . gmdate("D, d M Y H:i:s", time() + $cache_time) . " GMT");
    }
}

// Call the function
setCacheHeaders();

// Minify HTML output
function minifyHTML($html) {
    $search = [
        '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
        '/[^\S ]+\</s',     // strip whitespaces before tags, except space
        '/(\s)+/s',         // shorten multiple whitespace sequences
        '/<!--(.|\s)*?-->/' // Remove HTML comments
    ];
    
    $replace = [
        '>',
        '<',
        '\\1',
        ''
    ];
    
    return preg_replace($search, $replace, $html);
}

// Register shutdown function to minify output
register_shutdown_function(function() {
    $output = ob_get_clean();
    echo minifyHTML($output);
});
?>