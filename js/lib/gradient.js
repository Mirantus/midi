function leftPad(x) { return (x.length < 2) ? "0"+x : x; }
function hex(x) { return leftPad(x.toString(0x10).toUpperCase()); }

for (i=0; i<256; i++) {
  var color = "#"+hex(i)+hex(i)+hex(i);
  document.write("<div style='background: "+color+"; font-size:
    0; height: 2px; width: 100%; line-height: 0;'></div>");
}
