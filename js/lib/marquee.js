var source,dest,len,now=0,letters=1;
var string_array = new Array ('...');
var i=0;
var source = string_array[i] + ' ';

function show_text()
{
		dest = document.getElementById("dots");
		len = source.length;
		show();
}

function show()
{
	var array;
	dest.innerHTML += source.substr(now,letters);
	now+=letters;

	if (now>len)
	{
		len,now=0,letters=1;
		dest.innerHTML = '';
		i++; if (i >= string_array.length) i = 0;
		source = string_array[i];
	}
	
	setTimeout("show()", 500);
}
show_text();