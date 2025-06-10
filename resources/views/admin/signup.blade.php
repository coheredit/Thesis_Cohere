<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Signup - Villa Salud Catering</title>
    @vite('resources/css/signup.css')
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
                    <input type="email" name="email" id="email" required><br>

                    <label>First Name:</label>
                    <input type="text" name="f_name" id="first_name" required><br>

                    <label>Last Name:</label>
                    <input type="text" name="l_name" id="last_name" required><br>

                    <label>Contact Number:</label>
                    <input type="text" id="phone" name="phone" required><br>

                    <label>Password:</label>
                    <input type="password" id="password" name="password" required><br>

                    <label>Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required><br>

                    <!-- Profile Picture Selection -->
                    <div class="profile-picture-section">
                        <label>Choose Profile Picture:</label>
                        <div class="avatar-options">
                            <label class="avatar-option selected" for="default">
                                <input type="radio" name="profile_picture" value="default.png" id="default" checked>
                                <img src="{{ asset('images/default.png') }}" alt="Default Avatar">
                                <span>Default</span>
                            </label>
                            
                            <label class="avatar-option" for="boy">
                                <input type="radio" name="profile_picture" value="boy.png" id="boy">
                                <img src="{{ asset('images/boy.png') }}" alt="Boy Avatar">
                                <span>Boy</span>
                            </label>
                            
                            <label class="avatar-option" for="boy1">
                                <input type="radio" name="profile_picture" value="boy1.png" id="boy1">
                                <img src="{{ asset('images/boy1.png') }}" alt="Boy Avatar 1">
                                <span>Boy 1</span>
                            </label>
                            
                            <label class="avatar-option" for="boy2">
                                <input type="radio" name="profile_picture" value="boy2.png" id="boy2">
                                <img src="{{ asset('images/boy2.png') }}" alt="Boy Avatar 2">
                                <span>Boy 2</span>
                            </label>
                            
                            <label class="avatar-option" for="girl">
                                <input type="radio" name="profile_picture" value="girl.png" id="girl">
                                <img src="{{ asset('images/girl.png') }}" alt="Girl Avatar">
                                <span>Girl</span>
                            </label>
                            
                            <label class="avatar-option" for="girl1">
                                <input type="radio" name="profile_picture" value="girl1.png" id="girl1">
                                <img src="{{ asset('images/girl1.png') }}" alt="Girl Avatar 1">
                                <span>Girl 1</span>
                            </label>
                            
                            <label class="avatar-option" for="girl2">
                                <input type="radio" name="profile_picture" value="girl2.png" id="girl2">
                                <img src="{{ asset('images/girl2.png') }}" alt="Girl Avatar 2">
                                <span>Girl 2</span>
                            </label>
                        </div>
                    </div>

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