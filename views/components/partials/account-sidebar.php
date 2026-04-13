<?php
$activeAccountPage = $activeAccountPage ?? '';
?>
<div class="sidebar sidebar-style-2" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="index.php?page=dashboard-admin" class="logo text-white fw-bold text-decoration-none">Career Lab</a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
            </div>
            <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
        </div>
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item">
                    <a href="index.php?page=dashboard-admin"><i class="fas fa-home"></i><p>Dashboard</p></a>
                </li>
                <li class="nav-item active submenu">
                    <a data-bs-toggle="collapse" href="#base"><i class="fas fa-layer-group"></i><p>Gestion des comptes</p><span class="caret"></span></a>
                    <div class="collapse show" id="base">
                        <ul class="nav nav-collapse">
                            <li class="<?php echo $activeAccountPage === 'utilisateur' ? 'active' : ''; ?>"><a href="index.php?page=gestion-utilisateurs"><span class="sub-item">Comptes Utilisateur</span></a></li>
                            <li class="<?php echo $activeAccountPage === 'formateur' ? 'active' : ''; ?>"><a href="index.php?page=gestion-formateurs"><span class="sub-item">Comptes Formateur</span></a></li>
                            <li class="<?php echo $activeAccountPage === 'entreprise' ? 'active' : ''; ?>"><a href="index.php?page=gestion-entreprises"><span class="sub-item">Comptes Entreprise</span></a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=accueil"><i class="fas fa-globe"></i><p>Front</p></a>
                </li>
            </ul>
        </div>
    </div>
</div>
