//TODO:
//  option to specify xml file loc for dzi
//  actually be able to override options hash
//  refactor _addOverlayToDZI

// TODO: check if dependencies are loaded
// TODO: add more color options

// prototype of peek method to enhance code readability

/**
 * Returns the last element in an array without removing it
 *
 * @return {Object}
 */
Array.prototype.peek = function() {
    if (this.length <= 0)
        return undefined;
    return this[this.length - 1];
}

var $ = jQuery.noConflict();

var EUL = {};
EUL.Utils = {};

/**
 * EUL.Utils.Colors
 *
 */
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
        "#FF0000", 
        "#00FF00", 
        "#0000FF",
        "#990000", 
        "#009900", 
        "#000099"
    ]
}

EUL.Utils.Colors.RED        = EUL.Utils.Colors.choices[0];
EUL.Utils.Colors.GREEN      = EUL.Utils.Colors.choices[1];
EUL.Utils.Colors.BLUE       = EUL.Utils.Colors.choices[2];


EUL.Utils.Polygon = No5.Seajax.Shapes.Polygon;
EUL.Utils.Marker  = No5.Seajax.Shapes.Marker;

EUL.Utils.ClearTemps = function () {
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
 *  EUL.OverlayManager
 *
 *  @constructor
 *  @this {EUL.OverlayManager}
 *  @params {hash} options The desired options to overrride
 */
EUL.OverlayManager = function(options) {
    var self = this;
    if (typeof jQuery == 'undefined') {
        alert("MapManager requires jQuery to function.");
        return;
    }
    // options
    self.options = {
        precision : 5,
        map_container : "mapcontainer",
        dzi_path: "/vor/images/map/GeneratedImages/dzc_output.xml",
        edit_mode: false,
        center_poly_on_click: true,
        padding: 0.05,
        open_event_callback: function(){}
    }
    jQuery.extend(self.options, options);

    // member vars
    self.viewer = null;
    self.activeOverlay = null;
    self.overlays = [];
    self.newOverlayPoints = [];
    self.data = null;
    self.isDirty = false;

    // open event callback was done to prevent race condition when back button was pressed
    self.viewer = new Seadragon.Viewer(self.options.map_container);
    self.viewer.addEventListener("open", self.options.open_event_callback);
    
    window.viewer = self.viewer;
    self.viewer.openDzi(self.options.dzi_path);

    self.points = [];

    // listeners to print data to screen
    self.viewer.addEventListener("open", self._showViewport);
    self.viewer.addEventListener("animation", self._showViewport);

    //Seadragon.Utils.addEvent(self.viewer.elmt, "mousemove", self.showMouse);
    // listener to add click points to img
    var tempMarker = null;
    self.defaultClickHandler = self.viewer.tracker.clickHandler;

    // TODO: look into only overriding this if we are in edit mode
    self.viewer.tracker.clickHandler = function(tracker, position) {
        if (!self.options.edit_mode) {
            return;
        }
        if (!self.event.shiftKey) {
            return;
        }

        var pixel = Seadragon.Utils.getMousePosition(self.event).minus(Seadragon.Utils.getElementPosition(self.viewer.elmt));
        var point = self.viewer.viewport.pointFromPixel(pixel);
        
        if (!self.points) {self.points = new Array();}

        var newPoint = new No5.Seajax.toImageCoordinates(self.viewer, point.x, point.y);

        self.points.push(newPoint);

        var img = document.createElement("img");
        img.src = "/vor/wp-content/themes/viewsofrome-theme/images/point_marker.gif";
        img.className = 'temp-point';
        
        // $(point.img).addClass('temp-point');
        var anchor = new Seadragon.Point(point.x, point.y);
        var placement = Seadragon.OverlayPlacement.CENTER;
        self.viewer.drawer.addOverlay(img, anchor, placement);
    }
}

EUL.OverlayManager.prototype.showMouse = function(event) {
    var self = this;
    self.event = event;
}

/**
 * Returns the OverlayManager DeepZoom Viewer
 *
 * @return {Seadragon.Viewer} the managers viewer
 */
EUL.OverlayManager.prototype.getViewer = function() {
    var self = this;
    return self.viewer;
}

/**
 * Sets the overlay data for the overlay Manager
 *
 * @this {EUL.OverlayManager}
 * @param {hash} data The json data for the overlays
 */
EUL.OverlayManager.prototype.setData = function(data) {
    var self = this;
    self.data = data;

    for (var i = 0; i < self.data.overlays.length; i++) {
        self.addOverlayFromJSON(self.data.overlays[i]);
    }
}

/**
 * Returns the JSON data for the overlays
 * 
 * @this {EUL.OverlayManager}
 * @return {hash}
 */
EUL.OverlayManager.prototype.getData = function() {
    var self = this;

    return self.data;
}

/**
 * Serialization method that returns hash of new overlays to save
 *
 * @this {EUL.OverlayManager}
 * @return {hash}
 */
EUL.OverlayManager.prototype.serializeOverlays = function() {
    var self = this;

    var tempData = [];
    for (var i = 0; i < self.overlays.length; i++) {
        tempData.push(self.overlays[i].getPointsJSON());
    }

    return tempData;
}

/**
 * Function to reload overalys from data
 *
 * @deprecated
 * @this {EUL.OverlayManager}
 */
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

/**
 * Returns a new EUL.OverlayManager.Overlay from the provided points
 * 
 * @this {EUL.OverlayManager}
 * @params {Array} points Array of Seadragon Points
 * @return {EUL.OverlayManager.Overlay}
 */
EUL.OverlayManager.prototype.getNewOverlayFromPoints = function(points) {
    var self = this;

    var viewer = self.viewer; // hack because Seajax uses global viewer for this

    // get the polygon and overlay obects for manipulation
    var polygon = new EUL.Utils.Polygon(points, self.viewer);
    var overlay = new EUL.OverlayManager.Overlay();

    overlay.polygon = polygon;
    overlay.points = points.slice();

    // TODO: look into default classes and adding htem to the dom?
    // set polygon's fill color
    var fillColor = (self.options.edit_mode) ? EUL.Utils.Colors.getColor() : EUL.Utils.Colors.BLUE;
    overlay.polygon.getElement().attr({
        "fill" : fillColor, 
        "fill-opacity" : 0.5
    });
    $(overlay.polygon.div).addClass("overlay-div");

    // event handlers for the overlay
    var polyElement = overlay.polygon.getElement();
    polyElement.node.onmouseover = function() {
        polyElement.attr({
            'fill': '#fff'
        });
    }

    polyElement.node.onmouseout = function() {
        // early exit so we don't reset the activated overlay
        if (self.activeOverlay == overlay) 
            return;

        polyElement.attr({
            'fill': fillColor
        });
        
    }

    // TODO: this shoudl be refactored in case we want to be able to use options.overlay_click_callback in manager mode
    if (!self.options.edit_mode) {
        polyElement.node.onclick = function() {
            self.options.overlay_click_callback(overlay);

            // reset activeOverlay fill color
            if (self.activeOverlay != null) {
                self.activeOverlay.polygon.getElement().attr({
                    'fill': fillColor
                });
            }
            self.activeOverlay = overlay;
            overlay.polygon.getElement().attr({
                'fill': '#fff'
            });

            // TODO: do we want this functionality in manager mode as well?
            if (self.options.center_poly_on_click) {
                var poly = overlay.polygon;
                var overlayOrigin = No5.Seajax.toWorldCoordinates(self.viewer, poly.origin.x, poly.origin.y);
                var overlayDims = No5.Seajax.toWorldCoordinates(self.viewer, poly.width, poly.height);

                // compute bounding rectangle for overlay so that we can zoom 
                // and pan to ensure it's in the viewport
                var boundingRect = new Seadragon.Rect(
                    overlayOrigin.x - self.options.padding,      // origin x
                    overlayOrigin.y - self.options.padding,      // origin y
                    overlayDims.x + (2 * self.options.padding),  // padding area width
                    overlayDims.y + (2 * self.options.padding)   // padding area height
                );

                self.viewer.viewport.fitBounds(boundingRect);
            }
        }
    }

    return overlay;
}

/** 
 * Adds a new Overlay Manager div to the Overlay Manager legend
 *
 * @this {EUL.OverlayManager}
 */
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
        "margin": "0 0 0 15px",
        "float": "left"
    });
    removeLink.html("Remove this Overlay");
    removeLink.click(function() {
        self.destroyOverlay(overlay);
        self.overlays = self.overlays.splice(1, self.overlays.indexOf(overlay));
        $(div).remove();
    });

    removeLink.hover(overlay.polygon.getElement().node.onmouseover);
    removeLink.mouseout(overlay.polygon.getElement().node.onmouseout);

    div.hover(overlay.polygon.getElement().node.onmouseover);
    div.mouseout(overlay.polygon.getElement().node.onmouseout);

    legend.hover(overlay.polygon.getElement().node.onmouseover);
    legend.mouseout(overlay.polygon.getElement().node.onmouseout);

    div.append(removeLink);
    div.append("<div style='clear:both;'></div>");
    $("#overlay-staging").append(div);
}

