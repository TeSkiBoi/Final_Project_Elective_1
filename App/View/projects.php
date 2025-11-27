<!DOCTYPE html>
<html lang="en">
    <?php 
        // Include authentication protection
        require_once __DIR__ . '/middleware/ProtectAuth.php';
        
        // Include RBAC protection (Admin and Staff)
        require_once __DIR__ . '/middleware/RBACProtect.php';
        
        include 'template/header.php';
        require_once __DIR__ . '/../Model/Project.php';
        
        // Initialize Project model
        $projectModel = new Project();
        $projects = $projectModel->getAll();
        $statistics = $projectModel->getStatistics();
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
                        <h1 class="mt-4">Barangay Projects</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="dashboard.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Barangay Projects</li>
                        </ol>

                        <!-- Introduction Section -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-info-circle me-2"></i>About Barangay Projects</h5>
                                <p class="card-text">
                                    The Barangay Projects module provides comprehensive project management capabilities to track, monitor, and manage 
                                    all barangay development initiatives. This system enables efficient oversight of project implementation from planning 
                                    to completion, including detailed budget tracking and accountability for project proponents. Stay informed about the 
                                    different projects implemented in the barangay, their budget allocation, and the responsible parties ensuring successful execution.
                                </p>
                                <h6 class="mt-3 mb-2"><strong>Key Features:</strong></h6>
                                <ul class="mb-0">
                                    <li><strong>Project Portfolio Management:</strong> Track all barangay projects with complete details including descriptions, timelines, and implementation status.</li>
                                    <li><strong>Budget Allocation Tracking:</strong> Monitor total budget, utilized amounts, and remaining funds for each project to ensure financial accountability.</li>
                                    <li><strong>Proponent Accountability:</strong> Record and display responsible parties or organizations for each project, ensuring clear ownership and accountability.</li>
                                    <li><strong>Status Monitoring:</strong> Track projects through various stages including Planning, Ongoing, Completed, On Hold, and Cancelled.</li>
                                    <li><strong>Progress Tracking:</strong> Percentage-based progress indicators for ongoing projects to visualize implementation status at a glance.</li>
                                    <li><strong>Category Organization:</strong> Classify projects by category (Infrastructure, Health, Livelihood, Youth Development, Social Services, Environment) for better management.</li>
                                    <li><strong>Priority Management:</strong> Set and track priority levels (High, Medium, Low) to focus resources on critical initiatives.</li>
                                    <li><strong>Comprehensive Reporting:</strong> Access detailed project information including beneficiaries, locations, funding sources, and completion dates.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Project Statistics Cards -->
                        <div class="row mb-4">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="small">Total Projects</div>
                                                <div class="h4 mb-0"><?php echo number_format($statistics['total_projects'] ?? 0); ?></div>
                                            </div>
                                            <div>
                                                <i class="fas fa-project-diagram fa-3x opacity-50"></i>
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
                                                <div class="small">Ongoing Projects</div>
                                                <div class="h4 mb-0"><?php echo number_format($statistics['ongoing_projects'] ?? 0); ?></div>
                                            </div>
                                            <div>
                                                <i class="fas fa-spinner fa-3x opacity-50"></i>
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
                                                <div class="small">Completed Projects</div>
                                                <div class="h4 mb-0"><?php echo number_format($statistics['completed_projects'] ?? 0); ?></div>
                                            </div>
                                            <div>
                                                <i class="fas fa-check-circle fa-3x opacity-50"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-info text-white mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="small">Total Budget</div>
                                                <div class="h4 mb-0">₱<?php echo number_format($statistics['total_budget'] ?? 0, 2); ?></div>
                                            </div>
                                            <div>
                                                <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Projects Table -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-table me-1"></i>
                                    Barangay Projects List
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                                        <i class="fas fa-plus"></i> Add Project
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="projectsTable" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Project Name</th>
                                            <th>Status</th>
                                            <th>Start Date</th>
                                            <th>Proponent</th>
                                            <th>Category</th>
                                            <th>Total Budget</th>
                                            <th>Budget Utilized</th>
                                            <th>Progress</th>
                                            <th>Priority</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($projects && count($projects) > 0): ?>
                                            <?php foreach ($projects as $project): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($project['project_id']); ?></td>
                                                    <td><?php echo htmlspecialchars($project['project_name']); ?></td>
                                                    <td>
                                                        <?php
                                                            $statusClass = '';
                                                            switch($project['project_status']) {
                                                                case 'Planning':
                                                                    $statusClass = 'bg-secondary';
                                                                    break;
                                                                case 'Ongoing':
                                                                    $statusClass = 'bg-warning';
                                                                    break;
                                                                case 'Completed':
                                                                    $statusClass = 'bg-success';
                                                                    break;
                                                                case 'On Hold':
                                                                    $statusClass = 'bg-info';
                                                                    break;
                                                                case 'Cancelled':
                                                                    $statusClass = 'bg-danger';
                                                                    break;
                                                                default:
                                                                    $statusClass = 'bg-secondary';
                                                            }
                                                        ?>
                                                        <span class="badge <?php echo $statusClass; ?>">
                                                            <?php echo htmlspecialchars($project['project_status']); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo $project['start_date'] ? date('M d, Y', strtotime($project['start_date'])) : 'N/A'; ?></td>
                                                    <td><?php echo htmlspecialchars($project['proponent']); ?></td>
                                                    <td><?php echo htmlspecialchars($project['project_category'] ?? 'N/A'); ?></td>
                                                    <td class="text-end">₱<?php echo number_format($project['total_budget'], 2); ?></td>
                                                    <td class="text-end">₱<?php echo number_format($project['budget_utilized'], 2); ?></td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                                                <div class="progress-bar" role="progressbar" 
                                                                     style="width: <?php echo $project['progress_percentage']; ?>%;" 
                                                                     aria-valuenow="<?php echo $project['progress_percentage']; ?>" 
                                                                     aria-valuemin="0" aria-valuemax="100">
                                                                    <?php echo $project['progress_percentage']; ?>%
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $priorityClass = '';
                                                            switch($project['priority_level']) {
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
                                                            <?php echo htmlspecialchars($project['priority_level'] ?? 'N/A'); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info me-1 view-project-btn" 
                                                                data-id="<?php echo $project['project_id']; ?>"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#viewProjectModal">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-warning me-1 edit-project-btn" 
                                                                data-id="<?php echo $project['project_id']; ?>"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#updateProjectModal">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger delete-project-btn" 
                                                                data-id="<?php echo $project['project_id']; ?>"
                                                                data-name="<?php echo htmlspecialchars($project['project_name']); ?>"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#deleteProjectModal">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="11" class="text-center text-muted py-4">
                                                    <i class="fas fa-inbox me-2"></i>No projects found. Click "Add Project" to create one.
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

        <!-- Create Project Modal -->
        <div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createProjectModalLabel"><i class="fas fa-plus-circle me-2"></i>Add New Project</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="createProjectForm">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="create_project_name" class="form-label">Project Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="create_project_name" name="project_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="create_proponent" class="form-label">Proponent <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="create_proponent" name="proponent" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="create_project_description" class="form-label">Project Description</label>
                                    <textarea class="form-control" id="create_project_description" name="project_description" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="create_project_status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="create_project_status" name="project_status" required>
                                        <option value="Planning">Planning</option>
                                        <option value="Ongoing">Ongoing</option>
                                        <option value="Completed">Completed</option>
                                        <option value="On Hold">On Hold</option>
                                        <option value="Cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="create_project_category" class="form-label">Category</label>
                                    <input type="text" class="form-control" id="create_project_category" name="project_category" 
                                           placeholder="e.g., Infrastructure, Health, Livelihood">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="create_priority_level" class="form-label">Priority Level</label>
                                    <select class="form-select" id="create_priority_level" name="priority_level">
                                        <option value="High">High</option>
                                        <option value="Medium" selected>Medium</option>
                                        <option value="Low">Low</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="create_start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="create_start_date" name="start_date">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="create_end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="create_end_date" name="end_date">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="create_completion_date" class="form-label">Completion Date</label>
                                    <input type="date" class="form-control" id="create_completion_date" name="completion_date">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="create_total_budget" class="form-label">Total Budget <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="create_total_budget" name="total_budget" step="0.01" min="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="create_budget_utilized" class="form-label">Budget Utilized</label>
                                    <input type="number" class="form-control" id="create_budget_utilized" name="budget_utilized" step="0.01" min="0" value="0">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="create_progress_percentage" class="form-label">Progress (%)</label>
                                    <input type="number" class="form-control" id="create_progress_percentage" name="progress_percentage" min="0" max="100" value="0">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="create_funding_source" class="form-label">Funding Source</label>
                                    <input type="text" class="form-control" id="create_funding_source" name="funding_source" 
                                           placeholder="e.g., Local Government, National Government">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="create_beneficiaries" class="form-label">Beneficiaries</label>
                                    <input type="text" class="form-control" id="create_beneficiaries" name="beneficiaries" 
                                           placeholder="e.g., All residents, Youth, Seniors">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="create_location" class="form-label">Location</label>
                                    <input type="text" class="form-control" id="create_location" name="location" 
                                           placeholder="Specific location within barangay">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="create_remarks" class="form-label">Remarks</label>
                                    <textarea class="form-control" id="create_remarks" name="remarks" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Save Project</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Project Modal -->
        <div class="modal fade" id="viewProjectModal" tabindex="-1" aria-labelledby="viewProjectModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewProjectModalLabel"><i class="fas fa-eye me-2"></i>Project Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Project Name:</strong>
                                <p id="view_project_name"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Proponent:</strong>
                                <p id="view_proponent"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <strong>Description:</strong>
                                <p id="view_project_description"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <strong>Status:</strong>
                                <p id="view_project_status"></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Category:</strong>
                                <p id="view_project_category"></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Priority Level:</strong>
                                <p id="view_priority_level"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <strong>Start Date:</strong>
                                <p id="view_start_date"></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>End Date:</strong>
                                <p id="view_end_date"></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Completion Date:</strong>
                                <p id="view_completion_date"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <strong>Total Budget:</strong>
                                <p id="view_total_budget"></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Budget Utilized:</strong>
                                <p id="view_budget_utilized"></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Budget Remaining:</strong>
                                <p id="view_budget_remaining"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <strong>Progress:</strong>
                                <p id="view_progress_percentage"></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Funding Source:</strong>
                                <p id="view_funding_source"></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Beneficiaries:</strong>
                                <p id="view_beneficiaries"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <strong>Location:</strong>
                                <p id="view_location"></p>
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

        <!-- Update Project Modal -->
        <div class="modal fade" id="updateProjectModal" tabindex="-1" aria-labelledby="updateProjectModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateProjectModalLabel"><i class="fas fa-edit me-2"></i>Update Project</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="updateProjectForm">
                        <input type="hidden" id="update_project_id" name="project_id">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="update_project_name" class="form-label">Project Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="update_project_name" name="project_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="update_proponent" class="form-label">Proponent <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="update_proponent" name="proponent" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="update_project_description" class="form-label">Project Description</label>
                                    <textarea class="form-control" id="update_project_description" name="project_description" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="update_project_status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="update_project_status" name="project_status" required>
                                        <option value="Planning">Planning</option>
                                        <option value="Ongoing">Ongoing</option>
                                        <option value="Completed">Completed</option>
                                        <option value="On Hold">On Hold</option>
                                        <option value="Cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="update_project_category" class="form-label">Category</label>
                                    <input type="text" class="form-control" id="update_project_category" name="project_category">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="update_priority_level" class="form-label">Priority Level</label>
                                    <select class="form-select" id="update_priority_level" name="priority_level">
                                        <option value="High">High</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Low">Low</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="update_start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="update_start_date" name="start_date">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="update_end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="update_end_date" name="end_date">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="update_completion_date" class="form-label">Completion Date</label>
                                    <input type="date" class="form-control" id="update_completion_date" name="completion_date">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="update_total_budget" class="form-label">Total Budget <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="update_total_budget" name="total_budget" step="0.01" min="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="update_budget_utilized" class="form-label">Budget Utilized</label>
                                    <input type="number" class="form-control" id="update_budget_utilized" name="budget_utilized" step="0.01" min="0">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="update_progress_percentage" class="form-label">Progress (%)</label>
                                    <input type="number" class="form-control" id="update_progress_percentage" name="progress_percentage" min="0" max="100">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="update_funding_source" class="form-label">Funding Source</label>
                                    <input type="text" class="form-control" id="update_funding_source" name="funding_source">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="update_beneficiaries" class="form-label">Beneficiaries</label>
                                    <input type="text" class="form-control" id="update_beneficiaries" name="beneficiaries">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="update_location" class="form-label">Location</label>
                                    <input type="text" class="form-control" id="update_location" name="location">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="update_remarks" class="form-label">Remarks</label>
                                    <textarea class="form-control" id="update_remarks" name="remarks" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Update Project</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Project Modal -->
        <div class="modal fade" id="deleteProjectModal" tabindex="-1" aria-labelledby="deleteProjectModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteProjectModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="deleteProjectForm">
                        <input type="hidden" id="delete_project_id" name="project_id">
                        <div class="modal-body">
                            <p>Are you sure you want to delete this project?</p>
                            <div class="alert alert-warning">
                                <strong>Project Name:</strong> <span id="delete_project_name"></span>
                            </div>
                            <p class="text-danger"><i class="fas fa-exclamation-circle"></i> This action cannot be undone!</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt me-2"></i>Delete Project</button>
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
                const table = document.getElementById('projectsTable');
                if (table && typeof simpleDatatables !== 'undefined') {
                    new simpleDatatables.DataTable(table, {
                        searchable: true,
                        fixedHeight: false,
                        perPage: 10
                    });
                }

                // Use event delegation for button clicks
                document.body.addEventListener('click', function(e) {
                    // View Project Handler
                    if (e.target.closest('.view-project-btn')) {
                        const button = e.target.closest('.view-project-btn');
                        const projectId = button.getAttribute('data-id');
                        
                        fetch(`../Controller/ProjectController.php?action=getById&id=${projectId}`)
                            .then(response => response.json())
                            .then(result => {
                                if (result.success) {
                                    const project = result.data;
                                    
                                    // Populate view modal
                                    document.getElementById('view_project_name').textContent = project.project_name || 'N/A';
                                    document.getElementById('view_proponent').textContent = project.proponent || 'N/A';
                                    document.getElementById('view_project_description').textContent = project.project_description || 'N/A';
                                    document.getElementById('view_project_status').textContent = project.project_status || 'N/A';
                                    document.getElementById('view_project_category').textContent = project.project_category || 'N/A';
                                    document.getElementById('view_priority_level').textContent = project.priority_level || 'N/A';
                                    document.getElementById('view_start_date').textContent = project.start_date ? new Date(project.start_date).toLocaleDateString() : 'N/A';
                                    document.getElementById('view_end_date').textContent = project.end_date ? new Date(project.end_date).toLocaleDateString() : 'N/A';
                                    document.getElementById('view_completion_date').textContent = project.completion_date ? new Date(project.completion_date).toLocaleDateString() : 'N/A';
                                    document.getElementById('view_total_budget').textContent = '₱' + parseFloat(project.total_budget).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                    document.getElementById('view_budget_utilized').textContent = '₱' + parseFloat(project.budget_utilized).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                    document.getElementById('view_budget_remaining').textContent = '₱' + parseFloat(project.budget_remaining).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                    document.getElementById('view_progress_percentage').textContent = project.progress_percentage + '%';
                                    document.getElementById('view_funding_source').textContent = project.funding_source || 'N/A';
                                    document.getElementById('view_beneficiaries').textContent = project.beneficiaries || 'N/A';
                                    document.getElementById('view_location').textContent = project.location || 'N/A';
                                    document.getElementById('view_remarks').textContent = project.remarks || 'N/A';
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: result.message || 'Failed to load project details'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to load project details. Please check console for details.'
                                });
                            });
                    }

                    // Edit Project Handler
                    if (e.target.closest('.edit-project-btn')) {
                        const button = e.target.closest('.edit-project-btn');
                        const projectId = button.getAttribute('data-id');
                        
                        fetch(`../Controller/ProjectController.php?action=getById&id=${projectId}`)
                            .then(response => response.json())
                            .then(result => {
                                if (result.success) {
                                    const project = result.data;
                                    
                                    // Populate update form
                                    document.getElementById('update_project_id').value = project.project_id;
                                    document.getElementById('update_project_name').value = project.project_name || '';
                                    document.getElementById('update_proponent').value = project.proponent || '';
                                    document.getElementById('update_project_description').value = project.project_description || '';
                                    document.getElementById('update_project_status').value = project.project_status || 'Planning';
                                    document.getElementById('update_project_category').value = project.project_category || '';
                                    document.getElementById('update_priority_level').value = project.priority_level || 'Medium';
                                    document.getElementById('update_start_date').value = project.start_date || '';
                                    document.getElementById('update_end_date').value = project.end_date || '';
                                    document.getElementById('update_completion_date').value = project.completion_date || '';
                                    document.getElementById('update_total_budget').value = project.total_budget || 0;
                                    document.getElementById('update_budget_utilized').value = project.budget_utilized || 0;
                                    document.getElementById('update_progress_percentage').value = project.progress_percentage || 0;
                                    document.getElementById('update_funding_source').value = project.funding_source || '';
                                    document.getElementById('update_beneficiaries').value = project.beneficiaries || '';
                                    document.getElementById('update_location').value = project.location || '';
                                    document.getElementById('update_remarks').value = project.remarks || '';
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: result.message || 'Failed to load project details'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to load project details'
                                });
                            });
                    }

                    // Delete Project Handler
                    if (e.target.closest('.delete-project-btn')) {
                        const button = e.target.closest('.delete-project-btn');
                        const projectId = button.getAttribute('data-id');
                        const projectName = button.getAttribute('data-name');
                        
                        document.getElementById('delete_project_id').value = projectId;
                        document.getElementById('delete_project_name').textContent = projectName;
                    }
                });

                // Create Project Form Submit
                document.getElementById('createProjectForm').addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    
                    try {
                        const response = await fetch('../Controller/ProjectController.php?action=create', {
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
                            text: 'Failed to create project'
                        });
                    }
                });

                // Update Project Form Submit
                document.getElementById('updateProjectForm').addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    
                    try {
                        const response = await fetch('../Controller/ProjectController.php?action=update', {
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
                            text: 'Failed to update project'
                        });
                    }
                });

                // Delete Project Form Submit
                document.getElementById('deleteProjectForm').addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    
                    try {
                        const response = await fetch('../Controller/ProjectController.php?action=delete', {
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
                            text: 'Failed to delete project'
                        });
                    }
                });
            });
        </script>
    </body>
