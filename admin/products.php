<?php include('includes/header.php'); 
include ('../config/dbc.php');

?>

<div class="container-fluid px-4">
     <div class="card mt-4 shadow-sm">
     <div class="card-header">
        <h4 class="mb-0">Products
            <a href="products-create.php" class="btn btn-primary float-end">Add Products</a>
         </h4>
    </div>
    <div class="card-body">
      <!---- alert message ---->
    <?php alertMessage();?>
             
    <?php
       
       
       
       
       $products = getAll('products');
       if(!$products) {
        echo '<h4>Something Went Wrong...! </h4>';
        return false;

       }
       if(mysqli_num_rows($products) > 0)
          {
     ?>
            
          <!--- admin table ---->

       <div class="table-responsive">
          <table class="table table-striped table-bordered">
             <thead>
                <tr>
                    <th> ID</th>
                    <th> Image</th>
                    <th> Name</th>
                    <th> Status</th>
                    <th> Action</th>
                </tr>
            </thead>
            <tbody>
              
                <?php foreach($products as $item) : ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td>
                        <img src="../<?= $item['image']; ?>" style="width:50px;height:50px;" alt="Img" /></td>
                    <td><?= $item['name'] ?></td>
        
                    <td>

                    <?php 
                       if($item['status'] == 1){
                        echo '<span class="badge bg-danger">Hidden</span>';
                       }else{
                        echo '<span class="badge bg-primary">Visible</span>';

                       }
                       ?>
             <!--- edit & delete button ---->
                        <a href="products-edit.php?id=<?= $item['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                        <a href="products-delete.php?id=<?= $item['id']; ?>" 
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Are you sure you want to delete this image')"
                        >Delete
                     </a>
                    

               </tr>
               <?php endforeach; ?>

               

                

            </tbody>
            </thead>
          </table>
         </div>
         <?php
               
                }
                else
                {
                  ?>
                  
                      <h4 class="mb-0">No Record Found</h4>
            
                 <?php
                }
                ?>
        </div>
     </div>
</div>

<?php include('includes/footer.php'); ?>