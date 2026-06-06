console.log("etudiant.js chargé");
let currentUploadKind = 'document';
let mediaRecorder = null;
let audioChunks = [];

function escapeHtml(text){return (text||'').replace(/[&<>"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));}
function switchTab(btn, tab) { document.querySelectorAll('.nav-tab').forEach(b => b.classList.remove('active')); btn.classList.add('active'); }
function selectConv(item) { document.querySelectorAll('.conv-item').forEach(i => i.classList.remove('active')); item.classList.add('active'); }
function handleKey(e) { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMsg(); } }

async function postMessage(formData){
  formData.append('ajax','1');
  const res = await fetch('backend/actions/send_message.php', { method:'POST', body:formData, headers:{'Accept':'application/json'} });
  const data = await res.json();
  if(!data.ok) throw new Error(data.message || 'Erreur envoi');
  return data;
}
function appendTextMessage(text){
  const msgs = document.getElementById('messages'); const now = new Date();
  const time = now.getHours().toString().padStart(2,'0') + ':' + now.getMinutes().toString().padStart(2,'0');
  const row = document.createElement('div'); row.className = 'msg-row mine';
  row.innerHTML = `<div class="msg-avatar" style="background:linear-gradient(135deg,var(--sky),var(--accent));">ME</div><div class="msg-group"><div class="bubble mine">${escapeHtml(text)}</div><div class="msg-meta">${time} <span class="check-read">✓</span></div></div>`;
  msgs.appendChild(row); msgs.scrollTop = msgs.scrollHeight;
}
function appendFileMessage(file){
  const msgs = document.getElementById('messages'); const row = document.createElement('div'); row.className = 'msg-row mine';
  const size = file.taille ? (file.taille/1024/1024).toFixed(1)+' Mo' : '';
  let inner = '';
  if(file.type === 'audio') inner = `<div class="voice-bubble mine"><audio controls src="${escapeHtml(file.url)}"></audio></div>`;
  else inner = `<a class="file-bubble" href="${escapeHtml(file.url)}" target="_blank" style="text-decoration:none;color:inherit;background:rgba(79,163,224,0.1);border-color:rgba(79,163,224,0.3);"><div class="file-icon">📎</div><div class="file-info"><h5>${escapeHtml(file.nom)}</h5><p>${size} · ${escapeHtml(file.type)}</p></div><span style="font-size:18px;margin-left:auto;">⬇</span></a>`;
  row.innerHTML = `<div class="msg-avatar" style="background:linear-gradient(135deg,var(--sky),var(--accent));">ME</div><div class="msg-group">${inner}<div class="msg-meta">À l'instant ✓</div></div>`;
  msgs.appendChild(row); msgs.scrollTop = msgs.scrollHeight;
}
async function sendMsg(){
  const ta = document.getElementById('msgInput'); const text = ta.value.trim(); if(!text) return;
  const fd = new FormData(); fd.append('contenu', text); fd.append('type','public'); fd.append('cours_id','1');
  try { await postMessage(fd); appendTextMessage(text); ta.value=''; ta.style.height='auto'; } catch(e){ alert(e.message); }
}
function chooseFile(kind){
  currentUploadKind = kind;
  let input = document.getElementById('hiddenUploadInput');
  if(!input){ input = document.createElement('input'); input.type='file'; input.id='hiddenUploadInput'; input.style.display='none'; document.body.appendChild(input); input.addEventListener('change', uploadSelectedFile); }
  const accepts = {image:'image/*',video:'video/*',document:'.pdf,.doc,.docx,.xls,.xlsx',pdf:'.pdf',file:'.pdf,.doc,.docx,.xls,.xlsx,image/*,video/*,audio/*'};
  input.accept = accepts[kind] || accepts.file; input.value=''; input.click();
}
async function uploadSelectedFile(e){
  const file = e.target.files[0]; if(!file) return;
  const fd = new FormData(); fd.append('contenu',''); fd.append('type','public'); fd.append('cours_id','1'); fd.append('fichier', file);
  try { const data = await postMessage(fd); appendFileMessage(data.fichier); } catch(err){ alert(err.message); }
}
async function toggleAudio(){
  if(mediaRecorder && mediaRecorder.state === 'recording'){ mediaRecorder.stop(); return; }
  if(!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia){ alert('Enregistrement audio non supporté par ce navigateur.'); return; }
  try{
    const stream = await navigator.mediaDevices.getUserMedia({audio:true}); audioChunks=[]; mediaRecorder = new MediaRecorder(stream);
    mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
    mediaRecorder.onstop = async () => { stream.getTracks().forEach(t=>t.stop()); const blob = new Blob(audioChunks, {type:'audio/webm'}); const file = new File([blob], 'message-vocal.webm', {type:'audio/webm'}); const fd = new FormData(); fd.append('contenu','Message vocal'); fd.append('type','public'); fd.append('cours_id','1'); fd.append('fichier', file); try{ const data=await postMessage(fd); appendFileMessage(data.fichier); }catch(e){ alert(e.message); } };
    mediaRecorder.start(); alert('Enregistrement audio lancé. Clique encore sur 🎤 pour arrêter.');
  } catch(e){ alert('Impossible d’accéder au micro.'); }
}
window.addEventListener('DOMContentLoaded', () => {
    const msgs = document.getElementById('messages');
    if (msgs) msgs.scrollTop = msgs.scrollHeight;

    const ta = document.getElementById('msgInput');
    if (ta) {
        ta.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
    }

    const labels = ['file', 'image', 'video', 'pdf'];

    document.querySelectorAll('.toolbar-btn').forEach((btn, index) => {
        btn.addEventListener('click', function () {
            chooseFile(labels[index] || 'file');
        });
    });

    const vb = document.querySelector('.voice-btn');
    if (vb) vb.addEventListener('click', toggleAudio);
});
