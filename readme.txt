=== Pretty Link (Lite Version) ===
Contributors: supercleanse
Donate link: http://prettylinkpro.com
Tags: links, link, url, urls, affiliate, affiliates, pretty, marketing, redirect, forward, plugin, twitter, tweet, rewrite, shorturl, hoplink, hop, shortlink, short, shorten, click, clicks, track, tracking, tiny, tinyurl, budurl, shrinking, domain, shrink, mask, masking, cloak, cloaking, slug, slugs, admin, administration, stats, statistics, stat, statistic, email, ajax, javascript, ui, csv, download, page, post, pages, posts, shortcode, seo, automation, widget, widgets, dashboard
Requires at least: 2.9
Tested up to: 3.0.4
Stable tag: 1.4.55

Shrink, track and share any URL on the Internet from your WordPress website. Create short links suitable for Twitter using your own domain name!

== Description ==

Shrink, track and share any URL on the Internet from your WordPress website. Create short links suitable for Twitter using your own domain name!

= Upgrade to Pretty Link Pro =

Pretty Link Pro is an upgrade to Pretty Link that adds the capability to automate your pretty link creation, cloak links, auto-tweet them, replace keywords thoughout your blog and much more. You can learn more about it here:

http://prettylinkpro.com

= Detail =

Pretty Link enables you to shorten links using your own domain name (as opposed to using tinyurl.com, bit.ly, or any other link shrinking service)! In addition to creating clean links, Pretty Link tracks each hit on your URL and provides a full, detailed report of where the hit came from, the browser, os and host. Pretty Link is a killer plugin for people who want to clean up their affiliate links, track clicks from emails, their links on Twitter to come from their own domain, or generally increase the reach of their website by spreading these links on forums or comments on other blogs.

= Examples =

This is a link setup using Pretty Link that redirects to the Pretty Link Homepage where you can find more info about this Plugin:

http://blairwilliams.com/pl

Here's a named Pretty Link (I used the slug 'aweber') that does a 307 redirect to my affiliate link for aweber.com:

http://blairwilliams.com/aweber

Here's a link that Pretty Link generated a random slug for (similar to how bit.ly or tinyurl would do):

http://blairwilliams.com/w7a

= Features =

* Gives you the ability to create clean, simple URLs on your website that redirect to any other URL (allows for 301 and 307 redirects only)
* Generates random 2-3 character slugs for your URL or allows you to name a custom slug for your URL
* Tracks the Number of Hits per link
* Tracks the Number of Unique Hits per link
* Provides a reporting interface where you can see a configurable chart of clicks per day. This report can be filtered by the specific link clicked, date range, and/or unique clicks.
* View click details including ip address, remote host, browser (including browser version), operating system, and referring site
* Download hit details in CSV format
* Intuitive Javascript / AJAX Admin User Interface
* Pass custom parameters to your scripts through pretty link and still have full tracking ability
* Exclude IP Addresses from Stats
* Enables you to post your Pretty Links to Twitter directly from your WordPress admin
* Enables you to send your Pretty Links via Email directly from your WordPress admin
* Select Temporary (307) or Permanent (301) redirection for your Pretty Links
* Cookie based system for tracking visitor activity across hits
* Organize Links into Groups
* Create nofollow/noindex links
* Turn tracking on / off on each link
* Pretty Link Bookmarklet

== Installation ==

1. Upload 'pretty-link.zip' to the '/wp-content/plugins/' directory

2. Activate the plugin through the 'Plugins' menu in WordPress

3. Make sure you have changed your permalink Common Settings in Settings -> Permalinks away from "Default" to something else. I prefer using custom and then "/%postname%/" for the simplest possible URL slugs.

== Changelog ==

= 1.4.55 =
* Fixed the CSV export issues
* Moved all Pretty Link images to Amazon CloudFront
* Added TweetDeck & Twitter for iPhone support for Pro Users

= 1.4.53 =
* Added the ability to change the pretty link tracking mode to simple, normal and extended
* Fixed numerous debug issues
* Fixed memory_limit issue in pretty link
* Fixed pretty bar issue affecting pro users

= 1.4.52 =
* Fixed bugs related to the cloaking and pretty bar redirection changes

= 1.4.51 =
* Removed cloaking & pretty bar redirection to comply with wordpress.org policy requirements

= 1.4.50 =
* Updated to use the Twitter oAuth authentication protocol
* Updated the tweetbadge to use the new Twitter Tweet Button

= 1.4.49 =
* Fixed Keyword Caching Issue for Pro Users
* Fixed recording duplicate tweet issue for Pro Users

= 1.4.48 =
* Fixed a performance issue in Pretty Link affecting some users when viewing their dashboard
* Fixed the custom menu auto-tweet issue affecting some Pretty Link Pro users

