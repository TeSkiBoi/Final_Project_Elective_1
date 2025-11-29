<!DOCTYPE html>
<html lang="en">
    <?php 
        // Include authentication protection
        require_once __DIR__ . '/middleware/ProtectAuth.php';
        
        // Include RBAC protection (Admin and Staff)
        require_once __DIR__ . '/middleware/RBACProtect.php';
        
        include 'template/header.php';
        require_once __DIR__ . '/../Model/Resident.php';
        require_once __DIR__ . '/../Model/Household.php';
        
        // Initialize models
        $residentModel = new Resident();
        $residents = $residentModel->getAll();
        
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
                                <h1 class="mt-4">Residents</h1>
                                <ol class="breadcrumb mb-4">
                                    <li class="breadcrumb-item"><a href="dashboard.html">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Residents</li>
                                </ol>
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createResidentModal">
                                    <i class="fas fa-plus"></i> Add New Resident
                                </button>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-users me-1"></i>
                                    Residents List
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="table" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Full Name</th>
                                            <th>Birthdate</th>
                                            <th>Gender</th>
                                            <th>Occupation</th>
                                            <th>Household No</th>
                                            <th>Relation to Head</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($residents && count($residents) > 0): ?>
                                            <?php foreach ($residents as $resident): ?>
                                                <tr data-household-id="<?php echo htmlspecialchars($resident['household_id']); ?>">
                                                    <td><?php echo htmlspecialchars($resident['id']); ?></td>
                                                    <td><?php echo htmlspecialchars($resident['full_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($resident['birthdate'] ?? ''); ?></td>
                                                    <td><?php echo htmlspecialchars($resident['gender']); ?></td>
                                                    <td><?php echo htmlspecialchars($resident['occupation'] ?? 'N/A'); ?></td>
                                                    <td><?php echo htmlspecialchars($resident['household_no'] ?? 'N/A'); ?></td>
                                                    <td>
                                                        <?php 
                                                        $relation = $resident['relation_to_head'];
                                                        $badge_class = '';
                                                        switch($relation) {
                                                            case 'Head': $badge_class = 'bg-primary'; break;
                                                            case 'Spouse': $badge_class = 'bg-success'; break;
                                                            case 'Son': case 'Daughter': $badge_class = 'bg-info'; break;
                                                            default: $badge_class = 'bg-secondary';
                                                        }
                                                        ?>
                                                        <span class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($relation); ?></span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#updateResidentModal">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteResidentModal">
                                                            <i class="fas fa-trash-alt"></i> Delete
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="8" class="text-center text-muted py-4">
                                                    <i class="fas fa-inbox me-2"></i>No residents found. Click "Add New Resident" to create one.
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

        <!-- Create Resident Modal -->
        <div class="modal fade" id="createResidentModal" tabindex="-1" aria-labelledby="createResidentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createResidentModalLabel"><i class="fas fa-plus me-2"></i>Create Resident</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="createResidentForm" method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" id="full_name" name="full_name" class="form-control" required placeholder="e.g., Juan Dela Cruz">
                            </div>
                            <div class="mb-3">
                                <label for="birthdate" class="form-label">Birthdate <span class="text-danger">*</span></label>
                                <input type="date" id="birthdate" name="birthdate" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                <select id="gender" name="gender" class="form-select" required>
                                    <option value="">-- Select Gender --</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="occupation" class="form-label">Occupation</label>
                                <input type="text" id="occupation" name="occupation" class="form-control" placeholder="e.g., Teacher, Farmer">
                            </div>
                            <div class="mb-3">
                                <label for="household_id" class="form-label">Household <span class="text-danger">*</span></label>
                                <select id="household_id" name="household_id" class="form-select" required>
                                    <option value="">-- Select Household --</option>
                                    <?php foreach ($households as $household): ?>
                                        <option value="<?php echo $household['household_id']; ?>">
                                            <?php echo htmlspecialchars($household['household_id']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="relation_to_head" class="form-label">Relation to Head</label>
                                <select id="relation_to_head" name="relation_to_head" class="form-select">
                                    <option value="Other">Other</option>
                                    <option value="Head">Head</option>
                                    <option value="Spouse">Spouse</option>
                                    <option value="Son">Son</option>
                                    <option value="Daughter">Daughter</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Create Resident</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Update Resident Modal -->
        <div class="modal fade" id="updateResidentModal" tabindex="-1" aria-labelledby="updateResidentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateResidentModalLabel"><i class="fas fa-edit me-2"></i>Update Resident</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="updateResidentForm">
                        <div class="modal-body">
                            <input type="hidden" id="resident_id_edit" name="id">
                            <div class="mb-3">
                                <label for="resident_id_display" class="form-label">Resident ID</label>
                                <input type="text" id="resident_id_display" class="form-control" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="full_name_edit" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" id="full_name_edit" name="full_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="birthdate_edit" class="form-label">Birthdate <span class="text-danger">*</span></label>
                                <input type="date" id="birthdate_edit" name="birthdate" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="gender_edit" class="form-label">Gender <span class="text-danger">*</span></label>
                                <select id="gender_edit" name="gender" class="form-select" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="occupation_edit" class="form-label">Occupation</label>
                                <input type="text" id="occupation_edit" name="occupation" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="household_id_edit" class="form-label">Household <span class="text-danger">*</span></label>
                                <select id="household_id_edit" name="household_id" class="form-select" required>
                                    <option value="">-- Select Household --</option>
                                    <?php foreach ($households as $household): ?>
                                        <option value="<?php echo $household['household_id']; ?>">
                                            <?php echo htmlspecialchars($household['household_no'] . ' - ' . $household['address']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="relation_to_head_edit" class="form-label">Relation to Head</label>
                                <select id="relation_to_head_edit" name="relation_to_head" class="form-select">
                                    <option value="Other">Other</option>
                                    <option value="Head">Head</option>
                                    <option value="Spouse">Spouse</option>
                                    <option value="Son">Son</option>
                                    <option value="Daughter">Daughter</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Update Resident</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Resident Modal -->
        <div class="modal fade" id="deleteResidentModal" tabindex="-1" aria-labelledby="deleteResidentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteResidentModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>Delete Resident</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="deleteResidentForm">
                        <div class="modal-body">
                            <input type="hidden" id="delete_resident_id" name="resident_id">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Warning!</strong> This action cannot be undone.
                            </div>
                            <div class="mb-3">
                                <label for="delete_resident_name" class="form-label">Resident Name</label>
                                <input type="text" id="delete_resident_name" class="form-control" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_delete_resident" class="form-label">
                                    Type the full name to confirm <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="confirm_delete_resident" class="form-control" required placeholder="Type full name to confirm">
                                <small class="form-text text-muted">This is a safety measure to prevent accidental deletion.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt me-2"></i>Delete Resident</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Logout Modal -->
        <?php include 'template/script.php'; ?>

        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!--<script>
            // API Base URL
            const API_URL = '../../App/Controller/ResidentController.php';

            /**
             * Create Resident Form Submission
             */
            document.getElementById('createResidentForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const full_name = document.getElementById('full_name').value.trim();
                const birthdate = document.getElementById('birthdate').value;
                const gender = document.getElementById('gender').value;
                const occupation = document.getElementById('occupation').value.trim();
                const household_id = document.getElementById('household_id').value;
                const relation_to_head = document.getElementById('relation_to_head').value;

                // Validation
                if (!full_name || !birthdate || !gender || !household_id) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validation Error',
                        text: 'Please fill all required fields (Full Name, Birthdate, Gender, Household).',
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
                            full_name: full_name,
                            birthdate: birthdate,
                            gender: gender,
                            occupation: occupation || null,
                            household_id: parseInt(household_id),
                            relation_to_head: relation_to_head
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
                                document.getElementById('createResidentForm').reset();

                                // Close modal
                                const modal = bootstrap.Modal.getInstance(document.getElementById('createResidentModal'));
                                modal.hide();

                                // Reload page to show new resident
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
             * Update Resident Form Submission
             */
            document.getElementById('updateResidentForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const residentId = document.getElementById('resident_id_edit').value;
                const full_name = document.getElementById('full_name_edit').value.trim();
                const birthdate = document.getElementById('birthdate_edit').value;
                const gender = document.getElementById('gender_edit').value;
                const occupation = document.getElementById('occupation_edit').value.trim();
                const household_id = document.getElementById('household_id_edit').value;
                const relation_to_head = document.getElementById('relation_to_head_edit').value;

                if (!residentId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error',
                        text: 'Resident ID is missing.',
                        confirmButtonColor: '#6ec207'
                    });
                    return;
                }

                if (!full_name || !birthdate || !gender || !household_id) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validation Error',
                        text: 'Please fill all required fields.',
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
                            id: parseInt(residentId),
                            full_name: full_name,
                            birthdate: birthdate,
                            gender: gender,
                            occupation: occupation || null,
                            household_id: parseInt(household_id),
                            relation_to_head: relation_to_head
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
                                const modal = bootstrap.Modal.getInstance(document.getElementById('updateResidentModal'));
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
             * Delete Resident Form Submission
             */
            document.getElementById('deleteResidentForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const residentId = document.getElementById('delete_resident_id').value;
                const residentName = document.getElementById('delete_resident_name').value;
                const confirmDelete = document.getElementById('confirm_delete_resident').value.trim();

                if (confirmDelete !== residentName) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Confirmation Failed',
                        text: 'The name does not match. Please type the correct name.',
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
                            id: parseInt(residentId)
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
                                const modal = bootstrap.Modal.getInstance(document.getElementById('deleteResidentModal'));
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
                if (e.target.closest('button[data-bs-target="#updateResidentModal"]')) {
                    const row = e.target.closest('tr');
                    const residentId = row.querySelector('td:nth-child(1)').textContent;
                    const full_name = row.querySelector('td:nth-child(2)').textContent;
                    const birthdate = row.querySelector('td:nth-child(3)').textContent;
                    const gender = row.querySelector('td:nth-child(4)').textContent;
                    const occupation = row.querySelector('td:nth-child(5)').textContent;
                    const household_no = row.querySelector('td:nth-child(6)').textContent;
                    const relation_to_head = row.querySelector('td:nth-child(7)').textContent.trim();

                    // Get household_id from data attribute
                    const household_id = row.dataset.householdId || '';

                    document.getElementById('resident_id_edit').value = residentId;
                    document.getElementById('resident_id_display').value = residentId;
                    document.getElementById('full_name_edit').value = full_name;
                    document.getElementById('birthdate_edit').value = birthdate;
                    document.getElementById('gender_edit').value = gender;
                    document.getElementById('occupation_edit').value = occupation === 'N/A' ? '' : occupation;
                    document.getElementById('household_id_edit').value = household_id;
                    document.getElementById('relation_to_head_edit').value = relation_to_head;
                }

                if (e.target.closest('button[data-bs-target="#deleteResidentModal"]')) {
                    const row = e.target.closest('tr');
                    const residentId = row.querySelector('td:nth-child(1)').textContent;
                    const full_name = row.querySelector('td:nth-child(2)').textContent;

                    document.getElementById('delete_resident_id').value = residentId;
                    document.getElementById('delete_resident_name').value = full_name;
                    document.getElementById('confirm_delete_resident').value = '';
                }
            });

            /**
             * Clear forms when modals are hidden
             */
            document.getElementById('createResidentModal').addEventListener('hide.bs.modal', function() {
                document.getElementById('createResidentForm').reset();
            });

            document.getElementById('updateResidentModal').addEventListener('hide.bs.modal', function() {
                document.getElementById('updateResidentForm').reset();
            });

            document.getElementById('deleteResidentModal').addEventListener('hide.bs.modal', function() {
                document.getElementById('deleteResidentForm').reset();
            });
        </script>-->

        <script>
    // API Base URL
    const API_URL = '../../App/Controller/ResidentController.php';

    /**
     * Create Resident Form Submission
     */
    document.getElementById('createResidentForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const full_name = document.getElementById('full_name').value.trim();
        const birthdate = document.getElementById('birthdate').value;
        const gender = document.getElementById('gender').value;
        const occupation = document.getElementById('occupation').value.trim();
        const household_id = document.getElementById('household_id').value;
        const relation_to_head = document.getElementById('relation_to_head').value;

        // Validation
        if (!full_name || !birthdate || !gender || !household_id) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill all required fields (Full Name, Birthdate, Gender, Household).',
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
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    full_name,
                    birthdate,
                    gender,
                    occupation: occupation || null,
                    household_id: parseInt(household_id),
                    relation_to_head
                })
            });

            const result = await response.json();

            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: result.message,
                    confirmButtonColor: '#6ec207'
                }).then(() => {
                    document.getElementById('createResidentForm').reset();
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createResidentModal'));
                    modal.hide();
                    setTimeout(() => location.reload(), 500);
                });
            } else {
                let errorTitle = 'Error';
                if (result.message.includes('already exists')) errorTitle = 'Duplicate Entry';
                Swal.fire({
                    icon: 'error',
                    title: errorTitle,
                    text: result.message,
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
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    /**
     * Update Resident Form Submission
     */
    document.getElementById('updateResidentForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const residentId = document.getElementById('resident_id_edit').value;
        const full_name = document.getElementById('full_name_edit').value.trim();
        const birthdate = document.getElementById('birthdate_edit').value;
        const gender = document.getElementById('gender_edit').value;
        const occupation = document.getElementById('occupation_edit').value.trim();
        const household_id = document.getElementById('household_id_edit').value;
        const relation_to_head = document.getElementById('relation_to_head_edit').value;

        if (!residentId) {
            Swal.fire({
                icon: 'warning',
                title: 'Error',
                text: 'Resident ID is missing.',
                confirmButtonColor: '#6ec207'
            });
            return;
        }

        if (!full_name || !birthdate || !gender || !household_id) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill all required fields.',
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
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id: parseInt(residentId),
                    full_name,
                    birthdate,
                    gender,
                    occupation: occupation || null,
                    household_id: parseInt(household_id),
                    relation_to_head
                })
            });

            const result = await response.json();

            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: result.message,
                    confirmButtonColor: '#6ec207'
                }).then(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('updateResidentModal'));
                    modal.hide();
                    setTimeout(() => location.reload(), 500);
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
     * Delete Resident Form Submission
     */
    document.getElementById('deleteResidentForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const residentId = document.getElementById('delete_resident_id').value;
        const residentName = document.getElementById('delete_resident_name').value;
        const confirmDelete = document.getElementById('confirm_delete_resident').value.trim();

        if (confirmDelete !== residentName) {
            Swal.fire({
                icon: 'warning',
                title: 'Confirmation Failed',
                text: 'The name does not match. Please type the correct name.',
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
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: parseInt(residentId) })
            });

            const result = await response.json();

            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: result.message,
                    confirmButtonColor: '#6ec207'
                }).then(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteResidentModal'));
                    modal.hide();
                    setTimeout(() => location.reload(), 500);
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
     * Populate Update & Delete Modals
     */
    document.addEventListener('click', function(e) {
        if (e.target.closest('button[data-bs-target="#updateResidentModal"]')) {
            const row = e.target.closest('tr');
            const residentId = row.querySelector('td:nth-child(1)').textContent;
            const full_name = row.querySelector('td:nth-child(2)').textContent;
            const birthdate = row.querySelector('td:nth-child(3)').textContent;
            const gender = row.querySelector('td:nth-child(4)').textContent;
            const occupation = row.querySelector('td:nth-child(5)').textContent;
            const household_id = row.dataset.householdId || '';
            const relation_to_head = row.querySelector('td:nth-child(7)').textContent.trim();

            document.getElementById('resident_id_edit').value = residentId;
            document.getElementById('resident_id_display').value = residentId;
            document.getElementById('full_name_edit').value = full_name;
            document.getElementById('birthdate_edit').value = birthdate;
            document.getElementById('gender_edit').value = gender;
            document.getElementById('occupation_edit').value = occupation === 'N/A' ? '' : occupation;
            document.getElementById('household_id_edit').value = household_id;
            document.getElementById('relation_to_head_edit').value = relation_to_head;
        }

        if (e.target.closest('button[data-bs-target="#deleteResidentModal"]')) {
            const row = e.target.closest('tr');
            const residentId = row.querySelector('td:nth-child(1)').textContent;
            const full_name = row.querySelector('td:nth-child(2)').textContent;

            document.getElementById('delete_resident_id').value = residentId;
            document.getElementById('delete_resident_name').value = full_name;
            document.getElementById('confirm_delete_resident').value = '';
        }
    });

    /**
     * Reset forms on modal hide
     */
    ['createResidentModal', 'updateResidentModal', 'deleteResidentModal'].forEach(id => {
        document.getElementById(id).addEventListener('hide.bs.modal', function() {
            this.querySelector('form').reset();
        });
    });
</script>

    </body>
</html>
