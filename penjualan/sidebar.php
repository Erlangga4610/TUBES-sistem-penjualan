<?php
// Detect current page for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Mobile Toggle Button -->
<button class="mobile-toggle" type="button" id="mobileToggle">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
    </svg>
</button>

<!-- Sidebar Navigation -->
<nav class="sidebar" id="sidebarNav">
    <!-- Brand/Logo -->
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <i class="bi bi-shop"></i>
            <span>App Penjualan</span>
        </div>
        <div class="sidebar-subtitle">Management System</div>
    </div>
    
    <!-- Navigation Menu -->
    <div class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">Menu Utama</div>
            <ul class="nav flex-column">
                <li class="nav-item sidebar-nav-item">
                    <a href="index.php" class="sidebar-link <?= ($current_page == 'index.php') ? 'active' : '' ?>">
                        <i class="bi bi-grid-1x2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item sidebar-nav-item">
                    <a href="tambah.php" class="sidebar-link <?= ($current_page == 'tambah.php') ? 'active' : '' ?>">
                        <i class="bi bi-cart-plus"></i>
                        <span>Transaksi Baru</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Master Data</div>
            <ul class="nav flex-column">
                <li class="nav-item sidebar-nav-item">
                    <a href="tambah_barang.php" class="sidebar-link <?= ($current_page == 'tambah_barang.php') ? 'active' : '' ?>">
                        <i class="bi bi-box-seam"></i>
                        <span>Barang</span>
                    </a>
                </li>
                <li class="nav-item sidebar-nav-item">
                    <a href="tambah_sales.php" class="sidebar-link <?= ($current_page == 'tambah_sales.php') ? 'active' : '' ?>">
                        <i class="bi bi-people"></i>
                        <span>Sales</span>
                    </a>
                </li>
                <li class="nav-item sidebar-nav-item">
                    <a href="tambah_customer.php" class="sidebar-link <?= ($current_page == 'tambah_customer.php') ? 'active' : '' ?>">
                        <i class="bi bi-person-badge"></i>
                        <span>Customer</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Laporan</div>
            <ul class="nav flex-column">
                <li class="nav-item sidebar-nav-item">
                    <a href="laporan.php" class="sidebar-link <?= ($current_page == 'laporan.php' || $current_page == 'cetak_laporan.php') ? 'active' : '' ?>">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        <span>Laporan Penjualan</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="sidebar-footer">
        <div class="sidebar-footer-text">
            <i class="bi bi-gear me-1"></i>Admin Panel v1.0
        </div>
    </div>
</nav>

<!-- Mobile Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Mobile Sidebar Panel -->
<div class="mobile-sidebar-panel" id="mobileSidebar">
    <div class="mobile-sidebar-header">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-shop" style="color: #818cf8; font-size: 24px;"></i>
            <span class="fw-bold" style="font-size: 18px; color: #fff;">App Penjualan</span>
        </div>
        <button type="button" class="mobile-sidebar-close" id="sidebarClose">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
            </svg>
        </button>
    </div>
    <div class="mobile-sidebar-body">
        <div class="nav-section">
            <div class="nav-section-title" style="color: rgba(255,255,255,0.6);">Menu Utama</div>
            <ul class="nav flex-column">
                <li class="nav-item sidebar-nav-item">
                    <a href="index.php" class="sidebar-link <?= ($current_page == 'index.php') ? 'active' : '' ?>">
                        <i class="bi bi-grid-1x2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item sidebar-nav-item">
                    <a href="tambah.php" class="sidebar-link <?= ($current_page == 'tambah.php') ? 'active' : '' ?>">
                        <i class="bi bi-cart-plus"></i>
                        <span>Transaksi Baru</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title" style="color: rgba(255,255,255,0.6);">Master Data</div>
            <ul class="nav flex-column">
                <li class="nav-item sidebar-nav-item">
                    <a href="tambah_barang.php" class="sidebar-link <?= ($current_page == 'tambah_barang.php') ? 'active' : '' ?>">
                        <i class="bi bi-box-seam"></i>
                        <span>Barang</span>
                    </a>
                </li>
                <li class="nav-item sidebar-nav-item">
                    <a href="tambah_sales.php" class="sidebar-link <?= ($current_page == 'tambah_sales.php') ? 'active' : '' ?>">
                        <i class="bi bi-people"></i>
                        <span>Sales</span>
                    </a>
                </li>
                <li class="nav-item sidebar-nav-item">
                    <a href="tambah_customer.php" class="sidebar-link <?= ($current_page == 'tambah_customer.php') ? 'active' : '' ?>">
                        <i class="bi bi-person-badge"></i>
                        <span>Customer</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title" style="color: rgba(255,255,255,0.6);">Laporan</div>
            <ul class="nav flex-column">
                <li class="nav-item sidebar-nav-item">
                    <a href="laporan.php" class="sidebar-link <?= ($current_page == 'laporan.php' || $current_page == 'cetak_laporan.php') ? 'active' : '' ?>">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        <span>Laporan Penjualan</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Mobile Sidebar JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.getElementById('mobileToggle');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebarClose = document.getElementById('sidebarClose');
    
    if (mobileToggle && mobileSidebar) {
        // Toggle sidebar on button click
        mobileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = mobileSidebar.classList.contains('active');
            
            if (isOpen) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            closeSidebar();
        });
    }
    
    if (sidebarClose) {
        sidebarClose.addEventListener('click', function() {
            closeSidebar();
        });
    }
    
    // Close sidebar when clicking on a link
    const sidebarLinks = mobileSidebar ? mobileSidebar.querySelectorAll('.sidebar-link') : [];
    sidebarLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            closeSidebar();
        });
    });
    
    // Keyboard escape to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSidebar();
        }
    });
    
    function openSidebar() {
        if (mobileSidebar) {
            mobileSidebar.classList.add('active');
            mobileSidebar.style.visibility = 'visible';
            mobileSidebar.style.transform = 'translateX(0)';
        }
        if (sidebarOverlay) {
            sidebarOverlay.classList.add('active');
        }
        document.body.classList.add('sidebar-mobile-open');
    }
    
    function closeSidebar() {
        if (mobileSidebar) {
            mobileSidebar.classList.remove('active');
            mobileSidebar.style.visibility = '';
            mobileSidebar.style.transform = '';
        }
        if (sidebarOverlay) {
            sidebarOverlay.classList.remove('active');
        }
        document.body.classList.remove('sidebar-mobile-open');
    }
});
</script>