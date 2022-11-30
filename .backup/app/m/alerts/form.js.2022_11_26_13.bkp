sub('check', function() {
  setTimeout(function() {
    qs('#check input, #check select', function(el) {
      el.addEventListener('change', function() {
        if ( this.name == 'enabled' ) {
          this.checked ? 
            qs('#check')[0].classList.add('enabled') :
            qs('#check')[0].classList.remove('enabled') ;
        }
        phpy('/m/alerts/add', this.form);
      });
    });
  }, 100);
});