<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Challenge — Admin</title>
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
                    <li>
                      <a href="charts.html">
                        <span class="sub-item">blog</span>
                      </a>
                    </li>
                    <li class="active">
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
                <h3 class="fw-bold mb-3">Challenge</h3>
                <h6 class="op-7 mb-2">
                  Challenge statistics and CRUD (local storage)
                </h6>
              </div>
            </div>

            <div class="row mb-4">
              <div class="col-6 col-md-4 col-lg-2 mb-3">
                <div class="card card-stats card-round">
                  <div class="card-body text-center">
                    <p class="card-category mb-1">Total Challenges</p>
                    <h4 class="card-title" id="totalChallenges">0</h4>
                  </div>
                </div>
              </div>
              <div class="col-6 col-md-4 col-lg-2 mb-3">
                <div class="card card-stats card-round">
                  <div class="card-body text-center">
                    <p class="card-category mb-1">Active Challenges</p>
                    <h4 class="card-title" id="activeChallenges">0</h4>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mb-4">
              <div class="col-md-6 mb-4">
                <div class="card card-round">
                  <div class="card-header">
                    <h4 class="card-title mb-0">Challenges — flair</h4>
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
                    <h4 class="card-title mb-0">Challenges — status</h4>
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
                    <h4 class="card-title">Challenges</h4>
                    <p class="card-category">Create, edit, and delete — 10 rows per page</p>
                  </div>
                  <button type="button" class="btn btn-primary" id="addChallengeBtn">
                    Add Challenge
                  </button>
                </div>
                <div class="table-responsive">
                  <table
                    id="challengesTable"
                    class="table table-striped table-bordered"
                    style="width: 100%"
                  >
                    <thead>
                      <tr>
                        <th>Theme</th>
                        <th>Flair</th>
                        <th>Creator Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody id="challengesTableBody"></tbody>
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
                      Add Challenge
                    </h5>
                    <button
                      type="button"
                      class="btn-close"
                      data-bs-dismiss="modal"
                      aria-label="Close"
                    ></button>
                  </div>
                  <div class="modal-body">
                    <form id="challengeForm" novalidate>
                      <input type="hidden" id="challengeId" />
                      <div class="mb-3">
                        <label class="form-label" for="challengePostId"
                          >Attach to Post *</label
                        >
                        <select class="form-control" id="challengePostId"></select>
                        <div class="text-danger small" id="challengePostIdError"></div>
                        <div class="small text-muted mt-1">
                          A challenge attached to a post that hasn't reached the post minimum upvotes will be saved as <b>upcoming</b>.
                        </div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="challengeTheme"
                          >Theme *</label
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
                          <option value="">Select Flair</option>
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
                            >Creator Type *</label
                          >
                          <select class="form-control" id="challengeCreatorType">
                            <option value="">Select Creator Type</option>
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
                            >Start Date *</label
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
                            >End Date *</label
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
                            >Status *</label
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
                            Status is auto-managed from the selected post's upvotes.
                          </div>
                        </div>
                        <div class="col-md-4">
                          <label class="form-label" for="challengeRewardType"
                            >Reward Type *</label
                          >
                          <select class="form-control" id="challengeRewardType">
                            <option value="">Select Reward Type</option>
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
                          >Reward Title *</label
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
                          >Reward Description *</label
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
                      Close
                    </button>
                    <button
                      type="button"
                      class="btn btn-primary"
                      id="saveChallengeBtn"
                    >
                      Save Challenge
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
        let challenges = [];
        let challengeModal;

        const challengeKey = "blogChallenges";

        const CREATOR_TYPES = ["Formateur", "Entreprise"];
        const REWARD_TYPES = ["job", "course"];
        const CHALLENGE_FLAIR = ["Projet", "Showcast", "Débat", "Pitch"];

        function charCount(s) {
          return String(s || "").trim().length;
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
            '<option value="">Select a post</option>' +
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
              '">Edit</button> ' +
              '<button type="button" class="btn btn-sm btn-danger btn-del-challenge" data-id="' +
              challenge.id +
              '">Delete</button>' +
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
              "Theme must be between 10 and 200 characters.";
            valid = false;
          }
          const description = document
            .getElementById("challengeDescription")
            .value.trim();
          const descC = charCount(description);
          if (descC < 10 || descC > 5000) {
            document.getElementById("challengeDescriptionError").textContent =
              "Description must be between 10 and 5000 characters.";
            valid = false;
          }
          const flairVal = document.getElementById("challengeFlair").value;
          if (!flairVal || !CHALLENGE_FLAIR.includes(flairVal)) {
            document.getElementById("challengeFlairError").textContent =
              "Flair is required (Projet, Showcast, Débat, or Pitch).";
            valid = false;
          }
          const creatorType = document.getElementById("challengeCreatorType").value;
          if (!creatorType || !CREATOR_TYPES.includes(creatorType)) {
            document.getElementById("challengeCreatorTypeError").textContent =
              "Creator type must be Formateur or Entreprise.";
            valid = false;
          }

          const startDateValue = document.getElementById("challengeStartDate").value;
          const today = todayISODate();
          if (!startDateValue) {
            document.getElementById("challengeStartDateError").textContent =
              "Start date is required.";
            valid = false;
          } else if (startDateValue < today) {
            if (!original) {
              document.getElementById("challengeStartDateError").textContent =
                "Start date cannot be in the past.";
              valid = false;
            } else if (original.startDate !== startDateValue) {
              document.getElementById("challengeStartDateError").textContent =
                "Start date cannot be in the past.";
              valid = false;
            }
          }

          const endDateValue = document.getElementById("challengeEndDate").value;
          if (!endDateValue) {
            document.getElementById("challengeEndDateError").textContent =
              "End date is required.";
            valid = false;
          } else if (startDateValue && endDateValue <= startDateValue) {
            document.getElementById("challengeEndDateError").textContent =
              "End date must be after start date.";
            valid = false;
          }

          if (!document.getElementById("challengeStatus").value) {
            document.getElementById("challengeStatusError").textContent =
              "Status is required.";
            valid = false;
          }

          const rewardType = document.getElementById("challengeRewardType").value;
          if (!rewardType || !REWARD_TYPES.includes(rewardType)) {
            document.getElementById("challengeRewardTypeError").textContent =
              "Reward type must be job or course.";
            valid = false;
          }
          const rt = document.getElementById("challengeRewardTitle").value.trim();
          const rtc = charCount(rt);
          if (rtc < 10 || rtc > 200) {
            document.getElementById("challengeRewardTitleError").textContent =
              "Reward title must be between 10 and 200 characters.";
            valid = false;
          }
          const rd = document
            .getElementById("challengeRewardDescription")
            .value.trim();
          const rdc = charCount(rd);
          if (rdc < 10 || rdc > 5000) {
            document.getElementById("challengeRewardDescriptionError").textContent =
              "Reward description must be between 10 and 5000 characters.";
            valid = false;
          }

          const postId = document.getElementById("challengePostId").value;
          if (!postId) {
            document.getElementById("challengePostIdError").textContent =
              "You must select a post for this challenge.";
            valid = false;
          } else {
            const posts = loadPostsRaw();
            const p = posts.find(function (x) {
              return String(x.id) === String(postId);
            });
            if (!p) {
              document.getElementById("challengePostIdError").textContent =
                "Selected post was not found.";
              valid = false;
            }
          }
          return valid;
        }

        function openChallengeModal(id) {
          document.getElementById("challengeModalTitle").textContent = id
            ? "Edit Challenge"
            : "Add Challenge";
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
          updateStatistics();
          renderChallengesTable();
          challengeModal.hide();
        }

        function deleteChallenge(id) {
          if (
            !window.confirm(
              "Delete this challenge? This cannot be undone."
            )
          )
            return;
          challenges = challenges.filter((item) => String(item.id) !== String(id));
          saveData();
          updateStatistics();
          renderChallengesTable();
        }

        document.addEventListener("DOMContentLoaded", function () {
          challengeModal = new bootstrap.Modal(
            document.getElementById("challengeModal")
          );

          document
            .getElementById("challengeForm")
            .addEventListener("submit", function (e) {
              e.preventDefault();
              saveChallenge();
            });

          loadData();
          updateStatistics();
          renderChallengesTable();
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
            .getElementById("saveChallengeBtn")
            .addEventListener("click", saveChallenge);

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
        });
      })();
    </script>
  </body>
</html>
