var total=0;
$('.marksTB').each(function(){
	if( $(this).val() != '' ){
		total += parseFloat($(this).val());
	}
});

$('#total-marks-text-field').val(total);