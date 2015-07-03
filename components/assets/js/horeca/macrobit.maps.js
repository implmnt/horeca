var MapManager = function(elementId) {
	var self = this;

	this.map = new ymaps.Map(elementId, {
		center: [43.03567, 44.681286],
		controls: [],
		zoom: 14
	});

	this.addGeoObjects = function(items) {
		for (var name in items) {
			var item = items[name];
			if (typeof item === 'string' && item.length > 2) {
				item = JSON.parse(item);
			}
			if (item.length === 2) {
				self.map.geoObjects.add(new ymaps.Placemark(item, {
					hintContent: name 
				}, {
					iconLayout: 'default#image',
					iconImageHref: '/themes/macrobit/assets/images/map-star.png',
					iconImageSize: [22, 21]
				}));
			}
		}
	};

	var items = $('#' + elementId).data('coords');
	self.addGeoObjects(items);
};

if (window.ymaps) {
	ymaps.ready(function() {
		window.mapManager = new MapManager('map-canvas');
	});
}