<!DOCTYPE html>
<html lang="en">
    <?php 
        // Include authentication protection
        require_once __DIR__ . '/middleware/ProtectAuth.php';
        
        // Include RBAC protection (Admin and Staff)
        require_once __DIR__ . '/middleware/RBACProtect.php';
        
        include 'template/header.php';
        require_once __DIR__ . '/../Config/Database.php';
        require_once __DIR__ . '/../Model/Household.php';
        
        // Initialize database and fetch from adults_view
        $db = new Database();
        $connection = $db->connect();
        
        // Fetch adults from residents table (ages 18-59)
        $query = "SELECT 
                    r.resident_id,
                    r.first_name,
                    r.middle_name,
                    r.last_name,
                    r.age,
                    r.contact_no,
                    r.email,
                    r.household_id,
                    h.family_no,
                    h.full_name as household_head
                  FROM residents r
                  LEFT JOIN households h ON r.household_id = h.household_id
                  WHERE r.age BETWEEN 18 AND 59
                  ORDER BY r.last_name ASC, r.first_name ASC";
        $result = $connection->query($query);
        $adults = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $adults[] = $row;
            }
        }
        
        // Get households for dropdown
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
                                <h1 class="mt-4">Adults</h1>
                                <ol class="breadcrumb mb-4">
                                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Adults</li>
                                </ol>
                            </div>
                            <div>
                                <a href="Resident.php" class="btn btn-primary">
                                    <i class="fas fa-users"></i> Manage Residents
                                </a>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-users me-1"></i>
                                    Adults List
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="table" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Resident ID</th>
                                            <th>First Name</th>
                                            <th>Middle Name</th>
                                            <th>Last Name</th>
                                            <th>Age</th>
                                            <th>Contact No</th>
                                            <th>Email</th>
                                            <th>Family No</th>
                                            <th>Household Head</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($adults && count($adults) > 0): ?>
                                            <?php foreach ($adults as $adult): ?>
                                                <tr data-household-id="<?php echo htmlspecialchars($adult['household_id']); ?>">
                                                    <td><?php echo htmlspecialchars($adult['resident_id']); ?></td>
                                                    <td><?php echo htmlspecialchars($adult['first_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($adult['middle_name'] ?? 'N/A'); ?></td>
                                                    <td><?php echo htmlspecialchars($adult['last_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($adult['age']); ?></td>
                                                    <td><?php echo htmlspecialchars($adult['contact_no'] ?? 'N/A'); ?></td>
                                                    <td><?php echo htmlspecialchars($adult['gmail'] ?? 'N/A'); ?></td>
                                                    <td><?php echo htmlspecialchars($adult['family_no'] ?? 'N/A'); ?></td>
                                                    <td><?php echo htmlspecialchars($adult['household_head'] ?? 'N/A'); ?></td>
                                                    <td>
                                                        <a href="Resident.php" class="btn btn-sm btn-primary" title="View in Residents">
                                                            <i class="fas fa-eye"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="10" class="text-center text-muted py-4">
                                                    <i class="fas fa-inbox me-2"></i>No adults found. Click "Add New Adult" to create one.
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

        <!-- Create Adult Modal -->
        <div class="modal fade" id="createAdultModal" tabindex="-1" aria-labelledby="createAdultModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createAdultModalLabel"><i class="fas fa-user-plus me-2"></i>Create New Adult</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="createAdultForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required placeholder="e.g., Juan Dela Cruz">
                                <small class="text-muted">Enter first, middle, and last name separated by spaces</small>
                            </div>
                            <div class="mb-3">
                                <label for="birthdate" class="form-label">Birthdate <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                            </div>
                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="occupation" class="form-label">Occupation</label>
                                <input type="text" class="form-control" id="occupation" name="occupation" placeholder="e.g., Teacher, Farmer">
                            </div>
                            <div class="mb-3">
                                <label for="household_id" class="form-label">Household <span class="text-danger">*</span></label>
                                <select class="form-select" id="household_id" name="household_id" required>
                                    <option value="">Select Household</option>
                                    <?php foreach ($households as $household): ?>
                                        <option value="<?php echo htmlspecialchars($household['household_id']); ?>">
                                            <?php echo htmlspecialchars($household['household_no']); ?> - <?php echo htmlspecialchars($household['address']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="relation_to_head" class="form-label">Relation to Head <span class="text-danger">*</span></label>
                                <select class="form-select" id="relation_to_head" name="relation_to_head" required>
                                    <option value="">Select Relation</option>
                                    <option value="Head">Head</option>
                                    <option value="Spouse">Spouse</option>
                                    <option value="Son">Son</option>
                                    <option value="Daughter">Daughter</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Create Adult</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Update Adult Modal -->
        <div class="modal fade" id="updateAdultModal" tabindex="-1" aria-labelledby="updateAdultModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateAdultModalLabel"><i class="fas fa-user-edit me-2"></i>Update Adult</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="updateAdultForm">
                        <div class="modal-body">
                            <input type="hidden" id="person_id_edit" name="id">
                            <div class="mb-3">
                                <label for="person_id_display" class="form-label">Person ID</label>
                                <input type="text" class="form-control" id="person_id_display" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="full_name_edit" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="full_name_edit" name="full_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="birthdate_edit" class="form-label">Birthdate <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="birthdate_edit" name="birthdate" required>
                            </div>
                            <div class="mb-3">
                                <label for="gender_edit" class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-select" id="gender_edit" name="gender" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="occupation_edit" class="form-label">Occupation</label>
                                <input type="text" class="form-control" id="occupation_edit" name="occupation">
                            </div>
                            <div class="mb-3">
                                <label for="household_id_edit" class="form-label">Household <span class="text-danger">*</span></label>
                                <select class="form-select" id="household_id_edit" name="household_id" required>
                                    <?php foreach ($households as $household): ?>
                                        <option value="<?php echo htmlspecialchars($household['household_id']); ?>">
                                            <?php echo htmlspecialchars($household['household_no']); ?> - <?php echo htmlspecialchars($household['address']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="relation_to_head_edit" class="form-label">Relation to Head <span class="text-danger">*</span></label>
                                <select class="form-select" id="relation_to_head_edit" name="relation_to_head" required>
                                    <option value="Head">Head</option>
                                    <option value="Spouse">Spouse</option>
                                    <option value="Son">Son</option>
                                    <option value="Daughter">Daughter</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Update Adult</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Adult Modal -->
        <div class="modal fade" id="deleteAdultModal" tabindex="-1" aria-labelledby="deleteAdultModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title text-white" id="deleteAdultModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>Delete Adult</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="deleteAdultForm">
                        <div class="modal-body">
                            <input type="hidden" id="delete_person_id" name="id">
                            <div class="alert alert-warning" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Warning!</strong> This action cannot be undone. Are you sure you want to delete this adult record?
                            </div>
                            <div class="mb-3">
                                <label for="delete_adult_name" class="form-label">Adult to Delete:</label>
                                <input type="text" class="form-control" id="delete_adult_name" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_delete_adult" class="form-label">Type the adult's full name to confirm: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="confirm_delete_adult" name="confirm_delete" placeholder="Type full name here" required>
                                <small class="text-muted">This is to prevent accidental deletion.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt me-2"></i>Delete Adult</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Logout Modal -->
        <?php include 'template/script.php'; ?>

        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // API Base URL
            const API_URL = '../../App/Controller/ResidentController.php';

            /**
             * Create Adult Form Submission
             */
            document.getElementById('createAdultForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const full_name = document.getElementById('full_name').value.trim();
                const birthdate = document.getElementById('birthdate').value;
                const gender = document.getElementById('gender').value;
                const occupation = document.getElementById('occupation').value.trim();
                const household_id = document.getElementById('household_id').value;
                const relation_to_head = document.getElementById('relation_to_head').value;

                // Validation
                if (!full_name || !birthdate || !gender || !household_id || !relation_to_head) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validation Error',
                        text: 'Please fill all required fields.',
                        confirmButtonColor: '#6ec207'
                    });
                    return;
                }

                // Age validation (must be 18-59 for adults)
                const age = Math.floor((new Date() - new Date(birthdate)) / (365.25 * 24 * 60 * 60 * 1000));
                if (age < 18 || age > 59) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validation Error',
                        text: 'Adults must be between 18 and 59 years old.',
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
                                document.getElementById('createAdultForm').reset();

                                // Close modal
                                const modal = bootstrap.Modal.getInstance(document.getElementById('createAdultModal'));
                                modal.hide();

                                // Reload page to show new adult
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
             * Update Adult Form Submission
             */
            document.getElementById('updateAdultForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const personId = document.getElementById('person_id_edit').value;
                const full_name = document.getElementById('full_name_edit').value.trim();
                const birthdate = document.getElementById('birthdate_edit').value;
                const gender = document.getElementById('gender_edit').value;
                const occupation = document.getElementById('occupation_edit').value.trim();
                const household_id = document.getElementById('household_id_edit').value;
                const relation_to_head = document.getElementById('relation_to_head_edit').value;

                if (!personId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error',
                        text: 'Person ID is missing.',
                        confirmButtonColor: '#6ec207'
                    });
                    return;
                }

                if (!full_name || !birthdate || !gender || !household_id || !relation_to_head) {
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
                            id: parseInt(personId),
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
                                const modal = bootstrap.Modal.getInstance(document.getElementById('updateAdultModal'));
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
             * Delete Adult Form Submission
             */
            document.getElementById('deleteAdultForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const personId = document.getElementById('delete_person_id').value;
                const adultName = document.getElementById('delete_adult_name').value;
                const confirmDelete = document.getElementById('confirm_delete_adult').value.trim();

                if (confirmDelete !== adultName) {
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
                            id: parseInt(personId)
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
                                const modal = bootstrap.Modal.getInstance(document.getElementById('deleteAdultModal'));
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
                if (e.target.closest('button[data-bs-target="#updateAdultModal"]')) {
                    const row = e.target.closest('tr');
                    const personId = row.querySelector('td:nth-child(1)').textContent;
                    const firstName = row.querySelector('td:nth-child(2)').textContent;
                    const middleName = row.querySelector('td:nth-child(3)').textContent;
                    const lastName = row.querySelector('td:nth-child(4)').textContent;
                    const birthdate = row.querySelector('td:nth-child(5)').textContent;
                    const gender = row.querySelector('td:nth-child(6)').textContent;
                    const household_id = row.dataset.householdId || '';
                    const relation_to_head = row.querySelector('td:nth-child(9) .badge').textContent.trim();
                    
                    // Construct full name
                    const fullName = middleName && middleName !== '' ? 
                        `${firstName} ${middleName} ${lastName}` : 
                        `${firstName} ${lastName}`;

                    document.getElementById('person_id_edit').value = personId;
                    document.getElementById('person_id_display').value = personId;
                    document.getElementById('full_name_edit').value = fullName;
                    document.getElementById('birthdate_edit').value = birthdate;
                    document.getElementById('gender_edit').value = gender;
                    document.getElementById('household_id_edit').value = household_id;
                    document.getElementById('relation_to_head_edit').value = relation_to_head;
                }

                if (e.target.closest('button[data-bs-target="#deleteAdultModal"]')) {
                    const row = e.target.closest('tr');
                    const personId = row.querySelector('td:nth-child(1)').textContent;
                    const firstName = row.querySelector('td:nth-child(2)').textContent;
                    const middleName = row.querySelector('td:nth-child(3)').textContent;
                    const lastName = row.querySelector('td:nth-child(4)').textContent;
                    
                    const fullName = middleName && middleName !== '' ? 
                        `${firstName} ${middleName} ${lastName}` : 
                        `${firstName} ${lastName}`;

                    document.getElementById('delete_person_id').value = personId;
                    document.getElementById('delete_adult_name').value = fullName;
                    document.getElementById('confirm_delete_adult').value = '';
                }
            });

            /**
             * Clear forms when modals are hidden
             */
            document.getElementById('createAdultModal').addEventListener('hide.bs.modal', function() {
                document.getElementById('createAdultForm').reset();
            });

            document.getElementById('updateAdultModal').addEventListener('hide.bs.modal', function() {
                document.getElementById('updateAdultForm').reset();
            });

            document.getElementById('deleteAdultModal').addEventListener('hide.bs.modal', function() {
                document.getElementById('deleteAdultForm').reset();
            });
        </script>
    </body>
</html>
