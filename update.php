<?php 
require_once './db/db.php';
session_start();

if (!isset($_GET['id'])) {
    die("Invalid request!");
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM students_info WHERE id=$id";
$result = $conn->query($sql);
$student = $result->fetch_assoc();

$updated = false;
$goingBack = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];

    $sql = "UPDATE students_info SET first_name='$first_name', last_name='$last_name', age='$age', gender='$gender' WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        $updated = true;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center h-screen">

<?php if ($updated): ?>
    <div id="successModal" class="fixed top-5 right-5 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg">
        Updated Successfully
    </div>
<?php endif; ?>

<?php if ($goingBack): ?>
    <div id="backModal" class="fixed top-5 right-5 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg">
        Going back to tables
    </div>
<?php endif; ?>

<div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md">
    <h1 class="text-2xl font-bold text-center mb-6">Update Student</h1>
    
    <form method="POST" class="space-y-4">
        <div>
            <label for="first_name" class="block text-sm font-medium">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($student['first_name']) ?>" required class="w-full p-2 rounded bg-gray-700 text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        <div>
            <label for="last_name" class="block text-sm font-medium">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($student['last_name']) ?>" required class="w-full p-2 rounded bg-gray-700 text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        <div>
            <label for="age" class="block text-sm font-medium">Age:</label>
            <input type="number" id="age" name="age" value="<?= htmlspecialchars($student['age']) ?>" required class="w-full p-2 rounded bg-gray-700 text-white border border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium">Gender:</label>
            <div class="flex gap-4">
                <label class="flex items-center gap-2">
                    <input type="radio" id="male" name="gender" value="male" <?= ($student['gender'] === 'male') ? 'checked' : '' ?> required class="form-radio">
                    Male
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" id="female" name="gender" value="female" <?= ($student['gender'] === 'female') ? 'checked' : '' ?> required class="form-radio">
                    Female
                </label>
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">Update</button>
        
        <button type="button" class="w-full bg-black text-white py-2 rounded-lg hover:bg-blue-700 transition" id="cancel">Cancel</button>
    </form>
</div>

<script>
    function hideModal() {
        setTimeout(() => {
            const modal = document.getElementById('successModal');
            if (modal) {
                modal.classList.add('hidden');
            }
            window.location.href = 'table.php';
        }, 1000);
    }

    if (document.getElementById('successModal')) {
        hideModal();
    }

    document.getElementById('cancel').addEventListener('click', function() {
        const backModal = document.createElement('div');
        backModal.id = 'backModal';
        backModal.className = 'fixed top-5 right-5 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg';
        backModal.innerText = 'Going back to tables';
        document.body.appendChild(backModal);
        
        setTimeout(() => {
            window.location.href = 'table.php';
        }, 500);
    });
</script>

</body>
</html>