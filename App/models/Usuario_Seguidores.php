<?php
namespace App\Models;

use MF\Model\Model;

class Usuario_Seguidores extends Model{
    private $id;
    private $id_usuario_seguido;
    private $id_usuario_seguidor;

    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }

    public function seguirUsuario($id_usuario_seguindo){
       $query = "insert into usuarios_seguidores(id_usuario, id_usuario_seguindo) values (?, ?)";
       $stmt  = $this->db->prepare($query);
       $stmt->bindValue(1, $this->__get('id'));
       $stmt->bindValue(2, $id_usuario_seguindo);
       $stmt->execute();
       return true;
    }

    public function deixarDeSeguirUsuario($id_usuario_seguindo){
        $query = "
        delete from
         usuarios_seguidores 
        where
         id_usuario = ? and id_usuario_seguindo = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->__get('id'));
        $stmt->bindValue(2, $id_usuario_seguindo);
        $stmt->execute();
        return true;
    }
}
?>