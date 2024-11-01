<?php

require 'config/function.php';

// Start the session if not already started
session_start();

if (isset($_POST['loginBtn'])) {
    // Validate and sanitize user input
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);

    // Check if email and password fields are not empty
    if ($email != '' && $password != '') {
        // Use prepared statements to prevent SQL injection
        $query = "SELECT * FROM admins WHERE email=? LIMIT 1";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                $hashedPassword = $row['password'];

                // Verify the password
                if (!password_verify($password, $hashedPassword)) {
                    redirect('login.php', 'Invalid password');
                }

                // Check if the account is banned
                if ($row['is_ban'] == 1) {
                    redirect('login.php', 'Your Account Has Been Banned. Contact Your Admin.');
                }

                // Set session variables for logged in user
                $_SESSION['loggedIn'] = true;
                $_SESSION['loggedInUser'] = [
                    'user_id' => $row['id'],
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'phone' => $row['phone'],
                ];

                redirect('admin/index.php', 'Logged In Successfully');

            } else {
                redirect('login.php', 'Invalid Email Address');
            }

        } else {
            redirect('login.php', 'Something Went Wrong!');
        }

    } else {
        redirect('login.php', 'All fields are mandatory!');
    }
}

?>
