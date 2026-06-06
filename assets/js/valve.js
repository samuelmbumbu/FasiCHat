
function escapeHtml(text){return (text||'').replace(/[&<>"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));}
function catFromLabel(txt){ txt=(txt||'').toLowerCase(); if(txt.includes('urgence')||txt.includes('urgent'))return'urgence'; if(txt.includes('convocation'))return'convocation'; if(txt.includes('acad'))return'academique'; if(txt.includes('info'))return'info'; return'toutes'; }
function openModal(){document.getElementById('modal').classList.add('open');}
function closeModal(){document.getElementById('modal').classList.remove('open');}
function closeModalOutside(e){if(e.target===document.getElementById('modal'))closeModal();}
async function loadValve(categorie='toutes'){
  const res=await fetch('backend/actions/get_valve.php?categorie='+encodeURIComponent(categorie),{headers:{'Accept':'application/json'}}); const data=await res.json(); if(!data.ok)return;
  const container=document.querySelector('.annonces-grid') || document.querySelector('.valve-content') || document.querySelector('.main-area');
  if(!container)return;
  let cards=container.querySelectorAll('.annonce-card'); cards.forEach(c=>c.remove());
  const html=data.annonces.map(a=>renderAnnonce(a)).join('');
  container.insertAdjacentHTML('beforeend', html);
}
function renderAnnonce(a){
  const icon={urgence:'🚨',convocation:'📅',info:'📢',academique:'🎓'}[a.categorie]||'📢';
  const file=a.fichier_url?`<div class="ac-file"><span>📎</span><a href="${escapeHtml(a.fichier_url)}" target="_blank">${escapeHtml(a.fichier_nom||'Pièce jointe')}</a></div>`:'';
  return `<div class="annonce-card ${escapeHtml(a.categorie)}" data-cat="${escapeHtml(a.categorie)}"><div class="ac-header"><div class="ac-cat-icon">${icon}</div><div class="ac-meta"><div class="ac-cat-label">${escapeHtml(a.categorie||'info').toUpperCase()}</div><div class="ac-title">${escapeHtml(a.titre)}</div></div></div><div class="ac-body"><div class="ac-text">${escapeHtml(a.contenu)}</div>${file}<div class="ac-footer"><div class="ac-author"><div class="ac-author-ava">AD</div><div><div class="ac-author-name">${escapeHtml(a.auteur||'Administration')}</div></div></div><div class="ac-date">${escapeHtml(a.date_publication||'')}</div></div></div></div>`;
}
async function publishAnnonce(){
  const modal=document.querySelector('#modal .modal-body');
  const titre=modal.querySelector('input[type="text"]')?.value.trim();
  const categorie=catFromLabel(modal.querySelector('select')?.value || modal.querySelector('select option:checked')?.textContent);
  const expiration=modal.querySelector('input[type="date"]')?.value || '';
  const contenu=modal.querySelector('textarea')?.value.trim();
  const file=modal.querySelector('input[type="file"]')?.files[0];
  if(!titre||!contenu){alert('Veuillez saisir le titre et le contenu.');return;}
  const fd=new FormData(); fd.append('titre',titre); fd.append('contenu',contenu); fd.append('categorie',categorie); fd.append('date_expiration',expiration); fd.append('ajax','1'); if(file)fd.append('fichier',file);
  try{ const res=await fetch('backend/actions/valve.php',{method:'POST',body:fd,headers:{'Accept':'application/json'}}); const data=await res.json(); if(!data.ok)throw new Error(data.message); closeModal(); alert('Annonce publiée.'); loadValve('toutes'); }catch(e){ alert(e.message || 'Action réservée à l’Apparitaire.'); }
}
window.addEventListener('DOMContentLoaded',()=>{
  document.querySelectorAll('.filter-chip').forEach(c=>c.addEventListener('click',()=>{document.querySelectorAll('.filter-chip').forEach(x=>x.classList.remove('active')); c.classList.add('active'); loadValve(catFromLabel(c.textContent));}));
  document.querySelectorAll('.cat-item').forEach(c=>c.addEventListener('click',function(){document.querySelectorAll('.cat-item').forEach(x=>x.classList.remove('active'));this.classList.add('active'); const n=this.querySelector('.cat-name'); if(n)loadValve(catFromLabel(n.textContent));}));
  loadValve('toutes');
});
