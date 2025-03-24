<?php 
//Inluce DATABASE Connection
require_once './db/db.php';
session_start();

//Initialize the modal to false
$signup_success = false;

//Checks if the request method is POST.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //Gets data from the submitted form
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];

    //Stored securely instead of in plaintext.
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
 
    //SQL query to insert into students_info table
    $sql = "INSERT INTO students_info (first_name, last_name, age, gender, password) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("ssiss", $first_name, $last_name, $age, $gender, $hashed_password);

    if ($stmt->execute()) {
        $signup_success = true;
    } else {
        echo "Error: " . $stmt->error;
        exit();
    }

    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center h-screen">

<?php if ($signup_success): ?>
    <div id="success-modal" class="fixed top-4 right-4 bg-green-500 text-white py-2 px-4 rounded shadow-lg">
        Signup Successful!
    </div>
<?php endif; ?>

<div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md">
    <h1 class="text-2xl font-bold text-center mb-6">Sign Up</h1>
    <form method="POST" class="space-y-4">
        <div>
            <label for="first_name" class="block text-sm font-medium">First Name:</label>
            <input type="text" id="first_name" name="first_name" required class="w-full p-2 rounded bg-gray-700 text-white border border-gray-600">
        </div>

        <div>
            <label for="last_name" class="block text-sm font-medium">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required class="w-full p-2 rounded bg-gray-700 text-white border border-gray-600">
        </div>

        <div>
            <label for="age" class="block text-sm font-medium">Age:</label>
            <input type="number" id="age" name="age" required class="w-full p-2 rounded bg-gray-700 text-white border border-gray-600">
        </div>

        <div>
            <label class="block text-sm font-medium">Gender:</label>
            <div class="flex gap-4">
                <label class="flex items-center gap-2">
                    <input type="radio" id="male" name="gender" value="male" required class="form-radio">
                    Male
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" id="female" name="gender" value="female" required class="form-radio">
                    Female
                </label>
            </div>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium">Password:</label>
            <input type="password" id="password" name="password" required class="w-full p-2 rounded bg-gray-700 text-white border border-gray-600">
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-900 transition">Sign Up</button>
    </form>
</div>


<script>
        setTimeout(() => {
            document.getElementById('success-modal').style.display = 'none';
            window.location.href = 'table.php';
        }, 1000);
    </script>
    
</body>
</html>
