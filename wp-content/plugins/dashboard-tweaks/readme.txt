=== Dashboard Tweaks ===
Contributors: senlin
Donate link: http://senl.in/PPd0na
Tags: dashboard, tweaks, admin bar, toolbar, menus, functions, styles
Requires at least: 3.3
Tested up to: 3.3
Stable tag: 1.0.9

PLUGIN NO LONGER IN ACTIVE DEVELOPMENT! INSTALL AT OWN RISK!

== Description ==

<strong>As there are better alternatives to this plugin - also with option panels - I have decided to no further pursue its development.</strong>

If someone is interested to take over the development, please contact me, so we can arrange that.

With the arrival of WordPress version 3.3 the WordPress Dashboard once again received an overhaul/makeover. The tweaks we have been using since 3.2 no longer cut it and that inspired me to develop this plugin for <strong>WordPress version 3.3 or higher</strong>.

<strong>I have not included an options panel</strong> as this plugin is catered to developers and you are meant to play around with it. If you don't develop WordPress websites for clients, then most likely you won't have much use for this plugin and if you do, then be prepared to roll up your sleeves and get your hands dirty.

If you try to install the plugin on any WordPress version lower than 3.3 it will die nicely and you will be taken back to the Dashboard.

A list of the tweaks included in the Dashboard Tweaks plugin:<br />
- change the WordPress logo in the top-left of the toolbar with a custom admin logo<br />
- link that logo to the home URL instead of the new wp-admin/about.php<br />
- hide the entire sub-menu with links to Codex, forums and what not<br />
- hide the Visit Site sub-menu under the site's name<br />
- hide the Comments balloon in the toolbar<br />
- change "Howdy" into "Welcome back"<br />
- add a special favicon for the Dashboard only (ideally different from the frontend, but up to you)<br />
- change the tab title into a standard "Dashboard of [sitename]"<br />
- hide icons in front of Dashboard menus<br />
- reduce page heading size<br />
- hide publish icon of Add new Post/Page screens<br />
- add colored background to sidebar active sub-menu to add contrast<br />
- add colored background to activated plugins to add contrast<br />
- add custom footer text

The plugin comes localized for use on sites other than the English language and/or on bi/multilingual websites.

== Installation ==

The Dashboard Tweaks plugin requires WordPress 3.3-beta4 or higher.

1. Upload `dashboard-tweaks.zip` to the `/wp-content/plugins/` directory
2. Edit the files `dashboard-tweaks.php` and `/css/wp-admin.css` to your liking (link: `/wp-admin/plugin-editor.php?file=dashboard-tweaks/dashboard-tweaks.php`). See below what can be changed on which lines.
3. Alternatively you can unzip the file on your computer, do your edits and then upload the entire directory `dashboard-tweaks` to the `wp-content/plugins/` folder.
4. Activate the plugin through the 'Plugins' menu in WordPress and enjoy your customized WordPress Dashboard!

Change images for admin favicon and toolbar logo:<br />
Use FTP to upload your own images, the admin favicon is called `dashboard.png` and the toolbar logo is called `adminbar-logo.png`; both files are located in the images folder of the dashboard-tweaks folder.

Which lines of `dashboard-tweaks.php` can be edited?<br />
lines 63-81: change menus that are hidden and/or the text that shows when hovering over the new toolbar logo
line 89: If you want to change "Welcome Back" to something else<br />
line 96: change tab title text<br/>
line 103: change custom footer text

Which lines of `/css/wp-admin.css` can be edited?<br />
lines 6, 10-11: (hover) background color for toolbar logo<br />
line 18: change page heading size<br />
line 25: change colored background to activated plugins to add contrast<br />
lines 28-29: change colored background to sidebar active sub-menu to add contrast

== Frequently Asked Questions ==

= On the front-end the WordPress stuff in the toolbar is still visible! =

Disable the toolbar for the user when viewing the site. Most clients rather see their own website in its full glory instead of with that ugly toolbar at the top.

= Are you planning to add an options panel? =

Yes. That will be the last "problematic" update where you have to edit your edits. You can definitely expect a working options panel for the next version!

== Screenshots ==

1. Dashboard after installation.
2. Plugin screen after installation.

== Changelog ==

= 1.0.9 =
* Plugin no longer in active development. If you want to take over the development, please let me know. Degraded compatibility.

= 1.0.8 =
* Changed version to show compatibility with WordPress release 3.5

= 1.0.7 =
* Changed version to show compatibility with WordPress release 3.4.1

= 1.0.6 =
* FAIL

= 1.0.5 =
* Today the final WordPress version 3.3 was released, so some small tweaks and updates were once again needed.
* Edited the way the toolbar logo shows
* Added the option to hide the comments as many websites don't have those enabled
* Removed opacity on inactive and hover state of toolbar logo
* Edits in readme.txt
* Changed admin bar (or admin-bar or adminbar) wording throughout the plugin with the new "toolbar"

= 1.0.1 =
* Today WordPress version 3.3-RC1 was released and with it some functions in wp-includes/admin-bar.php. As it turned out the admin logo was no longer visible, hence this small update.
* Added opacity on inactive and hover state of toolbar logo
* Small edits in the readme.txt file

= 1.0 =
* First version (stable)

== Upgrade Notice ==

= 1.0.9 =
<strong>Plugin no longer in active development.</strong> If you want to take over the development, please let me know.

= 1.0.5 =
Make sure to <strong>save your edits before updating</strong> the Dashboard Tweaks plugin, otherwise they will all be lost. Yeah, I know, options panel...

= 1.0.1 =
Make sure to <strong>save your edits before updating</strong> the Dashboard Tweaks plugin, otherwise they will all be lost. Yeah, I know, options panel...

= 1.0 =
First release
