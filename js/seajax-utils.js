/*No5={};No5.Seajax={};No5.Seajax.Shapes={};No5.Seajax.Tilesource={};No5.Seajax.toImageCoordinates=function(a,b,c){a=No5.Seajax.Dimension(a);return new Seadragon.Point(b*a,c*a)};No5.Seajax.toWorldCoordinates=function(a,b,c){a=No5.Seajax.Dimension(a);return new Seadragon.Point(b/a,c/a)};No5.Seajax.Dimension=function(a){return a.source.width>a.source.height?a.source.width:a.source.height};No5.Seajax.Shapes.Marker=function(a){this.img=document.createElement("img");this.img.src=a};No5.Seajax.Shapes.Marker.prototype.attachTo=function(a,b,c){b=No5.Seajax.toWorldCoordinates(a,b,c);a.drawer.addOverlay(this.img,b,Seadragon.OverlayPlacement.BOTTOM)};No5.Seajax.Shapes.Marker.prototype.addEventListener=function(a,b){Seadragon.Utils.addEvent(this.img,a,Seadragon.Utils.stopEvent);this.img.addEventListener(a,b,!1)};No5.Seajax.Shapes.Ellipse=function(a,b){this.width=a;this.height=b;this.div=document.createElement("div");var c=Raphael(this.div,a,b),d=viewer.viewport.getMaxZoom();this.ellipse=c.ellipse(a/(2*d),b/(2*d),a/(2*d),b/(2*d));this.ellipse.node.style.cursor="pointer"};
No5.Seajax.Shapes.Ellipse.prototype.attachTo=function(a,b,c){b=No5.Seajax.toWorldCoordinates(a,b,c);c=No5.Seajax.toWorldCoordinates(a,this.width,this.height);a.drawer.addOverlay(this.div,new Seadragon.Rect(b.x,b.y,c.x,c.y));var d=this.ellipse;a.addEventListener("animation",function(){var b=a.viewport.getZoom(!0);d.scale(b,b,0,0)})};No5.Seajax.Shapes.Ellipse.prototype.getElement=function(){return this.ellipse};
No5.Seajax.Shapes.Ellipse.prototype.redraw=function(a){a=a.viewport.getZoom(!0);this.ellipse.scale(a,a,0,0)};No5.Seajax.Shapes.Ellipse.prototype.addEventListener=function(a,b){Seadragon.Utils.addEvent(this.div,a,Seadragon.Utils.stopEvent);this.img.addEventListener(a,b,!1)};No5.Seajax.Shapes.Polygon=function(a){for(var b=a[0].x,c=b,d=a[0].y,f=d,e=1,h=a.length;e<h;++e){if(a[e].x<b)b=a[e].x;if(a[e].x>c)c=a[e].x;if(a[e].y<d)d=a[e].y;if(a[e].y>f)f=a[e].y}this.origin=new Seadragon.Point(b,d);this.width=c-b;this.height=f-d;c=viewer.viewport.getMaxZoom();this.normWidth=2*this.width/c;this.normHeight=2*this.height/c;this.div=document.createElement("div");this.paper=Raphael(this.div);for(var f=2*(a[0].x-b)/c+" "+2*(a[0].y-d)/c,g="M"+f,e=1,h=a.length;e<h;++e)g+="L"+2*(a[e].x-
b)/c+" "+2*(a[e].y-d)/c;g+="L"+f;this.path=this.paper.path(g);this.paper.setSize(this.normWidth,this.normHeight)};No5.Seajax.Shapes.Polygon.prototype.attachTo=function(a){var b=No5.Seajax.toWorldCoordinates(a,this.origin.x,this.origin.y);a.drawer.addOverlay(this.div,new Seadragon.Rect(b.x,b.y,0,0));var c=this.paper,d=this.path,f=this.normWidth,e=this.normHeight;a.addEventListener("animation",function(){var b=a.viewport.getZoom(!0);c.setSize(f*b,e*b);d.scale(b,b,0,0)})};
No5.Seajax.Shapes.Polygon.prototype.getElement=function(){return this.path};No5.Seajax.Shapes.Polygon.prototype.redraw=function(a){a=a.viewport.getZoom(!0);this.paper.setSize(this.normWidth*a,this.normHeight*a);this.path.scale(a,a,0,0)};No5.Seajax.Shapes.Polygon.prototype.addEventListener=function(a,b){Seadragon.Utils.addEvent(this.div,a,Seadragon.Utils.stopEvent);this.div.addEventListener(a,b,!1)};No5.Seajax.Tilesource.OSM=function(){var a=new Seadragon.TileSource(65572864,65572864,256,0);a.getTileUrl=function(a,c,d){return"http://tile.openstreetmap.org/"+(a-8)+"/"+c+"/"+d+".png"};return a};No5.Seajax.Tilesource.TMS=function(a,b,c){var b=Math.ceil(b/256)*256,c=Math.ceil(c/256)*256,d=Math.ceil(Math.log(b>c?b/256:c/256)/Math.log(2)),f=c/256,c=new Seadragon.TileSource(b,c,256,0);c.getTileUrl=function(b,c,g){b-=8;return a+b+"/"+c+"/"+(Math.ceil(f/Math.pow(2,d-b))-1-g)+".jpg"};return c};
*/

