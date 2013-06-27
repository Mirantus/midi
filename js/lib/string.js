function trans(rusStr)
{
var eng = new Array();
eng["Ё"] = "YO";
eng["Ж"] = "ZH";
eng["Ч"] = "CH";
eng["Ш"] = "SH";
eng["Ю"] = "YU";
eng["Я"] = "YA";
eng["ж"] = "zh";
eng["ч"] = "ch";
eng["ш"] = "sh";
eng["ю"] = "yu";
eng["я"] = "ya";
eng["А"] = "A";
eng["Б"] = "B";
eng["В"] = "V";
eng["Г"] = "G";
eng["Д"] = "D";
eng["Е"] = "E";
eng["З"] = "Z";
eng["И"] = "I";
eng["Й"] = "J";
eng["К"] = "K";
eng["Л"] = "L";
eng["М"] = "M";
eng["Н"] = "N";
eng["О"] = "O";
eng["П"] = "P";
eng["Р"] = "R";
eng["С"] = "S";
eng["Т"] = "T";
eng["У"] = "U";
eng["Ф"] = "F";
eng["Х"] = "H";
eng["Ц"] = "C";
eng["Щ"] = "W";
eng["Ъ"] = "'";
eng["Ы"] = "Y";
eng["Ь"] = "'";
eng["Э"] = "E";
eng["а"] = "a";
eng["б"] = "b";
eng["в"] = "v";
eng["г"] = "g";
eng["д"] = "d";
eng["е"] = "e";
eng["ё"] = "e";
eng["з"] = "z";
eng["и"] = "i";
eng["й"] = "j";
eng["к"] = "k";
eng["л"] = "l";
eng["м"] = "m";
eng["н"] = "n";
eng["о"] = "o";
eng["п"] = "p";
eng["р"] = "r";
eng["с"] = "s";
eng["т"] = "t";
eng["у"] = "u";
eng["ф"] = "f";
eng["х"] = "h";
eng["ц"] = "c";
eng["щ"] = "w";
eng["ъ"] = "'";
eng["ы"] = "y";
eng["ь"] = "'"; 
eng["э"] = "e";

var engStr ='';
for (i = 0;  i < rusStr.length;  i++)
  {
    ch = rusStr.charAt(i);
    if (eng[ch]==eng[" "]) {engStr = engStr +  ch;}
    else {engStr = engStr +  eng[ch];}
 }

return (engStr);
}