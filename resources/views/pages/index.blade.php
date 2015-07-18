@extends("layouts.layout3")

@section("content")
<style type="text/css" media="screen">
    #holder {
        -moz-border-radius: 10px;
        -webkit-border-radius: 10px;
    }
    p {
        text-align: center;
    }

    .infobox {
        z-index: 1;
        position: absolute;
        overflow: hidden;
        padding: 10px;
    }

    .box-ratio {
        display: inline-block;
        position: relative;
        width: 100%;
        font-size: 12px;

        /*margin: 25% 0 0 25%;*/
        /*float: left;*/
    }

    .box-ratio .content-ratio {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        /*background: black;*/
        overflow: hidden;
        text-align: center;
        vertical-align: center;
        text-transform: uppercase;
        color: #fff;
    }

    .content-ratio img {
        width: 100%;
    }

    .ratio_1:before {
        content: "";
        display: block;
        padding-top: 100%;
    }

    .infobox .content-slide {
        background-color: rgba(255,255,255,0.5);
        position: absolute;
      
        transition: all 0.3s ease;
    }

    .infobox .content-slide h3 {
        font-size: 1.5em;
        margin-bottom: 0.5em;
    }

    .content-slide:hover {
        background-color: rgba(255,255,255,0.8);
        bottom: -100px;
    }

    .infobox .control-menu {
        position: absolute;
        top: 0px;
        right: -20px;
    }

</style>
<p>
<?php
    // echo '<pre>';
    // var_dump($userRelative);
    // echo '</pre>';
?>
</p>

<div id="holder"></div>

<script type="text/template" class="map-view-el-tp">
    <div class="box-ratio ratio_1">
        <div class="content-ratio">
            <img src='<%= user_avatar ? user_avatar : "{{asset('/img/default.jpg')}}" %>'>
        </div>
        <div class="user-info content-slide">
            <h3 class="user-name" href="{{asset('/user/')}}/<%= user_name %>"><%= user_name %></h3>
            <h5 class="user-birthday"><%= user_birthday %></h5>
            <p class="text-left"><%= user_alias %></p>
        </div>
    </div>

    <div class="control-menu">
        <span class="btn btn-success"><i class="fa fa-plus"/></span>
    </div>
</script>

<script>
var BOX_WIDTH = 220,
    BOX_HEIGHT = 300,
    RATIO = 1.2,
    BOX_WIDTH_COVER = BOX_WIDTH * RATIO,
    BOX_HEIGHT_COVER = BOX_HEIGHT * RATIO;


var maxWidth = $(window).width(), maxHeight = $(window).height();

var el;
var u_arr = <?php echo !empty($user) ? json_encode($user) : "[]" ?>;
var ur_arr = <?php echo !empty($userRelative) ? json_encode($userRelative) : "[]" ?>;
var recipe = _.template($("script.map-view-el-tp").html());

$(document).ready(function(){
    var r = Raphael("holder", 800, 800);

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

    var k = 0;
    for (i = 0; i < uRank.length; i++) {
        if (typeof uRank[i] != "undefined") {

            px = typeof uBox[k-1] != "undefined" ? uBox[k-1].x : maxWidth / 2 - BOX_WIDTH_COVER / 2;
            py = typeof uBox[k-1] != "undefined" ? uBox[k-1].y + BOX_HEIGHT * 1.2 : 50;
            maxHeight = Math.max(maxHeight, py + BOX_HEIGHT_COVER);

            var ofFlag = false;
            ofFlag = px + (1/2 - uRank[i].length / 2) * BOX_WIDTH_COVER < 0 ? true : false;
            if (!ofFlag) {
                maxWidth = Math.max(maxWidth, px + uRank[i].length / 2 * BOX_WIDTH_COVER);

                for (j = 0; j < uRank[i].length; j++) {
                    color = Raphael.getColor();
                    tmp = new Infobox(r, {
                        name: "user" + uRank[i][j].user_id,
                        x: px + (j + 1/2 - uRank[i].length / 2) * BOX_WIDTH_COVER,
                        y: py
                    }, {fill: "green", stroke: color, "fill-opacity": 0, "stroke-width": 2, cursor: "mouse"});
                    tmp.div.html(recipe({
                        user_name: uRank[i][j].user_name,
                        user_avatar: uRank[i][j].user_avatar,
                        user_birthday: uRank[i][j].user_birthday,
                        user_alias: uRank[i][j].user_alias1
                    }));
                    tmp.container.drag(move, dragger, up);

                    uBox[k] = tmp;
                    k++;
                }
            } else {
                maxWidth = Math.max(maxWidth, (1/2 + uRank[i].length) * BOX_WIDTH_COVER);

                for (j = 0; j < uRank[i].length; j++) {
                    color = Raphael.getColor();
                    tmp = new Infobox(r, {
                        name: "user" + uRank[i][j].user_id,
                        x: (j + 1/2) * BOX_WIDTH_COVER,
                        y: py
                    }, {fill: "green", stroke: color, "fill-opacity": 0, "stroke-width": 2, cursor: "mouse"});
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
    }

    r.setSize(maxWidth, maxHeight);

    console.log(uBox);
    window.abc= uBox;

    for (i = 0; i < ur_arr.length; i++) {
        var src = $.grep(uBox, function(e){ return e.name == "user" + ur_arr[i].user_id1; });
        var des = $.grep(uBox, function(e){ return e.name == "user" + ur_arr[i].user_id2; });
        connections.push(r.connection(src[0].container, des[0].container, "green"));
    }


});



</script>
@stop

