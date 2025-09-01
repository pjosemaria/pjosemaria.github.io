<?php
session_start();
require 'conexion.php';

if(!isset($_SESSION['usuario_id'])){
    header("Location: index.html");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$nombre = $_SESSION['nombre'];
$handle = $_SESSION['usuario'];
$iniciales = $_SESSION['iniciales'];




// Traer cuentas del usuario desde la base de datos
$stmt = $conn->prepare("SELECT tipo, numero, saldo FROM cuentas WHERE usuario_id=?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$cuentas = [];
while($row = $result->fetch_assoc()){
    $cuentas[] = $row;
}

?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Caja Virtual - Cliente</title>
  <style>
    :root{
      --header:#facc15; 
      --bg:#ffffff; 
      --card:#2e7e32; 
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
    .logo{
      display:flex;
      align-items:center;
      gap:10px;
      font-weight:700;
      font-size:18px;
    }
    .logo img{height:40px;width:auto}

    .home-btn{
      background:#2e7e32;
      color:white;
      border:none;
      border-radius:50%;
      width:40px;
      height:40px;
      display:flex;
      align-items:center;
      justify-content:center;
      cursor:pointer;
      box-shadow:0 4px 10px rgba(0,0,0,0.2);
    }
    .home-btn svg{width:20px;height:20px}

    main{padding:24px}

    .profile-row{display:flex;align-items:center;gap:12px;margin-bottom:18px}
    .avatar{
      width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#c7d2fe,#a7f3d0);display:flex;align-items:center;justify-content:center;font-weight:700;color:#0b1220;box-shadow:0 2px 6px rgba(12,18,30,0.08)
    }
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

    .bank-card {
  position: relative; /* necesario para colocar el botón en la card */
}

.card-footer {
  display: flex;
  justify-content: flex-end;
  margin-top: 12px;
}

.btn-transacciones {
  background: #ffffff;
  color: #2e7e32;
  border: 1px solid #2e7e32;
  border-radius: 8px;
  padding: 6px 10px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-transacciones:hover {
  background: #2e7e32;
  color: white;
}
  </style>
</head>
<body>
  <header>
    <div class="logo">
      <img src="assets/LOGO CARACAS.png" alt="Logo" />
    </div>
    <button class="home-btn" title="Regresar a inicio" onclick="window.location.href='index.html'">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M3 12l9-9 9 9"></path>
        <path d="M9 21V9h6v12"></path>
      </svg>
    </button>
  </header>

  <main>
    <!-- Perfil del usuario -->
    <div class="profile-row">
      <div class="avatar"><?php echo $iniciales; ?></div>
      <div class="user-info">
        <div class="user-name"><?php echo $nombre; ?></div>
        <div class="user-handle">@<?php echo $handle; ?></div>
      </div>
    </div>

    <!-- Cards de cuentas dinámicas -->
    <?php foreach($cuentas as $cuenta): ?>
      <div class="bank-card">
        <div class="bank-header">
          <div class="bank-brand">
            <div class="chip">•••</div>
            <div>
              <div class="bank-name">Banco Amate</div>
             <div class="account-number">Cuenta <?php echo $cuenta['numero']; ?></div>

            </div>
          </div>
          <div class="subtle">Válida 12/28</div>
        </div>

        <div>
          <div class="subtle">Saldo disponible</div>
          <div class="balance">L <?php echo number_format($cuenta['saldo'], 2); ?></div>
        </div>

        <div class="more-info">
          <div class="info-item">Tipo: <?php echo $cuenta['tipo']; ?></div>
          <div class="info-item">Moneda: L</div>
        </div>
        <!-- Botón para ver transacciones -->
    <div class="card-footer">
      <button class="btn-transacciones" onclick="window.location.href='transacciones.php?cuenta=<?php echo $cuenta['numero']; ?>'">
        Ver transacciones
      </button>
    </div>
      </div>
    <?php endforeach; ?>
  </main>
</body>
</html>