= 1.4.47 =
* Updated code for WP 3.0
* Fixed tweetbadge indexing issue
* Added an underscore to postmeta values

= 1.4.46 =
* Fixed a subdirectory redirection issue

= 1.4.45 =
* Refactored and Options code
* Pro: Refactored Update code to work with the upcoming WordPress 3.0
* Pro: Tweet Badge now loads asynchronously in an iFrame to prevent performance issues when updating multiple tweet badges simultaneously.
* Pro: Enabled limit on number of keyword replacements to occur per page load
* Pro: Cleaned up options code
* Pro: Added keyword replacement to comments and feeds and an option to make all links into pretty links automatically.

= 1.4.44 =
* Fixed the phantom postmeta issue

= 1.4.43 =
* Added an enhanced CSV Hit Reports...
* Added CSV IP History reports... (the history of each visitor by IP address)
* Added CSV IP Origin reports... (the first time we see each visitor click a Pretty Link)
* Separating CSV Reports into blocks of 5000 rows each ... this will help with performance in a major way and prevent locking ...
* Added an option to use a prefixed element from your permalink structure (this is necessary for users who need an index.php as part of their permalink structure)
* Fixed the pretty link nesting issue so larger slugs are matched first enabling users to create folder structures more efficiently
* The Link Description is now showing up as the meta description for Pretty Bar'd and Cloaked Pretty Links 
* Altered Tweet badge so it shows up as an image which will help it stay consistent across sites and won't ever mess up the excerpts ever again
* Cleaned up the front facing CSS for the tweet badge, social buttons and twitter comments
* Checked the [tweetbadge] shortcode and the_tweetbadge() template tag and verified that they are working properly...
* Fixed saving posts / pages issue... Now pages are saved & auto tweeted (if the option is set)...

= 1.4.42 =
* Fixed more pretty link path issues to easily handle pre-slug elements in custom permalink structures
* Optimized php code executed in pretty link tracking
* Replaced fsockopen with curl for validating urls and grabbing the target url title

= 1.4.41 =
* Fixed pretty link path issue

= 1.4.40 =
* Added support for Pre-Slug URL elements -- this will be helpful for those users who don't have rewrite working fully
* Additional, unnecessary postmeta fields not being created anymore for pro users
* Fixed auto pretty link creation and auto twitter posting on scheduled and xml-rpc post for pro users
* Fixed twitter badge count issue -- it was reporting incorrect tweet results for some pro users on some posts
* Added Option to show tweet badge and/or social buttons in the RSS Feed

= 1.4.39 =
* Fixed browsecap integration for users on PHP 5.3 or higher
* Added new browsecap file to include android based phones in hit results
* Fixed form submission issues affecting a small number of users
* Fixed a pro update bug affecting some users
* Fixed a bug affecting pro users more tag and keyword replacement

= 1.4.38 =
* Fixed some update and validation bugs affecting a small number of users

= 1.4.36 =
* Fixed pro export issue
* Fixed html within shortcodes in keyword replacement issues for pro users
* Fixed XHTML validation for pro users using keyword replacement
* Added an email button to the social bar for pro users
* Fixed update code for pro users to not display false update message
* Fixed url utilities port configuration for all users
* Fixed url validation issue for all users

= 1.4.35 =
* Made significant changes to the Pretty Link Pro update routines
* Fixed several bugs with remote url reading

= 1.4.34 =
* Fixed a redirection issue for all users

= 1.4.33 =
* Added more support options for all users
* Updated install
* Added exclude tweet badge/comments & social media buttons for specific pages & posts to the page/post edit screen for pro users

= 1.4.32 =
* Optimized CSV download of hits
* Optimized more SQL calls
* Fixed keyword replacement bug on password protected posts for Pro Users
* Fixed group issue on options page for Pro Users

= 1.4.31 =
* Fixed a bug in pretty link, pixel & cloaking redirection.

= 1.4.30 =
* Altered the where Pretty Links are redirected to put less burden on normal page loads
* Optimized Group & Report SQL calls to be significantly faster
* Added a shortcode & template tag for pro users to display their pretty links on pages & posts (the shortcode is [post-pretty-link] and the template tag is the_prettylink())

= 1.4.29 =
* Added some more support options
* fixed some minor bugs in keyword replacement and pro options.

= 1.4.28 =
* Fixed a bug with link creation from the bookmarklet, post publishing and public link creation that was introduced in the last release

= 1.4.27 =
* Simplified SQL Calls and reduced the number of them that it takes to load a page
* Modified tweets to be counted for each link -- even ones not associated with a post -- got rid of the url_alias feature
* Streamlined database calls & fixed a bug in the api
* Added the ability to tweet to multiple accounts...
* Added tweetmeme count checking and added additional twitter account validation

= 1.4.26 =
* Fixed the html entity display issue for target urls
* Reduced package size of Pretty Link for more reliable installation
* Added Customizable Bookmarklet for Pro Users

