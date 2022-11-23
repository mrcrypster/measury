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

/*sub('redata', function(data) {
  setTimeout(function() { redata(data) }, 2500)
});*/

sub('rule-aded', function() {
  qs('button.alerts')[0].classList.add('checked');
  cancel_rule();
});



qs('.chrt').forEach(function(el) {
  let data = JSON.parse(el.dataset.data);
  let color = el.dataset.color || '#0074D9';
  
  
  new Chart(el, {
    type: 'line',
    data: {
      labels: Object.keys(data),
      datasets: [{
        label: 'Value',
        borderColor: color + 'aa',
        backgroundColor: color + '22',
        fill: {target: 'origin'},
        data: Object.values(data),
        borderWidth: 2
      }]
    },
    options: {
      aspectRatio: 6,
      animation: {duration: 0},
      
      scales: {
        y: {
          beginAtZero: false,
          grid: {
            display: true,
            drawTicks: true,
            color: '#f6f6f6'
          }
        },
        x: {
          ticks: { display: false },
          grid: {
            display: true,
            drawTicks: true,
            color: '#f6f6f6'
          }
        }
      },
      plugins: {
        legend: { display: false }
      }
    }
  });
});