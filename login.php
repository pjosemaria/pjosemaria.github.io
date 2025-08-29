<?php
session_start();
require "conexion.php";

$usuario = $_POST['usuario'] ?? '';
$pin     = $_POST['pin'] ?? '';

// 1️⃣ Buscar el usuario
$sqlUser = "SELECT id, nombre FROM usuarios WHERE usuario = ? AND pin = ?";
$stmtUser = mysqli_prepare($conn, $sqlUser);
mysqli_stmt_bind_param($stmtUser, "ss", $usuario, $pin);
mysqli_stmt_execute($stmtUser);
$resultUser = mysqli_stmt_get_result($stmtUser);

if ($userData = mysqli_fetch_assoc($resultUser)) {
    $_SESSION['usuario'] = $usuario;
    $_SESSION['nombre'] = $userData['nombre'];
    $_SESSION['usuario_id'] = $userData['id'];
    $_SESSION['iniciales'] = $iniciales;

    


    // 2️⃣ Traer las cuentas usando usuario_id
    $sqlCuentas = "SELECT * FROM cuentas WHERE usuario_id = ?";
    $stmtCuentas = mysqli_prepare($conn, $sqlCuentas);
    mysqli_stmt_bind_param($stmtCuentas, "i", $_SESSION['usuario_id']);
    mysqli_stmt_execute($stmtCuentas);
    $resultCuentas = mysqli_stmt_get_result($stmtCuentas);

    $_SESSION['cuentas'] = mysqli_fetch_all($resultCuentas, MYSQLI_ASSOC);

    header("Location: cliente.php");
    exit;

} else {
    echo "Usuario o PIN incorrectos";
}
?>


<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Caja Virtual</title>
  <style>
    :root{
      --header:#facc15; /* header amarillo */
      --bg:#ffffff; /* fondo blanco */
      --card:#2e7e32; /* verde oscuro */
      --muted:#d1d5db;
      --radius:14px;
      --shadow: 0 6px 18px rgba(16,24,40,0.12);
    }
    *{box-sizing:border-box;font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial}
    body{margin:0;background:var(--bg);color:#0f172a}

    header{
      background:var(--header);
      display:flex;
      align-items:center;
      justify-content:space-between;
      padding:12px 20px;
      box-shadow:0 2px 6px rgba(0,0,0,0.1);
    }
    .logo{display:flex;align-items:center;gap:10px;font-weight:700;font-size:18px;}
    .logo img{height:40px;width:auto}
    .home-btn{background:#2e7e32;color:white;border:none;border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 4px 10px rgba(0,0,0,0.2);}
    .home-btn svg{width:20px;height:20px}
    main{padding:24px}
    .profile-row{display:flex;align-items:center;gap:12px;margin-bottom:18px}
    .avatar{width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#c7d2fe,#a7f3d0);display:flex;align-items:center;justify-content:center;font-weight:700;color:#000;box-shadow:0 2px 6px rgba(12,18,30,0.08)}
    .user-info{display:flex;flex-direction:column}
    .user-name{font-weight:700;font-size:16px}
    .user-handle{font-size:13px;color:#374151}
    .bank-card{width:100%;max-width:420px;background:var(--card);color:white;border-radius:var(--radius);padding:18px 18px 16px;box-shadow:var(--shadow);position:relative;overflow:hidden;margin-bottom:20px}
    .bank-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px}
    .bank-brand{display:flex;gap:12px;align-items:center}
    .chip{width:44px;height:30px;border-radius:6px;background:linear-gradient(180deg,#f8fafc,#e2e8f0);display:flex;align-items:center;justify-content:center;color:#0f172a;font-weight:700}
    .bank-name{font-weight:700;letter-spacing:0.6px}
    .account-number{font-size:14px;color:rgba(255,255,255,0.85);margin-bottom:8px}
    .balance{font-size:22px;font-weight:800}
    .subtle{font-size:12px;color:rgba(255,255,255,0.7)}
    .more-info{display:flex;gap:12px;margin-top:12px;flex-wrap:wrap}
    .info-item{background:rgba(255,255,255,0.1);padding:8px 10px;border-radius:10px;font-size:13px}
    @media (max-width:420px){.bank-card{padding:14px}}
  </style>
</head>
<body>
  <header>
    <div class="logo">
      <img src="logo.png" alt="Logo" />
      <span>Caja Virtual</span>
    </div>
    <button class="home-btn" title="Salir" onclick="window.location.href='logout.php'">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
        <path d="M10 17l5-5-5-5"></path>
        <path d="M15 12H3"></path>
      </svg>
    </button>
  </header>

  <main>
    <div class="profile-row">
      <div class="avatar"><?php echo strtoupper(substr($userData['nombre'],0,2)); ?></div>
      <div class="user-info">
        <div class="user-name"><?php echo $userData['nombre']; ?></div>
        <div class="user-handle">@<?php echo $usuario; ?></div>
      </div>
    </div>

    <?php while($cuenta = mysqli_fetch_assoc($resultCuentas)): ?>
      <div class="bank-card">
        <div class="bank-header">
          <div class="bank-brand">
            <div class="chip">•••</div>
            <div>
              <div class="bank-name">Caja Virtual</div>
              <div class="account-number">Cuenta •••• <?php echo substr($cuenta['numero_cuenta'], -4); ?></div>
            </div>
          </div>
          <div class="subtle">Válida <?php echo $cuenta['expira']; ?></div>
        </div>
        <div>
          <div class="subtle">Saldo disponible</div>
          <div class="balance">L <?php echo number_format($cuenta['saldo'],2); ?></div>
        </div>
        <div class="more-info">
          <div class="info-item">Tipo: <?php echo $cuenta['tipo']; ?></div>
          <div class="info-item">Moneda: L</div>
        </div>
      </div>
    <?php endwhile; ?>
  </main>
</body>
</html>
