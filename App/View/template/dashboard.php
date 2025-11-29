<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard Overview</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">
                <i class="fas fa-tachometer-alt me-2"></i>Barangay Management System Dashboard
            </li>
        </ol>

        <!-- TOP ROW - Key Performance Indicators -->
        <div class="row">
            <!-- Total Residents -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Residents
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php echo number_format($dashboardModel->getCountResidents()); ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between bg-primary text-white">
                        <a class="small text-white stretched-link text-decoration-none" href="Resident.php">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Total Households -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Households
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php echo number_format($dashboardModel->getCountHouseholds()); ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-home fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between bg-success text-white">
                        <a class="small text-white stretched-link text-decoration-none" href="household.php">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Active Blotter Cases -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Active Blotter Cases
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php echo number_format($dashboardModel->getActiveBlotterCases()); ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between bg-warning text-white">
                        <a class="small text-white stretched-link text-decoration-none" href="blotter.php">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Budget Utilization -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Budget Utilization (This Month)
                                </div>
                                <?php 
                                    $budgetData = $dashboardModel->getBudgetUtilization();
                                    $percentage = $budgetData['percentage'];
                                    $statusColor = $percentage > 90 ? 'danger' : ($percentage > 70 ? 'warning' : 'success');
                                ?>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $percentage; ?>%</div>
                                    </div>
                                    <div class="col">
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-<?php echo $statusColor; ?>" role="progressbar" 
                                                 style="width: <?php echo min($percentage, 100); ?>%" 
                                                 aria-valuenow="<?php echo $percentage; ?>" 
                                                 aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-pie fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between bg-info text-white">
                        <a class="small text-white stretched-link text-decoration-none" href="financial.php">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECOND ROW - Demographics Overview -->
        <div class="row">
            <!-- Population by Age Group -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-chart-pie me-2"></i>
                        <strong>Population by Age Group</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="ageGroupChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Contact Information Distribution -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-success">
                        <i class="fas fa-chart-doughnut me-2 text-white"></i>
                        <strong class="text-white">Residents Contact Information</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="genderChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- THIRD ROW - Blotter & Financial -->
        <div class="row">
            <!-- Incident Trends -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-warning">
                        <i class="fas fa-chart-line me-2 text-white"></i>
                        <strong class="text-white">Monthly Incident Reports (<?php echo date('Y'); ?>)</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="incidentTrendsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Budget vs Expenses -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <i class="fas fa-chart-bar me-2"></i>
                        <strong>Budget vs Expenses by Category</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="budgetExpenseChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- FOURTH ROW - Projects & Incidents -->
        <div class="row">
            <!-- Project Status -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white">
                        <i class="fas fa-tasks me-2"></i>
                        <strong>Barangay Projects Status</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="projectStatusChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Incident Status Distribution -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <i class="fas fa-clipboard-list me-2"></i>
                        <strong>Incident Status Distribution</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="incidentStatusChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- FIFTH ROW - Recent Activity -->
        <div class="row">
            <!-- Recent Blotter Cases -->
            <div class="col-xl-12 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <i class="fas fa-list me-2"></i>
                        <strong>Recent Blotter Cases</strong>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Case Number</th>
                                        <th>Incident Type</th>
                                        <th>Date</th>
                                        <th>Complainant</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $recentCases = $dashboardModel->getRecentBlotterCases();
                                    if (count($recentCases) > 0):
                                        foreach($recentCases as $case): 
                                            // Status badge colors
                                            $statusColors = [
                                                'Pending' => 'secondary',
                                                'Under Investigation' => 'info',
                                                'For Mediation' => 'warning',
                                                'Resolved' => 'success',
                                                'Closed' => 'dark',
                                                'Escalated' => 'danger'
                                            ];
                                            $statusColor = $statusColors[$case['incident_status']] ?? 'secondary';
                                            
                                            // Priority badge colors
                                            $priorityColors = [
                                                'High' => 'danger',
                                                'Medium' => 'warning',
                                                'Low' => 'info'
                                            ];
                                            $priorityColor = $priorityColors[$case['priority_level']] ?? 'secondary';
                                    ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($case['case_number']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($case['incident_type']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($case['incident_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($case['complainant_name']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $statusColor; ?>">
                                                <?php echo htmlspecialchars($case['incident_status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo $priorityColor; ?>">
                                                <?php echo htmlspecialchars($case['priority_level']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php 
                                        endforeach;
                                    else:
                                    ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No recent blotter cases found.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="blotter.php" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye me-1"></i>View All Cases
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart.js Script with Data -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        <script>
            // ============================================
            // BARANGAY MANAGEMENT SYSTEM - CHART DATA
            // ============================================

            // Fetch data from PHP Backend
            const ageGroupData = <?php 
                $ageData = $dashboardModel->getPopulationByAgeGroup();
                echo json_encode($ageData ?? []); 
            ?>;
            
            const genderData = <?php 
                $genderDist = $dashboardModel->getGenderDistribution();
                echo json_encode($genderDist ?? []); 
            ?>;
            
            const incidentTrendsData = <?php 
                $incidentData = $dashboardModel->getIncidentTrends();
                echo json_encode($incidentData ?? []); 
            ?>;
            
            const budgetExpenseData = <?php 
                $budgetExpenseInfo = $dashboardModel->getBudgetVsExpensesByCategory();
                echo json_encode($budgetExpenseInfo ?? []); 
            ?>;
            
            const projectStatusData = <?php 
                $projectData = $dashboardModel->getProjectStatus();
                echo json_encode($projectData ?? []); 
            ?>;
            
            const incidentStatusData = <?php 
                $incidentStatusDist = $dashboardModel->getIncidentStatusDistribution();
                echo json_encode($incidentStatusDist ?? []); 
            ?>;

            // Color Palettes
            const chartColors = {
                primary: 'rgba(78, 115, 223, 0.8)',
                success: 'rgba(28, 200, 138, 0.8)',
                info: 'rgba(54, 185, 204, 0.8)',
                warning: 'rgba(246, 194, 62, 0.8)',
                danger: 'rgba(231, 74, 59, 0.8)',
                secondary: 'rgba(133, 135, 150, 0.8)',
                light: 'rgba(230, 230, 230, 0.8)',
                dark: 'rgba(90, 92, 105, 0.8)'
            };

            const ageColors = ['rgba(54, 162, 235, 0.7)', 'rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)'];
            const genderColors = ['rgba(54, 162, 235, 0.7)', 'rgba(255, 99, 132, 0.7)'];
            const statusColors = [
                'rgba(133, 135, 150, 0.7)', // Pending
                'rgba(54, 185, 204, 0.7)',  // Under Investigation
                'rgba(246, 194, 62, 0.7)',  // For Mediation
                'rgba(28, 200, 138, 0.7)',  // Resolved
                'rgba(90, 92, 105, 0.7)',   // Closed
                'rgba(231, 74, 59, 0.7)'    // Escalated
            ];

            // 1. Age Group Chart (Pie)
            if (ageGroupData.length > 0) {
                const ctx1 = document.getElementById('ageGroupChart').getContext('2d');
                new Chart(ctx1, {
                    type: 'pie',
                    data: {
                        labels: ageGroupData.map(d => d.age_group),
                        datasets: [{
                            data: ageGroupData.map(d => d.count),
                            backgroundColor: ageColors,
                            borderColor: '#fff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + context.parsed + ' residents';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // 2. Gender Distribution Chart (Doughnut)
            if (genderData.length > 0) {
                const ctx2 = document.getElementById('genderChart').getContext('2d');
                new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: genderData.map(d => d.gender),
                        datasets: [{
                            data: genderData.map(d => d.count),
                            backgroundColor: genderColors,
                            borderColor: '#fff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + context.parsed + ' residents';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // 3. Incident Trends Chart (Line)
            const allMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const incidentCounts = new Array(12).fill(0);
            incidentTrendsData.forEach(d => {
                incidentCounts[d.month - 1] = d.incident_count;
            });

            const ctx3 = document.getElementById('incidentTrendsChart').getContext('2d');
            new Chart(ctx3, {
                type: 'line',
                data: {
                    labels: allMonths,
                    datasets: [{
                        label: 'Incident Reports',
                        data: incidentCounts,
                        borderColor: chartColors.warning,
                        backgroundColor: 'rgba(246, 194, 62, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: chartColors.warning,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // 4. Budget vs Expenses Chart (Bar)
            if (budgetExpenseData.length > 0) {
                const ctx4 = document.getElementById('budgetExpenseChart').getContext('2d');
                new Chart(ctx4, {
                    type: 'bar',
                    data: {
                        labels: budgetExpenseData.map(d => d.category),
                        datasets: [
                            {
                                label: 'Budget',
                                data: budgetExpenseData.map(d => d.budget),
                                backgroundColor: chartColors.success,
                                borderColor: chartColors.success,
                                borderWidth: 1
                            },
                            {
                                label: 'Expenses',
                                data: budgetExpenseData.map(d => d.expense),
                                backgroundColor: chartColors.danger,
                                borderColor: chartColors.danger,
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ₱' + context.parsed.y.toLocaleString();
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '₱' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // 5. Project Status Chart (Horizontal Bar)
            if (projectStatusData.length > 0) {
                const ctx5 = document.getElementById('projectStatusChart').getContext('2d');
                const projectColors = {
                    'Completed': chartColors.success,
                    'Ongoing': chartColors.info,
                    'Planned': chartColors.secondary
                };
                new Chart(ctx5, {
                    type: 'bar',
                    data: {
                        labels: projectStatusData.map(d => d.status),
                        datasets: [{
                            label: 'Number of Projects',
                            data: projectStatusData.map(d => d.count),
                            backgroundColor: projectStatusData.map(d => projectColors[d.status] || chartColors.secondary),
                            borderColor: '#fff',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }

            // 6. Incident Status Distribution Chart (Doughnut)
            if (incidentStatusData.length > 0) {
                const ctx6 = document.getElementById('incidentStatusChart').getContext('2d');
                new Chart(ctx6, {
                    type: 'doughnut',
                    data: {
                        labels: incidentStatusData.map(d => d.status),
                        datasets: [{
                            data: incidentStatusData.map(d => d.count),
                            backgroundColor: statusColors.slice(0, incidentStatusData.length),
                            borderColor: '#fff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + context.parsed + ' cases';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        </script>

        <!-- Custom CSS for Dashboard Cards -->
        <style>
            .border-left-primary {
                border-left: 0.25rem solid #4e73df !important;
            }
            .border-left-success {
                border-left: 0.25rem solid #1cc88a !important;
            }
            .border-left-warning {
                border-left: 0.25rem solid #f6c23e !important;
            }
            .border-left-info {
                border-left: 0.25rem solid #36b9cc !important;
            }
            .text-gray-800 {
                color: #5a5c69 !important;
            }
            .shadow {
                box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15) !important;
            }
            .progress-sm {
                height: 0.5rem;
            }
        </style>
    </div>
</main>