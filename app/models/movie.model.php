<?php

require_once 'config.php';

class MovieModel
{
  private $db;

  public function __construct()
  {
    $this->db = new PDO("mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DB . ";charset=utf8", MYSQL_USER, MYSQL_PASS);
    $this->deploy();
  }

  // AUTODEPLOY
  private function deploy()
  {
    $query = $this->db->query('SHOW TABLES');
    $tables = $query->fetchAll();
    if (count($tables) == 0) {
      $sql = "CREATE TABLE `genre` (
        `id_genre` int(11) NOT NULL,
        `main_genre` varchar(45) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        
        INSERT INTO `genre` (`id_genre`, `main_genre`) VALUES
        (1, 'accion'),
        (2, 'terror'),
        (3, 'animacion'),
        (4, 'comedia');
        
        CREATE TABLE `movie` (
        `id_movie` int(11) NOT NULL,
        `title` varchar(60) NOT NULL,
        `poster_path` varchar(60) NOT NULL,
        `release_date` date NOT NULL,
        `overview` text NOT NULL,
        `id_genre` int(11) NOT NULL)
        ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        
        INSERT INTO `movie` (`id_movie`, `title`, `poster_path`, `release_date`, `overview`, `id_genre`) VALUES
        (12, 'The Mummy', 'images/movies/The mummy.jfif', '2026-04-03', 'La historia sigue a la joven hija de un periodista que desaparece misteriosamente en medio del desierto, dejando a su familia destrozada. Ocho años después, se produce un impactante reencuentro cuando la encuentran viva dentro de un sarcófago de 3000 años de antigüedad. Sin embargo, lo que debería ser una emotiva reunión familiar se transforma rápidamente en una aterradora pesadilla, ya que la niña regresa acompañada de fuerzas oscuras', 2),
        (14, 'The SpongeBob Movie', 'images/movies/The spongebob.jpg', '2025-12-16', 'The SpongeBob Movie: Search for SquarePants sigue a Bob Esponja en un épico viaje a las profundidades del océano para enfrentarse al Holandés Errante. En su intento por demostrar que es un \"tipo grande\" y valiente, se une accidentalmente a la tripulación del pirata fantasma, lo que lleva a sus amigos a una alocada misión de rescate en el inframundo', 3),
        (16, 'Scary Movie', 'images/movies/Scary Movie.jpg', '2000-07-07', 'Un año después de atropellar accidentalmente a un hombre y deshacerse de su cuerpo, seis amigos de preparatoria comienzan a ser perseguidos por un asesino enmascarado (muy similar al de Scream). Mientras intentan sobrevivir, se burlan de todos los clichés típicos de las películas de terror, mezclando misterio con un humor absurdo y escatológico', 4);

        CREATE TABLE `user` (
        `id_user` int(11) NOT NULL,
        `email` varchar(100) NOT NULL,
        `password` char(100) NOT NULL)
        ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
              
        INSERT INTO `user` (`id_user`, `email`, `password`) VALUES
        (1, 'web@admin.com', '\$2y\$10\$th8zeOQxEIOTkYz4J0ePmuueSxKJWoCdn2P1MPWymyqZLPQSIf3h2');
        
        ALTER TABLE `genre`
        ADD PRIMARY KEY (`id_genre`),
        ADD KEY `id_genre` (`id_genre`);
            
        ALTER TABLE `movie`
        ADD PRIMARY KEY (`id_movie`),
        ADD KEY `id_genre` (`id_genre`);
        
        ALTER TABLE `user`
        ADD PRIMARY KEY (`id_user`);
          
        ALTER TABLE `user`
        MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
            
        ALTER TABLE `movie`
        ADD CONSTRAINT `movie_ibfk_1` FOREIGN KEY (`id_genre`) REFERENCES `genre` (`id_genre`) ON DELETE CASCADE ON UPDATE CASCADE;
        COMMIT;
        ";
      $this->db->query($sql);
    }
  }

  // -- ACCESO PÚBLICO -- 
  // obtener todas las películas
  public function getMovies()
  {
    $query = $this->db->prepare('SELECT id_movie, title, poster_path, release_date, overview, main_genre FROM movie INNER JOIN genre ON movie.id_genre = genre.id_genre');
    $query->execute();
    $movies = $query->fetchAll(PDO::FETCH_OBJ);

    return $movies;
  }

  // buscar película según id o título
  public function getMovie($id_movie, $title)
  {
    $query = $this->db->prepare('SELECT id_movie, title, poster_path, release_date, overview, main_genre FROM movie INNER JOIN genre ON movie.id_genre = genre.id_genre WHERE id_movie = ? || title = ?');
    $query->execute([$id_movie, $title]);
    $movie = $query->fetch(PDO::FETCH_OBJ);

    return $movie;
  }

  // obtener la lista de géneros
  public function getGenres()
  {
    $query = $this->db->prepare('SELECT * FROM genre');
    $query->execute();
    $genres = $query->fetchAll(PDO::FETCH_OBJ);

    return $genres;
  }

  // obtener la lista de películas según género
  public function getMoviesByGenre($genre)
  {
    $query = $this->db->prepare('SELECT id_movie, title, poster_path, release_date, overview, main_genre FROM movie INNER JOIN genre ON movie.id_genre = genre.id_genre WHERE main_genre = ?');
    $query->execute([$genre]);
    $movies = $query->fetchAll(PDO::FETCH_OBJ);

    return $movies;
  }

  // -- ACCESO ADMINISTRADOR --
  // insertar una película
  public function add($id_movie, $title, $imgToLoad, $release_date, $overview, $id_genre)
  {
    $poster_path = $this->uploadImage($imgToLoad);

    $query = $this->db->prepare('INSERT INTO movie (id_movie, title, poster_path, release_date, overview, id_genre) VALUES (?,?,?,?,?,?)');
    $query->execute([$id_movie, $title, $poster_path, $release_date, $overview, $id_genre]);

    $id = $this->db->lastInsertId();
    return $id;
  }

  public function uploadImage($imgToLoad)
  {
    $poster_path = 'images/movies/' . uniqid("", true) . '.' . strtolower(pathinfo($imgToLoad['full_path'], PATHINFO_EXTENSION));
    move_uploaded_file($imgToLoad['tmp_name'], $poster_path);
    return $poster_path;
  }

  // eliminar una película
  public function delete($title)
  {
    $query = $this->db->prepare('DELETE FROM movie WHERE title = ?');
    $query->execute([$title]);
  }

  // editar una película
  public function edit($id_movie, $title, $imgToLoad, $release_date, $overview, $id_genre)
  {
    $poster_path = $this->uploadImage($imgToLoad);
    
    $query = $this->db->prepare("UPDATE movie SET title = ?, poster_path = ?, release_date = ?, overview = ?, id_genre = ? WHERE movie.id_movie = ?");
    $query->execute([$title, $poster_path, $release_date, $overview, $id_genre, $id_movie]);

    $id = $this->db->lastInsertId();
    return $id;
  }

  // buscar un género
  public function getGenre($id_genre, $main_genre)
  {
    $query = $this->db->prepare('SELECT * FROM genre WHERE id_genre = ? || main_genre = ?');
    $query->execute([$id_genre, $main_genre]);
    $genre = $query->fetch(PDO::FETCH_OBJ);

    return $genre;
  }

  // insertar un género
  public function addGenre($id_genre, $main_genre)
  {
    $query = $this->db->prepare('INSERT INTO genre(id_genre, main_genre) VALUES (?, ?)');
    $query->execute([$id_genre, $main_genre]);

    $id = $this->db->lastInsertId();
    return $id;
  }

  // eliminar género
  public function deleteGenre($main_genre)
  {
    $query = $this->db->prepare('DELETE FROM genre WHERE main_genre = ?');
    $query->execute([$main_genre]);
  }

  // editar un género
  public function editGenre($main_genre, $id_genre)
  {
    $query = $this->db->prepare("UPDATE genre SET main_genre = ? WHERE genre.id_genre = ?");
    $query->execute([$main_genre, $id_genre]);

    $id = $this->db->lastInsertId();
    return $id;
  }
}
