<?php
require_once __DIR__ . '/firebase_config.php';

$dbRef = $database->getReference('popmart_items');

// --- DELETE DATA ---
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['key'])) {
    $dbRef->getChild($_GET['key'])->remove();
    header('Location: index.php');
    exit;
}

// --- CREATE & UPDATE DATA ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $key = $_POST['keyId'] ?? '';
    $payload = [
        'nama'    => $_POST['nama'],
        'seri'    => $_POST['seri'],
        'kondisi' => $_POST['kondisi'],
        'harga'   => (int)$_POST['harga'],
        'stok'    => (int)$_POST['stok']
    ];

    if (!empty($key)) {
        $dbRef->getChild($key)->update($payload);
    } else {
        $dbRef->push($payload);
    }

    header('Location: index.php');
    exit;
}

// --- READ DATA ---
$snapshot = $dbRef->getSnapshot();
$items = $snapshot->getValue() ?? [];

$editData = null;
$editKey = '';
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['key'])) {
    $editKey = $_GET['key'];
    $editData = $dbRef->getChild($editKey)->getValue();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PopMart VIP Inventory (PHP)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --sage-dark: #1b2e24;
      --sage-primary: #2d4a3e;
      --sage-accent: #52796f;
    }
    body {
      background: linear-gradient(135deg, #111e17 0%, #2d4a3e 50%, #1b2e24 100%);
      min-height: 100vh;
      color: #333;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .main-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 16px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
    }
    .btn-sage {
      background-color: var(--sage-primary);
      color: #fff;
      border: none;
    }
    .btn-sage:hover {
      background-color: var(--sage-dark);
      color: #fff;
    }
    .table-custom-dark {
      background-color: var(--sage-dark) !important;
      color: #ffffff !important;
    }
    .badge-seri {
      background-color: var(--sage-accent);
      color: #ffffff;
    }
  </style>
</head>
<body class="p-3 p-md-5">

  <div class="container main-card p-4 p-md-5 my-auto" style="max-width: 950px;">
    <div class="text-center mb-4">
      <h2 class="fw-bold" style="color: var(--sage-dark);">POPMART VIP</h2>
      <p class="text-muted small">Inventory Management System (PHP Server-Side)</p>
    </div>
    
    <!-- FORM INPUT -->
    <form action="index.php" method="POST" class="row g-3">
      <input type="hidden" name="keyId" value="<?= htmlspecialchars($editKey) ?>">
      
      <div class="col-md-6">
        <label class="form-label fw-semibold">Figure Name</label>
        <input type="text" name="nama" class="form-control" placeholder="e.g. Skullpanda The Warmth" value="<?= htmlspecialchars($editData['nama'] ?? '') ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">IP Series</label>
        <select name="seri" class="form-select" required>
          <?php 
            $options = ['Skullpanda', 'The Monsters (Labubu)', 'Space Molly', 'Dimoo', 'Hirono'];
            foreach ($options as $opt) {
              $selected = ($editData['seri'] ?? '') === $opt ? 'selected' : '';
              echo "<option value='$opt' $selected>$opt</option>";
            }
          ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold">Condition</label>
        <select name="kondisi" class="form-select">
          <?php 
            $conds = ['Sealed Box', 'Opened Box (Foil Sealed)', 'Unboxed'];
            foreach ($conds as $c) {
              $selected = ($editData['kondisi'] ?? '') === $c ? 'selected' : '';
              echo "<option value='$c' $selected>$c</option>";
            }
          ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold">Price (IDR)</label>
        <input type="number" name="harga" class="form-control" placeholder="285000" value="<?= htmlspecialchars($editData['harga'] ?? '') ?>" required>
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold">Stock</label>
        <input type="number" name="stok" class="form-control" placeholder="3" value="<?= htmlspecialchars($editData['stok'] ?? '') ?>" required>
      </div>
      <div class="col-12 text-end mt-4">
        <a href="index.php" class="btn btn-outline-secondary me-2">Reset</a>
        <button type="submit" class="btn btn-sage px-4"><?= $editData ? 'Update Item' : 'Save Item' ?></button>
      </div>
    </form>

    <hr class="my-4 text-secondary">

    <!-- DATA TABLE -->
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-custom-dark">
          <tr>
            <th>Figure Name</th>
            <th>IP Series</th>
            <th>Condition</th>
            <th>Price</th>
            <th>Stock</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($items)): ?>
            <?php foreach ($items as $key => $item): ?>
              <tr>
                <td class="fw-bold text-dark"><?= htmlspecialchars($item['nama']) ?></td>
                <td><span class="badge badge-seri"><?= htmlspecialchars($item['seri']) ?></span></td>
                <td><small class="text-muted"><?= htmlspecialchars($item['kondisi']) ?></small></td>
                <td class="fw-semibold">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                <td><?= htmlspecialchars($item['stok']) ?></td>
                <td class="text-end">
                  <a href="index.php?action=edit&key=<?= $key ?>" class="btn btn-sm btn-outline-warning me-1">Edit</a>
                  <a href="index.php?action=delete&key=<?= $key ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6" class="text-center text-muted py-3">No data available. Please fill out the form above.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>