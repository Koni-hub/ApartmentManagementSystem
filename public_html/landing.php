<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Flowbite CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.min.css" rel="stylesheet">
    <title>Shermelle Online Apartment Management System</title>
</head>
<body class="font-sans bg-gray-100 text-gray-900 flex flex-col min-h-screen">

    <header class="bg-white shadow-md sticky top-0 z-50">
        <!-- Navbar -->
        <nav class="bg-white border-gray-200 dark:bg-gray-900">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
                <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Home</span>
                </a>
                <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                    </svg>
                </button>
                <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                    <li>
                    <a href="#apartments" class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-white md:dark:text-blue-500" aria-current="page">Apartments</a>
                    </li>
                    <li>
                    <a href="#reservation" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Inquiry</a>
                    </li>
                    <li>
                    <a href="#contacts" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Contacts</a>
                    </li>
                    <div class="block py-2 px-3 text-gray-900 rounded-lg hover:bg-blue-100 md:hover:bg-transparent md:border-0 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">
                        <select id="filterStatus" class="w-full py-2 pl-4 pr-10 text-gray-900 bg-transparent border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="" disabled selected>Select status</option>
                            <option value="vacant">Vacant</option>
                            <option value="occupied">Occupied</option>
                        </select>
                    </div>

                    <a href="./rentee.php">
                        <button class="block w-full bg-blue-600 text-white px-5 py-1 rounded">
                            Login
                        </button>
                    </a>
                </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="flex-grow">
        <div class="py-8">
            <div class="container mx-auto text-center py-8">
                <h1 class="text-2xl font-bold mb-4">SHERMELLE APARTMENT</h1>
                <!-- <img src="./pictures/sharmelle.png" alt="Apartment Image" class="w-full h-auto max-w-screen-xl mx-auto object-cover" /> -->
            </div>
        </div>
        <section id="apartments" class="py-8">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold mb-8 text-gray-800 text-center">APARTMENTS</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                include('./db_connect.php');

                $sql = "SELECT houses.id, houses.house_no, houses.description, houses.price, houses.NumberOfRooms, houses.roomPrefixName, categories.name as category_name
                        FROM houses
                        JOIN categories ON houses.category_id = categories.id";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $house_id = $row['id'];

                        $sql_prefix = "SELECT roomPrefixName FROM houses WHERE id = ?";
                        $stmt = $conn->prepare($sql_prefix);
                        $stmt->bind_param("i", $house_id);
                        $stmt->execute();
                        $prefix_result = $stmt->get_result();
                        
                        if ($prefix_result->num_rows > 0) {
                            $prefix_row = $prefix_result->fetch_assoc();
                            $prefixName = $prefix_row['roomPrefixName'];

                            $sql_rooms = "SELECT * FROM roomtbl_" . $prefixName;
                            $resultRooms = $conn->query($sql_rooms);

                            $available_count = 0;
                            $occupied_count = 0;

                            $rooms_display = '';
                            $house_status = 'vacant';
                            if ($resultRooms->num_rows > 0) {
                                while ($room = $resultRooms->fetch_assoc()) {

                                    if ($room['room_status'] == 'Occupied') {
                                        $occupied_count++;
                                    } else {
                                        $available_count++;
                                    }
                            
                                    $room_status_color = ($room['room_status'] == 'Occupied') ? 'bg-red-500' : 'bg-green-500';
                                    $rooms_display .= "<p><strong>Room ID:</strong> " . $room['id'] . "<br>";
                                    $rooms_display .= "<strong>Room Name:</strong> " . $room['room_name'] . "<br>";
                                    $rooms_display .= "<strong>Status:</strong> <span class='inline-block w-4 h-4 rounded-full " . $room_status_color . "'></span> " . $room['room_status'] . "</p><br>";
                                }
                                if ($occupied_count > $available_count) {
                                    $house_status = 'occupied';
                                } else {
                                    $house_status = 'vacant';
                                }
                                } else {
                                $rooms_display .= "<p>No rooms found in this category.</p><br>";
                            }
                        } else {
                            $prefixName = null;
                            $rooms_display = "<p>No rooms available.</p><br>";
                        }

                        $image_sql = "SELECT image_path FROM house_images WHERE house_id = ? AND is_primary = 1 LIMIT 1";
                        $image_stmt = $conn->prepare($image_sql);
                        $image_stmt->bind_param("i", $house_id);
                        $image_stmt->execute();
                        $image_result = $image_stmt->get_result();
                        
                        $banner_image = './uploads/default-banner.png';
                        
                        if ($image_result->num_rows > 0) {
                            $image_row = $image_result->fetch_assoc();
                            $banner_image = $image_row['image_path'];
                        }

                        // Display house details
                        echo '<div class="house" data-status="' . $house_status . '" class="bg-white shadow-lg rounded-lg overflow-hidden transition-transform transform hover:scale-105 hover:shadow-xl">';
                        echo '<img id="house-banner-' . $house_id . '" src="' . htmlspecialchars($banner_image) . '" alt="House Banner" class="w-full h-40 object-cover">';
                        echo '<div class="p-4">';
                        echo '<h1 class="text-2xl font-semibold mb-2">' . htmlspecialchars($row['category_name']) . '</h1>';
                        echo '<p class="font-semibold mb-2">' . htmlspecialchars($row['description']) . '</p>';
                        echo '<p class="font-semibold mb-2">Room Count: ' . number_format($row['NumberOfRooms']) . '</p>';
                        echo '<p class="font-semibold mb-2">Room PrefixName: ' . htmlspecialchars($row['roomPrefixName']) . '</p>';
                        echo '<p class="text-xl font-bold text-green-600">' . number_format($row['price'], 2) . ' MONTHLY</p>';
                        echo '<button class="bg-blue-500 text-white px-4 py-2 rounded mt-4" onclick="openModal(' . $house_id . ')">View Rooms</button>';
                        echo '<button class="bg-gray-600 text-white ml-2 px-4 py-2 rounded mt-4" onclick="window.location.href=\'view-house.php?house_id=' . $house_id . '\'">House Info</button>';
                        echo '</div>';
                        echo '</div>';

                        // Modal
                        echo '<div id="modal-' . $house_id . '" class="modal fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 backdrop-blur-md hidden z-50">';
                        echo    '<div class="modal-content bg-white bg-opacity-30 pt-10 pr-12 pb-10 pl-10 rounded-lg max-w-4xl mx-auto transform transition-all duration-300 scale-95 hover:scale-100">';
                        echo        '<button onclick="closeModal(' . $house_id . ')" class="absolute top-1 right-4 text-3xl font-bold text-red-600 hover:text-red-700 focus:outline-none transition-colors duration-300">×</button>';
                        echo        '<h2 class="font-semibold text-2xl text-center text-gray-800 mb-8">Rooms for House ' . htmlspecialchars($row['house_no']) . '</h2>';
                        echo        '<div id="room-details-' . $house_id . '" class="space-y-6">';
                        echo        '<hr>';
                        echo            $rooms_display;
                        echo        '</div>';
                        echo    '</div>';
                        echo '</div>';


                        $image_stmt->close();
                    }
                } else {
                    echo '<h1 class="text-gray-600">No apartments available.</h1>';
                }

                $conn->close();
                ?>

                </div>
            </div>
        </section>

        <section id="reservation" class="py-8 bg-gray-50">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold mb-8 text-gray-800 text-center">Inquiry Form</h2>
                <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg mx-auto">
                    <form id="mailForm" method="POST" action="send_mail.php">
                        <input type="hidden" name="send-mail" value="1">
                        <label for="name" class="block text-gray-700 font-medium mb-2">Name:</label>
                        <input type="text" id="name" name="name" required class="w-full p-2 mb-4 border border-gray-300 rounded-md">

                        <label for="email" class="block text-gray-700 font-medium mb-2">Email:</label>
                        <input type="email" id="email" name="email" required class="w-full p-2 mb-4 border border-gray-300 rounded-md">

                        <label for="phone" class="block text-gray-700 font-medium mb-2">Phone:</label>
                        <input type="tel" id="phone" name="phone" required class="w-full p-2 mb-4 border border-gray-300 rounded-md">

                        <label for="message" class="block text-gray-700 font-medium mb-2">Message:</label>
                        <textarea id="message" name="message" rows="4" required class="w-full p-2 mb-4 border border-gray-300 rounded-md"></textarea>

                        <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Submit</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer id="contacts" class="bg-gray-800 text-white py-6">
        <div class="container mx-auto text-center">
            <h2 class="text-2xl font-bold mb-4">CONTACT US</h2>
            <p><a href="mailto:shermelleapartment@gmail.com" class="text-blue-300 hover:underline">shermelleapartment@gmail.com</a> | Phone: +9325964338</p>
            <p class="mt-4">Getha Rd, Calle SCD Maliwalo Tarlac City</p>
        </div>
    </footer>

    <!-- Modal and JavaScript for handling modal opening and closing -->
    <script>
    function openModal(houseId) {
        document.getElementById("modal-" + houseId).classList.remove("hidden");
    }

    function closeModal(houseId) {
        document.getElementById("modal-" + houseId).classList.add("hidden");
    }
    document.getElementById('filterStatus').addEventListener('change', function () {
        let selectedStatus = this.value;

        let allHouses = document.querySelectorAll('.house');
        allHouses.forEach(house => {
            let houseStatus = house.getAttribute('data-status');
            if (selectedStatus === '' || houseStatus === selectedStatus) {
                house.style.display = 'block';
            } else {
                house.style.display = 'none';
            }
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"></script>
    <!-- SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.getElementById('mailForm').addEventListener('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        console.log('FormData Entries:');
        for (var pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        fetch('send_mail.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: data.message,
                    confirmButtonText: "Save"
                }).then(() => {
                    document.getElementById('mailForm').reset();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: error.message || 'Something went wrong! Try again.'
            });
        });
    });
    </script>
    <script>
    function fetchHouseImages(houseId, element) {
        fetch(`fetch_images.php?house_id=${houseId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const bannerImage = data.images.length > 0 ? data.images[0] : './uploads/default-banner.png';
                    const bannerImgElement = element.querySelector('img');
                    bannerImgElement.src = bannerImage;
                    console.log('Fetched images:', data.images);
                } else {
                    console.error('Error fetching images:', data.message);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
    }
    </script>
</body>
</html>