<?php
// Include the header
$pageTitle = "Movie Details";
include 'header.php';

// Check if file parameter is set
if (isset($_GET['file'])) {
    $filePath = urldecode($_GET['file']);

    // Read JSON file
    $jsonContent = file_get_contents($filePath);
    $movieData = json_decode($jsonContent, true);

    // Output the page structure
    echo '<div class="movie-details-wrap section-ptb-50 bg-black">';
    echo '<div class="container">';
    echo '<div class="movie-details-video-content-wrap">';
    echo '<div class="video-wrap">';

    // Display iframe URLs if available
    if (isset($movieData['iframe_urls'])) {
        echo '<div class="iframe-section">';
        echo '<h4>Watch Online:</h4>';
        foreach ($movieData['iframe_urls'] as $key => $iframeURL) {
            echo '<div id="iframeContainer"></div>'; // Container for loading iframes

            // Output a link with onclick event to load iframe
            echo '<a href="#" onclick="loadIframe(\'' . $iframeURL . '\', \'' . $key . '\'); return false;">Watch on ' . $key . '</a>';
            echo '<br>';
        }
        $firstIframeURL = reset($movieData['iframe_urls']); // Get the first iframe URL
        echo '<script>';
        echo 'window.onload = function() {';
        echo '    loadIframe(\'' . $firstIframeURL . '\', \'' . key($movieData['iframe_urls']) . '\');'; // Load the first iframe
        echo '}';
        echo '</script>';

        echo '</div>';
    }

    echo '<img src="' . $movieData['image_url'] . '" alt="' . $movieData['movie name'] . '">';
    echo '</div>';
    echo '<div class="movie-details-content">';
    echo '<div class="movie-details-info">';
    echo '<ul>';
    echo '<li><span>Cast: </span>' . implode(', ', $movieData['Cast']) . '</li>';
    echo '<li><span>Genre: </span>' . implode(', ', $movieData['Genre']) . '</li>';
    echo '<li><span>Country: </span>' . implode(', ', $movieData['Country']) . '</li>';
    echo '<li><span>Director: </span>' . $movieData['Director'] . '</li>';
    echo '<li><span>Duration: </span>' . $movieData['duration'] . '</li>';
    echo '</ul>';
    echo '</div>';
    echo '<p>' . $movieData['story'] . '</p>';

    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

// Include the footer
include 'footer.php';
?>

<script>
// JavaScript function to load iframe
function loadIframe(url, title) {
    var iframeContainer = document.getElementById('iframeContainer');
    iframeContainer.innerHTML = ''; // Clear existing content
    var iframe = document.createElement('iframe');
    iframe.src = url;
    iframe.width = '100%';
    iframe.allowfullscreen = true;

    // Preventing popups inside iframe by intercepting window.open method
    iframe.onload = function() {
        var iframeDocument = iframe.contentWindow || iframe.contentDocument;

        // Block any attempts to open new windows (like popups) from the iframe
        iframeDocument.open = function() { return false; };
        iframeDocument.close = function() { return false; };

        // Ensure that any links inside the iframe open in the same window
        var links = iframeDocument.getElementsByTagName('a');
        for (var i = 0; i < links.length; i++) {
            links[i].setAttribute('target', '_self'); // Force links to open in the same window
        }
    };

    // Adjust iframe height dynamically
    var width = window.innerWidth; // Get the current viewport width
    iframe.height = (width / 2.35) + 'px'; // For both desktop and mobile

    iframeContainer.appendChild(iframe);
}
</script>
