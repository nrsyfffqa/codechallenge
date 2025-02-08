<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Teacher's Portal</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .hero-section {
            background: linear-gradient(to right, #4A90E2, #56CCF2);
            color: white;
            padding: 80px 0;
            text-align: center;
            border-radius: 0 0 30px 30px;
        }
        .hero-section h1 {
            font-size: 2.5rem;
            font-weight: 600;
        }
        .hero-section p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        .card {
            border-radius: 15px;
            transition: 0.3s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .btn-custom {
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 500;
            text-transform: uppercase;
            transition: 0.3s ease-in-out;
        }
        .btn-primary {
            background-color: #4A90E2;
            border: none;
        }
        .btn-primary:hover {
            background-color: #357ABD;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>

    <!-- Hero Section -->
    <div class="hero-section">
        <h1>Welcome to the Teacher’s Portal</h1>
        <p>Your centralized system for managing students efficiently.</p>
    </div>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h3 class="mb-3">Get Started</h3>
                        <p class="text-muted">Log in to access student records, upload data, and manage your classroom effectively.</p>

                        <!-- Authentication Buttons -->
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-custom">
                                    <i class="fas fa-home"></i> Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary btn-custom">
                                    <i class="fas fa-sign-in-alt"></i> Log In
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-custom ms-2">
                                        <i class="fas fa-user-plus"></i> Register
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; {{ date('Y') }} Teacher's Portal | Built with Laravel</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
