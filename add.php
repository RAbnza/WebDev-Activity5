<?php
// add.php – form to add a new contact

require_once 'dbConnect.php';

$name = $email = $phone = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $phone = trim($_POST["phone"] ?? '');

    if ($name == "") $errors[] = "Name is required.";
    if ($email == "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if ($phone == "") $errors[] = "Phone is required.";

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, phone) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $name, $email, $phone);
            $stmt->execute();
            $stmt->close();
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Contact</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center">
    <div class="w-full max-w-lg mt-12 bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Add New Contact</h1>
        <a href="index.php" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Back to List</a>
        <?php if (!empty($errors)): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <ul class="list-disc ml-5"><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul>
            </div>
        <?php endif; ?>
        <form method="post" action="" class="space-y-5">
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Name:</label>
                <input type="text" name="name" value="<?=htmlspecialchars($name)?>" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-200" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Email:</label>
                <input type="email" name="email" value="<?=htmlspecialchars($email)?>" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-200" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Phone:</label>
                <input type="text" name="phone" value="<?=htmlspecialchars($phone)?>" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-200" required>
            </div>
            <div>
                <input type="submit" value="Add Contact" class="w-full px-4 py-2 bg-blue-600 text-white rounded font-semibold hover:bg-blue-700 transition">
            </div>
        </form>
    </div>
</body>
</html>
<?php
$conn->close();
?>