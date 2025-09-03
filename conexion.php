<?php
$host = "aws.connect.psdb.cloud";
$user = "498465k3cy4zzyfton8n";
$pass = "pscale_pw_z48OWLRSN3fW903gw8cHR1NCB2cPvIlb1VrFChajsg2";
$db   = "caja_virtual";

// Ruta al certificado SSL (ajústala si lo guardaste en otra carpeta)
$ssl_cert = __DIR__ . "/cacert.pem";

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, $ssl_cert, NULL, NULL);
mysqli_real_connect($conn, $host, $user, $pass, $db, 3306, NULL, MYSQLI_CLIENT_SSL);

if (mysqli_connect_errno()) {
    die("Error de conexión: " . mysqli_connect_error());
} else {
    // ✅ Conexión exitosa
    // echo "Conectado correctamente a la base de datos";
}
?>
