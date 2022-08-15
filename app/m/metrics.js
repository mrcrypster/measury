// Drag and Drop

on('.metrics-list li', 'dragstart', function(e) {
  this.classList.add('dragging');
  e.dataTransfer.setData('metric', this.querySelector('a').dataset.metric);
});

on('.metrics-list li', 'dragend', function() {
  this.classList.remove('dragging');
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
