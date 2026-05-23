<?php

class MovieView{
  public function showMovies($movies){
    require_once 'templates/layout/header.phtml';
    require_once 'templates/movies.phtml';        
    require_once 'templates/layout/footer.phtml';
  }

  public function showMovie($movie){
    require_once 'templates/layout/header.phtml';
    require_once 'templates/movie.phtml';        
    require_once 'templates/layout/footer.phtml';
  }

  public function showGenres($genresAndNumberOfMovies){
    require_once 'templates/layout/header.phtml';
    require_once 'templates/genres.phtml';        
    require_once 'templates/layout/footer.phtml';
  }
  
  public function showError($error){
    require_once 'templates/layout/header.phtml';
    require_once 'templates/error.phtml';
    require_once 'templates/layout/footer.phtml';
  }
}