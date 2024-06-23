<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>hotel</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    
</head>

<body>
    <div class="container-xxl bg-white p-0">
 

        <!-- Navbar Start -->
        <div class="container-fluid nav-bar bg-transparent sticky-top">
            <nav class="navbar navbar-expand-lg bg-white navbar-light py-0 px-4">
                <a href="index.html" class="navbar-brand d-flex align-items-center text-center">
                    <div class="icon p-2 me-2">
                        <img class="img-fluid" src="img/Logo.svg.png" alt="Icon" style="width: 30px; height: 30px;">
                    </div>
                    <h1 class="m-0 text-primary">Hotel</h1>
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto">
                        <a href="index.php" class="nav-item nav-link">Home</a>
                        <a href="room.php" class="nav-item nav-link">Room</a>
                        <!-- <a href="facilites.php" class="nav-item nav-link">Facilities</a> -->
                        <a href="#" class="nav-item nav-link">Contact</a>
                    </div>
                    <a href="#" class="btn btn-primary px-3 d-none d-lg-flex">Admin</a>
                </div>
            </nav>
        </div>
        <!-- Navbar End -->

        <!-- Book Form Start-->
        <div class="container-fluid bg-primary mt-5 mb-5 wow fadeIn" data-wow-delay="0.1s" style="padding: 35px;">
            <div class="container">
                <form action="bookNow.php" method="post">
                <div class="row g-2">
                    <div class="col-md-10">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <input type="text" name="userName" class="form-control border-0 py-3" placeholder="Input Name">
                            </div>
                            <div class="col-md-4">
                                <input type="tel" name="userTelNum" class="form-control border-0 py-3" placeholder="Input Phone Number">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="userIdCardNum" class="form-control border-0 py-3" placeholder="Input ID card Number">
                            </div>
                        </div>
                        <div class="row g-2 mt-2">
                            <?php if(empty($_SESSION["roomType"])&& empty($_SESSION["selectedDate"])){
                                
                            ?>
                            <div class="col-md-4">
                                <select name="roomType" class="form-select border-0 py-3">
                                    <option value="" disabled selected>Select Room Type</option>
                                    <option value="President">President</option>
                                    <option value="Deluxe">Deluxe</option>
                                    <option value="Suite">Suite</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="selectedDate" class="form-control border-0 py-3" placeholder="Select date">
                            </div>
                            <?php
                            }else{
                                $_POST["roomType"] = $_SESSION["roomType"];
                                $_POST["selectedDate"] = $_SESSION["selectedDate"];
                            }
                            ?>
                            
                        </div>
                    </div>




                    <div class="col-md-2">
                        <button class="btn btn-dark border-0 w-100 py-3" name="submit">Submit</button>
                    </div>
                </div>
                </form>
                <?php
                    if(isset($_POST["submit"])){
                        $notifMsg = NULL;
                        if(empty($_POST['userName'])||empty($_POST['userTelNum'])||empty($_POST['userIdCardNum'])||empty($_POST['userName'])||empty($_POST['selectedDate'])||empty($_POST['roomType'])){
                            $notifMsg = "All data needs to be filled";
                        }else{
                            $conn = mysqli_connect("localhost", "root", "", "hotel") or die("Couldn't connect");
                            if (!$conn) {
                                die("Connection failed: " . mysqli_connect_error());
                            }

                            $userName = $_POST['userName'];
                            $userTelNum = $_POST['userTelNum'];
                            $userIdCardNum = $_POST['userIdCardNum'];
                            $roomType = $_POST['roomType'];
                            $selectedDate = $_POST['selectedDate'];

                            // 1. Select first row matching roomType and store in $temp
                            $getRoomTemp = "SELECT * FROM Rooms WHERE RoomType = ?";
                            $getRoom = mysqli_prepare($conn, $getRoomTemp);
                            mysqli_stmt_bind_param($getRoom, "s", $roomType);
                            mysqli_stmt_execute($getRoom);
                            $result = mysqli_stmt_get_result($getRoom);

                            $temp = mysqli_fetch_assoc($result);  // $temp holds the first matching room data
                            $price = $temp["RoomPrice"];
                            $deadline = date('Y-m-d', strtotime('now + 1 day'));
                            mysqli_stmt_close($getRoom);

                            if ($temp) {  // Check if a room was found

                                $roomID = $temp['RoomID'];
                                $roomPrice = $temp['RoomPrice'];  // Assuming RoomPrice is available in Rooms table

                                // 2. Create new entry in RoomAvailability
                                $updateTemp = "INSERT INTO RoomAvailability (RoomID, Date) VALUES (?, ?)";
                                $update = mysqli_prepare($conn, $updateTemp);
                                mysqli_stmt_bind_param($update, "is", $roomID, $selectedDate);
                                mysqli_stmt_execute($update);
                                mysqli_stmt_close($update);

                                // 3. Create new Transaction
                                $transactionTemp = "INSERT INTO Transactions (transactionDate, userIdCardNum, paymentStatus, RoomID, transactionPrice, userName, userTelNum)
                                        VALUES (CURDATE(), ?, 'Pending', ?, ?, ?, ?)";

                                $transaction = mysqli_prepare($conn, $transactionTemp);
                                mysqli_stmt_bind_param($transaction, "sssss", $userIdCardNum, $roomID, $roomPrice, $userName, $userTelNum);

                                if (mysqli_stmt_execute($transaction)) {
                                    $notifMsg = "Total Price is " . $price . ", due is " . $deadline;
                                } else {
                                    $notifMsg = "Booking failed";
                                }

                                mysqli_stmt_close($transaction);
                            }
                            mysqli_close($conn);
                        }
                        
                    }
                ?>
                <?php
                if(isset($_POST["submit"])){?>
                <h5 class="text-white mb-4"><?php echo $notifMsg?></h5>
                <?php }?>
            </div>
        </div>
        
        <!--Book form end-->
        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Get In Touch</h5>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Jalan 123, Ngawi jawa barat</p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i>contact@gmail.com</p>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Quick Links</h5>
                        <a class="btn btn-link text-white-50" href="">About Us</a>
                        <a class="btn btn-link text-white-50" href="">Contact Us</a>
                        <a class="btn btn-link text-white-50" href="">Our Services</a>
                        <a class="btn btn-link text-white-50" href="">Privacy Policy</a>
                        <a class="btn btn-link text-white-50" href="">Terms & Condition</a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Photo Gallery</h5>
                        <div class="row g-2 pt-2">
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="img/president.jpg" alt="">
                            </div>
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="img/deluxe.jpg" alt="">
                            </div>
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="img/suite3.jpg" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Newsletter</h5>
                        <p>Get latest discounts.</p>
                        <div class="position-relative mx-auto" style="max-width: 400px;">
                            <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="text" placeholder="Your email">
                            <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">SignUp</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="#">Hotel</a>, All Right Reserved. 
							

							Designed By <a class="border-bottom" href="">Hotel</a>
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <div class="footer-menu">
                                <a href="index.php">Home</a>
                                <a href="">Cookies</a>
                                <a href="">Help</a>
                                <a href="">FQAs</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>