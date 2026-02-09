// JavaScript Document

function GetXmlHttpObject()
{
if (window.XMLHttpRequest)
  {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  return new XMLHttpRequest();
  }
if (window.ActiveXObject)
  {
  // code for IE6, IE5
  return new ActiveXObject("Microsoft.XMLHTTP");
  }
return null;
}

function getHTTPObject() {
var req;

try {
if (window.XMLHttpRequest) {
req = new XMLHttpRequest();

if (req.readyState == null) {
req.readyState = 1;
req.addEventListener("load", function () {
req.readyState = 4;

if (typeof req.onReadyStateChange == "function")
req.onReadyStateChange();
}, false);
}

return req;
}

if (window.ActiveXObject) {
var prefixes = ["MSXML2", "Microsoft", "MSXML", "MSXML3"];

for (var i = 0; i < prefixes.length; i++) {
try {
req = new ActiveXObject(prefixes[i] + ".XmlHttp");
return req;
} catch (ex) {};

}
}
} catch (ex) {}

alert("XmlHttp Objects not supported by client browser");
}
var http = getHTTPObject();
// Logo após fazer a verificação, é chamada a função e passada
// o valor à variável global http.