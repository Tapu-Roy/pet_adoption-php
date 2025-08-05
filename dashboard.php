<?php
// db connection
$conn = new mysqli("localhost", "root", "", "petadoption");

$success = '';
$error = '';
$editPet = null; // pet data for editing

// Handle Add Pet
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_pet'])) {
    $name = trim($_POST['name']);
    $type = trim($_POST['type']);
    $age = intval($_POST['age']);
    $photo = trim($_POST['photo']);

    if ($name === '') {
        $error = "Pet name is required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO pets (name, type, age, photo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $name, $type, $age, $photo);
        if ($stmt->execute()) {
            $success = "Pet added successfully!";
        } else {
            $error = "Failed to add pet.";
        }
        $stmt->close();
    }
}

// Handle Delete Pet
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM pets WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $success = "Pet deleted successfully!";
    } else {
        $error = "Failed to delete pet.";
    }
    $stmt->close();
}

// Handle Edit Pet - Show form with current data
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM pets WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $editPet = $result->fetch_assoc();
    $stmt->close();
}

// Handle Update Pet
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_pet'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $type = trim($_POST['type']);
    $age = intval($_POST['age']);
    $photo = trim($_POST['photo']);

    if ($name === '') {
        $error = "Pet name is required.";
    } else {
        $stmt = $conn->prepare("UPDATE pets SET name = ?, type = ?, age = ?, photo = ? WHERE id = ?");
        $stmt->bind_param("ssisi", $name, $type, $age, $photo, $id);
        if ($stmt->execute()) {
            $success = "Pet updated successfully!";
            $editPet = null; // reset edit form
        } else {
            $error = "Failed to update pet.";
        }
        $stmt->close();
    }
}

// Handle Search Pets safely using prepared statements
$search = $_GET['search'] ?? '';
$pets = [];
if ($search !== '') {
    $search_param = "%$search%";
    $stmt = $conn->prepare("SELECT * FROM pets WHERE name LIKE ? ORDER BY id DESC");
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $pets[] = $row;
    }
    $stmt->close();
} else {
    // Fetch all pets if no search term
    $result = $conn->query("SELECT * FROM pets ORDER BY id DESC");
    while ($row = $result->fetch_assoc()) {
        $pets[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Pet Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
<?php include('includes/header.php'); ?>

<div class="max-w-5xl mx-auto bg-white p-6 rounded shadow mt-5">
    <h1 class="text-3xl font-bold mb-6 text-center">Pet Dashboard</h1>

    <?php if ($success): ?>
      <p class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
      <p class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- Add or Edit Pet Form -->
    <form method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <input name="name" placeholder="Pet Name" required
        class="p-2 border rounded"
        value="<?= htmlspecialchars($editPet['name'] ?? '') ?>"
      />
      <input name="type" placeholder="Pet Type"
        class="p-2 border rounded"
        value="<?= htmlspecialchars($editPet['type'] ?? '') ?>"
      />
      <input name="age" type="number" placeholder="Age"
        class="p-2 border rounded"
        value="<?= htmlspecialchars($editPet['age'] ?? '') ?>"
      />
      <input name="photo" placeholder="Photo URL"
        class="p-2 border rounded"
        value="<?= htmlspecialchars($editPet['photo'] ?? '') ?>"
      />

      <?php if ($editPet): ?>
        <input type="hidden" name="id" value="<?= intval($editPet['id']) ?>" />
        <button type="submit" name="update_pet"
          class="bg-yellow-500 text-white py-2 px-4 rounded col-span-4 hover:bg-yellow-600 transition"
        >Update Pet</button>
        <a href="dashboard.php" class="text-center col-span-4 text-blue-600 hover:underline mt-1">Cancel Edit</a>
      <?php else: ?>
        <button type="submit" name="add_pet"
          class="bg-blue-500 text-white py-2 px-4 rounded col-span-4 hover:bg-blue-600 transition"
        >Add Pet</button>
      <?php endif; ?>
    </form>

    <!-- Search -->
    <form method="GET" class="mb-6 max-w-md mx-auto">
      <input
        name="search"
        value="<?= htmlspecialchars($search) ?>"
        placeholder="Search pets by name..."
        class="p-2 border rounded w-full"
      />
    </form>

    <!-- Pet List -->
    <div class="overflow-x-auto">
      <table class="w-full border border-gray-300">
        <thead>
          <tr class="bg-gray-200 text-left">
            <th class="p-2">Photo</th>
            <th class="p-2">Name</th>
            <th class="p-2">Type</th>
            <th class="p-2">Age</th>
            <th class="p-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($pets) === 0): ?>
            <tr>
              <td colspan="5" class="p-4 text-center text-gray-500">No pets found.</td>
            </tr>
          <?php endif; ?>

          <?php foreach ($pets as $row): ?>
            <tr class="border-t hover:bg-gray-50">
              <td class="p-2">
                <?php if ($row['photo']): ?>
                  <img
                    src="<?= htmlspecialchars($row['photo']) ?>"
                    alt="Photo"
                    class="w-16 h-16 object-cover rounded"
                    onerror="this.src='https://via.placeholder.com/64?text=No+Image';"
                  />
                <?php else: ?>
                  <div class="w-16 h-16 bg-gray-300 flex items-center justify-center rounded text-gray-500 text-xs">No Image</div>
                <?php endif; ?>
              </td>
              <td class="p-2"><?= htmlspecialchars($row['name']) ?></td>
              <td class="p-2"><?= htmlspecialchars($row['type']) ?></td>
              <td class="p-2"><?= htmlspecialchars($row['age']) ?></td>
              <td class="p-2 space-x-2">
                <a href="dashboard.php?edit=<?= intval($row['id']) ?>"
                   class="text-yellow-600 hover:underline">Edit</a>
                <a href="dashboard.php?delete=<?= intval($row['id']) ?>"
                   class="text-red-600 hover:underline"
                   onclick="return confirm('Are you sure you want to delete this pet?')">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
