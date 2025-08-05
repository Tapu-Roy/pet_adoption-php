<?php
session_start();
include 'includes/db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // Check if all fields are filled
    if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "An account with this email already exists.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $name, $email, $hashed);
            if ($insert->execute()) {
                $success = "Account created successfully. You can now <a href='login.php' class='text-purple-600 underline'>login</a>.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>

<?php include 'includes/header.php'; ?>
<main class="flex flex-col lg:flex-row h-[90vh]">
  <!-- Left Image -->
  <div class="w-full lg:w-1/2">
    <img
      src="https://images.unsplash.com/photo-1574158622682-e40e69881006?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
      alt="Pets"
      class="w-full h-full object-cover"
    />
  </div>

  <!-- Form -->
  <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
    <div class="w-full max-w-md">
      <h1 class="text-3xl font-bold mb-6 text-center">Create an Account</h1>
      <?php if ($error): ?>
        <p class="text-red-600 text-center mb-4"><?= $error ?></p>
      <?php elseif ($success): ?>
        <p class="text-green-600 text-center mb-4"><?= $success ?></p>
      <?php endif; ?>
      <form method="POST" class="space-y-4">
        <div>
          <label class="block mb-1 font-semibold">Full Name</label>
          <input
            type="text"
            name="fullname"
            class="w-full border px-3 py-2 rounded focus:ring-2 focus:ring-purple-400"
            required
          />
        </div>
        <div>
          <label class="block mb-1 font-semibold">Email</label>
          <input
            type="email"
            name="email"
            class="w-full border px-3 py-2 rounded focus:ring-2 focus:ring-purple-400"
            required
          />
        </div>
        <div>
          <label class="block mb-1 font-semibold">Password</label>
          <input
            type="password"
            name="password"
            class="w-full border px-3 py-2 rounded focus:ring-2 focus:ring-purple-400"
            required
          />
        </div>
        <div>
          <label class="block mb-1 font-semibold">Confirm Password</label>
          <input
            type="password"
            name="confirm_password"
            class="w-full border px-3 py-2 rounded focus:ring-2 focus:ring-purple-400"
            required
          />
        </div>
        <button
          type="submit"
          class="w-full bg-purple-600 text-white py-2 rounded hover:bg-purple-700"
        >
          Register
        </button>
        <p class="text-center text-gray-600 mt-4">
          Already have an account?
          <a href="login.php" class="text-purple-600 hover:underline">Login</a>
        </p>
      </form>
    </div>
  </div>
</main>
<?php include 'includes/footer.php'; ?>
