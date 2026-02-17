<?php
// Diagnosis Script for Image Issues
header('Content-Type: text/plain');

echo "--- System Diagnosis ---\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "APP_URL (env): " . getenv('APP_URL') . "\n";
echo "APP_URL (config): " . config('app.url') . "\n";

echo "\n--- Storage Link Check ---\n";
$publicStorage = $_SERVER['DOCUMENT_ROOT'] . '/storage';
$targetStorage = $_SERVER['DOCUMENT_ROOT'] . '/../storage/app/public';

echo "Link Path: $publicStorage\n";
if (file_exists($publicStorage)) {
    echo "Exists: YES\n";
    echo "Is Link: " . (is_link($publicStorage) ? "YES" : "NO") . "\n";
    if (is_link($publicStorage)) {
        echo "Link Target: " . readlink($publicStorage) . "\n";
    }
} else {
    echo "Exists: NO (CRITICAL)\n";
}

echo "\n--- Target Directory Check ---\n";
echo "Target Path: $targetStorage\n";
if (file_exists($targetStorage)) {
    echo "Exists: YES\n";
    echo "Permissions: " . substr(sprintf('%o', fileperms($targetStorage)), -4) . "\n";
    echo "Owner/Group: " . fileowner($targetStorage) . ":" . filegroup($targetStorage) . "\n";
} else {
    echo "Exists: NO (CRITICAL - storage/app/public missing)\n";
}

echo "\n--- Logo File Check ---\n";
$logoSetting = \App\Models\Setting::get('system_logo');
echo "DB Setting 'system_logo': " . ($logoSetting ? $logoSetting : "NULL") . "\n";

if ($logoSetting) {
    $fullPath = $targetStorage . '/' . $logoSetting;
    echo "Expected File: $fullPath\n";
    if (file_exists($fullPath)) {
        echo "File Exists: YES\n";
        echo "File Perms: " . substr(sprintf('%o', fileperms($fullPath)), -4) . "\n";
        echo "File Size: " . filesize($fullPath) . " bytes\n";
    } else { // Check if it's just in the root of storage
         $rootPath = $targetStorage . '/' . basename($logoSetting);
         if (file_exists($rootPath)) {
            echo "File Found in Root (Path Mismatch): YES\n";
         } else {
            echo "File Exists: NO\n";
         }
    }
}

echo "\n--- Directory List (storage/app/public) ---\n";
if (is_dir($targetStorage)) {
    $files = scandir($targetStorage);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        echo "$file\n";
    }
}

echo "\n--- End Diagnosis ---\n";
