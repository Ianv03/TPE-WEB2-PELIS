<?php

class AdminView{
  public function showList($movies){
    require_once 'templates/layout/header.phtml';
    require_once 'templates/admin/panel.phtml';
    require_once 'templates/admin/movies.phtml';
    require_once 'templates/layout/footer.phtml';
  }

  public function showAdd($genres){
    require_once 'templates/layout/header.phtml';
    require_once 'templates/admin/panel.phtml';
    require_once 'templates/admin/insert.phtml';
    require_once 'templates/layout/footer.phtml';
  }

  public function showEdit($genres, $movie){
    require_once 'templates/layout/header.phtml';
    require_once 'templates/admin/panel.phtml';
    require_once 'templates/admin/edit.phtml';
    require_once 'templates/layout/footer.phtml';
  }
  
  public function showError($error){
    require_once 'templates/layout/header.phtml';
    require_once 'templates/admin/panel.phtml';
    require_once 'templates/error.phtml';
    require_once 'templates/layout/footer.phtml';
  }

  public function showGenreList($genres){
    require_once 'templates/layout/header.phtml';
    require_once 'templates/admin/panel.phtml';
    require_once 'templates/admin/genres.phtml';
    require_once 'templates/layout/footer.phtml';
  }
  
  public function showAddGenre(){
    require_once 'templates/layout/header.phtml';
    require_once 'templates/admin/panel.phtml';
    require_once 'templates/admin/insertGenre.phtml';
    require_once 'templates/layout/footer.phtml';
  }
  
  public function showGenreEdit($genre){
    require_once 'templates/layout/header.phtml';
    require_once 'templates/admin/panel.phtml';
    require_once 'templates/admin/editGenre.phtml';
    require_once 'templates/layout/footer.phtml';
  }
}