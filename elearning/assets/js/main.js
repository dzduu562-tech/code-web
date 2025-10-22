(function(){
  const root = document.documentElement;
  const key = 'theme';
  function setTheme(t){ root.setAttribute('data-theme', t); localStorage.setItem(key, t); }
  const saved = localStorage.getItem(key);
  if(saved){ setTheme(saved); }
  document.getElementById('themeToggle')?.addEventListener('click', function(){
    const curr = root.getAttribute('data-theme')==='dark'?'light':'dark';
    setTheme(curr);
  });

  // Poll notifications badge (dummy placeholder; endpoint to implement)
  const notifEl = document.getElementById('notifBadge');
  async function poll(){
    if(!notifEl) return;
    try{
      const res = await fetch(BASE_URL + '/index.php?route=/api/notifications&since=' + encodeURIComponent(notifEl.dataset.since||''), {headers:{'X-CSRF': CSRF_TOKEN}});
      if(res.ok){
        const data = await res.json();
        notifEl.textContent = data.unread || 0;
        notifEl.classList.toggle('d-none', (data.unread||0)===0);
        notifEl.dataset.since = data.since || '';
      }
    }catch(e){}
  }
  setInterval(poll, 25000);
  poll();
})();
