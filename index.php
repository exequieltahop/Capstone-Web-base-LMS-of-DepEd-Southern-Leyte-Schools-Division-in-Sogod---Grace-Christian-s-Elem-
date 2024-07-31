<?php 
    include('pages/dbconnection/dbconnection.php');
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- css -->
    <link rel="stylesheet" href="css/lagin.css">
    <!-- <link rel="icon" href="assets/imgs/logo-bg-removed.png"> -->

    <link rel="apple-touch-icon" sizes="180x180" href="assets/imgs/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/imgs/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/imgs/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="assets/imgs/favicon_io/site.webmanifest">
    <!-- jquery -->
    <script src="js/jquery.js"></script>
    <title>Login</title>
</head>
<body>
    <main id="main">
        <!-- loading animation -->
        <div class="backdrop_blur">
            <div class="loading"></div>
            <span class="span_loading">Loading...</span>
        </div>
        <div class="container">
            <div class="logo-h1-span">
                <img src="assets/imgs/logo.jpg" alt="logo" id="logo">
                <h1>Login Now</h1>
                <span>Welcome! Please enter your details</span>
            </div>
            <!-- form -->
            <form class="form">
                <div>
                    <label for="username">Username</label>
                    <input type="text" class="input" name="username" placeholder="Enter your Username" id="username">
                </div>
                <div>
                    <label for="password">Password</label>
                    <div class="show_hide_pass">
                        <input type="password" class="input" name="password" placeholder="Enter your Password" id="password">
                        <img src="assets/imgs/eye.png" alt="show/hide password" id="showHidePass">
                    </div>
                </div>
                <div class="div-third">
                    <button class="btn-login" type="button" id="btnSignin">Log in</button>
                </div>
                <div>
                    <span class="sign-up-link">
                        Don't have an account?
                        <a href="pages/other/signUp.php">Sign Up</a>
                    </span>
                </div>
            </form>
            <!-- end form -->
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', ()=>{
            document.getElementById('showHidePass').addEventListener('click',(e)=>{
                let password = document.getElementById('password');

                if(password.type == 'password'){
                    password.type = 'text'
                    e.target.src = 'assets/imgs/hidden.png'
                }else{
                    password.type = 'password'
                    e.target.src = 'assets/imgs/eye.png'
                }
            });
            const btnSignin = document.getElementById('btnSignin');
            btnSignin.addEventListener('click', function(e) {
                e.preventDefault();

                const username = document.getElementById('username').value;
                const password = document.getElementById('password').value;

                console.log('AJAX on');
                if (username === '' || password === '') {
                    alert('Please fill all the required fields!');
                } else {
                    const formData = new FormData();

                    formData.append('username', username);
                    formData.append('password', password);
                    const blur = document.querySelector('.backdrop_blur');
                    blur.style.display = 'flex';
                    fetch('pages/process/teacherProcess/loginProcess.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then((response) => response.json())
                        .then((responseData) => {
                            // console.log(responseData.status);
                            if(responseData.status == 'Pending'){
                                blur.style.display = 'none';
                                alert('Your account approval is pending. Please await confirmation from the administrator.');
                            }
                            if (responseData.status === 'Failed') {
                                blur.style.display = 'none';
                                alert('Wrong username or password, please try again!');
                            } 
                            if (responseData.status === 'Invalid') {
                                blur.style.display = 'none';
                                alert('Invalid Account, please sign up');
                                window.location.href = 'pages/other/signUp.php';
                            }
                            if (responseData.status === 'Success') {
                                if (responseData.usertype === 'Teacher' && responseData.accountStatus == 'Approved') {
                                    blur.style.display = 'none';
                                    alert('Login successful, welcome ' + responseData.usertype + ' ' + responseData.user);
                                    window.location.href = 'pages/teacher/teacherDashboard.php';
                                }
                                if(responseData.usertype === 'Pupil' && responseData.accountStatus == 'Approved'){
                                    blur.style.display = 'none';
                                    alert('Login successful, welcome ' + responseData.usertype + ' ' + responseData.user);
                                    window.location.href = 'pages/student/studentDashBoard.php';
                                }
                                if(responseData.usertype == 'Admin'){
                                    blur.style.display = 'none';
                                    alert('Login successful, welcome ' + responseData.usertype + ' ' + responseData.user);
                                    window.location.href = 'pages/admin/adminDashboard.php';
                                }
                                // Add other conditions for different user types if needed
                            } 
                        })
                        .catch((error) => {
                            blur.style.display = 'none';
                            alert('Something went wrong please try again!');
                            console.error('Failed to communicate with the server.');
                        });
                }
            });
        });
        //loading animation
        document.addEventListener('readystatechange', () => {
            if (document.readyState == 'loading') {
                const blur = document.querySelector('.backdrop_blur');
                blur.style.display = 'flex';
            } else if (document.readyState == 'complete') {
                const blur = document.querySelector('.backdrop_blur');
                blur.style.display = 'none';
            }
        });
        //animation before window unload
        window.addEventListener('beforeunload',(event)=>{
            const blur = document.querySelector('.backdrop_blur');
            blur.style.display = 'flex';
        });
    </script>
</body>
</html>