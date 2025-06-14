<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Installation Complete - Restulator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;600&display=swap"
        rel="stylesheet">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') center/cover fixed no-repeat;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }

        .container {
            position: relative;
            z-index: 2;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.9);
        }

        .card-header {
            background: #b76e79;
            color: white;
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            text-align: center;
            font-weight: bold;
            padding: 1.2rem;
            border-bottom: 5px solid #8e4e57;
        }

        .card-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .btn-primary {
            background: #b76e79;
            border: none;
            transition: all 0.3s ease;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 30px;
            box-shadow: 0 4px 15px rgba(183, 110, 121, 0.4);
        }

        .btn-primary:hover {
            background: #8e4e57;
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(183, 110, 121, 0.5);
        }

        .btn-primary:active {
            transform: translateY(1px);
        }

        .icon-success {
            font-size: 4.5rem;
            color: #b76e79;
            margin-bottom: 1.5rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        .card-body {
            padding: 2.5rem;
        }

        .alert-info {
            background-color: #f8f9fa;
            border-left: 4px solid #b76e79;
            color: #495057;
            border-radius: 0;
            position: relative;
            padding-left: 20px;
        }

        .alert-info::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: #b76e79;
        }

        .credentials {
            font-family: 'Courier New', monospace;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px dashed #ced4da;
        }

        .card-footer {
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            padding: 1.2rem;
            font-style: italic;
            font-family: 'Playfair Display', serif;
        }

        .food-icon {
            display: inline-block;
            margin: 0 15px;
            font-size: 1.5rem;
            color: #b76e79;
            animation: swing 3s infinite;
        }

        @keyframes swing {
            0%, 100% {
                transform: rotate(-10deg);
            }
            50% {
                transform: rotate(10deg);
            }
        }

        .congrats-message {
            color: #495057;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card text-center">
                <div class="card-header">
                    <i class="bi bi-cup-hot food-icon"></i>
                    Installation Complete
                    <i class="bi bi-egg-fried food-icon"></i>
                </div>
                <div class="card-body">
                    <div class="icon-success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h5 class="card-title">Your Restaurant Management System is Ready!</h5>
                    <p class="congrats-message">
                        Congratulations! You've successfully installed Restulator, your all-in-one restaurant management
                        solution.
                        Your digital kitchen is now open for business!
                    </p>

                    <div class="credentials">
                        <div class="row mb-2">
                            <div class="col-4 text-start fw-bold">Email:</div>
                            <div class="col-8 text-start">admin@restulator.com</div>
                        </div>
                        <div class="row">
                            <div class="col-4 text-start fw-bold">Password:</div>
                            <div class="col-8 text-start">12345678</div>
                        </div>
                    </div>

                    <p class="text-muted mb-4">For security reasons, we recommend changing your password after your
                        first login.</p>

                    <a href="/login" class="btn btn-primary btn-lg">
                        <i class="bi bi-arrow-right-circle me-2"></i>Enter Your Dashboard
                    </a>
                </div>
                <div class="card-footer text-muted">
                    <i class="bi bi-stars me-2"></i>Enjoy managing your restaurant with Restulator!
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
