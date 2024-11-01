<?php 

require 'config/function.php';

if(isset($_SESSION['loggedIn'])) {

    logoutSession();
    redirect('Login.php', 'Logged Out Successfully.');
    
}

?>