/**
 * Adds a new overlay to the deep zoom image
 *
 * @this {EUL.OverlayManager}
 */
EUL.OverlayManager.prototype._addOverlayToDZI = function(newOverlay) {
    var self = this;
    
    var overlay = (newOverlay == undefined) ? self.getNewOverlayFromPoints(self.points) : newOverlay;

    // attach overlay to the map
    overlay.polygon.attachTo(self.viewer);

    // push to overlays for serialization
    self.overlays.push(overlay);

    self.addOMDiv(overlay);

    setTimeout(function() {
        overlay.polygon.redraw(self.viewer);
    }, 500);

    //clear temp points
    self.points = [];
    $('img.temp-point').each(function(index, item) {
        self.viewer.drawer.removeOverlay(item);
    });
}

/**
 * Adds a new overlay from json, used on the front page
 *
 * @this {EUL.OverlayManager}
 * @param {hash} json A JSON object denoting a serialized overlay
 */
EUL.OverlayManager.prototype.addOverlayFromJSON = function(json) {
    var self = this;
    var points = [];
    for (var i = 0; i < json.coords.points.length; i++ ) {
        var tempPoint = new No5.Seajax.toWorldCoordinates(
            self.viewer, 
            parseFloat(json.coords.points[i].x), 
            parseFloat(json.coords.points[i].y)
        );
        var tempPoint = new No5.Seajax.toImageCoordinates(self.viewer, tempPoint.x, tempPoint.y);

        points.push(tempPoint);
    }

    var overlay = self.getNewOverlayFromPoints(points);
    overlay.id = json.id;

    self._addOverlayToDZI(overlay);

    //setTimeout(function() {
        overlay.polygon.redraw(self.viewer);
    //}, 500);
}

