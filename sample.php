<?php
// Include the header
$pageTitle = "Action Movies";
include 'header.php';

// Directory containing JSON files
$jsonDirectory = 'json-movie';

// Get all JSON files in the directory
$files = glob("$jsonDirectory/*.json");

// Limit number of movies per page
$moviesPerPage = 12; // Display 12 movies per page

// Paginate movie files
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$totalMovies = count($files);
$totalPages = ceil($totalMovies / $moviesPerPage);

// Calculate starting index for pagination
$startIndex = ($page - 1) * $moviesPerPage;
$paginatedFiles = array_slice($files, $startIndex, $moviesPerPage);

?>

<div class="movie-list section-padding-lr section-pt-50 section-pb-50 bg-black">
    <div class="container">
        <div class="section-title-4 st-border-bottom">
            <h2><?php echo htmlspecialchars($pageTitle); ?></h2>
        </div>
        <div class="row">
            <?php foreach ($paginatedFiles as $file) :
                $jsonContent = file_get_contents($file);
                $movieData = json_decode($jsonContent, true);
            ?>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                    <div class="movie-wrap text-center mb-30">
                        <div class="movie-img">
                            <a href="movieinfo.php?file=<?php echo urlencode($file); ?>"><img src="<?php echo $movieData['image_url']; ?>" alt="<?php echo $movieData['movie name']; ?>"></a>
                            <button title="Watchlist" class="Watch-list-btn" type="button"><i class="zmdi zmdi-plus"></i></button>
                        </div>
                        <div class="movie-content">
                            <h3 class="title"><a href="movieinfo.php?file=<?php echo urlencode($file); ?>"><?php echo $movieData['movie name']; ?></a></h3>
                            <span>Genre: <?php echo implode(', ', $movieData['Genre']); ?></span>
                            <div class="movie-btn">
                                <a href="movieinfo.php?file=<?php echo urlencode($file); ?>" class="btn-style-hm4-2 animated">Watch Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- Pagination -->
        <div class="pagination-style mt-30">
            <ul>
                <?php if ($totalPages > 1) :
                    for ($i = 1; $i <= $totalPages; $i++) :
                ?>
                        <li><a <?php echo ($i === $page) ? 'class="active"' : ''; ?> href="sample.php?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php
                    endfor;
                endif;
                ?>
            </ul>
        </div>
        <!-- End Pagination -->
    </div>
</div>

<?php
// Include the footer
include 'footer.php';
?>
