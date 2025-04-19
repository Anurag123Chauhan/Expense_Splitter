<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome to Expense Splitter</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                min-height: 100vh;
                padding: 2rem 0;
            }
            .hero-section {
                background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
                color: white;
                padding: 4rem 0;
                margin-bottom: 3rem;
                border-radius: 0 0 50px 50px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            }
            .hero-title {
                font-size: 3.5rem;
                font-weight: 700;
                margin-bottom: 1.5rem;
            }
            .hero-subtitle {
                font-size: 1.25rem;
                opacity: 0.9;
                margin-bottom: 2rem;
            }
            .feature-card {
                background: white;
                padding: 2rem;
                border-radius: 15px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.05);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                height: 100%;
            }
            .feature-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            }
            .feature-icon {
                font-size: 2.5rem;
                color: #0d6efd;
                margin-bottom: 1.5rem;
            }
            .feature-title {
                font-size: 1.5rem;
                font-weight: 600;
                margin-bottom: 1rem;
                color: #212529;
            }
            .feature-text {
                color: #6c757d;
                line-height: 1.6;
            }
            .btn-custom {
                padding: 0.8rem 2rem;
                font-weight: 500;
                border-radius: 8px;
                transition: all 0.3s ease;
            }
            .btn-custom:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            }
            .btn-primary-custom {
                background: #0d6efd;
                border: none;
                color: white;
            }
            .btn-success-custom {
                background: #198754;
                border: none;
                color: white;
            }
            .btn-outline-custom {
                border: 2px solid white;
                color: white;
            }
            .btn-outline-custom:hover {
                background: white;
                color: #0d6efd;
            }
            .stats-section {
                background: white;
                padding: 3rem 0;
                margin-top: 3rem;
                border-radius: 15px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            }
            .stat-card {
                text-align: center;
                padding: 2rem;
            }
            .stat-number {
                font-size: 2.5rem;
                font-weight: 700;
                color: #0d6efd;
                margin-bottom: 0.5rem;
            }
            .stat-label {
                color: #6c757d;
                font-size: 1.1rem;
            }
            </style>
    </head>
    <body>
        <div class="hero-section">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1 class="hero-title">Split Expenses with Ease</h1>
                        <p class="hero-subtitle">The smart way to manage and split expenses with your friends and groups. No more awkward conversations about money!</p>
                    @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-custom btn-primary-custom">
                                <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                            </a>
                        @else
                            <div>
                                <a href="{{ route('login') }}" class="btn btn-custom btn-outline-custom me-3">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-custom btn-success-custom">
                                    <i class="fas fa-user-plus me-2"></i>Register
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="feature-title">Create Groups</h3>
                        <p class="feature-text">Organize expenses by creating different groups for different occasions or circles. Keep your expenses well-organized and easy to track.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <h3 class="feature-title">Track Expenses</h3>
                        <p class="feature-text">Add and manage expenses within your groups easily. Keep a clear record of who paid for what and when.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <h3 class="feature-title">Split Bills</h3>
                        <p class="feature-text">Automatically calculate how much each person owes or is owed. No more manual calculations or confusion.</p>
                    </div>
                </div>
            </div>

            <div class="stats-section">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-number">100%</div>
                            <div class="stat-label">Free to Use</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-number">24/7</div>
                            <div class="stat-label">Always Available</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-number">Easy</div>
                            <div class="stat-label">Simple to Use</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
