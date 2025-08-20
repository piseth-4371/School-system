<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University School Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #34495e 100%);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="70" r="1" fill="rgba(255,255,255,0.05)"/><circle cx="70" cy="30" r="1" fill="rgba(255,255,255,0.05)"/></svg>');
        }
        
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .stats-section {
            background: var(--light-bg);
            padding: 80px 0;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: var(--secondary-color);
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        
        .btn-primary {
            background: var(--secondary-color);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }
        
        .btn-outline-primary {
            border: 2px solid var(--secondary-color);
            color: var(--secondary-color);
            padding: 10px 28px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-building me-2"></i>UniSMS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    <li class="nav-item ms-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

   <!-- Hero Section -->
    <section class="hero-section" style="position: relative; z-index: 1;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Welcome to University SMS</h1>
                    <p class="lead mb-4">The complete school management system designed for modern universities. Manage students, courses, attendance, and more in one powerful platform.</p>
                    <div class="d-flex gap-3 flex-wrap">
                        <!-- Get Started Button -->
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg d-inline-flex align-items-center" style="z-index: 2;">
                            <i class="bi bi-person-plus me-2"></i>Get Started
                        </a>
                        <!-- Learn More Button -->
                        <a href="#features" class="btn btn-outline-light btn-lg d-inline-flex align-items-center" style="z-index: 2;">
                            <i class="bi bi-play-circle me-2"></i>Learn More
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 500 400'%3E%3Crect width='500' height='400' fill='%23ecf0f1'/%3E%3Ccircle cx='250' cy='150' r='80' fill='%233498db'/%3E%3Cpath d='M200 250 L300 250 L250 350 Z' fill='%232c3e50'/%3E%3Ctext x='250' y='120' text-anchor='middle' fill='white' font-size='20' font-weight='bold'%3EUni%3C/text%3E%3Ctext x='250' y='145' text-anchor='middle' fill='white' font-size='20' font-weight='bold'%3ESMS%3C/text%3E%3C/svg%3E" 
                        alt="University Illustration" class="img-fluid" style="max-width: 400px;">
                </div>
            </div>
        </div>
    </section>



    <!-- Features Section -->
    <section id="features" class="py-5" style="padding-top: 100px !important; padding-bottom: 100px !important;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Powerful Features</h2>
                <p class="lead text-white">Everything you need to manage your university efficiently</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon text-primary">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <h4 class="card-title">Student Management</h4>
                            <p class="card-text">Comprehensive student profiles, enrollment tracking, and academic records management.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon text-success">
                                <i class="bi bi-clipboard-data"></i>
                            </div>
                            <h4 class="card-title">Attendance System</h4>
                            <p class="card-text">Track student attendance with detailed reports and analytics for better monitoring.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon text-warning">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <h4 class="card-title">Fee Management</h4>
                            <p class="card-text">Streamlined payment processing, invoice generation, and financial reporting.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon text-info">
                                <i class="bi bi-journal-bookmark"></i>
                            </div>
                            <h4 class="card-title">Course Management</h4>
                            <p class="card-text">Create and manage courses, assign teachers, and track academic progress.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon text-danger">
                                <i class="bi bi-graph-up"></i>
                            </div>
                            <h4 class="card-title">Exam System</h4>
                            <p class="card-text">Complete examination management with grading, results, and performance analytics.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon text-secondary">
                                <i class="bi bi-bar-chart"></i>
                            </div>
                            <h4 class="card-title">Reporting</h4>
                            <p class="card-text">Comprehensive reports and analytics for informed decision-making.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-number">5,000+</div>
                    <p class="text-muted">Students</p>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-number">250+</div>
                    <p class="text-muted">Teachers</p>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-number">15+</div>
                    <p class="text-muted">Departments</p>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-number">98%</div>
                    <p class="text-muted">Satisfaction</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5" style="padding-top: 100px !important; padding-bottom: 100px !important;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-5 fw-bold mb-4">Why Choose Our System?</h2>
                    <p class="lead mb-4">Our University School Management System is designed to simplify academic administration and enhance the learning experience.</p>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle p-2 me-3">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">User-Friendly Interface</h5>
                            <p class="text-white mb-0">Intuitive design for easy navigation</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success text-white rounded-circle p-2 me-3">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Secure & Reliable</h5>
                            <p class="text-white mb-0">Enterprise-grade security measures</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning text-white rounded-circle p-2 me-3">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">24/7 Support</h5>
                            <p class="text-white mb-0">Round-the-clock technical support</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 500 400'%3E%3Crect width='500' height='400' fill='%23f8f9fa'/%3E%3Ccircle cx='150' cy='150' r='100' fill='%23e3f2fd'/%3E%3Cpath d='M120 150 L180 150 L150 200 Z' fill='%231976d2'/%3E%3Ccircle cx='350' cy='150' r='80' fill='%23e8f5e8'/%3E%3Cpath d='M320 150 L380 150 L350 200 Z' fill='%232e7d32'/%3E%3Ccircle cx='250' cy='300' r='70' fill='%23fbe9e7'/%3E%3Cpath d='M225 300 L275 300 L250 350 Z' fill='%23d32f2f'/%3E%3C/svg%3E" 
                         alt="System Features" class="img-fluid rounded-3">
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-4" style="padding-top: 100px !important; padding-bottom: 100px !important; background-color: #f8f9fa;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Contact Us</h2>
                <p class="lead text-muted">We'd love to hear from you! Whether you have questions or need support, we're here to help.</p>
            </div>

            <!-- Contact Form -->
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" required placeholder="Enter your full name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" required placeholder="Enter your email address">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" rows="4" required placeholder="Your message"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5" style="background: linear-gradient(135deg, var(--secondary-color) 0%, #2980b9 100%); color: white; padding: 80px 0;">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-4">Ready to Get Started?</h2>
            <p class="lead mb-4">Join thousands of universities already using our platform to transform their academic management.</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-person-plus me-2"></i>Create Account
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold">University SMS</h5>
                    <p class="text-white">Comprehensive school management system for modern universities.</p>
                </div>
                <div class="col-lg-2 col-6 mb-4">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="#features" class="text-white text-decoration-none">Features</a></li>
                        <li><a href="#about" class="text-white text-decoration-none">About</a></li>
                        <li><a href="#contact" class="text-white text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-6 mb-4">
                    <h6>Account</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('login') }}" class="text-white text-decoration-none">Login</a></li>
                        <li><a href="{{ route('register') }}" class="text-white text-decoration-none">Register</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6>Contact Info</h6>
                    <p class="text-white mb-1"><i class="bi bi-geo-alt me-2"></i>123 University Ave, Campus City</p>
                    <p class="text-white mb-1"><i class="bi bi-envelope me-2"></i>info@university-sms.edu</p>
                    <p class="text-white mb-0"><i class="bi bi-phone me-2"></i>+1 (555) 123-4567</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="text-white mb-0">&copy; 2024 University School Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar background change on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98) !important';
                navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95) !important';
                navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
            }
        });
    </script>
</body>
</html>