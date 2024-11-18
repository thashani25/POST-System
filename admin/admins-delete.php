<?php

require '../config/function.php';  // conect function php

$paraRestultId = checkParamId('id');


if(is_numeric($paraRestultId)){

    $adminId = validate($paraRestultId);

    $admin = getById('admins',$adminId);
    if($admin['status'] == 200)
    {
        $adminDelecteRes = delete('admins', $adminId);
        if($adminDelecteRes)
        {
            redirect('admins.php','Admin Delected Successfully.');

        }
        else
        {  
            redirect('admins.php','Something Went Wrong.');
        }
    }
    else
    {
        redirect('admins.php',$admin['message']);
    }
   // echo $adminId;

}else{
    redirect('admins.php','Something Went Wrong.');
}
?>