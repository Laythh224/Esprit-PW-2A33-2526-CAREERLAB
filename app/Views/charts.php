<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Blog — Posts</title>
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
                  <p>Dashboard</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="dashboard">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="../index.html">
                        <span class="sub-item">Dashboard 1</span>
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
                    <li class="active">
                      <a href="charts.html">
                        <span class="sub-item">blog</span>
                      </a>
                    </li>
                    <li>
                      <a href="challenge.html">
                        <span class="sub-item">challenge</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a href="../indexF.html">
                  <i class="fas fa-external-link-alt"></i>
                  <p>Front</p>
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
                      <span class="op-7">Hi,</span>
                      <span class="fw-bold">Admin</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <li>
                      <a class="dropdown-item" href="../index.html">Dashboard</a>
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
                <h3 class="fw-bold mb-3">Blog — Posts</h3>
                <h6 class="op-7 mb-2">
                  Post statistics and CRUD (local storage)
                </h6>
              </div>
            </div>

            <div class="row mb-4">
              <div class="col-6 col-md-4 col-lg-2 mb-3">
                <div class="card card-stats card-round">
                  <div class="card-body text-center">
                    <p class="card-category mb-1">Total Posts</p>
                    <h4 class="card-title" id="totalPosts">0</h4>
                  </div>
                </div>
              </div>
              <div class="col-6 col-md-4 col-lg-2 mb-3">
                <div class="card card-stats card-round">
                  <div class="card-body text-center">
                    <p class="card-category mb-1">Utilisateur</p>
                    <h4 class="card-title" id="utilisateurPosts">0</h4>
                  </div>
                </div>
              </div>
              <div class="col-6 col-md-4 col-lg-2 mb-3">
                <div class="card card-stats card-round">
                  <div class="card-body text-center">
                    <p class="card-category mb-1">Formateur</p>
                    <h4 class="card-title" id="formateurPosts">0</h4>
                  </div>
                </div>
              </div>
              <div class="col-6 col-md-4 col-lg-2 mb-3">
                <div class="card card-stats card-round">
                  <div class="card-body text-center">
                    <p class="card-category mb-1">Entreprise</p>
                    <h4 class="card-title" id="entreprisePosts">0</h4>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-4 col-lg-2 mb-3">
                <div class="card card-stats card-round">
                  <div class="card-body text-center">
                    <p class="card-category mb-1">Total Upvotes</p>
                    <h4 class="card-title" id="totalUpvotes">0</h4>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mb-4">
              <div class="col-md-6 mb-4">
                <div class="card card-round">
                  <div class="card-header">
                    <h4 class="card-title mb-0">Posts — author type</h4>
                  </div>
                  <div class="card-body">
                    <div style="max-height: 280px; position: relative">
                      <canvas id="postsAuthorPieChart"></canvas>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-4">
                <div class="card card-round">
                  <div class="card-header">
                    <h4 class="card-title mb-0">Posts — status</h4>
                  </div>
                  <div class="card-body">
                    <div style="max-height: 280px; position: relative">
                      <canvas id="postsStatusPieChart"></canvas>
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
                    <h4 class="card-title">Posts</h4>
                    <p class="card-category">
                      Sort, filter, search — 10 rows per page
                    </p>
                  </div>
                  <button type="button" class="btn btn-primary" id="addPostBtn">
                    Add Post
                  </button>
                </div>
                <div class="row g-3 mb-3">
                  <div class="col-md-3">
                    <label class="form-label" for="flairFilter"
                      >Filter by Flair</label
                    >
                    <select id="flairFilter" class="form-control">
                      <option value="">All</option>
                      <option value="Question">Question</option>
                      <option value="Thread">Thread</option>
                      <option value="Disclaimer">Disclaimer</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="statusFilter"
                      >Filter by Status</label
                    >
                    <select id="statusFilter" class="form-control">
                      <option value="">All</option>
                      <option value="active">active</option>
                      <option value="locked">locked</option>
                      <option value="deleted">deleted</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="authorTypeFilter"
                      >Filter by Author Type</label
                    >
                    <select id="authorTypeFilter" class="form-control">
                      <option value="">All</option>
                      <option value="Utilisateur">Utilisateur</option>
                      <option value="Formateur">Formateur</option>
                      <option value="Entreprise">Entreprise</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="titleSearch"
                      >Search by Title</label
                    >
                    <input
                      type="text"
                      id="titleSearch"
                      class="form-control"
                      placeholder="Search title"
                      autocomplete="off"
                    />
                  </div>
                </div>
                <div class="table-responsive">
                  <table
                    id="postsTable"
                    class="table table-striped table-bordered"
                    style="width: 100%"
                  >
                    <thead>
                      <tr>
                        <th>Title</th>
                        <th>Flair</th>
                        <th>Status</th>
                        <th>Upvote Count</th>
                        <th>Author Type</th>
                        <th>Created At</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody id="postsTableBody"></tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="modal fade" id="postModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="postModalTitle">Add Post</h5>
                    <button
                      type="button"
                      class="btn-close"
                      data-bs-dismiss="modal"
                      aria-label="Close"
                    ></button>
                  </div>
                  <div class="modal-body">
                    <form id="postForm" novalidate>
                      <input type="hidden" id="postId" />
                      <div class="mb-3">
                        <label class="form-label" for="postTitle">Title *</label>
                        <input type="text" class="form-control" id="postTitle" />
                        <div class="text-danger small" id="postTitleError"></div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="postBody">Body *</label>
                        <textarea
                          class="form-control"
                          id="postBody"
                          rows="4"
                        ></textarea>
                        <div class="text-danger small" id="postBodyError"></div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="postPhotoUrl"
                          >Photo URL</label
                        >
                        <input
                          type="text"
                          class="form-control"
                          id="postPhotoUrl"
                          placeholder="https://..."
                        />
                        <div class="text-danger small" id="postPhotoUrlError"></div>
                      </div>
                      <div class="row g-3">
                        <div class="col-md-4">
                          <label class="form-label" for="postFlair">Flair *</label>
                          <select class="form-control" id="postFlair">
                            <option value="">Select Flair</option>
                            <option value="Question">Question</option>
                            <option value="Thread">Thread</option>
                            <option value="Disclaimer">Disclaimer</option>
                          </select>
                          <div class="text-danger small" id="postFlairError"></div>
                        </div>
                        <div class="col-md-4">
                          <label class="form-label" for="postStatus"
                            >Status *</label
                          >
                          <select class="form-control" id="postStatus">
                            <option value="">Select Status</option>
                            <option value="active">active</option>
                            <option value="locked">locked</option>
                            <option value="deleted">deleted</option>
                          </select>
                          <div class="text-danger small" id="postStatusError"></div>
                        </div>
                        <div class="col-md-4">
                          <label class="form-label" for="postAuthorType"
                            >Author Type *</label
                          >
                          <select class="form-control" id="postAuthorType">
                            <option value="">Select Author Type</option>
                            <option value="Utilisateur">Utilisateur</option>
                            <option value="Formateur">Formateur</option>
                            <option value="Entreprise">Entreprise</option>
                          </select>
                          <div
                            class="text-danger small"
                            id="postAuthorTypeError"
                          ></div>
                        </div>
                      </div>
                      <div
                        class="mt-3 border rounded p-3 bg-light"
                        id="postMinUpvotesForChallengeWrap"
                        style="display: none"
                      >
                        <label class="form-label" for="postMinUpvotesForChallenge"
                          >Minimum upvotes (post) to be eligible for Challenge selection *</label
                        >
                        <input
                          type="number"
                          class="form-control"
                          id="postMinUpvotesForChallenge"
                          min="0"
                          step="1"
                          value="0"
                        />
                        <div class="text-danger small" id="postMinUpvotesForChallengeError"></div>
                        <p class="small text-muted mb-0 mt-2">
                          This field is only used for posts authored by <b>Formateur</b> or <b>Entreprise</b>.
                          Challenges can only be attached to eligible posts.
                        </p>
                      </div>
                      <div class="mb-3 mt-3 border rounded p-3 bg-light">
                        <label class="form-label">Voting poll *</label>
                        <p class="small text-muted mb-2">Between 2 and 5 options (leave unused rows empty).</p>
                        <input type="text" class="form-control mb-2" id="pollOpt0" placeholder="Option 1" />
                        <input type="text" class="form-control mb-2" id="pollOpt1" placeholder="Option 2" />
                        <input type="text" class="form-control mb-2" id="pollOpt2" placeholder="Option 3 (optional)" />
                        <input type="text" class="form-control mb-2" id="pollOpt3" placeholder="Option 4 (optional)" />
                        <input type="text" class="form-control" id="pollOpt4" placeholder="Option 5 (optional)" />
                        <div class="text-danger small" id="pollOptionsError"></div>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button
                      type="button"
                      class="btn btn-secondary"
                      data-bs-dismiss="modal"
                    >
                      Close
                    </button>
                    <button type="button" class="btn btn-primary" id="savePostBtn">
                      Save Post
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="http://www.themekita.com">ThemeKita</a>
                </li>
              </ul>
            </nav>
            <div class="copyright">
              Blog module — Career Lab
            </div>
          </div>
        </footer>
      </div>

      <div class="custom-template">
        <div class="title">Settings</div>
        <div class="custom-content">
          <div class="switcher">
            <div class="switch-block">
              <h4>Logo Header</h4>
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
              <h4>Navbar Header</h4>
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
              <h4>Sidebar</h4>
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
        let posts = [];
        let postsTable;
        let postModal;

        const postKey = "blogPosts";

        const FLAIR_VALUES = ["Question", "Thread", "Disclaimer"];

        function charCount(s) {
          return String(s || "").trim().length;
        }

        function collectPollOptions() {
          const opts = [];
          for (let i = 0; i < 5; i += 1) {
            const el = document.getElementById("pollOpt" + i);
            if (el && el.value.trim()) opts.push(el.value.trim());
          }
          return opts;
        }

        const defaultPosts = [
          {
            id: 1,
            title: "This sample post title has exactly ten words in it now",
            body: "This is the body text of the sample post number one and it must contain at least twenty words for validation to pass successfully here today.",
            photoUrl: "",
            createdAt: "2025-01-01",
            status: "active",
            flair: "Question",
            upvoteCount: 12,
            upvotedBy: [],
            authorType: "Utilisateur",
            pollOptions: ["Yes", "No"],
            pollVotes: [0, 0],
          },
          {
            id: 2,
            title: "Second example post title still has ten words total for rules",
            body: "Another sample post body that meets the minimum twenty word requirement for the body field and validates correctly for the Career Lab blog module today.",
            photoUrl: "",
            createdAt: "2025-01-05",
            status: "active",
            flair: "Thread",
            upvoteCount: 6,
            upvotedBy: [],
            authorType: "Formateur",
            pollOptions: ["Option A", "Option B", "Option C"],
            pollVotes: [0, 0, 0],
          },
        ];

        function isValidUrl(value) {
          if (!value || !String(value).trim()) return true;
          try {
            new URL(String(value).trim());
            return true;
          } catch (e) {
            return false;
          }
        }

        function todayISODate() {
          return new Date().toISOString().split("T")[0];
        }

        function loadData() {
          const storedPosts = localStorage.getItem(postKey);
          posts = storedPosts ? JSON.parse(storedPosts) : defaultPosts.slice();
          posts = posts.map(function (p) {
            const copy = Object.assign({}, p);
            if (copy.minUpvotesForChallenge == null || Number.isNaN(Number(copy.minUpvotesForChallenge))) {
              copy.minUpvotesForChallenge = 0;
            } else {
              copy.minUpvotesForChallenge = Math.max(0, Math.floor(Number(copy.minUpvotesForChallenge)));
            }
            return copy;
          });
          localStorage.setItem(postKey, JSON.stringify(posts));
        }

        function saveData() {
          localStorage.setItem(postKey, JSON.stringify(posts));
        }

        function updatePieCharts() {
          if (typeof CareerLabCharts === "undefined") return;
          CareerLabCharts.renderPie(
            "postsAuthorPieChart",
            ["Utilisateur", "Formateur", "Entreprise"],
            [
              posts.filter((p) => p.authorType === "Utilisateur").length,
              posts.filter((p) => p.authorType === "Formateur").length,
              posts.filter((p) => p.authorType === "Entreprise").length,
            ],
            ["#177dff", "#f3545d", "#ffa534"]
          );
          CareerLabCharts.renderPie(
            "postsStatusPieChart",
            ["active", "locked", "deleted"],
            [
              posts.filter((p) => p.status === "active").length,
              posts.filter((p) => p.status === "locked").length,
              posts.filter((p) => p.status === "deleted").length,
            ],
            ["#31ce36", "#fdaf4b", "#6c757d"]
          );
        }

        function updateStatistics() {
          document.getElementById("totalPosts").textContent = posts.length;
          document.getElementById("utilisateurPosts").textContent =
            posts.filter((p) => p.authorType === "Utilisateur").length;
          document.getElementById("formateurPosts").textContent =
            posts.filter((p) => p.authorType === "Formateur").length;
          document.getElementById("entreprisePosts").textContent =
            posts.filter((p) => p.authorType === "Entreprise").length;
          document.getElementById("totalUpvotes").textContent = posts.reduce(
            (sum, p) => sum + (Number(p.upvoteCount) || 0),
            0
          );
          updatePieCharts();
        }

        function escapeHtml(s) {
          const d = document.createElement("div");
          d.textContent = s == null ? "" : String(s);
          return d.innerHTML;
        }

        function renderPostsTable() {
          const body = document.getElementById("postsTableBody");
          body.innerHTML = "";
          posts.forEach((post) => {
            const row = document.createElement("tr");
            row.innerHTML =
              "<td>" +
              escapeHtml(post.title) +
              "</td>" +
              "<td>" +
              escapeHtml(post.flair) +
              "</td>" +
              "<td>" +
              escapeHtml(post.status) +
              "</td>" +
              "<td data-order=\"" +
              (Number(post.upvoteCount) || 0) +
              "\">" +
              escapeHtml(post.upvoteCount) +
              "</td>" +
              "<td>" +
              escapeHtml(post.authorType) +
              "</td>" +
              "<td data-order=\"" +
              escapeHtml(post.createdAt) +
              "\">" +
              escapeHtml(post.createdAt) +
              "</td>" +
              "<td>" +
              '<button type="button" class="btn btn-sm btn-warning me-1 btn-edit-post" data-id="' +
              post.id +
              '">Edit</button> ' +
              '<button type="button" class="btn btn-sm btn-danger btn-del-post" data-id="' +
              post.id +
              '">Delete</button>' +
              "</td>";
            body.appendChild(row);
          });

          if (postsTable) {
            postsTable.destroy();
            postsTable = null;
          }
          postsTable = $("#postsTable").DataTable({
            paging: true,
            pageLength: 10,
            lengthChange: false,
            dom: "rtip",
            ordering: true,
            order: [[5, "desc"]],
            columnDefs: [{ orderable: false, targets: 6 }],
          });
        }

        function clearPostErrors() {
          document
            .querySelectorAll("#postForm .text-danger")
            .forEach((el) => {
              el.textContent = "";
            });
        }

        function validatePost() {
          clearPostErrors();
          let valid = true;
          const title = document.getElementById("postTitle").value.trim();
          const tc = charCount(title);
          if (tc < 10 || tc > 200) {
            document.getElementById("postTitleError").textContent =
              "Title must be between 10 and 200 characters.";
            valid = false;
          }
          const body = document.getElementById("postBody").value.trim();
          const bc = charCount(body);
          if (bc < 10 || bc > 5000) {
            document.getElementById("postBodyError").textContent =
              "Body must be between 10 and 5000 characters.";
            valid = false;
          }
          const photoUrl = document.getElementById("postPhotoUrl").value.trim();
          if (photoUrl && !isValidUrl(photoUrl)) {
            document.getElementById("postPhotoUrlError").textContent =
              "Photo URL must be a valid URL if provided.";
            valid = false;
          }
          const flair = document.getElementById("postFlair").value;
          if (!flair || !FLAIR_VALUES.includes(flair)) {
            document.getElementById("postFlairError").textContent =
              "Flair is required (Question, Thread, or Disclaimer).";
            valid = false;
          }
          const status = document.getElementById("postStatus").value;
          if (!status) {
            document.getElementById("postStatusError").textContent =
              "Status is required.";
            valid = false;
          }
          const authorType = document.getElementById("postAuthorType").value;
          if (!authorType) {
            document.getElementById("postAuthorTypeError").textContent =
              "Author type is required.";
            valid = false;
          }
          const minUpvotesWrap = document.getElementById("postMinUpvotesForChallengeWrap");
          const minUpvotesEl = document.getElementById("postMinUpvotesForChallenge");
          const minUpvotesRaw = minUpvotesEl ? minUpvotesEl.value : "";
          const showMinUpvotes = authorType === "Formateur" || authorType === "Entreprise";
          if (minUpvotesWrap) minUpvotesWrap.style.display = showMinUpvotes ? "" : "none";
          if (showMinUpvotes) {
            const v = parseInt(minUpvotesRaw, 10);
            if (minUpvotesRaw === "" || Number.isNaN(v) || v < 0) {
              document.getElementById("postMinUpvotesForChallengeError").textContent =
                "Minimum upvotes must be a number ≥ 0.";
              valid = false;
            }
          } else {
            if (minUpvotesEl) minUpvotesEl.value = "0";
          }
          const pollOpts = collectPollOptions();
          if (pollOpts.length === 1) {
            document.getElementById("pollOptionsError").textContent =
              "If you start a poll, provide at least 2 options.";
            valid = false;
          } else if (pollOpts.length > 5) {
            document.getElementById("pollOptionsError").textContent =
              "Provide up to 5 poll options.";
            valid = false;
          }
          return valid;
        }

        function openPostModal(id) {
          document.getElementById("postModalTitle").textContent = id
            ? "Edit Post"
            : "Add Post";
          document.getElementById("postId").value = id || "";
          if (id) {
            const post = posts.find((item) => item.id === id);
            document.getElementById("postTitle").value = post.title;
            document.getElementById("postBody").value = post.body;
            document.getElementById("postPhotoUrl").value = post.photoUrl || "";
            document.getElementById("postFlair").value = post.flair;
            document.getElementById("postStatus").value = post.status;
            document.getElementById("postAuthorType").value = post.authorType;
            document.getElementById("postMinUpvotesForChallenge").value =
              String(post.minUpvotesForChallenge == null ? 0 : post.minUpvotesForChallenge);
            const po = post.pollOptions || [];
            for (let i = 0; i < 5; i += 1) {
              const el = document.getElementById("pollOpt" + i);
              if (el) el.value = po[i] || "";
            }
          } else {
            document.getElementById("postForm").reset();
            document.getElementById("postMinUpvotesForChallenge").value = "0";
            for (let i = 0; i < 5; i += 1) {
              const el = document.getElementById("pollOpt" + i);
              if (el) el.value = "";
            }
          }
          const authorTypeNow = document.getElementById("postAuthorType").value;
          const show = authorTypeNow === "Formateur" || authorTypeNow === "Entreprise";
          document.getElementById("postMinUpvotesForChallengeWrap").style.display = show ? "" : "none";
          clearPostErrors();
          postModal.show();
        }

        function savePost() {
          if (!validatePost()) return;
          const idValue = document.getElementById("postId").value;
          const prev = idValue
            ? posts.find((item) => item.id === Number(idValue))
            : null;
          const pollOpts = collectPollOptions();
          let pollVotes;
          if (
            prev &&
            prev.pollOptions &&
            JSON.stringify(prev.pollOptions) === JSON.stringify(pollOpts) &&
            Array.isArray(prev.pollVotes)
          ) {
            pollVotes = prev.pollVotes.slice(0, pollOpts.length);
            while (pollVotes.length < pollOpts.length) pollVotes.push(0);
          } else {
            pollVotes = pollOpts.map(function () {
              return 0;
            });
          }
          const post = {
            id: idValue ? Number(idValue) : Date.now(),
            title: document.getElementById("postTitle").value.trim(),
            body: document.getElementById("postBody").value.trim(),
            photoUrl: document.getElementById("postPhotoUrl").value.trim(),
            createdAt: idValue ? prev.createdAt : todayISODate(),
            status: document.getElementById("postStatus").value,
            flair: document.getElementById("postFlair").value,
            upvoteCount: prev ? Number(prev.upvoteCount) || 0 : 0,
            upvotedBy: prev && prev.upvotedBy ? prev.upvotedBy : [],
            authorType: document.getElementById("postAuthorType").value,
            minUpvotesForChallenge:
              (document.getElementById("postAuthorType").value === "Formateur" ||
                document.getElementById("postAuthorType").value === "Entreprise")
                ? Math.max(
                    0,
                    Math.floor(
                      Number(document.getElementById("postMinUpvotesForChallenge").value || 0)
                    )
                  )
                : 0,
            pollOptions: pollOpts,
            pollVotes: pollVotes,
          };
          if (idValue) {
            posts = posts.map((item) =>
              item.id === Number(idValue) ? post : item
            );
          } else {
            posts.push(post);
          }
          saveData();
          updateStatistics();
          renderPostsTable();
          wirePostsTableFilters();
          postModal.hide();
        }

        function deletePost(id) {
          if (
            !window.confirm(
              "Delete this post? This cannot be undone."
            )
          )
            return;
          posts = posts.filter((item) => item.id !== id);
          saveData();
          updateStatistics();
          renderPostsTable();
          wirePostsTableFilters();
        }

        function wirePostsTableFilters() {
          if (!postsTable) return;
          const flair = document.getElementById("flairFilter").value;
          const status = document.getElementById("statusFilter").value;
          const author = document.getElementById("authorTypeFilter").value;
          const title = document.getElementById("titleSearch").value;
          postsTable.column(1).search(flair);
          postsTable.column(2).search(status);
          postsTable.column(4).search(author);
          postsTable.column(0).search(title);
          postsTable.draw();
        }

        document.addEventListener("DOMContentLoaded", function () {
          postModal = new bootstrap.Modal(document.getElementById("postModal"));

          document.getElementById("postForm").addEventListener("submit", function (e) {
            e.preventDefault();
            savePost();
          });

          loadData();
          updateStatistics();
          renderPostsTable();
          wirePostsTableFilters();

          document
            .getElementById("addPostBtn")
            .addEventListener("click", function () {
              openPostModal(null);
            });
          document
            .getElementById("savePostBtn")
            .addEventListener("click", savePost);

          document.getElementById("postAuthorType").addEventListener("change", function () {
            const authorType = document.getElementById("postAuthorType").value;
            const show = authorType === "Formateur" || authorType === "Entreprise";
            document.getElementById("postMinUpvotesForChallengeWrap").style.display = show ? "" : "none";
            if (!show) {
              document.getElementById("postMinUpvotesForChallenge").value = "0";
              document.getElementById("postMinUpvotesForChallengeError").textContent = "";
            }
          });

          ["flairFilter", "statusFilter", "authorTypeFilter"].forEach(function (id) {
            document.getElementById(id).addEventListener("change", wirePostsTableFilters);
          });
          document
            .getElementById("titleSearch")
            .addEventListener("keyup", wirePostsTableFilters);

          document.getElementById("postsTableBody").addEventListener("click", function (e) {
            const editBtn = e.target.closest(".btn-edit-post");
            const delBtn = e.target.closest(".btn-del-post");
            if (editBtn) {
              openPostModal(Number(editBtn.getAttribute("data-id")));
            } else if (delBtn) {
              deletePost(Number(delBtn.getAttribute("data-id")));
            }
          });
        });
      })();
    </script>
  </body>
</html>
