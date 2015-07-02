Raphael.fn.connection = function (obj1, obj2, line, bg) {
    if (obj1.line && obj1.from && obj1.to) {
        line = obj1;
        obj1 = line.from;
        obj2 = line.to;
    }
    var bb1 = obj1.getBBox(),
        bb2 = obj2.getBBox(),
        p = [{x: bb1.x + bb1.width / 2, y: bb1.y - 1},
            {x: bb1.x + bb1.width / 2, y: bb1.y + bb1.height + 1},
            {x: bb1.x - 1, y: bb1.y + bb1.height / 2},
            {x: bb1.x + bb1.width + 1, y: bb1.y + bb1.height / 2},
            {x: bb2.x + bb2.width / 2, y: bb2.y - 1},
            {x: bb2.x + bb2.width / 2, y: bb2.y + bb2.height + 1},
            {x: bb2.x - 1, y: bb2.y + bb2.height / 2},
            {x: bb2.x + bb2.width + 1, y: bb2.y + bb2.height / 2}],
        d = {}, dis = [];
    for (var i = 0; i < 4; i++) {
        for (var j = 4; j < 8; j++) {
            var dx = Math.abs(p[i].x - p[j].x),
                dy = Math.abs(p[i].y - p[j].y);
            if ((i == j - 4) || (((i != 3 && j != 6) || p[i].x < p[j].x) && ((i != 2 && j != 7) || p[i].x > p[j].x) && ((i != 0 && j != 5) || p[i].y > p[j].y) && ((i != 1 && j != 4) || p[i].y < p[j].y))) {
                dis.push(dx + dy);
                d[dis[dis.length - 1]] = [i, j];
            }
        }
    }
    if (dis.length == 0) {
        var res = [0, 4];
    } else {
        res = d[Math.min.apply(Math, dis)];
    }
    var x1 = p[res[0]].x,
        y1 = p[res[0]].y,
        x4 = p[res[1]].x,
        y4 = p[res[1]].y;
    dx = Math.max(Math.abs(x1 - x4) / 2, 10);
    dy = Math.max(Math.abs(y1 - y4) / 2, 10);
    var x2 = [x1, x1, x1 - dx, x1 + dx][res[0]].toFixed(3),
        y2 = [y1 - dy, y1 + dy, y1, y1][res[0]].toFixed(3),
        x3 = [0, 0, 0, 0, x4, x4, x4 - dx, x4 + dx][res[1]].toFixed(3),
        y3 = [0, 0, 0, 0, y1 + dy, y1 - dy, y4, y4][res[1]].toFixed(3);
    var path = ["M", x1.toFixed(3), y1.toFixed(3), "C", x2, y2, x3, y3, x4.toFixed(3), y4.toFixed(3)].join(",");
    if (line && line.line) {
        line.bg && line.bg.attr({path: path});
        line.line.attr({path: path});
    } else {
        var color = typeof line == "string" ? line : "#000";
        return {
            bg: bg && bg.split && this.path(path).attr({stroke: bg.split("|")[0], fill: "none", "stroke-width": bg.split("|")[1] || 3}),
            line: this.path(path).attr({stroke: color, fill: "none"}),
            from: obj1,
            to: obj2
        };
    }
};


function Infobox(r, options, attrs) {
	options = options || {};
	attrs = attrs || {};

	// get data
	this.name = options.name || "";
	this.paper = r;
	this.x = options.x || 0;
	this.y = options.y || 0;
	this.b = options.b || 0;
	this.width = options.width || this.paper.width;
	this.height = options.height || this.paper.height;
	this.rounding = options.rounding || 0;
	this.show_border = options.with_border || true;

	this.container_type = options.container_type || "rect";
	switch (this.container_type) {
	  case "ellipse":
		this.container = this.paper.rect(this.x, this.y, this.width, this.height).attr(attrs);
		break;
	  default: 
		//rect
		this.container = this.paper.rect(this.x, this.y, this.width, this.height, this.rounding).attr(attrs);    
		break;
	}
	
	var container_id = this.container.node.parentNode.parentNode.id;
	container_id = container_id || this.container.node.parentNode.parentNode.parentNode.id;
	this.raph_container = jQuery('#' + container_id);
	this.container.name = this.name;
	this.container.raph_container = this.raph_container;
	
	if (!this.show_border) { this.container.hide(); }
	this.div = jQuery('<div class="infobox" name="' + this.name + '"></div>').insertAfter(this.raph_container);

	jQuery(document).bind('ready', this, function(event) { event.data.update(); });
	jQuery(window).bind('resize', this, function(event) { event.data.update(); });
}

Infobox.prototype.update = function() {
	var offset = this.raph_container.offset();
	this.div.css({
	  'top': (this.y + (this.rounding) + (offset.top)) + 'px',
	  'left': (this.x + (this.rounding) + (offset.left)) + 'px',
	  'height': (this.height - (this.rounding*2) + 'px'),
	  'width': (this.width - (this.rounding*2) + 'px')
	});
}
  
  // Note that the fadein/outs for the content div are at double speed. With frequent animations, it gives the best behavior
Infobox.prototype.show = function() {
	this.container.animate({opacity: 1}, 400, ">");
	this.div.fadeIn(200);
}

Infobox.prototype.hide = function() {
	this.container.animate({opacity: 0}, 400, ">");
	this.div.fadeOut(200);
}
