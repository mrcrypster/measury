function search_hover(el) {
  qs('#search-results li.hover', function(li) { li.classList.remove('hover'); });
  el.classList.add('hover');
}

function search_go(li) {
  location = '/m?f=' + li.dataset.f + '&m=' + li.dataset.m;
}


function coverage() {
  qs('#coverage')[0].classList.add('on');
}


function alerts() {
  qs('#alerts')[0].classList.add('on');
}


var last_q = '';
on('#search', 'keyup', function() {
  q = this.value;
  if ( q == last_q ) {
    return;
  }
  
  phpy('/m/search', {q: q}, function(r) {
    last_q = q;
    qs('#search-results li:first-child', function(li) {
      li.classList.add('hover');
    });
  });
});

on('#search', 'focus', function() {
  this.classList.add('focus');
  qs('#search-results')[0].classList.add('on');
});

on('#search', 'keydown', function(e) {
  if ( e.keyCode == 13 ) {
    qs('#search-results li.hover', function(li) {
      search_go(li);
    });
    
    e.stopPropagation();
    e.preventDefault();
  }
  else if ( e.keyCode == 40 ) {
    qs('#search-results li.hover', function(li) {
      if ( li.nextElementSibling ) {
        li.classList.remove('hover');
        li.nextElementSibling.classList.add('hover');
      }
    });
    
    e.stopPropagation();
    e.preventDefault();
  }
  else if ( e.keyCode == 38 ) {
    qs('#search-results li.hover', function(li) {
      if ( li.previousElementSibling ) {
        li.classList.remove('hover');
        li.previousElementSibling.classList.add('hover');
      }
    });
    
    e.stopPropagation();
    e.preventDefault();
  }
});

document.addEventListener('click', function(e) {
  if ( qs('#search.focus').length >= 1 ) {
    if ( !e.target.matches('#search, #search *, #search-results *') ) {
      qs('#search.focus')[0].classList.remove('focus');
      qs('#search-results')[0].classList.remove('on');
    }
  }
  
  if ( qs('#coverage.on').length >= 1 ) {
    if ( !e.target.matches('#coverage, #coverage *') ) {
      qs('#coverage')[0].classList.remove('on');
    }
  }
  
  if ( qs('#alerts.on').length >= 1 ) {
    if ( !e.target.matches('#alerts, #alerts *') ) {
      qs('#alerts')[0].classList.remove('on');
    }
  }
});