on('.drop', 'click', function(e) {
  if ( !confirm('Really???') ) {
    e.stopPropagation();
    e.preventDefault();
  }
});