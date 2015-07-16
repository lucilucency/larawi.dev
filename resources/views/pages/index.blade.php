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

    .img-cover {
        width: 200px;
        height: 200px; 
        overflow: hidden;
    }

    .img-cover img {
        width: 200px;
    }

</style>
<p>
<?php
    echo '<pre>';
    var_dump($userRelative);
    echo '</pre>';
?>
</p>

<div id="holder"></div>

<script type="text/template" class="map-view-el-tp">
    <div class="user-avt text-center">
        <div class="img-cover text-center">
            <img src='<%= user_avatar %>'>
        </div>
    </div>
    <div class="user-info text-center">
        <a class="user-name" href="{{asset('/user/')}}/<%= user_name %>"><%= user_name %></a>
        <p class="user-birthday"><%= user_birthday %></p>
    </div>
</script>

<script>
var DEFAULT_WIDTH = 250,
    DEFAULT_HEIGHT = 300;

var recipe = _.template($("script.map-view-el-tp").html());
var mapviewElDt = {
    user_avatar: "{{asset('/img/1.jpg')}}",
    user_birthday: "11/11/1991",
    user_name: "Leonardo De Carprio"
};

var maxWidth = $(window).width(), maxHeight = $(window).height();

var el;
var u_arr = <?php echo !empty($user) ? json_encode($user) : "[]" ?>;
var ur_arr = <?php echo !empty($userRelative) ? json_encode($userRelative) : "[]" ?>;
console.log(ur_arr);

$(document).ready(function(){
    var r = Raphael("holder", maxWidth, maxHeight);

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
        uBox = [], uRank = [],
        connections = [];

    for (i = 0; i < u_arr.length; i++) {
        if (typeof (uRank[u_arr[i].user_rank]) == "undefined") { uRank[u_arr[i].user_rank] = []; }
        uRank[u_arr[i].user_rank].push(u_arr[i]);
    }
    console.log(uRank);
    window.abc= uRank;

    var k = 0;
    for (i = 0; i < uRank.length; i++) {
        if (typeof uRank[i] != "undefined") {
            maxWidth = Math.max(maxWidth, uRank[i].length * (DEFAULT_WIDTH + 40));
            maxHeight = Math.max(maxHeight, (i+1) * (DEFAULT_HEIGHT + 40));

            bx = maxWidth / 2;
            // by = max

            for (j = 0; j < uRank[i].length; j++) {
                color = Raphael.getColor();
                tmp = new Infobox(r, {
                    name: "user" + uRank[i][j].user_id,
                    x: 600,
                    y: 100
                }, {fill: "green", stroke: color, "fill-opacity": 0, "stroke-width": 2, cursor: "move"});
                tmp.div.html(recipe({
                    user_name: uRank[i][j].user_name,
                    user_avatar: uRank[i][j].user_avatar,
                    user_birthday: uRank[i][j].user_birthday
                }));
                tmp.container.drag(move, dragger, up);
                uBox[k] = tmp;
                k++;
            }
        }
    }

    for (i = 0; i < ur_arr.length; i++) {
        var src = $.grep(uBox, function(e){ return e.name == "user" + ur_arr[i].user_id1; });
        var des = $.grep(uBox, function(e){ return e.name == "user" + ur_arr[i].user_id2; });
        connections.push(r.connection(src[0].container, des[0].container, "green"));
    }


});



</script>
@stop

