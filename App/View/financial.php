<!DOCTYPE html>
<html lang="en">
    <?php 
        // Include authentication protection
        require_once __DIR__ . '/middleware/ProtectAuth.php';
        
        // Include RBAC protection (Admin and Staff)
        require_once __DIR__ . '/middleware/RBACProtect.php';
        
        include 'template/header.php';
        require_once __DIR__ . '/../Model/Financial.php';
        
        // Initialize Financial model
        $financialModel = new Financial();
        $transactions = $financialModel->getAll();
        $summary = $financialModel->getSummary();
        
        // Get categories for dropdowns
        $incomeCategories = $financialModel->getIncomeCategories();
        $expenseCategories = $financialModel->getExpenseCategories();
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
                        <h1 class="mt-4">Financial Management</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="dashboard.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Financial Management</li>
                        </ol>

                        <!-- Introduction Section -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-info-circle me-2"></i>About Financial Management</h5>
                                <p class="card-text">
                                    The Financial Management module provides comprehensive tools to track and manage the barangay's financial activities. 
                                    This system enables efficient monitoring of income sources, expense tracking, and generation of detailed financial reports 
                                    to ensure transparency and accountability in barangay fund management.
                                </p>
                                <h6 class="mt-3 mb-2"><strong>Key Features:</strong></h6>
                                <ul class="mb-0">
                                    <li><strong>Income Tracking:</strong> Record and monitor all barangay income sources including government allocations, community taxes, permit fees, and donations.</li>
                                    <li><strong>Expense Management:</strong> Track all expenditures with detailed categorization for infrastructure, utilities, office supplies, salaries, and other operational costs.</li>
                                    <li><strong>Financial Reports:</strong> Generate comprehensive financial reports including monthly, quarterly, and annual summaries with income vs. expense analysis.</li>
                                    <li><strong>Real-time Balance:</strong> View current financial status with automatic calculation of total income, total expenses, and net balance.</li>
                                    <li><strong>Transaction History:</strong> Maintain complete records of all financial transactions with reference numbers, payee/payer details, and payment methods.</li>
                                    <li><strong>Category Analysis:</strong> Analyze spending patterns and income sources by category to support better budget planning and resource allocation.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Financial Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-xl-4 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="small">Total Income</div>
                                                <div class="h4 mb-0">₱<?php echo number_format($summary['total_income'] ?? 0, 2); ?></div>
                                            </div>
                                            <div>
                                                <i class="fas fa-arrow-up fa-3x opacity-50"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="small">Total Expenses</div>
                                                <div class="h4 mb-0">₱<?php echo number_format($summary['total_expense'] ?? 0, 2); ?></div>
                                            </div>
                                            <div>
                                                <i class="fas fa-arrow-down fa-3x opacity-50"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="small">Net Balance</div>
                                                <div class="h4 mb-0">₱<?php echo number_format($summary['net_balance'] ?? 0, 2); ?></div>
                                            </div>
                                            <div>
                                                <i class="fas fa-wallet fa-3x opacity-50"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Transactions Table -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-table me-1"></i>
                                    Financial Transactions
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createTransactionModal">
                                        <i class="fas fa-plus"></i> Add Transaction
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="transactionsTable" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Category</th>
                                            <th>Amount</th>
                                            <th>Payee/Payer</th>
                                            <th>Payment Method</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($transactions && count($transactions) > 0): ?>
                                            <?php foreach ($transactions as $transaction): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($transaction['transaction_id']); ?></td>
                                                    <td><?php echo date('M d, Y', strtotime($transaction['transaction_date'])); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php echo $transaction['transaction_type'] === 'Income' ? 'success' : 'danger'; ?>">
                                                            <?php echo htmlspecialchars($transaction['transaction_type']); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($transaction['category']); ?></td>
                                                    <td class="text-end">₱<?php echo number_format($transaction['amount'], 2); ?></td>
                                                    <td><?php echo htmlspecialchars($transaction['payee_payer'] ?? 'N/A'); ?></td>
                                                    <td><?php echo htmlspecialchars($transaction['payment_method']); ?></td>
                                                    <td><?php echo htmlspecialchars(substr($transaction['description'] ?? '', 0, 50)) . (strlen($transaction['description'] ?? '') > 50 ? '...' : ''); ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#updateTransactionModal">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTransactionModal">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="9" class="text-center text-muted py-4">
                                                    <i class="fas fa-inbox me-2"></i>No transactions found. Click "Add Transaction" to create one.
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

        <!-- Create Transaction Modal -->
        <div class="modal fade" id="createTransactionModal" tabindex="-1" aria-labelledby="createTransactionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createTransactionModalLabel"><i class="fas fa-plus-circle me-2"></i>Add New Transaction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="createTransactionForm">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="transaction_date" class="form-label">Transaction Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="transaction_date" name="transaction_date" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="transaction_type" class="form-label">Transaction Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="transaction_type" name="transaction_type" required>
                                        <option value="">Select Type</option>
                                        <option value="Income">Income</option>
                                        <option value="Expense">Expense</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="category" name="category" placeholder="e.g., Barangay Allocation, Infrastructure" required>
                                    <small class="text-muted">Enter a category name</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0.01" placeholder="0.00" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="payee_payer" class="form-label">Payee/Payer</label>
                                    <input type="text" class="form-control" id="payee_payer" name="payee_payer" placeholder="Enter name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-select" id="payment_method" name="payment_method" required>
                                        <option value="Cash">Cash</option>
                                        <option value="Check">Check</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Online Payment">Online Payment</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="reference_number" class="form-label">Reference Number</label>
                                <input type="text" class="form-control" id="reference_number" name="reference_number" placeholder="Optional reference number">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter transaction details"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Create Transaction</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Update Transaction Modal -->
        <div class="modal fade" id="updateTransactionModal" tabindex="-1" aria-labelledby="updateTransactionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateTransactionModalLabel"><i class="fas fa-edit me-2"></i>Update Transaction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="updateTransactionForm">
                        <div class="modal-body">
                            <input type="hidden" id="transaction_id_edit" name="transaction_id">
                            <div class="mb-3">
                                <label for="transaction_id_display" class="form-label">Transaction ID</label>
                                <input type="text" class="form-control" id="transaction_id_display" disabled>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="transaction_date_edit" class="form-label">Transaction Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="transaction_date_edit" name="transaction_date" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="transaction_type_edit" class="form-label">Transaction Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="transaction_type_edit" name="transaction_type" required>
                                        <option value="Income">Income</option>
                                        <option value="Expense">Expense</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="category_edit" class="form-label">Category <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="category_edit" name="category" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="amount_edit" class="form-label">Amount <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="amount_edit" name="amount" step="0.01" min="0.01" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="payee_payer_edit" class="form-label">Payee/Payer</label>
                                    <input type="text" class="form-control" id="payee_payer_edit" name="payee_payer">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="payment_method_edit" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-select" id="payment_method_edit" name="payment_method" required>
                                        <option value="Cash">Cash</option>
                                        <option value="Check">Check</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Online Payment">Online Payment</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="reference_number_edit" class="form-label">Reference Number</label>
                                <input type="text" class="form-control" id="reference_number_edit" name="reference_number">
                            </div>
                            <div class="mb-3">
                                <label for="description_edit" class="form-label">Description</label>
                                <textarea class="form-control" id="description_edit" name="description" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Update Transaction</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Transaction Modal -->
        <div class="modal fade" id="deleteTransactionModal" tabindex="-1" aria-labelledby="deleteTransactionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title text-white" id="deleteTransactionModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>Delete Transaction</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="deleteTransactionForm">
                        <div class="modal-body">
                            <input type="hidden" id="delete_transaction_id" name="transaction_id">
                            <div class="alert alert-warning" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Warning!</strong> This action cannot be undone. Are you sure you want to delete this transaction?
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Transaction Details:</label>
                                <p id="delete_transaction_details" class="form-control-plaintext"></p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt me-2"></i>Delete Transaction</button>
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
            const API_URL = '../../App/Controller/FinancialController.php';

            // Set today's date as default
            document.addEventListener('DOMContentLoaded', function() {
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('transaction_date').value = today;
            });

            /**
             * Create Transaction Form Submission
             */
            document.getElementById('createTransactionForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = {
                    transaction_date: document.getElementById('transaction_date').value,
                    transaction_type: document.getElementById('transaction_type').value,
                    category: document.getElementById('category').value.trim(),
                    amount: parseFloat(document.getElementById('amount').value),
                    payee_payer: document.getElementById('payee_payer').value.trim() || null,
                    payment_method: document.getElementById('payment_method').value,
                    reference_number: document.getElementById('reference_number').value.trim() || null,
                    description: document.getElementById('description').value.trim() || null
                };

                // Validation
                if (!formData.transaction_date || !formData.transaction_type || !formData.category || !formData.amount) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validation Error',
                        text: 'Please fill all required fields.',
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
                        body: JSON.stringify(formData)
                    });

                    const result = await response.json();

                    if (result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: result.message,
                            confirmButtonColor: '#6ec207'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById('createTransactionForm').reset();
                                const modal = bootstrap.Modal.getInstance(document.getElementById('createTransactionModal'));
                                modal.hide();
                                setTimeout(() => {
                                    location.reload();
                                }, 500);
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
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
             * Update Transaction Form Submission
             */
            document.getElementById('updateTransactionForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const transactionId = document.getElementById('transaction_id_edit').value;
                const formData = {
                    transaction_id: parseInt(transactionId),
                    transaction_date: document.getElementById('transaction_date_edit').value,
                    transaction_type: document.getElementById('transaction_type_edit').value,
                    category: document.getElementById('category_edit').value.trim(),
                    amount: parseFloat(document.getElementById('amount_edit').value),
                    payee_payer: document.getElementById('payee_payer_edit').value.trim() || null,
                    payment_method: document.getElementById('payment_method_edit').value,
                    reference_number: document.getElementById('reference_number_edit').value.trim() || null,
                    description: document.getElementById('description_edit').value.trim() || null
                };

                if (!transactionId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error',
                        text: 'Transaction ID is missing.',
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
                        body: JSON.stringify(formData)
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
                                const modal = bootstrap.Modal.getInstance(document.getElementById('updateTransactionModal'));
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
             * Delete Transaction Form Submission
             */
            document.getElementById('deleteTransactionForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const transactionId = document.getElementById('delete_transaction_id').value;

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
                            transaction_id: parseInt(transactionId)
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
                                const modal = bootstrap.Modal.getInstance(document.getElementById('deleteTransactionModal'));
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
                if (e.target.closest('button[data-bs-target="#updateTransactionModal"]')) {
                    const row = e.target.closest('tr');
                    const cells = row.querySelectorAll('td');
                    
                    const transactionId = cells[0].textContent;
                    const dateText = cells[1].textContent.trim();
                    const type = cells[2].querySelector('.badge').textContent.trim();
                    const category = cells[3].textContent;
                    const amount = cells[4].textContent.replace('₱', '').replace(/,/g, '').trim();
                    const payeePayer = cells[5].textContent;
                    const paymentMethod = cells[6].textContent;
                    const description = cells[7].textContent;

                    // Convert date from "Mon dd, yyyy" to "yyyy-mm-dd"
                    const date = new Date(dateText);
                    const formattedDate = date.toISOString().split('T')[0];

                    document.getElementById('transaction_id_edit').value = transactionId;
                    document.getElementById('transaction_id_display').value = transactionId;
                    document.getElementById('transaction_date_edit').value = formattedDate;
                    document.getElementById('transaction_type_edit').value = type;
                    document.getElementById('category_edit').value = category;
                    document.getElementById('amount_edit').value = amount;
                    document.getElementById('payee_payer_edit').value = payeePayer === 'N/A' ? '' : payeePayer;
                    document.getElementById('payment_method_edit').value = paymentMethod;
                    document.getElementById('description_edit').value = description.replace('...', '');
                }

                if (e.target.closest('button[data-bs-target="#deleteTransactionModal"]')) {
                    const row = e.target.closest('tr');
                    const cells = row.querySelectorAll('td');
                    
                    const transactionId = cells[0].textContent;
                    const date = cells[1].textContent;
                    const type = cells[2].querySelector('.badge').textContent.trim();
                    const category = cells[3].textContent;
                    const amount = cells[4].textContent;

                    document.getElementById('delete_transaction_id').value = transactionId;
                    document.getElementById('delete_transaction_details').innerHTML = 
                        `<strong>ID:</strong> ${transactionId}<br>
                        <strong>Date:</strong> ${date}<br>
                        <strong>Type:</strong> ${type}<br>
                        <strong>Category:</strong> ${category}<br>
                        <strong>Amount:</strong> ${amount}`;
                }
            });

            /**
             * Clear forms when modals are hidden
             */
            document.getElementById('createTransactionModal').addEventListener('hide.bs.modal', function() {
                document.getElementById('createTransactionForm').reset();
            });

            document.getElementById('updateTransactionModal').addEventListener('hide.bs.modal', function() {
                document.getElementById('updateTransactionForm').reset();
            });

            document.getElementById('deleteTransactionModal').addEventListener('hide.bs.modal', function() {
                document.getElementById('deleteTransactionForm').reset();
            });
        </script>
    </body>
</html>
