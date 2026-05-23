<?php

class AuthView{
  public function showLogin(){
    require_once 'templates/layout/header.phtml';
    require_once 'templates/auth/login.phtml';
    require_once 'templates/layout/footer.phtml';
  }

  public function showError($error){
    require_once 'templates/layout/header.phtml';
    require_once 'templates/error.phtml';
    require_once 'templates/layout/footer.phtml';
  }
}
