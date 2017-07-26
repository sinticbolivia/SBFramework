/**
 * 
 */
function SBCamera(video, canvas)
{
	// Grab elements, create settings, etc.
	//var canvas = document.getElementById("camera-canvas"),
	//video = document.getElementById("camera-video"),
	var context = null;
	var videoObj = { "video": true };
	var errBack = function(error) 
	{
		window.console && console.log("Video capture error: " + error.code); 
		//alert("Video capture error: " + error.message);
	};
	if( !video )
		return false;
	if( canvas )
	{
		canvas.width 	= video.width;
		canvas.height 	= video.height;
		context 		= canvas.getContext("2d");
	}

	// Put video listeners into place
	if(navigator.getUserMedia) 
	{ 
		// Standard
		navigator.getUserMedia(videoObj, function(stream) 
		{
			video.src = stream;
			video.play();
		}, errBack);
	} 
	else if(navigator.webkitGetUserMedia) 
	{ 
		//alert('webkitGetUserMedia');
		// WebKit-prefixed
		navigator.webkitGetUserMedia(videoObj, function(stream)
		{
			video.src = window.webkitURL.createObjectURL(stream);
			video.play();
		}, errBack);
	}
	else if(navigator.mozGetUserMedia) 
	{ 
		// Firefox-prefixed
		navigator.mozGetUserMedia(videoObj, function(stream)
		{
			video.src = window.URL.createObjectURL(stream);
			video.play();
		}, errBack);
	}
	else
	{
		alert('Camera not supported');
	}
	// Trigger photo take
	this.Capture = function() 
	{
		if( !context )
			return false;
		//console.log(video.width);
		context.drawImage(video, 0, 0, canvas.width, canvas.height);
	};
}