<?php include('includes/header.php'); 
?>

<div class="container-fluid px-4">
     <div class="card mt-4 shadow-sm">
     <div class="card-header">
        <h4 class="mb-0"> Add Customer
            <a href="customers.php" class="btn btn-danger float-end">Back</a>
         </h4>
    </div>
    <div class="card-body">
      
        <?php alertMessage(); ?>

          <form action="code.php" method ="POST">

          <div class="row">
            <div class="col-md-12 mb-3">
               <label for=""> Name </lable>
               <input type="text" name="name" required class="form-control" />
            </div>
            <div class="col-md-12 mb-3">
               <label for=""> Email Id </lable>
               <input type="email" name="email"  class="form-control" />
            </div>
            <div class="col-md-12 mb-3">
               <label for=""> Phone </lable>
               <input type="number" name="phone" required class="form-control" />
            </div>
        
            <div class="col-md-6">
                <lable>Status (UnChecked=Visible, Checked=Hidden)</lable>
                </br>
                <input type="checkbox" name="status" style="width:30px;height:30px" ;>
               </div>

            <div class="col-md-6 mb-3 text-end">
</br>
            <button type="submit" name="saveCustome" class="btn btn-primary">Save</button>
            </div>
r
            </div>
          </form>


        </div>
     </div>
</div>

<?php include('includes/footer.php'); ?>
