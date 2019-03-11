=== The Social Links ===
Contributors: leapsandbounds, Seags, leogopal, hayleydia
Tags: social, social bookmarks, social links, social networking
Requires at least: 3.8
Tested up to: 4.8.3
Stable tag: 1.3.1
Requires PHP: 5.6
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Social Links plugin adds a widget and shortcode to your WordPress website allowing you to display icons linking to your social profiles.

== Description ==

**Note: Development for The Social Links happens on [Github](https://github.com/flickerleap/the-social-links). Please submit an issue there.**

The Social Links plugin adds a widget and shortcode to your WordPress website allowing you to display icons linking to your social profiles. The new version includes the following social networks:

* Google+
* Facebook
* Twitter
* Linkedin
* YouTube
* Instagram
* Pinterest
* Behance
* Bitcoin
* Delicious
* DeviantArt
* Digg
* Dribble
* Flickr
* Foursquare
* GitHub
* LastFM
* Medium
* Skype
* Soundcloud
* Spotify
* Tumblr
* Vine
* WordPress

We've also added support for a **shortcode** (`[the-social-links]`) for use in WordPress posts and pages and a **custom template tag** (`<?php the_social_links();?>`) for use in template files.

**The Social Links is translation ready!**

== Installation ==

Installation via WordPress Dashboard:

1. Navigate to Plugins->Add New
1. Search for "The Social Links" and click "Install Now"
1. Click “Settings” or browse to the "The Social Links" once you have installed the plugin to configure your social network links.
1. Go to your widgets and add the "The Social Links" widget to your sidebar, add the shortcode (`[the-social-links]`) in your posts and pages or add the custom template tag (`<?php the_social_links();?>`) in your template files.

== Frequently Asked Questions ==

= How do I add the bookmarks to my theme manually (also for themes that do not support widgets)? =

Using the shortcode (`[the-social-links]`) in your posts and pages or by adding the custom template tag (`<?php the_social_links();?>`) in your template files.

= Are you going to add other social networks? =

To keep the plugin as fluid as possible we have decided to minimise the amount of networks in the free version. If you have a network that you think should be added, please head to our [support page](http://digitalleap.co.za/wordpress/plugin/the-social-links/) to submit a request.

= Can I use other icons? =

We have decided that to keep a high standard for the plugin we will be using font based icons. All icons are styled by CSS. We are developing additional premium icon packs that you will be able to purchase.

= Can I change the order of the icons? =

You can change the order on your settings page.


== Screenshots ==

1. Settings Page
2. Default Social Links

== Changelog ==

= 1.3.1 =

* Fixed type hinting error (we're not there yet, captain)

= 1.3.0 =

* We added all social networks in the pack
* Preparation for some new features to come

= 1.2.9 =
* Added more social networks

= 1.2.8 =
* Removed escaping of widget output

= 1.2.7 =
* Fixed a bug that prevented the widgets from loading

= 1.2.6 =
* Fixed readme.txt

= 1.2.4 =
* Removed '_' when there shouldn't have been one.
* Notify users that support is now done on Github.

= 1.2.3 =
* Fixed static function error on shortcode

= 1.2 =
* Released quality code (according to WordPress Coding Standards)
* Added unit testing

== Upgrade Notice ==
This version includes support for shortcodes and custom template tags. We've also added support to define what order you want the social links in. ** This removes support for some social networks so please back up before updating **
