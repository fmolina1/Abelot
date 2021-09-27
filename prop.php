<?php
function Exportar($T,$f1,$f2){
//include('conexion.php');
       


$con=conectar();
if($f1 == '' || $f2==''){
        echo "<script language= 'javascript'>";
        echo "alert('No seleccionó fecha desde o la fecha hasta');";
        echo "window.location.href='../Vista/IUGenFactP.php';";
        echo "</script>";
}
if($T==1){

//manda Filtro de LAEM
$consulta=("SELECT TP.tipofactdescr AS 'Cód tipo de cbte', '1' AS PV, '' AS Número, DATE_FORMAT(F.fechaFactura, '%d/%m/%Y') AS 'Fecha de emisión', '1' AS 'Cód cond vta', C.NroCliente AS 'Código cliente', concat (C.NombreCli, ' ' , C.ApellidoCli) AS 'Nombre o razón social', C.IdTipoDoc AS idTipoDocumento, C.DNICli AS 'Nro documento', C.CondTrib AS idTipoResponsable, DATE_FORMAT(CURDATE(), '01/%m/%Y') AS 'fecha servicios desde', DATE_FORMAT(LAST_DAY(CURDATE()), '%d/%m/%Y') AS 'fecha servicios hasta', DATE_FORMAT(CURDATE(), '%d/%m/%Y') AS 'fecha vto pago', '1' AS idLista, C.IdTipoFact AS idConcepto, C.IdPlan AS 'Cód articulo', S.nombreServ AS 'Descripción', S.PlanMegas AS 'Descripción adicional',   '1' AS 'Cantidad', P.TotalPago AS Precio, '0,00' AS '% dto', '' AS 'cód vendedor', C.mailCli AS Email, DATE_FORMAT(F.FSVenc, '%d/%m/%Y') AS 'Fecha vto', '' AS 'Percepciones', '' AS 'Referencia', '' AS 'Comprobante relacionado'
from cliente AS C
inner join factura AS F ON C.NroCliente = F.IdCliente
inner join pago AS P ON F.NroFact = P.IdFact
INNER JOIN servicio AS S ON C.IdPlan = S.IdPlan
inner join TipoFact AS TP ON C.IdTipoFact = TP.IdTipoFact where P.fechaPago BETWEEN '$f1' AND '$f2' AND C.FAfip=$T");

$archivo= 'LAEM-FacturasAfip.xls';

}elseif($T==0){
        //manda filtro de AB
        $consulta = ("SELECT TP.tipofactdescr AS 'Cód tipo de cbte', '1' AS PV, '' AS Número, DATE_FORMAT(F.fechaFactura, '%d/%m/%Y') AS 'Fecha de emisión', '1' AS 'Cód cond vta', C.NroCliente AS 'Código cliente', concat (C.NombreCli, ' ' , C.ApellidoCli) AS 'Nombre o razón social', C.IdTipoDoc AS idTipoDocumento, C.DNICli AS 'Nro documento', C.CondTrib AS idTipoResponsable, DATE_FORMAT(CURDATE(), '01/%m/%Y') AS 'fecha servicios desde', DATE_FORMAT(LAST_DAY(CURDATE()), '%d/%m/%Y') AS 'fecha servicios hasta', DATE_FORMAT(CURDATE(), '%d/%m/%Y') AS 'fecha vto pago', '1' AS idLista, C.IdTipoFact AS idConcepto, C.IdPlan AS 'Cód articulo', S.nombreServ AS 'Descripción', S.PlanMegas AS 'Descripción adicional',   '1' AS 'Cantidad', P.TotalPago AS Precio, '0,00' AS '% dto', '' AS 'cód vendedor', C.mailCli AS Email, DATE_FORMAT(F.FSVenc, '%d/%m/%Y') AS 'Fecha vto', '' AS 'Percepciones', '' AS 'Referencia', '' AS 'Comprobante relacionado' from cliente AS C
inner join factura AS F ON C.NroCliente = F.IdCliente
inner join pago AS P ON F.NroFact = P.IdFact
INNER JOIN servicio AS S ON C.IdPlan = S.IdPlan
inner join TipoFact AS TP ON C.IdTipoFact = TP.IdTipoFact where P.fechaPago BETWEEN '$f1' AND '$f2' AND C.FAfip=$T");
        $archivo = 'AB-FacturasAfip.xls';
}
   // header("Content-Type: application/vnd.ms-excel charset=iso-8859-1");
$q=mysqli_query($con,(strval($consulta)));
$prueba = array();
 
    
   // $prueba = utf8_decode($prueba[]);
    
while ($rows = mysqli_fetch_assoc($q)) {
    
    $prueba[] = $rows;
    
}
    
   
if(!empty($prueba)) {

$filename = $archivo. ".xls";


        header('Content-type: application/vnd.ms-excel; charset=utf-16LE');
        header("Content-Disposition:attachment;filename=".$filename);
        header("Pragma: no-cache");
        header("Expires: 0");

$mostrar_columnas = false;

 

foreach($prueba as $prueb) {

if(!$mostrar_columnas) {

echo mb_convert_encoding(implode("\t", array_keys($prueb)) . "\n", 'UTF-16LE', 'UTF-8');

$mostrar_columnas = true;

}

echo mb_convert_encoding(implode("\t", array_values($prueb)) . "\n", 'UTF-16LE', 'UTF-8');

}

 

}else{

        echo "<script language= 'javascript'>";
        echo "alert('No hay Datos para Exportar');";
        echo "window.location.href='../Vista/IUGenFactP.php';";
        echo "</script>";

}

exit;

}

function ExportarCupon($T,$f){
    $con = conectar();

    if ($T == 1) {

        //manda Filtro de LAEM
        $consulta = ("SELECT c.NroCliente as 'Nro Cliente', DATE_FORMAT(f.FPVenc, '%d/%m/%Y') as '1º Vto', df.totalPago as '1º Monto', DATE_FORMAT(f.FSVenc, '%d/%m/%Y') as '2º Vto', df.totalPago2 as '2º Monto', DATE_FORMAT(f.FSVenc, '%d/%m/%Y') as '3º Vto', df.totalPago2 as '3º Monto' FROM factura as f inner JOIN cliente as c on f.IdCliente=c.NroCliente INNER JOIN detallefact as df on f.NroFact = df.IdFactura WHERE f.fechaFactura = '$f' and C.FAfip= $T and f.FactP=0 ");

        $archivo = 'LAEM-CuponSiro';
    } elseif ($T == 0) {
        //manda filtro de AB
        $consulta = ("SELECT c.NroCliente as 'Nro Cliente', DATE_FORMAT(f.FPVenc, '%d/%m/%Y') as '1º Vto', df.totalPago as '1º Monto', DATE_FORMAT(f.FSVenc, '%d/%m/%Y') as '2º Vto', df.totalPago2 as '2º Monto', DATE_FORMAT(f.FSVenc, '%d/%m/%Y') as '3º Vto', df.totalPago2 as '3º Monto' FROM factura as f inner JOIN cliente as c on f.IdCliente=c.NroCliente INNER JOIN detallefact as df on f.NroFact = df.IdFactura WHERE f.fechaFactura = '$f' and C.FAfip= $T and f.FactP=1 ");
        $archivo = 'AB-CuponSiro';
    }
    // header("Content-Type: application/vnd.ms-excel charset=iso-8859-1");
    $q = mysqli_query($con, (strval($consulta)));
    $prueba = array();
    //  header('Content-type:application/vnd.ms-excel; charset=utf-8');

    //header("Content-type:application/x-msexcel; charset=utf-8");
    while ($rows = mysqli_fetch_assoc($q)) {

        $prueba[] = $rows;
    }

    if (!empty($prueba)) {

        $filename = $archivo . ".xls";

        header("Content-Type: application/vnd.ms-excel; charset=UTF-16LE");

        header("Content-Disposition: attachment; filename=" . $filename);
        header("Expires: 0");



        $mostrar_columnas = false;



        foreach ($prueba as $prueb) {

            if (!$mostrar_columnas) {

                echo mb_convert_encoding(implode("\t", array_keys($prueb)) . "\n", 'UTF-16LE', 'UTF-8');

                $mostrar_columnas = true;
            }

            echo mb_convert_encoding(implode("\t", array_values($prueb)) . "\n", 'UTF-16LE', 'UTF-8');
        }
    } else {

        echo "<script language= 'javascript'>";
        echo "alert('No hay Cupones para exportar');";
        echo "window.location.href='../Vista/IUGenFact.php';";
        echo "</script>";

    }

    exit;
}

function ExportarPadronClientes($T){
    $con = conectar();

    if ($T == 1) {

        //manda Filtro de LAEM
        $consulta = ("SELECT NroCliente as 'NroCliente', CONCAT(NombreCli, ' ' , ApellidoCli) AS 'Descripción', '' AS 'Complemento', mailCli as 'Correo electrónico', '' as 'Porcentaje', '' as 'Tipo Adhesión', '' as 'Nro Adhesión' FROM `cliente`WHERE estadoInstal=1 AND date_format(FechaAlta, '%d/%m/%Y') <= date_format(date_sub(CURRENT_DATE(), INTERVAL 1 month),'30/%m/%Y') AND NOT NroCliente = 000000000 AND FAfip=$T ORDER BY `cliente`.`NroCliente` ASC  ");

        $archivo = 'LAEM-PadrónSiro';
    } elseif ($T == 0) {
        //manda filtro de AB
        $consulta = ("SELECT NroCliente as 'NroCliente', CONCAT(NombreCli, ' ' , ApellidoCli) AS 'Descripción', '' AS 'Complemento', mailCli as 'Correo electrónico', '' as 'Porcentaje', '' as 'Tipo Adhesión', '' as 'Nro Adhesión' FROM `cliente`WHERE estadoInstal=1 AND date_format(FechaAlta, '%d/%m/%Y') <= date_format(date_sub(CURRENT_DATE(), INTERVAL 1 month),'30/%m/%Y') AND NOT NroCliente = 000000000 AND FAfip=$T ORDER BY `cliente`.`NroCliente` ASC ");
        $archivo = 'AB-PadrónSiro';
    }
    // header("Content-Type: application/vnd.ms-excel charset=iso-8859-1");
    $q = mysqli_query($con, (strval($consulta)));
    $prueba = array();

   
    while ($rows = mysqli_fetch_assoc($q)) {
        //header('Content-type:application/vnd.ms-excel; charset=UTF-16LE');

        //header("Content-type:application/x-msexcel; charset=utf-8");
        $prueba[] = $rows;
    }

    if (!empty($prueba)) {

        $filename = $archivo . ".xls";

        header("Content-Type: application/vnd.ms-excel; charset=UTF-16LE");

        header("Content-Disposition: attachment; filename=" . $filename);
        header("Expires: 0");



        $mostrar_columnas = false;



        foreach ($prueba as $prueb) {

            if (!$mostrar_columnas) {

                
                echo mb_convert_encoding(implode("\t", array_keys($prueb)) . "\n", 'UTF-16LE', 'UTF-8');

                $mostrar_columnas = true;
            }
            if(preg_match("/^0/", $prueb['NroCliente'])) {

         //   $nc = $prueb['NroCliente'];
   //$prueb['NroCliente'] = $nc."";
}         
//$prub['NroCliente'] = "'" +$prub['NroCliente'];
            echo mb_convert_encoding(implode("\t", array_values($prueb)) . "\n", 'UTF-16LE', 'UTF-8');
        }
    } else {

        echo "<script language= 'javascript'>";
        echo "alert('No hay Clientes para exportar');";
        echo "window.location.href='../Vista/IUGenFact.php';";
        echo "</script>";
    }

    exit;
}
?>