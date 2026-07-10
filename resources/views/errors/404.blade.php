<!-- resources/views/errors/404.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .container {
            text-align: center;
        }
        .error-code {
            font-size: 150px;
            font-weight: bold;
            text-shadow: 4px 4px 0 rgba(0,0,0,0.2);
        }
        .error-message {
            font-size: 24px;
            margin: 20px 0;
        }
        .home-btn {
            display: inline-block;
            padding: 12px 30px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            transition: transform 0.3s;
        }
        .home-btn:hover {
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">404</div>
        <div class="error-message">Oops! Page Not Found</div>
        <p>Jo page dhoondh rahe ho, wo yahan nahi hai.</p>
        <br>
        <a href="{{ url('/') }}" class="home-btn">🏠 Home Page</a>
    </div>
</body>
</html>