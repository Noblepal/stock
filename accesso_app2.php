<?php

$servername = $_POST['ip_destination'];

$databasehost = $servername;
$databasename = "androidBarcideReader";
$databaseusername = "root";
$databasepassword = $_POST['password'];

$con = mysql_connect($databasehost, $databaseusername, $databasepassword) or die(mysql_error($con));
mysql_select_db($con, $databasename) or die(mysql_error($con));
mysql_query($con, "SET CHARACTER SET utf8");

$response = array();

if (mysql_errno($con)) {
    $response['error'] = true;
    $response['message'] = "Credenciales no válidas";
} else {
    if (isset($_POST['get_products'])) {
        $query = "SELECT * FROM productos_stock";

        $sth = mysql_query($con, $query);
        $rows = array();
        while ($r = mysql_fetch_assoc($sth)) {
            $rows[] = $r;
        }

        if (sizeof($rows) > 0) {
            $response['error'] = false;
            $response['message'] = "Retrieved products";
            $response['products'] = $rows;
        } else {
            $response['error'] = true;
            $response['message'] = "No products";
        }
    } else if (isset($_POST['save_counted_products'])) {

        $producto = $_POST['producto'];
        $cantidad_contada = $_POST['cantidad_contada'];
        $cedula = $_POST['cedula'];
        $empresa = $_POST['empresa'];
        $sucursal = $_POST['sucursal'];

        $query = "INSERT INTO productos_contados(producto,cantidad_contada,cedula,empresa,sucursal) values('$producto','$cantidad_contada','$cedula','$empresa','$sucursal')";

        $result = mysql_query($con, $query);

        if (mysql_affected_rows($con) > 0) {
            $response['error'] = false;
            $response['message'] = "Added products";
        } else {
            $response['error'] = true;
            $response['message'] = "Did not add products " . mysql_error($con);
        }
    } else {
        $response['error'] = true;
        $response['message'] = "Operation not allowed";
    }
}

echo json_encode($response);
