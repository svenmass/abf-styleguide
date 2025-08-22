/* Palette JS: Copy HEX & Step switching */
(function(){
  function qs(scope, sel){ return scope.querySelector(sel); }
  function qsa(scope, sel){ return Array.prototype.slice.call(scope.querySelectorAll(sel)); }

  // Copy buttons
  qsa(document, '.sg-copy-hex').forEach(function(btn){
    btn.addEventListener('click', function(){
      var hex = btn.getAttribute('data-hex');
      if (!hex) return;
      var finish = function(){
        btn.classList.add('is-copied');
        var old = btn.querySelector('.sg-copy-text');
        if (old) { old.dataset.old = old.textContent; old.textContent = 'Kopiert!'; }
        setTimeout(function(){
          btn.classList.remove('is-copied');
          if (old && old.dataset.old) { old.textContent = old.dataset.old; }
        }, 1200);
      };
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(hex).then(finish).catch(finish);
      } else {
        finish();
      }
    });
  });

  // Step selection
  qsa(document, '.sg-color-section').forEach(function(section){
    var preview = qs(section, '.sg-col-preview');
    var hexEl = qs(section, '.js-hex');
    var rgbEl = qs(section, '.js-rgb');
    var cmykEl = qs(section, '.js-cmyk');
    var stepLabel = qs(section, '.js-step-label');
    var textColorEl = qs(section, '.js-text-color');
    var contrastEl = qs(section, '.js-contrast');
    var copyBtn = qs(section, '.sg-copy-hex');

    qsa(section, '.sg-step').forEach(function(stepBtn){
      stepBtn.addEventListener('click', function(){
        // Visuelles Selected-State
        qsa(section, '.sg-step').forEach(function(b){ b.setAttribute('aria-selected','false'); });
        stepBtn.setAttribute('aria-selected','true');

        var hex = stepBtn.getAttribute('data-hex');
        var r = stepBtn.getAttribute('data-r');
        var g = stepBtn.getAttribute('data-g');
        var b = stepBtn.getAttribute('data-b');
        var cmyk = stepBtn.getAttribute('data-cmyk');
        var tcolor = stepBtn.getAttribute('data-text-color');
        var contrast = stepBtn.getAttribute('data-contrast');

        if (hex) {
          preview.style.backgroundColor = hex;
          hexEl.textContent = hex;
          if (copyBtn) copyBtn.setAttribute('data-hex', hex);
        }
        if (r && g && b) {
          rgbEl.textContent = [r,g,b].join(' | ');
        }
        if (cmyk) {
          cmykEl.textContent = cmyk;
        }
        stepLabel.textContent = stepBtn.getAttribute('data-step') + '%';
        textColorEl.textContent = (tcolor === 'white') ? 'Weiß' : 'Schwarz';
        if (contrast) {
          contrastEl.textContent = contrast + ' : 1';
        }

        // Update Textfarbe der Preview-Fläche
        if (preview) {
          preview.classList.remove('has-text-white','has-text-black');
          preview.classList.add(tcolor === 'white' ? 'has-text-white' : 'has-text-black');
        }
      });
    });
  });

  // Smooth scroll fallback
  if ('scrollBehavior' in document.documentElement.style === false) {
    document.addEventListener('click', function(e){
      var a = e.target.closest('a[href^="#"]');
      if (!a) return;
      var id = a.getAttribute('href').slice(1);
      var el = document.getElementById(id);
      if (!el) return;
      e.preventDefault();
      var y = el.getBoundingClientRect().top + window.pageYOffset - 10;
      window.scrollTo({ top: y, behavior: 'smooth' });
    });
  }
})();


