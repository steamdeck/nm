<?php
// Get all files in the current directory
$files = scandir(__DIR__);

// Filter out the current (.) and parent (..) directory entries
$files = array_filter($files, function($file) {
    return $file !== '.' && $file !== '..';
});

// Display the list of files with clickable links
echo "<ul>";
foreach ($files as $file) {
    echo "<li><a href='$file' target='_blank'>$file</a></li>";
}
echo "</ul>";
?>
