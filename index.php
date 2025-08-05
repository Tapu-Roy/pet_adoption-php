<?php include('includes/header.php'); ?>

<!-- Hero Section -->
<section class="flex flex-col lg:flex-row items-center lg:space-x-10 px-8 py-12 max-w-7xl mx-auto">
  <img
    src="https://images.unsplash.com/photo-1560807707-8cc77767d783?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
    alt="Woman with dogs"
    class="rounded-lg w-full lg:w-1/2 object-cover mb-6 lg:mb-0"
  />
  <div class="lg:w-1/2">
    <h1 class="text-4xl font-bold mb-4">Unlock the Joy of Pet Parenthood</h1>
    <p class="mb-6">Search adoptable pets all in one place</p>
    <div class="mb-4">
      <span class="font-semibold">Pet Type</span>
      <div class="flex space-x-3 mt-2">
        <button class="bg-purple-700 text-white px-4 py-2 rounded">Dog</button>
        <button class="border px-4 py-2 rounded">Cat</button>
        <button class="border px-4 py-2 rounded">Other</button>
      </div>
    </div>
    <div class="mb-4">
      <label class="block font-semibold mb-1">Breeds</label>
      <select class="w-full border px-3 py-2 rounded focus:ring-2 focus:ring-purple-400">
        <option>Select from menu...</option>
        <option>Labrador Retriever</option>
        <option>German Shepherd</option>
        <option>Golden Retriever</option>
        <option>Bulldog</option>
        <option>Beagle</option>
        <option>Poodle</option>
        <option>Rottweiler</option>
        <option>Yorkshire Terrier</option>
        <option>Dachshund</option>
        <option>Boxer</option>
      </select>
    </div>
    <div class="mb-4">
      <label class="block font-semibold mb-1">Zip/Postal Code</label>
      <input
        type="text"
        placeholder="Enter Zip/Postal Code"
        class="w-full border px-3 py-2 rounded focus:ring-2 focus:ring-purple-400"
      />
    </div>
    <button class="bg-red-600 text-white px-6 py-3 rounded hover:bg-red-700">
      Start Your Search
    </button>
  </div>
</section>

<!-- Step by Step Guide -->
<section class="bg-gray-50 px-8 py-12">
  <div class="max-w-5xl mx-auto">
    <h2 class="text-3xl font-bold mb-8">Start your journey to pet parenthood with our step-by-step guide</h2>
    <div class="space-y-6">
      <!-- Step 1 -->
      <div class="flex items-start space-x-4">
        <div class="bg-purple-100 p-3 px-5 rounded-full">
          <span class="text-purple-700 font-bold text-xl">1</span>
        </div>
        <div>
          <h3 class="text-xl font-semibold">Begin Your Pet Search</h3>
          <p class="text-gray-600">Browse from our vast network of shelters and rescues to find your perfect pet.</p>
        </div>
      </div>
      <!-- Step 2 -->
      <div class="flex items-start space-x-4">
        <div class="bg-purple-100 p-3 px-5 rounded-full">
          <span class="text-purple-700 font-bold text-xl">2</span>
        </div>
        <div>
          <h3 class="text-xl font-semibold">Get Ready to Meet the Pet</h3>
          <p class="text-gray-600">View shelter details directly on the pet profile page and get recommendations for questions to ask at your visit.</p>
        </div>
      </div>
      <!-- Step 3 -->
      <div class="flex items-start space-x-4">
        <div class="bg-purple-100 p-3 px-5 rounded-full">
          <span class="text-purple-700 font-bold text-xl">3</span>
        </div>
        <div>
          <h3 class="text-xl font-semibold">Finalize Your Adoption</h3>
          <p class="text-gray-600">Get ready to bring home your new pet. Use our adoption checklist for tips on caring for your new family member.</p>
        </div>
      </div>
      <button class="bg-red-600 text-white px-6 py-3 rounded hover:bg-red-700">
        View Our Adoption Checklist
      </button>
    </div>
  </div>
</section>

<!-- Adoptable Pets -->
<section class="px-8 py-12">
  <div class="max-w-7xl mx-auto">
    <h2 class="text-3xl font-bold mb-8">Adoptable Pets</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      <!-- Pet cards here -->
      <?php
        $pets = ['MILES', 'SAND', 'MARLIN', 'NATALIE'];
        $descriptions = [
          'Male • Labrador Retriever Mix',
          'Male • American',
          'Male • Chihuahua - Smooth Coat',
          'Female • Domestic Shorthair',
        ];
        $locations = [
          'Los Angeles, CA',
          'Los Angeles, CA',
          'Los Angeles, CA',
          'Los Angeles, CA',
        ];
        $images = [
          'https://images.unsplash.com/photo-1558788353-f76d92427f16?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
          'https://images.unsplash.com/photo-1574158622682-e40e69881006?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
          'https://images.unsplash.com/photo-1543852786-1cf6624b9987?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
          'https://images.unsplash.com/photo-1560807707-8cc77767d783?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        ];

        for ($i = 0; $i < count($pets); $i++):
      ?>
        <div class="bg-white shadow rounded-lg overflow-hidden">
          <img src="<?= $images[$i] ?>" class="w-full h-48 object-cover" alt="<?= $pets[$i] ?>" />
          <div class="p-4">
            <h3 class="font-bold text-lg"><?= $pets[$i] ?></h3>
            <p class="text-gray-600"><?= $descriptions[$i] ?></p>
            <p class="text-gray-500"><?= $locations[$i] ?></p>
            <button
              onclick="window.location.href='adopt.php?pet=<?= $pets[$i] ?>'"
              class="mt-3 w-full bg-purple-600 text-white py-2 rounded hover:bg-purple-700"
            >
              Adopt
            </button>
          </div>
        </div>
      <?php endfor; ?>
    </div>
  </div>
</section>

<?php include('includes/footer.php'); ?>
