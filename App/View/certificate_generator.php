<!DOCTYPE html>
<html lang="en">
    <?php
        // Access control consistent with other pages
        require_once __DIR__ . '/middleware/ProtectAuth.php';
        require_once __DIR__ . '/middleware/RBACProtect.php';
        
        // Load Resident model to fetch registered residents with household info
        require_once __DIR__ . '/../Model/Resident.php';
        require_once __DIR__ . '/../Config/Database.php';
        
        // Get residents with household address
        $database = new Database();
        $connection = $database->connect();
        $query = "SELECT r.resident_id, r.household_id, r.first_name, r.middle_name, r.last_name, 
                  r.birth_date, r.gender, r.age, r.contact_no, r.email, h.address 
                  FROM residents r 
                  LEFT JOIN households h ON r.household_id = h.household_id 
                  ORDER BY r.last_name ASC, r.first_name ASC";
        $result = $connection->query($query);
        $residents = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $residents[] = $row;
            }
        }

        include 'template/header.php';
    ?>
    <body class="sb-nav-fixed">

        <?php include 'template/header_navigation.php'; ?>

        <div id="layoutSidenav">
            <?php include 'template/sidebar_navigation.php'; ?>
            <div id="layoutSidenav_content">

                <main>
                    <div class="container-fluid px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="mt-4">Certificate Generator</h1>
                                <ol class="breadcrumb mb-4">
                                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Certificate Generator</li>
                                </ol>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-5">
                                <div class="card">
                                    <div class="card-header">
                                        <i class="bi bi-file-earmark-medical"></i> Input Details
                                    </div>
                                    <div class="card-body">
                                        <form id="certForm">
                                            <div class="mb-3">
                                                <label class="form-label">Select Resident <span class="text-danger">*</span></label>
                                                <select id="residentSelect" class="form-select" required>
                                                    <option value=""></option>
                                                    <?php if ($residents && count($residents) > 0): ?>
                                                        <?php foreach ($residents as $resident): ?>
                                                            <?php 
                                                                $fullName = trim($resident['first_name'] . ' ' . ($resident['middle_name'] ?? '') . ' ' . $resident['last_name']);
                                                                $address = !empty($resident['address']) ? $resident['address'] : 'Brgy. Biga';
                                                                $residentData = htmlspecialchars(json_encode([
                                                                    'id' => $resident['resident_id'],
                                                                    'name' => $fullName,
                                                                    'age' => $resident['age'],
                                                                    'address' => $address
                                                                ]));
                                                            ?>
                                                            <option value="<?php echo htmlspecialchars($resident['resident_id']); ?>" 
                                                                    data-resident='<?php echo $residentData; ?>'>
                                                                <?php echo htmlspecialchars($fullName . ' - ' . $resident['resident_id']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <option value="" disabled>No residents found. Please add residents first.</option>
                                                    <?php endif; ?>
                                                </select>
                                                <div class="form-text">Only registered residents can generate certificates</div>
                                            </div>
                                            
                                            <!-- Hidden fields auto-populated from resident selection -->
                                            <input type="hidden" id="residentId" value="">
                                            <input type="hidden" id="fullName" value="">
                                            <input type="hidden" id="age" value="">
                                            <input type="hidden" id="address" value="">
                                            
                                            <!-- Display selected resident info -->
                                            <div id="residentInfo" class="alert alert-info" style="display:none;">
                                                <strong>Selected Resident:</strong>
                                                <div id="residentInfoText"></div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Purpose</label>
                                                <select id="purpose" class="form-select">
                                                    <option value="Certificate of Residency">Certificate of Residency</option>
                                                    <option value="Certificate of Indigency">Certificate of Indigency</option>
                                                    <option value="Barangay Clearance">Barangay Clearance</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Date</label>
                                                <input type="date" id="certDate" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                            </div>
                                            <div class="d-grid gap-2">
                                                <button type="button" id="generateBtn" class="btn btn-primary" disabled>Generate Preview</button>
                                                <button type="button" id="printBtn" class="btn btn-success no-print" disabled>Print Certificate</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div class="card">
                                        <div class="card-header">Notes</div>
                                        <div class="card-body small text-muted">
                                            - <strong>Only registered residents</strong> can generate certificates.<br>
                                            - Select a resident from the dropdown to auto-fill their information.<br>
                                            - Click <strong>Generate Preview</strong> to view the certificate template.<br>
                                            - Click <strong>Print Certificate</strong> to open your browser print dialog.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-7">
                                <div class="card">
                                    <div class="card-header">Certificate Preview</div>
                                    <div class="card-body" id="printableArea">
                                        <!-- print-area: only this will be visible when printing -->
                                        <div class="print-area" id="certificate-area">
                                            <div id="certificate" class="certificate-wrapper">
                                                <div class="certificate-header">
                                                    <img src="../../assets/img/BIGA-LOGO.png" alt="Barangay Logo" class="barangay-logo" onerror="this.style.display='none'">
                                                    <div class="certificate-title">
                                                        <h6 style="margin-bottom:6px;">Republic of the Philippines</h6>
                                                        <h5 style="margin-bottom:6px; font-weight:700;">BARANGAY BIGA</h5>
                                                        <div style="font-size:0.9rem; color:#444;">Municipality / City Name - Province</div>
                                                    </div>
                                                </div>

                                                <hr style="border-top:2px solid #333; margin-top:6px; margin-bottom:16px;">

                                                <div style="text-align:center; margin-bottom:14px;">
                                                    <h4 style="text-decoration: underline; font-weight:700;">CERTIFICATE</h4>
                                                    <div style="font-size:0.95rem; color:#555;">(This is a system-generated certificate)</div>
                                                </div>

                                                <div class="certificate-body" id="certificateBody">
                                                    <p style="text-align:justify;">This is to certify that <strong id="p_name">[Full Name]</strong>, <strong id="p_age">[age]</strong> years old, residing at <strong id="p_address">[address]</strong>, is known to me to be of good moral character and a resident of Barangay Biga. This certification is issued upon the request of the above-named person for the purpose of <strong id="p_purpose">[purpose]</strong>.</p>

                                                    <p style="text-align:justify;">Issued this <strong id="p_date">[date]</strong> at Barangay Biga, Municipality / City Name.</p>
                                                </div>

                                                <div class="certificate-footer">
                                                    <div class="small text-muted">Barangay Seal</div>
                                                    <div class="official-sign">
                                                        <div style="height:60px;"></div>
                                                        <div style="font-weight:700;">Punong Barangay</div>
                                                        <div style="font-size:0.9rem;">(Signature over printed name)</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </main>

                <?php include 'template/footer.php'; ?>
            </div>
        </div>

        <?php include 'template/script.php'; ?>

        <!-- jQuery and Select2 JS (load after Bootstrap) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- Page-specific styles and print rules -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            /* Select2 Bootstrap 5 Theme Customization */
            .select2-container--default .select2-selection--single {
                height: 38px;
                border: 1px solid #ced4da;
                border-radius: 0.375rem;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 36px;
                color: #212529;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 36px;
            }
            .select2-container--default.select2-container--focus .select2-selection--single {
                border-color: #86b7fe;
                outline: 0;
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            }
            .select2-dropdown {
                border: 1px solid #ced4da;
                border-radius: 0.375rem;
            }
            .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
                background-color: #0d6efd;
            }
            .select2-container {
                width: 100% !important;
            }
            
            /* Global print settings */
            @page { margin: 10mm; }
            html, body { height: auto; margin: 0; padding: 0; background: white; }

            /* Certificate page styles */
            .certificate-wrapper { border: 1px solid #333; padding: 18px; background: #fff; color: #000; box-sizing: border-box; width: 100%; max-width: 190mm; margin: 0 auto; }
            .certificate-header { display:flex; align-items:center; gap:16px; margin-bottom: 12px; }
            .barangay-logo { width: 80px; height: 80px; object-fit: contain; }
            .certificate-title { text-align: center; width:100%; }
            .certificate-body { margin-top: 18px; font-size: 1.05rem; line-height: 1.6; }
            .certificate-footer { margin-top: 28px; display:flex; justify-content:space-between; align-items:center; }
            .official-sign { text-align:center; width:40%; }

            /* Print styles */
            @media print {
                @page { size: A4; margin: 20mm 15mm 15mm 15mm; }
                html, body { height: auto; margin: 0; padding: 0; background: white; }

                body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }

                body * { visibility: hidden !important; }

                #certificate-area, #certificate-area * { visibility: visible !important; }

                #certificate-area {
                    position: absolute !important;
                    top: 20mm !important;
                    left: 15mm !important;
                    right: 15mm !important;
                    width: calc(210mm - 30mm) !important;
                    margin: 0 auto !important;
                    transform: translateY(0) !important;
                    page-break-inside: avoid !important;
                }

                .certificate-wrapper {
                    width: 100% !important;
                    max-width: 800px !important;
                    margin: 0 auto !important;
                    padding-top: 10px !important;
                    page-break-inside: avoid !important;
                }

                .no-print, header, footer, nav, .sb-sidenav, .sb-topnav, .breadcrumb, .card-header .btn { display: none !important; }
            }
        </style>

        <!-- Page scripts -->
        <script>
            // Initialize Select2 on page load
            $(document).ready(function() {
                $('#residentSelect').select2({
                    placeholder: '-- Search for a Registered Resident --',
                    allowClear: true,
                    width: '100%',
                    theme: 'default',
                    language: {
                        noResults: function() {
                            return "No residents found. Please add residents first.";
                        },
                        searching: function() {
                            return "Searching...";
                        }
                    }
                });
                
                // Handle resident selection using jQuery for Select2 compatibility
                $('#residentSelect').on('change', function() {
                    const selectedValue = $(this).val();
                    const generateBtn = document.getElementById('generateBtn');
                    const printBtn = document.getElementById('printBtn');
                    const residentInfo = document.getElementById('residentInfo');
                    const residentInfoText = document.getElementById('residentInfoText');
                    
                    if (selectedValue) {
                        const selectedOption = this.options[this.selectedIndex];
                        try {
                            const residentData = JSON.parse(selectedOption.getAttribute('data-resident'));
                            
                            // Populate hidden fields
                            document.getElementById('residentId').value = residentData.id;
                            document.getElementById('fullName').value = residentData.name;
                            document.getElementById('age').value = residentData.age;
                            document.getElementById('address').value = residentData.address;
                            
                            // Show resident info
                            residentInfoText.innerHTML = `
                                <strong>Name:</strong> ${residentData.name}<br>
                                <strong>Age:</strong> ${residentData.age}<br>
                                <strong>Address:</strong> ${residentData.address}<br>
                                <strong>ID:</strong> ${residentData.id}
                            `;
                            residentInfo.style.display = 'block';
                            
                            // Enable buttons
                            generateBtn.disabled = false;
                            printBtn.disabled = false;
                        } catch (e) {
                            console.error('Error parsing resident data:', e);
                            alert('Error loading resident data');
                        }
                    } else {
                        // Reset if no resident selected
                        document.getElementById('residentId').value = '';
                        document.getElementById('fullName').value = '';
                        document.getElementById('age').value = '';
                        document.getElementById('address').value = '';
                        residentInfo.style.display = 'none';
                        generateBtn.disabled = true;
                        printBtn.disabled = true;
                    }
                });
            });
            
            function formatDate(input) {
                if (!input) return '';
                const d = new Date(input);
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                return d.toLocaleDateString(undefined, options);
            }

            function generateCertificate() {
                const residentId = document.getElementById('residentId').value;
                if (!residentId) {
                    alert('Please select a registered resident first.');
                    return false;
                }
                
                const name = document.getElementById('fullName').value.trim() || '[Full Name]';
                const age = document.getElementById('age').value.trim() || '[age]';
                const address = document.getElementById('address').value.trim() || '[address]';
                const purposeSelect = document.getElementById('purpose');
                const purpose = purposeSelect.options[purposeSelect.selectedIndex].value;
                const dateVal = document.getElementById('certDate').value;
                const formattedDate = formatDate(dateVal) || '[date]';

                document.getElementById('p_name').textContent = name;
                document.getElementById('p_age').textContent = age;
                document.getElementById('p_address').textContent = address;
                document.getElementById('p_purpose').textContent = purpose;
                document.getElementById('p_date').textContent = formattedDate;

                const bodyEl = document.getElementById('certificateBody');
                if (purpose === 'Certificate of Indigency') {
                    bodyEl.innerHTML = `<p style="text-align:justify;">This is to certify that <strong id=\"p_name\">${name}</strong>, <strong id=\"p_age\">${age}</strong> years old, residing at <strong id=\"p_address\">${address}</strong>, is a bona fide resident of Barangay Biga and qualifies for indigency assistance. This certification is issued upon request for the purpose of <strong id=\"p_purpose\">${purpose}</strong>.</p><p style="text-align:justify;">Issued this <strong id=\"p_date\">${formattedDate}</strong> at Barangay Biga, Municipality / City Name.</p>`;
                } else if (purpose === 'Barangay Clearance') {
                    bodyEl.innerHTML = `<p style="text-align:justify;">This is to certify that <strong id=\"p_name\">${name}</strong>, <strong id=\"p_age\">${age}</strong> years old, residing at <strong id=\"p_address\">${address}</strong>, has no pending criminal case recorded at Barangay Biga as of this date. This clearance is issued for the purpose of <strong id=\"p_purpose\">${purpose}</strong>.</p><p style="text-align:justify;">Issued this <strong id=\"p_date\">${formattedDate}</strong> at Barangay Biga, Municipality / City Name.</p>`;
                } else {
                    bodyEl.innerHTML = `<p style="text-align:justify;">This is to certify that <strong id=\"p_name\">${name}</strong>, <strong id=\"p_age\">${age}</strong> years old, residing at <strong id=\"p_address\">${address}</strong>, is known to me to be a resident of Barangay Biga. This certification is issued for the purpose of <strong id=\"p_purpose\">${purpose}</strong>.</p><p style="text-align:justify;">Issued this <strong id=\"p_date\">${formattedDate}</strong> at Barangay Biga, Municipality / City Name.</p>`;
                }
            }

            document.getElementById('generateBtn').addEventListener('click', function() {
                if (!document.getElementById('residentId').value) {
                    alert('Please select a registered resident first.');
                    return;
                }
                generateCertificate();
                document.getElementById('printableArea').scrollIntoView({behavior:'smooth'});
            });

            document.getElementById('printBtn').addEventListener('click', function() {
                if (!document.getElementById('residentId').value) {
                    alert('Please select a registered resident first.');
                    return;
                }
                generateCertificate();

                // Build minimal print CSS to ensure consistent A4 output in the print window
                const printCss = `
                    @page { size: A4; margin: 20mm 15mm 15mm 15mm; }
                    html, body { height: auto; margin: 0; padding: 0; background: white; }
                    body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }

                    /* Hide everything by default and reveal only the certificate-area */
                    body * { visibility: hidden !important; }
                    #certificate-area, #certificate-area * { visibility: visible !important; }

                    #certificate-area {
                        position: absolute !important;
                        top: 20mm !important;
                        left: 15mm !important;
                        right: 15mm !important;
                        width: calc(210mm - 30mm) !important;
                        margin: 0 auto !important;
                        transform: none !important;
                        page-break-inside: avoid !important;
                    }

                    .certificate-wrapper {
                        width: 100% !important;
                        max-width: 800px !important;
                        margin: 0 auto !important;
                        padding-top: 10px !important;
                        page-break-inside: avoid !important;
                    }

                    .certificate-header { display:flex; align-items:center; gap:16px; margin-bottom: 12px; }
                    .barangay-logo { width: 80px; height: 80px; object-fit: contain; }
                    .certificate-title { text-align: center; width:100%; }
                    .certificate-body { margin-top: 18px; font-size: 1.05rem; line-height: 1.6; }
                    .certificate-footer { margin-top: 28px; display:flex; justify-content:space-between; align-items:center; }
                    .official-sign { text-align:center; width:40%; }

                    /* Ensure no page-breaks inside these regions */
                    .certificate-wrapper, .print-area { page-break-inside: avoid !important; }
                `;

                const printable = document.getElementById('printableArea');
                if (!printable) { alert('Printable area not found'); return; }

                const newWin = window.open('', '_blank');
                if (!newWin) { alert('Popup blocked. Allow popups for this site to print.'); return; }

                const doc = newWin.document;
                doc.open();
                doc.write('<!doctype html><html><head><meta charset="utf-8"><title>Print Certificate</title>');
                doc.write('<style>' + printCss + '</style>');
                doc.write('</head><body>');

                // Clone printable content to avoid removing it from the current DOM
                const clone = printable.cloneNode(true);
                // Remove interactive elements from clone
                clone.querySelectorAll && clone.querySelectorAll('.no-print, button').forEach(function(el){ el.remove(); });

                // Ensure image sources are absolute or relative to site root if needed. If the image fails
                // to appear in the printed window, consider replacing its src with a root-relative path.
                doc.write(clone.innerHTML);
                doc.write('</body></html>');
                doc.close();

                // Give the new window time to render its content then call print
                newWin.focus();
                setTimeout(function(){
                    try { newWin.print(); } catch (e) { console.error('Print failed', e); }
                }, 300);
            });

            document.getElementById('certForm').addEventListener('submit', function(e){ e.preventDefault(); generateCertificate(); });
        </script>
    </body>
</html>
