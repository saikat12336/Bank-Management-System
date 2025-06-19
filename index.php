<?php
include("admin/conf/config.php");
/* Persist System Settings On Brand */
$ret = "SELECT * FROM `iB_SystemSettings` ";
$stmt = $mysqli->prepare($ret);
$stmt->execute();
$res = $stmt->get_result();
while ($sys = $res->fetch_object()) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $sys->sys_name; ?> - <?php echo $sys->sys_tagline; ?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="./admin/dist/img/<?php echo $sys->sys_logo; ?>">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* General Styles */
/* General Styles */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #e0eafc, #cfdef3);
    margin: 0;
    padding: 0;
}

/* Navbar Styles */
.navbar {
    background-color: #0f172a; /* Darker blue/gray */
}

.navbar-brand {
    font-weight: 600;
    font-size: 1.75rem;
    color: #f8fafc;
}

.nav-link {
    color: #cbd5e1;
    margin-right: 20px;
    position: relative;
    transition: color 0.3s ease;
}

.nav-link::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: -4px;
    left: 0;
    background: linear-gradient(90deg, #4ade80, #22d3ee);
    transition: width 0.3s ease;
}

.nav-link:hover {
    color: #22d3ee;
}

.nav-link:hover::after {
    width: 100%;
}

.btn-join {
    background: linear-gradient(45deg, #22d3ee, #4ade80, #facc15);
    background-size: 200% 200%;
    color: white;
    font-weight: 700;
    padding: 0.65rem 1.8rem;
    border-radius: 12px;
    border: none;
    box-shadow:
        0 6px 0 #16a34a,   /* deep green shadow for 3D */
        0 12px 15px rgba(0, 0, 0, 0.25); /* soft drop shadow */
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    transition: background-position 0.5s ease, transform 0.25s ease, box-shadow 0.25s ease;
    cursor: pointer;
    user-select: none;
    position: relative;
}

.btn-join:hover {
    background-position: 100% 0;
    box-shadow:
        0 4px 0 #166622,
        0 8px 12px rgba(0, 0, 0, 0.3);
    transform: translateY(4px);
}

/* Button Styles */
.btn-primary {
    background: linear-gradient(90deg, #ff416c, #ff4b2b); /* pink to orange */
    border: none;
    border-radius: 30px;
    padding: 10px 26px;
    font-weight: 600;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 4px 10px rgba(255, 75, 43, 0.6);
}

.btn-primary:hover {
    background: linear-gradient(90deg, #ff4b2b, #ff416c);
    box-shadow: 0 6px 15px rgba(255, 75, 43, 0.9);
    transform: scale(1.05);
}


/* Hero Section */
.hero {
    position: relative;
    background: url('dist/bg.webp') center center/cover no-repeat;
    height: 100vh;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(15, 23, 42, 0.7);
}

.hero-content {
    position: relative;
    z-index: 1;
    animation: zoomInFade 1s ease forwards;
}

.hero h1 {
    font-size: 3.5rem;
    margin-bottom: 20px;
    font-weight: 600;
}

.hero p {
    font-size: 1.5rem;
    margin-bottom: 30px;
    font-weight: 400;
}

/* Animations */
@keyframes zoomInFade {
    0% {
        opacity: 0;
        transform: scale(0.8);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.nav-link {
    animation: fadeInLeft 0.6s ease forwards;
}

.nav-link:nth-child(1) {
    animation-delay: 0.2s;
}

.nav-link:nth-child(2) {
    animation-delay: 0.4s;
}

@keyframes fadeInLeft {
    0% {
        opacity: 0;
        transform: translateX(-15px);
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Footer Styles */
.footer {
    background-color: #0f172a;
    color: #cbd5e1;
    padding: 40px 0;
}

.footer .row {
    display: flex;
    justify-content: space-between; /* space between left, center, and right columns */
    align-items: flex-start; /* vertical alignment */
    gap: 0; /* remove gap so space-between works cleanly */
}

.footer .col-md-4:nth-child(1) {
    /* About Section aligned left by default */
    flex: 1;
    text-align: left;
}

.footer .col-md-4:nth-child(2) {
    /* Quick Links centered */
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.footer .col-md-4:nth-child(3) {
    /* Follow Us aligned right */
    flex: 1;
    text-align: right;
    margin-left: 0; /* remove previous margin */
}


.footer h5 {
    color: #f8fafc;
    margin-bottom: 20px;
}

.footer a {
    color: #94a3b8;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer a:hover {
    color: #22d3ee;
}

.footer .social-icons{
    position: relative;
    right: -2rem;
}

.footer .social-icons a {
    font-size: 1.3rem;
    margin-right: 15px;
    color: #94a3b8;
    transition: color 0.3s ease;
}

.footer .social-icons a:hover {
    color: #22d3ee;
}

.footer-bottom {
    background: linear-gradient(90deg, #0f172a, #1e3a8a, #38bdf8, #1e3a8a, #0f172a); /* dark blue to blue to sky */
    color: #f1f5f9; /* light text */
    text-align: center;
    padding: 15px 0;
    margin-top: 20px;
    font-weight: 500;
    font-size: 0.95rem;
    letter-spacing: 0.5px;
}


.footer-separator {
    width: 100%;
    height: 3px; /* thin line */
    background: linear-gradient(90deg,
        #22d3ee,
        #4ade80,
        #facc15,
        #f87171,
        #a78bfa,
        #22d3ee);
    background-size: 300% 100%;
    animation: gradientMove 5s linear infinite;
    border-radius: 1.5px;
    margin: 0; /* remove extra spacing */
}
@keyframes gradientMove {
    0% {
        background-position: 0% 0;
    }
    100% {
        background-position: 300% 0;
    }
}


    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="./admin/dist/img/<?php echo $sys->sys_logo; ?>" alt="Logo" style="height: 60px;">
               <span style="font-family: 'Pacifico', cursive; font-size: 18px;"><?php echo $sys->sys_name; ?></span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="client/pages_client_index.php" target="_blank">Client Portal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About Us</a>
                    </li>
                </ul>
                <a class="btn btn-success btn-join ms-3" href="client/pages_client_signup.php" target="_blank">Join Us</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1><?php echo $sys->sys_name; ?></h1>
            <p><?php echo $sys->sys_tagline; ?></p>
            <a class="btn btn-primary btn-lg" href="client/pages_client_signup.php" target="_blank">Get Started</a>
        </div>
    </section>

    <!-- Footer -->
    <div class="footer-separator"></div>
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>About <?php echo $sys->sys_name; ?></h5>
                    <p>Providing secure and reliable banking services to our valued clients.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Support</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Follow Us</h5>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom mt-4">
                <p>&copy; <?php echo date("Y"); ?> <?php echo $sys->sys_name; ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
document.addEventListener('keydown', function(e) {
    // Admin: Ctrl + Shift + A
    if (e.ctrlKey && e.shiftKey && e.key === 'A') {
        window.open('secure/entry_admin.php', '_blank');
    }

    // Staff: Ctrl + Shift + S
    if (e.ctrlKey && e.shiftKey && e.key === 'S') {
        window.open('secure/entry_staff.php', '_blank');
    }
});
</script>

</body>
</html>
<?php
}
?>
