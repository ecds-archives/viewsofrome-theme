#Views of Rome

##About
A child theme based on the [Responsive](http://wordpress.org/extend/themes/responsive) developed for the Digital Scholarship Commons of Emory University for the Views of Rome project.

This theme provides an indepth, interactive view into the Ligorio Map of Rome. Technologies utilized to make this possible are Wordpress, Seadragon Ajax and Seajax

###Dependencies

* [Wordpress](http://www.wordpress.org)
* [Responsive](http://themes.svn.wordpress.org/responsive)
* The Ligorio DeepZoom image files. This theme assumes these files are located at 
* 		$WORDPRESS_ROOT/images/map

##Deployment	
1. From a terminal, cd to themes directory and run the following command

		git clone https://github.com/emory-libraries-disc/viewsofrome-theme.git

2. Activate the theme through your WordPress admin panel

##Development Requirements
1. A working Wordpress install.
2. The Responsive Theme
3. The Views of Rome Theme

#####Optional
* For EUL.OverlayManager development, a DeepZoom image xml file and associated images must be present.
	1. Create a Page in the admin panel and assign the map manager template.
	2. Publish page and the Map Manager will now be available for use.

