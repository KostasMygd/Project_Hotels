<?php
session_start();
require "db.php";

if (!isset($_SESSION['admin_logged_in'])) { header("Location: admin_login.php"); exit; }

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$id]);
$room = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $price = $_POST['price'];
    $desc = $_POST['description'];
    
    // Update query
    $stmt = $pdo->prepare("UPDATE rooms SET price = ?, description = ? WHERE id = ?");
    $stmt->execute([$price, $desc, $id]);
    
    header("Location: admin_panel.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
    <h2>Επεξεργασία Δωματίου <?= $room['room_number'] ?></h2>
    <form method="POST">
        <div class="mb-3">
            <label>Τιμή (€)</label>
            <input type="number" name="price" value="<?= $room['price'] ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label>Περιγραφή</label>
            <textarea name="description" class="form-control" rows="5"><?= $room['description'] ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Ενημέρωση</button>
        <a href="admin_panel.php" class="btn btn-secondary">Ακύρωση</a>
    </form>
	
	<?php include 'footer.php'; ?>

</body>
</html>