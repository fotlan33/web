document.getElementById('pic-links').onclick = function (event) {
	event = event || window.event;
	var target = event.target || event.srcElement,
		link = target.src ? target.parentNode : target,
				options = {	index: link,
					event: event,
					onslide: function (index, slide) {
						var sSource = this.list[index].getAttribute('data-pic-source'),
							nSource = this.container.find('.download');
						if (sSource) {
							nSource[0].setAttribute('href', sSource);
						}
						var sDate = this.list[index].getAttribute('data-pic-date'),
							nDate = this.container.find('.date');
						nDate.empty();
						if (sDate) {
							nDate[0].appendChild(document.createTextNode(sDate));
						}
					}
				},
				links = this.getElementsByTagName('a');
	blueimp.Gallery(links, options);
};
