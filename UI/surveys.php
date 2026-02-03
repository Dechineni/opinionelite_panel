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
</style>

<div class="survey-list-container">
    <h1 style="color:#fff;">Surveys</h1>
    <div id="surveyContainer" class="survey-list"></div>
    <div id="surveyMessage" class="notice" style="display:none;"></div>
</div>

<script>
(function(){
  const username = (localStorage.getItem("username") || "").trim();
  const container = document.getElementById("surveyContainer");
  const msgBox = document.getElementById("surveyMessage");

  function showMessage(text, isError=false){
    msgBox.style.display = "block";
    msgBox.className = "notice" + (isError ? " error" : "");
    msgBox.textContent = text;
  }

  if (!username) {
    showMessage("No user found. Please login again.", true);
    return;
  }

  // Use GET with user_id to match your working Postman test
  const url = `get_surveys.php?user_id=${encodeURIComponent(username)}`;

  fetch(url, { cache: "no-store" })
    .then(async (res) => {
      const text = await res.text();
      let json = {};
      try { json = JSON.parse(text); } catch (e) {
        throw new Error(`Non-JSON response (${res.status}). Body: ${text.slice(0, 200)}`);
      }
      if (!res.ok) {
        throw new Error(json.error ? `${json.error}${json.detail ? " - " + json.detail : ""}` : `Request failed (${res.status})`);
      }
      return json;
    })
    .then((data) => {
      const items = Array.isArray(data.items) ? data.items : [];
      container.innerHTML = "";

      if (!items.length) {
        showMessage("No surveys available for your profile right now.");
        return;
      }

      msgBox.style.display = "none";

      items.forEach((survey) => {
        const card = document.createElement("div");
        card.className = "survey-card";

        card.innerHTML = `
          <h3>${survey.surveyName || "Survey"}</h3>
          <p><strong>Supplier:</strong> ${(survey.supplierName || "—")}</p>
          <p><strong>LOI:</strong> ${(survey.loi ?? "—")}</p>
          <p><strong>Rewards:</strong> ${(survey.rewards ?? "—")}</p>
          <a href="${survey.surveyLink}" target="_blank" rel="noopener noreferrer">Start Survey</a>
        `;

        container.appendChild(card);
      });
    })
    .catch((err) => {
      container.innerHTML = "";
      showMessage(err?.message || "Failed to load surveys.", true);
      console.error("Survey load error:", err);
    });
})();
</script>

<?php include('footer.php'); ?>
