<?php
session_start();
require "db.php";

// Έλεγχος αν είναι συνδεδεμένος ο Admin
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// ---------------------------------------------------
// LOGIC: ΔΙΑΓΡΑΦΗ ΔΩΜΑΤΙΟΥ
// ---------------------------------------------------
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    
    // Προαιρετικά: Διαγραφή και της εικόνας από τον φάκελο
    $stmt = $pdo->prepare("SELECT image FROM rooms WHERE id = ?");
    $stmt->execute([$id]);
    $roomDel = $stmt->fetch();
    if($roomDel && file_exists($roomDel['image'])) {
        unlink($roomDel['image']); // Διαγράφει το αρχείο εικόνας
    }

    $pdo->prepare("DELETE FROM rooms WHERE id = ?")->execute([$id]);
    header("Location: admin_panel.php");
    exit;
}

// ---------------------------------------------------
// LOGIC: ΠΡΟΣΘΗΚΗ ΝΕΟΥ ΔΩΜΑΤΙΟΥ
// ---------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_room'])) {
    $number = $_POST['room_number'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $bed = $_POST['bed'];
    $rating = $_POST['rating'];
    $desc = $_POST['description'];
    
    // --- ΡΥΘΜΙΣΗ ΦΑΚΕΛΟΥ ΕΙΚΟΝΩΝ ---
    // Αφού είμαστε στο htdocs/rooms/, ο φάκελος είναι απλά "roomPics/"
    $target_dir = "roomPics/";
    
    // Έλεγχος αν υπάρχει ο φάκελος, αν όχι τον δημιουργούμε
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $filename = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $filename;
    $uploadOk = 1;
    
    // Έλεγχος τύπου αρχείου (προαιρετικό αλλά καλό)
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $valid_extensions = array("jpg", "jpeg", "png", "gif", "webp");

    if(in_array($imageFileType, $valid_extensions)) {
        // Προσπάθεια μεταφόρτωσης
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Επιτυχία Upload -> Εγγραφή στη Βάση
            $sql = "INSERT INTO rooms (room_number, type, price, bed, description, image, rating) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            
            if($stmt->execute([$number, $type, $price, $bed, $desc, $target_file, $rating])) {
                $msg = "Το δωμάτιο και η φωτογραφία προστέθηκαν επιτυχώς!";
                $alert_type = "success";
            } else {
                $msg = "Σφάλμα βάσης δεδομένων.";
                $alert_type = "danger";
            }
        } else {
            $msg = "Σφάλμα: Η εικόνα δεν μπόρεσε να μεταφερθεί στον φάκελο 'roomPics'. Ελέγξτε τα δικαιώματα.";
            $alert_type = "danger";
        }
    } else {
        $msg = "Σφάλμα: Επιτρέπονται μόνο αρχεία JPG, JPEG, PNG, GIF, WEBP.";
        $alert_type = "warning";
    }
}

// FETCH DATA
$bookings = $pdo->query("SELECT * FROM bookings ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$rooms = $pdo->query("SELECT * FROM rooms")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<nav class="navbar navbar-dark bg-dark p-3">
    <a class="navbar-brand" href="#">Admin Panel</a>
    <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
</nav>

<div class="container mt-4">

    <?php if(isset($msg)): ?>
        <div class="alert alert-<?= $alert_type ?> alert-dismissible fade show">
            <?= $msg ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#bookings">Κρατήσεις</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#rooms">Δωμάτια & Επεξεργασία</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#add">Προσθήκη Δωματίου</button></li>
    </ul>

    <div class="tab-content p-3 border border-top-0">
        
        <div class="tab-pane fade show active" id="bookings">
            <h3>Τρέχουσες Κρατήσεις</h3>
            <table class="table table-striped">
                <thead>
                    <tr><th>Room ID</th><th>Όνομα</th><th>Τηλέφωνο</th><th>Check-In</th><th>Check-Out</th></tr>
                </thead>
                <tbody>
                <?php foreach($bookings as $b): ?>
                <tr>
                    <td><?= htmlspecialchars($b['room_id']) ?></td>
                    <td><?= htmlspecialchars($b['customer_name']) ?></td>
                    <td><?= htmlspecialchars($b['phone']) ?></td>
                    <td><?= htmlspecialchars($b['check_in']) ?></td>
                    <td><?= htmlspecialchars($b['check_out']) ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="rooms">
            <h3>Διαχείριση Δωματίων</h3>
            <table class="table align-middle">
                <thead>
                    <tr><th>Εικόνα</th><th>Αρ.</th><th>Τύπος</th><th>Rating</th><th>Τιμή</th><th>Ενέργειες</th></tr>
                </thead>
                <tbody>
                <?php foreach($rooms as $r): ?>
                <tr>
                    <td>
                        <img src="<?= htmlspecialchars($r['image']) ?>" width="80" class="rounded" 
                             onerror="this.onerror=null; this.src='https://via.placeholder.com/80?text=No+Img';">
                    </td>
                    <td><?= htmlspecialchars($r['room_number']) ?></td>
                    <td><?= htmlspecialchars($r['type']) ?></td>
                    <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($r['rating']) ?></span></td>
                    <td><?= htmlspecialchars($r['price']) ?>€</td>
                    <td>
                        <a href="edit_room.php?id=<?= $r['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="admin_panel.php?delete_id=<?= $r['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Σίγουρα θέλετε να διαγραφεί;')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="add">
            <h3>Προσθήκη Νέου Δωματίου</h3>
            
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="add_room" value="1">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Αριθμός Δωματίου</label>
                        <input type="text" name="room_number" class="form-control" placeholder="π.χ. 104" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Τύπος Δωματίου</label>
                        <input type="text" name="type" class="form-control" placeholder="π.χ. Suite" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Τιμή (€)</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Κρεβάτι</label>
                        <input type="text" name="bed" class="form-control" placeholder="π.χ. King Bed" required>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label>Βαθμολογία (1-10)</label>
                        <input type="number" step="0.1" max="10" name="rating" class="form-control" placeholder="π.χ. 9.5" required>
                    </div>

                    <div class="col-12 mb-3">
                        <label>Περιγραφή</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="col-12 mb-3">
                        <label>Φωτογραφία:</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Αποθήκευση Δωματίου</button>
            </form>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>


</body>
</html>