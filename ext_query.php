<?php 
if (isset($_GET['msg'])) {
    $msg=$_GET['msg'];
}else{
  $msg=0;
}

function olinkext()
{
  $serverName = "172.16.1.203";
  $connectionInfo = array( "Database"=>"ProaEx", "UID"=>"CHWEB", "PWD"=>"g488Vt@rEFc6");
  $conn = sqlsrv_connect( $serverName, $connectionInfo); 

if( $conn ) { 
     //echo "Conectado a la Base de Datos.<br />"; 
     return $conn;
}else{ 
     echo "NO se puede conectar a la Base de Datos.<br />"; 
     die( print_r( sqlsrv_errors(), true)); 
}
}

$now = 2018;
$nom=$_GET['nomina'];

$conn = olinkext(); 

$qbeneficiarios="SELECT FOLIO_TICKET,IDJV,BC,MONTOCON,NOMINA,NC,MONTOSOL FROM [dbo].[ASJUGUETIVALE] WHERE NOMINA=$nom AND ANIO=$now";
 $colaborador = sqlsrv_query($conn, $qbeneficiarios);
   if ($colaborador !== NULL) {  
      $rows = sqlsrv_has_rows( $colaborador );  
      if ($rows === false) {
        header("Location: ext_search.php?msg=1");
      }else{
        
     


    while( $obj = sqlsrv_fetch_object($colaborador)){
        $nomina=$obj->NOMINA;
        $nombre=utf8_encode($obj->NC);
        $monto=$obj->MONTOSOL;
        $montocon=$obj->MONTOCON;
        $id=$obj->IDJV;
        $bc=$obj->BC;
        $folio=$obj->FOLIO_TICKET;
    }
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title></title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/font-awesome.min.css">
      <script type="text/javascript" src="js/moment.js"></script>
  <script type="text/javascript" src="jquery-latest.min.js"></script>
  <script src="js/jquery.validate.js"></script>
  <style type="text/css" media="screen">
      .icon-white {color: white}
      .icon-silver {color: silver}
              body{
  background:  url("images/bici.jpg");
  background-size: cover;
  background-repeat: no-repeat;
}

table{
  background-color: rgba(255, 255, 255, 0.83);
}
h1, #datos, h3{
  background-color: rgba(255, 255, 255, 0.83);
  padding: 20px;
}

        <style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;margin:0px auto;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-yw4l{vertical-align:top}
</style>
  </style>

</head>
<body>
  <div class="container-fluid">
    <div class="row" align="center">
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
    </div>
    <div class="row">
      <div class="col-md-offset-2 col-md-8">
<br>
<a class="btn btn-default" href="ext_search.php" role="button">Nueva busqueda</a><br><br>
        <?php if ($msg == 2){?>
        <div class="alert alert-info" id='alerta'>
          <strong>Aviso!</strong> Datos guardados correctamente
        </div>
        <?php }?>
                <?php if ($msg == 3){?>
        <div class="alert alert-info" id='alerta'>
          <strong>Aviso!</strong> Debe completar todos los campos
        </div>
        <?php }?>
<table class="table table-striped">
  <tr>
    <td class="tg-031e">Nómina:</td>
    <td class="tg-yw4l"><?php echo $nomina; ?></td>
  </tr>
    <tr>
    <td class="tg-031e">Nombre:</td>
    <td class="tg-yw4l"><?php echo $nombre; ?></td>
  </tr>
  <tr>
    <td class="tg-031e">Monto Solicitado</td>
    <td class="tg-yw4l">$ <?php echo  number_format($monto, 2, '.', ','); ?></td>
  </tr>

<?php if ($bc == 0) { ?>
  <tr>
    <form action="ext_save.php" method="get" id="forma" accept-charset="utf-8">


    <td class="tg-031e">Monto Consumido</td>
    <td class="tg-yw4l"><input type="number" name="montocon" value="" step="0.01" id="montocon" required="" onchange="calculo(this.value)"></td>
  </tr>
  <tr>
    <td class="tg-031e">Folio Ticket</td>
    <td class="tg-yw4l"><input type="text" name="foliotkt" value="" id="foliotkt" required=""></td>
  </tr>
      <input type="hidden" name="id" value="<?php echo $id ?>">
      <input type="hidden" name="nomina" value="<?php echo $nomina ?>">
  <div id="msg"></div>
  <tr>
    <td class="tg-yw4l" colspan="2" align="right"><button type="submit" class="btn btn-default">Guardar</button></td>
  </form>
</tr>
  <?php }else{?>
      <tr>
    <td class="tg-031e">Monto Consumido</td>
    <td class="tg-yw4l">$ <?php echo number_format($montocon, 2, '.', ','); ?></td>
  </tr>
    <tr>
    <td class="tg-031e">Folio Ticket</td>
    <td class="tg-yw4l"><?php echo $folio ?></td>
  </tr>
 <?php } ?>
</table>
      </div>
    </div>
    </div>
</body>
<script>
function calculo() {
  var adr = $('#montocon').val();
  var madmax = '<?php echo $monto ?>';
  var adr = parseFloat(adr);
  var madmax = parseFloat(madmax);
  if (adr > madmax){
    $("button").attr("disabled","disabled");
    //alert('Tu Ahorro adicional mensual no puede sobrepasar los $ '+madmax+'.00 MNX')
    $('#msg').html('<div class="alert alert-warning" role="alert" >No puede sobrepasar los $ '+madmax+' MNX que solicito el colaborador</p></div>');
  }else{
    $('#msg').html('');
    $("button").removeAttr("disabled");

  }
}
</script>
<script>
  $(function(){
  $('input').on('input', function(){
    $('#acepto').attr('checked', false);
  });
});  
</script>


<script>
    $("#b2").click(function(){
      var id = '<?php echo $id ?>';
      var dataString = 'id='+ id;
    $('#div3').html('<div><img src="images/ajax-loader.gif"/></div>');
    $.ajax({url: "pdfdisabled.php", data: dataString, success: function(result){
        $("#div3").html(result);
    }});
        $("button").attr("disabled","disabled");
});
</script>

</html>

<?php }} ?>