<?php
require_once './db/db.php';
session_start();

// Handle soft delete
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    
    // Mark as deleted by setting age = -1
    $sql = "UPDATE students_info SET age = -1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: table.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}

// Fetch students who are not deleted
$sql = "SELECT id, first_name, last_name, age, gender FROM students_info WHERE age != -1";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Informations</title>
</head>
<body class="bg-gray-900 text-white flex flex-col items-center py-10 min-h-screen">

<h1 class="text-3xl font-bold mb-6">Student List</h1>
<a href="signup.php" class="mr-[48rem] bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition mb-4">Add Student</a>

<div class="overflow-x-auto w-full max-w-4xl">
    <table class="w-full bg-gray-800 shadow-lg rounded-lg">
        <thead>
            <tr class="bg-gray-700 text-white uppercase text-sm">
                <th class="py-3 px-4 text-center">ID</th>
                <th class="py-3 px-4 text-center">First Name</th>
                <th class="py-3 px-4 text-center">Last Name</th>
                <th class="py-3 px-4 text-center">Age</th>
                <th class="py-3 px-4 text-center">Gender</th>
                <th class="py-3 px-4 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php while ($row = $result->fetch_assoc()): ?>  
            <tr class="border-b border-gray-700 hover:bg-gray-700 transition">
                <td class="py-3 px-4"><?= htmlspecialchars($row['id']) ?></td>
                <td class="py-3 px-4"><?= htmlspecialchars($row['first_name']) ?></td>
                <td class="py-3 px-4"><?= htmlspecialchars($row['last_name']) ?></td>
                <td class="py-3 px-4"><?= htmlspecialchars($row['age']) ?></td>
                <td class="py-3 px-4"><?= htmlspecialchars($row['gender']) ?></td>
                <td class="py-3 px-4">
                    <a href="update.php?id=<?= $row['id'] ?>" class="bg-blue-500 px-4 py-2 rounded-lg text-white hover:bg-blue-700">Edit</a>
                    <button onclick="openModal(<?= $row['id'] ?>)" class="bg-red-500 px-4 py-2 rounded-lg text-white hover:bg-red-700">Delete</button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<section class="text-gray-200 text-xl mt-6">
    Don't have an account yet?
        <a href="signup.php" class="text-blue-500 hover:underline">
        SignUp
        </a>
</section>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-red-700 p-6 rounded-lg text-center text-white">
        <h2 class="text-xl mb-4 font-bold">Confirm Delete</h2>
        <p class="mb-4">Are you sure you want to delete this student?</p>
        <div class="flex justify-center gap-4">
            <a id="confirm-delete" href="#" class="bg-white text-red-700 px-4 py-2 rounded-lg font-bold hover:bg-gray-300">Yes, Delete</a>
            <button onclick="closeModal()" class="bg-gray-300 text-black px-4 py-2 rounded-lg font-bold hover:bg-gray-400">Cancel</button>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById('confirm-delete').href = 'table.php?delete_id=' + id;
        document.getElementById('delete-modal').classList.remove('hidden');
    }
    
    function closeModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }
</script>
<script src="https://cdn.tailwindcss.com"></script>
</body>
</html>
