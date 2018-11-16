<?php

$url = $_GET['url'] ?? '';
if ($url) {
    header('Content-Type: image/gif');
    echo file_get_contents($url);
}
