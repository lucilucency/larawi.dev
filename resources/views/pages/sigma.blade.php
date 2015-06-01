<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo isset($pageTitle) ? $pageTitle : "Luci.dev"?></title>

	<link href="{{asset('/css/app.css')}}" rel="stylesheet">
	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	   <![endif]-->
	   {{--	{{HTML::script("public/js/")}}--}}
	   <script src="{{asset('/js/sigma/sigma.core.js')}}"></script>
	   <script src="{{asset('/js/sigma/conrad.js')}}"></script>
	   <script src="{{asset('/js/sigma/utils/sigma.utils.js')}}"></script>
	   <script src="{{asset('/js/sigma/utils/sigma.polyfills.js')}}"></script>
	   <script src="{{asset('/js/sigma/sigma.settings.js')}}"></script>
	   <script src="{{asset('/js/sigma/classes/sigma.classes.dispatcher.js')}}"></script>
	   <script src="{{asset('/js/sigma/classes/sigma.classes.configurable.js')}}"></script>
	   <script src="{{asset('/js/sigma/classes/sigma.classes.graph.js')}}"></script>
	   <script src="{{asset('/js/sigma/classes/sigma.classes.camera.js')}}"></script>
	   <script src="{{asset('/js/sigma/classes/sigma.classes.quad.js')}}"></script>
	   <script src="{{asset('/js/sigma/captors/sigma.captors.mouse.js')}}"></script>
	   <script src="{{asset('/js/sigma/captors/sigma.captors.touch.js')}}"></script>
	   <script src="{{asset('/js/sigma/renderers/sigma.renderers.canvas.js')}}"></script>
	   <script src="{{asset('/js/sigma/renderers/sigma.renderers.webgl.js')}}"></script>
	   <script src="{{asset('/js/sigma/renderers/sigma.renderers.def.js')}}"></script>
	   <script src="{{asset('/js/sigma/renderers/webgl/sigma.webgl.nodes.def.js')}}"></script>
	   <script src="{{asset('/js/sigma/renderers/webgl/sigma.webgl.nodes.fast.js')}}"></script>
	   <script src="{{asset('/js/sigma/renderers/webgl/sigma.webgl.edges.def.js')}}"></script>
	   <script src="{{asset('/js/sigma/renderers/webgl/sigma.webgl.edges.fast.js')}}"></script>
	   <script src="{{asset('/js/sigma/renderers/webgl/sigma.webgl.edges.arrow.js')}}"></script>
	   <script src="{{asset('/js/sigma/renderers/canvas/sigma.canvas.labels.def.js')}}"></script>
	   <script src="{{asset('/js/sigma/renderers/canvas/sigma.canvas.hovers.def.js')}}"></script>
	   <script src="{{asset('/js/sigma/renderers/canvas/sigma.canvas.nodes.def.js')}}"></script>
	   <script src="{{asset('/js/sigma/renderers/canvas/sigma.canvas.edges.def.js')}}"></script>
	   <script src="{{asset('/js/sigma/renderers/canvas/sigma.canvas.edges.curve.js')}}"></script>
	   <script src="{{asset('/js/sigma/renderers/canvas/sigma.canvas.edges.arrow.js')}}"></script>
	   <script src="{{asset('/js/sigma/renderers/canvas/sigma.canvas.edges.curvedArrow.js')}}"></script>
	   <script src="{{asset('/js/sigma/middlewares/sigma.middlewares.rescale.js')}}"></script>
	   <script src="{{asset('/js/sigma/middlewares/sigma.middlewares.copy.js')}}"></script>

	   <script src="{{asset('/js/sigma/misc/sigma.misc.animation.js')}}"></script>
	   <script src="{{asset('/js/sigma/misc/sigma.misc.bindEvents.js')}}"></script>
	   <script src="{{asset('/js/sigma/misc/sigma.misc.drawHovers.js')}}"></script>

	   <script src="{{asset('/js/sigma/plugins/sigma.renderers.customShapes/shape-library.js')}}"></script>
	   <script src="{{asset('/js/sigma/plugins/sigma.renderers.customShapes/sigma.renderers.customShapes.js')}}"></script>
	   <script src="{{asset('/js/sigma/plugins/sigma.plugins.dragNodes/sigma.plugins.dragNodes.js')}}"></script>
   </head>
   <body>
	   <nav class="navbar navbar-default">
		<div class="container-fluid">
		 <div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		   <span class="sr-only">Toggle Navigation</span>
		   <span class="icon-bar"></span>
		   <span class="icon-bar"></span>
		   <span class="icon-bar"></span>
	   </button>
	   <a class="navbar-brand" href="#">Laravel</a>
   </div>

   <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	<ul class="nav navbar-nav">
	 <li><a href="{{ url('/') }}">Home</a></li>
 </ul>
 <ul class="nav navbar-nav">
	<li>
	  <a href="{{asset('/')}}">{{isset($pageUrl) && !empty($pageUrl)? $pageUrl : "Page Url Here"}}</a>
  </li>
