
function _cat(v){v=(v||'').toLowerCase(); if(v.includes('urgent'))return'urgence'; if(v.includes('convocation'))return'convocation'; if(v.includes('acad'))return'academique'; return'info';}
async function _publishValve(t,c,cat,exp,file){ const fd=new FormData(); fd.append('titre',t); fd.append('contenu',c); fd.append('categorie',_cat(cat)); fd.append('date_expiration',exp||''); fd.append('ajax','1'); if(file)fd.append('fichier',file); const res=await fetch('backend/actions/valve.php',{method:'POST',body:fd,headers:{'Accept':'application/json'}}); const data=await res.json(); if(!data.ok)throw new Error(data.message); return data; }
window.publishFromModal = async function(){
  const t=document.getElementById('mTitle').value.trim(), c=document.getElementById('mContent').value.trim(), cat=document.getElementById('mCat').value, exp=document.querySelector('#modal input[type="date"]')?.value||'', file=document.querySelector('#modal input[type="file"]')?.files[0];
  if(!t||!c){alert('Veuillez remplir le titre et le contenu.');return;}
  try{ await _publishValve(t,c,cat,exp,file); closeModal(); alert('Annonce publiée en base de données.'); location.href='valve.html'; }catch(e){alert(e.message);}
}
window.publishAnnonce = async function(){
  const t=document.getElementById('compTitle').value.trim(), c=document.getElementById('compContent').value.trim(), cat=document.getElementById('compCat').value, exp=document.querySelector('.compose-card input[type="date"]')?.value||'', file=document.querySelector('.compose-card input[type="file"]')?.files[0];
  if(!t||!c){alert('Veuillez remplir le titre et le contenu.');return;}
  try{ await _publishValve(t,c,cat,exp,file); alert('Annonce publiée en base de données.'); document.getElementById('compTitle').value=''; document.getElementById('compContent').value=''; }catch(e){alert(e.message);}
}
window.deleteAnnonce = async function(btn){ alert('Suppression visuelle uniquement pour les annonces de démonstration. Les nouvelles annonces sont gérées dans la base.'); if(confirm('Masquer cette annonce dans l’interface ?')) btn.closest('.annonce-item')?.remove(); }
