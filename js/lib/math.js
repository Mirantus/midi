function roundvalue(val)
{
  if (val==0) return("0");
  var inputVal=(""+val);
  if (inputVal.indexOf(".")==-1) inputVal+=".0";
  var decPart=inputVal.substring(0,inputval.indexOf("."));
  var fracPart=parseInt(inputVal.substring(inputVal.indexOf(".")+1,inputVal.indexOf(".")+3));
  //Precision>0.5 results in next largest number.
  if (parseInt(fracPart)>50){return(""+(parseInt(decPart)+1)+".00");}
  else {fracPart=""+Math.round(val*100);
  newfracPart=fracPart.substring(fracPart.length-2,fracPart.length);
  return (""+decPart+"."+newfracPart);
  }
}


function formatFloat(src,digits) 
{
var powered, tmp, result
// make sure it is number
if (isNaN(src)) return src;

// 10^digits
var powered = Math.pow(10,digits);

var tmp = src*powered;

// round tmp
tmp = Math.round(tmp);

// get result
var result = tmp/powered;

result=result.toString();
a=result.indexOf(".");
b=result.substr(a,10);
if(b==result) result=result+".00";
if(b.length==0) result=result+".00";
if(b.length==1) result=result+"00";
if(b.length==2) result=result+"0";

return result;
}

res=formatFloat(1024.2267,2);
document.write(res);