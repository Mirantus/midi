<script LANGUAGE="Javascript">
var flashinstalled = 0;
MSDetect = "false";

if (navigator.mimeTypes && navigator.mimeTypes.length)
{
x = navigator.mimeTypes['application/x-shockwave-flash'];
if (x && x.enabledPlugin) flashinstalled = 2;
else flashinstalled = 1;
}
else
{
MSDetect = "true";
}
</SCRIPT>

<script LANGUAGE="VBScript">
If MSDetect = "true" Then
If Not(IsObject(CreateObject
("ShockwaveFlash.ShockwaveFlash"))) Then
flashinstalled = 1
Else
flashinstalled = 2
End If
End If
</SCRIPT>

<script LANGUAGE="Javascript">
alert(flashinstalled);
</SCRIPT>