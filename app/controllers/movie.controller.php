<?php

require_once 'app/models/movie.model.php';
require_once 'app/views/movie.view.php';

class MovieController
{
  private $model;
  private $view;

  public function __construct()
  {
    $this->model = new MovieModel();
    $this->view = new Movieview();
  }

  // inicio con lista de películas
  public function showMovies()
  {
    $movies = $this->model->getMovies();

    if ($movies) {
      $this->view->showMovies($movies);
    } else {
      $this->view->showError("movies not found");
    }
  }

  // detalle de peícula
  public function showMovie($title)
  {
    $movie = $this->model->getMovie(null, $title);

    if ($movie) {
      $this->view->showMovie($movie);
    } else {
      $this->view->showError("movie not found");
    }
  }

  // listado de géneros
  public function showGenres()
  {
    $genres = $this->model->getGenres();

    if ($genres) {
      // cantidad de películas por género
      $genresAndNumberOfMovies = [];

      foreach ($genres as $key => $genre) {
        // obtengo el nombre de cada género
        $genreName = $genre->main_genre;
        // obtengo las películas de cada género
        $moviesByGenre = $this->model->getMoviesByGenre($genreName);
        // inserto al array cada clave y valor
        $genresAndNumberOfMovies[$genreName] = count($moviesByGenre);
      }

      $this->view->showGenres($genresAndNumberOfMovies);
    } else {
      $this->view->showError("genres not found");
    }
  }

  // listado de películas por género
  public function showMoviesByGenre($genre)
  {
    $movies = $this->model->getMoviesByGenre($genre);

    if ($movies) {
      $this->view->showMovies($movies);
    } else {
      $this->view->showError("movies not found");
    }
  }

  // mostrar error
  public function showError($error)
  {
    $this->view->showError($error);
  }
}
