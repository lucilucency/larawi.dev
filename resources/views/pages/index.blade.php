@extends("layouts.layout3")

@section("content")
<style type="text/css" media="screen">
    #holder {
        -moz-border-radius: 10px;
        -webkit-border-radius: 10px;
        border: solid 1px #333;
    }
    p {
        text-align: center;
    }

    .infobox {
        z-index: -1;
        position: absolute;
        overflow: auto;
        width: 0;
        height: 0;
        padding: 20px;
    }
</style>
<p>Drag shapes around.</p>
<div id="holder"></div>

<script>
var el;
$(document).ready(function(){
    var r = Raphael("holder", $(window).width(), $(window).height());
    window.abc= r;

    var dragger = function (x, y, e) {
            this.ox = this.type == "rect" ? this.attr("x") : this.attr("cx");
            this.oy = this.type == "rect" ? this.attr("y") : this.attr("cy");
            this.animate({"fill-opacity": .2}, 500);
            this.courier = $(".infobox[name=" + this.name + "]");
            $(".infobox[name=" + this.name + "]").remove();
        },
        move = function (dx, dy) {
            var att = this.type == "rect" ? {x: this.ox + dx, y: this.oy + dy} : {cx: this.ox + dx, cy: this.oy + dy};
            this.attr(att);
            for (var i = connections.length; i--;) {
                r.connection(connections[i]);
            }
            r.safari();
        },
        up = function () {
            this.ox = this.type == "rect" ? this.attr("x") : this.attr("cx");
            this.oy = this.type == "rect" ? this.attr("y") : this.attr("cy");
            this.animate({"fill-opacity": 0}, 500);
            if (this.raph_container) {
                $(this.courier).insertAfter(this.raph_container);
                            this.courier.css({"top": (this.oy + this.raph_container.offset().top), "left": (this.ox + this.raph_container.offset().left)});
            }

        },

        connections = [];

    var shapes = [  r.rect(100, 300, 60, 40, 2),
                    r.rect(600, 600, 60, 40, 2),
                    r.ellipse(900, 600, 100, 100)
                    ];

    for (var i = 0, ii = shapes.length; i < ii; i++) {
        var color = Raphael.getColor();
        shapes[i].attr({fill: color, stroke: color, "fill-opacity": 0, "stroke-width": 2, cursor: "move"});
        shapes[i].drag(move, dragger, up);
    }

   var infobox = new Infobox(r, {name: "xxx", width:250, height:250, x:600, y:100, drag:true}, {fill: color, stroke: color, "fill-opacity": 0, "stroke-width": 2, cursor: "move"});
   infobox.div.html('<p>This is some crazy content that goes inside of that box that will wrap around.</p>');
   infobox.container.drag(move, dragger, up);

   connections.push(r.connection(infobox.container, shapes[0], "green"));
   connections.push(r.connection(infobox.container, shapes[1], "green"));
   connections.push(r.connection(infobox.container, shapes[2], "green"));
});
</script>
@stop

