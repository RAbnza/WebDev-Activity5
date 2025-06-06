<?php
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
    <link rel="icon" type="image/svg+xml" href='data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="blue"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-2.5 3.6-4.5 8-4.5s8 2 8 4.5v1H4z"/></svg>'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <div class="flex-1 flex flex-col items-center">
        <div class="w-full max-w-lg mt-12 bg-white rounded-xl shadow-lg p-8">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Add New Contact</h1>
            <a href="index.php" class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition font-semibold">&larr; Back to List</a>
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
    </div>
    <footer class="w-full text-center py-6 bg-gray-100 border-t text-sm text-gray-600 mt-auto flex flex-col items-center">
    <div class="flex flex-wrap justify-center gap-4 items-center">
        <span class="flex items-center gap-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9.001 9.001 0 0112 15c2.042 0 3.918.68 5.388 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        Rendel N. Abainza
        </span>
        <span class="flex items-center gap-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        Contact Manager
        </span>
        <span class="flex items-center gap-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m0 0H5m7 0h7" />
        </svg>
        BSCS 3-3
        </span>
    </div>
    </footer>
</body>
</html>
<?php
$conn->close();
?>