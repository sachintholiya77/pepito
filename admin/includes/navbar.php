  <?php
// This must be the very first line of code

include('../config/db_connect.php'); 


// Ensure $conn is available from the parent script (e.g., dashboard.php)
// Ensure $_SESSION['admin_id'] and $_SESSION['admin_name'] are set

$admin_name = $_SESSION['user_name'] ?? 'Admin'; // Fallback name if session not set
$profile_image_src = ''; // Initialize empty

if (isset($_SESSION['user_id'])) {
    // Fetch the 'profile_picture' path for the logged-in admin
    $sql = "SELECT profile_picture FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        // Construct the full relative path from the current partial file to the image
        // The value from the 'profile_picture' column should be like 'uploads/profile_pictures/filename.jpg'
        // From admin/partials/ to project root: '../../'
        $profile_image_src = '../uploads/users/profile/' . htmlspecialchars($user_data['profile_picture']);
    }
    $stmt->close();
}

// Fallback to a generic template image if for any reason the path is not resolved from DB
// (This is a safety net; with the DB default, it should ideally not be hit if files exist)
if (empty($profile_image_src)) {
    $profile_image_src = '../assets/images/dashboard/profile.png'; // Path to a default image from admin template assets
}

?>
  <div class="page-header row">
        <div class="header-logo-wrapper col-auto">
          <div class="logo-wrapper"><a href="index.html"><img class="img-fluid for-light" src="assets/images/logo/logo.svg" alt=""/><img class="img-fluid for-dark" src="assets/images/logo/logo2.svg" alt=""/></a></div>
        </div>
        <!-- Page Header Start-->
        <div class="header-wrapper col m-0">
          <div class="row">
            <form class="form-inline search-full col" action="#" method="get">
              <div class="form-group w-100">
                <div class="Typeahead Typeahead--twitterUsers">
                  <div class="u-posRelative">
                    <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text" placeholder="Search Pepito .." name="q" title="" autofocus>
                    <div class="spinner-border Typeahead-spinner" role="status"><span class="sr-only">Loading...</span></div><i class="close-search" data-feather="x"></i>
                  </div>
                  <div class="Typeahead-menu"></div>
                </div>
              </div>
            </form>
            <div class="header-logo-wrapper col-auto p-0">
              <div class="logo-wrapper"><a href="index.html"><img class="img-fluid" src="assets/images/logo/logo.png" alt=""></a></div>
              <div class="toggle-sidebar">
                <svg class="stroke-icon sidebar-toggle status_toggle middle">
                  <use href="assets/svg/icon-sprite.svg#toggle-icon"></use>
                </svg>
              </div>
            </div>
            <div class="nav-right col-xxl-8 col-xl-6 col-md-7 col-8 pull-right right-header p-0 ms-auto">
              <ul class="nav-menus">
                <li>                         <span class="header-search">
                    <svg>
                      <use href="assets/svg/icon-sprite.svg#search"></use>
                    </svg></span></li>
                <li>
                  <div class="form-group w-100">
                    <div class="Typeahead Typeahead--twitterUsers">
                      <div class="u-posRelative d-flex align-items-center">
                        <svg class="search-bg svg-color"> 
                          <use href="assets/svg/icon-sprite.svg#search"></use>
                        </svg>
                        <input class="demo-input py-0 Typeahead-input form-control-plaintext w-100" type="text" placeholder="Search Pepito .." name="q" title="">
                      </div>
                    </div>
                  </div>
                </li>
                <li class="onhover-dropdown">
                  <div class="notification-box">
                    <svg>
                      <use href="assets/svg/icon-sprite.svg#notification"></use>
                    </svg><span class="badge rounded-pill badge-primary">4 </span>
                  </div>
                  <div class="onhover-show-div notification-dropdown">
                    <h5 class="f-18 f-w-600 mb-0 dropdown-title">Notitications                               </h5>
                    <ul class="notification-box">
                      <li class="d-flex"> 
                        <div class="flex-shrink-0 bg-light-primary"><img src="assets/images/dashboard/icon/wallet.png" alt="Wallet"></div>
                        <div class="flex-grow-1"> <a href="../template/letter-box.html">
                            <h6>New daily offer added</h6></a>
                          <p>New user-only offer added</p>
                        </div>
                      </li>
                      <li class="d-flex"> 
                        <div class="flex-shrink-0 bg-light-info"><img src="assets/images/dashboard/icon/shield-dne.png" alt="Shield-dne"></div>
                        <div class="flex-grow-1"> <a href="../template/letter-box.html">
                            <h6>Product Evaluation</h6></a>
                          <p>Changed to a new workflow</p>
                        </div>
                      </li>
                      <li class="d-flex"> 
                        <div class="flex-shrink-0 bg-light-warning"><img src="assets/images/dashboard/icon/graph.png" alt="Graph"></div>
                        <div class="flex-grow-1"> <a href="../template/letter-box.html">
                            <h6>Return of a Product</h6></a>
                          <p>452 items were returned</p>
                        </div>
                      </li>
                      <li class="d-flex"> 
                        <div class="flex-shrink-0 bg-light-tertiary"><img src="assets/images/dashboard/icon/ticket-star.png" alt="Ticket-star"></div>
                        <div class="flex-grow-1"> <a href="../template/letter-box.html">
                            <h6>Recently Paid</h6></a>
                          <p>Mastercard payment of $343</p>
                        </div>
                      </li>
                      <li><a class="f-w-700" href="../template/letter-box.html">Check all     </a></li>
                    </ul>
                  </div>
                </li>
               
                <li>
                  <div class="mode">
                    <svg>
                      <use href="assets/svg/icon-sprite.svg#moon"></use>
                    </svg>
                  </div>
                </li>
              
              
                <li class="profile-nav onhover-dropdown px-0 py-0">
               <div class="d-flex profile-media align-items-center">
                    <img class="img-40 rounded-circle" src="<?php echo $profile_image_src; ?>" alt="<?php echo htmlspecialchars($admin_name); ?> Profile">
                    <div class="flex-grow-1">
                        <span><?php echo htmlspecialchars($admin_name); ?></span>
                        <p class="mb-0 font-outfit">Admin<i class="fa fa-angle-down"></i></p>
                    </div>
                </div>
                  <ul class="profile-dropdown onhover-show-div">
                    <li><a href="../template/private-chat.html"><i data-feather="user"></i><span>Account </span></a></li>
                    <li><a href="../logout.php"><i data-feather="log-in"> </i><span>Logout</span></a></li>
                  </ul>
                </li>
              </ul>
            </div>
            <script class="result-template" type="text/x-handlebars-template">
              <div class="ProfileCard u-cf">                        
              <div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
              <div class="ProfileCard-details">
              <div class="ProfileCard-realName">{{name}}</div>
              </div>
              </div>
            </script>
            <script class="empty-template" type="text/x-handlebars-template"><div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div></script>
          </div>
        </div>
        <!-- Page Header Ends                              -->
      </div>

      