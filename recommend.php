<?php
    // Include the header
	$pageTitle = "Action Movies";
    include 'header.php';
?>	
<div class="movie-list section-ptb-50 bg-black-2">
    <div class="container">
        <div class="section-title-4 st-border-bottom">
            <h2 class="res-font-dec">Recommended For You</h2>
        </div>
        <div class="movie-slider-active-3 nav-style-3">
            <!-- Movie items will be included dynamically here -->
            <?php
                // Example movie data - You can replace this with dynamic data from your database
                $recommended_movies = [
                    ["title" => "Top Of The World", "image" => "assets/images/product/movie-17.jpg"],
                    ["title" => "Land And Sea", "image" => "assets/images/product/movie-02.jpg"],
                    ["title" => "The Walk", "image" => "assets/images/product/movie-03.jpg"],
                    ["title" => "Never Stop Looking", "image" => "assets/images/product/movie-04.jpg"],
                    ["title" => "The Lost Girl", "image" => "assets/images/product/movie-01.jpg"],
                    ["title" => "Silkovettes In The Attic", "image" => "assets/images/product/movie-05.jpg"],
                ];

                // Loop through recommended movies and display each movie item
                foreach ($recommended_movies as $movie) {
                    echo '<div class="movie-wrap-plr">
                            <div class="movie-wrap text-center">
                                <div class="movie-img">
                                    <a href="movie-details.html"><img src="' . $movie["image"] . '" alt=""></a>
                                    <button title="Watchlist" class="Watch-list-btn" type="button"><i class="zmdi zmdi-plus"></i></button> 
                                </div>
                                <div class="movie-content">
                                    <h3 class="title"><a href="movie-details.html">' . $movie["title"] . '</a></h3>
                                    <span>Quality : HD</span>
                                    <div class="movie-btn">
                                        <a href="movie-details.html" class="btn-style-hm4-2 animated">Watch Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>';
                }
            ?>
        </div>
    </div>
</div>
 <?php
    // Include the footer
    include 'footer.php';
?>