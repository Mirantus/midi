function ReadCookie(cookiename) 
{
  var numOfCookies=document.cookie.length;
  var nameOfCookie=cookiename+"=";
  var cookieLen=nameOfCookie.length;
  var x=0;
  while (x<=numOfCookies){
         var y=(x+cookieLen);
         if (document.cookie.substring(x,y)==nameOfCookie)
             return(extractCookieValue(y));
         x=document.cookie.indexOf(" ",x)+1;
         if (x==0)
             break;
  }
  return null;
}

function extractCookieValue(val)
{
  if((endOfCookie=document.cookie.indexOf(";",val))==-1
     endOfCookie=document.cookie.length;
  return unescape(document.cookie.substring(val,endOfCookie));
}

function createCookie(name, value, expiredays)
{
  var todayDate=new Date();
  todayDate.setDate(todayDate.getDate()+expiredays);
  document.cookie=name+"="+value+"; expires="+
  todayDate.toGMTString()+";";
}

function returnExpiry(days)
{
  var todayDate=new Date();
  todayDate.setDate(todayDate.getDate()+days);
  return(todayDate.toGMTString());
}

function deleteCookie(name)
{
  var todayDate=new Date();
  var value=ReadCookie(name);
  todayDate.setDate(todayDate.getDate()-1);
  document.cookie=name+"="+value+";expires="+
           todayDate.toGMTString()+";"
}