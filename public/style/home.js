alert('yes')
const blank = {
  theme:'dark',
  username:'Marie',
  mood:null,
  sleep:null,
  reminders:[
    {id:1, date:todayISO(), time:'12:30', title:'Médicament', done:false},
    {id:2, date:todayISO(), time:'15:00', title:'Rendez-vous', done:false}
  ],
  regGoal:4,
  sessions:0,
  avgScore:30,
  adherence:0,
  feed:[]
};
let state = {...blank};
let currentExercise = null;

function todayISO(d=new Date()){
  const pad=n=>String(n).padStart(2,'0');
  return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
}

function init(){
  document.body.dataset.theme = state.theme;
  document.getElementById('today').textContent = new Date().toLocaleDateString('fr-FR', {weekday:'long', day:'numeric', month:'long'});
  document.getElementById('username').textContent = state.username;
  renderNextReminders();
  renderAgenda();
  renderFeed();
  updateTrack();
}

function toast(msg){
  const box = document.querySelector('.toasts');
  const el = document.createElement('div');
  el.className='toast';
  el.textContent = msg;
  box.appendChild(el);
  setTimeout(()=>el.remove(), 3000);
}

function speak(text){
  try{
    const u = new SpeechSynthesisUtterance(text);
    u.lang='fr-FR';
    speechSynthesis.speak(u);
  }catch(e){
    toast('Synthèse vocale non disponible');
  }
}

function toggleTheme(){
  state.theme = state.theme==='dark'?'light':'dark';
  document.body.dataset.theme = state.theme;
}

function biggerText(){
  const cur = parseFloat(getComputedStyle(document.body).fontSize);
  document.body.style.fontSize = Math.min(cur + 1, 22) + 'px';
  toast('Texte agrandi');
}

function goTab(id, btn){
  document.querySelectorAll('section').forEach(s=>s.classList.remove('active'));
  document.getElementById(id).classList.add('active');
  document.querySelectorAll('.tabbtn').forEach(b=>b.classList.remove('active'));
  if(btn) btn.classList.add('active');
  document.querySelector('main').scrollTop = 0;
}

function setMood(m){
  state.mood=m;
  document.getElementById('mood').textContent=m;
  toast('Humeur enregistrée');
}

function saveSleep(){
  state.sleep = document.getElementById('sleep').value;
  toast('Sommeil enregistré');
}

function renderNextReminders(){
  const ul = document.getElementById('nextReminders');
  ul.innerHTML='';
  const today = todayISO();
  const items = state.reminders.filter(r=>r.date===today).sort((a,b)=>a.time.localeCompare(b.time)).slice(0,3);
  if(!items.length){
    ul.innerHTML = '<li class="li"><span class="muted">Aucun rappel aujourd\'hui</span></li>';
    return;
  }
  items.forEach(r=>{
    const li = document.createElement('li');
    li.className='li';
    li.innerHTML = `<div class="li-left">${r.time} — ${r.title}</div><div class="li-right">${r.done?'✅':'🕒'}</div>`;
    ul.appendChild(li);
  });
}

function renderAgenda(){
  const ul = document.getElementById('agendaList');
  ul.innerHTML='';
  const today = todayISO();
  const items = state.reminders.filter(r=>r.date===today).sort((a,b)=>a.time.localeCompare(b.time));
  if(!items.length){
    ul.innerHTML = '<li class="li"><span class="muted">Aucun rappel</span></li>';
    return;
  }
  items.forEach(r=>{
    const li = document.createElement('li');
    li.className='li';
    li.innerHTML = `
      <div class="li-left">${r.time} — ${r.title}</div>
      <div class="li-right">
        <button class="btn" style="min-height:40px; padding:0 12px; font-size:13px" onclick="markDone(${r.id}, true)">✓</button>
      </div>`;
    ul.appendChild(li);
  });
}

function markDone(id, val){
  const r = state.reminders.find(x=>x.id===id);
  if(!r) return;
  r.done = val;
  toast('Rappel validé');
  calcAdherence();
  renderAgenda();
  renderNextReminders();
}

function calcAdherence(){
  const today = todayISO();
  const day = state.reminders.filter(r=>r.date===today);
  const total = day.length;
  const done = day.filter(r=>r.done).length;
  state.adherence = Math.round(100*done/Math.max(total,1));
  updateTrack();
}

const backdrop = document.getElementById('backdrop');
function showBackdrop(){ backdrop.classList.add('show') }
function hideBackdrop(){ backdrop.classList.remove('show') }
function closeAllSheets(){ closeReminderSheet(); closeSOS() }

