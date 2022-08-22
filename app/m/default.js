on('.drop', 'click', function(e) {
  if ( !confirm('Really???') ) {
    e.stopPropagation();
    e.preventDefault();
  }
});

on('#search', 'keyup', function(e) {
  if ( e.keyCode == 13 ) {
    location = '/m?m=' + this.value;
  }
});


function setup_check() {
  qs('#check')[0].classList.add('on');
}

function cancel_rule() {
  qs('#check')[0].classList.remove('on');
}


function redata(data) {
  phpy('/m/data?m=' + data.metric + '&last_update=' + data.last_update);
}

sub('redata', function(data) {
  setTimeout(function() { redata(data) }, 2500)
});

sub('rule-aded', function() {
  qs('button.alerts')[0].classList.add('checked');
  cancel_rule();
});