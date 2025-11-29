<!DOCTYPE html>
<html lang="en">
<?php
    // Include authentication config
    require_once __DIR__ . '/App/Config/Auth.php';
    
    // Check if already logged in
    if (isAuthenticated()) {
        header('Location: App/View/index.php');
        exit();
    }
    
    // Get any error or success messages
    $error_message = getErrorMessage();
    $success_message = getSuccessMessage();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Barangay Biga Management System - Digital Solutions for Community Governance">
    <meta name="theme-color" content="#6ec207">
    <title>Barangay Biga Management System | Santa Cruz, Marinduque</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        :root {
            --apple-green: #6ec207;
            --apple-green-dark: #7edc2a;
            --default: #ffff;

            --primary-700: #6ec207;
            --primary-600: #7edc2a;
            --primary-500: #8efc3d;
        }

        /* Dropdown Animation Styles */
        .dropdown-menu {
            display: block;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            transition: all 0.2s ease;
            padding: 0.75rem 1.5rem;
        }

        .dropdown-item:hover {
            background-color: var(--apple-green);
            color: white;
            transform: translateX(5px);
        }
        #modal-logout {
            background-color: var(--apple-green);
            color: var(--default);
        }
        .hero-section {
            height: 90vh;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .carousel-item {
            height: 90vh;
            background-size: cover;
            background-position: center;
        }
       .main .carousel-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                rgba(115, 129, 97, 0.8),   /* --primary-700: #6ec207 */
                #FFF2C6  /* --primary-600: #7edc2a */
            );
            z-index: 1;
        }
        .carousel-content {
            position: relative;
            z-index: 2;
        }
        .navbar {
            background-color:#452829!important;
        }
        .btn-primary {
            background-color: #E8D1C5 !important;
            border-color: black !important;
        }
        .btn-primary:hover {
            background-color: #57595B !important;
            border-color: black !important;
        }
        .btn-outline-light:hover {
            background-color: var(--apple-green) !important;
            border-color: var(--apple-green) !important;
        }
        .text-primary {
            color:white!important;
        }
        
        .card:hover {
            border-color: var(--apple-green);
            transition: border-color 0.3s ease;
        }
        footer {
            background-color: var(--apple-green-dark) !important;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="assets/img/BIGA-LOGO.png" alt="BIGA Logo" height="55" class="me-3">
                <div class="d-flex flex-column">
                <span class="h6 mb-0 fw-bold text-white">BARANGAY BIGA</span>
                    <small class="text-white fw-semibold">Santa Cruz, Marinduque</small>
                </div>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>



            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="#" class="nav-link active nav-bg-active text-primary">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#about" class="nav-link dropdown-toggle text-primary" id="aboutDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            About
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="aboutDropdown">
                            <li><a class="dropdown-item" href="#about"><i class="bi bi-info-circle me-2"></i>Barangay Overview</a></li>
                            <li><a class="dropdown-item" href="#about"><i class="bi bi-bullseye me-2"></i>Mission & Vision</a></li>
                            <li><a class="dropdown-item" href="#services"><i class="bi bi-briefcase me-2"></i>Services</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#contact"><i class="bi bi-people-fill me-2"></i>Officials</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#contact" class="nav-link text-primary">Contact</a>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-primary text-dark ms-2"data-bs-toggle="modal" data-bs-target="#loginModal"><i class="bi bi-person-fill"></i> Login</button>
                    </li>
                </ul>
                <script>
                    // Highlight active nav-link with background
                    document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
                        link.addEventListener('click', function() {
                            document.querySelectorAll('.navbar-nav .nav-link').forEach(l => {
                                l.classList.remove('nav-bg-active');
                            });
                            this.classList.add('nav-bg-active');
                        });
                    });
                </script>
                <style>
                    .nav-bg-active {
                        background-color:#E8D1C5 !important;
                        color: black !important;
                        border-radius: 0.375rem;
                        transition: background 0.2s;
                    }
                </style>
            </div>
        </div>
    </nav>


    
    <!--THIS IS EXAMPLE MODAL-->
        <div class="modal fade" id="krukkruk" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header" id="modal-logout">
                        <h5 class="modal-title text-white" id="logoutModalLabel">Confirm Logout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to logout?
                        <form action="" method="post">
                            <label for="">Example Required Trigger function</label>
                            <input type="text" class="form-control" required placeholder="Enter Something">
                            <br>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Submit</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <a href="../index.html" class="btn btn-danger">Yes</a>
                    </div>
                </div>
            </div>
        </div>
    <!--THIS IS THE END OF MODAL-->

    <!-- Alert Messages Container -->
    <div class="container-fluid pt-5" style="margin-top: 80px;">
        <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show mx-auto" role="alert" style="max-width: 600px;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Error:</strong> <?php echo htmlspecialchars($error_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show mx-auto" role="alert" style="max-width: 600px;">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong>Success:</strong> <?php echo htmlspecialchars($success_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Hero Section -->
    <section class="hero-section main">
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" style="width: 12px; height: 12px; border-radius: 50%; margin: 0 6px;"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" style="width: 12px; height: 12px; border-radius: 50%; margin: 0 6px;"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" style="width: 12px; height: 12px; border-radius: 50%; margin: 0 6px;"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active" style="background-image: url('assets/img/biga1.jpg'); background-position: center 30%;">
                    <div class="carousel-content d-flex align-items-center h-100">
                        <div class="container text-center">
                            <h1 class="display-2 fw-bold mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">Barangay Management System</h1>
                            <p class="lead mb-4" style="font-size: 1.5rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">Empowering Community Governance Through Technology</p>
                            <button class="btn btn-outline-light btn-lg px-5 py-3" style="font-weight: 600;" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Access Portal
                            </button>
                        </div>
                    </div>
                </div>
                <div class="carousel-item" style="background-image: url('assets/img/bigamap.jpg'); background-position: center center;">
                    <div class="carousel-content d-flex align-items-center h-100">
                        <div class="container text-center">
                            <h1 class="display-2 fw-bold mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">Serving Barangay Biga</h1>
                            <p class="lead mb-4" style="font-size: 1.5rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">Digital solutions for efficient barangay administration and services</p>
                            <button class="btn btn-outline-light btn-lg px-5 py-3" style="font-weight: 600;" onclick="document.getElementById('about').scrollIntoView({behavior: 'smooth'});">
                                <i class="bi bi-info-circle me-2"></i>Learn More
                            </button>
                        </div>
                    </div>
                </div>
                <div class="carousel-item" style="background-image: url('assets/img/brgyofice.jpg'); background-position: center 40%;">
                    <div class="carousel-content d-flex align-items-center h-100">
                        <div class="container text-center">
                            <h1 class="display-2 fw-bold mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">Transparent Governance</h1>
                            <p class="lead mb-4" style="font-size: 1.5rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">Building trust through accessible information and streamlined processes</p>
                            <button class="btn btn-outline-light btn-lg px-5 py-3" style="font-weight: 600;" onclick="document.getElementById('contact').scrollIntoView({behavior: 'smooth'});">
                                <i class="bi bi-telephone me-2"></i>Contact Us
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>


    
   
     
    

    <!-- Testimonials Section -->
    <!-- <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold display-4 mb-3">What Our Students Say</h2>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <p class="lead text-muted">Hear from our students about their experiences at MarSU</p>
                    </div>
                </div>
            </div>
            <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="6000">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="text-center bg-white p-5 rounded-4" style="box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); border: 1px solid rgba(0, 0, 0, 0.08);">
                                    <img src="assets/img/MARSU LOGO.png" alt="Student" class="rounded-circle mb-4" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #6ec207;">
                                    <p class="lead mb-4">"The CICS program at MarSU has been transformative. The hands-on experience and supportive faculty have prepared me well for my career in tech."</p>
                                    <h5 class="fw-bold mb-1">Maria Santos</h5>
                                    <p class="text-muted">BS Computer Science, Class of 2024</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="text-center bg-white p-5 rounded-4" style="box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); border: 1px solid rgba(0, 0, 0, 0.08);">
                                    <img src="assets/img/CICS LOGO-min.png" alt="Student" class="rounded-circle mb-4" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #6ec207;">
                                    <p class="lead mb-4">"The research opportunities here are incredible. I've had the chance to work on cutting-edge projects that have real-world impact."</p>
                                    <h5 class="fw-bold mb-1">Juan Dela Cruz</h5>
                                    <p class="text-muted">BS Information Technology, Class of 2025</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="text-center bg-white p-5 rounded-4" style="box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); border: 1px solid rgba(0, 0, 0, 0.08);">
                                    <img src="assets/img/logo.png" alt="Student" class="rounded-circle mb-4" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #6ec207;">
                                    <p class="lead mb-4">"Being part of MarSU's CICS has opened doors I never thought possible. The community here is supportive and inspiring."</p>
                                    <h5 class="fw-bold mb-1">Ana Reyes</h5>
                                    <p class="text-muted">BS Information Systems, Class of 2023</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button type="button" data-bs-target="#testimonialCa</div>rousel" data-bs-slide-to="0" class="btn btn-sm mx-1 p-0 active" style="width:10px;height:10px;background:#6ec207;border:none;border-radius:50%;"></button>
                    <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="1" class="btn btn-sm mx-1 p-0" style="width:10px;height:10px;background:#6ec207;border:none;border-radius:50%;"></button>
                    <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="2" class="btn btn-sm mx-1 p-0" style="width:10px;height:10px;background:#6ec207;border:none;border-radius:50%;"></button>
                </div>
            </div>
        </div>
    </section> -->

    <!-- About Section -->
    <section id="about" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="fw-bold mb-3">About Barangay Management System</h1>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <p class="lead text-muted">Modernizing barangay operations through digital transformation, making government services more accessible and efficient for all residents of Barangay Biga, Santa Cruz, Marinduque.</p>
                    </div>
                </div>
            </div>
            
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <div class="position-relative rounded-4 overflow-hidden shadow-lg">
                        <img src="assets/img/brgy2.jpg" alt="Barangay Office" class="img-fluid rounded-4" style="width: 100%; height: 400px; object-fit: cover;">
                        <div class="position-absolute bottom-0 start-0 w-100 p-4" style="background: linear-gradient(transparent, rgba(110, 194, 7, 0.9));">
                            <h3 class="text-white mb-2 fw-bold">Our Mission</h3>
                            <p class="text-white mb-0">To provide efficient, transparent, and accessible barangay services through innovative technology while fostering community development and engagement.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-4">
                        <div class="col-6">
                            <div class="card h-100 border-0 rounded-4 shadow-sm hover-shadow" style="transition: all 0.3s ease;">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <i class="bi bi-people-fill text-success" style="font-size: 2.5rem;"></i>
                                    </div>
                                    <h3 class="h2 fw-bold mb-2 text-success">370+</h3>
                                    <p class="text-muted mb-0 fw-semibold">Residents</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card h-100 border-0 rounded-4 shadow-sm hover-shadow" style="transition: all 0.3s ease;">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <i class="bi bi-house-door text-dark" style="font-size: 2.5rem;"></i>
                                    </div>
                                    <h3 class="h2 fw-bold mb-2 text-dark">150+</h3>
                                    <p class="text-muted mb-0 fw-semibold">Households</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card h-100 border-0 rounded-4 shadow-sm hover-shadow" style="transition: all 0.3s ease;">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <img src="assets/img/icons/person-wheelchair.svg" alt="Wheelchair Icon" width="40" height="40" style="filter: brightness(0);">
                                    </div>
                                    <h3 class="h2 fw-bold mb-2 text-warning">80+</h3>
                                    <p class="text-muted mb-0 fw-semibold">Senior Citizens</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card h-100 border-0 rounded-4 shadow-sm hover-shadow" style="transition: all 0.3s ease;">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <i class="bi bi-people text-info" style="font-size: 2.5rem;"></i>
                                    </div>
                                    <h3 class="h2 fw-bold mb-2 text-info">120+</h3>
                                    <p class="text-muted mb-0 fw-semibold">Youth & Children</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold display-5 mb-3">Barangay Services</h2>
                <p class="lead text-muted">Accessible and efficient services for all residents</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="bi bi-file-earmark-text-fill text-success" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Barangay Clearance</h5>
                            <p class="text-muted">Request and process barangay clearance certificates online for various purposes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="bi bi-person-plus-fill text-dark" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Resident Registration</h5>
                            <p class="text-muted">Register as a resident and maintain updated information in our database</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="bi bi-shield-fill-exclamation text-danger" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Incident Reporting</h5>
                            <p class="text-muted">Report incidents and emergencies directly to barangay officials</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="bi bi-house-fill text-warning" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Household Management</h5>
                            <p class="text-muted">Manage household information and track family members efficiently</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="bi bi-megaphone-fill text-info" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Announcements</h5>
                            <p class="text-muted">Stay updated with the latest barangay news, events, and announcements</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="bi bi-cash-stack text-success" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Financial Tracking</h5>
                            <p class="text-muted">Transparent budget allocation and expense monitoring system</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold display-5 mb-3">System Features</h2>
                <p class="lead text-muted">Advanced tools for modern barangay administration</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-speedometer2 text-success" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold">Real-time Dashboard</h5>
                            <p class="text-muted mb-0">Monitor barangay statistics, population demographics, and key metrics at a glance with interactive charts and graphs.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-shield-lock-fill text-primary" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold">Secure Data Management</h5>
                            <p class="text-muted mb-0">Protected resident information with role-based access control ensuring data privacy and security compliance.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-graph-up-arrow text-warning" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold">Analytics & Reports</h5>
                            <p class="text-muted mb-0">Generate comprehensive reports on demographics, incidents, and financial data for informed decision-making.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-phone-fill text-info" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold">Mobile Responsive</h5>
                            <p class="text-muted mb-0">Access the system anytime, anywhere on any device with fully responsive design optimized for all screen sizes.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-danger bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-bell-fill text-danger" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold">Alert System</h5>
                            <p class="text-muted mb-0">Instant notifications for emergencies, announcements, and important updates to keep everyone informed.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold">Easy to Use</h5>
                            <p class="text-muted mb-0">Intuitive interface designed for users of all technical levels, making barangay management simple and efficient.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    .hover-card {
        transition: all 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(110, 194, 7, 0.2) !important;
    }
    @media (max-width: 768px) {
        .about-img {
            height: 300px !important;
        }
    }
    </style>

    


    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 flex-column">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center w-100 pb-3">
                        <img src="assets/img/BIGA-LOGO.png" alt="Barangay Biga Logo" class="mb-3" style="height: 150px;">
                        <h4 class="modal-title fw-bold text-primary">Welcome Back!</h4>
                        <p class="text-muted">Login to access Barangay Management System</p>
                    </div>
                </div>
                <div class="modal-body px-4">

                    <form id="loginForm" class="needs-validation" novalidate>
                        <div class="mb-4">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-person-fill text-dark"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" id="username" required
                                    placeholder="Enter your username">
                                <div class="invalid-feedback">Please enter your username</div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-lock-fill text-dark"></i>
                                </span>
                                <input type="password" class="form-control border-start-0" id="password" required
                                    placeholder="Enter your password">
                                <div class="invalid-feedback">Please enter your password</div>
                            </div>
                        </div>
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="showPassword">
                            <label class="form-check-label" for="showPassword">Show password</label>
                            <a href="#" class="float-end text-primary text-decoration-none">Forgot password?</a>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 fw-bold">
                                <span class="me-2">Sign In</span>
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center border-0 pt-0">
                    <p class="text-muted">Don't have an account? <a href="#" class="text-primary text-decoration-none">Contact Admin</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        // Add floating label effect
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('border-primary');
            });
            
            input.addEventListener('blur', () => {
                input.parentElement.classList.remove('border-primary');
            });
        });

        // Form validation and submission
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            
            if (!form.checkValidity()) {
                e.stopPropagation();
                form.classList.add('was-validated');
                return;
            }

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Signing in...';
            
            try {
                const response = await fetch('App/Controller/LoginController.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        username: username,
                        password: password
                    })
                });

                const result = await response.json();

                if (result.success) {
                    submitBtn.innerHTML = '<span class="me-2"><i class="bi bi-check-circle-fill"></i> Success!</span>';
                    submitBtn.classList.remove('btn-primary');
                    submitBtn.classList.add('btn-success');
                    
                    setTimeout(() => {
                        window.location.href = 'App/View/index.php';
                    }, 1000);
                } else {
                    submitBtn.innerHTML = '<span class="me-2"><i class="bi bi-exclamation-circle-fill"></i> Login Failed</span>';
                    submitBtn.classList.remove('btn-primary');
                    submitBtn.classList.add('btn-danger');
                    
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('btn-danger');
                        submitBtn.classList.add('btn-primary');
                        submitBtn.innerHTML = '<span class="me-2">Sign In</span><i class="bi bi-arrow-right"></i>';
                        
                        // Show error message with appropriate styling
                        const alertType = response.status === 403 ? 'alert-warning' : 'alert-danger';
                        const alertIcon = response.status === 403 ? 'exclamation-triangle-fill' : 'exclamation-triangle-fill';
                        
                        const alert = document.createElement('div');
                        alert.className = `alert ${alertType} alert-dismissible fade show mt-3`;
                        alert.innerHTML = `
                            <i class="bi bi-${alertIcon} me-2"></i>
                            ${result.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        form.appendChild(alert);
                    }, 1500);
                }
            } catch (error) {
                console.error('Error:', error);
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-primary');
                submitBtn.classList.add('btn-danger');
                submitBtn.innerHTML = '<span class="me-2"><i class="bi bi-exclamation-circle-fill"></i> Network Error</span>';
                
                setTimeout(() => {
                    submitBtn.classList.remove('btn-danger');
                    submitBtn.classList.add('btn-primary');
                    submitBtn.innerHTML = '<span class="me-2">Sign In</span><i class="bi bi-arrow-right"></i>';
                    
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-danger alert-dismissible fade show mt-3';
                    alert.innerHTML = `
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Failed to connect to server. Please try again.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    form.appendChild(alert);
                }, 1500);
            }
        });

        // Modal animation enhancements
        const loginModal = document.getElementById('loginModal');
        loginModal.addEventListener('show.bs.modal', function () {
            this.querySelector('.modal-content').style.transform = 'scale(0.7)';
            this.querySelector('.modal-content').style.opacity = '0';
            
            setTimeout(() => {
                this.querySelector('.modal-content').style.transform = 'scale(1)';
                this.querySelector('.modal-content').style.opacity = '1';
            }, 200);
        });

        // Add this to your existing style section
        document.head.insertAdjacentHTML('beforeend', `
            <style>
                .modal-content {
                    transition: all 0.3s ease-in-out;
                }
                .input-group:focus-within {
                    box-shadow: 0 0 0 0.25rem rgba(76, 175, 80, 0.25);
                    border-radius: 0.375rem;
                }
                .form-control:focus {
                    box-shadow: none;
                }
                .input-group-text {
                    transition: all 0.2s;
                }
                .input-group:focus-within .input-group-text {
                    border-color: var(--apple-green);
                    background-color: #f8f9fa;
                }
            </style>
        `);



        // Show/hide password functionality
        document.getElementById('showPassword').addEventListener('change', function() {
            const passwordInput = document.getElementById('password');
            if (this.checked) {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });
    </script>

     <script>
        // Log a simple string
        console.log("Hello, world!");

        // Log the value of a variable
        const myVariable = "This is a variable.";
        console.log(myVariable);

        // Log an object
        const myObject = { name: "Alice", age: 30 };
        console.log(myObject.name);

        // FUNCTIONS 
        function exampleOutput(){
            return "THIS IS MY FUNCTIONS";
        }
        console.log(exampleOutput());

        //ADD TWO NUMBERS
        function add(a, b) {
            return a + b;
        }
        let num1 = 10;
        let num2 = 20;
        let result = add(num1, num2);
        console.log(`The sum of ${num1} and ${num2} is ${result}`);
        console.log("THE SUM OF " + num1 + " AND " + num2 + " IS " + result);


        // function greet(name) {
        //     return `Hello, ${name}!`;
        // }
    </script>


    <!-- Footer -->
    <footer class="text-light py-5 bg-dark" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="assets/img/BIGA-LOGO.png" alt="Barangay Biga Logo" height="60" class="me-3">
                        <div>
                            <h5 class="text-white fw-bold mb-0">Barangay Biga</h5>
                            <small class="text-white-50">Santa Cruz, Marinduque</small>
                        </div>
                    </div>
                    <p class="text-white-50">Empowering our community through innovative digital solutions and transparent governance.</p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 40px; height: 40px; padding: 0; line-height: 40px;">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 40px; height: 40px; padding: 0; line-height: 40px;">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 40px; height: 40px; padding: 0; line-height: 40px;">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 40px; height: 40px; padding: 0; line-height: 40px;">
                            <i class="bi bi-envelope"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="text-white fw-bold mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-link"><i class="bi bi-chevron-right me-2"></i>Home</a></li>
                        <li class="mb-2"><a href="#about" class="text-white-50 text-decoration-none hover-link"><i class="bi bi-chevron-right me-2"></i>About Us</a></li>
                        <li class="mb-2"><a href="#services" class="text-white-50 text-decoration-none hover-link"><i class="bi bi-chevron-right me-2"></i>Services</a></li>
                        <li class="mb-2"><a href="#contact" class="text-white-50 text-decoration-none hover-link"><i class="bi bi-chevron-right me-2"></i>Contact</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-link" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="bi bi-chevron-right me-2"></i>Login</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="text-white fw-bold mb-3">Services</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-link"><i class="bi bi-chevron-right me-2"></i>Clearance</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-link"><i class="bi bi-chevron-right me-2"></i>Registration</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-link"><i class="bi bi-chevron-right me-2"></i>Incidents</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-link"><i class="bi bi-chevron-right me-2"></i>Reports</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="text-white fw-bold mb-3">Contact Info</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="bi bi-geo-alt-fill text-success me-2"></i>
                            <span class="text-white-50">Barangay Biga<br>Santa Cruz, Marinduque</span>
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-telephone-fill text-success me-2"></i>
                            <span class="text-white-50">(042) 123-4567</span>
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-envelope-fill text-success me-2"></i>
                            <span class="text-white-50">barangaybiga@gmail.com</span>
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-clock-fill text-success me-2"></i>
                            <span class="text-white-50">Mon - Fri: 8:00 AM - 5:00 PM</span>
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 bg-secondary">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 text-white-50">&copy; 2025 Barangay Biga Management System. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-white-50 text-decoration-none me-3 hover-link">Privacy Policy</a>
                    <a href="#" class="text-white-50 text-decoration-none me-3 hover-link">Terms of Service</a>
                    <a href="#" class="text-white-50 text-decoration-none hover-link">Help Center</a>
                </div>
            </div>
        </div>
    </footer>

    <style>
        .hover-link {
            transition: all 0.3s ease;
        }
        .hover-link:hover {
            color: var(--apple-green) !important;
            transform: translateX(5px);
        }
    </style>
</body>
</html>