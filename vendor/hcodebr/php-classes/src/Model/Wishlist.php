<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Model\User;
use \Hcode\Model\Product;

class Wishlist extends Model {

    public function get(int $idlist)
    {

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_wishlist WHERE idlist = :idlist", [
            ':idlist'=>$idlist
        ]);

        if(count($results) > 0) {

            $this->setData($results[0]);
        }

    }

    public function save()
    {

        $sql = new Sql();

        $results = $sql->select("CALL sp_wishlist_save(:idproducts, :idlist, :iduser)", [
            ':idproducts'=>$this->getidproducts(),
            ':idlist'=>$this->getidlist(),
            ':iduser'=>$this->getiduser()
        ]);

        $this->setData($results[0]);

    }

    public function remove()
    {

        $sql = new Sql();

        $sql->query("DELETE FROM tb_wishlist WHERE idlist = :idlist", [
            ':idlist'=>$this->getidlist()
        ]);

    }

    public static function getFromUserProducts($iduser, $idproducts)
    {

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_wishlist WHERE iduser = :iduser AND idproducts = :idproducts", [
            ':iduser'=>$iduser,
            ':idproducts'=>$idproducts
        ]);

        if(count($results) > 0) {

            return $results[0];

        }

    }

    public static function getFromUser($iduser)
    {

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_wishlist WHERE iduser = :iduser", [
            ':iduser'=>$iduser
        ]);

        if(count($results) > 0) {

            return $results;

        }

    }

}
