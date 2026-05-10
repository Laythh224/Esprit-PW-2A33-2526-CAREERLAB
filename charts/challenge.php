<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Défis — Administration</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="../assets/img/kaiadmin/favicon.ico"
      type="image/x-icon"
    />

    <script src="../assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["../assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
  </head>
  <body>
    <div class="wrapper">
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <div class="logo-header" data-background-color="dark">
            <a href="../components/avatars.html">
              <span class="sub-item">Career Lab</span>
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              <li class="nav-item">
                <a
                  data-bs-toggle="collapse"
                  href="#dashboard"
                  class="collapsed"
                  aria-expanded="false"
                >
                  <i class="fas fa-home"></i>
                  <p>Tableau de bord</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="dashboard">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="../index.html">
                        <span class="sub-item">Tableau de bord 1</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Composants</h4>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#base">
                  <i class="fas fa-layer-group"></i>
                  <p>utilisateur</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="base">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="../components/avatars.html">
                        <span class="sub-item">utilisateur</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#sidebarLayouts">
                  <i class="fas fa-th-list"></i>
                  <p>métiers</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="sidebarLayouts">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="../sidebar-style-2.html">
                        <span class="sub-item">métiers</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#forms">
                  <i class="fas fa-pen-square"></i>
                  <p>evaluation</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="forms">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="../forms/forms.html">
                        <span class="sub-item">evaluation</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#tables">
                  <i class="fas fa-table"></i>
                  <p>les offres</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="tables">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="../tables/tables.html">
                        <span class="sub-item">les offres</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#maps">
                  <i class="fas fa-map-marker-alt"></i>
                  <p>E_learning</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="maps">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="../maps/googlemaps.html">
                        <span class="sub-item">E_learning</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item active submenu">
                <a data-bs-toggle="collapse" href="#navBlog">
                  <i class="far fa-chart-bar"></i>
                  <p>blog</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse show" id="navBlog">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="charts.php">
                        <span class="sub-item">blog</span>
                      </a>
                    </li>
                    <li class="active">
                      <a href="challenge.php">
                        <span class="sub-item">challenge</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a href="../indexF.html">
                  <i class="fas fa-external-link-alt"></i>
                  <p>Front-office</p>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <div class="logo-header" data-background-color="dark">
              <a href="../index.html" class="logo">
                <img
                  src="../assets/img/kaiadmin/logo_light.svg"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
                />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
          </div>
          <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
          >
            <div class="container-fluid">
              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <div class="avatar-sm">
                      <img
                        src="../assets/img/profile.jpg"
                        alt="..."
                        class="avatar-img rounded-circle"
                      />
                    </div>
                    <span class="profile-username">
                      <span class="op-7">Salut,</span>
                      <span class="fw-bold">Admin</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <li>
                      <a class="dropdown-item" href="../index.html">Tableau de bord</a>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
        </div>

        <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
              <div>
                <h3 class="fw-bold mb-3">Défis</h3>
                <h6 class="op-7 mb-2">
                  Statistiques des défis et CRUD (stockage local)
                </h6>
              </div>
            </div>

            <div class="row mb-4">
              <div class="col-6 col-md-4 col-lg-2 mb-3">
                <div class="card card-stats card-round">
                  <div class="card-body text-center">
                    <p class="card-category mb-1">Total des défis</p>
                    <h4 class="card-title" id="totalChallenges">0</h4>
                  </div>
                </div>
              </div>
              <div class="col-6 col-md-4 col-lg-2 mb-3">
                <div class="card card-stats card-round">
                  <div class="card-body text-center">
                    <p class="card-category mb-1">Défis actifs</p>
                    <h4 class="card-title" id="activeChallenges">0</h4>
                  </div>
                </div>
              </div>
              <div class="col-6 col-md-4 col-lg-2 mb-3">
                <div class="card card-stats card-round">
                  <div class="card-body text-center">
                    <p class="card-category mb-1">Total des commentaires</p>
                    <h4 class="card-title" id="totalChallengeComments">0</h4>
                  </div>
                </div>
              </div>
              <div class="col-6 col-md-4 col-lg-2 mb-3">
                <div class="card card-stats card-round">
                  <div class="card-body text-center">
                    <p class="card-category mb-1">Upvotes des commentaires</p>
                    <h4 class="card-title" id="totalCommentUpvotes">0</h4>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mb-4">
              <div class="col-md-6 mb-4">
                <div class="card card-round">
                  <div class="card-header">
                    <h4 class="card-title mb-0">Défis — flair</h4>
                  </div>
                  <div class="card-body">
                    <div style="max-height: 280px; position: relative">
                      <canvas id="challengesFlairPieChart"></canvas>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-4">
                <div class="card card-round">
                  <div class="card-header">
                    <h4 class="card-title mb-0">Défis — statut</h4>
                  </div>
                  <div class="card-body">
                    <div style="max-height: 280px; position: relative">
                      <canvas id="challengesStatusPieChart"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card card-round mb-4">
              <div class="card-body">
                <div
                  class="d-flex justify-content-between align-items-center mb-3"
                >
                  <div>
                    <h4 class="card-title">Défis</h4>
                    <p class="card-category">Créer, modifier et supprimer — 10 lignes par page</p>
                  </div>
                  <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" id="exportChallengesPdfBtn">
                      Exporter en PDF
                    </button>
                    <button type="button" class="btn btn-primary" id="addChallengeBtn">
                      Ajouter un défi
                    </button>
                  </div>
                </div>
                <div class="table-responsive">
                  <table
                    id="challengesTable"
                    class="table table-striped table-bordered"
                    style="width: 100%"
                  >
                    <thead>
                      <tr>
                        <th>Thème</th>
                        <th>Flair</th>
                        <th>Type de créateur</th>
                        <th>Date de début</th>
                        <th>Date de fin</th>
                        <th>Statut</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody id="challengesTableBody"></tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="card card-round mb-4">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <div>
                    <h4 class="card-title">Commentaires des défis</h4>
                    <p class="card-category">Gérer les commentaires avec actions CRUD</p>
                  </div>
                  <button type="button" class="btn btn-primary" id="addCommentBtn">
                    Ajouter un commentaire
                  </button>
                </div>
                <div class="row g-3 mb-3">
                  <div class="col-md-4">
                    <label class="form-label" for="commentChallengeFilter">Filtrer par défi</label>
                    <select id="commentChallengeFilter" class="form-control"></select>
                  </div>
                </div>
                <div class="table-responsive">
                  <table
                    id="commentsTable"
                    class="table table-striped table-bordered"
                    style="width: 100%"
                  >
                    <thead>
                      <tr>
                        <th>Défi</th>
                        <th>Commentaire</th>
                        <th>Média</th>
                        <th>Créé le</th>
                        <th>Upvotes</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody id="commentsTableBody"></tbody>
                  </table>
                </div>
              </div>
            </div>

            <div
              class="modal fade"
              id="challengeModal"
              tabindex="-1"
              aria-hidden="true"
            >
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="challengeModalTitle">
                      Ajouter un défi
                    </h5>
                    <button
                      type="button"
                      class="btn-close"
                      data-bs-dismiss="modal"
                      aria-label="Fermer"
                    ></button>
                  </div>
                  <div class="modal-body">
                    <form id="challengeForm" novalidate>
                      <input type="hidden" id="challengeId" />
                      <div class="mb-3">
                        <label class="form-label" for="challengePostId"
                          >Associer à un post *</label
                        >
                        <select class="form-control" id="challengePostId"></select>
                        <div class="text-danger small" id="challengePostIdError"></div>
                        <div class="small text-muted mt-1">
                          Un défi associé à un post qui n'a pas atteint le minimum d'upvotes sera enregistré comme <b>à venir</b>.
                        </div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="challengeTheme"
                          >Thème *</label
                        >
                        <input type="text" class="form-control" id="challengeTheme" />
                        <div class="text-danger small" id="challengeThemeError"></div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="challengeDescription"
                          >Description *</label
                        >
                        <textarea
                          class="form-control"
                          id="challengeDescription"
                          rows="3"
                        ></textarea>
                        <div
                          class="text-danger small"
                          id="challengeDescriptionError"
                        ></div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="challengeFlair">Flair *</label>
                        <select class="form-control" id="challengeFlair">
                          <option value="">Sélectionner un flair</option>
                          <option value="Projet">Projet</option>
                          <option value="Showcast">Showcast</option>
                          <option value="Débat">Débat</option>
                          <option value="Pitch">Pitch</option>
                        </select>
                        <div class="text-danger small" id="challengeFlairError"></div>
                      </div>
                      <div class="row g-3">
                        <div class="col-md-4">
                          <label class="form-label" for="challengeCreatorType"
                            >Type de créateur *</label
                          >
                          <select class="form-control" id="challengeCreatorType">
                            <option value="">Sélectionner un type de créateur</option>
                            <option value="Formateur">Formateur</option>
                            <option value="Entreprise">Entreprise</option>
                          </select>
                          <div
                            class="text-danger small"
                            id="challengeCreatorTypeError"
                          ></div>
                        </div>
                        <div class="col-md-4">
                          <label class="form-label" for="challengeStartDate"
                            >Date de début *</label
                          >
                          <input
                            type="date"
                            class="form-control"
                            id="challengeStartDate"
                          />
                          <div
                            class="text-danger small"
                            id="challengeStartDateError"
                          ></div>
                        </div>
                        <div class="col-md-4">
                          <label class="form-label" for="challengeEndDate"
                            >Date de fin *</label
                          >
                          <input
                            type="date"
                            class="form-control"
                            id="challengeEndDate"
                          />
                          <div
                            class="text-danger small"
                            id="challengeEndDateError"
                          ></div>
                        </div>
                      </div>
                      <div class="row g-3 mt-3">
                        <div class="col-md-4">
                          <label class="form-label" for="challengeStatus"
                            >Statut *</label
                          >
                          <select class="form-control" id="challengeStatus">
                            <option value="upcoming">upcoming</option>
                            <option value="active">active</option>
                          </select>
                          <div
                            class="text-danger small"
                            id="challengeStatusError"
                          ></div>
                          <div class="small text-muted mt-1">
                            Le statut est géré automatiquement selon les upvotes du post sélectionné.
                          </div>
                        </div>
                        <div class="col-md-4">
                          <label class="form-label" for="challengeRewardType"
                            >Type de récompense *</label
                          >
                          <select class="form-control" id="challengeRewardType">
                            <option value="">Sélectionner un type de récompense</option>
                            <option value="job">job</option>
                            <option value="course">course</option>
                          </select>
                          <div
                            class="text-danger small"
                            id="challengeRewardTypeError"
                          ></div>
                        </div>
                      </div>
                      <div class="mb-3 mt-3">
                        <label class="form-label" for="challengeRewardTitle"
                          >Titre de la récompense *</label
                        >
                        <input
                          type="text"
                          class="form-control"
                          id="challengeRewardTitle"
                        />
                        <div
                          class="text-danger small"
                          id="challengeRewardTitleError"
                        ></div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="challengeRewardDescription"
                          >Description de la récompense *</label
                        >
                        <textarea
                          class="form-control"
                          id="challengeRewardDescription"
                          rows="3"
                        ></textarea>
                        <div
                          class="text-danger small"
                          id="challengeRewardDescriptionError"
                        ></div>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button
                      type="button"
                      class="btn btn-secondary"
                      data-bs-dismiss="modal"
                    >
                      Fermer
                    </button>
                    <button
                      type="button"
                      class="btn btn-primary"
                      id="saveChallengeBtn"
                    >
                      Enregistrer le défi
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="commentModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="commentModalTitle">Ajouter un commentaire</h5>
                    <button
                      type="button"
                      class="btn-close"
                      data-bs-dismiss="modal"
                      aria-label="Fermer"
                    ></button>
                  </div>
                  <div class="modal-body">
                    <form id="commentForm" novalidate>
                      <input type="hidden" id="commentId" />
                      <div class="mb-3">
                        <label class="form-label" for="commentChallengeId">Défi *</label>
                        <select class="form-control" id="commentChallengeId"></select>
                        <div class="text-danger small" id="commentChallengeIdError"></div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="commentBody">Commentaire *</label>
                        <textarea class="form-control" id="commentBody" rows="4"></textarea>
                        <div class="text-danger small" id="commentBodyError"></div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="commentImageUrl">URL de l'image</label>
                        <input type="text" class="form-control" id="commentImageUrl" placeholder="https://..." />
                        <div class="text-danger small" id="commentImageUrlError"></div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="commentVideoUrl">URL de la vidéo</label>
                        <input type="text" class="form-control" id="commentVideoUrl" placeholder="https://..." />
                        <div class="text-danger small" id="commentVideoUrlError"></div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="commentUpvoteCount">Upvotes</label>
                        <input type="number" class="form-control" id="commentUpvoteCount" min="0" step="1" value="0" />
                        <div class="text-danger small" id="commentUpvoteCountError"></div>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button
                      type="button"
                      class="btn btn-secondary"
                      data-bs-dismiss="modal"
                    >
                      Fermer
                    </button>
                    <button
                      type="button"
                      class="btn btn-primary"
                      id="saveCommentBtn"
                    >
                      Enregistrer le commentaire
                    </button>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Notification Modal -->
            <div class="modal fade" id="notificationModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Envoyer une notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label class="form-label" for="notifyType">Type de notification *</label>
                      <select id="notifyType" class="form-control">
                        <option value="email">Email</option>
                        <option value="sms">SMS (Téléphone)</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label class="form-label" for="notifyContact">Email ou Numéro de téléphone *</label>
                      <input type="text" id="notifyContact" class="form-control" placeholder="exemple@mail.com ou +33612345678">
                      <div class="text-danger small" id="notifyContactError"></div>
                    </div>
                    <div class="mb-3">
                      <label class="form-label" for="notifyMessage">Message personnalisé (optionnel)</label>
                      <textarea id="notifyMessage" class="form-control" rows="3" placeholder="Votre message..."></textarea>
                    </div>
                    <div class="alert alert-info small">
                      <strong>Info:</strong> La notification inclura le contenu du commentaire sélectionné.
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="sendNotificationBtn">Envoyer</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="http://www.themekita.com">ThemeKita</a>
                </li>
              </ul>
            </nav>
            <div class="copyright">
              Module blog — Career Lab
            </div>
          </div>
        </footer>
      </div>

      <div class="custom-template">
        <div class="title">Paramètres</div>
        <div class="custom-content">
          <div class="switcher">
            <div class="switch-block">
              <h4>En-tête du logo</h4>
              <div class="btnSwitch">
                <button type="button" class="selected changeLogoHeaderColor" data-color="dark"></button>
                <button type="button" class="selected changeLogoHeaderColor" data-color="blue"></button>
                <button type="button" class="changeLogoHeaderColor" data-color="purple"></button>
                <button type="button" class="changeLogoHeaderColor" data-color="light-blue"></button>
                <button type="button" class="changeLogoHeaderColor" data-color="green"></button>
                <button type="button" class="changeLogoHeaderColor" data-color="orange"></button>
                <button type="button" class="changeLogoHeaderColor" data-color="red"></button>
                <button type="button" class="changeLogoHeaderColor" data-color="white"></button>
                <br />
                <button type="button" class="changeLogoHeaderColor" data-color="dark2"></button>
                <button type="button" class="changeLogoHeaderColor" data-color="blue2"></button>
                <button type="button" class="changeLogoHeaderColor" data-color="purple2"></button>
                <button type="button" class="changeLogoHeaderColor" data-color="light-blue2"></button>
                <button type="button" class="changeLogoHeaderColor" data-color="green2"></button>
                <button type="button" class="changeLogoHeaderColor" data-color="orange2"></button>
                <button type="button" class="changeLogoHeaderColor" data-color="red2"></button>
              </div>
            </div>
            <div class="switch-block">
              <h4>En-tête de la barre de navigation</h4>
              <div class="btnSwitch">
                <button type="button" class="changeTopBarColor" data-color="dark"></button>
                <button type="button" class="changeTopBarColor" data-color="blue"></button>
                <button type="button" class="changeTopBarColor" data-color="purple"></button>
                <button type="button" class="changeTopBarColor" data-color="light-blue"></button>
                <button type="button" class="changeTopBarColor" data-color="green"></button>
                <button type="button" class="changeTopBarColor" data-color="orange"></button>
                <button type="button" class="changeTopBarColor" data-color="red"></button>
                <button type="button" class="changeTopBarColor" data-color="white"></button>
                <br />
                <button type="button" class="changeTopBarColor" data-color="dark2"></button>
                <button type="button" class="selected changeTopBarColor" data-color="blue2"></button>
                <button type="button" class="changeTopBarColor" data-color="purple2"></button>
                <button type="button" class="changeTopBarColor" data-color="light-blue2"></button>
                <button type="button" class="changeTopBarColor" data-color="green2"></button>
                <button type="button" class="changeTopBarColor" data-color="orange2"></button>
                <button type="button" class="changeTopBarColor" data-color="red2"></button>
              </div>
            </div>
            <div class="switch-block">
              <h4>Barre latérale</h4>
              <div class="btnSwitch">
                <button type="button" class="selected changeSideBarColor" data-color="white"></button>
                <button type="button" class="changeSideBarColor" data-color="dark"></button>
                <button type="button" class="changeSideBarColor" data-color="dark2"></button>
              </div>
            </div>
          </div>
        </div>
        <div class="custom-toggle">
          <i class="icon-settings"></i>
        </div>
      </div>
    </div>

    <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="../assets/js/plugin/datatables/datatables.min.js"></script>
    <script src="../assets/js/plugin/chart.js/chart.min.js"></script>
    <script src="../assets/js/careerlab-admin-charts.js"></script>
    <script src="../assets/js/kaiadmin.min.js"></script>
    <script src="../assets/js/setting-demo2.js"></script>
    <script>
      (function () {
        let challenges = [];
        let challengeModal;
        let comments = [];
        let commentsTable;
        let commentModal;
        let notificationModal;
        let currentCommentForNotification = null;

        const challengeKey = "blogChallenges";
        const commentKey = "challengeComments";

        const CREATOR_TYPES = ["Formateur", "Entreprise"];
        const REWARD_TYPES = ["job", "course"];
        const CHALLENGE_FLAIR = ["Projet", "Showcast", "Débat", "Pitch"];

        function charCount(s) {
          return String(s || "").trim().length;
        }

        function wordCount(s) {
          return String(s || "")
            .trim()
            .split(/\s+/)
            .filter(Boolean).length;
        }

        function loadPostsRaw() {
          try {
            const raw = localStorage.getItem("blogPosts");
            return raw ? JSON.parse(raw) : [];
          } catch (e) {
            return [];
          }
        }

        function normalizeMinUpvotesForChallenge(p) {
          const raw = p && p.minUpvotesForChallenge;
          const n = Math.floor(Number(raw));
          return Number.isFinite(n) && n > 0 ? n : 0;
        }

        function computeAutoStatusFromPost(post) {
          const up = post ? Number(post.upvoteCount) || 0 : 0;
          const req = post ? normalizeMinUpvotesForChallenge(post) : 0;
          return up >= req ? "active" : "upcoming";
        }

        function syncStatusFieldFromSelectedPost() {
          const sel = document.getElementById("challengePostId");
          const statusEl = document.getElementById("challengeStatus");
          if (!sel || !statusEl) return;
          const posts = loadPostsRaw();
          const p = posts.find(function (x) {
            return String(x.id) === String(sel.value || "");
          });
          statusEl.value = computeAutoStatusFromPost(p);
          statusEl.disabled = true;
        }

        function renderPostOptions() {
          const sel = document.getElementById("challengePostId");
          if (!sel) return;
          const posts = loadPostsRaw();
          posts.sort(function (a, b) {
            return String(b.createdAt || "").localeCompare(String(a.createdAt || ""));
          });
          sel.innerHTML =
            '<option value="">Sélectionner un post</option>' +
            posts
              .map(function (p) {
                const id = String(p.id);
                const up = Number(p.upvoteCount) || 0;
                const req = normalizeMinUpvotesForChallenge(p);
                const title = String(p.title || "").slice(0, 80);
                const eligible = up >= req;
                return (
                  '<option value="' +
                  escapeHtml(id) +
                  '">#' +
                  escapeHtml(id) +
                  " — " +
                  escapeHtml(title) +
                  " (upvotes: " +
                  up +
                  (req ? ", min eligible: " + req : "") +
                  (eligible ? "" : " — upcoming until eligible") +
                  ")</option>"
                );
              })
              .join("");
          syncStatusFieldFromSelectedPost();
        }

        const defaultChallenges = [
          {
            id: 1,
            theme: "This challenge theme uses exactly ten words for validation rules here today",
            description:
              "This challenge description has at least twenty words so validation passes for the Career Lab module and the challenge admin form requirements today.",
            flair: "Projet",
            creatorType: "Formateur",
            startDate: "2026-05-01",
            endDate: "2026-05-31",
            status: "active",
            rewardType: "job",
            rewardTitle: "This reward title has ten words exactly for validation here today",
            rewardDescription:
              "This reward description text contains twenty words minimum so it passes validation for the challenge reward field in the Career Lab application today.",
            postId: "",
          },
        ];

        function stripWinnerId(items) {
          return items.map(function (c) {
            const copy = Object.assign({}, c);
            delete copy.winnerId;
            return copy;
          });
        }

        function todayISODate() {
          return new Date().toISOString().split("T")[0];
        }

        function loadData() {
          const stored = localStorage.getItem(challengeKey);
          challenges = stored
            ? stripWinnerId(JSON.parse(stored))
            : defaultChallenges.slice();
          challenges = challenges.map(function (c) {
            const x = Object.assign({}, c);
            if (x.postId == null) x.postId = "";
            return x;
          });
          localStorage.setItem(challengeKey, JSON.stringify(challenges));
        }

        function saveData() {
          localStorage.setItem(challengeKey, JSON.stringify(challenges));
        }

        function loadComments() {
          const stored = localStorage.getItem(commentKey);
          comments = stored ? JSON.parse(stored) : [];
          comments = (Array.isArray(comments) ? comments : []).map(function (c) {
            const x = Object.assign({}, c);
            x.challengeId = String(x.challengeId || "");
            x.body = String(x.body || "");
            x.imageUrl = String(x.imageUrl || "");
            x.videoUrl = String(x.videoUrl || "");
            x.createdAt = String(x.createdAt || todayISODate());
            x.upvoteCount = Math.max(0, Number(x.upvoteCount) || 0);
            if (!Array.isArray(x.upvotedBy)) x.upvotedBy = [];
            return x;
          });
          localStorage.setItem(commentKey, JSON.stringify(comments));
        }

        function saveComments() {
          localStorage.setItem(commentKey, JSON.stringify(comments));
        }

        function isValidUrl(value) {
          if (!value || !String(value).trim()) return true;
          try {
            new URL(String(value).trim());
            return true;
          } catch (e) {
            return false;
          }
        }

        function updatePieCharts() {
          if (typeof CareerLabCharts === "undefined") return;
          const flairLabels = ["Projet", "Showcast", "Débat", "Pitch"];
          CareerLabCharts.renderPie(
            "challengesFlairPieChart",
            flairLabels,
            flairLabels.map((f) =>
              challenges.filter((c) => c.flair === f).length
            ),
            ["#177dff", "#6861ce", "#48abf7", "#f3545d"]
          );
          CareerLabCharts.renderPie(
            "challengesStatusPieChart",
            ["upcoming", "active", "closed"],
            [
              challenges.filter((c) => c.status === "upcoming").length,
              challenges.filter((c) => c.status === "active").length,
              challenges.filter((c) => c.status === "closed").length,
            ],
            ["#fdaf4b", "#31ce36", "#6c757d"]
          );
        }

        function updateStatistics() {
          document.getElementById("totalChallenges").textContent =
            challenges.length;
          document.getElementById("activeChallenges").textContent =
            challenges.filter((c) => c.status === "active").length;
          document.getElementById("totalChallengeComments").textContent =
            comments.length;
          document.getElementById("totalCommentUpvotes").textContent =
            comments.reduce(function (sum, c) {
              return sum + (Number(c.upvoteCount) || 0);
            }, 0);
          updatePieCharts();
        }

        function escapeHtml(s) {
          const d = document.createElement("div");
          d.textContent = s == null ? "" : String(s);
          return d.innerHTML;
        }

        function renderChallengesTable() {
          const body = document.getElementById("challengesTableBody");
          body.innerHTML = "";
          challenges.forEach((challenge) => {
            const row = document.createElement("tr");
            row.innerHTML =
              "<td>" +
              escapeHtml(challenge.theme) +
              "</td>" +
              "<td>" +
              escapeHtml(challenge.flair || "") +
              "</td>" +
              "<td>" +
              escapeHtml(challenge.creatorType) +
              "</td>" +
              "<td>" +
              escapeHtml(challenge.startDate) +
              "</td>" +
              "<td>" +
              escapeHtml(challenge.endDate) +
              "</td>" +
              "<td>" +
              escapeHtml(challenge.status) +
              "</td>" +
              "<td>" +
              '<button type="button" class="btn btn-sm btn-warning me-1 btn-edit-challenge" data-id="' +
              challenge.id +
              '">Modifier</button> ' +
              '<button type="button" class="btn btn-sm btn-danger btn-del-challenge" data-id="' +
              challenge.id +
              '">Supprimer</button>' +
              "</td>";
            body.appendChild(row);
          });

          if ($.fn.DataTable.isDataTable("#challengesTable")) {
            $("#challengesTable").DataTable().destroy();
          }
          $("#challengesTable").DataTable({
            paging: true,
            pageLength: 10,
            lengthChange: false,
            dom: "rtip",
            ordering: true,
            columnDefs: [{ orderable: false, targets: 6 }],
          });
        }

        function challengeThemeById(challengeId) {
          const ch = challenges.find(function (x) {
            return String(x.id) === String(challengeId);
          });
          return ch ? String(ch.theme || "") : "(Défi supprimé)";
        }

        function commentMediaLabel(c) {
          const parts = [];
          if (c.imageUrl && String(c.imageUrl).trim()) parts.push("Image");
          if (c.videoUrl && String(c.videoUrl).trim()) parts.push("Vidéo");
          return parts.length ? parts.join(" + ") : "Aucun";
        }

        function renderCommentChallengeOptions() {
          const sel = document.getElementById("commentChallengeId");
          const filter = document.getElementById("commentChallengeFilter");
          const opts =
            '<option value="">Tous les défis</option>' +
            challenges
              .map(function (ch) {
                return (
                  '<option value="' +
                  escapeHtml(String(ch.id)) +
                  '">#' +
                  escapeHtml(String(ch.id)) +
                  " - " +
                  escapeHtml(String(ch.theme || "").slice(0, 80)) +
                  "</option>"
                );
              })
              .join("");
          if (filter) {
            const prev = filter.value;
            filter.innerHTML = opts;
            filter.value = prev || "";
          }
          if (sel) {
            const prevSel = sel.value;
            sel.innerHTML =
              '<option value="">Sélectionner un défi</option>' +
              opts.replace('<option value="">Tous les défis</option>', "");
            sel.value = prevSel || "";
          }
        }

        function renderCommentsTable() {
          const body = document.getElementById("commentsTableBody");
          if (!body) return;
          const challengeFilter = document.getElementById("commentChallengeFilter");
          const activeChallenge = challengeFilter ? String(challengeFilter.value || "") : "";
          const shown = comments
            .filter(function (c) {
              return !activeChallenge || String(c.challengeId) === activeChallenge;
            })
            .sort(function (a, b) {
              return String(b.createdAt || "").localeCompare(String(a.createdAt || ""));
            });
          body.innerHTML = "";
          shown.forEach(function (c) {
            const row = document.createElement("tr");
            row.innerHTML =
              "<td>" +
              escapeHtml(challengeThemeById(c.challengeId)) +
              "</td>" +
              "<td>" +
              escapeHtml(String(c.body || "").slice(0, 110)) +
              (String(c.body || "").length > 110 ? "..." : "") +
              "</td>" +
              "<td>" +
              escapeHtml(commentMediaLabel(c)) +
              "</td>" +
              "<td>" +
              escapeHtml(c.createdAt) +
              "</td>" +
              "<td>" +
              escapeHtml(String(c.upvoteCount)) +
              "</td>" +
              "<td>" +
              '<button type="button" class="btn btn-sm btn-warning me-1 btn-edit-comment" data-id="' +
              escapeHtml(String(c.id)) +
              '">Modifier</button> ' +
              '<button type="button" class="btn btn-sm btn-info me-1 btn-notify-comment" data-id="' +
              escapeHtml(String(c.id)) +
              '" title="Envoyer notification">Notifier</button> ' +
              '<button type="button" class="btn btn-sm btn-danger btn-del-comment" data-id="' +
              escapeHtml(String(c.id)) +
              '">Supprimer</button>' +
              "</td>";
            body.appendChild(row);
          });

          if ($.fn.DataTable.isDataTable("#commentsTable")) {
            $("#commentsTable").DataTable().destroy();
          }
          commentsTable = $("#commentsTable").DataTable({
            paging: true,
            pageLength: 10,
            lengthChange: false,
            dom: "rtip",
            ordering: true,
            columnDefs: [{ orderable: false, targets: 5 }],
          });
        }

        function clearCommentErrors() {
          document
            .querySelectorAll("#commentForm .text-danger")
            .forEach(function (el) {
              el.textContent = "";
            });
        }

        function validateComment() {
          clearCommentErrors();
          let valid = true;
          const challengeId = document.getElementById("commentChallengeId").value;
          const body = document.getElementById("commentBody").value.trim();
          const img = document.getElementById("commentImageUrl").value.trim();
          const vid = document.getElementById("commentVideoUrl").value.trim();
          const upvotesRaw = document.getElementById("commentUpvoteCount").value;
          const up = Number(upvotesRaw);
          if (!challengeId) {
            document.getElementById("commentChallengeIdError").textContent =
              "Sélectionnez un défi.";
            valid = false;
          }
          const wc = wordCount(body);
          if (wc < 10 || wc > 500) {
            document.getElementById("commentBodyError").textContent =
              "Le commentaire doit contenir entre 10 et 500 mots.";
            valid = false;
          }
          if (img && !isValidUrl(img)) {
            document.getElementById("commentImageUrlError").textContent =
              "L'URL de l'image est invalide.";
            valid = false;
          }
          if (vid && !isValidUrl(vid)) {
            document.getElementById("commentVideoUrlError").textContent =
              "L'URL de la vidéo est invalide.";
            valid = false;
          }
          if (!Number.isFinite(up) || up < 0) {
            document.getElementById("commentUpvoteCountError").textContent =
              "Les upvotes doivent être >= 0.";
            valid = false;
          }
          return valid;
        }

        function openCommentModal(id) {
          document.getElementById("commentModalTitle").textContent = id
            ? "Modifier le commentaire"
            : "Ajouter un commentaire";
          document.getElementById("commentId").value = id || "";
          renderCommentChallengeOptions();
          if (id) {
            const c = comments.find(function (x) {
              return String(x.id) === String(id);
            });
            if (c) {
              document.getElementById("commentChallengeId").value = String(c.challengeId || "");
              document.getElementById("commentBody").value = c.body || "";
              document.getElementById("commentImageUrl").value = c.imageUrl || "";
              document.getElementById("commentVideoUrl").value = c.videoUrl || "";
              document.getElementById("commentUpvoteCount").value = String(c.upvoteCount || 0);
            }
          } else {
            document.getElementById("commentForm").reset();
            document.getElementById("commentUpvoteCount").value = "0";
            document.getElementById("commentChallengeId").value = "";
          }
          clearCommentErrors();
          commentModal.show();
        }

        function saveComment() {
          if (!validateComment()) return;
          const idValue = document.getElementById("commentId").value;
          const challengeId = String(document.getElementById("commentChallengeId").value);
          const body = document.getElementById("commentBody").value.trim();
          const imageUrl = document.getElementById("commentImageUrl").value.trim();
          const videoUrl = document.getElementById("commentVideoUrl").value.trim();

          // Save to database via API
          fetch("../index.php?c=challenge&a=saveCommentBackoffice", {
            method: "POST",
            headers: {
              "Content-Type": "application/json"
            },
            body: JSON.stringify({
              challenge_id: parseInt(challengeId),
              body: body,
              image_url: imageUrl,
              video_url: videoUrl
            })
          })
          .then(function (res) { return res.json(); })
          .then(function (data) {
            if (data.ok) {
              const c = {
                id: data.item ? data.item.id : ("c_" + Date.now() + "_" + Math.random().toString(36).slice(2, 9)),
                challengeId: challengeId,
                body: body,
                imageUrl: imageUrl,
                videoUrl: videoUrl,
                createdAt: idValue
                  ? (comments.find(function (x) { return String(x.id) === String(idValue); }) || {}).createdAt || todayISODate()
                  : todayISODate(),
                upvoteCount: 0,
                upvotedBy: []
              };
              if (idValue) {
                comments = comments.map(function (x) {
                  return String(x.id) === String(idValue) ? c : x;
                });
              } else {
                comments.push(c);
              }
              saveComments();
              updateStatistics();
              renderCommentsTable();
              commentModal.hide();
              alert("Commentaire enregistré avec succès!");
            } else {
              alert("Erreur: " + (data.message || "Impossible d'enregistrer le commentaire"));
            }
          })
          .catch(function (err) {
            alert("Erreur réseau: " + err);
          });
        }

        function deleteComment(id) {
          if (!window.confirm("Supprimer ce commentaire ? Cette action est irréversible.")) return;
          comments = comments.filter(function (c) {
            return String(c.id) !== String(id);
          });
          saveComments();
          updateStatistics();
          renderCommentsTable();
        }

        function openNotificationModal(commentId) {
          const comment = comments.find(function (c) {
            return String(c.id) === String(commentId);
          });
          if (!comment) {
            alert("Commentaire introuvable.");
            return;
          }
          currentCommentForNotification = comment;
          document.getElementById("notifyContact").value = "";
          document.getElementById("notifyMessage").value = "";
          document.getElementById("notifyType").value = "email";
          document.getElementById("notifyContactError").textContent = "";
          if (!notificationModal) {
            notificationModal = new (window.bootstrap.Modal || bootstrap.Modal)(document.getElementById("notificationModal"));
          }
          notificationModal.show();
        }

        function sendNotification() {
          if (!currentCommentForNotification) {
            alert("Aucun commentaire sélectionné.");
            return;
          }
          const contactField = document.getElementById("notifyContact");
          const contact = contactField.value.trim();
          const type = document.getElementById("notifyType").value;
          const customMessage = document.getElementById("notifyMessage").value.trim();
          
          if (!contact) {
            document.getElementById("notifyContactError").textContent = "Veuillez entrer une adresse email ou un numéro de téléphone.";
            return;
          }
          
          if (type === "email" && !contact.includes("@")) {
            document.getElementById("notifyContactError").textContent = "Veuillez entrer une adresse email valide.";
            return;
          }
          
          if (type === "sms" && !contact.match(/^\+?\d{8,}/)) {
            document.getElementById("notifyContactError").textContent = "Veuillez entrer un numéro de téléphone valide.";
            return;
          }
          
          // Send via API
          fetch("../index.php?c=challenge&a=sendNotification", {
            method: "POST",
            headers: {
              "Content-Type": "application/json"
            },
            body: JSON.stringify({
              type: type,
              contact: contact,
              custom_message: customMessage,
              comment_id: currentCommentForNotification.id
            })
          })
          .then(function (res) { return res.json(); })
          .then(function (data) {
            if (data.ok) {
              alert("Notification " + (type === "email" ? "par email" : "par SMS") + " envoyée avec succès à " + contact + "!");
              if (notificationModal) {
                notificationModal.hide();
              }
              currentCommentForNotification = null;
            } else {
              alert("Erreur: " + (data.message || "Impossible d'envoyer la notification"));
            }
          })
          .catch(function (err) {
            alert("Erreur réseau: " + err);
          });
        }

        function clearChallengeErrors() {
          document
            .querySelectorAll("#challengeForm .text-danger")
            .forEach(function (el) {
              el.textContent = "";
            });
        }

        function validateChallenge() {
          clearChallengeErrors();
          let valid = true;
          const idVal = document.getElementById("challengeId").value;
          const original = idVal
            ? challenges.find((c) => c.id === Number(idVal))
            : null;

          const theme = document.getElementById("challengeTheme").value.trim();
          const themeC = charCount(theme);
          if (themeC < 10 || themeC > 200) {
            document.getElementById("challengeThemeError").textContent =
              "Le thème doit contenir entre 10 et 200 caractères.";
            valid = false;
          }
          const description = document
            .getElementById("challengeDescription")
            .value.trim();
          const descC = charCount(description);
          if (descC < 10 || descC > 5000) {
            document.getElementById("challengeDescriptionError").textContent =
              "La description doit contenir entre 10 et 5000 caractères.";
            valid = false;
          }
          const flairVal = document.getElementById("challengeFlair").value;
          if (!flairVal || !CHALLENGE_FLAIR.includes(flairVal)) {
            document.getElementById("challengeFlairError").textContent =
              "Le flair est obligatoire (Projet, Showcast, Débat ou Pitch).";
            valid = false;
          }
          const creatorType = document.getElementById("challengeCreatorType").value;
          if (!creatorType || !CREATOR_TYPES.includes(creatorType)) {
            document.getElementById("challengeCreatorTypeError").textContent =
              "Le type de créateur doit être Formateur ou Entreprise.";
            valid = false;
          }

          const startDateValue = document.getElementById("challengeStartDate").value;
          const today = todayISODate();
          if (!startDateValue) {
            document.getElementById("challengeStartDateError").textContent =
              "La date de début est obligatoire.";
            valid = false;
          } else if (startDateValue < today) {
            if (!original) {
              document.getElementById("challengeStartDateError").textContent =
                "La date de début ne peut pas être dans le passé.";
              valid = false;
            } else if (original.startDate !== startDateValue) {
              document.getElementById("challengeStartDateError").textContent =
                "La date de début ne peut pas être dans le passé.";
              valid = false;
            }
          }

          const endDateValue = document.getElementById("challengeEndDate").value;
          if (!endDateValue) {
            document.getElementById("challengeEndDateError").textContent =
              "La date de fin est obligatoire.";
            valid = false;
          } else if (startDateValue && endDateValue <= startDateValue) {
            document.getElementById("challengeEndDateError").textContent =
              "La date de fin doit être après la date de début.";
            valid = false;
          }

          if (!document.getElementById("challengeStatus").value) {
            document.getElementById("challengeStatusError").textContent =
              "Le statut est obligatoire.";
            valid = false;
          }

          const rewardType = document.getElementById("challengeRewardType").value;
          if (!rewardType || !REWARD_TYPES.includes(rewardType)) {
            document.getElementById("challengeRewardTypeError").textContent =
              "Le type de récompense doit être job ou course.";
            valid = false;
          }
          const rt = document.getElementById("challengeRewardTitle").value.trim();
          const rtc = charCount(rt);
          if (rtc < 10 || rtc > 200) {
            document.getElementById("challengeRewardTitleError").textContent =
              "Le titre de la récompense doit contenir entre 10 et 200 caractères.";
            valid = false;
          }
          const rd = document
            .getElementById("challengeRewardDescription")
            .value.trim();
          const rdc = charCount(rd);
          if (rdc < 10 || rdc > 5000) {
            document.getElementById("challengeRewardDescriptionError").textContent =
              "La description de la récompense doit contenir entre 10 et 5000 caractères.";
            valid = false;
          }

          const postId = document.getElementById("challengePostId").value;
          if (!postId) {
            document.getElementById("challengePostIdError").textContent =
              "Vous devez sélectionner un post pour ce défi.";
            valid = false;
          } else {
            const posts = loadPostsRaw();
            const p = posts.find(function (x) {
              return String(x.id) === String(postId);
            });
            if (!p) {
              document.getElementById("challengePostIdError").textContent =
                "Le post sélectionné est introuvable.";
              valid = false;
            }
          }
          return valid;
        }

        function openChallengeModal(id) {
          document.getElementById("challengeModalTitle").textContent = id
            ? "Modifier le défi"
            : "Ajouter un défi";
          document.getElementById("challengeId").value = id || "";
          if (id) {
            const challenge = challenges.find((item) => item.id === id);
            document.getElementById("challengeTheme").value = challenge.theme;
            document.getElementById("challengeDescription").value =
              challenge.description;
            document.getElementById("challengeFlair").value =
              challenge.flair || "";
            document.getElementById("challengeCreatorType").value =
              challenge.creatorType;
            document.getElementById("challengeStartDate").value =
              challenge.startDate;
            document.getElementById("challengeEndDate").value =
              challenge.endDate;
            document.getElementById("challengeStatus").value =
              challenge.status;
            document.getElementById("challengeRewardType").value =
              challenge.rewardType;
            document.getElementById("challengeRewardTitle").value =
              challenge.rewardTitle;
            document.getElementById("challengeRewardDescription").value =
              challenge.rewardDescription;
            document.getElementById("challengePostId").value = challenge.postId || "";
          } else {
            document.getElementById("challengeForm").reset();
            document.getElementById("challengePostId").value = "";
          }
          syncStatusFieldFromSelectedPost();
          clearChallengeErrors();
          challengeModal.show();
        }

        function saveChallenge() {
          if (!validateChallenge()) return;
          const idValue = document.getElementById("challengeId").value;
          const postId = document.getElementById("challengePostId").value;
          const posts = loadPostsRaw();
          const p = posts.find(function (x) {
            return String(x.id) === String(postId);
          });
          const autoStatus = computeAutoStatusFromPost(p);
          const challenge = {
            id: idValue ? Number(idValue) : Date.now(),
            theme: document.getElementById("challengeTheme").value.trim(),
            description: document
              .getElementById("challengeDescription")
              .value.trim(),
            flair: document.getElementById("challengeFlair").value,
            creatorType: document.getElementById("challengeCreatorType").value,
            startDate: document.getElementById("challengeStartDate").value,
            endDate: document.getElementById("challengeEndDate").value,
            status: autoStatus,
            rewardType: document.getElementById("challengeRewardType").value,
            rewardTitle: document
              .getElementById("challengeRewardTitle")
              .value.trim(),
            rewardDescription: document
              .getElementById("challengeRewardDescription")
              .value.trim(),
            postId: postId,
          };
          if (idValue) {
            challenges = challenges.map((item) =>
              item.id === Number(idValue) ? challenge : item
            );
          } else {
            challenges.push(challenge);
          }
          saveData();
          renderCommentChallengeOptions();
          updateStatistics();
          renderChallengesTable();
          challengeModal.hide();
        }

        function deleteChallenge(id) {
          if (
            !window.confirm(
              "Supprimer ce défi ? Cette action est irréversible."
            )
          )
            return;
          challenges = challenges.filter((item) => String(item.id) !== String(id));
          comments = comments.filter((c) => String(c.challengeId) !== String(id));
          saveData();
          saveComments();
          renderCommentChallengeOptions();
          updateStatistics();
          renderChallengesTable();
          renderCommentsTable();
        }

        document.addEventListener("DOMContentLoaded", function () {
          challengeModal = new bootstrap.Modal(
            document.getElementById("challengeModal")
          );
          commentModal = new bootstrap.Modal(
            document.getElementById("commentModal")
          );
          notificationModal = new bootstrap.Modal(
            document.getElementById("notificationModal")
          );

          document
            .getElementById("challengeForm")
            .addEventListener("submit", function (e) {
              e.preventDefault();
              saveChallenge();
            });

          loadData();
          loadComments();
          renderCommentChallengeOptions();
          updateStatistics();
          renderChallengesTable();
          renderCommentsTable();
          renderPostOptions();
          const postSel = document.getElementById("challengePostId");
          if (postSel) {
            postSel.addEventListener("change", function () {
              syncStatusFieldFromSelectedPost();
            });
          }

          document
            .getElementById("addChallengeBtn")
            .addEventListener("click", function () {
              renderPostOptions();
              openChallengeModal(null);
            });
          document
            .getElementById("exportChallengesPdfBtn")
            .addEventListener("click", function () {
              window.print();
            });
          document
            .getElementById("saveChallengeBtn")
            .addEventListener("click", saveChallenge);
          document
            .getElementById("addCommentBtn")
            .addEventListener("click", function () {
              openCommentModal(null);
            });
          document
            .getElementById("saveCommentBtn")
            .addEventListener("click", saveComment);
          document
            .getElementById("sendNotificationBtn")
            .addEventListener("click", sendNotification);
          document
            .getElementById("commentForm")
            .addEventListener("submit", function (e) {
              e.preventDefault();
              saveComment();
            });
          document
            .getElementById("commentChallengeFilter")
            .addEventListener("change", renderCommentsTable);

          document
            .getElementById("challengesTableBody")
            .addEventListener("click", function (e) {
              const editBtn = e.target.closest(".btn-edit-challenge");
              const delBtn = e.target.closest(".btn-del-challenge");
              if (editBtn) {
                renderPostOptions();
                openChallengeModal(Number(editBtn.getAttribute("data-id")));
              } else if (delBtn) {
                deleteChallenge(Number(delBtn.getAttribute("data-id")));
              }
            });
          document
            .getElementById("commentsTableBody")
            .addEventListener("click", function (e) {
              const editBtn = e.target.closest(".btn-edit-comment");
              const delBtn = e.target.closest(".btn-del-comment");
              const notifyBtn = e.target.closest(".btn-notify-comment");
              if (editBtn) {
                openCommentModal(editBtn.getAttribute("data-id"));
              } else if (delBtn) {
                deleteComment(delBtn.getAttribute("data-id"));
              } else if (notifyBtn) {
                openNotificationModal(notifyBtn.getAttribute("data-id"));
              }
            });
        });
      })();
    </script>
  </body>
</html>
