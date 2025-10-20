const rol = document.getElementById('rolSelect');
const wrap = document.getElementById('dirWrap');
function toggleDir(){ wrap.style.display = rol.value==='admin' ? 'none':'block'; }
rol.addEventListener('change', toggleDir); toggleDir();
