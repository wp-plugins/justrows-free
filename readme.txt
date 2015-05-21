=== JustRows free ===
Contributors: canaveralstudio
Tags: gallery, image grid, google images, images, image, grid, responsive, justified, rows, pictures, thumbnails, shortcode, posts
Requires at least: 3.5
Tested up to: 4.2
Stable tag: 0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

JustRows is a Wordpress plugin that allows to display a gallery of featured images in a grid similar to Google Images.

== Description ==
JustRows takes the featured images of posts from a given post type, and shows them as a responsive image grid.
The generated galleries may be customized using one of many user-created configurations.

* images are placed in order (by date or other fields)
* all images are resized via javascript so that each row is composed of thumbnails of the same height, also all rows except the last one are justified
* new images can be appended, either with a button or infinite scrolling (depending on the settings)
* users can define multiple configurations
* users can place a gallery through a widget or a shortcode

= Create a configuration =

1. In the "JustRows" menu on the left, click "Create configuration".
1. The "Add new configuration" panel will open.
1. Give the configuration a name and change the options as you see fit, then hit the "Save" button at the bottom.

= Show the gallery =

Place the JustRows widget somewhere in your template, or use the shortcode [justrows slug="default"] (where "default" is the slug of your configuration).

== Installation ==
1. Upload the "justrows-free" folder to the "/wp-content/plugins/" directory.
1. Activate the plugin through the "Plugins" menu in WordPress.
1. Place the JustRows widget somewhere in your template, or use the shortcode [justrows slug="default"] (where "default" is the slug of the JustRows configuration)
1. Make sure that the posts used by the configuration have a featured image

== Screenshots ==
1. Example of a gallery generated with JustRows.
2. Editing a gallery configuration with JustRows.
3. Example of a gallery generated with JustRows inside a wordpress site.

== Changelog ==
= 0.1 =
* Initial release.
= 0.2 =
* Added "caption visible" layouts.

== Upgrade Notice ==
= 0.1 =
This version is the initial release.
= 0.2 =
Added "caption visible" layouts.