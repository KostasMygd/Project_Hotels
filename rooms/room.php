<?php
require "db.php";

if (!isset($_GET['id'])) {
  die("Room not found");
}

$id = (int) $_GET['id'];

/* δωμάτιο */
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

/* εικόνες */
$stmt = $pdo->prepare("SELECT image FROM room_images WHERE room_id = ?");
$stmt->execute([$id]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(!$images){
	// die("Images not found"); // Σχολίασα αυτό για να μην κρασάρει αν δεν βρει extra εικόνες
}

if (!$room) {
  die("Room not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Room <?= htmlspecialchars($room['room_number']) ?></title>
  <meta charset="utf-8">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="d-flex flex-column min-vh-100">

<div class="p-5 bg-primary text-white text-center">
  <h1>Rooms</h1>
  <p>Find and book your room at the best price</p>
</div>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <div class="container-fluid">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link active" href="http://localhost/rooms/mainPage.html">Main Page</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="http://localhost/rooms/rooms.php">Rooms</a>
      </li>
      
    </ul>
  </div>
</nav>

<div class="container mt-5">
  <div class="row">

    <div class="col-md-6">
      <img src="<?= htmlspecialchars($room['image']) ?>" class="img-fluid rounded">
    </div>

     <div class="col-md-6">
      <h2>Room <?= $room['room_number'] ?></h2>
      <p class="text-muted"><?= nl2br(htmlspecialchars($room['description'])) ?></p>

      <ul>
        <li><?= $room['bed'] ?></li>
        <li>Free WiFi</li>
        <li>Air Conditioning</li>
      </ul>

      <p class="fs-4 text-primary"><?= $room['price'] ?>€ / night</p>

      <button type="button" class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#bookingModal">
        Κράτηση Δωματίου
      </button>

    </div>

  </div>
</div>

<div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Κράτηση: Δωμάτιο <?= $room['room_number'] ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
        <form action="book_room.php" method="POST">
          <input type="hidden" name="room_id" value="<?= $room['id'] ?>">

          <div class="mb-3">
            <label class="form-label">Ονοματεπώνυμο</label>
            <input type="text" name="name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Τηλέφωνο</label>
            <input type="tel" name="phone" class="form-control" required>
          </div>

          <div class="row">
            <div class="col">
              <label class="form-label">Άφιξη (Check-in)</label>
              <input type="date" name="check_in" class="form-control" required min="<?= date('Y-m-d') ?>">
            </div>
            <div class="col">
              <label class="form-label">Αναχώρηση (Check-out)</label>
              <input type="date" name="check_out" class="form-control" required>
            </div>
          </div>

          <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-success">Ολοκλήρωση Κράτησης</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>