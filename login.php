<?php
// Datos de conexión
$servername = "aws.connect.psdb.cloud";  // o el host de tu DB (PlanetScale, etc.)
$username   = "4cllkeeppkt4b3587xbo";       // tu usuario
$password   = "pscale_pw_LbWMpjirs4S99U9BdROE2I6cmUzs2u1MEai9tbyXdWH";           // tu contraseña
$dbname     = "caracas";    // el nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Recibir datos del formulario
$usuario = $_POST['usuario'];
$pass    = $_POST['password'];

// Buscar usuario
$sql = "SELECT * FROM usuarios WHERE usuario = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $usuario, $pass);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Usuario encontrado
    $user = $result->fetch_assoc();
    $id_usuario = $user['id'];
    $nombre     = $user['nombre'];

    // Buscar la cuenta asociada
    $sql2 = "SELECT numero_cuenta, saldo FROM cuentas WHERE usuario_id = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $id_usuario);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    if ($result2->num_rows > 0) {
        $cuenta = $result2->fetch_assoc();
        $numero_cuenta = $cuenta['numero_cuenta'];
        $saldo         = $cuenta['saldo'];

        echo "<h2>Bienvenido, $nombre</h2>";
        echo "<p>Número de cuenta: $numero_cuenta</p>";
        echo "<p>Saldo: $saldo</p>";
    } else {
        echo "No se encontró cuenta asociada.";
    }
} else {
    echo "Usuario o contraseña incorrectos.";
}

$conn->close();
?>
