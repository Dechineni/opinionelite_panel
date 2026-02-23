<?php
include('config.php');
include('header.php');
?>

<style>
.survey-list-container {
    padding: 20px;
    max-width: 1100px; /* centered, adjust if you want full screen */
    margin: 0 auto;
}

.survey-header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
}

.survey-list {
    display: flex;
    flex-direction: column; /* ✅ one below the other */
    gap: 16px;
    margin-top: 16px;
}

.survey-card {
    background: #1a1a1a;
    border: 1px solid #444;
    border-radius: 10px;
    padding: 18px;
    width: 100%; /* ✅ full width */
    color: #fff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
}

.survey-card h3 {
    font-size: 18px;
    margin: 0 0 10px 0;
    color: #ff9800;
}

.survey-card p {
    margin: 6px 0;
    font-size: 14px;
}

.survey-card a {
    display: inline-block;
    margin-top: 12px;
    padding: 10px 16px;
    background: #ff9800;
    color: #000;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
}

.survey-card a:hover { background: #e68900; }

.refresh-btn {
    padding: 8px 14px;
    background: #ff9800;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
}
.refresh-btn:hover { background: #e68900; }

.notice {
    margin-top: 12px;
    padding: 10px 12px;
    background: #121212;
    border: 1px solid #444;
    border-radius: 8px;
    color: #ddd;
    font-size: 14px;
    display: none;
}

.notice.top {
    display: none;
    margin-top: 0;
    margin-bottom: 10px;
}

.notice.success {
    background: #0f2a16;
    border-color: #1f6b3a;
    color: #b7f7c8;
}

.notice.info {
    background: #1a1a1a;
    border-color: #444;
    color: #ddd;
}

.notice.error {
    background: #2a0f0f;
    border-color: #a33;
    color: #ffb3b3;
}
</style>

<div class="survey-list-container">

    <div id="surveyTopMessage" class="notice top"></div>

    <div class="survey-header-row">
        <h1 style="color:#fff; margin:0;">Surveys</h1>

        <button class="refresh-btn" onclick="refreshSurveys()">
            Refresh
        </button>
    </div>

    <div id="surveyEmptyMessage" class="notice info"></div>
    <div id="surveyContainer" class="survey-list"></div>

</div>

<script>
(function(){
  const username = (localStorage.getItem("username") || "").trim();
  const container = document.getElementById("surveyContainer");
  const topMsg = document.getElementById("surveyTopMessage");
  const emptyMsg = document.getElementById("surveyEmptyMessage");

  // Keep last signature so we can detect changes
  let lastSignature = null;

  function showTopMessage(text, type = "info") {
    topMsg.style.display = "block";
    topMsg.className = `notice top ${type}`;
    topMsg.textContent = text;

    // auto-hide after a few seconds (optional UX)
    clearTimeout(showTopMessage._t);
    showTopMessage._t = setTimeout(() => {
      topMsg.style.display = "none";
    }, 3500);
  }

  function showEmptyMessage(text) {
    emptyMsg.style.display = "block";
    emptyMsg.textContent = text;
  }

  function hideEmptyMessage() {
    emptyMsg.style.display = "none";
    emptyMsg.textContent = "";
  }

  function clearCards() {
    container.innerHTML = "";
  }

  function formatLoi(loi) {
    if (loi === null || loi === undefined || loi === "" || loi === "—") return "—";
    return `${loi}mins`;
  }

  function formatRewards(rewards) {
    if (rewards === null || rewards === undefined || rewards === "" || rewards === "—") return "—";
    return `${rewards}$`;
  }

  function computeSignature(items) {
    // Use stable fields to detect change; surveyLink is best unique key.
    // Sort to make signature order-independent.
    const keys = items.map(i => String(i.surveyLink || "")).sort();
    return JSON.stringify(keys);
  }

  if (!username) {
    showTopMessage("No user found. Please login again.", "error");
    return;
  }

  function renderItems(items) {
    clearCards();

    items.forEach((survey) => {
      const card = document.createElement("div");
      card.className = "survey-card";

      const loiText = formatLoi(survey.loi);
      const rewardsText = formatRewards(survey.rewards);

      card.innerHTML = `
        <h3>${survey.surveyName || "Survey"}</h3>
        <p><strong>Supplier:</strong> ${survey.supplierName || "—"}</p>
        <p><strong>LOI:</strong> ${loiText}</p>
        <p><strong>Rewards:</strong> ${rewardsText}</p>
        <a href="${survey.surveyLink}" target="_blank" rel="noopener noreferrer">Start Survey</a>
      `;

      container.appendChild(card);
    });
  }

  function loadSurveys({ showRefreshAlert }) {
    const url = `get_surveys.php?user_id=${encodeURIComponent(username)}`;

    fetch(url, { cache: "no-store" })
      .then(async (res) => {
        const text = await res.text();
        let json = {};
        try { json = JSON.parse(text); }
        catch (e) { throw new Error("Invalid server response."); }

        if (!res.ok) {
          throw new Error(json.error || `Request failed (${res.status})`);
        }
        return json;
      })
      .then((data) => {
        const items = Array.isArray(data.items) ? data.items : [];

        // If empty
        if (!items.length) {
          clearCards();
          showEmptyMessage("No surveys available for your profile right now.");

          if (showRefreshAlert) {
            showTopMessage("No New Surveys", "info");
          }

          // Reset signature so next time if surveys appear, it counts as new
          lastSignature = computeSignature([]);
          return;
        }

        hideEmptyMessage();
        renderItems(items);

        const newSig = computeSignature(items);

        // First load: set baseline, don't show "new surveys"
        if (lastSignature === null) {
          lastSignature = newSig;
          return;
        }

        // Refresh: show alert only if list changed
        if (showRefreshAlert) {
          if (newSig !== lastSignature) {
            showTopMessage("New Surveys are Added", "success");
          } else {
            showTopMessage("No New Surveys", "info");
          }
        }

        lastSignature = newSig;
      })
      .catch((err) => {
        clearCards();
        hideEmptyMessage();
        showTopMessage(err?.message || "Failed to load surveys.", "error");
        console.error("Survey load error:", err);
      });
  }

  // Expose refresh button handler
  window.refreshSurveys = function() {
    loadSurveys({ showRefreshAlert: true });
  };

  // Initial load (no refresh alert)
  loadSurveys({ showRefreshAlert: false });

})();
</script>

<?php include('footer.php'); ?>
