<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Admin
                <a href="admins.php" class="btn btn-danger float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <form action="code.php" method="POST">
                <?php 
                if (isset($_GET['id'])) {
                    $adminId = $_GET['id'] ?? '';
                    if ($adminId == '') {
                        echo '<h5>No Id Found</h5>';
                        return false;
                    }
                } else {
                    echo '<h5>No Id given in params</h5>';
                    return false;
                }

                $adminData = getById('admins', $adminId);
                if ($adminData) {
                    if ($adminData['status'] == 200) {
                ?>
                <!-----add to edit page details---->
                        <input type="hidden" name="adminId" value="<?= htmlspecialchars($adminData['data']['id']); ?>">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label>Name *</label>
                                <input type="text" name="name" required value="<?= htmlspecialchars($adminData['data']['name']); ?>" class="form-control" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Email *</label>
                                <input type="email" name="email" required value="<?= htmlspecialchars($adminData['data']['email']); ?>" class="form-control" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Password *</label>
                                <input type="password" name="password" class="form-control" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Phone Number *</label>
                                <input type="tel" name="phone" required value="<?= htmlspecialchars($adminData['data']['phone']); ?>" class="form-control" />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Is Ban</label>
                    </br>
                                <input type="checkbox" name="is_ban" <?= $adminData['data']['is_ban'] ? 'checked' : ''; ?> style="width:30px;height:30px;" />
                            </div>
                            <div class="col-md-12 mb-3 text-end">
                                <button type="submit" name="updateAdmin" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                <?php
                    } else {
                        echo '<h5>' . htmlspecialchars($adminData['message']) . '</h5>';
                    }
                } else {
                    echo 'Something Went Wrong';
                    return false;
                }
                ?>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
