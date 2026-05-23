<?php

require_once 'app/views/admin.view.php';

class AdminController
{
  private $model;
  private $view;

  public function __construct()
  {
    $this->model = new MovieModel();
    $this->view = new AdminView();
  }

  // desloguearse
  public function logout()
  {
    session_start();
    session_destroy();
    header('Location: ' . BASE_URL);
  }

  // panel con lista de películas
  public function showList()
  {
    $movies = $this->model->getMovies();

    if ($movies) {
      $this->view->showList($movies);
    } else {
      $this->view->showError("movies not found");
    }
  }

  // formulario para insertar una película
  public function showAdd()
  {
    $genres = $this->model->getGenres();

    $this->view->showAdd($genres);
  }

  // insertar una película
  public function add()
  {
    if (!isset($_POST['id_movie']) || empty($_POST['id_movie'])) {
      return $this->view->showError("'id_movie' field must be complete");
    }
    if (!isset($_POST['title']) || empty($_POST['title'])) {
      return $this->view->showError("'title' field must be complete");
    }
    if ($_FILES['poster_path']['type'] != "image/png" && $_FILES['poster_path']['type'] != "image/jpg" && $_FILES['poster_path']['type'] != "image/jpeg") {
      return $this->view->showError("image loading error");
    }
    if (!isset($_POST['release_date']) || empty($_POST['release_date'])) {
      return $this->view->showError("'release_date' field must be complete");
    }
    if (!isset($_POST['overview']) || empty($_POST['overview'])) {
      return $this->view->showError("'overview' field must be complete");
    }
    if (!isset($_POST['id_genre']) || empty($_POST['id_genre'])) {
      return $this->view->showError("'id_genre' field must be complete");
    }

    $id_movie = $_POST['id_movie'];
    $title = $_POST['title'];
    $poster_path = $_FILES['poster_path'];
    $release_date = $_POST['release_date'];
    $overview = $_POST['overview'];
    $id_genre = $_POST['id_genre'];

    $movie = $this->model->getMovie(null, $title);

    if ($movie) {
      return $this->view->showError('this movie already exists in your database');
    }

    $id = $this->model->add($id_movie, $title, $poster_path, $release_date, $overview, $id_genre);

    // redirijo al panel admin
    header('Location: ' . BASE_URL . 'showList');
  }

  // eliminar una película
  public function delete($title)
  {
    $movie = $this->model->getMovie(null, $title);

    if ($movie) {
      $this->model->delete($title);

      // redirijo al panel admin
      header('Location: ' . BASE_URL . 'showList');
    } else {
      $this->view->showError('the movie you want to delete does not exist');
    }
  }

  // formulario para editar película
  public function showEdit($title)
  {
    $genres = $this->model->getGenres();
    $movie = $this->model->getMovie(null, $title);

    if ($movie) {
      $this->view->showEdit($genres, $movie);
    } else {
      $this->view->showError('this movie does not exist in your database');
    }
  }

  // editar una película
  public function edit()
  {

    if (!isset($_POST['id_movie']) || empty($_POST['id_movie'])) {
      return $this->view->showError("'id_movie' field must be complete");
    }
    if (!isset($_POST['title']) || empty($_POST['title'])) {
      return $this->view->showError("'title' field must be complete");
    }
    if ($_FILES['poster_path']['type'] != "image/png" && $_FILES['poster_path']['type'] != "image/jpg" && $_FILES['poster_path']['type'] != "image/jpeg") {
      return $this->view->showError("image loading error");
    }
    if (!isset($_POST['release_date']) || empty($_POST['release_date'])) {
      return $this->view->showError("'release_date' field must be complete");
    }
    if (!isset($_POST['overview']) || empty($_POST['overview'])) {
      return $this->view->showError("'overview' field must be complete");
    }
    if (!isset($_POST['id_genre']) || empty($_POST['id_genre'])) {
      return $this->view->showError("'id_genre' field must be complete");
    }

    $id_movie = $_POST['id_movie'];
    $title = $_POST['title'];
    $poster_path = $_FILES['poster_path'];
    $release_date = $_POST['release_date'];
    $overview = $_POST['overview'];
    $id_genre = $_POST['id_genre'];

    $movie = $this->model->getMovie($id_movie, null);

    if ($movie) {
      $id = $this->model->edit($id_movie, $title, $poster_path, $release_date, $overview, $id_genre);

      // redirijo al panel admin
      header('Location: ' . BASE_URL . 'showList');
    } else {
      $this->view->showError('the movie you want to modify does not exist');
    }
  }

  // panel con lista de géneros
  public function showGenreList()
  {
    $genres = $this->model->getGenres();

    if ($genres) {
      $this->view->showGenreList($genres);
    } else {
      $this->view->showError("genres not found");
    }
  }

  // formulario para insertar un género
  public function showAddGenre()
  {
    $this->view->showAddGenre();
  }

  // insertar un género
  public function addGenre()
  {
    if (!isset($_POST['id_genre']) || empty($_POST['id_genre'])) {
      return $this->view->showError("'id_genre' field must be complete");
    }
    if (!isset($_POST['main_genre']) || empty($_POST['main_genre'])) {
      return $this->view->showError("'main_genre' field must be complete");
    }

    $id_genre = $_POST['id_genre'];
    $main_genre = $_POST['main_genre'];

    $genre = $this->model->getGenre($id_genre, $main_genre);

    if (!$genre) {
      $id = $this->model->addGenre($id_genre, $main_genre);

      // redirijo al panel admin
      header('Location: ' . BASE_URL . 'showGenreList');
    } else {
      $this->view->showError('this genre already exists in your database');
    }
  }

  // eliminar un género
  public function deleteGenre($main_genre)
  {
    $genre = $this->model->getGenre(null, $main_genre);

    if ($genre) {
      $this->model->deleteGenre($main_genre);

      // redirijo al panel admin
      header('Location: ' . BASE_URL . 'showGenreList');
    } else {
      $this->view->showError('the genre you want to delete does not exist');
    }
  }

  // formulario para editar un género
  public function showGenreEdit($main_genre)
  {
    $genre = $this->model->getGenre(null, $main_genre);

    if ($genre) {
      $this->view->showGenreEdit($genre);
    } else {
      $this->view->showError('this movie does not exist in your database');
    }
  }

  // editar un género
  public function editGenre()
  {

    if (!isset($_POST['id_genre']) || empty($_POST['id_genre'])) {
      return $this->view->showError("'id_genre' field must be complete");
    }
    if (!isset($_POST['main_genre']) || empty($_POST['main_genre'])) {
      return $this->view->showError("'main_genre' field must be complete");
    }

    $id_genre = $_POST['id_genre'];
    $main_genre = $_POST['main_genre'];

    $genre = $this->model->getGenre($id_genre, null);

    if ($genre) {
      $id = $this->model->editGenre($main_genre, $id_genre);

      // redirijo al panel admin
      header('Location: ' . BASE_URL . 'showGenreList');
    } else {
      $this->view->showError('the movie you want to modify does not exist');
    }
  }
}
