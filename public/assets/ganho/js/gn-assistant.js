/* Ganho : assistant GANXÔ (instance réutilisable) + fenêtre de discussion flottante + création d'alerte guidée.
   - Une instance par élément [data-gn-assistant] : la fenêtre flottante (mode "window", toutes les pages) ou la page /assistant (mode "page").
   - Questions-réponses : POST /assistant/ask. Création d'alerte : mêmes routes que le formulaire /guest/openticket.
   - Vie privée : l'historique des questions vit dans sessionStorage (cet onglet) ; le brouillon d'alerte aussi, effacé après envoi,
     annulation ou « Effacer ». Rien n'est enregistré côté serveur hors l'alerte finale (comme avec le formulaire).
   Aucune bibliothèque : JavaScript natif. */
(function () {
  'use strict';
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
  var KEY = 'gn_asst_v1', DRAFT = 'gn_asst_draft_v1', OPEN = 'gn_asst_open_v1';
  var reduce = !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
  var csrf = ($('meta[name="csrf-token"]') || {}).content || '';
  var DANGER = 'Si vous êtes en danger immédiat, joignez d’abord les secours de votre pays ; le bouton SOS prépare une alerte avec votre position pour l’équipe GANXÔ.';
  var ROLES = {
    victim: { btn: 'Je suis la victime', label: 'la victime' },
    witness: { btn: 'Je suis témoin ou proche', label: 'un témoin ou un proche' },
    unknown: { btn: 'Je préfère ne pas le dire', label: 'une personne qui préfère ne pas préciser son lien avec les faits' }
  };
  var instances = [], winCtl = null;

  var store = {
    get: function (k) { try { return JSON.parse(sessionStorage.getItem(k) || 'null'); } catch (e) { return null; } },
    set: function (k, v) { try { sessionStorage.setItem(k, JSON.stringify(v)); } catch (e) {} },
    del: function (k) { try { sessionStorage.removeItem(k); } catch (e) {} }
  };
  var safeUrl = function (u) { return (typeof u === 'string' && /^(\/(?!\/)|#|https?:\/\/)/i.test(u)) ? u : null; };
  var norm = function (s) {
    s = String(s || '').toLowerCase();
    if (s.normalize) s = s.normalize('NFD').replace(/[̀-ͯ]/g, '');
    return s.replace(/[^a-z0-9]+/g, ' ').trim();
  };
  var esc = function (s) { return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); };
  var focusSafe = function (el) { if (el && el.focus) el.focus({ preventScroll: true }); };

  /* ------------------------------------------------------------------ requêtes */
  function request(url, opt) {
    var ctrl = window.AbortController ? new AbortController() : null;
    var to = setTimeout(function () { if (ctrl) ctrl.abort(); }, opt.timeout || 15000);
    var headers = { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' };
    var init = { method: opt.method || 'POST', credentials: 'same-origin', headers: headers, signal: ctrl ? ctrl.signal : undefined };
    if (opt.json) { headers['Content-Type'] = 'application/json'; init.body = JSON.stringify(opt.json); }
    if (opt.form) { init.body = opt.form; }
    return fetch(url, init).then(function (res) {
      clearTimeout(to);
      return res.text().then(function (t) {
        var j = null; try { j = t ? JSON.parse(t) : null; } catch (e) {}
        return { status: res.status, ok: res.ok, json: j };
      });
    }, function () { clearTimeout(to); return { status: 0, ok: false, json: null }; });
  }

  /* ------------------------------------------------------------------ instance */
  function Assistant(root) {
    var self = this;
    this.root = root;
    this.mode = root.getAttribute('data-mode') || 'page';
    this.ASK = root.getAttribute('data-ask-url') || '/assistant/ask';
    this.CONF = root.getAttribute('data-config-url') || '/assistant/alert-config';
    this.ALERT = root.getAttribute('data-alert-url') || '/guest/openticket';
    this.MAX = parseInt(root.getAttribute('data-max'), 10) || 500;
    this.alertChat = root.getAttribute('data-alert-chat') === '1';
    this.log = $('[data-gn-log]', root);
    this.chips = $('[data-gn-chips]', root);
    this.form = $('[data-gn-form]', root);
    this.input = $('[data-gn-input]', root);
    this.label = $('[data-gn-label]', root);
    this.flowbar = $('[data-gn-flowbar]', root);
    this.alertbar = $('[data-gn-alertbar]', root);
    this.sendBtn = $('[data-gn-send]', root);
    this.help = $('[data-gn-help]', root);
    this.hist = [];
    this.sugg = [];
    this.busy = false;
    this.cfg = null;
    this.F = null;
    this.hintTimer = null;
    this.baseLabel = this.label ? this.label.textContent : '';
    this.basePh = this.input ? this.input.getAttribute('placeholder') : '';
    this.initialChips = $$('[data-gn-ask]', this.chips || document.createElement('div')).map(function (b) { return b.getAttribute('data-gn-ask'); });
    if (!this.log || !this.form || !this.input) return;

    this.form.addEventListener('submit', function (e) { e.preventDefault(); self.submit(); });
    this.input.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' && !e.shiftKey && !e.isComposing) { e.preventDefault(); self.submit(); }
    });
    this.input.addEventListener('input', function () { self.grow(); self.sync(); });
    root.addEventListener('click', function (e) {
      var t = e.target.closest ? e.target.closest('[data-gn-ask],[data-gn-act],[data-gn-reply],[data-gn-copy]') : null;
      if (!t || !root.contains(t)) return;
      if (t.hasAttribute('data-gn-copy')) { self.copy(t); return; }
      if (t.hasAttribute('data-gn-ask')) { self.ask(t.getAttribute('data-gn-ask')); focusSafe(self.input); return; }
      if (t.hasAttribute('data-gn-reply')) { if (!t.disabled) self.reply(t.getAttribute('data-gn-reply')); return; }
      var act = t.getAttribute('data-gn-act');
      if (act === 'clear') self.clear();
      else if (act === 'close' && winCtl) winCtl.close();
      else if (act === 'start-alert') self.startAlert();
      else if (act === 'back') self.back();
      else if (act === 'cancel') self.cancelAlert(true);
    });
    /* « Utiliser le bouton SOS » : ferme la fenêtre, met le vrai bouton au focus (rien n'est déclenché) */
    this.log.addEventListener('click', function (e) {
      var a = e.target.closest ? e.target.closest('a[href="#gn-sos"]') : null;
      if (!a) return;
      e.preventDefault();
      if (winCtl && self.mode === 'window') winCtl.close(true);
      var s = $('#gn-sos'), h = $('#gn-sos-hint');
      if (s) s.focus();
      if (h) { h.hidden = false; clearTimeout(self.hintTimer); self.hintTimer = setTimeout(function () { h.hidden = true; }, 6000); }
    });

    /* Reprise de l'onglet : questions déjà posées, puis brouillon d'alerte éventuel */
    var saved = store.get(KEY);
    if (saved && saved.h && saved.h.length) {
      this.hist = saved.h.filter(function (m) { return m && typeof m.t === 'string'; });
      this.hist.forEach(function (m) { self.render(m); });
      this.setChips(saved.s || []);
    } else {
      this.setChips([]);
    }
    var draft = store.get(DRAFT);
    if (draft && draft.F && draft.F.step) { this.resume(draft.F); }
    this.updateUi();
  }

  var P = Assistant.prototype;

  P.grow = function () {
    var i = this.input;
    if (i.tagName !== 'TEXTAREA') return;
    i.style.height = 'auto';
    i.style.height = Math.min(i.scrollHeight, 224) + 'px';
  };
  /* Bouton d'envoi (icône) : inactif tant que la zone de saisie est vide */
  P.sync = function () { if (this.sendBtn) this.sendBtn.disabled = !this.input.value.trim(); };
  P.scrollDown = function () { this.log.scrollTop = this.log.scrollHeight; };
  P.persist = function () { store.set(KEY, { h: this.hist.filter(function (m) { return !m.f; }).slice(-40), s: this.sugg }); };

  /* ---------- affichage ---------- */
  P.render = function (m) {
    var el = document.createElement('div');
    el.className = 'gh-msg ' + (m.w === 'me' ? 'gh-msg--me' : 'gh-msg--bot') + (m.esc ? ' gh-msg--esc' : '') + (m.card ? ' gh-msg--card' : '');
    if (m.esc) {
      el.setAttribute('role', 'alert');
      var t = document.createElement('strong'); t.className = 'gh-msg__title'; t.textContent = 'Besoin d’aide immédiate ?'; el.appendChild(t);
    }
    if (m.title) { var h = document.createElement('strong'); h.className = 'gh-msg__title gh-msg__title--ok'; h.textContent = m.title; el.appendChild(h); }
    if (m.ref) { var rf = document.createElement('strong'); rf.className = 'gh-ref'; rf.textContent = m.ref; el.appendChild(rf); }
    var tx = document.createElement('span'); tx.className = 'gh-msg__text'; tx.textContent = m.t; el.appendChild(tx);
    if (m.card) {
      var dl = document.createElement('dl'); dl.className = 'gh-card';
      m.card.forEach(function (row) {
        var dt = document.createElement('dt'); dt.textContent = row[0];
        var dd = document.createElement('dd'); dd.textContent = row[1];
        dl.appendChild(dt); dl.appendChild(dd);
      });
      el.appendChild(dl);
    }
    var links = (m.cta || []).filter(function (c) { return c && c.label && safeUrl(c.url); });
    if (links.length) {
      var w = document.createElement('div'); w.className = 'gh-msg__links';
      links.forEach(function (c) { var a = document.createElement('a'); a.href = safeUrl(c.url); a.textContent = c.label; w.appendChild(a); });
      el.appendChild(w);
    }
    if (m.copy && safeUrl(m.copy)) {
      var cb = document.createElement('button'); cb.type = 'button'; cb.className = 'gh-copy'; cb.setAttribute('data-gn-copy', m.copy); cb.textContent = 'Copier le lien';
      el.appendChild(cb);
    }
    this.log.appendChild(el);
    this.scrollDown();
    return el;
  };
  P.say = function (text, o) {
    o = o || {};
    var m = { w: 'bot', t: text, cta: o.cta || [], esc: !!o.esc, card: o.card, title: o.title, ref: o.ref, copy: o.copy, f: !!o.f };
    if (!m.f) { this.hist.push(m); }
    var el = this.render(m);
    if (!m.f) this.persist();
    return el;
  };
  P.me = function (text, flow) {
    var m = { w: 'me', t: text, f: !!flow };
    if (!flow) this.hist.push(m);
    this.render(m);
    if (!flow) this.persist();
  };
  P.typing = function (on) {
    var t = $('[data-gn-typing]', this.log);
    if (on && !t) {
      t = document.createElement('div'); t.setAttribute('data-gn-typing', ''); t.className = 'gh-typing'; t.setAttribute('aria-hidden', 'true');
      t.innerHTML = '<span></span><span></span><span></span>';
      this.log.appendChild(t); this.scrollDown();
    } else if (!on && t) { this.log.removeChild(t); }
    this.log.setAttribute('aria-busy', on ? 'true' : 'false');
  };
  P.chipBtn = function (o) {
    var b = document.createElement('button');
    b.type = 'button';
    b.className = 'gh-sug' + (o.cls ? ' ' + o.cls : '');
    b.textContent = o.label;
    Object.keys(o.attrs || {}).forEach(function (k) { b.setAttribute(k, o.attrs[k]); });
    return b;
  };
  /* Suggestions de la conversation libre (la première propose toujours la création d'alerte guidée) */
  P.setChips = function (list) {
    if (!this.chips) return;
    var self = this;
    var items = (list && list.length ? list : this.initialChips).slice(0, 4);
    this.sugg = list || [];
    this.chips.textContent = '';
    if (this.alertChat && !this.F) {
      this.chips.appendChild(this.chipBtn({ label: 'Créer une alerte avec l’assistant', cls: 'gh-sug--alert', attrs: { 'data-gn-act': 'start-alert' } }));
    }
    items.forEach(function (q) { self.chips.appendChild(self.chipBtn({ label: q, attrs: { 'data-gn-ask': q } })); });
  };
  /* Réponses rapides d'une étape de l'alerte */
  P.setReplies = function (list, focusFirst) {
    if (!this.chips) return;
    var self = this, first = null;
    this.chips.textContent = '';
    (list || []).forEach(function (r) {
      var b = self.chipBtn({ label: r.label, cls: 'gh-sug--reply' + (r.cls ? ' ' + r.cls : ''), attrs: { 'data-gn-reply': r.v } });
      self.chips.appendChild(b);
      if (!first) first = b;
    });
    if (focusFirst && first && this.isVisible()) focusSafe(first);
  };
  P.isVisible = function () { return !this.root.closest('[hidden]'); };
  P.updateUi = function () {
    var inFlow = !!this.F;
    if (this.flowbar) this.flowbar.hidden = !inFlow;
    if (this.alertbar) this.alertbar.hidden = inFlow || !this.alertChat;
    this.root.classList.toggle('is-flow', inFlow);
    if (!inFlow) {
      this.setInput({});
      if (this.chips && !this.chips.children.length) this.setChips([]);
    }
  };
  P.setInput = function (o) {
    var i = this.input;
    i.maxLength = o.max || this.MAX;
    i.setAttribute('inputmode', o.mode || 'text');
    i.setAttribute('autocomplete', o.ac || 'off');
    i.setAttribute('placeholder', o.ph || this.basePh || '');
    i.setAttribute('enterkeyhint', 'send');
    if (this.label) this.label.textContent = o.label || this.baseLabel;
    if (this.help) { this.help.textContent = o.help || ''; this.help.hidden = !o.help; }
    i.value = ''; this.grow(); this.sync();
  };

  /* ---------- conversation libre ---------- */
  P.submit = function () {
    var v = this.input.value;
    if (!v.trim()) return;
    this.input.value = ''; this.grow(); this.sync();
    if (this.F) this.flowInput(v.trim()); else this.ask(v);
    focusSafe(this.input);
  };
  P.localReply = function (q) {
    var n = norm(q);
    if (/danger|suicid|me tuer|menace|couteau|frapp|au secours|mourir|me faire du mal/.test(n)) {
      return { answer: DANGER + ' Vous pouvez aussi créer une alerte pour écrire à l’équipe.',
        cta: [{ label: 'Créer une alerte', url: this.ALERT }, { label: 'Utiliser le bouton SOS', url: '#gn-sos' }], suggestions: [], escalate: true };
    }
    return { answer: 'Je n’arrive pas à joindre l’assistant pour le moment. Vous pouvez réessayer dans un instant, consulter la FAQ, ou créer une alerte pour écrire à l’équipe GANXÔ.',
      cta: [{ label: 'Créer une alerte', url: this.ALERT }, { label: 'Voir la FAQ', url: '/faq' }], suggestions: [], escalate: false };
  };
  P.fetchAnswer = function (q) {
    var self = this;
    return request(this.ASK, { json: { message: q }, timeout: 9000 }).then(function (r) {
      if (r.status === 429) return { answer: 'Vous envoyez beaucoup de messages : patientez une minute avant de continuer. Pour être entendu·e, vous pouvez créer une alerte.', cta: [{ label: 'Créer une alerte', url: self.ALERT }], suggestions: [] };
      if (r.status === 422) return { answer: 'Votre message est vide ou trop long (' + self.MAX + ' caractères au maximum). Pouvez-vous le raccourcir ?', cta: [], suggestions: [] };
      if (r.status === 503 && r.json) return r.json;
      if (!r.ok || !r.json) return self.localReply(q);
      return r.json;
    });
  };
  P.ask = function (q) {
    var self = this;
    q = (q || '').trim().slice(0, this.MAX);
    if (!q || this.busy) return;
    if (this.F) { this.flowInput(q); return; }
    this.busy = true;
    this.me(q);
    this.typing(true);
    var t0 = Date.now();
    this.fetchAnswer(q).then(function (r) {
      var wait = reduce ? 0 : Math.max(0, 500 - (Date.now() - t0));
      setTimeout(function () {
        self.typing(false);
        self.say(String(r.answer || ''), { cta: r.cta || [], esc: !!r.escalate });
        self.setChips(r.suggestions || []);
        self.persist();
        self.busy = false;
        if (r.start_alert && self.alertChat) self.startAlert(true);
      }, wait);
    });
  };
  P.copy = function (btn) {
    var url = btn.getAttribute('data-gn-copy'), done = function (ok) {
      btn.textContent = ok ? 'Lien copié' : 'Copie impossible : sélectionnez le lien ci-dessus';
      setTimeout(function () { btn.textContent = 'Copier le lien'; }, 4000);
    };
    if (navigator.clipboard && navigator.clipboard.writeText) { navigator.clipboard.writeText(url).then(function () { done(true); }, function () { done(false); }); return; }
    try { var t = document.createElement('textarea'); t.value = url; t.style.position = 'fixed'; t.style.opacity = '0'; document.body.appendChild(t); t.select(); var ok = document.execCommand('copy'); document.body.removeChild(t); done(ok); } catch (e) { done(false); }
  };
  P.clear = function () {
    var self = this;
    this.hist = []; this.F = null;
    store.del(KEY); store.del(DRAFT);
    $$('.gh-msg', this.log).forEach(function (m) { if (!m.hasAttribute('data-gn-greeting')) self.log.removeChild(m); });
    this.typing(false); this.busy = false;
    this.setChips([]); this.updateUi(); focusSafe(this.input);
  };

  /* ================================================================== alerte guidée */
  P.loadConfig = function () {
    var self = this;
    if (this.cfg) return Promise.resolve(this.cfg);
    return request(this.CONF, { method: 'GET', timeout: 9000 }).then(function (r) {
      if (!r.ok || !r.json) return null;
      self.cfg = r.json; return r.json;
    });
  };
  P.order = function () {
    return ['role', 'email'].concat(this.cfg && this.cfg.otp && this.F && this.F.d.email ? ['otp'] : []).concat(['category', 'subject', 'description', 'phone', 'gps', 'summary']);
  };
  P.saveDraft = function () {
    if (!this.F) return;
    var F = this.F;
    store.set(DRAFT, { F: { step: F.step === 'otp' ? 'email' : F.step, d: F.d, stack: F.stack, ret: F.ret } });
  };
  P.wipe = function () { this.F = null; store.del(DRAFT); };

  P.startAlert = function (fromIntent) {
    var self = this;
    if (this.F || this.busy) { if (this.F) focusSafe(this.input); return; }
    if (this.mode === 'window' && winCtl && !winCtl.isOpen()) winCtl.open({ noFocus: true });
    this.busy = true;
    if (!fromIntent) this.me('Créer une alerte avec l’assistant');
    this.typing(true);
    this.loadConfig().then(function (cfg) {
      self.typing(false); self.busy = false;
      if (!cfg) {
        self.say('Je n’arrive pas à préparer l’alerte pour le moment. Vous pouvez réessayer dans un instant ou utiliser le formulaire.', { cta: [{ label: 'Ouvrir le formulaire d’alerte', url: self.ALERT }] });
        return;
      }
      if (!cfg.ok) {
        var msg = cfg.reason === 'disabled'
          ? 'La création d’alerte sans compte est momentanément désactivée. Vous pouvez vous connecter pour écrire à l’équipe GANXÔ.'
          : 'Cette alerte demande des vérifications que je ne peux pas faire ici. Le formulaire d’alerte vous guidera.';
        self.say(msg, { cta: [{ label: cfg.reason === 'disabled' ? 'Se connecter' : 'Ouvrir le formulaire d’alerte', url: cfg.reason === 'disabled' ? cfg.urls.login : cfg.urls.form }] });
        return;
      }
      self.F = { step: 'role', d: {}, stack: [], ret: false, verified: false };
      self.say('Très bien, je vous guide pas à pas. À chaque étape, vous pouvez revenir en arrière ou annuler : rien n’est envoyé avant que vous ayez relu le résumé et confirmé.', { f: true });
      self.say(DANGER, { f: true });
      self.updateUi();
      self.goto('role');
    });
  };

  P.resume = function (saved) {
    var self = this;
    this.busy = true;
    this.loadConfig().then(function (cfg) {
      self.busy = false;
      if (!cfg || !cfg.ok) { store.del(DRAFT); return; }
      self.F = { step: saved.step, d: saved.d || {}, stack: saved.stack || [], ret: !!saved.ret, verified: !!(saved.d && saved.d.otp), verifiedEmail: saved.d && saved.d.email };
      self.say('Reprenons votre alerte là où vous en étiez. Vous pouvez la modifier ou l’annuler à tout moment.', { f: true });
      self.updateUi();
      self.goto(self.F.step, { keepStack: true });
    });
  };

  P.goto = function (step, o) {
    o = o || {};
    var F = this.F;
    if (!o.keepStack && !o.back && F.step && F.step !== step && F.step !== 'summary') { if (F.stack[F.stack.length - 1] !== F.step) F.stack.push(F.step); }
    if (!o.keepStack && !o.back && F.step === 'summary' && step !== 'summary') { F.stack.push('summary'); }
    F.step = step;
    this.saveDraft();
    this['p_' + step]();
  };
  P.next = function () {
    var F = this.F;
    if (F.ret) { F.ret = false; return this.goto('summary'); }
    var o = this.order(), i = o.indexOf(F.step);
    var n = o[i + 1];
    if (n === 'otp' && !F.d.email) n = o[i + 2];
    if (n === 'otp') {
      if (F.verified && F.verifiedEmail === F.d.email) n = o[i + 2];
      else return this.sendCode(false);
    }
    this.goto(n);
  };
  P.back = function () {
    var F = this.F;
    if (!F) return;
    var prev = F.stack.pop();
    if (!prev) { this.say('Vous êtes au tout début. Vous pouvez annuler l’alerte avec le bouton « Annuler ».', { f: true }); return; }
    if (prev === 'otp') prev = F.stack.pop() || 'email';
    F.ret = false;
    this.me('Revenir en arrière', true);
    this.goto(prev, { back: true });
  };
  P.cancelAlert = function (explicit) {
    if (!this.F) return;
    this.wipe();
    this.say('L’alerte est annulée : rien n’a été envoyé et votre brouillon est effacé de cet onglet.' + (explicit ? ' Vous pouvez recommencer quand vous voulez.' : ''), { f: true });
    this.updateUi(); this.setChips([]);
  };
  P.ask1 = function (text, replies, o) {
    this.say(text, { f: true });
    this.setReplies(replies || [], (o || {}).focus !== false && (replies || []).length > 0);
  };

  /* ----- étapes ----- */
  P.p_role = function () {
    this.setInput({ ph: 'Ou écrivez votre réponse', label: 'Votre réponse (ou choisissez un bouton)' });
    this.ask1('D’abord, qui transmet cette alerte ?', [
      { v: 'role:victim', label: ROLES.victim.btn }, { v: 'role:witness', label: ROLES.witness.btn }, { v: 'role:unknown', label: ROLES.unknown.btn }
    ]);
  };
  P.p_email = function () {
    this.setInput({ mode: 'email', ac: 'email', max: 254, ph: 'vous@exemple.fr', label: 'Votre adresse e-mail' });
    this.ask1('Avez-vous une adresse e-mail pour le suivi ? Elle est utile pour vous confirmer la réception de l’alerte et échanger avec l’équipe GANXÔ, mais elle n’est pas obligatoire. Si vous n’en avez pas, choisissez un bouton ci-dessous : l’alerte sera envoyée quand même.' + (this.cfg.otp ? ' Si vous en indiquez une, nous vous enverrons un code de vérification à 6 chiffres.' : ''),
      (this.F.d.email ? [{ v: 'keep', label: 'Garder : ' + this.F.d.email }] : []).concat([{ v: 'noemail', label: 'Je n’ai pas d’adresse e-mail' }, { v: 'noemail', label: 'Passer' }]), { focus: false });
    focusSafe(this.input);
  };
  P.p_otp = function () {
    this.setInput({ mode: 'numeric', ac: 'one-time-code', max: 6, ph: 'Code à 6 chiffres', label: 'Code de vérification reçu par e-mail' });
    this.ask1('Un code à 6 chiffres vient d’être envoyé à ' + this.F.d.email + '. Saisissez-le ici pour confirmer que cette adresse est bien la vôtre.',
      [{ v: 'otp:resend', label: 'Renvoyer le code' }, { v: 'otp:change', label: 'Changer d’adresse e-mail' }], { focus: false });
    focusSafe(this.input);
  };
  P.p_category = function () {
    this.setInput({ ph: 'Ou écrivez la catégorie', label: 'Catégorie (ou choisissez un bouton)' });
    var cur = this.F.d.catName;
    this.ask1('De quel type de situation s’agit-il ? Choisissez la catégorie la plus proche.',
      this.cfg.categories.map(function (c) { return { v: 'cat:' + c.id, label: c.name }; }).concat(cur ? [{ v: 'keep', label: 'Garder : ' + cur }] : []));
  };
  P.p_subject = function () {
    var max = this.cfg.subject_max;
    this.setInput({ max: max, ph: 'Titre court de l’alerte', label: 'Sujet de l’alerte (' + max + ' caractères au maximum)' });
    this.ask1('Donnez un titre court à l’alerte (' + max + ' caractères au maximum), par exemple « Harcèlement au travail ».', this.F.d.subject ? [{ v: 'keep', label: 'Garder : ' + this.F.d.subject.slice(0, 30) + (this.F.d.subject.length > 30 ? '…' : '') }] : [], { focus: false });
    focusSafe(this.input);
  };
  P.p_description = function () {
    this.setInput({ max: 1500, ph: 'Écrivez ici…', label: 'Description de la situation', help: 'Écrivez librement, vous pouvez envoyer plusieurs messages.' });
    var has = (this.F.d.desc || '').length;
    this.ask1('Racontez ce qui s’est passé avec vos mots (au moins ' + this.cfg.desc_min + ' caractères). Vous pouvez écrire en plusieurs messages ; quand vous avez fini, appuyez sur « C’est terminé ».' + (has ? ' Votre description actuelle est conservée : vous pouvez la compléter ou la remplacer.' : ''),
      has ? [{ v: 'desc:done', label: 'C’est terminé' }, { v: 'desc:reset', label: 'Tout réécrire' }] : [], { focus: false });
    focusSafe(this.input);
  };
  P.p_phone = function () {
    this.setInput({ mode: 'tel', ac: 'tel', max: 25, ph: 'Numéro de téléphone', label: 'Numéro de téléphone (facultatif)' });
    this.ask1('Souhaitez-vous laisser un numéro de téléphone pour que l’équipe puisse vous joindre ? C’est facultatif.' + (this.F.d.email ? '' : ' Sans adresse e-mail, c’est le seul moyen pour l’équipe de vous contacter en dehors de la page de suivi.'),
      [{ v: 'skip', label: 'Passer cette étape' }].concat(this.F.d.phone ? [{ v: 'keep', label: 'Garder : ' + this.F.d.phone }] : []), { focus: false });
    focusSafe(this.input);
  };
  P.p_gps = function () {
    this.setInput({ ph: 'Ou écrivez votre réponse', label: 'Position (choisissez un bouton)' });
    if (!('geolocation' in navigator)) {
      this.say('Votre navigateur ne permet pas de partager la position : on passe cette étape.', { f: true });
      return this.next();
    }
    this.ask1('Souhaitez-vous partager votre position approximative avec l’équipe GANXÔ ? Votre navigateur vous demandera l’autorisation. C’est facultatif.' + (this.F.d.gps ? ' Une position est déjà enregistrée.' : ''),
      [{ v: 'gps:share', label: 'Partager ma position' }, { v: 'gps:no', label: 'Non merci' }].concat(this.F.d.gps ? [{ v: 'keep', label: 'Garder la position' }] : []));
  };
  P.roleLabel = function () { return (ROLES[this.F.d.role] || ROLES.unknown).label; };
  P.gpsText = function (g) {
    var lat = g.lat.toFixed(6), lon = g.lon.toFixed(6);
    var map = 'https://www.openstreetmap.org/?mlat=' + lat + '&mlon=' + lon + '#map=17/' + lat + '/' + lon;
    return { text: 'Position GPS : ' + lat + ', ' + lon + (g.acc ? ' (précision environ ' + g.acc + ' m)' : '') + ' — ' + map + ' — relevée le ' + new Date(g.ts).toLocaleString('fr-FR') + '.', map: map, lat: lat, lon: lon };
  };
  P.p_summary = function () {
    var d = this.F.d;
    this.setInput({ ph: 'Ou écrivez votre réponse', label: 'Réponse (choisissez un bouton)' });
    var rows = [['Transmise par', this.roleLabel()], ['E-mail', d.email || 'non renseigné'], ['Catégorie', d.catName], ['Sujet', d.subject], ['Description', d.desc],
      ['Téléphone', d.phone || 'Non indiqué'], ['Position', d.gps ? this.gpsText(d.gps).lat + ', ' + this.gpsText(d.gps).lon : 'Non partagée']];
    this.say('Voici le résumé. Relisez-le : en envoyant, vous confirmez la véracité des faits. L’alerte est transmise à l’équipe GANXÔ.', { f: true, card: rows });
    this.setReplies([{ v: 'send', label: 'Envoyer l’alerte', cls: 'gh-sug--go' }, { v: 'edit', label: 'Modifier' }], true);
  };
  P.p_edit = function () {
    this.F.step = 'summary';
    this.ask1('Que souhaitez-vous modifier ?', [
      { v: 'goto:role', label: 'Qui transmet' }, { v: 'goto:email', label: 'E-mail' }, { v: 'goto:category', label: 'Catégorie' }, { v: 'goto:subject', label: 'Sujet' },
      { v: 'goto:description', label: 'Description' }, { v: 'goto:phone', label: 'Téléphone' }, { v: 'goto:gps', label: 'Position' }, { v: 'goto:summary', label: 'Retour au résumé' }
    ]);
  };

  /* ----- réponses ----- */
  P.reply = function (v) {
    var F = this.F, self = this;
    if (!F || this.busy) return;
    var label = (function () { var b = self.chips.querySelector('[data-gn-reply="' + v + '"]'); return b ? b.textContent : v; })();
    if (v !== 'send') this.me(label, true);
    var k = v.split(':'), a = k[0], b = k.slice(1).join(':');
    if (a === 'role') { F.d.role = b; return this.next(); }
    if (a === 'cat') { var c = this.cfg.categories.filter(function (x) { return String(x.id) === b; })[0]; if (c) { F.d.cat = c.id; F.d.catName = c.name; } return this.next(); }
    if (a === 'noemail') { F.d.email = ''; F.verified = false; return this.next(); }
    if (a === 'keep' || a === 'skip') {
      if (a === 'skip') { F.d.phone = ''; }
      return this.next();
    }
    if (a === 'otp') {
      if (b === 'change') { F.verified = false; return this.goto('email', { back: true }); }
      return this.sendCode(true);
    }
    if (a === 'desc') {
      if (b === 'reset') { F.d.desc = ''; this.say('D’accord, on repart d’une page blanche. Écrivez votre description.', { f: true }); this.setReplies([]); return; }
      return this.finishDesc();
    }
    if (a === 'gps') {
      if (b === 'no') { F.d.gps = null; return this.next(); }
      return this.getGps();
    }
    if (v === 'send') return this.send();
    if (v === 'edit') return this.p_edit();
    if (a === 'goto') {
      if (b === 'summary') return this.goto('summary');
      F.ret = true;
      return this.goto(b);
    }
  };

  P.flowInput = function (text) {
    var F = this.F, self = this, n = norm(text);
    if (/^(annuler|annule|stop|quitter l alerte|abandonner)$/.test(n)) { this.me(text, true); return this.cancelAlert(true); }
    if (/^(retour|revenir|revenir en arriere|precedent)$/.test(n)) return this.back();
    if (this.busy) return;
    var d = F.d;
    switch (F.step) {
      case 'role':
        this.me(text, true);
        var r = /victime|moi/.test(n) ? 'victim' : /temoin|proche|ami|amie|famille/.test(n) ? 'witness' : /prefere|ne pas|anonyme|autre/.test(n) ? 'unknown' : '';
        if (!r) { this.say('Je n’ai pas compris. Choisissez l’un des boutons : victime, témoin ou proche, ou « Je préfère ne pas le dire ».', { f: true }); return; }
        d.role = r; return this.next();
      case 'email':
        this.me(text, true);
        if (/^(non|aucune?|passer|pas d adresse|pas de mail|pas d email|je n ai pas|sans (e ?mail|adresse))/.test(n)) { d.email = ''; F.verified = false; return this.next(); }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(text) || text.length > 254) { this.say('Cette adresse ne semble pas valide. Elle ressemble à « nom@exemple.fr ». Pouvez-vous la vérifier ?', { f: true }); return; }
        var changed = d.email !== text;
        d.email = text;
        if (changed) { F.verified = false; }
        return this.next();
      case 'otp':
        this.me('••••••', true);
        if (!/^\d{6}$/.test(text)) { this.say('Le code contient 6 chiffres. Vérifiez votre e-mail (et vos courriers indésirables).', { f: true }); return; }
        return this.checkCode(text);
      case 'category':
        this.me(text, true);
        var cat = this.cfg.categories.filter(function (c) { var m = norm(c.name); return m === n || (n.length > 3 && (m.indexOf(n) > -1 || n.indexOf(m) > -1)); })[0];
        if (!cat) { this.say('Je ne trouve pas cette catégorie. Choisissez l’un des boutons.', { f: true }); return; }
        d.cat = cat.id; d.catName = cat.name; return this.next();
      case 'subject':
        this.me(text, true);
        if (text.length > this.cfg.subject_max) { this.say('Ce titre est trop long (' + text.length + ' caractères, ' + this.cfg.subject_max + ' au maximum). Pouvez-vous le raccourcir ?', { f: true }); return; }
        if (text.length < 3) { this.say('Le titre est trop court. Donnez quelques mots.', { f: true }); return; }
        d.subject = text;
        return this.checkUrgent(text).then(function () { self.next(); });
      case 'description':
        this.me(text, true);
        if ((d.desc || '').length + text.length > this.cfg.desc_max) { this.say('La description est trop longue (' + this.cfg.desc_max + ' caractères au maximum). Résumez l’essentiel : l’équipe vous posera des questions.', { f: true }); return; }
        d.desc = (d.desc ? d.desc + '\n' : '') + text;
        this.saveDraft();
        return this.checkUrgent(text).then(function () {
          var len = d.desc.length;
          if (len >= self.cfg.desc_min) {
            self.say('Noté (' + len + ' caractères). Vous pouvez continuer à écrire, ou appuyer sur « C’est terminé ».', { f: true });
            self.setReplies([{ v: 'desc:done', label: 'C’est terminé' }, { v: 'desc:reset', label: 'Tout réécrire' }], false);
          } else {
            self.say('Noté. Il faut encore un peu de détails (' + len + ' sur ' + self.cfg.desc_min + ' caractères au minimum).', { f: true });
            self.setReplies([], false);
          }
        });
      case 'phone':
        this.me(text, true);
        if (!/^\+?[0-9 ().\-]{6,25}$/.test(text) || text.replace(/\D/g, '').length < 6) { this.say('Ce numéro ne semble pas valide (chiffres, espaces, « + » au début). Vous pouvez aussi passer cette étape.', { f: true }); return; }
        d.phone = text; return this.next();
      default:
        this.me(text, true);
        this.say('Utilisez les boutons ci-dessus, ou écrivez « annuler » pour arrêter.', { f: true });
    }
  };

  /* Détection d'urgence pendant le flux : bannière sans casser le déroulé (aucune trace côté serveur) */
  P.checkUrgent = function (text) {
    var self = this;
    this.busy = true; this.typing(true);
    return request(this.ASK, { json: { message: text, check_escalation: true }, timeout: 6000 }).then(function (r) {
      self.typing(false); self.busy = false;
      if (r.ok && r.json && r.json.escalate) { self.say(String(r.json.answer || DANGER), { esc: true, cta: r.json.cta || [], f: true }); }
    });
  };
  P.finishDesc = function () {
    var F = this.F;
    if ((F.d.desc || '').length < this.cfg.desc_min) {
      this.say('Il faut au moins ' + this.cfg.desc_min + ' caractères : ajoutez quelques détails.', { f: true });
      this.setReplies([], false); return;
    }
    this.next();
  };

  P.sendCode = function (resend) {
    var self = this, F = this.F;
    this.busy = true; this.typing(true);
    return request(this.cfg.urls.email_validate, { json: { email: F.d.email } }).then(function (r) {
      self.typing(false); self.busy = false;
      var j = r.json || {};
      if (j.message === 'createverifyotp') {
        if (resend) self.say('Un nouveau code vient d’être envoyé.', { f: true });
        return self.goto('otp', { keepStack: resend });
      }
      if (j.message === 'domainblock') { self.say('Cette adresse n’est pas acceptée. Essayez-en une autre.', { f: true }); return self.p_email(); }
      if (r.status === 422) { self.say('Cette adresse n’est pas acceptée (adresse temporaire ou invalide). Essayez-en une autre.', { f: true }); return self.p_email(); }
      if (r.status === 429) { self.say('Trop d’essais : patientez une minute puis réessayez.', { f: true }); return self.p_email(); }
      self.say('Je n’ai pas pu envoyer le code pour le moment. Vérifiez l’adresse et réessayez dans un instant.', { f: true });
      self.p_email();
    });
  };
  P.checkCode = function (code) {
    var self = this, F = this.F;
    this.busy = true; this.typing(true);
    return request(this.cfg.urls.verify_otp, { json: { otpvalue: code } }).then(function (r) {
      self.typing(false); self.busy = false;
      var j = r.json || {};
      if (j.success) { F.verified = true; F.verifiedEmail = F.d.email; F.d.otp = code; self.say('Adresse vérifiée, merci.', { f: true }); return self.next(); }
      self.say('Ce code n’est pas valide. Vérifiez-le ou demandez un nouveau code.', { f: true });
    });
  };
  P.getGps = function () {
    var self = this, F = this.F;
    this.say('Recherche de votre position… Autorisez la localisation si votre navigateur le demande.', { f: true });
    this.setReplies([]);
    navigator.geolocation.getCurrentPosition(function (pos) {
      F.d.gps = { lat: pos.coords.latitude, lon: pos.coords.longitude, acc: Math.round(pos.coords.accuracy || 0), ts: Date.now() };
      self.say('Position enregistrée. Vous pourrez la retirer au résumé.', { f: true });
      self.next();
    }, function (err) {
      self.say(err && err.code === 1 ? 'L’accès à la position a été refusé : on continue sans position.' : 'Position indisponible pour le moment : on continue sans position.', { f: true });
      F.d.gps = null; self.next();
    }, { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 });
  };

  /* ----- envoi : mêmes champs et mêmes routes que le formulaire d'alerte ----- */
  P.buildMessage = function () {
    var d = this.F.d, cfg = this.cfg;
    var paragraphs = String(d.desc).split(/\n+/).map(function (l) { return '<p>' + esc(l) + '</p>'; }).join('');
    var html = '<p><strong>Alerte transmise par : ' + esc(this.roleLabel()) + '</strong> (créée avec l’assistant GANXÔ)</p>' + paragraphs;
    if (d.phone && !cfg.phone_field) html += '<p>Téléphone : ' + esc(d.phone) + '</p>';
    if (d.gps) { var g = this.gpsText(d.gps); html += '<p>' + esc(g.text.split(' — ')[0]) + ' — <a href="' + esc(g.map) + '">Voir sur OpenStreetMap</a> — ' + esc(g.text.split(' — ')[2] || '') + '</p>'; }
    return html;
  };
  P.send = function () {
    var self = this, F = this.F, d = F.d, cfg = this.cfg;
    this.busy = true;
    this.setReplies([]);
    this.me('Envoyer l’alerte', true);
    this.typing(true);
    var fd = new FormData();
    fd.set('subject', d.subject);
    fd.set('category', String(d.cat));
    fd.set('message', this.buildMessage());
    fd.set('agree_terms', 'agreed');
    var noEmail = !d.email, url = cfg.urls.send;
    if (noEmail) {
      if (!d.ph) { var a8 = new Uint8Array(6); (window.crypto || window.msCrypto).getRandomValues(a8); d.ph = Array.prototype.map.call(a8, function (x) { return ('0' + x.toString(16)).slice(-2); }).join(''); this.saveDraft(); }
      fd.set('email', 'alerte-' + d.ph + '@anonyme.invalid'); url = cfg.urls.send_noemail || cfg.urls.send;
    } else if (cfg.otp) fd.set('verifyotp', d.otp || ''); else fd.set('email', d.email);
    if (d.phone && cfg.phone_field) fd.set('custom_' + cfg.phone_field, d.phone);
    request(url, { form: fd, timeout: 30000 }).then(function (r) {
      self.typing(false); self.busy = false;
      var j = r.json || {}, e = j.errors || {};
      if (j.guest === 'pass' && j.data && j.data.id) { return self.done(j); }
      if (j.guest === 'invaildotp') { self.say('Le code de vérification a expiré. Demandons-en un nouveau.', { f: true }); F.verified = false; return self.sendCode(true); }
      if (cfg.otp && j.warning) { self.say('La vérification de votre adresse n’est plus valable. Recommençons avec un nouveau code.', { f: true }); F.verified = false; return self.sendCode(true); }
      if (j.email === 'already') {
        self.say('Cette adresse est déjà liée à un compte. Connectez-vous pour créer l’alerte, ou utilisez une autre adresse.', { f: true, cta: [{ label: 'Se connecter', url: cfg.urls.login }] });
        F.ret = true; F.verified = false; return self.goto('email', { back: true });
      }
      if (noEmail && (j.message === 'domainblock' || e.email || j.email === 'already')) { self.say('L’envoi sans adresse e-mail n’est pas possible pour le moment. Indiquez une adresse e-mail, ou utilisez le formulaire d’alerte.', { f: true, cta: [{ label: 'Ouvrir le formulaire d’alerte', url: cfg.urls.form }] }); F.ret = true; return self.goto('email', { back: true }); }
      if (j.message === 'domainblock' || e.email) { self.say('Cette adresse e-mail n’est pas acceptée. Essayez-en une autre.', { f: true }); F.ret = true; F.verified = false; return self.goto('email', { back: true }); }
      if (e.category) { self.say('La catégorie n’est pas valide : choisissons-la à nouveau.', { f: true }); F.ret = true; return self.goto('category', { back: true }); }
      if (e.subject) { self.say('Le sujet n’est pas valide : écrivez-le à nouveau.', { f: true }); F.ret = true; return self.goto('subject', { back: true }); }
      if (e.message) { self.say('La description n’est pas valide : reprenons-la.', { f: true }); F.ret = true; F.d.desc = ''; return self.goto('description', { back: true }); }
      if (e.verifyotp) { self.say('La vérification de l’adresse a expiré. Recommençons avec un nouveau code.', { f: true }); F.verified = false; return self.sendCode(true); }
      var text = r.status === 419
        ? 'La session a expiré. Rechargez la page : vos réponses sont conservées dans cet onglet, vous pourrez reprendre.'
        : r.status === 429 ? 'Trop d’envois en peu de temps : patientez une minute puis réessayez.'
          : 'L’alerte n’a pas pu être envoyée pour le moment. Vos réponses sont conservées : vous pouvez réessayer, ou utiliser le formulaire d’alerte.';
      self.say(text, { f: true, cta: [{ label: 'Ouvrir le formulaire d’alerte', url: cfg.urls.form }] });
      self.setReplies([{ v: 'send', label: 'Réessayer l’envoi', cls: 'gh-sug--go' }, { v: 'edit', label: 'Modifier' }], true);
    });
  };
  P.done = function (j) {
    var cfg = this.cfg, ref = (j.data && j.data.ticket_id) || '', d = this.F.d, email = d.email, hasPhone = !!d.phone;
    var link = cfg.urls.view + '/' + j.data.id;
    this.wipe();
    if (email) {
      this.say('Votre alerte a bien été transmise à l’équipe GANXÔ. Référence : ' + ref + '. Notez-la : elle permet de retrouver votre alerte. Un e-mail de suivi est envoyé à ' + email + '. Vous pouvez aussi consulter votre alerte maintenant.',
        { f: true, title: 'Alerte transmise', ref: ref, cta: [{ label: 'Voir mon alerte', url: link }] });
    } else {
      this.say('Votre alerte a bien été transmise à l’équipe GANXÔ. Aucun e-mail ne sera envoyé : la référence et le lien ci-dessous sont le seul moyen de suivre votre alerte. Notez la référence ou copiez le lien, et gardez-les en lieu sûr.' + (hasPhone ? '' : ' Vous n’avez pas laissé de numéro de téléphone : l’équipe ne pourra vous répondre que via la page de suivi.'),
        { f: true, title: 'Alerte transmise', ref: ref, cta: [{ label: 'Voir mon alerte', url: link }], copy: link });
    }
    this.say(DANGER, { f: true });
    this.updateUi();
    this.setChips([]);
    try { localStorage.removeItem('guestsubject'); localStorage.removeItem('guestmessage'); } catch (e) {}
  };

  /* ------------------------------------------------------------------ fenêtre flottante */
  function Win(el, fab) {
    var self = this;
    this.el = el; this.fab = fab; this.last = null;
    this.mq = window.matchMedia ? window.matchMedia('(max-width: 640px)') : { matches: false };
    el.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !e.defaultPrevented) { e.preventDefault(); self.close(); return; }
      if (e.key === 'Tab' && self.mq.matches) { self.trap(e); }
    });
    fab.addEventListener('click', function () { self.open({ trigger: fab }); });
  }
  Win.prototype.isOpen = function () { return !this.el.hidden; };
  Win.prototype.open = function (o) {
    o = o || {};
    if (this.isOpen()) { if (!o.noFocus) this.focusIn(); return; }
    this.last = o.trigger || document.activeElement;
    this.el.hidden = false;
    this.fab.hidden = true;
    this.fab.setAttribute('aria-expanded', 'true');
    this.el.setAttribute('aria-modal', this.mq.matches ? 'true' : 'false');
    document.documentElement.classList.add('gn-asst-open');
    store.set(OPEN, 1);
    var inst = instances[0];
    if (inst) inst.scrollDown();
    if (!o.noFocus && !o.restore) this.focusIn();
  };
  Win.prototype.focusIn = function () {
    var inst = instances[0];
    var target = $('.gh-sug--reply', this.el);
    var go = function () { focusSafe(target || (inst && inst.input) || $('button', this.el)); }.bind(this);
    if (reduce) go(); else setTimeout(go, 30);
  };
  Win.prototype.close = function (skipFocus) {
    if (!this.isOpen()) return;
    this.el.hidden = true;
    this.fab.hidden = false;
    this.fab.setAttribute('aria-expanded', 'false');
    document.documentElement.classList.remove('gn-asst-open');
    store.del(OPEN);
    if (skipFocus === true) return;
    var back = this.last && document.contains(this.last) && !this.last.closest('[hidden]') ? this.last : this.fab;
    focusSafe(back);
  };
  Win.prototype.trap = function (e) {
    var f = $$('button:not([disabled]), a[href], textarea:not([disabled]), input:not([disabled]), [tabindex]:not([tabindex="-1"])', this.el).filter(function (x) { return x.offsetParent !== null; });
    if (!f.length) return;
    var first = f[0], last = f[f.length - 1];
    if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
    else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
  };

  /* ------------------------------------------------------------------ démarrage */
  function init() {
    $$('[data-gn-assistant]').forEach(function (root) { instances.push(new Assistant(root)); });
    var inst = instances[0];
    var winEl = $('[data-gn-window]'), fab = $('[data-gn-fab]');
    if (winEl && fab) winCtl = new Win(winEl, fab);
    if (!inst) return;

    var openChat = function (trigger, then) {
      if (winCtl) { winCtl.open({ trigger: trigger }); }
      else { focusSafe(inst.input); inst.root.scrollIntoView && inst.root.scrollIntoView({ block: 'center', behavior: reduce ? 'auto' : 'smooth' }); }
      if (then) then();
    };
    $$('[data-gn-open-chat]').forEach(function (a) {
      if (winCtl) a.setAttribute('aria-haspopup', 'dialog');
      a.addEventListener('click', function (e) {
        if (e.defaultPrevented || e.ctrlKey || e.metaKey || e.shiftKey || e.button > 0) return;
        e.preventDefault();
        openChat(a, a.hasAttribute('data-gn-start-alert') ? function () { inst.startAlert(); } : null);
      });
    });
    $$('[data-gn-ask]').forEach(function (b) {
      if (inst.root.contains(b)) return;
      b.addEventListener('click', function () { openChat(b, function () { inst.ask(b.getAttribute('data-gn-ask')); }); });
    });

    var q = (location.search || '');
    if (/[?&]alerte=1\b/.test(q) && inst.alertChat) {
      if (winCtl) winCtl.open({ noFocus: true }); else if (inst.root.scrollIntoView) inst.root.scrollIntoView({ block: 'center' });
      setTimeout(function () { inst.startAlert(); }, 50);
    } else if (winCtl && store.get(OPEN)) {
      winCtl.open({ restore: true });
    }
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
