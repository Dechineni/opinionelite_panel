<?php
include('config.php'); 
include('header.php'); 
?>

<style>
.survey-list-container {
    padding: 20px;
    max-width: 100%;
    margin: 0 auto;
}
.survey-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: flex-start;
}
.survey-card {
    background: #1a1a1a;
    border: 1px solid #444;
    border-radius: 10px;
    padding: 15px;
    width: 280px;
    color: #fff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
}
.survey-card h3 {
    font-size: 18px;
    margin-bottom: 10px;
    color: #ff9800;
}
.survey-card p {
    margin: 6px 0;
    font-size: 14px;
}
.survey-card a {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 12px;
    background: #ff9800;
    color: #000;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
}
.survey-card a:hover { background: #e68900; }

.notice {
    margin-top: 10px;
    padding: 10px 12px;
    background: #121212;
    border: 1px solid #444;
    border-radius: 8px;
    color: #ddd;
    font-size: 14px;
}
.notice.error {
    border-color: #a33;
    color: #ffb3b3;
}
.notice.top-alert {
    background: #ffb3b3;
    border: 1px solid #ffb3b3;
    color: #a33;
}
</style>

<div class="survey-list-container">

    <div id="surveyTopMessage" class="notice" style="display:none;"></div>
  
    <div style="display:flex; justify-content:space-between; align-items:center;">
    <h1 style="color:#fff;">Surveys</h1>

    <button onclick="refreshSurveys()" style="
        padding:8px 14px;
        background:#ff9800;
        border:none;
        border-radius:6px;
        font-weight:bold;
        cursor:pointer;">
        Refresh
    </button>
</div>
    <div id="surveyEmptyMessage" class="notice" style="display:none;"></div>

    <div id="surveyContainer" class="survey-list"></div>
    
</div>

<script>
(function(){

  const username = (localStorage.getItem("username") || "").trim();
  const container = document.getElementById("surveyContainer");
  const topmsg = document.getElementById("surveyTopMessage");
  const emptymsg = document.getElementById("surveyEmptyMessage");

  
function showTopMessage(text) {
  topmsg.style.display = "block";
  topmsg.textContent = text;
  topmsg.className = "notice top-alert";
}

function showEmptyMessage(text) {
  emptymsg.style.display = "block";
  emptymsg.textContent = text;
}

function hideMessages() {
  topmsg.style.display = "none";
  emptymsg.style.display = "none";
}

  if (!username) {
    showTopMessage("No user found. Please login again.");
    return;
  }

  function loadSurveys(showAlert = false) {

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
        container.innerHTML = "";

        if (!items.length) {

  if (showAlert) {
    showTopMessage("No New Surveys");
  }

  showEmptyMessage("No surveys available for your profile right now.");
  return;
}

        hideMessages();

        if (showAlert) {
          showTopMessage("New Surveys are Added");
        }

        items.forEach((survey) => {
          const card = document.createElement("div");
          card.className = "survey-card";

          card.innerHTML = `
            <h3>${survey.surveyName || "Survey"}</h3>
            <p><strong>Supplier:</strong> ${(survey.supplierName || "—")}</p>
            <p><strong>LOI:</strong> ${(survey.loi ?? "—")}</p>
            <p><strong>Rewards:</strong> ${(survey.rewards ?? "—")}</p>
            <a href="${survey.surveyLink}" target="_blank">Start Survey</a>
          `;

          container.appendChild(card);
        });
      })
      .catch((err) => {
        container.innerHTML = "";
        showTopMessage(err?.message || "Failed to load surveys.");
      });
  }

  //  This must be OUTSIDE loadSurveys
  window.refreshSurveys = function() {
    loadSurveys(true);
  };

  // Initial load
  loadSurveys(false);

})();
</script>
<?php include('footer.php'); ?>
