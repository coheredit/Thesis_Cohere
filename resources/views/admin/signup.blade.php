<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Signup - Villa Salud Catering</title>
    @vite('resources/css/signup.css')
        <link rel="icon" type="image/png" href="{{ asset('images/vs_logo.png') }}" />
</head>

<body>
    <div class="wrapper">
    <div class="container">
        <div class="left-section">
            <h1>Welcome to Villa Salud Catering System</h1>
            <p>Enter your personal details to complete your reservation and access all system features.</p>
            <div class="signup-box">
                <h2>Create Account!</h2>

                @if(session('error'))
                <div class="error-message" style="color: #dc3545; background: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
                    {{ session('error') }}
                </div>
                @endif

                @if(session('success'))
                <div class="success-message" style="color: #28a745; background: #d4edda; border: 1px solid #c3e6cb; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
                    {{ session('success') }}
                </div>
                @endif

                <form method="POST" action="{{ route('admin.signup.submit') }}" id="signupForm">
                    @csrf

                    <label>Email Address:</label>
                    <input type="email" name="email" id="email" required placeholder="e.g. johndoe@gmail.com"><br>

                    <label>First Name:</label>
                    <input type="text" name="f_name" id="first_name" required placeholder="e.g John"><br>

                    <label>Last Name:</label>
                    <input type="text" name="l_name" id="last_name" required placeholder="e.g. Doe"><br>

                    <label>Contact Number:</label>
                    <input type="text" id="phone" name="phone" required ><br>

                    <label>Password:</label>
                    <input type="password" id="password" name="password" required><br>

                    <label>Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required><br>
                    
                    <button type="submit">Sign Up</button>
                </form>

                <p class="login-link">
                    Already have an account? <a href="{{ route('admin.login') }}">Log in here!</a>
                </p>
            </div>
        </div>
        <div class="right-section">
            <img src="{{ asset('images/background_picture.jpeg') }}" alt="Background" class="right-img">
        </div>
    </div>
    </div>
    @vite('resources/js/signup.js')
</body>

</html>