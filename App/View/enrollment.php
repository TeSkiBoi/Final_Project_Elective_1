<!DOCTYPE html>
<html lang="en">
    <?php
        // Include authentication protection
        require_once __DIR__ . '/middleware/ProtectAuth.php';
        
        // Include RBAC protection (Admin and Staff)
        require_once __DIR__ . '/middleware/RBACProtect.php';
        include 'template/header.php';
    ?>
    
    <body class="sb-nav-fixed">

        <?php include 'template/header_navigation.php'; ?>

        <div id="layoutSidenav">
            <?php include 'template/sidebar_navigation.php'; ?>
            <!--CONTENT OF THE PAGE -->
            <div id="layoutSidenav_content">

                <!-- CONTENT HERE -->
                 <main>
                    <div class="container-fluid px-4">
                        <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h1 class="mt-4">Households</h1>
                                    <ol class="breadcrumb mb-4">
                                        <li class="breadcrumb-item"><a href="dashboard.html">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Households</li>
                                    </ol>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add New Household
                                    </button>
                                </div>
                            </div>
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-home me-1"></i>
                                    Households List
                                </div>
                            </div>
                            <div class="card-body">
                                <?php
                                    // Fetch household records from database
                                    require_once __DIR__ . '/../Config/Database.php';
                                    $db = new Database();
                                    $conn = $db->connect();

                                    $households = [];
                                    $sql = "SELECT firstname, middlename, lastname, birthday, age, occupation, income FROM households ORDER BY lastname, firstname";
                                    if ($res = $conn->query($sql)) {
                                        while ($r = $res->fetch_assoc()) {
                                            $households[] = $r;
                                        }
                                        $res->free();
                                    }
                                    $db->closeConnection();
                                ?>

                                <table id="table" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>NAME/MIDDLE/SURNAME</th>
                                            <th>BIRTHDAY</th>
                                            <th>AGE</th>
                                            <th>OCCUPATION</th>
                                            <th>INCOME</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($households)): ?>
                                            <?php foreach ($households as $row): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars((isset($row['firstname']) ? $row['firstname'] : '') . (isset($row['middlename']) ? $row['middlename'] : '') . ' ' . (isset($row['lastname']) ? $row['lastname'] : '')); ?></td>
                                                    <td><?php echo htmlspecialchars(isset($row['birthday']) ? $row['birthday'] : ''); ?></td>
                                                    <td><?php echo htmlspecialchars(isset($row['age']) ? $row['age'] : ''); ?></td>
                                                    <td><?php echo htmlspecialchars(isset($row['occupation']) ? $row['occupation'] : ''); ?></td>
                                                    <td><?php echo htmlspecialchars(isset($row['income']) ? $row['income'] : ''); ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#updateHouseholdModal">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteHouseholdModal">
                                                            <i class="fas fa-trash-alt"></i> Delete
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">No households found.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
                <!-- END CONTENT-->
                <?php include 'template/footer.php'; ?>
            </div>
        </div>
        <!-- Logout Modal -->
        <?php include 'template/script.php'; ?>
    </body>
</html>