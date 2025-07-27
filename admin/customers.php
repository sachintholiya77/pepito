<?php
$page_title = "Customers";

// Include required files
include 'includes/header.php';
include 'includes/auth.php';
include 'includes/navbar.php';
include 'includes/sidebar.php';
// include 'includes/db_connection.php'; // Assuming this contains $conn

// Initialize an array for users
$users = [];

// Query to fetch customers
$sql = "SELECT id, name, email, phone_number, profile_picture, is_verified, created_at 
        FROM users 
        WHERE role = 'customer' 
        ORDER BY created_at DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    $result->free();
}
?>

<div class="content-body">
    <div class="container-fluid">
        <div class="form-head d-flex mb-3 align-items-start">
            <div class="me-auto d-none d-lg-block">
                <h2 class="text-primary font-w600 mb-0">Customers</h2>
                <!-- <p class="mb-0">Here is your general customers list data</p> -->
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table id="example5" class="display mb-4 dataTablesCard w-100" style="min-width: 845px;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Profile</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Verified</th>
                                <th>Join Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td>#<?= htmlspecialchars($user['id']) ?></td>
                                        <td>
                                            <div class="customer-image">
                                                <?php
                                                $profileImgSrc = !empty($user['profile_picture']) 
                                                    ? '../uploads/users/profile/' . htmlspecialchars($user['profile_picture']) 
                                                    : 'assets/images/users/4.jpg';
                                                ?>
                                                <img src="<?= $profileImgSrc ?>" width="40" alt="Profile Picture" class="rounded-circle">
                                            </div>
                                        </td>
                                        <td><strong><?= htmlspecialchars($user['name']) ?></strong></td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td><?= htmlspecialchars($user['phone_number']) ?></td>
                                        <td>
                                            <?php if ($user['is_verified']): ?>
                                                <span class="badge light badge-success">Verified</span>
                                            <?php else: ?>
                                                <span class="badge light badge-warning">Unverified</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('d M Y, h:i A', strtotime($user['created_at'])) ?></td>
                                        <td class="text-end">
                                            <div class="dropdown ms-auto text-end c-pointer">
                                                <div class="btn-link" data-bs-toggle="dropdown">
                                                    <svg width="24px" height="24px" viewBox="0 0 24 24">
                                                        <g fill="none">
                                                            <circle fill="#000" cx="5" cy="12" r="2"></circle>
                                                            <circle fill="#000" cx="12" cy="12" r="2"></circle>
                                                            <circle fill="#000" cx="19" cy="12" r="2"></circle>
                                                        </g>
                                                    </svg>
                                                </div>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="user-detail.php?id=<?= urlencode($user['id']) ?>">View Detail</a>
                                                    <a class="dropdown-item" href="edit-user.php?id=<?= urlencode($user['id']) ?>">Edit</a>
                                                    <a class="dropdown-item" href="delete-user.php?id=<?= urlencode($user['id']) ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">No customer data found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<!-- Scripts -->
<script src="assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="assets/js/deznav-init.js"></script>
<script src="assets/vendor/datatables/js/jquery.dataTables.min.js"></script>

<script>
    (function($) {
        $('#example5').DataTable({
            searching: false,
            paging: true,
            select: false,
            lengthChange: false
        });
    })(jQuery);
</script>