const remSheet = document.getElementById('remSheet');
function openReminderSheet(){
  document.getElementById('remDate').value = todayISO();
  document.getElementById('remTime').value = '18:00';
  document.getElementById('remTitleInput').value='';
  document.getElementById('remRepeat').value='none';
  remSheet.classList.add('open');
  showBackdrop();
}

function closeReminderSheet(){
  remSheet.classList.remove('open');
  hideBackdrop();
}

function saveReminder(){
  const title = document.getElementById('remTitleInput').value.trim() || 'Rappel';
  const date = document.getElementById('remDate').value || todayISO();
  const time = document.getElementById('remTime').value || '18:00';
  const rep = document.getElementById('remRepeat').value;
  const id = Math.max(0,...state.reminders.map(x=>x.id))+1;
  state.reminders.push({id,title,date,time,repeat:rep,done:false});
  toast('Rappel créé');
  closeReminderSheet();
  renderAgenda();
  renderNextReminders();
}

function startMemory(){
  goTab('tab-exos');
  currentExercise = {type:'memo', stage:'learn', correct:['🍎','🔑','🌼'], pool:['🍎','🔑','🌼','🚗','🐱','⚽️'], picks:[]};
  document.getElementById('exerciseTitle').textContent = 'Mémoire — Mémorisez ces pictos';
  const learn = `<div style="font-size:3rem; text-align:center; margin:24px 0">${currentExercise.correct.join(' ')}</div><div class="muted" style="text-align:center">Observez bien pendant 3 secondes...</div>`;
  document.getElementById('exerciseContent').innerHTML = learn;
  document.getElementById('exerciseArea').style.display='block';
  document.getElementById('exerciseResult').style.display='none';
  
  setTimeout(()=>{
    currentExercise.stage='select';
    const pool = currentExercise.pool.map(x=>`<button class="emojiBtn" onclick="pickEmoji('${x}')">${x}</button>`).join('');
    document.getElementById('exerciseContent').innerHTML = `<div class="muted" style="margin-bottom:12px">Sélectionnez les pictos que vous avez vus :</div><div class="emojiGrid">${pool}</div>`;
  }, 3000);
}

function pickEmoji(e){
  if(!currentExercise) return;
  if(!currentExercise.picks.includes(e)){
    currentExercise.picks.push(e);
    event.target.classList.add('selected');
  }
}

function startAttention(){
  goTab('tab-exos');
  currentExercise = {type:'attention', target:'▲', grid:['▲','●','■','●','■','▲','●','▲','■'], picks:[]};
  document.getElementById('exerciseTitle').textContent = 'Attention — Repérez ▲';
  const buttons = currentExercise.grid.map((g,i)=>`<button class="emojiBtn" onclick="markCell(${i})">${g}</button>`).join('');
  document.getElementById('exerciseContent').innerHTML = `<div class="muted" style="margin-bottom:12px">Cliquez sur tous les triangles :</div><div class="emojiGrid">${buttons}</div>`;
  document.getElementById('exerciseArea').style.display='block';
  document.getElementById('exerciseResult').style.display='none';
}

function markCell(i){
  if(!currentExercise) return;
  if(!currentExercise.picks.includes(i)){
    currentExercise.picks.push(i);
    event.target.classList.add('selected');
  }
}

function startLanguage(){
  goTab('tab-exos');
  currentExercise = {type:'lang', words:['pomme','clé','fleur'], answer:''};
  document.getElementById('exerciseTitle').textContent = 'Langage — Écrire un mot';
  document.getElementById('exerciseContent').innerHTML = `
    <div class="muted" style="margin-bottom:12px">Mots : <strong>${currentExercise.words.join(', ')}</strong></div>
    <div class="field">
      <input id="langInput" class="input" placeholder="Écrivez un des mots" oninput="currentExercise.answer=this.value">
    </div>`;
  document.getElementById('exerciseArea').style.display='block';
  document.getElementById('exerciseResult').style.display='none';
}

function startOrientation(){
  goTab('tab-exos');
  currentExercise = {type:'ori', q:'Quel jour sommes-nous ?', a:(new Date()).toLocaleDateString('fr-FR',{weekday:'long'}), ans:''};
  const opts = ['lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche'];
  const buttons = opts.map(o=>`<button class="pill" onclick="currentExercise.ans='${o}'; document.getElementById('picked').textContent='${o}'">${o}</button>`).join('');
  document.getElementById('exerciseTitle').textContent = 'Orientation — Jour';
  document.getElementById('exerciseContent').innerHTML = `
    <div class="muted" style="margin-bottom:12px">${currentExercise.q}</div>
    <div class="pillRow">${buttons}</div>
    <div class="muted" style="margin-top:12px">Choisi : <strong id="picked">—</strong></div>`;
  document.getElementById('exerciseArea').style.display='block';
  document.getElementById('exerciseResult').style.display='none';
}