/**
 * Removes all overlays from the DZI as well as destroys their corresponding Javascript objects
 *
 * @this {EUL.OverlayManager}
 * @param {boolean} remove_manager_divs A boolean flag to be used in the even that we are in edit mode
 */
EUL.OverlayManager.prototype.destroyOverlays = function(remove_manager_divs) {
    var self = this;

    for (var i = 0; i < self.overlays.length; i++) {
        self.destroyOverlay(self.overlays[i]);
    }

    if (remove_manager_divs) {
        $(".remove-link").remove();
    }
    self.overlays = [];
}

/**
 * Destroys a single overlay and its objects
 *
 * @this {EUL.OverlayManager}
 * @param {EUL.OverlayManager.Overlay} overlay
 */
// TODO: consider moving to Overlay class
EUL.OverlayManager.prototype.destroyOverlay = function(overlay) {
    var self = this;
    // remove overlay
    self.viewer.drawer.removeOverlay(overlay.polygon.div);
}

/**
 * EUL.OverlayManager.Overlay
 *
 * @constructor
 * @this {EUL.OverlayManager.Overlay}
 * @param {number} id The numerical ID of the article for this overlay in the database
 * @param {hash} category
 * @param {Array} points An array of points for this overlay
 * @param {polygon} polygon the No5.Shapes.Polygon object for this overlay
 */
EUL.OverlayManager.Overlay = function(id, category, points, polygon) {
    var self = this;

    self.id = (id != 'undefined') ? id : null;
    self.category = (category != 'undefined') ? category : null;
    self.points = (points != 'undefined') ? points : null;
    self.polygon = (polygon != 'undefined') ? polygon : null;
}

/**
 * Returns a JSON hash for this overlay
 * 
 * @this {EUL.OverlayManager.Overlay}
 * @return {hash}
 */
EUL.OverlayManager.Overlay.prototype.getPointsJSON = function() {
    var self = this;

    var pointsArray = [];
    for (i = 0; i < self.points.length; i++) {
        var temp = {};
        temp.x = self.points[i].x;
        temp.y = self.points[i].y;

        pointsArray.push(temp);
    }
    return pointsArray;
}

/**
 * Returns the No5.Shapes.Polygon object for this overlay
 *
 * @this {EUL.OverlayManager.Overlay}
 * @return {No5.Shapes.Polygon}
 */
EUL.OverlayManager.Overlay.prototype.getPolygon = function() {
    var self = this;
    return self.polygon;
}



