<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            overflow: hidden;
        }

        h1 {
            font-size: 4rem;
            text-align: center;
            animation: fadeIn 2s ease-out, glow 1.5s infinite alternate;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes glow {
            from {
                text-shadow: 0 0 10px #ffffff, 0 0 20px #ffffff, 0 0 30px #6a11cb, 0 0 40px #6a11cb, 0 0 50px #6a11cb, 0 0 60px #2575fc, 0 0 70px #2575fc;
            }
            to {
                text-shadow: 0 0 20px #ffffff, 0 0 30px #ffffff, 0 0 40px #6a11cb, 0 0 50px #6a11cb, 0 0 60px #2575fc, 0 0 70px #2575fc, 0 0 80px #2575fc;
            }
        }

        footer {
            position: absolute;
            bottom: 20px;
            text-align: center;
            width: 100%;
            font-size: 0.9rem;
        }

        footer a {
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Welcome to Our Website!</h1>
    <footer>
        <p>Powered by <a href="#">Sk Studio</a></p>
    </footer>
</body>
</html>
