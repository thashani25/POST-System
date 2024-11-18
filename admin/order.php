<?php include('includes/header.php'); 
?>

<div class="container-fluid px-4">
     <div class="card mt-4 shadow-sm">
     <div class="card-header">
        <h4 class="mb-0">Orders
            <a href="products.php" class="btn btn-danger float-end">Back</a>
         </h4>
    </div>
    <div class="card-body">

    <?php
    $query = "SELECT * FROM orders o, customer c ";

    ?>


    </div>
     </div>
</div>

<?php include('includes/footer.php'); ?>
