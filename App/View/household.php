<!DOCTYPE html>
<html lang="en">
    <?php 
        // Include authentication protection
        require_once __DIR__ . '/middleware/ProtectAuth.php';
        
        // Include RBAC protection (Admin and Staff)
        require_once __DIR__ . '/middleware/RBACProtect.php';
        
        include 'template/header.php';
        require_once __DIR__ . '/../Model/Household.php';
        
        // Initialize Household model
        $householdModel = new Household();
        $households = $householdModel->getAll();
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
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createHouseholdModal">
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
                                <table id="table" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Household ID</th>
                                            <th>Family No</th>
                                            <th>Full Name</th>
                                            <th>Address</th>
                                            <th>Income</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($households && count($households) > 0): ?>
                                            <?php foreach ($households as $household): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($household['household_id']); ?></td>
                                                    <td><?php echo htmlspecialchars($household['family_no']); ?></td>
                                                    <td><?php echo htmlspecialchars($household['full_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($household['address']); ?></td>
                                                    <td><?php echo number_format($household['income'] ?? 0, 2); ?></td>
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
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    <i class="fas fa-inbox me-2"></i>No households found. Click "Add New Household" to create one.
                                                </td>
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
        <!-- Create / Update / Delete Modals -->
        <div class="modal fade" id="createHouseholdModal" tabindex="-1" aria-labelledby="createHouseholdModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createHouseholdModalLabel"><i class="fas fa-plus me-2"></i>Create Household</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="createHouseholdForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="household_id" class="form-label">Household ID <span class="text-danger">*</span></label>
                                <input type="text" id="household_id" name="household_id" class="form-control" required placeholder="e.g., HH-2024-001" maxlength="20">
                            </div>
                            <div class="mb-3">
                                <label for="family_no" class="form-label">Family No <span class="text-danger">*</span></label>
                                <input type="number" id="family_no" name="family_no" class="form-control" required placeholder="Enter family number" min="1">
                            </div>
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" id="full_name" name="full_name" class="form-control" required placeholder="Enter full name" maxlength="150">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                <input type="text" id="address" name="address" class="form-control" required placeholder="Enter complete address" maxlength="255">
                            </div>
                            <div class="mb-3">
                                <label for="income" class="form-label">Household Income</label>
                                <input type="number" id="income" name="income" class="form-control" step="0.01" min="0" placeholder="0.00">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Create Household</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="updateHouseholdModal" tabindex="-1" aria-labelledby="updateHouseholdModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateHouseholdModalLabel"><i class="fas fa-edit me-2"></i>Update Household</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="updateHouseholdForm">
                        <div class="modal-body">
                            <input type="hidden" id="household_id_edit" name="household_id">
                            <div class="mb-3">
                                <label for="household_id_display" class="form-label">Household ID</label>
                                <input type="text" id="household_id_display" class="form-control" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="family_no_edit" class="form-label">Family No <span class="text-danger">*</span></label>
                                <input type="number" id="family_no_edit" name="family_no" class="form-control" required placeholder="Enter family number" min="1">
                            </div>
                            <div class="mb-3">
                                <label for="full_name_edit" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" id="full_name_edit" name="full_name" class="form-control" required placeholder="Enter full name" maxlength="150">
                            </div>
                            <div class="mb-3">
                                <label for="address_edit" class="form-label">Address <span class="text-danger">*</span></label>
                                <input type="text" id="address_edit" name="address" class="form-control" required placeholder="Enter complete address" maxlength="255">
                            </div>
                            <div class="mb-3">
                                <label for="income_edit" class="form-label">Household Income</label>
                                <input type="number" id="income_edit" name="income" class="form-control" step="0.01" min="0" placeholder="0.00">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Update Household</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteHouseholdModal" tabindex="-1" aria-labelledby="deleteHouseholdModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteHouseholdModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>Delete Household</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="deleteHouseholdForm">
                        <div class="modal-body">
                            <input type="hidden" id="delete_household_id" name="household_id">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Warning!</strong> This action cannot be undone.
                            </div>
                            <div class="mb-3">
                                <label for="delete_household_id_display" class="form-label">Household ID</label>
                                <input type="text" id="delete_household_id_display" class="form-control" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_delete_household" class="form-label">
                                    Type the household ID to confirm <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="confirm_delete_household" class="form-control" required placeholder="Type household ID to confirm">
                                <small class="form-text text-muted">This is a safety measure to prevent accidental deletion.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt me-2"></i>Delete Household</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php include 'template/script.php'; ?>

        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // API Base URL
            const API_URL = '../../App/Controller/HouseholdController.php';

            /**
             * Create Household Form Submission
             */
            document.getElementById('createHouseholdForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const household_id = document.getElementById('household_id').value.trim();
                const family_no = document.getElementById('family_no').value;
                const full_name = document.getElementById('full_name').value.trim();
                const address = document.getElementById('address').value.trim();
                const income = document.getElementById('income').value;

                // Validation
                if (!household_id || !family_no || !full_name || !address) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validation Error',
                        text: 'Please fill all required fields (Household ID, Family No, Full Name, and Address).',
                        confirmButtonColor: '#6ec207'
                    });
                    return;
                }

                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';

                try {
                    const response = await fetch(API_URL + '?action=create', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            household_id: household_id,
                            family_no: parseInt(family_no),
                            full_name: full_name,
                            address: address,
                            income: income ? parseFloat(income) : 0.00
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Success Alert
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: result.message,
                            confirmButtonColor: '#6ec207'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Reset form
                                document.getElementById('createHouseholdForm').reset();

                                // Close modal
                                const modal = bootstrap.Modal.getInstance(document.getElementById('createHouseholdModal'));
                                modal.hide();

                                // Reload page to show new household
                                setTimeout(() => {
                                    location.reload();
                                }, 500);
                            }
                        });
                    } else {
                        // Error handling based on error type
                        let errorTitle = 'Error';
                        let errorMessage = result.message;

                        if (result.message.includes('already exists')) {
                            errorTitle = 'Duplicate Entry';
                        }

                        Swal.fire({
                            icon: 'error',
                            title: errorTitle,
                            text: errorMessage,
                            confirmButtonColor: '#dc3545'
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error',
                        text: 'Failed to connect to the server. Please check your connection and try again.',
                        confirmButtonColor: '#dc3545'
                    });
                } finally {
                    // Restore button state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });

            /**
             * Update Household Form Submission
             */
            document.getElementById('updateHouseholdForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const householdId = document.getElementById('household_id_edit').value;
                const family_no = document.getElementById('family_no_edit').value;
                const full_name = document.getElementById('full_name_edit').value.trim();
                const address = document.getElementById('address_edit').value.trim();
                const income = document.getElementById('income_edit').value;

                if (!householdId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error',
                        text: 'Household ID is missing.',
                        confirmButtonColor: '#6ec207'
                    });
                    return;
                }

                if (!family_no || !full_name || !address) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validation Error',
                        text: 'Please fill all required fields (Family No, Full Name, and Address).',
                        confirmButtonColor: '#6ec207'
                    });
                    return;
                }

                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';

                try {
                    const response = await fetch(API_URL + '?action=update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            household_id: householdId,
                            family_no: parseInt(family_no),
                            full_name: full_name,
                            address: address,
                            income: income ? parseFloat(income) : 0.00
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: result.message,
                            confirmButtonColor: '#6ec207'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const modal = bootstrap.Modal.getInstance(document.getElementById('updateHouseholdModal'));
                                modal.hide();
                                setTimeout(() => {
                                    location.reload();
                                }, 500);
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Update Failed',
                            text: result.message,
                            confirmButtonColor: '#dc3545'
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error',
                        text: 'Failed to connect to the server.',
                        confirmButtonColor: '#dc3545'
                    });
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });

            /**
             * Delete Household Form Submission
             */
            document.getElementById('deleteHouseholdForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const householdId = document.getElementById('delete_household_id').value;
                const householdIdDisplay = document.getElementById('delete_household_id_display').value;
                const confirmDelete = document.getElementById('confirm_delete_household').value.trim();

                if (confirmDelete !== householdIdDisplay) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Confirmation Failed',
                        text: 'The household ID does not match. Please type the correct household ID.',
                        confirmButtonColor: '#6ec207'
                    });
                    return;
                }

                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...';

                try {
                    const response = await fetch(API_URL + '?action=delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            household_id: householdId
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: result.message,
                            confirmButtonColor: '#6ec207'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const modal = bootstrap.Modal.getInstance(document.getElementById('deleteHouseholdModal'));
                                modal.hide();
                                setTimeout(() => {
                                    location.reload();
                                }, 500);
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Deletion Failed',
                            text: result.message,
                            confirmButtonColor: '#dc3545'
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error',
                        text: 'Failed to connect to the server.',
                        confirmButtonColor: '#dc3545'
                    });
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });

            /**
             * Handle Edit Button Click - Populate Update Modal
             */
            document.addEventListener('click', function(e) {
                if (e.target.closest('button[data-bs-target="#updateHouseholdModal"]')) {
                    const row = e.target.closest('tr');
                    const householdId = row.querySelector('td:nth-child(1)').textContent;
                    const family_no = row.querySelector('td:nth-child(2)').textContent;
                    const full_name = row.querySelector('td:nth-child(3)').textContent;
                    const address = row.querySelector('td:nth-child(4)').textContent;
                    const income = row.querySelector('td:nth-child(5)').textContent;

                    document.getElementById('household_id_edit').value = householdId;
                    document.getElementById('household_id_display').value = householdId;
                    document.getElementById('family_no_edit').value = family_no;
                    document.getElementById('full_name_edit').value = full_name;
                    document.getElementById('address_edit').value = address;
                    document.getElementById('income_edit').value = parseFloat(income.replace(/,/g, ''));
                }

                if (e.target.closest('button[data-bs-target="#deleteHouseholdModal"]')) {
                    const row = e.target.closest('tr');
                    const householdId = row.querySelector('td:nth-child(1)').textContent;

                    document.getElementById('delete_household_id').value = householdId;
                    document.getElementById('delete_household_id_display').value = householdId;
                    document.getElementById('confirm_delete_household').value = '';
                }
            });

            /**
             * Clear forms when modals are hidden
             */
            document.getElementById('createHouseholdModal').addEventListener('hide.bs.modal', function() {
                document.getElementById('createHouseholdForm').reset();
            });

            document.getElementById('updateHouseholdModal').addEventListener('hide.bs.modal', function() {
                document.getElementById('updateHouseholdForm').reset();
            });

            document.getElementById('deleteHouseholdModal').addEventListener('hide.bs.modal', function() {
                document.getElementById('deleteHouseholdForm').reset();
            });
        </script>
    </body>
</html>