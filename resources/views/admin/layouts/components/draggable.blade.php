$("#dragable").sortable({
cursor: 'row-resize',
placeholder: 'ui-state-highlight',
items: '.dragable-handle',
update: function () {
var order = [];
$('tr.dragable-handle').each(function (index, element) {
order.push($(this).attr('data-id'))
})
$.ajax({
type: "POST",
dataType: "json",
url: '{{ $urlDraggable }}',
data: {
data: order,
},
success: function (response) {
if (response.status) {
TableData.draw();
} else {
TableData.draw();
}
}
});
}
}).disableSelection();
