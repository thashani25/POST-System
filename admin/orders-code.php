<?php

include('../config/function.php');

if (!isset($_SESSION['productItems'])) {
    $_SESSION['productItems'] = [];
}

if (!isset($_SESSION['productItemIds'])) {
    $_SESSION['productItemIds'] = [];
}

if (isset($_POST['addItem'])) {
    $productId = validate($_POST['product_id']);
    $quantity = validate($_POST['quantity']);

    // Use prepared statement for security
    $checkProduct = mysqli_query($conn, "SELECT * FROM products WHERE id='$productId' LIMIT 1");
    if($checkProduct){
        if(mysqli_num_rows($checkProduct) > 0){

            $row = mysqli_fetch_assoc($checkProduct);
            if($row['quantity'] < $quantity){
                redirect('order-create.php', 'Only' .$row['quantity'].'quantity available');
            }

            $productData = [
                'product_id' => $row['id'],
                'name' => $row['name'],
                'image' => $row['image'],
                'price' => $row['price'],
                'quantity' => $quantity,
            ];

            if(!in_array($row['id'], $_SESSION['productItemIds'])){

                array_push($_SESSION['productItemIds'],$row['id']);
                array_push($_SESSION['productItems'],$productData);

            }else{

                foreach($_SESSION['productItems'] as $key => $prodSessionItem) {
                    if($prodSessionItem['product_id'] == $row['id']){

                        $newQuantity = $prodSessionItem['quantity'] + $quantity;

                        $productData = [
                            'product_id' => $row['id'],
                            'name' => $row['name'],
                            'image' => $row['image'],
                            'price' => $row['price'],
                            'quantity' => $newQuantity,
                        ];
                        $_SESSION['productItems'][$key] =  $productData;

                    }
                }
            }
            redirect('order-create.php', 'Item Addded'.$row['name']);

        }else{
            redirect('order-create.php', 'No such product found!');

        }

    }else{

        redirect('order-create.php', 'Somthing Went Wrong.!');
    }

            }

    if(isset($_POST['productIncDec']))
    {
        $productId = validate($_POST['product_id']);
        $quantity = validate($_POST['quantity']);

        $flag = false;
        foreach($_SESSION['productItems'] as $key => $item){
            if($item['product_id'] == $productId){

                $flag = true;
                $_SESSION['productItems'][$key]['quantity'] = $quantity;


            }

        }
        if( $flag){

             json_encode(200, 'success', 'Quantity Updated');
            
        }else{

             json_encode(500, 'error', 'Something Went Wrong. Please re-fresh');

        }


    }

if(isset($_POST['proceedToPlaceBtn']))
{
    $phone = validate($_POST['cphone']);
    $payment_mode = validate($_POST['payment_mode']);


    //checking in customer

    $checkCustomer = mysqli_query($conn, "SELECT * FROM customers WHERE phone='$phone' LIMIT 1");
    if($checkCustomer){
        if(mysqli_num_rows($checkCustomer) > 0)
        {
            $_SESSION['invoice_no'] = "INV-".rand(111111,999999);
            $_SESSION['cphone'] = $phone;
            $_SESSION['payment_mode'] = $payment_mode;
            jsonResponse(200, 'success', 'Customer Found');
        }

        else
        {
            $_SESSION['cphone'] = $phone;

            jsonResponse(404, 'warning', 'Customer Not Found');
        }
    }
    else
    {
        jsonResponse(500, 'error', 'Something Went Wrong');
        

    }

}

if(isset($_POST['saveCustomerBtn']))
{
    $name = validate($_POST['name']);
    $phone = validate($_POST['phone']);
    $email = validate($_POST['email']);
    
  

    if($name != '' && $phone !=''){

        $data = [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,


        ];
        $result = insert('customers', $data);
        if($result){
            jsonResponse(200, 'success', 'Customer Created successfully');
        }else{
            jsonResponse(500, 'error', 'Something Went wrong');
        }

    }else{
        jsonResponse(422, 'warning', 'Please fill required field');
    }
}

if (isset($_POST['saveOrder'])) {
    // Validate session data
    $phone = validate($_SESSION['cphone']);
    $invoice_no = validate($_SESSION['invoice_no']);
    $payment_mode = validate($_SESSION['payment_mode']);
    $order_placed_by_id = $_SESSION['loggedInUser']['user_id'];

    // Check if customer exists
    $checkCustomer = mysqli_query($conn, "SELECT * FROM customers WHERE phone='$phone' LIMIT 1");
    if (!$checkCustomer) {
        jsonResponse(500, 'error', 'Something Went Wrong!');
    }

    if (mysqli_num_rows($checkCustomer) > 0) {
        $customerData = mysqli_fetch_assoc($checkCustomer);

        // Check if there are any items in the session
        if (!isset($_SESSION['productItems']) || empty($_SESSION['productItems'])) {
            jsonResponse(404, 'warning', 'No Items to Place Order!');
        }

        // Calculate total amount
        $sessionProducts = $_SESSION['productItems'];
        $totalAmount = 0;
        foreach ($sessionProducts as $amtItem) {
            $totalAmount += $amtItem['price'] * $amtItem['quantity'];
        }

        // Prepare data for the order
        $data = [
            'customer_id' => $customerData['id'],
            'tracking_no' => rand(111111, 999999),
            'invoice_no' => $invoice_no,
            'total_amount' => $totalAmount,
            'order_date' => date('Y-m-d'),
            'order_status' => 'booked',
            'payment_mode' => $payment_mode,
            'order_placed_by_id' => $order_placed_by_id
        ];

        // Insert the order into the database
        $result = insert('orders', $data); // Assuming `insert()` is a function that inserts into DB
        if (!$result) {
            jsonResponse(500, 'error', 'Failed to insert order');
        }

        // Get the last inserted order ID
        $lastOrderId = mysqli_insert_id($conn);

        // Insert order items
        foreach ($sessionProducts as $prodItem) {
            $productId = $prodItem['product_id']; // Correct variable name
            $price = $prodItem['price'];
            $quantity = $prodItem['quantity'];

            // Insert each order item
            $dataOrderItem = [
                'order_id' => $lastOrderId,
                'product_id' => $productId, // Correct variable name
                'price' => $price,
                'quantity' => $quantity
            ];

            $orderItemQuery = insert('order_items', $dataOrderItem); // Insert order items

            // Check product stock and update quantity
            $checkProductQuantityQuery = mysqli_query($conn, "SELECT * FROM products WHERE id='$productId'");
            if (!$checkProductQuantityQuery) {
                jsonResponse(500, 'error', 'Failed to fetch product data');
            }

            $productQtyData = mysqli_fetch_assoc($checkProductQuantityQuery);
            $totalProductQuantity = $productQtyData['quantity'] - $quantity;

            $dataUpdate = [
                'quantity' => $totalProductQuantity
            ];

            // Update the product quantity in the database
            $updateProductQty = update('products', $productId, $dataUpdate); // Assuming `update()` is a function that updates DB
            if (!$updateProductQty) {
                jsonResponse(500, 'error', 'Failed to update product quantity');
            }
        }

        // Clear session variables
        unset($_SESSION['productItemIds']);
        unset($_SESSION['productItems']);
        unset($_SESSION['cphone']);
        unset($_SESSION['payment_mode']);
        unset($_SESSION['invoice_no']);

        // Send success response
        jsonResponse(200, 'success', 'Order Placed Successfully');

    } else {
        // If customer not found
        jsonResponse(404, 'warning', 'No Customer Found');
    }
}



?>


        


           

            
           
                       

                        
            
            

       
   