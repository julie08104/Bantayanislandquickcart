<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<aside class="main-sidebar sidebar bg-light" style="border: 1px solid silver;">
    <img src="dist/img/images1.png" alt="logo" class="brand-image" style="display: block; margin: 5px auto; width: 180px; height: auto;">

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Home Menu Item -->
                <li class="nav-item has-treeview <?php echo isset($_GET['page']) && $_GET['page'] == 'home' ? 'menu-open' : '';?>">
                    <a href="index.php" class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] == 'home' ? 'active' : '';?>">
                        <i class="fas fa-home"></i>
                        <p>Home</p>
                    </a>
                </li>

                <!-- Customer List Menu Item -->
                <li class="nav-item">
                    <a href="index.php?page=costumer" class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] == 'costumer' ? 'active' : '';?>">
                        <i class="fas fa-users"></i>
                        <p>Customer List</p>
                    </a>
                </li>

                <!-- Rider List Menu Item -->
                <li class="nav-item">
                    <a href="index.php?page=buy_list" class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] == 'page=buy_list' ? 'active' : '';?>">
                        <i class="fas fa-biking"></i>
                        <p>Rider List</p>
                    </a>
                </li>

                <!-- Buy List Menu Item -->
         <li class="nav-item">
                    <a href="index.php?page=admin" class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] == 'admin' ? 'active' : '';?>">
                        <i class="fas fa-users"></i>
                        <p>User Admin</p>
                    </a>
                </li> -

                <!-- Refund Buy List Menu Item -->
                <li class="nav-item">
                    <a href="index.php?page=buy_refund_list" class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] == 'buy_refund_list' ? 'active' : '';?>">
                        <i class="fas fa-undo"></i>
                        <p>Refund Buy List</p>
                    </a>
                </li>

                <!-- Additional Menu Items -->
                <!-- Uncomment and add icons as needed -->
                <!--
                <li class="nav-item">
                    <a href="index.php?page=suppliar_list" class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] == 'suppliar_list' ? 'active' : '';?>">
                        <i class="fas fa-truck"></i>
                        <p>Suppliar List</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=backup_database" class="nav-link <?php echo isset($_GET['page']) && $_GET['page'] == 'backup_database' ? 'active' : '';?>">
                        <i class="fas fa-database"></i>
                        <p>Backup Database</p>
                    </a>
                </li>
                -->
            </ul>
        </nav>
    </div>
</aside>

