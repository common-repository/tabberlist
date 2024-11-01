=== Plugin Name ===
Contributors: SimonFairbairn
Donate link: http://www.line-in.co.uk/plugins/tabberlist
Tags: lists, categories, tabs
Requires at least: 2.8
Tested up to: 2.9.1
Stable Tag: 1.0.4

Organise your lists with this funky tabbed list display widget!

== Description ==

TabberList allows you to organise your many lists into a cool tabbed display on any page or post. The tabs are javascript-powered and the default skin uses Sean Catchpole's awesome idTabs.

The plugin is skinnable - you can add custom css and javascript and really make them your own.  It also features the ability to mark out new items and to strikethrough old items.

You can limit the categories by using the attribute 'cat' and a comma-separated list of the ids of the categories you want to include in the shortcode. e.g. 

`[tabberlist cat=1]
[tabberlist cat=1,2,5]`

You can also set a default title for the list in the admin panel and this can be over-ridden using the 'title' attribute as follows:

`[tabberlist title='Here is a Custom List Title']
[tabberlist cat=1,2,5 title='A title just for these categories']`

== Installation ==

1. Upload the contents of `tabberlist` to the `/wp-content/plugins/` directory
1. Activate the plugin through the `Plugins` menu in WordPress
1. Go into your WP Dashboard and, under the 'Settings' menu, you'll see an option for 'TabberList'
1. Create some categories and items.
1. In either a post or a page, use the shortcode [tabberlist] and it will drop in the list automagically.

== Frequently Asked Questions ==

= I can delete categories, but I can't delete list items. What gives? =

Yeah, bit inconsistent here - I'll improve it in later versions. To delete categories, check the delete box and save changes. To delete list items, simply delete the content from the 'item' text field and save changes. The plugin will detect the empty field and remove the corrseponding database entry.

= Eeep. I tried to select individual categories in the shorttag and now I'm getting crazy foreach warnings appearing on my blog =

It's cause the id you're using in the shorttag isn't a valid category ID. If you look in Settings->TabberList and hit the Edit Categories tab, you'll see the available IDs down the side. Use those numbers instead and that should fix it.

= Dude! This rocks! How do I start making skins for it? =

Probably the easiest way is to make a copy of the 'default' skin folder located in wp-content/plugins/tabberlist/skins. Inside that you'll find three files (2 css and 1 javascript) and an images folder. Put any images you want to use in the images folder, then start editing your CSS/js files.

The skin.css file has all of the available classes laid out for you already in a (hopefully) easy to use skeleton structure.

The ie.css is any specific styling that you might need for IE 6.

The skin.js file contains the JabaScript that controls the tabs. I've used Sean Catchpole's idTabs, but you can delete this and use whatever takes your fancy.

Once you're done editing, send me a message over at http://www.line-in.co.uk - I'd love to see what you've made!

== Screenshots ==

1. The default skin placed in a regular post
2. The TabberList admin area


== Changelog ==

= 1.0.4 =
* Minor CSS changes for a more consistent display across themes
* Fixed the foreach() errors that happen when there are no defined categories/list items
* IE6 CSS bug fixes for the default skin

= 1.0.3 =
* Addresses some conflict issues

= 1.0.2 =
* Changed the default skin to something a little more generic

= 1.0.1 =
* Added support for ie6 specific stylesheet

= 1.0.0 =
* First release
