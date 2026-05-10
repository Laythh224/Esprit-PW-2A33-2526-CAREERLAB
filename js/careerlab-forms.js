/**
 * Career Lab form validation helpers.
 */
(function (global) {
  "use strict";

  function todayISODate() {
    return new Date().toISOString().split("T")[0];
  }

  function wordCount(text) {
    return String(text || "")
      .trim()
      .split(/\s+/)
      .filter(Boolean).length;
  }

  function charCount(text) {
    return String(text || "").trim().length;
  }

  function isValidUrl(value) {
    if (!value || !String(value).trim()) return false;
    try {
      new URL(String(value).trim());
      return true;
    } catch (e) {
      return false;
    }
  }

  function loadPosts() {
    try {
      var raw = localStorage.getItem("blogPosts");
      var posts = raw ? JSON.parse(raw) : [];
      return Array.isArray(posts) ? posts : [];
    } catch (e) {
      return [];
    }
  }

  function validatePost(data) {
    var errors = {};
    var titleLen = charCount(data.title);
    var bodyLen = charCount(data.body);
    var minUpvotes = Number(data.minUpvotesForChallenge);

    if (titleLen < 10 || titleLen > 200) {
      errors.title = "Title must be between 10 and 200 characters.";
    }
    if (bodyLen < 10 || bodyLen > 5000) {
      errors.body = "Body must be between 10 and 5000 characters.";
    }
    if (data.photoUrl && !isValidUrl(data.photoUrl)) {
      errors.photoUrl = "Photo URL must be valid.";
    }
    if (!["Question", "Thread", "Disclaimer"].includes(data.flair)) {
      errors.flair = "Invalid flair.";
    }
    if (!["Utilisateur", "Formateur", "Entreprise"].includes(data.authorType)) {
      errors.authorType = "Invalid author type.";
    }
    if (!["active", "locked", "deleted"].includes(data.status)) {
      errors.status = "Invalid status.";
    }
    if (!Number.isFinite(minUpvotes) || minUpvotes < 0) {
      errors.minUpvotes = "Minimum upvotes must be >= 0.";
    }

    return { valid: Object.keys(errors).length === 0, errors: errors };
  }

  function validateChallenge(data) {
    var errors = {};
    var themeLen = charCount(data.theme);
    var descriptionLen = charCount(data.description);
    var rewardTitleLen = charCount(data.rewardTitle);
    var rewardDescriptionLen = charCount(data.rewardDescription);

    if (themeLen < 10 || themeLen > 200) {
      errors.theme = "Theme must be between 10 and 200 characters.";
    }
    if (descriptionLen < 10 || descriptionLen > 5000) {
      errors.description = "Description must be between 10 and 5000 characters.";
    }
    if (!["Projet", "Showcast", "Débat", "Pitch"].includes(data.flair)) {
      errors.flair = "Invalid flair.";
    }
    if (!["Formateur", "Entreprise"].includes(data.creatorType)) {
      errors.creatorType = "Invalid creator type.";
    }
    if (!data.startDate) {
      errors.startDate = "Start date is required.";
    }
    if (!data.endDate) {
      errors.endDate = "End date is required.";
    }
    if (data.startDate && data.endDate && data.endDate <= data.startDate) {
      errors.endDate = "End date must be after start date.";
    }
    if (!["job", "course"].includes(data.rewardType)) {
      errors.rewardType = "Invalid reward type.";
    }
    if (rewardTitleLen < 10 || rewardTitleLen > 200) {
      errors.rewardTitle = "Reward title must be between 10 and 200 characters.";
    }
    if (rewardDescriptionLen < 10 || rewardDescriptionLen > 5000) {
      errors.rewardDescription = "Reward description must be between 10 and 5000 characters.";
    }
    if (!data.postId) {
      errors.postId = "You must select a post.";
    }

    return { valid: Object.keys(errors).length === 0, errors: errors };
  }

  global.CareerLabForms = {
    todayISODate: todayISODate,
    wordCount: wordCount,
    isValidUrl: isValidUrl,
    loadPosts: loadPosts,
    validatePost: validatePost,
    validateChallenge: validateChallenge
  };
})(window);
