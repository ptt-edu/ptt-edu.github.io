<?php

function generate_sitemap($directory) {
    $urls = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'html') {
            $path = $file->getPathname();
            $url = 'https://pttedu.neocities.org/' . ltrim(str_replace($directory, '', $path), '/');
            $lastmod = date('c', $file->getMTime());
            $urls[] = [
                'loc' => $url,
                'lastmod' => $lastmod,
                'changefreq' => 'daily',
                'priority' => '0.8'
            ];
        }
    }

    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');
    foreach ($urls as $url) {
        $urlElement = $xml->addChild('url');
        $urlElement->addChild('loc', $url['loc']);
        $urlElement->addChild('lastmod', $url['lastmod']);
        $urlElement->addChild('changefreq', $url['changefreq']);
        $urlElement->addChild('priority', $url['priority']);
    }

    return $xml->asXML();
}

// Set the root directory of the website
$website_root_directory = $_SERVER['DOCUMENT_ROOT'];

// Generate the sitemap.xml content
$sitemap_xml = generate_sitemap($website_root_directory);

// Output the sitemap.xml content
header('Content-type: application/xml');
echo $sitemap_xml;