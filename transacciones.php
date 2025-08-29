<?php
session_start();
require 'conexion.php';

// Verificar que el usuario haya iniciado sesión
if(!isset($_SESSION['usuario_id'])){
    header("Location: index.html");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$nombre = $_SESSION['nombre'];
$handle = $_SESSION['usuario'];
$iniciales = isset($_SESSION['iniciales']) ? $_SESSION['iniciales'] : '';

// Obtener cuenta_id desde GET
if(!isset($_GET['cuenta_id'])){
    echo "No se ha especificado una cuenta.";
    exit();
}
$cuenta_id = intval($_GET['cuenta_id']);

// Verificar que la cuenta pertenece al usuario
$stmt = $conn->prepare("SELECT numero FROM cuentas WHERE id=? AND usuario_id=?");
$stmt->bind_param("ii", $cuenta_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$cuenta = $result->fetch_assoc();

if(!$cuenta){
    echo "Cuenta no encontrada o no pertenece al usuario.";
    exit();
}

// Traer transacciones
$stmt = $conn->prepare("SELECT fecha, tipo, monto, detalle FROM transacciones WHERE cuenta_id=? ORDER BY fecha DESC");
$stmt->bind_param("i", $cuenta_id);
$stmt->execute();
$result = $stmt->get_result();

$transacciones = [];
while($row = $result->fetch_assoc()){
    $transacciones[] = $row;
}
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Transacciones - Cuenta <?php echo $cuenta['numero']; ?></title>
  <style>
    body{margin:0;font-family:Inter,sans-serif;background:#f9fafb;color:#0f172a}
    header{background:#facc15;padding:12px 20px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 2px 6px rgba(0,0,0,0.1)}
    .logo{font-weight:700;font-size:18px}
    .home-btn{background:#2e7e32;color:white;border:none;border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;cursor:pointer}
    main{padding:24px;max-width:800px;margin:auto}
    h2{margin-bottom:16px}
    table{width:100%;border-collapse:collapse;background:white;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.08)}
    th,td{padding:12px;text-align:left;border-bottom:1px solid #e5e7eb}
    th{background:#f3f4f6;font-weight:700}
    tr:last-child td{border-bottom:none}
    .tipo-deposito{color:#2e7e32;font-weight:600}
    .tipo-retiro{color:#ef4444;font-weight:600}
    .tipo-deposito-de-interes{color:#0f172a;font-weight:600}
  </style>
</head>
<body>
  <header>
    <div class="logo">Banco Amate</div>
    <button class="home-btn" title="Regresar" onclick="window.location.href='cliente.php'">&#8592;</button>
  </header>
  <main>
    <h2>Transacciones de la cuenta <?php echo $cuenta['numero']; ?></h2>
    
    <?php if(empty($transacciones)): ?>
        <p>No hay transacciones para esta cuenta.</p>
    <?php else: ?>
        <table>
          <tr>
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Monto</th>
            <th>Detalle</th>
          </tr>
          <?php foreach($transacciones as $t): ?>
            <tr>
              <td><?php echo date("d/m/Y H:i", strtotime($t['fecha'])); ?></td>
              <td class="<?php 
                    if($t['tipo']=='Depósito') echo 'tipo-deposito';
                    elseif($t['tipo']=='Retiro') echo 'tipo-retiro';
                    else echo 'tipo-deposito-de-interes'; ?>">
                <?php echo $t['tipo']; ?>
              </td>
              <td>L <?php echo number_format($t['monto'],2); ?></td>
              <td><?php echo $t['detalle']; ?></td>
            </tr>
          <?php endforeach; ?>
        </table>
    <?php endif; ?>
  </main>
</body>
</html>
