<?php include('header.php'); ?>

<div class="container mx-auto px-4 py-6">
  <h2 class="text-xl font-semibold mb-4">Surveys</h2>

  <div id="surveys-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <!-- cards injected by JS -->
  </div>

  <div id="surveys-empty" class="hidden text-slate-600 mt-6">
    No surveys available for your profile right now.
  </div>
</div>

<script>
  async function loadSurveys() {
    const username = localStorage.getItem('username');
    if (!username) {
      document.getElementById('surveys-empty').classList.remove('hidden');
      return;
    }

    const form = new FormData();
    form.append('username', username);

    const res = await fetch('get_surveys.php', { method: 'POST', body: form });
    const json = await res.json().catch(() => ({}));

    const items = Array.isArray(json.items) ? json.items : [];
    const grid = document.getElementById('surveys-grid');

    if (!items.length) {
      document.getElementById('surveys-empty').classList.remove('hidden');
      return;
    }

    grid.innerHTML = items.map(item => {
      const name = item.surveyName || 'Survey';
      const link = item.surveyLink || '#';
      const loi  = item.loi ?? 0;
      const rew  = item.rewards ?? 0;

      return `
        <div class="bg-white rounded-lg shadow p-4 border border-slate-200">
          <div class="text-sm font-semibold text-slate-900 mb-2">${escapeHtml(name)}</div>

          <div class="text-sm text-slate-700 mb-1">
            <span class="font-medium">LOI:</span> ${escapeHtml(String(loi))}
          </div>

          <div class="text-sm text-slate-700 mb-3">
            <span class="font-medium">Rewards:</span> ${escapeHtml(String(rew))}
          </div>

          <a href="${escapeAttr(link)}"
             class="inline-block bg-teal-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-teal-700"
             target="_blank" rel="noopener noreferrer">
            Start Survey
          </a>
        </div>
      `;
    }).join('');
  }

  function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, (c) => ({
      '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'
    }[c]));
  }
  function escapeAttr(s) {
    // basic safety for href
    return escapeHtml(s);
  }

  loadSurveys();
</script>

<?php include('footer.php'); ?>
