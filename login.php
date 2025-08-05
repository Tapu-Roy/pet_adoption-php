<?php
session_start();
include 'includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = "Please enter both email and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res && $res->num_rows === 1) {
                $user = $res->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['name'] = $user['name'];
                    header("Location: /petadoption/dashboard.php");
                    exit();
                } else {
                    $error = "Invalid email or password.";
                }
            } else {
                // Protect against user enumeration by running password_verify on dummy hash
                password_verify($password, password_hash('dummy_password', PASSWORD_DEFAULT));
                $error = "Invalid email or password.";
            }
            $stmt->close();
        } else {
            $error = "Server error. Please try again later.";
        }
    }
}
?>

<?php include 'includes/header.php'; ?>
<main class="flex flex-col lg:flex-row h-[90vh]">
  <div class="w-full lg:w-1/2">
    <img src="https://images.unsplash.com/photo-1574158622682-e40e69881006?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Pets" class="w-full h-full object-cover" />
  </div>
  <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
    <div class="w-full max-w-md">
      <h1 class="text-3xl font-bold mb-6 text-center">Login to PetAdoption</h1>
      <?php if ($error): ?>
        <p class="text-red-600 text-center mb-4"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>
      <form method="POST" class="space-y-4" novalidate>
        <div>
          <label class="block mb-1 font-semibold">Email</label>
          <input type="email" name="email" required class="w-full border px-3 py-2 rounded focus:ring-2 focus:ring-purple-400" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
        </div>
        <div>
          <label class="block mb-1 font-semibold">Password</label>
          <input type="password" name="password" required class="w-full border px-3 py-2 rounded focus:ring-2 focus:ring-purple-400" />
        </div>
        <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded hover:bg-purple-700">Login</button>
        <p class="text-center text-gray-600 mt-4">Don't have an account? <a href="register.php" class="text-purple-600 hover:underline">Register</a></p>
      </form>
    </div>
  </div>
</main>
<?php include 'includes/footer.php'; ?>
