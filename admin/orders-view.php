<?php include('includes/header.php'); 
?>

<div class="container-fluid px-4">
     <div class="card mt-4 shadow-sm">
     <div class="card-header">
        <h4 class="mb-0">order View
            <a href="orders.php" class="btn btn-danger mx-2 btn-sm float-end"> Back</a>
        <h4>  
        
    </div>
    <div class="card-body">
        

    <?php alertMessage(); ?>

    <?php
        if(isset($_GET['track']))
        {
           $trackingNo = validate($_GET['track']);

           $query = "SELECT o.*, c.* FROM orders o, customers c 
                      WHERE c.id = o.customer_id AND tracking_no='$trackingNo 
           'ORDER BY o.id DESC";

           $orders = mysqli_query($conn, $query);
           if($orders)
           {
            if(mysqli_num_rows($orders) > 0){

               $orderData = mysqli_fetch_assoc($orders);
               $orderId = $orderData['id'];

               ?>
               <div class="card card-body shadow border-1 mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Order Details</h4>
                        <lable class="mb-1">
                            Tracking No: <span class="few-bold"><?= $orderData['tracking_no']; ?></span>
                        </lable>
            </br>        
                        <lable class="mb-1">
                            Order Date: <span class="few-bold"><?= $orderData['order_date']; ?></span>
                        </lable>
            </br>
                        <lable class="mb-1">
                           Order Status: <span class="few-bold"><?= $orderData['order_status']; ?></span>
                        </lable>
            </br>   
            <lable class="mb-1">
                            Payment Mode: <span class="few-bold"><?= $orderData['payment_mode']; ?></span>
                        </lable>
               </br>  
            </div> 
                  
                 <div class="col-md-6">
                    <h4>User Details</h4>
                    <lable class="mb-1">
                           Full Name: <span class="few-bold"><?= $orderData['name']; ?></span>
                    </lable>
            </br> 
                    <lable class="mb-1">
                           Email Address: <span class="few-bold"><?= $orderData['email']; ?></span>
                        </lable>
            </br>     
                    <lable class="mb-1">
                           Phone Number: <span class="few-bold"><?= $orderData['phone']; ?></span>
                        </lable>
            </br> 
            </div>  


            </div>
            </div>
            </div>



                 <?php
            }else{
                echo '<h5>No Record Found</h5>';
                return false;
            } 
        }
           else
           {
            echo '<h5>Something Went Wrong</h5>';
           }
        
        }
    
    ?>




    </div>
</div>
</div>

    
<?php include('includes/footer.php'); ?>