</ul>
<ul class="nav navbar-nav navbar-right">
   @if (Auth::guest())
   <li><a href="{{ url('/auth/login') }}">Login</a></li>
   <li><a href="{{ url('/auth/register') }}">Register</a></li>
   @else
   <li class="dropdown">
	 <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
	 <ul class="dropdown-menu" role="menu">
	  <li><a href="{{ url('/auth/logout') }}">Logout</a></li>
  </ul>
</li>
@endif
</ul>
</div>
</div>
</nav>
<div id="container">
  <style>
	#graph-container {
	  top: 0;
	  bottom: 0;
	  left: 0;
	  right: 0;
	  position: absolute;
  }
</style>
<div id="graph-container"></div>
</div>

<!-- Scripts -->
</body>
</html>
<script>
sigma.utils.pkg('sigma.canvas.nodes');
sigma.canvas.nodes.image = (function() {
  	var _cache = {},
  	_loading = {},
  	_callbacks = {};

	// Return the renderer itself:
	var renderer = function(node, context, settings) {
	var args = arguments,
	prefix = settings('prefix') || '',
	size = node[prefix + 'size'],
	color = node.color || settings('defaultNodeColor'),
	url = node.url;

	if (_cache[url]) {
		context.save();

		// Draw the clipping disc:
		context.beginPath();
		context.arc(
		node[prefix + 'x'],
		node[prefix + 'y'],
		node[prefix + 'size'],
		0,
		Math.PI * 2,
		true
		);
		context.closePath();
		context.clip();

		// Draw the image
		context.drawImage(
			_cache[url],
			node[prefix + 'x'] - size,
			node[prefix + 'y'] - size,
			2 * size,
			2 * size
		);

		// Quit the "clipping mode":
		context.restore();

		// Draw the border:
		context.beginPath();
		context.arc(
			node[prefix + 'x'],
			node[prefix + 'y'],
			node[prefix + 'size'],
			0,
			Math.PI * 2,
			true
			);
		context.lineWidth = size/5 > 5 ? 5 : size/5;
		context.strokeStyle = node.color || settings('defaultNodeColor');
		context.stroke();

	} else {
		sigma.canvas.nodes.image.cache(url);
		sigma.canvas.nodes.def.apply(
			sigma.canvas.nodes,
			args
		);
	}
};

		// Let's add a public method to cache images, to make it possible to
		// preload images before the initial rendering:
		renderer.cache = function(url, callback) {
		if (callback)
  			_callbacks[url] = callback;

			if (_loading[url])
  			return;

			var img = new Image();

			img.onload = function() {
  			_loading[url] = false;
  			_cache[url] = img;

  			if (_callbacks[url]) {
				_callbacks[url].call(this, img);
				delete _callbacks[url];
			}
		};

		_loading[url] = true;
		img.src = url;
	};

	return renderer;
})();

// Now that's the renderer has been implemented, let's generate a graph
// to render:
var i,
s,
img,
N = 3,
E = 3,
g = {
  nodes: [],
  edges: []
},
urls = [
	'{{asset("/img/1.jpg")}}',
	'{{asset("/img/2.jpg")}}',
	'{{asset("/img/3.jpg")}}',
	'{{asset("/img/4.jpg")}}'
],
loaded = 0,
colors = [
	'#617db4',
	'#668f3c',
	'#c6583e',
	'#b956af'
];

// Generate a random graph, with ~30% nodes having the type "image":
for (i = 0; i < N; i++) {
  	g.nodes.push({
		id: 'n' + i,
		label: 'Node ' + i,
		type: 'image',
		url: urls[Math.floor(i%4)],
		x: Math.random(),
		y: Math.random(),
		size: Math.random(),
		color: colors[Math.floor(Math.random() * colors.length)]
	});
}

// g.nodes.push({
// 	id: 'n4',
// 	label: 'Node 4',
// 	type: 'image',
// 	url: urls[3],
// 	x: 0.8,
// 	y: 0.1,
// 	size: Math.random(),
// 	color: colors[Math.floor(Math.random() * colors.length)]
// });

// g.nodes.push({
// 	id: 'n3',
// 	label: 'Node 3',
// 	type: 'image',
// 	url: urls[2],
// 	x: 0.8,
// 	y: 0.8,
// 	size: Math.random(),
// 	color: colors[Math.floor(Math.random() * colors.length)]
// });

// for (i = 0; i < E; i++) {
//   	g.edges.push({
// 		id: 'e' + i,
// 		// source: 'n' + (Math.random() * N | 0),
// 		source: 'n' + i,
// 		target: 'n' + (i+1),
// 		size: Math.random()
// 	});
// }

// Then, wait for all images to be loaded before instanciating sigma:
urls.forEach(function(url) {
	sigma.canvas.nodes.image.cache(url,function() {
		if (++loaded === urls.length) {
			// Instantiate sigma:
			s = new sigma({
				graph: g,
				renderer: {
						// IMPORTANT:
						// This works only with the canvas renderer, so the
						// renderer type set as "canvas" is necessary here.
						container: document.getElementById('graph-container'),
						type: 'canvas'
					},
					settings: {
						minNodeSize: 100,
						maxNodeSize: 100
					}
				});
			sigma.plugins.dragNodes(s, s.renderers[0]);
		}
	});
});
</script>
