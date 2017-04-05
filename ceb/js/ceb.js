function Tirage() {
	var c = new Array();
	c = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 25, 50, 75, 100, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1];
	for(i = 0; i < 6; i++) {
		sID = 'c' + ( i + 1);
		k = Math.floor(Math.random() * c.length);
		document.getElementById(sID).innerHTML = c[k];
		c.splice(k, 1);
	}
	n = 100 + Math.floor(Math.random() * 900);
	document.getElementById('ceb-target').innerHTML = n;
}
