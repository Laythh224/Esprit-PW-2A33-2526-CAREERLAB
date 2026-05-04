<div class="main-header">
    <style>
        .account-user-line {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
            color: #ffffff;
            line-height: 1.1;
        }
        .account-verified-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 999px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #fff;
            font-size: 12px;
            line-height: 1;
            vertical-align: middle;
            box-shadow: 0 6px 14px rgba(59, 130, 246, 0.32);
            flex: 0 0 auto;
        }
    </style>
    <div class="main-header-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="index.php?r=admin&view=dashboard" class="logo">
                <img src="views/assets/img/image_2026-04-11_005109464-removebg-preview.png" alt="navbar brand" class="navbar-brand" height="20" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
            </div>
            <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
        </div>
    </div>
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
        <div class="container-fluid">
            <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-search pe-1"><i class="fa fa-search search-icon"></i></button>
                    </div>
                    <input type="text" placeholder="Search ..." class="form-control" />
                </div>
            </nav>
            <div class="ms-auto d-flex align-items-center gap-3">
                <div class="d-flex flex-column text-end">
                    <span class="fw-semibold account-user-line">
                        <?php echo htmlspecialchars((string) ($_SESSION['user_name'] ?? 'Administrateur')); ?>
                        <?php if (!empty($_SESSION['account_verified'])): ?>
                            <span class="account-verified-badge" title="Compte vérifié">✔</span>
                        <?php endif; ?>
                    </span>
                    <small class="text-muted">Backoffice</small>
                </div>
                <a href="index.php?page=logout" class="btn btn-outline-danger btn-sm">Se déconnecter</a>
            </div>
        </div>
    </nav>
</div>
