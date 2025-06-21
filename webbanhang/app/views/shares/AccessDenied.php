<!-- app/views/shares/AccessDenied.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Access Denied</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body,
        html {
            height: 100%;
            background-color: #f8f9fa;
        }

        .centered-container {
            height: 100vh;
            /* full viewport height */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
        }

        .icon {
            font-size: 80px;
            color: #dc3545;
            /* bootstrap danger red */
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container centered-container">
        <div class="icon">
            <!-- Bootstrap Danger Icon SVG for lock or stop -->
            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-shield-lock-fill" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 0c-.69 0-1.912.3-2.947.743A12.798 12.798 0 0 0 1.5 2.27c-.166.095-.333.196-.5.304V8c0 4.978 3.39 7.63 6.768 8.727a1.735 1.735 0 0 0 1.464 0C11.11 15.63 14.5 12.978 14.5 8V2.574c-.166-.107-.334-.21-.5-.304a12.798 12.798 0 0 0-3.553-1.527A5.798 5.798 0 0 0 8 0zm.5 7.5v1a.5.5 0 0 1-1 0v-1a.5.5 0 0 1 1 0z" />
            </svg>
        </div>
        <h1 class="display-4 text-danger">Access Denied</h1>
        <p class="lead">You do not have permission to access this page.</p>
        <a href="/webbanhang/Product" class="btn btn-primary mt-3">Go to Homepage</a>
    </div>
</body>

</html>