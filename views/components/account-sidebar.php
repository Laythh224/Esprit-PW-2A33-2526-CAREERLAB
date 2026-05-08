<?php
$activeAccountPage = $activeAccountPage ?? '';
$pathPrefix = (strpos($_SERVER['SCRIPT_NAME'], 'startup2-1.0.0') !== false || strpos($_SERVER['SCRIPT_NAME'], 'projet_web') !== false) ? '../' : '';
?>
<div class="sidebar sidebar-style-2" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="<?php echo $pathPrefix; ?>index.php?r=admin&view=dashboard" class="logo">
                <img src="<?php echo $pathPrefix; ?>views/assets/img/image_2026-04-11_005109464-removebg-preview.png" alt="CareerLab" class="navbar-brand" style="height: 40px;" />
            </a>
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
                <li class="nav-item <?php echo $activeAccountPage === 'dashboard' ? 'active submenu' : ''; ?>">
                    <a data-bs-toggle="collapse" href="#dashboard" class="<?php echo $activeAccountPage === 'dashboard' ? '' : 'collapsed'; ?>" aria-expanded="<?php echo $activeAccountPage === 'dashboard' ? 'true' : 'false'; ?>">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse <?php echo $activeAccountPage === 'dashboard' ? 'show' : ''; ?>" id="dashboard">
                        <ul class="nav nav-collapse">
                            <li class="<?php echo $activeAccountPage === 'dashboard' ? 'active' : ''; ?>">
                                <a href="<?php echo $pathPrefix; ?>index.php?r=admin&view=dashboard">
                                    <span class="sub-item">Dashboard</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Components</h4>
                </li>

                <li class="nav-item <?php echo in_array($activeAccountPage, ['utilisateur', 'formateur', 'entreprise', 'inscription-entreprise', 'inscription-formateur']) ? 'active submenu' : ''; ?>">
                    <a data-bs-toggle="collapse" href="#base" class="<?php echo in_array($activeAccountPage, ['utilisateur', 'formateur', 'entreprise', 'inscription-entreprise', 'inscription-formateur']) ? '' : 'collapsed'; ?>" aria-expanded="<?php echo in_array($activeAccountPage, ['utilisateur', 'formateur', 'entreprise', 'inscription-entreprise', 'inscription-formateur']) ? 'true' : 'false'; ?>">
                        <i class="fas fa-layer-group"></i>
                        <p>Gestion des comptes</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse <?php echo in_array($activeAccountPage, ['utilisateur', 'formateur', 'entreprise', 'inscription-entreprise', 'inscription-formateur']) ? 'show' : ''; ?>" id="base">
                        <ul class="nav nav-collapse">
                            <li class="<?php echo $activeAccountPage === 'utilisateur' ? 'active' : ''; ?>">
                                <a href="<?php echo $pathPrefix; ?>index.php?page=gestion-utilisateurs"><span class="sub-item">Comptes Utilisateur</span></a>
                            </li>
                            <li class="<?php echo $activeAccountPage === 'formateur' ? 'active' : ''; ?>">
                                <a href="<?php echo $pathPrefix; ?>index.php?page=gestion-formateurs"><span class="sub-item">Comptes Formateur</span></a>
                            </li>
                            <li class="<?php echo $activeAccountPage === 'entreprise' ? 'active' : ''; ?>">
                                <a href="<?php echo $pathPrefix; ?>index.php?page=gestion-entreprises"><span class="sub-item">Comptes Entreprise</span></a>
                            </li>
                            <li class="<?php echo $activeAccountPage === 'inscription-entreprise' ? 'active' : ''; ?>">
                                <a href="<?php echo $pathPrefix; ?>index.php?page=inscription-entreprise"><span class="sub-item">Inscriptions Entreprises</span></a>
                            </li>
                            <li class="<?php echo $activeAccountPage === 'inscription-formateur' ? 'active' : ''; ?>">
                                <a href="<?php echo $pathPrefix; ?>index.php?page=inscription-formateur"><span class="sub-item">Inscriptions Formateurs</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item <?php echo $activeAccountPage === 'demandes-ia' ? 'active' : ''; ?>">
                    <a href="<?php echo $pathPrefix; ?>index.php?page=demandes-ia">
                        <i class="fas fa-robot"></i>
                        <p>Demandes IA</p>
                    </a>
                </li>

                <li class="nav-item <?php echo $activeAccountPage === 'metiers' ? 'active submenu' : ''; ?>">
                    <a data-bs-toggle="collapse" href="#metiersSub" class="<?php echo $activeAccountPage === 'metiers' ? '' : 'collapsed'; ?>" aria-expanded="<?php echo $activeAccountPage === 'metiers' ? 'true' : 'false'; ?>">
                        <i class="fas fa-briefcase"></i>
                        <p>Gestion Métiers</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse <?php echo $activeAccountPage === 'metiers' ? 'show' : ''; ?>" id="metiersSub">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="<?php echo $pathPrefix; ?>startup2-1.0.0/index.php?action=admin_metiers">
                                    <span class="sub-item">Liste des Métiers</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $pathPrefix; ?>startup2-1.0.0/index.php?action=admin_categories">
                                    <span class="sub-item">Catégories</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item <?php echo $activeAccountPage === 'evaluation' ? 'active' : ''; ?>">
                    <a href="<?php echo $pathPrefix; ?>index.php?page=evaluation">
                        <i class="fas fa-check-square"></i>
                        <p>Gestion Évaluations</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo $pathPrefix; ?>e-learning/index.php?r=back/formations">
                        <i class="fas fa-graduation-cap"></i>
                        <p>E-learning</p>
                    </a>
                </li>

                <li class="nav-item <?php echo $activeAccountPage === 'offres' ? 'active submenu' : ''; ?>">
                    <a data-bs-toggle="collapse" href="#offresSub" class="<?php echo $activeAccountPage === 'offres' ? '' : 'collapsed'; ?>" aria-expanded="<?php echo $activeAccountPage === 'offres' ? 'true' : 'false'; ?>">
                        <i class="fas fa-bullhorn"></i>
                        <p>Gestion Offres</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse <?php echo $activeAccountPage === 'offres' ? 'show' : ''; ?>" id="offresSub">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="<?php echo $pathPrefix; ?>index.php?page=admin-offres">
                                    <span class="sub-item">les offres</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo $pathPrefix; ?>index.php?page=admin-offres&view=about">
                                    <span class="sub-item">à propos</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="<?php echo $pathPrefix; ?>index.php?r=main">
                        <i class="fas fa-globe"></i>
                        <p>Front</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $pathPrefix; ?>index.php?page=logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Déconnexion</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
