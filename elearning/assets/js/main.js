(function(){
  const root = document.documentElement;
  const toggle = document.getElementById('themeToggle');
  if (toggle) {
    toggle.addEventListener('click', ()=>{
      const cur = root.getAttribute('data-bs-theme') || 'light';
      const next = cur === 'light' ? 'dark' : 'light';
      root.setAttribute('data-bs-theme', next);
      try{localStorage.setItem('theme', next);}catch(e){}
    });
    try{const saved=localStorage.getItem('theme'); if(saved){ root.setAttribute('data-bs-theme', saved);} }catch(e){}
  }

  // notifications poll
  async function poll(){
    try {
      const res = await fetch('index.php?route=/notifications/poll', {headers: {'X-Requested-With':'fetch'}});
      if(!res.ok) return; const data = await res.json();
      const badge = document.getElementById('notifBadge');
      if (badge) { badge.style.display = data.count>0 ? 'inline-block' : 'none'; badge.textContent = data.count; }
    } catch(e) {}
  }
  setInterval(poll, 25000);
  poll();
})();