<?php
// delete.php?id=1 – delete a contact

require_once 'dbConnect.php';

$id = $_GET['id'] ?? '';
if (!ctype_digit($id)) {
    die("Invalid ID.");
}

$stmt = $conn->prepare("DELETE FROM contacts WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

$conn->close();

header("Location: index.php");
exit;
?>