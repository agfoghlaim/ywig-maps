console.log('hello, ywig-map plugin main.js')

console.log(WP_MARKER_DETS);
// function checkRest(){
// 	var ourRequest = new XMLHttpRequest();
// 	ourRequest.open('GET' , 'http://138.68.182.248/wp-json/wp/v2/markers-api');
// 	ourRequest.onload = function(){
// 		if(ourRequest.status >= 200 && ourRequest.status < 400){
// 			var data = JSON.parse(ourRequest.responseText);
// 			console.log(data);
// 		}else{
// 			console.log("bad bad bad")
// 		}
// 	}

// 	ourRequest.onerror = function(){
// 		console.log('connection error');
// 	}

// 	ourRequest.send();
// }

//checkRest();

// $(document).ready(function(){
// //global vars
// var locations = [];

// console.log("have ajax url ", ajaxurl)
	

// 		//map expects...array of arrays called locations
// 		//var locations = [['Regional Office', 53.2767588, -9.0474665]]
// 		$.ajax({
// 			type:'GET',
// 			dataType: 'JSON',
// 			url: ajaxurl,
// 			data: {action: 'markers_action'},
// 			success: function(response){
// 				console.log(response)		
// 				jQuery.each(response.data, function(i,r){
// 					var arr = [];
// 					arr.push(r.marker, r.lat, r.lng);
// 					locations.push(arr );
// 					console.log("end ", locations);
// 				})
// 			},
// 			error: function(response){
// 				console.log("error ", response);
// 			}
// 		});


// });
