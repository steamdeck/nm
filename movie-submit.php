<?php
// Load countries and genres from JSON file
$data = json_decode(file_get_contents('cng.json'), true);
$countries = $data['countries'];
$genres = $data['genres'];

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $movieName = $_POST['movie_name'];
    $durationHour = $_POST['duration_hour'];
    $durationMinute = $_POST['duration_minute'];
    $story = $_POST['story'];
    $country = $_POST['country'];
    $newGenres = $_POST['new_genres']; // This field will allow multiple genres
    $release = $_POST['release'];
    $director = $_POST['director'];
    $cast = $_POST['cast'];
    $imageUrl = $_POST['image_url'];
    $imdbId = $_POST['imdb_id'];

    // Add new genres if provided and ensure they are unique and within length
    if ($newGenres) {
        $newGenresArray = array_map('trim', explode(',', $newGenres));
        $newGenresArray = array_filter($newGenresArray, function($genre) {
            return strlen($genre) <= 40; // Only keep genres with 40 characters or less
        });
        $genres = array_merge($genres, $newGenresArray);
        $genres = array_unique($genres);
        file_put_contents('data.json', json_encode(['countries' => $countries, 'genres' => $genres], JSON_PRETTY_PRINT));
    }

    // Create iframe URLs
    $iframeUrls = [
        "URL1" => "https://vidsrc.in/embed/movie/$imdbId",
        "URL2" => "https://vidsrc.xyz/embed/movie/$imdbId",
        "URL2" => "https://vidsrc.net/embed/movie/$imdbId",
        "URL4" => "https://vidsrc.io/embed/movie/$imdbId",
        "URL5" => "https://vidsrc.pm/embed/movie/$imdbId",
    ];

    // Construct the movie data array
    $movieData = [
        "movie name" => $movieName,
        "duration" => "$durationHour hours $durationMinute minutes",
        "story" => $story,
        "Country" => explode(',', $country), // Convert to array
        "Genre" => array_map('trim', $newGenresArray), // Use new genres
        "Release" => $release,
        "Director" => $director,
        "Cast" => explode(',', $cast), // Convert to array
        "image_url" => $imageUrl,
        "iframe_urls" => $iframeUrls,
    ];

    // Create JSON file name using IMDb ID
    $jsonFileName = 'json-movie/' . strtolower(trim($imdbId)) . '.json';

    // Save JSON data to a file
    file_put_contents($jsonFileName, json_encode($movieData, JSON_PRETTY_PRINT));

    echo "<p>Movie added successfully! <a href='$jsonFileName'>View JSON file</a></p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Movie</title>
    <style>
        .suggestions {
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            display: none;
            position: absolute;
            background: white;
            z-index: 1000;
        }
        .suggestion-item {
            padding: 5px;
            cursor: pointer;
        }
        .suggestion-item:hover {
            background: #f0f0f0;
        }
    </style>
</head>
<body>
    <h1>Add a New Movie</h1>
    <form method="post" action="">
        <label for="movie_name">Movie Name:</label>
        <input type="text" id="movie_name" name="movie_name" required><br><br>

        <label for="duration">Duration:</label>
        <select id="duration_hour" name="duration_hour" required>
            <option value="">Hour</option>
            <?php for ($i = 0; $i < 24; $i++): ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php endfor; ?>
        </select>
        <select id="duration_minute" name="duration_minute" required>
            <option value="">Minute</option>
            <?php for ($i = 0; $i < 60; $i += 5): ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php endfor; ?>
        </select><br><br>

        <label for="story">Story:</label>
        <textarea id="story" name="story" required></textarea><br><br>

        <label for="country">Country:</label>
        <input type="text" id="country" name="country" oninput="suggestCountry()" autocomplete="off" required>
        <div id="country-suggestions" class="suggestions"></div><br>

        <label for="new_genres">Genres (comma-separated):</label>
        <input type="text" id="new_genres" name="new_genres" oninput="suggestGenre()" autocomplete="off" required>
        <div id="genre-suggestions" class="suggestions"></div><br>

        <label for="release">Release Date:</label>
        <input type="date" id="release" name="release" required><br><br>

        <label for="director">Director:</label>
        <input type="text" id="director" name="director" required><br><br>

        <label for="cast">Cast (comma-separated):</label>
        <input type="text" id="cast" name="cast" required><br><br>

        <label for="image_url">Image URL:</label>
        <input type="url" id="image_url" name="image_url" required><br><br>

        <label for="imdb_id">IMDb ID:</label>
        <input type="text" id="imdb_id" name="imdb_id" required><br><br>

        <button type="submit">Add Movie</button>
    </form>

    <script>
        const countries = <?php echo json_encode($countries); ?>; // Fetch countries from PHP
        const genres = <?php echo json_encode($genres); ?>; // Fetch genres from PHP

        function suggestCountry() {
            const input = document.getElementById('country').value;
            const suggestionsBox = document.getElementById('country-suggestions');
            suggestionsBox.innerHTML = '';
            if (input) {
                const filteredCountries = countries.filter(country => country.toLowerCase().includes(input.toLowerCase()));
                if (filteredCountries.length) {
                    suggestionsBox.style.display = 'block';
                    filteredCountries.forEach(country => {
                        const item = document.createElement('div');
                        item.className = 'suggestion-item';
                        item.textContent = country;
                        item.onclick = () => {
                            document.getElementById('country').value = country;
                            suggestionsBox.innerHTML = '';
                            suggestionsBox.style.display = 'none';
                        };
                        suggestionsBox.appendChild(item);
                    });
                } else {
                    suggestionsBox.style.display = 'none';
                }
            } else {
                suggestionsBox.style.display = 'none';
            }
        }

        function suggestGenre() {
            const input = document.getElementById('new_genres').value;
            const suggestionsBox = document.getElementById('genre-suggestions');
            suggestionsBox.innerHTML = '';
            if (input) {
                const filteredGenres = genres.filter(genre => genre.toLowerCase().includes(input.toLowerCase()));
                if (filteredGenres.length) {
                    suggestionsBox.style.display = 'block';
                    filteredGenres.forEach(genre => {
                        const item = document.createElement('div');
                        item.className = 'suggestion-item';
                        item.textContent = genre;
                        item.onclick = () => {
                            const currentGenres = document.getElementById('new_genres').value;
                            document.getElementById('new_genres').value = currentGenres ? `${currentGenres}, ${genre}` : genre;
                            suggestionsBox.innerHTML = '';
                            suggestionsBox.style.display = 'none';
                        };
                        suggestionsBox.appendChild(item);
                    });
                } else {
                    suggestionsBox.style.display = 'none';
                }
            } else {
                suggestionsBox.style.display = 'none';
            }
        }
    </script>
</body>
</html>
