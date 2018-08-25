var btnName = document.getElementById("form_button");
btnName.addEventListener("click", sendForm);
var btnMass = document.getElementById("form_button_2");
btnMass.addEventListener("click", sendForm);
var btnYear = document.getElementById("form_button_3");
btnYear.addEventListener("click", sendForm);
var btnCont = document.getElementById("form_button_4");
btnCont.addEventListener("click", sendForm);
var btnAll = document.getElementById("form_button_5");
btnAll.addEventListener("click", sendForm);

var metArray = [];

function sendForm(event) {
  var form = document.getElementById("my_form");
  var form2 = document.getElementById("my_form_2");
  var form3 = document.getElementById("my_form_3");
  var form4 = document.getElementById("my_form_4");
  var form5 = document.getElementById("my_form_5");
  var form_data = null;

  if (event.target === btnName) {
    form_data = new FormData(form);
  } else if (event.target === btnMass) {
    form_data = new FormData(form2);
  } else if (event.target === btnYear) {
    form_data = new FormData(form3);
  } else if (event.target === btnCont) {
    form_data = new FormData(form4);
    var selection = document.getElementsByName("cont");
    var my_option;
    for (var i in selection) {
      if (selection[i].checked) {
        my_option = selection[i].value;
      }
    }
  } else if (event.target === btnAll) {
    form_data = new FormData(form5);
  }

  for (let [key, value] of form_data.entries()) {
    console.log(key + ":" + value);
  }

  if (form_data != null) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "./form_process.php", true);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.onload = function() {
      if (this.status === 200) {
        console.log(xhr.responseText);
        if (xhr.responseText) {
          try {
            metArray = JSON.parse(xhr.responseText);
          } catch (e) {
            console.log(e);
          }
        }

        defineMeteorites();
        initMap();
      }
    };
    xhr.send(form_data);
  } //sendForm
}

var markers = [];

function defineMeteorites() {
  for (let i = 0; i < metArray.length; i++) {
    markers.push({
      cords: {
        lat: parseFloat(metArray[i].lat),
        lng: parseFloat(metArray[i].long)
      },
      content:
        "<h1>Name:" +
        metArray[i].name +
        "<h1>" +
        "<h1>Mass:" +
        metArray[i].mass +
        "<h1>" +
        "<h1>Year:" +
        metArray[i].year +
        "<h1>"
    });

    var x = parseFloat(metArray[i].lat);
    var y = parseFloat(metArray[i].long);
  }
} //defineMeteorites

function initMap() {
  //map options
  var options = {
    zoom: 4,
    center: { lat: 50, lng: 50 }
  };
  //new map
  var map = new google.maps.Map(document.getElementById("map"), options);

  //Loop through the marker array
  for (var i = 0; i < markers.length; i++) {
    addMarker(markers[i]);
  }

  //Add Marker Function
  function addMarker(props) {
    var marker = new google.maps.Marker({
      position: props.cords,
      map: map
      //icon: props.iconImage
    });

    //check for custom icon
    if (props.iconImage) {
      //Set icon image
      marker.setIcon(props.iconImage);
    }

    //check for content
    if (props.content) {
      var infoWindow = new google.maps.InfoWindow({
        content: props.content
      });
    }
    marker.addListener("click", function() {
      infoWindow.open(map, marker);
    });
  } //addMarker
}
