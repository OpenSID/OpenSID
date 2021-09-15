<table border="2" width="200" cellspacing="0" cellpadding="0" bgcolor="#000000"
style="border-color:black" onClick="previouskey=event.srcElement.innerText">
  <tr>
    <td width="100%" bgcolor="#FFFFFF" id="result"
    style="font:bold 20px Verdana;color:black;text-align='right'">0</td>
  </tr>
  <tr>
    <td width="100%" valign="middle" align="center"><table border="0" width="100%"
    cellspacing="0" cellpadding="0" style="font:bold 20px Verdana;color:white">
      <tr>
        <td width="80%" align="center"><table border="1" width="100%" cellspacing="0"
        cellpadding="0" style="cursor:hand;font:bold 20px Verdana;color:white"
        onMouseover="if (event.srcElement.tagName=='TD')event.srcElement.style.color='yellow'"
        onMouseout="event.srcElement.style.color='white'" onselectStart="return false"
        onClick="calculate()" height="82">
          <tr>
            <td width="25%" align="center" height="17">7</td>
            <td width="25%" align="center" height="17">8</td>
            <td width="25%" align="center" height="17">9</td> 
          </tr>
          <tr>
            <td width="25%" align="center" height="19">4</td>
            <td width="25%" align="center" height="19">5</td>
            <td width="25%" align="center" height="19">6</td> 
          </tr>
          <tr>
            <td width="25%" align="center" height="19">1</td>
            <td width="25%" align="center" height="19">2</td>
            <td width="25%" align="center" height="19">3</td> 
          </tr>
          <tr>
             <td width="25%" align="center" height="19">1</td>
            <td width="25%" align="center" height="19">0</td>
            <td width="25%" align="center" height="19">3</td> 
          </tr>
        </table>
        </td>
        <td width="20%"><div align="left"><table border="1" width="100%" cellspacing="0"
        cellpadding="0">
          <tr>
            <td width="100%" style="cursor:hand;font:bold 20px Verdana;color:white;text-align:center"
            onClick="result.innerText=0;results=''">C</td>
          </tr>
        </table>
        </div><div align="left"><table border="1" width="100%" cellspacing="0" cellpadding="0"
        height="81">
          <tr>
            <td width="100%" style="cursor:hand;font:bold 32px Verdana;color:white;text-align:center"
            onMouseover="event.srcElement.style.color='yellow'"
            onMouseout="event.srcElement.style.color='white'" onClick="calculateresult()">=</td>
          </tr>
        </table>
        </div></td>
      </tr>
    </table>
    </td>
  </tr>
</table>


<script language="JavaScript1.2">

/*
DHTML Calculator Script- 
(c) Dynamic Drive (www.dynamicdrive.com)
For full source code, installation instructions,
100's more DHTML scripts, and Terms Of
Use, visit dynamicdrive.com
*/

var results=''
var previouskey=''
var re=/(\/|\*|\+|-)/
var re2=/(\/|\*|\+|-){2}$/
var re3=/.+(\/|\*|\+|-).+/
var re4=/\d|\./
var re5=/^[^\/\*\+].+\d$/
var re6=/\./

function calculate(){
if (event.srcElement.tagName=="TD"){
if (event.srcElement.innerText.match(re4)&&previouskey=="=")
results=''
if (result.innerText.match(re3)&&event.srcElement.innerText.match(re)){
if (!results.match(re5)){
result.innerText="Error!"
return
}
results=eval(results)
if (results.toString().length>=12&&results.toString().match(re6))
results=results.toString().substring(0,12)
result.innerText=results
}

results+=event.srcElement.innerText
if (results.match(re2))
results=results.substring(0,results.length-2)+results.charAt(results.length-1)

result.innerText=results
}
}

function calculateresult(){
if (!results.match(re5)){
result.innerText="Error!"
return
}
results=eval(results)
if (results.toString().length>=12&&results.toString().match(re6))
results=results.toString().substring(0,12)
result.innerText=results
}



function pn(){
if (result.innerText.charAt(0)!='-')
result.innerText=results='-'+result.innerText
else if (result.innerText.charAt(0)=='-')
result.innerText=results=result.innerText*(-1)
}

</script>
