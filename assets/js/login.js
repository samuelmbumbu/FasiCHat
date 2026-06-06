function setRole(btn) {
  document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  const role = btn.textContent.trim();
  const hidden = document.getElementById('role_choisi');
  if (hidden) hidden.value = role.toLowerCase();
}
// Checkbox toggle
document.querySelectorAll('.checkbox-wrap').forEach(wrap => {
  wrap.addEventListener('click', () => {
    const cb = wrap.querySelector('input');
    cb.checked = !cb.checked;
  });
});