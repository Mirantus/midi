function find_all(string,from,to)
{	
	var reg= new RegExp( "("+from+".*?"+to+")" );
	var found= new Array();
	i=0;
	
	for(i=0;string.match(reg) != null;i++)
		{
			found[i]=string.match(reg)[1];	
			string=string.replace(reg,'');
			found[i]=found[i].substring(from.length,found[i].length-to.length);
		}
	return found;
}