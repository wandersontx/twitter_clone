<?php
namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action{

    public function timeline(){
        $this->validaAutenticacao();    
        $tweet = Container::getModel('Tweet');
        $tweet->__set('id_usuario', $_SESSION['id']);
       

        //Paginação
        $totalRegistrosPagina = 5;       
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
        $deslocamento = ($pagina -1 ) * $totalRegistrosPagina;
      
        $tweets = $tweet->getPorPagina($totalRegistrosPagina, $deslocamento);         
        $total_twitters = $tweet->getTotalRegistros();         
        $this->view->total_de_paginas = ceil($total_twitters['total'] / $totalRegistrosPagina);
        $this->view->tweets = $tweets;
        $this->view->pagina_ativa = $pagina;


        //preenchendo dados do perfil
        $usuario = Container::getModel('Usuario');
        $usuario->__set('id',$_SESSION['id']);
        $this->view->info_usuario = $usuario->getInfoUsuario();
        $this->view->total_twitters = $usuario->getTotalTwitters();
        $this->view->total_seguindo = $usuario->getTotalSeguindo();
        $this->view->total_seguidores = $usuario->getTotalSegudores();
        $this->render('timeline');            
    }

    public function tweet(){
        $this->validaAutenticacao();  
        $tweet = Container::getModel('Tweet');
        $tweet->__set('tweet', $_POST['tweet']);
        $tweet->__set('id_usuario', $_SESSION['id']);
        $tweet->salvar();
        header('Location: /timeline');         
    }

    public function validaAutenticacao(){
        session_start();
        if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == ''){
            header('Location:/login=erro');
        }        
    }

    public function quemSeguir(){
        $this->validaAutenticacao(); 
        $pequisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';
        $usuarios = array();
        if(!empty($pequisarPor)){
            $usuario = Container::getModel('Usuario');
            $usuario->__set('nome', $pequisarPor);
            $usuario->__set('id', $_SESSION['id']);
            $usuarios = $usuario->getAll();           
        }
        $this->view->usuarios = $usuarios;

        $usuario = Container::getModel('Usuario');
        $usuario->__set('id',$_SESSION['id']);
        $this->view->info_usuario = $usuario->getInfoUsuario();
        $this->view->total_twitters = $usuario->getTotalTwitters();
        $this->view->total_seguindo = $usuario->getTotalSeguindo();
        $this->view->total_seguidores = $usuario->getTotalSegudores();
        $this->render('quemSeguir');
    }

    public function acao(){
       $this->validaAutenticacao();
       $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
       $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';
       $usuario_seguidor = Container::getModel('Usuario_Seguidores');
       $usuario_seguidor->__set('id',$_SESSION['id']);
       switch($acao){
           case 'seguir':
            $usuario_seguidor->seguirUsuario($id_usuario_seguindo);                      
           break;
           case 'deixar_de_seguir':
            $usuario_seguidor->deixarDeSeguirUsuario($id_usuario_seguindo);            
       }
       header('Location: /quem_seguir');

    }

    public function excluirTweet(){     
        $this->validaAutenticacao();  
        $tweet = Container::getModel('Tweet');
        $tweet->__set('tweet', $_POST['tweet']);
        $tweet->__set('id_usuario', $_SESSION['id']);
        $tweet->excluir($_GET['id_twitter']);
        header('Location: /timeline');     
    }


}

?>