document.cookie = 'tz=' + Intl.DateTimeFormat().resolvedOptions().timeZone;

function redata() {
  phpy('/data?m=' + (new URL(location.href)).searchParams.get('m'));
}

sub('redata', function() { setTimeout(redata, 500); });