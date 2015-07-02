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
/**
 * IMPORTANT: This only works with the canvas renderer. TBD in the future
 * will also support the WebGL renderer.
 */
 sigma.utils.pkg('sigma.canvas.nodes');

// generate a random graph
var i,
    s,
    img,
    N = 4,
    E = 50,
    g = {
      nodes: [],
      edges: []
    },
    imgs = [
        "{{asset('/img/1.jpg')}}",
        "{{asset('/img/2.jpg')}}",
        "{{asset('/img/3.jpg')}}",
        "{{asset('/img/4.jpg')}}"
    ],
    colors = [
        '#617db4',
        '#668f3c',
        '#c6583e',
        '#b956af'
    ];

// Generate a random graph, going through the different shapes
for (i = 0; i < N; i++) {
    var node = {
        id: 'n' + i,
        // label: 'Node ' + i,
        // note the ShapeLibrary.enumerate() returns the names of all
        // supported renderers
        type: ShapeLibrary.enumerate().map(function(s){return s.name;})[4],
        // type: ShapeLibrary.enumerate().map(function(s){return s.name;})[Math.round(Math.random()*5)],
        x: Math.random(),
        y: Math.random(),
        size: Math.random(),
        color: colors[Math.floor(Math.random() * colors.length)],
        borderColor: colors[Math.floor(Math.random()* colors.length)]
    };

    node.image = {
        url: imgs[Math.floor(Math.random() * imgs.length)],
          // scale/clip are ratio values applied on top of 'size'
          scale: 1.3,
          clip: 0.85
    }

    // rescale the cover
    switch(node.type) {
        case "equilateral":
            node.equilateral = {
                rotate: Math.random()*45, // random rotate up to 45 deg
                numPoints: Math.round(5 + Math.random()*3)
            }
            break;
        case "star":
            alert("star");
            node.star = {
                innerRatio: 0.4 + Math.random()*0.2,
                numPoints: Math.round(4 + Math.random()*3)
            }
            if(node.image) {
                // note clip/scale are ratio values. So we fit them to the inner ratio of the star shape
                node.image.clip = node.star.innerRatio *0.95;
                node.image.scale = node.star.innerRatio *1.2;
            }
            break;
        case "square":
        case "diamond":
            alert("diamond");
            if(node.image) {
                // note clip/scale are ratio values. So we fit them to the borders of the square shape
                node.image.clip = 0.7;
            }
            break;
        case "circle":
            break;
        case "cross":
            node.cross = {
                lineWeight: Math.round(Math.random() * 5)
            }
            break;
    }
    g.nodes.push(node);
}


// for (i = 0; i < E; i++)
//     g.edges.push({
//         id: 'e' + i,
//         source: 'n' + (Math.random() * N | 0),
//         target: 'n' + (Math.random() * N | 0),
//         size: Math.random()
// });

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
        minNodeSize: 80,
        maxNodeSize: 80
    }
});
CustomShapes.init(s);
s.refresh();
sigma.plugins.dragNodes(s, s.renderers[0]);


</script>
