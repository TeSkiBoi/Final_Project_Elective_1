<!DOCTYPE html>
<html lang="en">
    <?php 
        // Include authentication protection
        require_once __DIR__ . '/middleware/ProtectAuth.php';
        
        // Include RBAC protection (Admin and Staff)
        require_once __DIR__ . '/middleware/RBACProtect.php';
        
        include 'template/header.php';
        require_once __DIR__ . '/../Model/Blotter.php';
        
        // Initialize Blotter model
        $blotterModel = new Blotter();
        $incidents = $blotterModel->getAll();
        $statistics = $blotterModel->getStatistics();
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
                        <h1 class="mt-4">Blotter and Incident Recording</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="dashboard.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Blotter and Incident Recording</li>
                        </ol>

                        <!-- Introduction Section -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-info-circle me-2"></i>About Blotter and Incident Recording</h5>
                                <p class="card-text">
                                    The Blotter and Incident Recording module provides a comprehensive system for documenting and managing all barangay-related incidents, 
                                    complaints, and resolutions. This system ensures accurate record-keeping of community issues, facilitates proper case management, and 
                                    maintains a historical database of all reported incidents for monitoring and reference purposes. All relevant information related to 
                                    each case must be recorded to ensure complete documentation and proper resolution tracking.
                                </p>
                                <h6 class="mt-3 mb-2"><strong>Key Features:</strong></h6>
                                <ul class="mb-0">
                                    <li><strong>Comprehensive Case Recording:</strong> Document all essential details including complainant information, respondent information, incident description, location, date, and time of occurrence.</li>
                                    <li><strong>Case Number Generation:</strong> Automatically generate unique case numbers for easy tracking and reference of all recorded incidents.</li>
                                    <li><strong>Multiple Incident Types:</strong> Categorize incidents as Complaints, Disputes, Noise Complaints, Domestic Issues, Theft, Assault, Vandalism, Public Disturbances, and more.</li>
                                    <li><strong>Status Tracking:</strong> Monitor incident progression through various stages including Pending, Under Investigation, For Mediation, Resolved, Closed, and Escalated statuses.</li>
                                    <li><strong>Priority Management:</strong> Assign priority levels (High, Medium, Low) to cases for proper resource allocation and urgent response.</li>
                                    <li><strong>Complete Party Information:</strong> Record detailed information about both complainants and respondents including names, addresses, and contact details.</li>
                                    <li><strong>Witness Documentation:</strong> Maintain records of witnesses and supporting evidence for each incident.</li>
                                    <li><strong>Case Assignment:</strong> Track which barangay officials or personnel are assigned to handle specific incidents.</li>
                                    <li><strong>Resolution Monitoring:</strong> Document case resolutions, mediations, settlements, and follow-up actions for complete accountability.</li>
                                    <li><strong>Historical Records:</strong> Maintain a searchable database of all incidents for statistical analysis and reference purposes.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Incident Statistics Cards -->
                        <div class="row mb-4">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="small">Total Incidents</div>
                                                <div class="h4 mb-0"><?php echo number_format($statistics['total_incidents'] ?? 0); ?></div>
                                            </div>
                                            <div>
                                                <i class="fas fa-clipboard-list fa-3x opacity-50"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="small">Pending Cases</div>
                                                <div class="h4 mb-0"><?php echo number_format($statistics['pending_incidents'] ?? 0); ?></div>
                                            </div>
                                            <div>
                                                <i class="fas fa-hourglass-half fa-3x opacity-50"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="small">Resolved Cases</div>
                                                <div class="h4 mb-0"><?php echo number_format($statistics['resolved_incidents'] ?? 0); ?></div>
                                            </div>
                                            <div>
                                                <i class="fas fa-check-circle fa-3x opacity-50"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="small">High Priority</div>
                                                <div class="h4 mb-0"><?php echo number_format($statistics['high_priority_total'] ?? 0); ?></div>
                                            </div>
                                            <div>
                                                <i class="fas fa-exclamation-triangle fa-3x opacity-50"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Incidents Table -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-table me-1"></i>
                                    Blotter Records
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createIncidentModal">
                                        <i class="fas fa-plus"></i> Record Incident
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="incidentsTable" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Case No.</th>
                                            <th>Incident Type</th>
                                            <th>Date</th>
                                            <th>Complainant</th>
                                            <th>Respondent</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                            <th>Priority</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($incidents && count($incidents) > 0): ?>
                                            <?php foreach ($incidents as $incident): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($incident['case_number']); ?></td>
                                                    <td><?php echo htmlspecialchars($incident['incident_type']); ?></td>
                                                    <td><?php echo date('M d, Y', strtotime($incident['incident_date'])); ?></td>
                                                    <td><?php echo htmlspecialchars($incident['complainant_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($incident['respondent_name']); ?></td>
                                                    <td><?php echo htmlspecialchars(substr($incident['incident_location'], 0, 30)) . (strlen($incident['incident_location']) > 30 ? '...' : ''); ?></td>
                                                    <td>
                                                        <?php
                                                            $statusClass = '';
                                                            switch($incident['incident_status']) {
                                                                case 'Pending':
                                                                    $statusClass = 'bg-secondary';
                                                                    break;
                                                                case 'Under Investigation':
                                                                    $statusClass = 'bg-info';
                                                                    break;
                                                                case 'For Mediation':
                                                                    $statusClass = 'bg-warning';
                                                                    break;
                                                                case 'Resolved':
                                                                    $statusClass = 'bg-success';
                                                                    break;
                                                                case 'Closed':
                                                                    $statusClass = 'bg-dark';
                                                                    break;
                                                                case 'Escalated':
                                                                    $statusClass = 'bg-danger';
                                                                    break;
                                                                default:
                                                                    $statusClass = 'bg-secondary';
                                                            }
                                                        ?>
                                                        <span class="badge <?php echo $statusClass; ?>">
                                                            <?php echo htmlspecialchars($incident['incident_status']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $priorityClass = '';
                                                            switch($incident['priority_level']) {
                                                                case 'High':
                                                                    $priorityClass = 'bg-danger';
                                                                    break;
                                                                case 'Medium':
                                                                    $priorityClass = 'bg-warning';
                                                                    break;
                                                                case 'Low':
                                                                    $priorityClass = 'bg-secondary';
                                                                    break;
                                                                default:
                                                                    $priorityClass = 'bg-secondary';
                                                            }
                                                        ?>
                                                        <span class="badge <?php echo $priorityClass; ?>">
                                                            <?php echo htmlspecialchars($incident['priority_level'] ?? 'N/A'); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info me-1 view-incident-btn" 
                                                                data-id="<?php echo $incident['incident_id']; ?>"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#viewIncidentModal">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-warning me-1 edit-incident-btn" 
                                                                data-id="<?php echo $incident['incident_id']; ?>"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#updateIncidentModal">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger delete-incident-btn" 
                                                                data-id="<?php echo $incident['incident_id']; ?>"
                                                                data-case="<?php echo htmlspecialchars($incident['case_number']); ?>"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#deleteIncidentModal">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="9" class="text-center text-muted py-4">
                                                    <i class="fas fa-inbox me-2"></i>No incidents recorded. Click "Record Incident" to add one.
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

        <!-- Create Incident Modal -->
        <div class="modal fade" id="createIncidentModal" tabindex="-1" aria-labelledby="createIncidentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createIncidentModalLabel"><i class="fas fa-plus-circle me-2"></i>Record New Incident</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="createIncidentForm">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="create_case_number" class="form-label">Case Number <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="create_case_number" name="case_number" required readonly>
                                        <button type="button" class="btn btn-outline-secondary" id="generateCaseNumberBtn">
                                            <i class="fas fa-sync-alt"></i> Generate
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="create_incident_type" class="form-label">Incident Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="create_incident_type" name="incident_type" required>
                                        <option value="Complaint">Complaint</option>
                                        <option value="Dispute">Dispute</option>
                                        <option value="Noise Complaint">Noise Complaint</option>
                                        <option value="Domestic Issue">Domestic Issue</option>
                                        <option value="Theft">Theft</option>
                                        <option value="Assault">Assault</option>
                                        <option value="Vandalism">Vandalism</option>
                                        <option value="Public Disturbance">Public Disturbance</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="create_priority_level" class="form-label">Priority Level</label>
                                    <select class="form-select" id="create_priority_level" name="priority_level">
                                        <option value="High">High</option>
                                        <option value="Medium" selected>Medium</option>
                                        <option value="Low">Low</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="create_incident_date" class="form-label">Incident Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="create_incident_date" name="incident_date" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="create_incident_time" class="form-label">Incident Time</label>
                                    <input type="time" class="form-control" id="create_incident_time" name="incident_time">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="create_incident_location" class="form-label">Incident Location <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="create_incident_location" name="incident_location" required>
                                </div>
                            </div>
                            
                            <hr>
                            <h6 class="mb-3"><i class="fas fa-user me-2"></i>Complainant Information</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="create_complainant_name" class="form-label">Complainant Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="create_complainant_name" name="complainant_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="create_complainant_contact" class="form-label">Contact Number</label>
                                    <input type="text" class="form-control" id="create_complainant_contact" name="complainant_contact">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="create_complainant_address" class="form-label">Complainant Address</label>
                                    <textarea class="form-control" id="create_complainant_address" name="complainant_address" rows="2"></textarea>
                                </div>
                            </div>

                            <hr>
                            <h6 class="mb-3"><i class="fas fa-user-times me-2"></i>Respondent Information</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="create_respondent_name" class="form-label">Respondent Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="create_respondent_name" name="respondent_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="create_respondent_contact" class="form-label">Contact Number</label>
                                    <input type="text" class="form-control" id="create_respondent_contact" name="respondent_contact">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="create_respondent_address" class="form-label">Respondent Address</label>
                                    <textarea class="form-control" id="create_respondent_address" name="respondent_address" rows="2"></textarea>
                                </div>
                            </div>

                            <hr>
                            <h6 class="mb-3"><i class="fas fa-file-alt me-2"></i>Incident Details</h6>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="create_incident_description" class="form-label">Incident Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="create_incident_description" name="incident_description" rows="4" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="create_witnesses" class="form-label">Witnesses</label>
                                    <textarea class="form-control" id="create_witnesses" name="witnesses" rows="2" placeholder="List names of witnesses (if any)"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="create_incident_status" class="form-label">Status</label>
                                    <select class="form-select" id="create_incident_status" name="incident_status">
                                        <option value="Pending" selected>Pending</option>
                                        <option value="Under Investigation">Under Investigation</option>
                                        <option value="For Mediation">For Mediation</option>
                                        <option value="Resolved">Resolved</option>
                                        <option value="Closed">Closed</option>
                                        <option value="Escalated">Escalated</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="create_assigned_to" class="form-label">Assigned To</label>
                                    <input type="text" class="form-control" id="create_assigned_to" name="assigned_to" placeholder="Barangay official/staff assigned">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="create_filed_by" class="form-label">Filed By</label>
                                    <input type="text" class="form-control" id="create_filed_by" name="filed_by" placeholder="Person who recorded this incident">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="create_remarks" class="form-label">Remarks</label>
                                    <textarea class="form-control" id="create_remarks" name="remarks" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Record Incident</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Incident Modal -->
        <div class="modal fade" id="viewIncidentModal" tabindex="-1" aria-labelledby="viewIncidentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewIncidentModalLabel"><i class="fas fa-eye me-2"></i>Incident Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <strong>Case Number:</strong>
                                <p id="view_case_number"></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Incident Type:</strong>
                                <p id="view_incident_type"></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Priority Level:</strong>
                                <p id="view_priority_level"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Incident Date:</strong>
                                <p id="view_incident_date"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Incident Time:</strong>
                                <p id="view_incident_time"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <strong>Location:</strong>
                                <p id="view_incident_location"></p>
                            </div>
                        </div>
                        
                        <hr>
                        <h6><i class="fas fa-user me-2"></i>Complainant Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Name:</strong>
                                <p id="view_complainant_name"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Contact:</strong>
                                <p id="view_complainant_contact"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <strong>Address:</strong>
                                <p id="view_complainant_address"></p>
                            </div>
                        </div>

                        <hr>
                        <h6><i class="fas fa-user-times me-2"></i>Respondent Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Name:</strong>
                                <p id="view_respondent_name"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Contact:</strong>
                                <p id="view_respondent_contact"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <strong>Address:</strong>
                                <p id="view_respondent_address"></p>
                            </div>
                        </div>

                        <hr>
                        <h6><i class="fas fa-file-alt me-2"></i>Incident Details</h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <strong>Description:</strong>
                                <p id="view_incident_description"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <strong>Witnesses:</strong>
                                <p id="view_witnesses"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <strong>Status:</strong>
                                <p id="view_incident_status"></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Assigned To:</strong>
                                <p id="view_assigned_to"></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Filed By:</strong>
                                <p id="view_filed_by"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <strong>Remarks:</strong>
                                <p id="view_remarks"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Incident Modal -->
        <div class="modal fade" id="updateIncidentModal" tabindex="-1" aria-labelledby="updateIncidentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateIncidentModalLabel"><i class="fas fa-edit me-2"></i>Update Incident</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="updateIncidentForm">
                        <input type="hidden" id="update_incident_id" name="incident_id">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="update_case_number" class="form-label">Case Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="update_case_number" name="case_number" required readonly>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="update_incident_type" class="form-label">Incident Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="update_incident_type" name="incident_type" required>
                                        <option value="Complaint">Complaint</option>
                                        <option value="Dispute">Dispute</option>
                                        <option value="Noise Complaint">Noise Complaint</option>
                                        <option value="Domestic Issue">Domestic Issue</option>
                                        <option value="Theft">Theft</option>
                                        <option value="Assault">Assault</option>
                                        <option value="Vandalism">Vandalism</option>
                                        <option value="Public Disturbance">Public Disturbance</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="update_priority_level" class="form-label">Priority Level</label>
                                    <select class="form-select" id="update_priority_level" name="priority_level">
                                        <option value="High">High</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Low">Low</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="update_incident_date" class="form-label">Incident Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="update_incident_date" name="incident_date" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="update_incident_time" class="form-label">Incident Time</label>
                                    <input type="time" class="form-control" id="update_incident_time" name="incident_time">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="update_incident_location" class="form-label">Incident Location <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="update_incident_location" name="incident_location" required>
                                </div>
                            </div>
                            
                            <hr>
                            <h6 class="mb-3"><i class="fas fa-user me-2"></i>Complainant Information</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="update_complainant_name" class="form-label">Complainant Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="update_complainant_name" name="complainant_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="update_complainant_contact" class="form-label">Contact Number</label>
                                    <input type="text" class="form-control" id="update_complainant_contact" name="complainant_contact">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="update_complainant_address" class="form-label">Complainant Address</label>
                                    <textarea class="form-control" id="update_complainant_address" name="complainant_address" rows="2"></textarea>
                                </div>
                            </div>

                            <hr>
                            <h6 class="mb-3"><i class="fas fa-user-times me-2"></i>Respondent Information</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="update_respondent_name" class="form-label">Respondent Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="update_respondent_name" name="respondent_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="update_respondent_contact" class="form-label">Contact Number</label>
                                    <input type="text" class="form-control" id="update_respondent_contact" name="respondent_contact">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="update_respondent_address" class="form-label">Respondent Address</label>
                                    <textarea class="form-control" id="update_respondent_address" name="respondent_address" rows="2"></textarea>
                                </div>
                            </div>

                            <hr>
                            <h6 class="mb-3"><i class="fas fa-file-alt me-2"></i>Incident Details</h6>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="update_incident_description" class="form-label">Incident Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="update_incident_description" name="incident_description" rows="4" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="update_witnesses" class="form-label">Witnesses</label>
                                    <textarea class="form-control" id="update_witnesses" name="witnesses" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="update_incident_status" class="form-label">Status</label>
                                    <select class="form-select" id="update_incident_status" name="incident_status">
                                        <option value="Pending">Pending</option>
                                        <option value="Under Investigation">Under Investigation</option>
                                        <option value="For Mediation">For Mediation</option>
                                        <option value="Resolved">Resolved</option>
                                        <option value="Closed">Closed</option>
                                        <option value="Escalated">Escalated</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="update_assigned_to" class="form-label">Assigned To</label>
                                    <input type="text" class="form-control" id="update_assigned_to" name="assigned_to">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="update_filed_by" class="form-label">Filed By</label>
                                    <input type="text" class="form-control" id="update_filed_by" name="filed_by">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="update_remarks" class="form-label">Remarks</label>
                                    <textarea class="form-control" id="update_remarks" name="remarks" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Update Incident</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Incident Modal -->
        <div class="modal fade" id="deleteIncidentModal" tabindex="-1" aria-labelledby="deleteIncidentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteIncidentModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="deleteIncidentForm">
                        <input type="hidden" id="delete_incident_id" name="incident_id">
                        <div class="modal-body">
                            <p>Are you sure you want to delete this incident record?</p>
                            <div class="alert alert-warning">
                                <strong>Case Number:</strong> <span id="delete_case_number"></span>
                            </div>
                            <p class="text-danger"><i class="fas fa-exclamation-circle"></i> This action cannot be undone!</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt me-2"></i>Delete Record</button>
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
            // Initialize DataTable
            document.addEventListener('DOMContentLoaded', function() {
                const table = document.getElementById('incidentsTable');
                if (table && typeof simpleDatatables !== 'undefined') {
                    new simpleDatatables.DataTable(table, {
                        searchable: true,
                        fixedHeight: false,
                        perPage: 10
                    });
                }

                // Set today's date as default
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('create_incident_date').value = today;

                // Generate case number on modal open
                document.getElementById('createIncidentModal').addEventListener('shown.bs.modal', function() {
                    generateCaseNumber();
                });

                // Generate case number button
                document.getElementById('generateCaseNumberBtn').addEventListener('click', function() {
                    generateCaseNumber();
                });

                // Use event delegation for button clicks
                document.body.addEventListener('click', function(e) {
                    // View Incident Handler
                    if (e.target.closest('.view-incident-btn')) {
                        const button = e.target.closest('.view-incident-btn');
                        const incidentId = button.getAttribute('data-id');
                        
                        fetch(`../Controller/BlotterController.php?action=getById&id=${incidentId}`)
                            .then(response => response.json())
                            .then(result => {
                                if (result.success) {
                                    const incident = result.data;
                                    
                                    document.getElementById('view_case_number').textContent = incident.case_number || 'N/A';
                                    document.getElementById('view_incident_type').textContent = incident.incident_type || 'N/A';
                                    document.getElementById('view_priority_level').textContent = incident.priority_level || 'N/A';
                                    document.getElementById('view_incident_date').textContent = incident.incident_date ? new Date(incident.incident_date).toLocaleDateString() : 'N/A';
                                    document.getElementById('view_incident_time').textContent = incident.incident_time || 'N/A';
                                    document.getElementById('view_incident_location').textContent = incident.incident_location || 'N/A';
                                    document.getElementById('view_complainant_name').textContent = incident.complainant_name || 'N/A';
                                    document.getElementById('view_complainant_contact').textContent = incident.complainant_contact || 'N/A';
                                    document.getElementById('view_complainant_address').textContent = incident.complainant_address || 'N/A';
                                    document.getElementById('view_respondent_name').textContent = incident.respondent_name || 'N/A';
                                    document.getElementById('view_respondent_contact').textContent = incident.respondent_contact || 'N/A';
                                    document.getElementById('view_respondent_address').textContent = incident.respondent_address || 'N/A';
                                    document.getElementById('view_incident_description').textContent = incident.incident_description || 'N/A';
                                    document.getElementById('view_witnesses').textContent = incident.witnesses || 'N/A';
                                    document.getElementById('view_incident_status').textContent = incident.incident_status || 'N/A';
                                    document.getElementById('view_assigned_to').textContent = incident.assigned_to || 'N/A';
                                    document.getElementById('view_filed_by').textContent = incident.filed_by || 'N/A';
                                    document.getElementById('view_remarks').textContent = incident.remarks || 'N/A';
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: result.message || 'Failed to load incident details'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to load incident details'
                                });
                            });
                    }

                    // Edit Incident Handler
                    if (e.target.closest('.edit-incident-btn')) {
                        const button = e.target.closest('.edit-incident-btn');
                        const incidentId = button.getAttribute('data-id');
                        
                        fetch(`../Controller/BlotterController.php?action=getById&id=${incidentId}`)
                            .then(response => response.json())
                            .then(result => {
                                if (result.success) {
                                    const incident = result.data;
                                    
                                    document.getElementById('update_incident_id').value = incident.incident_id;
                                    document.getElementById('update_case_number').value = incident.case_number || '';
                                    document.getElementById('update_incident_type').value = incident.incident_type || 'Complaint';
                                    document.getElementById('update_priority_level').value = incident.priority_level || 'Medium';
                                    document.getElementById('update_incident_date').value = incident.incident_date || '';
                                    document.getElementById('update_incident_time').value = incident.incident_time || '';
                                    document.getElementById('update_incident_location').value = incident.incident_location || '';
                                    document.getElementById('update_complainant_name').value = incident.complainant_name || '';
                                    document.getElementById('update_complainant_contact').value = incident.complainant_contact || '';
                                    document.getElementById('update_complainant_address').value = incident.complainant_address || '';
                                    document.getElementById('update_respondent_name').value = incident.respondent_name || '';
                                    document.getElementById('update_respondent_contact').value = incident.respondent_contact || '';
                                    document.getElementById('update_respondent_address').value = incident.respondent_address || '';
                                    document.getElementById('update_incident_description').value = incident.incident_description || '';
                                    document.getElementById('update_witnesses').value = incident.witnesses || '';
                                    document.getElementById('update_incident_status').value = incident.incident_status || 'Pending';
                                    document.getElementById('update_assigned_to').value = incident.assigned_to || '';
                                    document.getElementById('update_filed_by').value = incident.filed_by || '';
                                    document.getElementById('update_remarks').value = incident.remarks || '';
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: result.message || 'Failed to load incident details'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to load incident details'
                                });
                            });
                    }

                    // Delete Incident Handler
                    if (e.target.closest('.delete-incident-btn')) {
                        const button = e.target.closest('.delete-incident-btn');
                        const incidentId = button.getAttribute('data-id');
                        const caseNumber = button.getAttribute('data-case');
                        
                        document.getElementById('delete_incident_id').value = incidentId;
                        document.getElementById('delete_case_number').textContent = caseNumber;
                    }
                });

                // Generate case number function
                function generateCaseNumber() {
                    fetch('../Controller/BlotterController.php?action=generateCaseNumber')
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                document.getElementById('create_case_number').value = result.case_number;
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }

                // Create Incident Form Submit
                document.getElementById('createIncidentForm').addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    
                    try {
                        const response = await fetch('../Controller/BlotterController.php?action=create', {
                            method: 'POST',
                            body: formData
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: result.message
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
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to record incident'
                        });
                    }
                });

                // Update Incident Form Submit
                document.getElementById('updateIncidentForm').addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    
                    try {
                        const response = await fetch('../Controller/BlotterController.php?action=update', {
                            method: 'POST',
                            body: formData
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: result.message
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
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update incident'
                        });
                    }
                });

                // Delete Incident Form Submit
                document.getElementById('deleteIncidentForm').addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    
                    try {
                        const response = await fetch('../Controller/BlotterController.php?action=delete', {
                            method: 'POST',
                            body: formData
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: result.message
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
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete incident'
                        });
                    }
                });
            });
        </script>
    </body>
</html>
