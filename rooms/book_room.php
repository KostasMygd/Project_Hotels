<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_id = $_POST['room_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    // Αποθήκευση
    $sql = "INSERT INTO bookings (room_id, customer_name, customer_email, phone, check_in, check_out) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    if($stmt->execute([$room_id, $name, $email, $phone, $check_in, $check_out])) {
        echo "<script>alert('Η κράτηση έγινε επιτυχώς!'); window.location.href='rooms.php';</script>";
    } else {
        echo "Υπήρξε κάποιο πρόβλημα.";
    }
}
?>