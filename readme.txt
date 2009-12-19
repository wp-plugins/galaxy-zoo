=== Plugin Name ===
Contributors: Tomas Vorobjov
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9965032
Tags: Galaxy Zoo, astronomy, stats
Requires at least: 2.8
Tested up to: 2.9
Stable tag: 1.0b

This plugin offers two WordPress widgets through which users can display data
from their accounts on Galaxy Zoo. 

== Description ==

This plugin offers two WordPress widgets through which users can display data
from their accounts on Galaxy Zoo. 

The first widget, 'Galaxy Zoo', display general stats such as the username, 
the date the user joined Galaxy Zoo, the number of galaxy classifications 
and the latest classified galaxy.

The second widget, 'Galaxy Zoo Favorites', displays thumbnail images of 
galaxies the user selected as his/er favorites (using the Galaxy Zoo's My
Galaxies page - http://galaxyzoo.org/my_galaxies)

Both widget are completely customizable via the widget's control panel and
style-able via a separate css file(s).

Galaxy Zoo is an online astronomy project which invites members of the public 
to assist in classifying over a million galaxies. It is an example of Citizen 
science as it enlists the help of members of the public to help in scientific 
research.

The Galaxy Zoo files contain almost a quarter of a million galaxies which 
have been imaged with a camera attached to a robotic telescope the Sloan 
Digital Sky Survey (http://www.sdss.org/)

**Requirements**

* PHP 5.2.0 or newer (SimpleXml required)
* WordPress 2.8 or newer

== Installation ==

1. Upload the plugin files to your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add the Galaxy Zoo and Galaxy Zoo Favorites widgets to your sidebar via
   the widgets control panel (Appearance -> Widgets)

It is recommened to check the box next to "Use AJAX" improve the loading
time of your blog (if AJAX is disabled, the Galaxy Zoo data will be loaded before
a blog page is displayed, which could add several seconds to the load time) 


== Frequently Asked Questions ==

= How do I get the plugin work with my Galaxy Zoo account =

As of the moment, only selected beta testers from Galaxy Zoo forums were 
given API keys required by the plugin to access Galaxy Zoo account data. If
you would like to stay informed and be notified when the plugin becomes 
available to the general public, sign-up at Galaxy Zoo Forums 
(http://www.galaxyzooforum.org/). 

= How do I change the background on the AJAX preloader =

The preloader can be changed via the plugin's CSS: On you Wordpress Dashboard
select "Plugins" then "Editor", then select "Galaxy Zoo" in the 
"Select plugin to edit". Finally, select the "galaxy-zoo/css/galaxy-zoo.css". 
Now you can edit the plugins global CSS (change made here will be made to both
widgets)... Look for the '.widget-galaxy-zoo-loader' class declaration and
change the value inside the 'url' brackets, e.g. from url(../img/loader-dark.gif)
to url(../img/loader-pink.gif).   

This plugin comes with 2 preloaders, one dark with background color 
#222222, and one white, with background color #FFFFFF (loader-white.gif).
 
If you would like to have a preloader with a different background color
use the online preloader generator at http://www.ajaxload.info/
 
Select "Big Roller" as the "Indicator type", the background color you wish
to have and #FF5D05 for the "Foreground color" (although you may change 
this value as well if you wish). Finally, click on "Download it!".

Once you have the new preloader image saved on your machine, you will need to
upload it to the plugin directory at /wp-content/plugins/galaxy-zoo/img 

== Screenshots ==

1. Galaxy Zoo and the Galaxy Zoo Favorites widgets in a sample wordpress blog sit
2. Detailed view of the Galaxy Zoo and the Galaxy Zoo Favorites widgets
3. Galaxy Zoo Settings view in the dashboard

== ChangeLog ==

= 1.0b =
* The initial release of this plugin. Only a few themes have been tested.