/**
 * Career Lab public helpers (localStorage).
 */
(function (global) {
  "use strict";

  var POST_KEY = "blogPosts";
  var CH_KEY = "blogChallenges";
  var COMMENT_KEY = "challengeComments";

  function escapeHtml(s) {
    if (s == null) return "";
    var d = document.createElement("div");
    d.textContent = s;
    return d.innerHTML;
  }

  function wordCount(s) {
    if (!s || !String(s).trim()) return 0;
    return String(s).trim().split(/\s+/).filter(Boolean).length;
  }

  function getVisitorId() {
    var id = localStorage.getItem("careerLabVisitorId");
    if (!id) {
      id = "v_" + Date.now() + "_" + Math.random().toString(36).slice(2, 11);
      localStorage.setItem("careerLabVisitorId", id);
    }
    return id;
  }

  function isValidUrl(value) {
    if (!value || !String(value).trim()) return false;
    try {
      new URL(value);
      return true;
    } catch (e) {
      return false;
    }
  }

  function safePostImage(url) {
    if (!url || !String(url).trim()) return "img/blog-1.jpg";
    try {
      new URL(url);
      return url;
    } catch (e) {
      return "img/blog-1.jpg";
    }
  }

  function loadPostsRaw() {
    try {
      var raw = localStorage.getItem(POST_KEY);
      return raw ? JSON.parse(raw) : [];
    } catch (e) {
      return [];
    }
  }

  function loadChallengesRaw() {
    try {
      var raw = localStorage.getItem(CH_KEY);
      return raw ? JSON.parse(raw) : [];
    } catch (e) {
      return [];
    }
  }

  function isActiveStatus(s) {
    var v = String(s || "").toLowerCase();
    return v === "active" || v === "actif";
  }

  function loadChallengeComments() {
    try {
      var raw = localStorage.getItem(COMMENT_KEY);
      return raw ? JSON.parse(raw) : [];
    } catch (e) {
      return [];
    }
  }

  function saveChallengeComments(arr) {
    localStorage.setItem(COMMENT_KEY, JSON.stringify(arr));
  }

  function commentsForChallenge(challengeId) {
    return loadChallengeComments()
      .filter(function (c) {
        return String(c.challengeId) === String(challengeId);
      })
      .sort(function (a, b) {
        return String(b.createdAt || "").localeCompare(String(a.createdAt || ""));
      });
  }

  function renderSidebarRecentPosts(containerId, limit) {
    var el = document.getElementById(containerId);
    if (!el) return;
    var n = limit || 5;
    var posts = loadPostsRaw()
      .filter(function (p) {
        return isActiveStatus(p.status);
      })
      .sort(function (a, b) {
        return String(b.createdAt || "").localeCompare(String(a.createdAt || ""));
      })
      .slice(0, n);

    if (!posts.length) {
      el.innerHTML = '<p class="text-muted small mb-0">No posts yet.</p>';
      return;
    }

    el.innerHTML = posts
      .map(function (post) {
        var img = safePostImage(post.photoUrl);
        var title = escapeHtml((post.title || "").slice(0, 80)) + ((post.title || "").length > 80 ? "..." : "");
        var href = "blog.php?postId=" + encodeURIComponent(String(post.id));
        return (
          '<div class="d-flex rounded overflow-hidden mb-3">' +
          '<img class="img-fluid" src="' + escapeHtml(img) + '" style="width:100px;height:100px;object-fit:cover;" alt="">' +
          '<a href="' + escapeHtml(href) + '" class="h6 fw-semi-bold d-flex align-items-center bg-light px-3 mb-0">' + title + "</a>" +
          "</div>"
        );
      })
      .join("");
  }

  function renderSidebarRecentChallenges(containerId, limit) {
    var el = document.getElementById(containerId);
    if (!el) return;
    var n = limit || 5;
    var list = loadChallengesRaw()
      .filter(function (c) {
        return isActiveStatus(c.status);
      })
      .sort(function (a, b) {
        return String(b.startDate || "").localeCompare(String(a.startDate || ""));
      })
      .slice(0, n);

    if (!list.length) {
      el.innerHTML = '<p class="text-muted small mb-0">No challenges yet.</p>';
      return;
    }

    el.innerHTML = list
      .map(function (ch) {
        var title = escapeHtml((ch.theme || "").slice(0, 80)) + ((ch.theme || "").length > 80 ? "..." : "");
        var href = "detail.php?challengeId=" + encodeURIComponent(String(ch.id));
        return (
          '<div class="d-flex rounded overflow-hidden mb-3">' +
          '<img class="img-fluid" src="img/blog-2.jpg" style="width:100px;height:100px;object-fit:cover;" alt="">' +
          '<a href="' + escapeHtml(href) + '" class="h6 fw-semi-bold d-flex align-items-center bg-light px-3 mb-0">' + title + "</a>" +
          "</div>"
        );
      })
      .join("");
  }

  global.CareerLab = {
    POST_KEY: POST_KEY,
    CH_KEY: CH_KEY,
    COMMENT_KEY: COMMENT_KEY,
    escapeHtml: escapeHtml,
    wordCount: wordCount,
    getVisitorId: getVisitorId,
    isValidUrl: isValidUrl,
    loadPostsRaw: loadPostsRaw,
    loadChallengesRaw: loadChallengesRaw,
    isActiveStatus: isActiveStatus,
    loadChallengeComments: loadChallengeComments,
    saveChallengeComments: saveChallengeComments,
    commentsForChallenge: commentsForChallenge,
    renderSidebarRecentPosts: renderSidebarRecentPosts,
    renderSidebarRecentChallenges: renderSidebarRecentChallenges
  };
})(window);
