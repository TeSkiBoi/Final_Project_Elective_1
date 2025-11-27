<!DOCTYPE html>
<html lang="en">
    <?php
        // Include authentication protection
        require_once __DIR__ . '/middleware/ProtectAuth.php';
        
        // Include RBAC protection (Admin only)
        require_once __DIR__ . '/middleware/RBACProtect.php';

        include 'template/header.php';
        require_once __DIR__ . '/../Model/BarangayOfficial.php';

        // Initialize model and fetch data
        $officialModel = new BarangayOfficial();
        $officials = $officialModel->getAll();
        $hierarchy = $officialModel->getByHierarchy();
        $currentUser = $_SESSION['user']['username'] ?? 'Admin';
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
                                <h1 class="mt-4">Barangay Officials</h1>
                                <ol class="breadcrumb mb-4">
                                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Barangay Officials</li>
                                </ol>
                            </div>
                        </div>

                    <!-- Statistics -->
                    <div class="row mb-4">
                        <div class="col-xl-4 col-md-6">
                            <div class="card text-white mb-4 shadow" style="background-color: #452829;">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-users fa-2x"></i>
                                        </div>
                                        <div>
                                            <div class="small">Total Officials</div>
                                            <h4 class="mb-0"><?php echo count($officials); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6">
                            <div class="card text-white mb-4 shadow" style="background-color: #6B4E3D;">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-user-tie fa-2x"></i>
                                        </div>
                                        <div>
                                            <div class="small">Barangay Kagawads</div>
                                            <h4 class="mb-0"><?php echo count($hierarchy['kagawads']); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6">
                            <div class="card text-white mb-4 shadow" style="background-color: #8B6F47;">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-briefcase fa-2x"></i>
                                        </div>
                                        <div>
                                            <div class="small">Executive Officers</div>
                                            <h4 class="mb-0"><?php echo count($hierarchy['executives']); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Organizational Chart -->
                    <div class="card shadow mb-4">
                        <div class="card-header text-white" style="background-color: #452829;">
                            <i class="fas fa-sitemap me-2"></i>
                            <strong>Organizational Hierarchy</strong>
                        </div>
                        <div class="card-body">
                            <!-- Chairman Level -->
                            <?php if (!empty($hierarchy['chairman'])): ?>
                            <div class="org-level mb-4">
                                <h5 class="text-center mb-3" style="color: #452829;">
                                    <i class="fas fa-crown me-2"></i>Barangay Leadership
                                </h5>
                                <div class="row justify-content-center">
                                    <?php foreach ($hierarchy['chairman'] as $official): ?>
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <?php echo renderOfficialCard($official, 'chairman'); ?>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Executive Officers Level -->
                            <?php if (!empty($hierarchy['executives'])): ?>
                            <div class="org-level mb-4">
                                <h5 class="text-center mb-3" style="color: #6B4E3D;">
                                    <i class="fas fa-briefcase me-2"></i>Executive Officers
                                </h5>
                                <div class="row justify-content-center">
                                    <?php foreach ($hierarchy['executives'] as $official): ?>
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <?php echo renderOfficialCard($official, 'executive'); ?>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Kagawads Level -->
                            <?php if (!empty($hierarchy['kagawads'])): ?>
                            <div class="org-level mb-4">
                                <h5 class="text-center mb-3" style="color: #8B6F47;">
                                    <i class="fas fa-users me-2"></i>Barangay Council (Sangguniang Barangay)
                                </h5>
                                <div class="row">
                                    <?php foreach ($hierarchy['kagawads'] as $official): ?>
                                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                        <?php echo renderOfficialCard($official, 'kagawad'); ?>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Youth & Security Level -->
                            <?php if (!empty($hierarchy['youth_security'])): ?>
                            <div class="org-level">
                                <h5 class="text-center mb-3" style="color: #A0826D;">
                                    <i class="fas fa-shield-alt me-2"></i>Youth & Security
                                </h5>
                                <div class="row justify-content-center">
                                    <?php foreach ($hierarchy['youth_security'] as $official): ?>
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <?php echo renderOfficialCard($official, 'youth_security'); ?>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                </main>
                <!-- END CONTENT-->
                <?php include 'template/footer.php'; ?>
            </div>
        </div>

    <!-- Edit Name Modal -->
    <div class="modal fade" id="editNameModal" tabindex="-1" aria-labelledby="editNameModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-white" style="background-color: #452829;">
                    <h5 class="modal-title text-white" id="editNameModalLabel">
                        <i class="fas fa-edit me-2"></i>Edit Official Name
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editNameForm">
                    <div class="modal-body">
                        <input type="hidden" id="edit_official_id" name="id">
                        <div class="mb-3">
                            <label for="edit_position_title" class="form-label">Position</label>
                            <input type="text" class="form-control" id="edit_position_title" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="edit_full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_full_name" name="full_name" required 
                                   placeholder="Enter full name" maxlength="255">
                            <div class="form-text">Enter the official's complete name</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn text-white" style="background-color: #452829;">
                            <i class="fas fa-save me-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Logout Modal -->
    <?php include 'template/script.php'; ?>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom Styles for Org Chart -->
    <style>
        .official-card {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: #fff;
            padding: 12px 15px;
            text-align: center;
            height: 100%;
        }

        .official-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(69, 40, 41, 0.2);
            border-color: #452829;
        }

        .official-card.chairman {
            border-color: #452829;
            border-width: 2px;
        }

        .official-card.executive {
            border-color: #6B4E3D;
        }

        .official-card.kagawad {
            border-color: #8B6F47;
        }

        .official-card.youth_security {
            border-color: #A0826D;
        }

        .position-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: #858796;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .official-name {
            font-size: 1rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
            min-height: 24px;
        }

        .org-level {
            padding: 20px 0;
            border-bottom: 2px dashed #e0e0e0;
        }

        .org-level:last-child {
            border-bottom: none;
        }

        .org-level h5 {
            font-weight: 700;
            position: relative;
            padding-bottom: 10px;
        }

        .org-level h5::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: currentColor;
            border-radius: 2px;
        }
    </style>

    <script>
        // ==========================================
        // BARANGAY OFFICIALS ORG CHART - JAVASCRIPT
        // ==========================================

        const API_URL = '/FINAL_PROJECT_ELECTIVE1/App/Controller/BarangayOfficialController.php';
        
        console.log('API_URL:', API_URL);
        console.log('Page loaded successfully');

        // Edit Name Functionality
        function editOfficialName(id, position, name) {
            console.log('Edit name clicked:', {id, position, name});
            document.getElementById('edit_official_id').value = id;
            document.getElementById('edit_position_title').value = position;
            document.getElementById('edit_full_name').value = name;
            
            const modal = new bootstrap.Modal(document.getElementById('editNameModal'));
            modal.show();
        }

        // Handle Edit Name Form Submission
        document.getElementById('editNameForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {
                id: formData.get('id'),
                full_name: formData.get('full_name')
            };

            console.log('Submitting name update:', data);
            console.log('URL:', API_URL + '?action=updateName');

            try {
                const response = await fetch(API_URL + '?action=updateName', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers.get('content-type'));
                
                const responseText = await response.text();
                console.log('Raw response:', responseText);
                
                let result;
                try {
                    result = JSON.parse(responseText);
                } catch (parseError) {
                    console.error('JSON parse error:', parseError);
                    console.error('Response was:', responseText);
                    throw new Error('Server returned invalid JSON. Check console for details.');
                }
                
                console.log('Parsed response data:', result);

                if (result.success) {
                    // Close modal first
                    const modalElement = document.getElementById('editNameModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) modalInstance.hide();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: result.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message
                    });
                }
            } catch (error) {
                console.error('Error updating name:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while updating the name: ' + error.message
                });
            }
        });


    </script>
    </body>
</html>

<?php
/**
 * Helper function to render official card
 */
function renderOfficialCard($official, $type = 'default') {
    $id = htmlspecialchars($official['id']);
    $position = htmlspecialchars($official['position_title']);
    $name = htmlspecialchars($official['full_name'] ?? 'Vacant');
    $typeClass = htmlspecialchars($type);
    
    return <<<HTML
    <div class="official-card {$typeClass}">
        <div class="position-title">{$position}</div>
        <div class="official-name">{$name}</div>
        <button class="btn btn-primary btn-sm" onclick="editOfficialName({$id}, '{$position}', '{$name}')">
            <i class="fas fa-edit me-1"></i>Edit
        </button>
    </div>
HTML;
}
?>