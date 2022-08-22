// Init on load
qs('#folders input[type=text]', function(el) {
  if ( el.value == 'New Folder' ) {
    el.focus();
    el.select();
  }
});


// New folder
function new_folder() {
  phpy('/m/folder');
}


// List folders (used on mobile only)
function show_folders() {
  qs("#folders")[0].classList.add('on');
}


// Edit folder title (or remove folder)
on('#folders input[type=text]', 'keydown', function(e) {
  if ( e.keyCode == 13 ) {
    this.blur();
  }
  else if ( (e.keyCode == 8) && (!this.value) ) {
    if ( confirm('Do you want to delete this folder?') ) {
      phpy('/m/folder', {id: this.dataset.id});
      location = '/m';
    }
  }
  
  let t = this.parentNode.querySelector('span');
  let i = this;
  setTimeout(function() {
    t.innerText = i.value || '_';
    w = (t.getBoundingClientRect().width + 8);
    if ( w < 32 ) w = 32;
    i.style.width = w + 'px';
    
    if ( i.value != '' ) {
      phpy('/m/folder', {id: i.dataset.id, title: i.value});
    }
  }, 10);
});


// Bind "Tab"
document.addEventListener('keydown', function(e) {
  let folders = qs('#folders > *');
  if ( folders.length == 0 ) {
    return;
  }
  
  if ( e.keyCode == 9 ) {
    e.stopPropagation();
    e.preventDefault();
    
    let next = folders[0];
    folders.forEach(function(f) {
      if ( f.classList.contains('on') && f.nextSibling ) {
        next = f.nextSibling;
      }
    });
    
    next.click();
  }
});