</html>


        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // Initialize DataTable
            document.addEventListener('DOMContentLoaded', function() {
                const table = document.getElementById('projectsTable');
                if (table && typeof simpleDatatables !== 'undefined') {
                    new simpleDatatables.DataTable(table, {
                        searchable: true,
                        fixedHeight: false,
                        perPage: 10
                    });
                }

                // Use event delegation for dynamically loaded content
                document.body.addEventListener('click', function(e) {
                    // View Project Handler
                    if (e.target.closest('.view-project-btn')) {
                        const button = e.target.closest('.view-project-btn');
                        const projectId = button.getAttribute('data-id');
                        
                        fetch(`../Controller/ProjectController.php?action=getById&id=${projectId}`)
                            .then(response => response.json())
                            .then(result => {
                                if (result.success) {
                                    const project = result.data;
                                    
                                    // Populate view modal
                                    document.getElementById('view_project_name').textContent = project.project_name || 'N/A';
                                    document.getElementById('view_proponent').textContent = project.proponent || 'N/A';
                                    document.getElementById('view_project_description').textContent = project.project_description || 'N/A';
                                    document.getElementById('view_project_status').textContent = project.project_status || 'N/A';
                                    document.getElementById('view_project_category').textContent = project.project_category || 'N/A';
                                    document.getElementById('view_priority_level').textContent = project.priority_level || 'N/A';
                                    document.getElementById('view_start_date').textContent = project.start_date ? new Date(project.start_date).toLocaleDateString() : 'N/A';
                                    document.getElementById('view_end_date').textContent = project.end_date ? new Date(project.end_date).toLocaleDateString() : 'N/A';
                                    document.getElementById('view_completion_date').textContent = project.completion_date ? new Date(project.completion_date).toLocaleDateString() : 'N/A';
                                    document.getElementById('view_total_budget').textContent = '₱' + parseFloat(project.total_budget).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                    document.getElementById('view_budget_utilized').textContent = '₱' + parseFloat(project.budget_utilized).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                    document.getElementById('view_budget_remaining').textContent = '₱' + parseFloat(project.budget_remaining).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                    document.getElementById('view_progress_percentage').textContent = project.progress_percentage + '%';
                                    document.getElementById('view_funding_source').textContent = project.funding_source || 'N/A';
                                    document.getElementById('view_beneficiaries').textContent = project.beneficiaries || 'N/A';
                                    document.getElementById('view_location').textContent = project.location || 'N/A';
                                    document.getElementById('view_remarks').textContent = project.remarks || 'N/A';
                                } else {
                                    Swal.fire('Error', result.message || 'Failed to load project details', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire('Error', 'Failed to load project details', 'error');
                            });
                    }

                    // Edit Project Handler
                    if (e.target.closest('.edit-project-btn')) {
                        const button = e.target.closest('.edit-project-btn');
                        const projectId = button.getAttribute('data-id');
                        
                        fetch(`../Controller/ProjectController.php?action=getById&id=${projectId}`)
                            .then(response => response.json())
                            .then(result => {
                                if (result.success) {
                                    const project = result.data;
                                    
                                    // Populate update form
                                    document.getElementById('update_project_id').value = project.project_id;
                                    document.getElementById('update_project_name').value = project.project_name || '';
                                    document.getElementById('update_proponent').value = project.proponent || '';
                                    document.getElementById('update_project_description').value = project.project_description || '';
                                    document.getElementById('update_project_status').value = project.project_status || 'Planning';
                                    document.getElementById('update_project_category').value = project.project_category || '';
                                    document.getElementById('update_priority_level').value = project.priority_level || 'Medium';
                                    document.getElementById('update_start_date').value = project.start_date || '';
                                    document.getElementById('update_end_date').value = project.end_date || '';
                                    document.getElementById('update_completion_date').value = project.completion_date || '';
                                    document.getElementById('update_total_budget').value = project.total_budget || 0;
                                    document.getElementById('update_budget_utilized').value = project.budget_utilized || 0;
                                    document.getElementById('update_progress_percentage').value = project.progress_percentage || 0;
                                    document.getElementById('update_funding_source').value = project.funding_source || '';
                                    document.getElementById('update_beneficiaries').value = project.beneficiaries || '';
                                    document.getElementById('update_location').value = project.location || '';
                                    document.getElementById('update_remarks').value = project.remarks || '';
                                } else {
                                    Swal.fire('Error', result.message || 'Failed to load project details', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire('Error', 'Failed to load project details', 'error');
                            });
                    }

                    // Delete Project Handler
                    if (e.target.closest('.delete-project-btn')) {
                        const button = e.target.closest('.delete-project-btn');
                        const projectId = button.getAttribute('data-id');
                        const projectName = button.getAttribute('data-name');
                        
                        document.getElementById('delete_project_id').value = projectId;
                        document.getElementById('delete_project_name').textContent = projectName;
                    }
                });

                // View Project Handler (kept for backward compatibility)
                document.querySelectorAll('.view-project-btn').forEach(button => {
                    button.addEventListener('click', async function() {
                        const projectId = this.getAttribute('data-id');
                        
                        try {
                            const response = await fetch(`../Controller/ProjectController.php?action=getById&id=${projectId}`);
                            const result = await response.json();
                            
                            if (result.success) {
                                const project = result.data;
                                
                                // Populate view modal
                                document.getElementById('view_project_name').textContent = project.project_name || 'N/A';
                                document.getElementById('view_proponent').textContent = project.proponent || 'N/A';
                                document.getElementById('view_project_description').textContent = project.project_description || 'N/A';
                                document.getElementById('view_project_status').textContent = project.project_status || 'N/A';
                                document.getElementById('view_project_category').textContent = project.project_category || 'N/A';
                                document.getElementById('view_priority_level').textContent = project.priority_level || 'N/A';
                // View Project Handler (kept for backward compatibility)
                document.querySelectorAll('.view-project-btn').forEach(button => {
                    button.addEventListener('click', async function() {
                        const projectId = this.getAttribute('data-id');
                        
                        try {
                            const response = await fetch(`../Controller/ProjectController.php?action=getById&id=${projectId}`);
                            const result = await response.json();
                            
                            if (result.success) {
                                const project = result.data;
                                
                                // Populate view modal
                                document.getElementById('view_project_name').textContent = project.project_name || 'N/A';
                                document.getElementById('view_proponent').textContent = project.proponent || 'N/A';
                                document.getElementById('view_project_description').textContent = project.project_description || 'N/A';
                                document.getElementById('view_project_status').textContent = project.project_status || 'N/A';
                                document.getElementById('view_project_category').textContent = project.project_category || 'N/A';
                                document.getElementById('view_priority_level').textContent = project.priority_level || 'N/A';
                                document.getElementById('view_start_date').textContent = project.start_date ? new Date(project.start_date).toLocaleDateString() : 'N/A';
                                document.getElementById('view_end_date').textContent = project.end_date ? new Date(project.end_date).toLocaleDateString() : 'N/A';
                                document.getElementById('view_completion_date').textContent = project.completion_date ? new Date(project.completion_date).toLocaleDateString() : 'N/A';
                                document.getElementById('view_total_budget').textContent = '₱' + parseFloat(project.total_budget).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                document.getElementById('view_budget_utilized').textContent = '₱' + parseFloat(project.budget_utilized).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                document.getElementById('view_budget_remaining').textContent = '₱' + parseFloat(project.budget_remaining).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                document.getElementById('view_progress_percentage').textContent = project.progress_percentage + '%';
                                document.getElementById('view_funding_source').textContent = project.funding_source || 'N/A';
                                document.getElementById('view_beneficiaries').textContent = project.beneficiaries || 'N/A';
                                document.getElementById('view_location').textContent = project.location || 'N/A';
                                document.getElementById('view_remarks').textContent = project.remarks || 'N/A';
                            } else {
                                Swal.fire('Error', result.message || 'Failed to load project details', 'error');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            Swal.fire('Error', 'Failed to load project details', 'error');
                        }
                    });
                });

                // Edit Project Handler (kept for backward compatibility)
                document.querySelectorAll('.edit-project-btn').forEach(button => {
                    button.addEventListener('click', async function() {
                        const projectId = this.getAttribute('data-id');
                        
                        try {
                            const response = await fetch(`../Controller/ProjectController.php?action=getById&id=${projectId}`);
                            const result = await response.json();
                            
                            if (result.success) {
                                const project = result.data;
                                
                                // Populate update form
                                document.getElementById('update_project_id').value = project.project_id;
                                document.getElementById('update_project_name').value = project.project_name || '';
                                document.getElementById('update_proponent').value = project.proponent || '';
                                document.getElementById('update_project_description').value = project.project_description || '';
                                document.getElementById('update_project_status').value = project.project_status || 'Planning';
                                document.getElementById('update_project_category').value = project.project_category || '';
                                document.getElementById('update_priority_level').value = project.priority_level || 'Medium';
                                document.getElementById('update_start_date').value = project.start_date || '';
                                document.getElementById('update_end_date').value = project.end_date || '';
                                document.getElementById('update_completion_date').value = project.completion_date || '';
                                document.getElementById('update_total_budget').value = project.total_budget || 0;
                                document.getElementById('update_budget_utilized').value = project.budget_utilized || 0;
                                document.getElementById('update_progress_percentage').value = project.progress_percentage || 0;
                                document.getElementById('update_funding_source').value = project.funding_source || '';
                                document.getElementById('update_beneficiaries').value = project.beneficiaries || '';
                // Edit Project Handler (kept for backward compatibility)
                document.querySelectorAll('.edit-project-btn').forEach(button => {
                    button.addEventListener('click', async function() {
                        const projectId = this.getAttribute('data-id');
                        
                        try {
                            const response = await fetch(`../Controller/ProjectController.php?action=getById&id=${projectId}`);
                            const result = await response.json();
                            
                            if (result.success) {
                                const project = result.data;
                                
                                // Populate update form
                                document.getElementById('update_project_id').value = project.project_id;
                                document.getElementById('update_project_name').value = project.project_name || '';
                                document.getElementById('update_proponent').value = project.proponent || '';
                                document.getElementById('update_project_description').value = project.project_description || '';
                                document.getElementById('update_project_status').value = project.project_status || 'Planning';
                                document.getElementById('update_project_category').value = project.project_category || '';
                                document.getElementById('update_priority_level').value = project.priority_level || 'Medium';
                                document.getElementById('update_start_date').value = project.start_date || '';
                                document.getElementById('update_end_date').value = project.end_date || '';
                                document.getElementById('update_completion_date').value = project.completion_date || '';
                                document.getElementById('update_total_budget').value = project.total_budget || 0;
                                document.getElementById('update_budget_utilized').value = project.budget_utilized || 0;
                                document.getElementById('update_progress_percentage').value = project.progress_percentage || 0;
                                document.getElementById('update_funding_source').value = project.funding_source || '';
                                document.getElementById('update_beneficiaries').value = project.beneficiaries || '';
                                document.getElementById('update_location').value = project.location || '';
                                document.getElementById('update_remarks').value = project.remarks || '';
                            } else {
                                Swal.fire('Error', result.message || 'Failed to load project details', 'error');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            Swal.fire('Error', 'Failed to load project details', 'error');
                        }
                    });
                });

                // Delete Project Handler (kept for backward compatibility)
                document.querySelectorAll('.delete-project-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const projectId = this.getAttribute('data-id');
                        const projectName = this.getAttribute('data-name');
                        
                        document.getElementById('delete_project_id').value = projectId;
                        document.getElementById('delete_project_name').textContent = projectName;
                    });
                });

                // Create Project Form Submit
                document.getElementById('createProjectForm').addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    formData.append('action', 'create');
                    
                    try {
                        const response = await fetch('../Controller/ProjectController.php', {
                            method: 'POST',
                            body: formData
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            Swal.fire('Success', result.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', result.message, 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Failed to create project', 'error');
                    }
                });

                // Update Project Form Submit
                document.getElementById('updateProjectForm').addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    formData.append('action', 'update');
                    
                    try {
                        const response = await fetch('../Controller/ProjectController.php', {
                            method: 'POST',
                            body: formData
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            Swal.fire('Success', result.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', result.message, 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Failed to update project', 'error');
                    }
                });

                // Delete Project Form Submit
                document.getElementById('deleteProjectForm').addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    formData.append('action', 'delete');
                    
                    try {
                        const response = await fetch('../Controller/ProjectController.php', {
                            method: 'POST',
                            body: formData
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            Swal.fire('Success', result.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', result.message, 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Failed to delete project', 'error');
                    }
                });
            });
        </script>
    </body>
</html>
