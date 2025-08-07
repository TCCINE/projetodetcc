document.addEventListener('DOMContentLoaded', () => {
  const main = document.getElementById('mainContent');

  fetch('api/get_videos.php')
    .then(r => r.json())
    .then(data => {
      // --- Seção de Lançamentos (top 10, sem duplicar) ---
      const shown = new Set();
      if (data.recent.length) {
        const recent = data.recent.filter(v => {
          if (shown.has(v.youtube_video_id)) return false;
          shown.add(v.youtube_video_id);
          return true;
        });
        if (recent.length) renderSection('Lançamentos', recent);
      }

      // --- Cada categoria completa ---
      Object.entries(data.categories).forEach(([cat, vids]) => {
        if (vids.length) renderSection(cat, vids);
      });

      // --- Depois de gerar tudo, ligamos o listener único ---
      attachHandlers();
    })
    .catch(err => console.error('Erro ao buscar vídeos:', err));

function renderSection(title, videos) {
  const sec = document.createElement('section');
  sec.className = 'catalogo-section';
  if (title === 'Lançamentos') sec.classList.add('recent');

  sec.innerHTML = `
    <h2 class="catalogo-titulo">${title}</h2>
    <div class="itens">
      ${videos.map(v => `
        <div class="filme-item-wrapper">
          <div class="filme-item">
            <button class="video-overlay-btn"
                    data-id="${v.youtube_video_id}"
                    data-title="${v.title}"
                    data-desc="${v.description}">
              <img src="https://img.youtube.com/vi/${v.youtube_video_id}/hqdefault.jpg"
                   alt="${v.title}">
            </button>
          </div>
          <p class="filme-titulo">${v.title}</p>
        </div>
      `).join('')}
    </div>
  `;
  main.appendChild(sec);
}

function attachHandlers() {
  document.body.addEventListener('click', e => {
    const btn = e.target.closest('.video-overlay-btn');
    if (!btn) return;

    const ytId = btn.dataset.id;
    window.location.href = `about.php?id=${encodeURIComponent(ytId)}`;
  });
}
});
