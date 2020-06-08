=== RPS Include Content ===
Contributors: redpixelstudios
Donate link: http://redpixel.com/donate
Tags: duplicate content, copy content, include, include content, includes, multisite, nested content, pull content, red pixel, red pixel studios, redpixelstudios, rps, same content
Requires at least: 3.0
Tested up to: 4.8
Stable tag: 1.1.10
License: GPL3

Makes it easy to pull content from one post or page and place it on another using a simple shortcode, even in a multisite environment.

== Description ==

The RPS Include Content plugin is useful when you need to include the same content across many posts or pages. For example, you may want to place your company boilerplate at the bottom of press releases in your News section. Instead of pasting the boilerplate copy at the end of each of these pages, you can now insert the shortcode (along with the post ID, and for multisite configurations, the blog ID) where that content should appear.

When you modify your source copy, changes will appear on all pages that contain the shortcode. There's no need to open and modify multiple pages.

When a user previews the post from the WordPress Administration, the included content is distinguished with an on-hover highlight. A link is provided so that the source content can be conveniently accessed and modified. The preview of the include will not distort the target page - the width, height and position will remain as defined by the site.

To easily obtain the blog ID and post ID, install the free [RPS Blog Info](http://wordpress.org/extend/plugins/rps-blog-info/) plugin, which places that information (and much more) on your WordPress 3.3 Toolbar.

= Features =
* Include featured image of the included post with optional wrapper and custom class.
* Allows specific shortcodes to display within included posts.
* Allows oEmbeds to display within included posts.
* Respects the more tag in posts automatically.
* Set the length of the content to be displayed by word count.
* Option to remove the hover interface.
* Option to include private content.
* Display the title of the included post without any content.
* Include the title of the included post with or without a hyperlink.
* Include a page or a post in another page or post.
* Specify whether the content or the excerpt should be used.
* Updates made to source content are reflected on all target pages.
* Source content pulled into a page is easily distinguishable and accessible when viewing the page on the front-end while logged into the WordPress back-end.
* Protects against generating include loops and calling nonexistent source content.
* Displays errors on the front-end to logged-in page/post editors.
* Compatible with single and multisite installs.
* Support for password protected posts.
* Strip shortcodes from included posts.
* Default Settings page.

== Installation ==

1. Upload the <code>rps-include-content</code> directory and its containing files to the <code>/wp-content/plugins/</code> directory.
1. Activate the plugin through the "Plugins" menu in WordPress.

== Frequently Asked Questions ==

= How does the RPS Include Content shortcode work? =

The basic syntax of the shortcode is:

`[rps-include blog="#" post="#"]`

You do not need to specify the blog ID if you are not in a multisite environment.

= What other shortcode attributes can be used? =

* **title** - Use a boolean (true/false) to specify whether to display the title of the source content on the target post/pages. The default is *false*.
* **titletag** - Use h1 through h6 to define the tag to wrap around the title. The default is *h2*.
* **titlelink** - Specify whether the titletag should be a link to the included post. The default is *false*.
* **content** - Specify whether to use the included post's *content*, *excerpt*, *lede*, *full* or *none*. The default is *content*. Note that if you set the content to none then the title attribute is automatically set to true, since something of the included content should be shown. Using the *full* option ignores the *more* tag of the included content.
* **length** - Define the length in words of the text that appears before the more link. The default is *55*. This setting has no effect if a more tag is present in the included content.
* **filter** - Force the included content to be formatted. The default is *false*.
* **shortcodes** - Specify whether shortcodes should be allowed to remain in included posts. The default is *true*.
* **allow_shortcodes** - Specify which shortcodes should be allowed to remain in included posts. Overrides the shortcodes attribute. (ie. gallery and caption)
* **embeds** - Specify whether embeds should be displayed in included posts. The default is *false*.
* **more_text** - Specify the string to be presented as the "more text". The default is *Read more ...*.
* **length** - Specify that only a specific number of words should be used before the more link is displayed. Requires that the content attribute be set to *lede*. The default is *55*.
* **hover** - Define whether to show the hover interface on the included content. The default is *true*. You can set the default behavior on the Settings page.
* **private** - Determines if included content with a status of private should be displayed regardless of current user capabilities. The default is *false*. You can set the default behavior on the Settings page.
* **featured_image** - Determines if the featured image associated with the included content is displayed. The default is *false*.
* **featured_image_size** - Specifies the size of the image to retrieve by name. The default is *post-thumbnail*.
* **featured_image_wrap** - Determines if the featured image is wrapped with a div. The default is *false*.
* **featured_image_wrap_class** - Specifies the class associated with the featured image wrap. The default is *post-thumbnail*.

= How do I add the capability "manage_rps_include_content" to roles other than the Administrator role? =

You can use Justin Tadlock's dependable [Members](http://wordpress.org/plugins/members/) plugin to enable the capability for other roles.

= What if my included content loses its formatting? =

It seems that there are cases where the default content filters are not applied before the content is displayed. If this is the case then you simply need to add the shortcode attribute "filter" and set it to "true".

= What if I have shortcodes in my included content that I don't want to display? =

Add the "shortcode" attribute and set it to "false". Keep in mind that using this method will remove all shortcodes from the included content, including any rps-include shortcodes that are designed to call included content that is nested inside other included content. However, this should not affect most users.

`[rps-include post="500" shortcodes="false"]`

You may consider using the "allow_shortcodes" attribute instead to specify which shortcodes should be displayed when the content is included elsewhere. This overrides the "shortcode" attribute setting so there is no need to provide it. Be sure to pass the shortcodes as a comma delimited string as in the example below.

`[rps-include post="500" allow_shortcodes="gallery,caption"]`

= How do I make a url in the included display as an embed? =

As long as the URL is on the WordPress embed whitelist you can make it display within the included content by adding the *embeds* attribute and setting it to *true*.

`[rps-include post="500" embeds="true"]`

= So what if I want to show the title of the source content (blog 2, post 500) wrapped in an h3 with the title being a link? =

`[rps-include blog="2" post="500" title="true" titletag="h3" titlelink="true"]`

= What if I only want to show the title of the source content? =

`[rps-include post="500" content="none"]`

Setting the content attribute to 'none' forces the title to show.

= How can I find the blog and post IDs? =

To easily obtain the blog ID and post ID, install the free [RPS Blog Info](http://wordpress.org/extend/plugins/rps-blog-info/) plugin, which places that information (and much more) on your WordPress Toolbar.

= Can I include pages or posts that already have includes within them? =

Yes. RPS Include Content supports "nested" includes. In addition, it prevents infinite loops and duplicate calls to the same content.

= What happens if I include a post that does not exist? =

If you are logged in and have the manage_rps_include_content capability, you will see an error message appear in place of the shortcode when viewing the public site. Users that are not logged in, or do not have the appropriate capability will not see the error message.

= What if the included post is protected by a password? =

The included post title will appear followed by the password form, just like the default WordPress behavior.

= How do I easily access the source content to edit it? =

The best way is to view the page on the public site while logged in. Each piece of source content will be marked with  an "i" symbol and a vertical line appearing in theright margin. Hovering over the included content will display "View" and "Edit" buttons.  Clicking Edit will take you to the source post/page.

= Is it possible to include content into different web sites? =

You can include content across different Web sites as long as they are in the same network. A multisite network is a collection of sites that all share the same WordPress installation.

= How do I wrap the output from the plugin with my own markup? =

You should use the `rps_include_content_html` filter by adding the following to your theme’s functions file:

`function rps_include_content_wrap( $html ) {
	$html = '<div class="rps_include_content_wrap">' . $html . '</div>';
	return $html;
}
add_filter( 'rps_include_content_html', 'rps_include_content_wrap' );`

Adjust the code as necessary to get the desired markup around the included content.

== Screenshots ==

1. The source content can be a page or a post.
1. The shortcode can be inserted anywhere within another page or post.
1. The source content is flagged to the right when viewing the public site while logged in as a user with the "manage_rps_include_content" capability.

== Upgrade Notice ==

= 1.1.10 =
* Now supports embeds.

= 1.1.9 =
* Maintenance release.

= 1.1.8 =
* Added content="full" option to ignore more tag of included content.

= 1.1.7 =
* Modified replaced the_content filter with independent functions.

= 1.1.6 =
* Additional options and multisite compatibility for including featured images.

= 1.1.5 =
* Option to display the featured image associated with the included content.

= 1.1.4 =
* Updated version number.

= 1.1.3 =
* Fixed bug that prevented settings from being initialized. Check for Administrator role before adding capability to avoid error.

= 1.1.2 =
* Added filter to allow included content to be wrapped in custom markup.

= 1.1.1 =
* Set default behavior to show all shortcodes as in previous versions.

= 1.1.0 =
* New features including support for More tag, options to display only specific shortcodes, disable hover state and include private content.

= 1.0.17 =
* Bug fix for multisite installs.

= 1.0.16 =
* Now requires included content to be published.

= 1.0.15 =
* New option to strip shortcodes from included content.

= 1.0.14 =
* Maintenance release to fix title of included post not displaying.

= 1.0.13 =
* New option to force included content to be formatted.

= 1.0.12 =
* New option to set 'content=none' to hide the content and show the tite.

= 1.0.11 =
* New option to link title to the included post using the 'titlelink=true' shortcode attribute.

= 1.0.10 =
* Better compatibility when third-party plugin output exists within included content.

= 1.0.9 =
* Support for password protected posts.

= 1.0.8 =
* New option to use excerpts instead of main content.

== Changelog ==

= 1.1.10 =
* Added option to display embeds within included posts.

= 1.1.9 =
* Fixed undefined variable notice on multisite admin. 

= 1.1.8 =
* Adds option to display full content of included post/page ignoring the presence of a "more" tag.

= 1.1.7 =
* Avoids duplicated content, such as sharing buttons, when setting the filter option to "true".

= 1.1.6 =
* Size of included featured image can be specified by name.
* Featured image inclusion now works with multisite.
* Featured image can now be wrapped and supplied with a custom class.

= 1.1.5 =
* Added option to include the featured image.

= 1.1.4 =
* Updated version number.

= 1.1.3 =
* Fixed bug that prevented saving settings since settings were not initialized.
* Added check for Administrator role so that add_cap function would not result in a fatal error if role does not exist.

= 1.1.2 =
* Added filter hook to HTML output.

= 1.1.1 =
* Fixed bug that prevented shortcodes from showing by default.

= 1.1.0 =
* Added support for <code>more</code> tag in posts.
* Added attribute to specify the length of the content being included.
* Added attribute to define which shortcodes should be processed and displayed.
* Added option disable hover state on included content.
* Added option to include private content.

= 1.0.17 =
* Fixed issue that would prevent included content from displaying on multisite installs.

= 1.0.16 =
* Added check to make certain that included content has a status of 'publish'.

= 1.0.15 =
* Added shortcodes option to allow shortcodes to be removed from included content.

= 1.0.14 =
* Fixed bug introduced in 1.0.13 which rendered title display impossible.

= 1.0.13 =
* Added filter option to force content formatting.

= 1.0.12 =
* Added option to set the content to none.

= 1.0.11 =
* Added option to link the title to the included post.

= 1.0.10 =
* Removed filter that caused duplication of included content in some cases.
* Toned down the souce content highlighting so it is not as bright.

= 1.0.9 =
* Added support for requiring authentication when password protected posts are included.

= 1.0.8 =
* Added option to override the content with the excerpt of the included post.

= 1.0.7 =
* First official release version.