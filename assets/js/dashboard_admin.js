
function setNav(el){document.querySelectorAll('.nav-item').forEach(n=>n.classList.remove('active'));el.classList.add('active');}
function openConvocModal(){document.getElementById('convocModal').classList.add('open');}
function closeConvocModal(){document.getElementById('convocModal').classList.remove('open');}
function closeModalOutside(e){if(e.target.id==='convocModal')closeConvocModal();}
async function sendConvocationData(obj,date,heure,lieu,msg){
  const fd=new FormData(); fd.append('objet',obj); fd.append('date',date); fd.append('heure',heure); fd.append('lieu',lieu); fd.append('message',msg||''); fd.append('ajax','1');
  const res=await fetch('backend/actions/convocation.php',{method:'POST',body:fd,headers:{'Accept':'application/json'}}); const data=await res.json(); if(!data.ok) throw new Error(data.message||'Erreur convocation'); return data;
}
async function sendConvocModal(){
  const obj=document.getElementById('convocObj').value.trim(), date=document.getElementById('convocDate').value, heure=document.getElementById('convocHeure').value, lieu=document.getElementById('convocLieu').value.trim(), msg=document.getElementById('convocMsg').value.trim();
  if(!obj||!date||!heure||!lieu){alert('Objet, date, heure et lieu sont obligatoires.');return;}
  try{ await sendConvocationData(obj,date,heure,lieu,msg); closeConvocModal(); alert('Convocation enregistrée et envoyée aux enseignants/assistants.'); }catch(e){alert(e.message);}
}
async function sendConvoc(){
  const inputs=document.querySelectorAll('.convoc-form .form-input'); const obj=inputs[0]?.value.trim(), date=inputs[1]?.value, heure=inputs[2]?.value, lieu=inputs[3]?.value.trim(); const msg=document.querySelector('.convoc-form textarea')?.value.trim()||'';
  if(!obj||!date||!heure||!lieu){alert('Complète l’objet, la date, l’heure et le lieu.');return;}
  try{ await sendConvocationData(obj,date,heure,lieu,msg); alert('Convocation envoyée avec succès.'); }catch(e){alert(e.message);}
}
