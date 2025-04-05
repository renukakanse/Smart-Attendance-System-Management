<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "school_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Sign Up
if (isset($_POST['signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $name_pattern = "/^[a-zA-Z ]{2,30}$/";
    $email_pattern = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/";
    $password_pattern = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/";
    
    if (!preg_match($name_pattern, $name)) {
        $_SESSION['message'] = "Name should be 2-30 characters long and contain only letters";
    }
    elseif (!preg_match($email_pattern, $email)) {
        $_SESSION['message'] = "Please enter a valid email address";
    }
    elseif (!preg_match($password_pattern, $password)) {
        $_SESSION['message'] = "Password must be at least 8 characters with letters and numbers";
    }
    else {
        $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $_SESSION['message'] = "Email already exists";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $_SESSION['message'] = "Registration successful! Please sign in.";
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['message'] = "Error: " . $conn->error;
            }
        }
        $stmt->close();
    }
}

// Handle Sign In
if (isset($_POST['signin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['message'] = "Login successful!";
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['message'] = "Incorrect password";
        }
    } else {
        $_SESSION['message'] = "Email not found";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Montserrat:400,800');

        * {
            box-sizing: border-box;
        }

        body {
            background: #e3f2fd;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            font-family: 'Montserrat', sans-serif;
            height: 100vh;
            margin: -20px 0 50px;
        }

        h1 {
            font-weight: bold;
            margin: 0;
        }

        p {
            font-size: 14px;
            font-weight: 100;
            line-height: 20px;
            letter-spacing: 0.5px;
            margin: 20px 0 30px;
        }

        a {
            color: #333;
            font-size: 14px;
            text-decoration: none;
            margin: 15px 0;
        }

        button {
            border-radius: 20px;
            border: 1px solid #1565c0;
            background-color: #1565c0;
            color: #FFFFFF;
            font-size: 12px;
            font-weight: bold;
            padding: 12px 45px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: transform 80ms ease-in;
            cursor: pointer;
        }

        button:active {
            transform: scale(0.95);
        }

        button:focus {
            outline: none;
        }

        button.ghost {
            background-color: transparent;
            border-color: #FFFFFF;
        }

        input {
            background-color: #eee;
            border: none;
            padding: 12px 15px;
            margin: 8px 0;
            width: 100%;
            border-radius: 5px;
        }

        .container {
            position: relative;
            width: 768px;
            max-width: 100%;
            min-height: 480px;
            display: flex;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            width: 50%;
            transition: all 0.6s ease-in-out;
        }

        .card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            padding: 40px;
            margin: 20px;
            height: calc(100% - 40px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
            transform: translateY(-5px);
        }

        .sign-in-container {
            left: 0;
            z-index: 2;
        }

        .container.right-panel-active .sign-in-container {
            transform: translateX(100%);
        }

        .sign-up-container {
            left: 0;
            opacity: 0;
            z-index: 1;
        }

        .container.right-panel-active .sign-up-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: show 0.6s;
        }

        @keyframes show {
            0%, 49.99% {
                opacity: 0;
                z-index: 1;
            }
            50%, 100% {
                opacity: 1;
                z-index: 5;
            }
        }

        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: transform 0.6s ease-in-out;
            z-index: 100;
        }

        .container.right-panel-active .overlay-container {
            transform: translateX(-100%);
        }

        .overlay {
            background: linear-gradient(to right, #1976d2, #0d47a1);
            color: #FFFFFF;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .container.right-panel-active .overlay {
            transform: translateX(50%);
        }

        .overlay-panel {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .overlay-left {
            transform: translateX(-20%);
        }

        .container.right-panel-active .overlay-left {
            transform: translateX(0);
        }

        .overlay-right {
            right: 0;
            transform: translateX(0);
        }

        .container.right-panel-active .overlay-right {
            transform: translateX(20%);
        }

        .notification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            padding: 15px 25px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
        }
    </style>
</head>
<body>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<div class='notification'>" . $_SESSION['message'] . "</div>";
        unset($_SESSION['message']);
    }
    ?>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <div class="card">
                <form action="" method="POST">
                    <h1>Create Account</h1>
                    <input type="text" name="name" placeholder="Name" required />
                    <input type="email" name="email" placeholder="Email" required />
                    <input type="password" name="password" placeholder="Password" required />
                    <button type="submit" name="signup">Sign Up</button>
                </form>
            </div>
        </div>
        <div class="form-container sign-in-container">
            <div class="card">
                <form action="" method="POST">
                    <h1>Sign in</h1>
                    <input type="email" name="email" placeholder="Email" required />
                    <input type="password" name="password" placeholder="Password" required />
                    <button type="submit" name="signin">Sign In</button>
                </form>
            </div>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start journey with us</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
        });

        setTimeout(() => {
            const notification = document.querySelector('.notification');
            if (notification) {
                notification.style.display = 'none';
            }
        }, 3000);
    </script>
</body>
</html>

<?php $conn->close(); ?>