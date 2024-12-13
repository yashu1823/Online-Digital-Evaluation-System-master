function zoomIn(imgDoma) {
	console.log('in func zoomIn');
	var wid = imgDoma.style.width;
	wid = wid.replace(/[^0-9.]/g, '');
	var hei = imgDoma.style.height;
	hei = hei.replace(/[^0-9.]/g, '');
	wid = Number(wid) + 50;
	wid = wid + 'px';
	hei = Number(hei) + (50 * 4 / 3);
	hei = hei + 'px';
	imgDoma.style.width = wid;
	imgDoma.style.height = hei;
	console.log('after func zoomIn ' + wid + ' ' + hei);
}

function zoomOut(imgDoma) {
	console.log("in func zoomOut");
	var wid = imgDoma.style.width;
	var hei = imgDoma.style.height;
	wid = wid.replace(/[^0-9.]/g, '');
	if ((Number(wid) - 50) >= 429) {
		wid = Number(wid) - 50;
	}
	wid = wid + 'px';
	hei = hei.replace(/[^0-9.]/g, '');
	if ((Number(hei) - (50 * 4 / 3)) >= 572) {
		hei = Number(hei) - (50 * 4 / 3);
		//console.log(hei);
	}
	hei = hei + 'px';
	imgDoma.style.width = wid;
	imgDoma.style.height = hei;

}