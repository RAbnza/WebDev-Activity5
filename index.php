<?php
require_once 'dbConnect.php';

$allowed_columns = ['id', 'name', 'email', 'phone'];
$sort = in_array($_GET['sort'] ?? '', $allowed_columns) ? $_GET['sort'] : 'id';
$order = ($_GET['order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

$next_order = $order === 'asc' ? 'desc' : 'asc';

$sql = "SELECT id, name, email, phone FROM contacts ORDER BY $sort $order";
$result = $conn->query($sql);

// for sorting
function sort_link($col, $current_sort, $current_order, $label) {
    $next_order = ($current_sort === $col && $current_order === 'asc') ? 'desc' : 'asc';
    $caret = '';
    if ($current_sort === $col) {
        $caret = $current_order === 'asc'
            ? ' <svg class="inline w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>'
            : ' <svg class="inline w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
    }
    $url = "?sort=$col&order=$next_order";
    return "<a href=\"$url\" class=\"hover:underline\">$label$caret</a>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Manager</title>
    <link rel="icon" type="image/svg+xml" href='data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="blue"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-2.5 3.6-4.5 8-4.5s8 2 8 4.5v1H4z"/></svg>'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <div class="flex-1 flex flex-col items-center">
        <div class="w-full max-w-screen-xl mt-10 bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20h6M3 20h5v-2a4 4 0 013-3.87M12 4a4 4 0 110 8 4 4 0 010-8z" />
                  </svg>
                  Contact Manager
                </h1>
                <a href="add.php" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Add New Contact</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase bg-gray-100">
                                <?=sort_link('id', $sort, $order, 'ID')?>
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase bg-gray-100">
                                <?=sort_link('name', $sort, $order, 'Name')?>
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase bg-gray-100">
                                <?=sort_link('email', $sort, $order, 'Email')?>
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase bg-gray-100">
                                <?=sort_link('phone', $sort, $order, 'Phone')?>
                            </th>
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
                                    <button 
                                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition delete-btn"
                                        data-id="<?=htmlspecialchars($row['id'])?>"
                                        data-name="<?=htmlspecialchars($row['name'])?>"
                                    >Delete</button>
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
        <div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
            <div class="bg-white rounded-lg p-8 max-w-md w-full shadow-xl">
                <h2 class="text-2xl font-bold mb-4 text-red-600">Confirm Delete</h2>
                <p class="mb-6" id="deleteMessage">Are you sure you want to delete this contact?</p>
                <div class="flex justify-end space-x-4">
                    <button id="cancelDelete" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Cancel</button>
                    <a id="confirmDelete" href="#" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</a>
                </div>
            </div>
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
    <script>
        let deleteModal = document.getElementById('deleteModal');
        let confirmDelete = document.getElementById('confirmDelete');
        let cancelDelete = document.getElementById('cancelDelete');
        let deleteMessage = document.getElementById('deleteMessage');

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Set the delete URL and the message
                confirmDelete.href = "delete.php?id=" + btn.dataset.id;
                deleteMessage.textContent = `Are you sure you want to delete "${btn.dataset.name}"?`;
                deleteModal.classList.remove('hidden');
            });
        });

        cancelDelete.addEventListener('click', function() {
            deleteModal.classList.add('hidden');
        });

        // Optional: Close modal when clicking outside of it
        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                deleteModal.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>