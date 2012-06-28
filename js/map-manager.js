//TODO:
//  option to specify xml file loc for dzi
//  actually be able to override options hash
//  

var $ = jQuery.noConflict();

var EUL = {};
EUL.Utils = {};

EUL.Utils.Colors = {
    getColor: function() {
        var self = this;

        if (self.index == self.choices.length)
            self.index = 0;
        val = self.choices[self.index];    
        self.index++;
        return val;
    },
    index : 0,
    choices : [
        "#FF0000", "#00FF00", "#0000FF",
        "#990000", "#009900", "#000099"
    ]
}

EUL.OverlayManager = function(map_container) {
    var self = this;
    if (typeof jQuery == 'undefined') {
        alert("MapManager requires jQuery to function.");
        return;
    }
    // options
    self.options = {
        precision : 5,

    }
    // member vars
    self.viewer = null;
    self.overlays = [];
    self.newOverlays = [];
    self.data = null;
    self.isDirty = false;

    self.viewer = new Seadragon.Viewer("mapcontainer");
    self.viewer.openDzi("/images/map/GeneratedImages/dzc_output.xml");

    self.points = [];

    // listeners to print data to screen
    self.viewer.addEventListener("open", self._showViewport);
    self.viewer.addEventListener("animation", self._showViewport);
    
    // listener to add click points to img
    self.viewer.tracker.clickHandler = function(tracker, position) {
        console.log("clickHandler")
        var pixel = Seadragon.Utils.getMousePosition(event).minus(Seadragon.Utils.getElementPosition(self.viewer.elmt));
        var point = self.viewer.viewport.pointFromPixel(pixel);
        if (!self.points) self.points = new Array();
        self.points.push(new No5.Seajax.toImageCoordinates(self.viewer, point.x, point.y));
    };
}

EUL.OverlayManager.prototype.getViewer = function() {
    var self = this;
    return self.viewer;
}

EUL.OverlayManager.prototype.getData = function() {
    var self = this;

    return self.data;
}

EUL.OverlayManager.prototype.reloadData = function() {
    var self = this;
    // TODO: peform ajax to reload data and init new overlays
    // after destroying old overlays

    self.overlays = []
    /*
    for (i in data) {
        //create overlay from points
        // self.overlays.push(overlay);
    }
    */
}

EUL.OverlayManager.prototype._addOverlayToDZI = function() {
    var self = this;

    viewer = self.viewer; // hackk because Seajax uses global viewer which is dumb
    var polygon = new No5.Seajax.Shapes.Polygon(self.points);
            
    var overlay = new EUL.OverlayManager.Overlay();
    overlay.polygon = polygon;

    var fillColor = EUL.Utils.Colors.getColor();
    overlay.polygon.getElement().attr({"fill":fillColor, "fill-opacity":0.5});

    // event handlers for overlay
    overlay.polygon.getElement().node.onmouseover = function() {
        overlay.polygon.getElement().attr({'fill': '#fff'});
    }
    overlay.polygon.getElement().node.onmouseout = function() {
        overlay.polygon.getElement().attr({'fill': fillColor});
        console.log(overlay.polygon.getElement());
    }
    overlay.polygon.getElement().node.onclick = function() {
        console.log(overlay.polygon);
    }

    // attach overlay to the map
    overlay.polygon.attachTo(self.viewer);

    self.newOverlays.push(overlay);

    // add div to overlays group
    var div = $("<div>");
    div.addClass("remove-link");
    var legend = $("<div>");
    legend.css({
        "width" : "20px",
        "height" : "20px",
        "background-color": overlay.polygon.getElement().attr('fill'),
        "opacity": 0.5,
        "float": "left",
        "margin-right": "10px;"
    });
    div.append(legend);
    div.css({
        "border": "1px solid",
        "border-color": overlay.polygon.getElement().attr('fill'),
        "color": "#000"
    });
    var removeLink = $("<a>");
    removeLink.css({
        "float": "left"
    });
    removeLink.html("Remove this Overlay");
    removeLink.click(function() {
        console.log("Destroying Overlay");
        //$(this).parents('.remove-link').remove();
        self.destroyOverlay(overlay);
        $(div).remove();

    });
    removeLink.hover(overlay.polygon.getElement().node.onmouseover);
    removeLink.mouseout(overlay.polygon.getElement().node.onmouseout);

    div.append(removeLink);
    div.append("<div style='clear:both;'></div>");
    $("#overlay-staging").append(div);

    setTimeout(function() {
        overlay.polygon.redraw(self.viewer);
    }, 500);

    self.points = [];
}

EUL.OverlayManager.prototype.addOverlayFromJSON = function() {

}

EUL.OverlayManager.prototype.destroyOverlay = function(overlay) {
    var self = this;
    self.newOverlays.splice(self.newOverlays.indexOf(overlay), 1);
    overlay.polygon.div.parentNode.removeChild(overlay.polygon.div);
    $(overlay.polygon.getElement().node).remove();
    delete overlay.polygon;
    delete overlay;
}
EUL.OverlayManager.Overlay = function(id, category, points, polygon) {
    var self = this;

    self.id = (id != 'undefined') ? id : null;
    self.category = (category != 'undefined') ? category : null;
    self.points = (points != 'undefined') ? points : null;
    self.polygon = (polygon != 'undefined') ? polygon : null;
}

EUL.OverlayManager.Overlay.prototype.getPolygon = function() {
    var self = this;
    return self.polygon;
}