/**
 * Namespace declarations
 */
No5 = {};
No5.Seajax = {};
No5.Seajax.Shapes = {};
No5.Seajax.Tilesource = {};

/**
 * Translates from Seajax viewer coordinate 
 * system to image coordinate system 
 */
No5.Seajax.toImageCoordinates = function(viewer, viewerX, viewerY) {
   //console.log(this);
   return new Seadragon.Point(viewerX * viewer.source.width, viewerY * viewer.source.height * viewer.source.aspectRatio);
}

/**
 * Translates from image coordinate system to
 * Seajax viewer coordinate system 
 */
No5.Seajax.toWorldCoordinates = function(viewer, imageX, imageY) {
   //console.log(this);
   return new Seadragon.Point(imageX / viewer.source.width, imageY / viewer.source.height / viewer.source.aspectRatio);
}

No5.Seajax.Shapes.Polygon = function(points, viewer) {
   // Get polygon bounding box
   var minX = points[0].x;
   var maxX = minX;
   var minY = points[0].y;
   var maxY = minY;

   for (var i=1, len = points.length; i<len; ++i) {
      if (points[i].x < minX)
         minX = points[i].x;

      if (points[i].x > maxX)
         maxX = points[i].x;

      if (points[i].y < minY)
         minY = points[i].y;

      if (points[i].y > maxY)
         maxY = points[i].y;
   }

   this.origin = new Seadragon.Point(minX, minY);

   // Bounding box width and height at maximum zoom
   this.width = maxX - minX;
   this.height = maxY - minY;

   // Bounding box width and height at zoom level 1
   var maxZoom = viewer.viewport.getMaxZoom();
   this.normWidth = 2 * this.width / maxZoom;
   this.normHeight = 2 * this.height / maxZoom

   // Create Polygon
   this.div = document.createElement("div");
   this.paper = Raphael(this.div);

   // NOTE! There seems to be a factor of 2 required. Might be because of the way
   // Zoom levels are defined in Seajax. But frankly I don't know -> investigate!!
   var firstPoint = 2 * (points[0].x - minX) / maxZoom + " " + 2 * (points[0].y - minY) / maxZoom;

   var svgFormattedPath = "M" + firstPoint;
   for (var i=1, len = points.length; i<len; ++i) {
      svgFormattedPath += "L" + 2 * (points[i].x - minX) / maxZoom + " " + 2 * (points[i].y - minY) / maxZoom;
   }
   svgFormattedPath += "L" + firstPoint;

   this.path = this.paper.path(svgFormattedPath);
   this.paper.setSize(this.normWidth, this.normHeight);
}

No5.Seajax.Shapes.Polygon.prototype.attachTo = function(viewer) {
   var anchor = No5.Seajax.toWorldCoordinates(viewer, this.origin.x, this.origin.y);
   viewer.drawer.addOverlay(this.div, new Seadragon.Rect(anchor.x, anchor.y, 0, 0)); 

   var canvas = this.paper;
   var p = this.path;
   var w = this.normWidth;
   var h = this.normHeight;
   viewer.addEventListener("animation", function() { 
      var zoom = viewer.viewport.getZoom(true);
      canvas.setSize(w * zoom, h * zoom);
      p.scale(zoom, zoom, 0, 0);
   });
}

No5.Seajax.Shapes.Polygon.prototype.getElement = function() {
   return this.path;
}

No5.Seajax.Shapes.Polygon.prototype.redraw = function(viewer) {
   var zoom = viewer.viewport.getZoom(true);
   this.paper.setSize(this.normWidth * zoom, this.normHeight * zoom);
   this.path.scale(zoom, zoom, 0, 0); 
} 

No5.Seajax.Shapes.Polygon.prototype.addEventListener = function(evt, listener) {
   Seadragon.Utils.addEvent(this.div, evt, Seadragon.Utils.stopEvent);
   this.div.addEventListener(evt, listener, false);
}