create procedure sp_wishlist_save(IN pidproducts    int, IN pidlist int, IN piduser int)
  BEGIN

    IF pidlist > 0 THEN

      UPDATE tb_wishlist
      SET
          idproducts = pidproducts,
          iduser = piduser
      WHERE idlist = pidlist;

    ELSE

      INSERT INTO tb_wishlist (idproducts, iduser)
      VALUES(pidproducts, piduser);

      SET pidlist = LAST_INSERT_ID();

    END IF;

    SELECT * FROM tb_wishlist WHERE idlist = pidlist;

  END;