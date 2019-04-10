<?php if(!class_exists('Rain\Tpl')){exit;}?><!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Lista de Reviews
  </h1>
</section>

<!-- Main content -->
<section class="content">

  <div class="row">
  	<div class="col-md-12">
  		<div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Review do produto</h3>
            <h2>Avaliações dos Usuários</h2>
            <div class="box-body" style="overflow: scroll; height: 80vmin">
            <?php if( $aval !== null && $aval !== '' ){ ?>

            <?php $counter1=-1;  if( isset($aval) && ( is_array($aval) || $aval instanceof Traversable ) && sizeof($aval) ) foreach( $aval as $key1 => $value1 ){ $counter1++; ?>

            <p><label>nome:</label> <?php echo utf8_decode($value1["nome"]); ?></p>
            <p><label>email:</label> <?php echo utf8_decode($value1["email"]); ?></p>
            <p><label>mensagem:</label></p>
            <p><?php echo utf8_decode($value1["review"]); ?></p>
            <a href="/admin/products/reviews/<?php echo htmlspecialchars( $value1["idreview"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/remove" onclick="return confirm('Deseja realmente excluir este registro?')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Excluir</a>
            <hr>
            <?php } ?>

            <?php }else{ ?>

            <p>Nenhuma avaliação disponivél!</p>
            <?php } ?>

            </div>

        </div>
        <!-- /.box-header -->
      </div>
  	</div>
  </div>

</section>
<!-- /.content -->
</div>
