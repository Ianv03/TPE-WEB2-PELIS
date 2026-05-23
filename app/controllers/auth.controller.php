<?php

require_once 'app/models/user.model.php';
require_once 'app/views/auth.view.php';

class AuthController
{
  private $model;
  private $view;

  public function __construct()
  {
    $this->model = new UserModel();
    $this->view = new AuthView();
  }

  // mostrar form login
  public function showLogin()
  {
    $this->view->showLogin();
  }

  // verificar datos y loguear al administrador
  public function login()
  {
    if (!isset($_POST['email']) || empty($_POST['email'])) {
      return $this->view->showError("'email' field must be complete");
    }
    if (!isset($_POST['password']) || empty($_POST['password'])) {
      return $this->view->showError("'password' field must be complete");
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    // verificar que el usuario esta en la DB
    $userDB = $this->model->getUserByEmail($email);

    if ($userDB && password_verify($password, $userDB->password)) {
      // guardamos los datos del usuario en session
      session_start();
      $_SESSION['id_user'] = $userDB->id_user;
      $_SESSION['email'] = $userDB->email;

      //redirijo al panel admin
      header('Location: ' . BASE_URL . 'showList');
    } else {
      return $this->view->showError('incorrect credentials');
    }
  }

  // mostrar error
  public function showError($error)
  {
    $this->view->showError($error);
  }
}