function validateExercise(){
  let score=0;
  if(!currentExercise){ cancelExercise(); return; }
  
  if(currentExercise.type==='memo'){
    score = currentExercise.picks.filter(e=>currentExercise.correct.includes(e)).length*33;
  }else if(currentExercise.type==='attention'){
    const correctIdx = [0,5,7];
    const ok = currentExercise.picks.filter(i=>correctIdx.includes(i)).length;
    score = Math.round(100*ok/3);
  }else if(currentExercise.type==='lang'){
    const ok = currentExercise.words.includes((currentExercise.answer||'').trim().toLowerCase());
    score = ok?100:0;
  }else if(currentExercise.type==='ori'){
    const ok = (currentExercise.ans||'').toLowerCase() === (currentExercise.a||'').toLowerCase();
    score = ok?100:0;
  }
  
  state.sessions += 1;
  state.avgScore = Math.round((state.avgScore + score)/2);
  updateTrack();
  
  document.getElementById('exerciseArea').style.display='none';
  document.getElementById('exerciseResult').style.display='block';
  document.getElementById('resultText').innerHTML = `<div class="statValue">${score}</div><div class="muted">${feedback(score)}</div>`;
  toast('Séance terminée !');
  currentExercise=null;
}

function feedback(s){
  if(s>=80) return "Excellent travail ! Continuez comme ça.";
  if(s>=50) return "Bien joué ! Vous progressez.";
  return "Pas de souci, on réessaie demain.";
}

function cancelExercise(){
  document.getElementById('exerciseArea').style.display='none';
  document.getElementById('exerciseResult').style.display='none';
  currentExercise = null;
}

function updateTrack(){
  document.getElementById('bar').style.width = Math.max(5, Math.min(100, state.avgScore)) + '%';
  document.getElementById('regularity').textContent = state.sessions;
  document.getElementById('adherence').textContent = state.adherence;
  document.getElementById('avgScoreDisplay').textContent = state.avgScore;
  const last = state.feed[0]?.text || 'Aucune note pour l\'instant.';
  document.getElementById('notesArea').textContent = last;
}

function postNote(){
  const txt = document.getElementById('noteText').value.trim();
  if(!txt) return;
  const vis = document.getElementById('visiblePatient').checked;
  const item = {who:'Aidant', text:txt, vis, ts: Date.now()};
  state.feed.unshift(item);
  document.getElementById('noteText').value='';
  renderFeed();
  updateTrack();
  toast('Note publiée');
}

function renderFeed(){
  const box = document.getElementById('feed');
  if(!state.feed.length){
    box.innerHTML = '<div class="muted">Aucune note.</div>';
    return;
  }
  box.innerHTML = state.feed.map(n=>`
    <div class="card" style="padding:16px; margin-bottom:12px">
      <div style="display:flex; justify-content:space-between; margin-bottom:8px">
        <strong>${n.who}</strong>
        <span class="muted" style="font-size:12px">${new Date(n.ts).toLocaleString('fr-FR')}</span>
      </div>
      <div style="margin-bottom:8px">${n.text}</div>
      <div class="muted" style="font-size:12px">${n.vis?'👁 Visible patient':'🔒 Privé aidants'}</div>
    </div>`).join('');
}

function generateReport(){
  const txt = `Rapport — App Aide Alzheimer
Date: ${new Date().toLocaleString('fr-FR')}

Scores (moyenne): ${state.avgScore}/100
Régularité (séances): ${state.sessions}/${state.regGoal}
Observance rappels: ${state.adherence}%
Humeur: ${state.mood||'-'} | Sommeil: ${state.sleep||'-'}

Dernière note aidant: ${state.feed[0]?.text||'-'}
`;
  const blob = new Blob([txt], {type:'text/plain;charset=utf-8'});
  const a = document.createElement('a');
  a.href = URL.createObjectURL(blob);
  a.download = 'rapport-suivi-' + todayISO() + '.txt';
  a.click();
  toast('Rapport téléchargé');
}

const sosSheet = document.getElementById('sosSheet');
function openSOS(){
  sosSheet.classList.add('open');
  showBackdrop();
}

function closeSOS(){
  sosSheet.classList.remove('open');
  hideBackdrop();
}

function confirmSOS(){
  closeSOS();
  toast('🚨 Appel d\'urgence déclenché');
}

document.addEventListener('keydown', (e)=>{
  if(e.key==='Escape') closeAllSheets();
});

document.addEventListener('DOMContentLoaded', init);
