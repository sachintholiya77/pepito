<?php
// Include the admin header partial (handles session check and common HTML head)
include 'includes/header.php'; 
include 'includes/navbar.php'; 
 



// Fetch user data from the database
$users = [];
// Select specific columns from the 'users' table for display
$sql = "SELECT id, name, email, phone_number, profile_picture, is_verified, created_at FROM users WHERE role = 'customer' ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
// Free result set for larger datasets
if (isset($result) && is_object($result)) {
    $result->free();
}

include 'includes/sidebar.php'; 
?>
<!-- <link rel="stylesheet" href="  assets/css/vendors/datatables.css"> -->
<div class="page-body">
<div class="content-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-no-border">
                    </div>
                    <div class="card-body">
                        <div class="dt-ext table-responsive theme-scrollbar">
                            <div id="multilevel-btn_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                <table class="display dataTable" id="users-btn" role="grid" aria-describedby="multilevel-btn_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="sorting_asc" tabindex="0" aria-controls="multilevel-btn" rowspan="1" colspan="1" aria-sort="ascending">ID</th>
                                            <th class="sorting" tabindex="0" aria-controls="multilevel-btn" rowspan="1" colspan="1">Name</th>
                                            <th class="sorting" tabindex="0" aria-controls="multilevel-btn" rowspan="1" colspan="1">Email</th>
                                            <th class="sorting" tabindex="0" aria-controls="multilevel-btn" rowspan="1" colspan="1">Phone Number</th>
                                            <th class="sorting" tabindex="0" aria-controls="multilevel-btn" rowspan="1" colspan="1">Verified Status</th>
                                            <th class="sorting" tabindex="0" aria-controls="multilevel-btn" rowspan="1" colspan="1">Join Date</th>
                                            <th class="sorting" tabindex="0" aria-controls="multilevel-btn" rowspan="1" colspan="1">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($users)): ?>
                                            <?php foreach ($users as $user): ?>
                                                <tr role="row">
                                                    <td class="sorting_1">#<?php echo htmlspecialchars($user['id']); ?></td>
                                                    <td>
                                                        <div class="d-flex profile-media align-items-center">
                                                            <?php
                                                            $profile_img_src = '';
                                                            if (!empty($user['profile_picture'])) {
                                                                // Path from admin/users.php to uploads/profile_pictures/
                                                                $profile_img_src = '../uploads/users/profile/' . htmlspecialchars($user['profile_picture']);
                                                            } else {
                                                                // Default image from admin template assets relative to admin/
                                                                $profile_img_src = 'assets/images/users/4.jpg'; // Or your specific default
                                                            }
                                                            ?>
                                                            <img class="img-30 rounded-circle me-2" src="<?php echo $profile_img_src; ?>" alt="Profile Picture">
                                                            <div class="flex-grow-1">
                                                                <span><?php echo htmlspecialchars($user['name']); ?></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                    <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
                                                    <td>
                                                        <?php if ($user['is_verified'] == 1): ?>
                                                            <span class="badge light badge-success">Verified</span>
                                                        <?php else: ?>
                                                            <span class="badge light badge-warning">Unverified</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo date('d M Y, h:i A', strtotime($user['created_at'])); ?></td>
                                                    <td>
                                                        <ul class="action"> 
                                                            <li class="edit"> <a href="edit-user.php?id=<?php echo $user['id']; ?>"><i class="icon-pencil-alt"></i></a></li>
                                                            <li class="delete"><a href="delete-user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');"><i class="icon-trash"></i></a></li>
                                                            </ul>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center">No customer data found.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone Number</th>
                                            <th>Verified Status</th>
                                            <th>Join Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div class="dataTables_info" id="multilevel-btn_info" role="status" aria-live="polite"></div>
                                <div class="dataTables_paginate paging_simple_numbers" id="multilevel-btn_paginate"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php
// Include the admin footer partial
include 'includes/footer.php';
?>
<script src="assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.buttons.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/jszip.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/buttons.colVis.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/pdfmake.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/vfs_fonts.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.autoFill.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.select.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/buttons.html5.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/buttons.print.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.responsive.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.keyTable.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.colReorder.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.fixedHeader.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/dataTables.scroller.min.js"></script>
    <script src="assets/js/datatable/datatable-extension/custom.js"></script>
    <script src="assets/js/tooltip-init.js"></script>

    <script>
         $("#users-btn").DataTable({
      dom: "Bfrtip",
      buttons: [
        {
          extend: "collection",
          text: "Table control",
          buttons: [
            "colvis",
          ],
        },
      ],
    });
    </script>
