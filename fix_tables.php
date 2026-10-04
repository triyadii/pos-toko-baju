<?php

$dir = __DIR__ . '/resources/views';
$iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($iter as $file) {
    if ($file->isFile() && $file->getExtension() === 'php' && strpos($file->getFilename(), 'index.blade.php') !== false) {
        $content = file_get_contents($file->getPathname());
        
        // 1. Move Modals out of the table
        // We look for everything between <!-- Modal Edit --> or <!-- Modal Detail --> or <!-- Modal Koreksi -->
        // and the end of the foreach loop. But wait, it's easier to just match all `<div class="modal fade"` ... `</div>`
        // Actually, extracting them robustly with regex is hard.
        // Let's use a simpler approach: finding the closing </table> and moving all <div class="modal fade" ... </div> before the table to after the table? No, they are inside the foreach.
        
        // A safer regex to extract all Modals inside tbody:
        // We match <!-- Modal ... --> ... </div> (the matching closing div of the modal).
        // Since regex for balanced HTML is tricky in PHP, let's just do it manually.
    }
}
echo "Done";