= 1.4.25 =
* Fixed some bugs in the install
* Added nofollows to links in the social bar & re-tweet badge

= 1.4.23/24 =
* Fixed an installation issue for PHP4 users

= 1.4.22 =
* Added known robot and unidentified browser filtering to Pretty Link stats 
* Added IP Address range definition to the Excluded IP address field 
* Fixed html formatting issue on the bookmarklet success page 
* Added the ability for Pro users to remove or alter the attribution link on the Pretty Bar 
* Added new shortcodes for Pro users to display the title, target url and social networking buttons for a newly created public pretty link 
* Enhanced the default success page for public link creation for pro users 
* Fixed the redirect-type not being set bug for pro users allowing public link creation 
* Fixed another php short-code bug affecting Pro users (thanks to Clay Loveless of KillerSoft for helping me with that one)

= 1.4.21 =
* Fixed UTF-8 issues
* Enabled UTF-8 Pretty Link slugs
* Enabled UTF-8 tweets for Pro users
* Fixed several issues for users hosted on Windows
* Added padding configuration to space the buttons on the social bar for Pro users
* Fixed the html validation issues with the tweet badge and social buttons bar for Pro users

= 1.4.20 =
* Added IPv6 support for IP Address Exclusions
* Added Twitter Comments post widget for Pro users
* Added RSS feed support for the tweet badge for Pro users

= 1.4.19 =
* Fixed https image loading / path issue
* Fixed bookmarklet javascript encoding issue
* Fixed import / export issue for pro users
* Added Hyves.nl and Sphinn to the social buttons bar
* Added more placement options for the social buttons bar
* Added a social buttons bar shortcode & template tag

= 1.4.18 =
* Added the Social Network Button Bar for Pro Users

= 1.4.17 =
* Fixed the php strict tags issue affecting some users
* Fixed the click record issue affecting some IIS users
* Added DOCTYPE line to Pretty Bar HTML
* Elimitated Pro upgrade messages for Pro users

= 1.4.16 =
* Fixed PrliUrlUtils not found error affecting some users
* Added instructions for installing the Pretty Link bookmarklet on the iPhone
* Added a URL Alias feature to Pro to allow tweet counts to be aggregated and hence, more accurate

= 1.4.15 =
* Fixed the nested slug cookie issue.

= 1.4.14 =
* Fixed bookmarklet/fopen issue affecting some users
* Fixed XML-RPC auto-tweeting of Posts
* Fixed Scheduled auto-tweeting & link creation of Posts issue
* Fixed bulk auto link creation issue
* Added slug choice for your post
* Added a twitter message formatting textarea on the post edit screen

= 1.4.13 =
* Fixed the option reset issue

= 1.4.12 =
* Added title detection
* Added enhancements to the Pretty Link Bookmarklet
* Added better support for IIS by redefining the fnmatch function if it isn't present
* Changed the keyword replacement algorithm in Pro to replace links throughout the post when thresholds are set (instead of only linking to the top x keywords)
* Fixed some issues surrounding keyword content caching in Pro

== Upgrade Notice ==
= 1.4.55 =
* Everyone should upgrade -- this fixes the CSV export issue with hits.

= 1.4.53 =
* Everyone should upgrade to this version. It fixes numerous bugs for all users -- including a memory_limit issue and some click tracking algorithm issues. In addition to the upgrade, users who have had performance issues with click tracking should also switch to simple click count tracking in "Pretty Link" -> "Options" -> "Reporting Options" ...

= 1.4.52 =
* Fixed bugs related to the cloaking and pretty bar redirection changes. This affected all Pretty Link users -- everyone should upgrade to this release.

= 1.4.51 =
* Removed cloaking & pretty bar redirection to comply with wordpress.org policy requirements

= 1.4.50 =
* If you are a pro user and use the twitter related features of Pretty Link Pro you need to update immediately -- non-pro users will be pretty much unchanged by this release

= 1.4.49 =
* Fixed some important bugs for pro users -- non-pro users will be pretty much unchanged by this release

= 1.4.48 =
Fixed some dashboard performance issues for Pretty Link users and a custom menu tweeting fix that was affecting some pro users.

= 1.4.47 =
Upgrade to make Pretty Link compatible with WordPress 3.0

= 1.4.46 =
If your wordpress website is in a subdirectory and you've had issues with your pretty links since the last release then this upgrade will fix it.

= 1.4.45 =
Bug fixes have been made in Pretty Link and several enhancements have been made it Pretty Link Pro.

= 1.4.44 =
All users -- especially pro users should upgrade to this new version -- it fixes the phantom postmeta issue

= 1.4.43 =
All users should upgrade to this new version -- several functional and performance related issues have been fixed for Pretty Link and Pretty Link Pro users.
