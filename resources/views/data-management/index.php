<?php require_once base_path('resources/views/layouts/app.php'); ?>

<div class="page-container">
    <!-- Page Header -->
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 5px; color: #1f2937;"> Records Management</h1>
        <p style="font-size: 15px; color: #6b7280;">View and manage all barangay records, households, and individuals.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="stat-card">
            <h4>Total Barangays</h4>
            <div class="stat-value"><?php echo $totalBarangays; ?></div>
            <div class="stat-change">Active records</div>
        </div>
        <div class="stat-card" style="border-left-color: #10b981;">
            <h4>Households</h4>
            <div class="stat-value" style="color: #10b981;"><?php echo number_format($totalHouseholds); ?></div>
            <div class="stat-change">Total families</div>
        </div>
        <div class="stat-card" style="border-left-color: #f59e0b;">
            <h4>Individuals</h4>
            <div class="stat-value" style="color: #f59e0b;"><?php echo number_format($totalIndividuals); ?></div>
            <div class="stat-change">Total population</div>
        </div>
    </div>

    <!-- Barangays Section -->
    <div class="card" style="margin-bottom: 30px;">
        <div class="card-header">
            <h3>📍 Barangays</h3>
        </div>
        <div class="card-body" id="barangaysContainer">
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                <?php if (!empty($barangays)): ?>
                    <?php foreach ($barangays as $barangay): ?>
                        <div class="barangay-card" onclick="openBarangayModal(event, <?php echo $barangay['id']; ?>, '<?php echo htmlspecialchars($barangay['name']); ?>')"
                             style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; background-color: #f9fafb; transition: all 0.3s ease; cursor: pointer;"
                             onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'; this.style.transform='translateY(-2px)'; this.style.backgroundColor='#fff';"
                             onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)'; this.style.backgroundColor='#f9fafb';">
                            <h4 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 600; color: #1f2937;"><?php echo htmlspecialchars($barangay['name']); ?></h4>
                            <div style="font-size: 14px; color: #6b7280; margin-bottom: 8px;">
                                <div><strong>Population:</strong> <?php echo number_format($barangay['population']); ?></div>
                                <div><strong>Area:</strong> <?php echo number_format($barangay['area'], 2); ?> km²</div>
                                <div><strong>Chairman:</strong> <?php echo htmlspecialchars($barangay['chairman']); ?></div>
                                <div><strong>Contact:</strong> <?php echo htmlspecialchars($barangay['contact']); ?></div>
                            </div>
                            <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #e5e7eb; color: #3b82f6; font-size: 13px; font-weight: 600;">
                                Click to view members →
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; padding: 20px; text-align: center; color: #6b7280;">No barangays found</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Barangay Members Modal -->
    <style>
        .barangay-modal {
            display: none !important;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100vh;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            animation: fadeIn 0.3s ease;
            overflow: visible;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .barangay-modal.active {
            display: flex !important;
        }

        .barangay-modal-content {
            background: rgba(255, 255, 255, 0.95) !important;
            border: 1px solid rgba(30, 144, 255, 0.3) !important;
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.15),
                0 0 20px rgba(30, 144, 255, 0.2);
            max-width: 900px;
            width: 90%;
            max-height: 85vh;
            overflow: hidden;
            animation: slidePopup 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: fixed;
            z-index: 2001;
            cursor: move;
            user-select: none;
            display: flex;
            flex-direction: column;
            border-radius: 12px;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .barangay-modal-content::-webkit-scrollbar {
            width: 8px;
        }

        .barangay-modal-content::-webkit-scrollbar-track {
            background: rgba(30, 144, 255, 0.05);
            border-radius: 4px;
        }

        .barangay-modal-content::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(30, 144, 255, 0.4) 0%, rgba(100, 200, 255, 0.3) 100%);
            border-radius: 4px;
            box-shadow: 0 0 8px rgba(30, 144, 255, 0.2);
        }

        .barangay-modal-content::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, rgba(30, 144, 255, 0.6) 0%, rgba(100, 200, 255, 0.5) 100%);
            box-shadow: 0 0 12px rgba(30, 144, 255, 0.3);
        }

        .barangay-modal-content.dragging {
            animation: none;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15), 0 0 20px rgba(30, 144, 255, 0.2);
        }

        @keyframes slidePopup {
            from {
                transform: scale(0.92) translateY(30px);
                opacity: 0;
            }
            to {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }

        .barangay-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            background: linear-gradient(90deg, rgba(30, 144, 255, 0.1) 0%, rgba(100, 200, 255, 0.05) 100%);
            border-bottom: 2px solid rgba(30, 144, 255, 0.2);
            color: #1e3a8a;
            cursor: grab;
            user-select: none;
            position: relative;
            flex-shrink: 0;
            border-radius: 12px 12px 0 0;
            sticky: top;
            z-index: 100;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .barangay-modal-header:active {
            cursor: grabbing;
        }

        .barangay-modal-header::before {
            content: '📍 ';
            position: absolute;
            left: 12px;
            font-size: 18px;
            opacity: 0.7;
        }

        .barangay-modal-header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #1e3a8a;
            flex: 1;
            padding-left: 20px;
            letter-spacing: 0.5px;
            text-shadow: 0 0 8px rgba(30, 144, 255, 0.15);
        }

        .barangay-modal-close {
            background: rgba(30, 144, 255, 0.1);
            border: 1px solid rgba(30, 144, 255, 0.2);
            font-size: 24px;
            cursor: pointer;
            color: #1e3a8a;
            transition: all 0.2s ease;
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(30, 144, 255, 0.1);
        }

        .barangay-modal-close:hover {
            background: rgba(30, 144, 255, 0.2);
            border-color: rgba(30, 144, 255, 0.4);
            box-shadow: 0 0 15px rgba(30, 144, 255, 0.2);
            transform: rotate(90deg);
        }

        .search-box {
            display: flex;
            gap: 12px;
            padding: 20px 24px;
            background: rgba(30, 144, 255, 0.05);
            border-bottom: 1px solid rgba(30, 144, 255, 0.1);
            align-items: center;
            flex-shrink: 0;
        }

        .search-box input {
            flex: 1;
            padding: 10px 14px;
            border: 1px solid rgba(30, 144, 255, 0.2);
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: rgba(30, 144, 255, 0.05);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            color: #1f2937;
        }

        .search-box input:focus {
            outline: none;
            border-color: rgba(30, 144, 255, 0.4);
            box-shadow: 0 0 10px rgba(30, 144, 255, 0.15);
            background: rgba(30, 144, 255, 0.1);
        }

        .search-box input::placeholder {
            color: #9ca3af;
        }

        .search-box span {
            background: linear-gradient(135deg, rgba(30, 144, 255, 0.3) 0%, rgba(100, 200, 255, 0.2) 100%);
            color: #1e3a8a;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
            box-shadow: 0 0 8px rgba(30, 144, 255, 0.15);
            border: 1px solid rgba(30, 144, 255, 0.2);
        }

        .search-results {
            flex: 1;
            overflow-y: auto;
            padding: 16px 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .member-card {
            padding: 14px 16px;
            border: 1px solid rgba(30, 144, 255, 0.15);
            border-radius: 8px;
            background: rgba(30, 144, 255, 0.05);
            transition: all 0.2s ease;
            position: relative;
            overflow: visible;
        }

        .member-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 100%;
            background: linear-gradient(180deg, rgba(30, 144, 255, 0.4), rgba(100, 200, 255, 0.2));
            border-radius: 8px 0 0 8px;
        }

        .member-card:hover {
            background: rgba(30, 144, 255, 0.1);
            box-shadow: 0 0 12px rgba(30, 144, 255, 0.2);
            transform: translateY(-1px);
            border-color: rgba(30, 144, 255, 0.3);
        }

        .member-card h4 {
            margin: 0 0 8px 0;
            color: #1e3a8a;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .member-card h4::before {
            content: '👤';
            font-size: 16px;
        }

        .member-card .info-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            font-size: 12px;
            color: #4b5563;
        }

        .member-card .info-row div {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .info-label {
            font-weight: 600;
            color: #1e3a8a;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            opacity: 0.7;
        }

        .info-value {
            color: #1f2937;
            font-size: 12px;
            font-weight: 500;
        }

        .health-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            width: fit-content;
            border: 1px solid rgba(30, 144, 255, 0.2);
        }

        .health-healthy {
            background: rgba(16, 185, 129, 0.1);
            color: #065f46;
            border-color: rgba(16, 185, 129, 0.3) !important;
        }

        .health-at-risk {
            background: rgba(245, 158, 11, 0.1);
            color: #92400e;
            border-color: rgba(245, 158, 11, 0.3) !important;
        }

        .health-poor {
            background: rgba(239, 68, 68, 0.1);
            color: #7f1d1d;
            border-color: rgba(239, 68, 68, 0.3) !important;
        }

        .no-results {
            padding: 40px 20px;
            text-align: center;
            color: #4b5563;
            font-size: 13px;
            grid-column: 1 / -1;
        }

        .no-results::before {
            content: '🔍';
            display: block;
            font-size: 36px;
            margin-bottom: 10px;
        }

        /* Responsive Modal Styles */
        @media (max-width: 1024px) {
            .barangay-modal-content {
                max-width: 85%;
            }

            .barangay-modal-header h2 {
                font-size: 22px;
            }

            .member-card .info-row {
                grid-template-columns: repeat(1, 1fr);
            }
        }

        /* Small tablets (768px and down) */
        @media (max-width: 768px) {
            .barangay-modal-content {
                max-width: 90%;
                padding: 20px;
                max-height: 85vh;
            }

            .barangay-modal-header h2 {
                font-size: 18px;
            }

            .barangay-modal-header {
                margin-bottom: 15px;
                padding-bottom: 12px;
            }

            .search-box {
                flex-direction: column;
                gap: 8px;
            }

            .search-box input {
                padding: 8px 12px;
                font-size: 13px;
            }

            .search-results {
                max-height: 500px;
            }

            .member-card {
                padding: 12px;
                margin-bottom: 8px;
            }

            .member-card h4 {
                font-size: 14px;
                margin: 0 0 8px 0;
            }

            .member-card .info-row {
                grid-template-columns: repeat(1, 1fr);
                gap: 8px;
                font-size: 12px;
            }
        }

        /* Mobile (481px to 768px) */
        @media (max-width: 481px) {
            .barangay-modal-content {
                max-width: 95%;
                padding: 16px;
                max-height: 90vh;
                border-radius: 10px;
            }

            .barangay-modal-header {
                margin-bottom: 12px;
                padding-bottom: 10px;
            }

            .barangay-modal-header h2 {
                font-size: 16px;
                word-break: break-word;
            }

            .barangay-modal-close {
                font-size: 24px;
                width: 28px;
                height: 28px;
            }

            .search-box {
                flex-direction: column;
                gap: 6px;
                margin-bottom: 15px;
            }

            .search-box input {
                padding: 8px 12px;
                font-size: 12px;
            }

            .search-box span {
                font-size: 11px !important;
            }

            .search-results {
                max-height: 450px;
            }

            .member-card {
                padding: 10px;
                margin-bottom: 8px;
            }

            .member-card h4 {
                font-size: 13px;
                margin: 0 0 6px 0;
            }

            .member-card .info-row {
                grid-template-columns: repeat(1, 1fr);
                gap: 6px;
                font-size: 11px;
            }

            .no-results {
                padding: 30px 15px;
                font-size: 12px;
            }

            .info-label {
                font-size: 11px;
            }
        }

        /* Extra small (375px and down) */
        @media (max-width: 374px) {
            .barangay-modal-content {
                max-width: 97%;
                padding: 12px;
                max-height: 92vh;
                border-radius: 8px;
            }

            .barangay-modal-header h2 {
                font-size: 14px;
            }

            .barangay-modal-close {
                font-size: 20px;
                width: 24px;
                height: 24px;
            }

            .search-box input {
                padding: 6px 10px;
                font-size: 11px;
            }

            .search-results {
                max-height: 400px;
            }

            .member-card {
                padding: 8px;
                margin-bottom: 6px;
            }

            .member-card h4 {
                font-size: 12px;
            }

            .member-card .info-row {
                font-size: 10px;
                gap: 4px;
            }
        }
    </style>

    <div id="barangayModal" class="barangay-modal">
        <div class="barangay-modal-content">
            <div class="barangay-modal-header">
                <h2 id="barangayModalTitle">📍 Barangay Members</h2>
                <button class="barangay-modal-close" onclick="closeBarangayModal()">&times;</button>
            </div>

            <div class="search-box">
                <input type="text" id="memberSearchInput" placeholder="🔍 Search by name..." onkeyup="filterMembers()">
                <span style="align-self: center; color: #6b7280; font-size: 13px;">
                    Total: <strong id="totalMembersCount">0</strong> | Found: <strong id="filteredMembersCount">0</strong>
                </span>
            </div>

            <div class="search-results" id="membersList">
                <div class="no-results">Loading members...</div>
            </div>
        </div>
    </div>

    <script>
        let allMembers = [];
        let filteredMembers = [];
        let isDragging = false;
        let dragOffsetX = 0;
        let dragOffsetY = 0;

        function openBarangayModal(event, barangayId, barangayName) {
            const modal = document.getElementById('barangayModal');
            const modalContent = document.querySelector('.barangay-modal-content');
            
            document.getElementById('barangayModalTitle').innerText = '📍 ' + barangayName + ' - Members';
            document.getElementById('memberSearchInput').value = '';

            // Show modal with active class - CSS handles centering
            modal.classList.add('active');
            
            // Reset modal positioning to let CSS handle centering
            modalContent.style.position = 'fixed';
            modalContent.style.left = '50%';
            modalContent.style.top = '50%';
            modalContent.style.transform = 'translate(-50%, -50%)';

            // Fetch members for this barangay
            fetch(`/api/barangay-members/${barangayId}`)
                .then(response => response.json())
                .then(data => {
                    allMembers = data.members || [];
                    filteredMembers = [...allMembers];
                    document.getElementById('totalMembersCount').innerText = allMembers.length;
                    document.getElementById('filteredMembersCount').innerText = allMembers.length;
                    renderMembers();
                })
                .catch(error => {
                    console.error('Error fetching members:', error);
                    document.getElementById('membersList').innerHTML = '<div class="no-results">Error loading members. Please try again.</div>';
                });
        }

        function closeBarangayModal() {
            const modal = document.getElementById('barangayModal');
            modal.classList.remove('active');
        }

        // Drag functionality
        function initDragFunctionality() {
            const modalContent = document.querySelector('.barangay-modal-content');
            const header = document.querySelector('.barangay-modal-header');
            
            if (!header) return;
            
            header.addEventListener('mousedown', function(e) {
                isDragging = true;
                const rect = modalContent.getBoundingClientRect();
                dragOffsetX = e.clientX - rect.left;
                dragOffsetY = e.clientY - rect.top;
                
                modalContent.classList.add('dragging');
                // Remove centering transform during drag
                modalContent.style.transform = 'none';
                
                e.preventDefault();
            });
        }

        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            
            const modalContent = document.querySelector('.barangay-modal-content');
            if (!modalContent) return;
            
            let left = e.clientX - dragOffsetX;
            let top = e.clientY - dragOffsetY;
            
            // Keep modal within viewport bounds
            left = Math.max(0, Math.min(left, window.innerWidth - modalContent.offsetWidth));
            top = Math.max(0, Math.min(top, window.innerHeight - 100));
            
            modalContent.style.left = left + 'px';
            modalContent.style.top = top + 'px';
            modalContent.style.transform = 'none';
        });

        document.addEventListener('mouseup', function() {
            if (isDragging) {
                isDragging = false;
                const modalContent = document.querySelector('.barangay-modal-content');
                if (modalContent) {
                    modalContent.classList.remove('dragging');
                    // Reset to centered position when drag ends
                    modalContent.style.left = '50%';
                    modalContent.style.top = '50%';
                    modalContent.style.transform = 'translate(-50%, -50%)';
                }
            }
        });

        // Initialize drag on page load
        document.addEventListener('DOMContentLoaded', function() {
            initDragFunctionality();
        });

        function filterMembers() {
            const searchTerm = document.getElementById('memberSearchInput').value.toLowerCase();
            
            if (searchTerm.trim() === '') {
                filteredMembers = [...allMembers];
            } else {
                filteredMembers = allMembers.filter(member => {
                    const fullName = (member.first_name + ' ' + member.last_name).toLowerCase();
                    return fullName.includes(searchTerm);
                });
            }

            document.getElementById('filteredMembersCount').innerText = filteredMembers.length;
            renderMembers();
        }

        function renderMembers() {
            const membersList = document.getElementById('membersList');

            if (filteredMembers.length === 0) {
                membersList.innerHTML = '<div class="no-results">No members found matching your search.</div>';
                return;
            }

            function getHealthBadge(status) {
                const healthClass = status === 'Healthy' ? 'health-healthy' : 
                                    status === 'At Risk' ? 'health-at-risk' : 
                                    'health-poor';
                return `<span class="health-badge ${healthClass}">${status}</span>`;
            }

            membersList.innerHTML = filteredMembers.map(member => `
                <div class="member-card">
                    <h4>${member.first_name} ${member.last_name}</h4>
                    <div class="info-row">
                        <div>
                            <span class="info-label">📅 Age</span>
                            <span class="info-value">${member.age} years</span>
                        </div>
                        <div>
                            <span class="info-label">👥 Gender</span>
                            <span class="info-value">${member.gender}</span>
                        </div>
                        <div>
                            <span class="info-label">❤️ Health Status</span>
                            <span class="info-value">${getHealthBadge(member.health_status)}</span>
                        </div>
                        <div>
                            <span class="info-label">🎓 Education</span>
                            <span class="info-value">${member.education_level}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('barangayModal');
            if (event.target == modal) {
                closeBarangayModal();
            }
        }
    </script>

        <!-- Households Section -->
    <div class="card" style="margin-bottom: 30px;">
        <div class="card-header">
            <h3>🏠 Households (Latest 50)</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                <?php 
                $count = 0;
                if (!empty($households)): 
                    foreach ($households as $household): 
                        if ($count >= 16) break;
                        $count++;
                ?>
                    <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; background-color: #f9fafb; transition: all 0.3s ease;"
                         onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'; this.style.transform='translateY(-2px)';"
                         onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)';">
                        <h4 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 600; color: #1f2937;"><?php echo htmlspecialchars($household['household_head']); ?></h4>
                        <div style="font-size: 14px; color: #6b7280; margin-bottom: 8px;">
                            <div><strong>Address:</strong> <?php echo htmlspecialchars($household['address']); ?></div>
                            <div><strong>Members:</strong> <?php echo $household['member_count']; ?></div>
                            <div style="margin-top: 8px;">
                                <span style="padding: 4px 8px; border-radius: 4px; background-color: #dbeafe; color: #0c4a6e; font-size: 12px;">
                                    <?php echo htmlspecialchars($household['socioeconomic_status']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php 
                    endforeach;
                else: 
                ?>
                    <div style="grid-column: 1 / -1; padding: 20px; text-align: center; color: #6b7280;">No households found</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

        <!-- Individuals Section -->
    <div class="card">
        <div class="card-header">
            <h3>👤 Individuals (Latest 50)</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                <?php 
                $count = 0;
                if (!empty($individuals)): 
                    foreach ($individuals as $individual): 
                        if ($count >= 16) break;
                        $count++;
                ?>
                    <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; background-color: #f9fafb; transition: all 0.3s ease;"
                         onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'; this.style.transform='translateY(-2px)';"
                         onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)';">
                        <h4 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 600; color: #1f2937;"><?php echo htmlspecialchars($individual['first_name'] . ' ' . $individual['last_name']); ?></h4>
                        <div style="font-size: 14px; color: #6b7280; margin-bottom: 8px;">
                            <div><strong>Age:</strong> <?php echo $individual['age']; ?> years</div>
                            <div><strong>Gender:</strong> <?php echo htmlspecialchars($individual['gender']); ?></div>
                            <div><strong>Health:</strong> <?php echo htmlspecialchars($individual['health_status']); ?></div>
                            <div><strong>Education:</strong> <?php echo htmlspecialchars($individual['education_level']); ?></div>
                        </div>
                    </div>
                <?php 
                    endforeach;
                else: 
                ?>
                    <div style="grid-column: 1 / -1; padding: 20px; text-align: center; color: #6b7280;">No individuals found</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.page-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.grid {
    display: grid;
    gap: 20px;
    margin-bottom: 20px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #3b82f6;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #3b82f6;
    margin-bottom: 5px;
}

.card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.card-header {
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
}

.card-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
}

.card-body {
    padding: 20px;
}

.card-body table tbody tr:hover {
    background-color: #f9fafb;
}

@media (max-width: 768px) {
    .grid {
        grid-template-columns: 1fr;
    }
}
</style>




