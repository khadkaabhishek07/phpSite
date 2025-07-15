
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Photography Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Toastify CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

<!-- Toastify JS -->
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <style>
        /* Custom styles can go here */
    </style>
</head>
<body class="bg-gray-100">

    <!-- Hero Section -->
            <header class="relative bg-cover bg-center min-h-screen flex flex-col" style="background-image: url('https://images.pexels.com/photos/2060240/pexels-photo-2060240.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2');">
    <div class="flex items-center justify-center flex-grow bg-black bg-opacity-50">
        <div class="text-center text-white px-4">
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold">BANDOBASTA PHOTOBOX</h1>
            <p class="mt-4 text-lg">Capturing Life’s Most Precious Moments</p>
            <a href="https://www.instagram.com/photosboxstudio/" class="mt-6 inline-block px-6 py-3 bg-yellow-500 text-black font-semibold rounded">View Portfolio</a>
        </div>
    </div>
</header>

        <!-- Portfolio Showcase -->
    <section id="portfolio" class="py-20 bg-gray-200">
        <div class="container mx-auto text-center">
            <h2 class="text-4xl font-bold mb-10">Portfolio Showcase</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Image Gallery -->
                <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                    <img src="https://images.pexels.com/photos/14743768/pexels-photo-14743768.jpeg?
                    auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" alt="Wedding Photo" class="w-full h-64 object-cover">
                    <div class="p-4">
                        <h3 class="font-semibold">Weddings</h3>
                    </div>
                </div>
                <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                    <img src="https://images.pexels.com/photos/14608917/pexels-photo-14608917.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" alt="Reception Photo" class="w-full h-64 object-cover">
                    <div class="p-4">
                        <h3 class="font-semibold">Receptions</h3>
                    </div>
                </div>
                <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                    <img src="Anniversary.jpg" alt="Anniversary Photo" class="w-full h-64 object-cover">
                    <div class="p-4">
                        <h3 class="font-semibold">Anniversaries</h3>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                <!-- Image Gallery -->
                <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                    <img src="mehendi.jpg" alt="Mehenddi" class="w-full h-64 object-cover">
                    <div class="p-4">
                        <h3 class="font-semibold">Mehendi</h3>
                    </div>
                </div>
                <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                    <img src="weaning.jpg" alt="Reception Photo" class="w-full h-64 object-cover">
                    <div class="p-4">
                        <h3 class="font-semibold">Annaprasna</h3>
                    </div>
                </div>
                <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                    <img src ="receptionphoto.jpg" alt="Anniversary Photo" class="w-full h-64 object-cover">                    <div class="p-4">
                        <h3 class="font-semibold">Any other Events</h3>
                    </div>
                </div>
            </div>
            <a href="https://www.facebook.com/fotoboxnepal/" class="mt-6 inline-block px-6 py-3 bg-yellow-500 text-black font-semibold rounded">Explore Full Portfolio →</a>
        </div>
    </section>

