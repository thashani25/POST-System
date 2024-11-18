<?php

require '../config/function.php';  // conect function php

$paraRestultId = checkParamId('id');


if(is_numeric($paraRestultId)){

    $productId = validate($paraRestultId);

    $product = getById('products',$productId);
    if($product['status'] == 200)
    {
        $response = delete('products', $productId);
        if($response)
        {
            $deleteImage = "../".$products['data']['image'];
            if(file_exists($deleteImage)){
                unlink($deleteImage);
            }
            redirect('products.php','product Delected Successfully.');

        }
        else
        {  
            redirect('products.php','Something Went Wrong.');
        }
    }
    else
    {
        redirect('products.php', $product['message']);
    }
   // echo $adminId;

}else{
    redirect('products.php','Something Went Wrong.');
}
?>