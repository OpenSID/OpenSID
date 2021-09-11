<div class='numpad_keypad'>
	<div class='td tdNumeric' data-num="1">1</div>
	<div class='td tdNumeric' data-num="2">2</div>
	<div class='td tdNumeric' data-num="3">3</div>
</div>
<div class='numpad_keypad'>
	<div class='td tdNumeric' data-num="4">4</div>
	<div class='td tdNumeric' data-num="5">5</div>
	<div class='td tdNumeric' data-num="6">6</div>
</div>
<div class='numpad_keypad'>
	<div class='td tdNumeric' data-num="7">7</div>
	<div class='td tdNumeric' data-num="8">8</div>
	<div class='td tdNumeric' data-num="9">9</div>
</div>
<div class='numpad_keypad'>
	<div class='td'><i class="fa fa-undo"></i></div>
	<div class='td tdNumeric' data-num="0">0</div>
	<div class='td'><i id='button' class="fa fa-arrow-circle-o-left" onclick='hapus()'></i></div>
</div>

<script>
var defInp='nik';
function hapus()
{
	id=$("#"+defInp).attr("id");
	console.log(id);
	str=$("#"+defInp).val();
	newTxt = str.substring(0, str.length - 1);
	$("#"+defInp).val(newTxt);
	
}

function changeDef(txt)
{
	defInp=txt;
}

$(function() {
	$('.tdNumeric').on('click', function () {
		str=$("#"+defInp).val();
		s=$(this).data('num');
		newTxt = str+s;
		$("#"+defInp).val(newTxt);
	});
	
});
</script>