<section id="services" class="relative isolate bg-white px-6 py-24 sm:py-32 lg:px-8">
  <div class="absolute inset-x-0 -top-3 -z-10 transform-gpu overflow-hidden px-36 blur-3xl" aria-hidden="true">
    <div class="mx-auto aspect-1155/678 w-[72.1875rem] bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
  </div>
  <div class="mx-auto max-w-4xl text-center">
    <h2 class="text-4xl font-semibold text-indigo-600">Services & Pricing</h2>
    <p class="mt-2 text-5xl font-semibold tracking-tight text-gray-900 sm:text-6xl">Choose the right package for your needs</p>
  </div>
  <p class="mx-auto mt-6 max-w-2xl text-center text-lg font-medium text-gray-600 sm:text-xl">Select a plan that's designed to elevate your photography and video experience while fitting your budget.</p>
  

          <div class="container mx-auto p-6">
            <h1 class="text-3xl font-bold text-center mb-6">Explore our Packages</h1>
        
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($packages as $package) { ?>
                <div class="bg-white shadow-lg rounded-lg p-4">
                    <img src="<?= $package['image_url'] ?: 'default.png' ?>" class="w-full h-48 object-cover rounded-lg">
                    <h2 class="text-xl font-bold mt-2"><?= htmlspecialchars($package['name']) ?></h2>
                    <p class="text-gray-600"><?= htmlspecialchars($package['description']) ?></p>
                    <p class="text-green-500 font-bold">Rs <?= number_format($package['price'] ?? 0, 2) ?></p>
        
                    <!-- Button to trigger the modal -->
                    <button onclick="openModal(<?= $package['id'] ?>)" class="w-full bg-indigo-600 text-white py-2 rounded-lg mt-4">Request Package</button>
        
                    <!-- Modal for each package -->
                    <div id="modal-<?= $package['id'] ?>" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
                        <div class="bg-white rounded-lg w-full max-w-md p-6">
                            <div class="flex justify-between items-center">
                                <h3 class="text-xl font-bold">Request for <?= htmlspecialchars($package['name']) ?></h3>
                                <button onclick="closeModal(<?= $package['id'] ?>)" class="text-gray-500 hover:text-gray-800"><i class="fa fa-close"></i></button>
                            </div>
                            <form action="submit_request.php" method="POST" class="mt-4">
                                <input type="hidden" name="package_id" value="<?= $package['id'] ?>">
                                <input type="hidden" name="package_name" value="<?= htmlspecialchars($package['name']) ?>">
                            
                                <!-- Name Input -->
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-semibold text-gray-700">Your Name</label>
                                    <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                                </div>
                            
                                <!-- Phone Input -->
                                <div class="mb-4">
                                    <label for="phone" class="block text-sm font-semibold text-gray-700">Enter your phone number:</label>
                                    <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                                </div>
                            
                                <!-- Event Date -->
                                <div class="mb-4">
                                    <label for="event_date" class="block text-sm font-semibold text-gray-700">Select Event Date:</label>
                                    <input type="date" id="event_date" name="event_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required min="<?= date('Y-m-d'); ?>">
                                </div>
                            
                                <!-- Message Input -->
                                <div class="mb-4">
                                    <label for="message" class="block text-sm font-semibold text-gray-700">Your Message</label>
                                    <textarea id="message" name="message" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required></textarea>
                                </div>
                            
                                <!-- Submit Button -->
                                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg">Submit Request</button>
                            </form>

                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

</div>
</section>



    <!-- Footer -->
    <footer class="py-10 bg-gray-800 text-white">
        <div class="container mx-auto text-center">
            <div class="flex justify-center space-x-6 mb-4">
                <a href="#services" class="hover:text-yellow-500">Services</a>
                <a href="#portfolio" class="hover:text-yellow-500">Portfolio</a>
                <a href="#contact" class="hover:text-yellow-500">Contact</a>
            </div>
            <div class="mb-4">
                <p>Follow Me:</p>
                <div class="flex justify-center space-x-4">
                    <a href="#" class="hover:text-yellow-500">Instagram</a>
                    <a href="#" class="hover:text-yellow-500">Pinterest</a>
                    <a href="#" class="hover:text-yellow-500">TikTok</a>
                </div>
            </div>
            <p class="text-sm">© 2025 Bandobasta. Proudly serving Nepal.</p>
        </div>
    </footer>
<script>
    // Function to open the modal
    function openModal(packageId) {
        const modal = document.getElementById(`modal-${packageId}`);
        modal.classList.remove('hidden');
    }

    // Function to close the modal
    function closeModal(packageId) {
        const modal = document.getElementById(`modal-${packageId}`);
        modal.classList.add('hidden');
    }
</script>
<script>
    // Check for status in the URL query
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    const errorMessage = urlParams.get('message');

    // Show success toast
    if (status === 'success') {
        Toastify({
            text: "Your request has been successfully submitted!",
            backgroundColor: "green",
            close: true,
            gravity: "top",
            position: "right",
            duration: 3000
        }).showToast();
    }
    
    // Show error toast
    if (status === 'error') {
        Toastify({
            text: `Error: ${errorMessage || "An error occurred while sending the request."}`,
            backgroundColor: "red",
            close: true,
            gravity: "top",
            position: "right",
            duration: 3000
        }).showToast();
    }
</script>


</body>
</html>