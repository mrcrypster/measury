// Drag and Drop

on('.metrics-list li', 'dragstart', function(e) {
  this.classList.add('dragging');
  e.dataTransfer.setData('metric', this.querySelector('a').dataset.metric);
});

on('.metrics-list li', 'dragend', function() {
  this.classList.remove('dragging');
});

on('.metrics-list li.on a', 'click', function(e) {
  qs('.metrics-list')[0].classList.add('on');
  e.stopPropagation();
  e.preventDefault();
});

on('#folders > a', 'dragover', function(e) {
  e.preventDefault();
  this.classList.add('dragover');
});

on('#folders > a', 'dragleave', function() {
  this.classList.remove('dragover');
});

on('#folders > a', 'drop', function(e) {
  this.classList.remove('dragover');
  let m = e.dataTransfer.getData('metric');
  
  if ( m ) {
    phpy('/m/move', {metric: m, folder: this.dataset.id})
    qs('.metrics-list li a[data-metric="' + m + '"]', function(el) {
      el.parentNode.remove();
    })
  }
});
