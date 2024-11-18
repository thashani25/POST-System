<?php

require 'config/function.php';

// Start the session if not already started


if (isset($_POST['loginBtn'])) {
    // Validate and sanitize user input
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);

    // Check if email and password fields are not empty
    if ($email != '' && $password != '') 
    {
        // Use prepared statements to prevent SQL injection
        $query = "SELECT * FROM admins WHERE email='$email' LIMIT 1";
        $result = mysqli_query($conn, $query);
        if($result){

            if (mysqli_num_rows($result) == 1) {

                $row = mysqli_fetch_assoc($result);
                $hashedPassword = $row['password'];

                if(!password_verify($password,$hashedPassword)){
                    redirect('login.php', 'Invalid password');

                }

                if ($row['is_ban'] == 1) {
                    redirect('login.php','Your Account Has Been Banned. Contact Your Admin.');
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


            }else{
                redirect('login.php', 'Invalid Email Address');
                
            }
                

        }else{
            redirect('login.php', 'Something Went Wrong!');

        }
        
    }
}
