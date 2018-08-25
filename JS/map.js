function drawMap(){
    var markers = [];


    for(let i=0;i<metArray.length;i++){
        markers.push(
            {
                cords:{lat:metArray[i].lat ,lng:metArray[i].long },
                content:
                "<h1>Name:"+metArray[i].name+"<h1>" +
                "<h1>Mass:" +metArray[i].mass+"<h1>" +
                "<h1>Year:"+metArray[i].year+"<h1>"
            });

        var x = metArray[i].lat;
        var y = metArray[i].long;
    }

    function initMap(){
        //map options
        var options = {
            zoom:4,
            center:{lat:50,lng:50}
        };
        //new map
        var map = new google.maps.Map(document.getElementById('map'),options);

        //Loop through the marker array
        for(var i = 0; i< markers.length; i++){
            addMarker(markers[i]);
        }

        //Add Marker Function
        function addMarker(props){
            var marker = new google.maps.Marker({
                position:props.cords,
                map:map,
                //icon: props.iconImage
            });

            //check for custom icon
            if(props.iconImage){
                //Set icon image
                marker.setIcon(props.iconImage);
            }

            //check for content
            if(props.content){
                var infoWindow = new google.maps.InfoWindow({
                    content: props.content
                });
            }
            marker.addListener('click',function(){
                infoWindow.open(map,marker);
            });
        }//addMarker
    }
}

