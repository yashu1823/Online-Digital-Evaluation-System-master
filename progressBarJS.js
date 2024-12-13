function updateProgressBar(){

	var numOfQuestions = 0;
	var numOfCorrectedQ = 0;

	$('.marksTB').each( function(){
		numOfQuestions++;
		if( $(this).val() != '' && !$(this).hasClass('err') ){
			numOfCorrectedQ++;
		}
	});

	var percent = numOfCorrectedQ / numOfQuestions * 100;
	percent1 = percent + "%";
	$('div#progress-bar div').css('width',percent1);
	$('#correction-percent-text-field').val(Math.floor(percent)+"% corrected");

}