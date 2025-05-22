<?php
// index.php – display a list of contacts

require_once 'dbConnect.php';

$sql = "SELECT id, name, email, phone FROM contacts ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center">
    <div class="w-full max-w-3xl mt-10 bg-white rounded-xl shadow-lg p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Contact Manager</h1>
            <a href="add.php" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Add New Contact</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase bg-gray-100">ID</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase bg-gray-100">Name</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase bg-gray-100">Email</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase bg-gray-100">Phone</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase bg-gray-100">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="px-4 py-3"><?=htmlspecialchars($row['id'])?></td>
                            <td class="px-4 py-3"><?=htmlspecialchars($row['name'])?></td>
                            <td class="px-4 py-3"><?=htmlspecialchars($row['email'])?></td>
                            <td class="px-4 py-3"><?=htmlspecialchars($row['phone'])?></td>
                            <td class="px-4 py-3 flex space-x-2">
                                <a href="edit.php?id=<?=urlencode($row['id'])?>" class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 transition">Edit</a>
                                <a href="delete.php?id=<?=urlencode($row['id'])?>" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition" onclick="return confirm('Are you sure?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">No contacts found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>