//TODO:
//  option to specify xml file loc for dzi
//  actually be able to override options hash
//  refactor _addOverlayToDZI

// TODO: check if dependencies are loaded
Array.prototype.peek = function() {
    if (this.length <= 0)
        return undefined;
    return this[this.length - 1];
}

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


EUL.Utils.Polygon = No5.Seajax.Shapes.Polygon;
EUL.Utils.Marker  = No5.Seajax.Shapes.Marker;

EUL.Utils.ClearTemps = function () {
    console.log('attempting to remove point');
    var temp = $('.temp-point');
    $(temp).remove();
}

EUL.Utils.clone = function(obj) {
    if (null == obj || "object" != typeof obj) return obj;
    var copy = obj.constructor();
    for (var attr in obj) {
        if (obj.hasOwnProperty(attr)) copy[attr] = obj[attr];
    }
    return copy;
}
/**
 *  EUL.OverlayManager constructor
 *
 *
 */
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
    self.newOverlayPoints = [];
    self.data = null;
    self.isDirty = false;

    self.viewer = new Seadragon.Viewer("mapcontainer");
    self.viewer.openDzi("/images/map/GeneratedImages/dzc_output.xml");

    self.points = [];

    // listeners to print data to screen
    self.viewer.addEventListener("open", self._showViewport);
    self.viewer.addEventListener("animation", self._showViewport);
    //Seadragon.Utils.addEvent(self.viewer.elmt, "mousemove", self.showMouse);
    // listener to add click points to img
    var tempMarker = null;
    self.viewer.tracker.clickHandler = function(tracker, position) {
        var pixel = Seadragon.Utils.getMousePosition(self.event).minus(Seadragon.Utils.getElementPosition(self.viewer.elmt));
        var point = self.viewer.viewport.pointFromPixel(pixel);
        if (!self.points) {self.points = new Array();}

        var newPoint = new No5.Seajax.toImageCoordinates(self.viewer, point.x, point.y);

        self.points.push(newPoint);

        self.newOverlayPoints.push(
            new EUL.Utils.Marker("/wp-content/themes/viewsofrome-theme/images/point_marker.gif"));
        $(self.newOverlayPoints.peek().img).addClass('temp-point');
        self.newOverlayPoints.peek().attachTo(self.viewer, self.points.peek().x, self.points.peek().y);
    }
}

EUL.OverlayManager.prototype.showMouse = function(event) {
    var self = this;
    self.event = event;
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

EUL.OverlayManager.prototype.getNewOverlayFromPoints = function(points) {
    console.log(this);
    var self = this;

    var viewer = self.viewer; // hack because Seajax uses global viewer for this

    // get the polygon and overlay obects for manipulation
    var polygon = new EUL.Utils.Polygon(points, self.viewer);
    var overlay = new EUL.OverlayManager.Overlay();

    overlay.polygon = polygon;

    // TODO: look into default classes and adding htem to the dom?
    // set polygon's fill color
    var fillColor = EUL.Utils.Colors.getColor();
    overlay.polygon.getElement().attr({
        "fill" : fillColor, 
        "fill-opacity" : 0.5
    });
    $(overlay.polygon.div).addClass("overlay-div");

    // event handlers for the overlay
    polyElement = overlay.polygon.getElement();
    polyElement.node.onmouseover = function() {
        polyElement.attr({
            'fill': '#fff'
        });
    }

    polyElement.node.onmouseout = function() {
        polyElement.attr({
            'fill': fillColor
        })
    }

    polyElement.node.onclick = function() {}

    return overlay;
}

EUL.OverlayManager.prototype.addOMDiv = function(overlay) {
    var self = this;
    // container corresponding to current overlay
    var div = $("<div>");
    div.addClass("remove-link");

    // legend to visually associate with an overlay
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

    // actual remove link
    var removeLink = $("<a>");
    removeLink.css({
        "float": "left"
    });
    removeLink.html("Remove this Overlay");
    removeLink.click(function() {
        console.log("Destroying Overlay");
        self.destroyOverlay(overlay);
        $(div).remove();
    });

    removeLink.hover(overlay.polygon.getElement().node.onmouseover);
    removeLink.mouseout(overlay.polygon.getElement().node.onmouseout);

    div.append(removeLink);
    div.append("<div style='clear:both;'></div>");
    $("#overlay-staging").append(div);
}

EUL.OverlayManager.prototype._addOverlayToDZI = function() {
    var self = this;
    console.log("points: " + self.points);
    var overlay = self.getNewOverlayFromPoints(self.points);

    // attach overlay to the map
    overlay.polygon.attachTo(self.viewer);

    // push to overlays for serialization
    self.newOverlays.push(overlay);

    self.addOMDiv(overlay);
    
    

    setTimeout(function() {
        overlay.polygon.redraw(self.viewer);
    }, 500);

    self.points = [];

    console.log("point removal");
    self.newOverlayPoints = [];
}

EUL.OverlayManager.prototype.addOverlayFromJSON = function() {

}

// TODO: consider moving to Overlay class
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



