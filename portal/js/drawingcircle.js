function initialize() {
  var myLatLong = new google.maps.LatLng(-25.88691, 28.20553);
  var mapOptions = {
    zoom: 8,
    center: myLatLong,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };

  var map = new google.maps.Map(document.getElementById('map_canvas'),
    mapOptions);
	
  var marker = new google.maps.Marker({
      position: myLatLong,
      map: map,
      title: "You Are Here!"
	});
	
	for(x = 0; x < 25; x++){  
		new google.maps.Marker({
			position: new google.maps.LatLng(Math.random() + -26.5, Math.random() + 27.5),
			map: map,
			title: "User: " + Math.floor(Math.random()*600)
	});
  }

  var drawingManager = new google.maps.drawing.DrawingManager({
    drawingMode: google.maps.drawing.OverlayType.CIRCLE,
    drawingControl: true,
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
      drawingModes: [
        google.maps.drawing.OverlayType.CIRCLE
      ]
    },
    circleOptions: {
      fillColor: '#000000',
      fillOpacity: 0.7,
      strokeWeight: 1,
      clickable: true,
      editable: false,
      draggable:true,
      zIndex: 1
    }
  });
  drawingManager.setMap(map);


  google.maps.event.addListener(drawingManager, 'circlecomplete', function(circle) {
    var radius = circle.getRadius();
    var center = circle.getCenter();
    var lat = center.lat();
    var lng = center.lng();
    console.log(radius);
    console.log(lat);
    console.log(lng);
    
    google.maps.event.addListener(circle, "dragend", function(ev) { 
        var radius = circle.getRadius();
        var center = circle.getCenter();
        var lat = center.lat();
        var lng = center.lng();
        console.log(radius);
        console.log(lat);
        console.log(lng);
    }); 

  });
  
  
}

google.maps.event.addDomListener(window, 'load', initialize);

