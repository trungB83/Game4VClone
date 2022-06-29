=== WP User - Custom Registration Forms, Login and User Profile ===
Contributors: walke.prashant
Donate link: https://wpuserplus.com/pricing/
Tags: login, register, user, profile, gravatar, ajax, restrict content, json-web-authentication, jwt, wp-json
Requires at least: 3.3.3
Tested up to: 5.5
Stable tag: 4.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Create elegant Login, Register, and Forgot Your Password form on Page, widget or Popups on your website in just minutes with AJAX.

== Description ==

* WP User plugin helps you to create front end login and registration form.
* User logins or registrations and would like to avoid the normal wordpress login pages, this plugin adds the capability of placing a login, Registration, forgot password with smooth effects in AJAX.
* Extends the WP REST API using JSON Web Tokens Authentication as an authentication method. Authorize your REST API requests so that you can create, update and delete your data.


<a target="_blank" href="https://wpuserplus.com/pricing/">https://wpuserplus.com</a>
<a target="_blank" href="https://www.wpseeds.com/product/wp-user-custom-registration-forms-login-and-user-profile/">www.wpseeds.com</a>

= Features =
<ul>
	<li><strong>Login : Login with Username or Email Id or Mobile Number</strong></li>
	<li>Login with - Password or OTP </li>
	<li><strong>Registration</strong></li>
	<li><strong>Forgot Password</strong></li>
	<li>Profile : View/Edit Profile</li>
	<li>Login with Password or <strong>OTP</strong></li>
	<li><strong>security</strong>
<ul>
	<li><strong>Limit Login</strong> Attempts</li>
	<li>Mechanism for slow down brute force attack</li>
	<li>Notify on lockout (Email to admin after cross limit the number of login attempts)</li>
	<li>Password Regular Expression (Form Validation &amp; Security )</li>
    <li>Google <strong>reCAPTCHA</strong></li>
    <li>Approve/Deny User</li>
    <li>Auto / Email Approval user</li>
    <li>View Login Log</li>
    <li>Restrict an entire post or page</li>
    <li>Restrict section of content within a post/page</li>
    <li>Logged in or selected role users only access content</li>
		<li>Blacklisting / Whitelisting IP addresses</li>
		<li>2-Step Verification</li>
</ul>
</li>
	<li><strong>Email Notification</strong>
<ul>
	<li>New Registration</li>
	<li>Email to admin after cross limit the number of login attempts</li>
	<li>Custom email subject, content</li>
</ul>
</li>
<li><strong>Front-end profile</strong>
<ul>
	<li>View/Edit user information user front end dashboard</li>
	<li><strong>User Avatar</strong> : for users to upload images or enter url to their profile</li>
	<li>Change the Default Gravatar</li>
	<li>Send mail to admin via Contact Us form</li>
	<li>Get Notification new comment on user post, woocommerce order status changed to refund/complete, new follow</li>
	<li>Whenever a user publish posts, all the followers will receive a notification</li>
</ul>
<li><strong>Member Directory</strong>
<ul>
	<li>Member Pagination,Search.</li>
	<li>View Member Profile.</li>
	<li>Send Mail to Member</li>
</ul>
</li>
</li>
	<li>Auto <strong>Generate page</strong> for Login,Register</li>
	<li>Enable / <strong>Disable Admin Bar</strong></li>
	<li>Templates : 4 login,register front end <strong>templates</strong></li>
	<li><strong>Customizable CSS</strong></li>
	<li> Admin : Export Users CSV</li>
<li>AJAX based verification for username and email accounts</li>
<li>Add smooth ajax login/registration effects</li>
<li>Login redirection</li>
<li>Login/registration/forgot password <strong>popup model</strong> :  You can create one popup that contains all 3 with a great interface for switching between them</li>
<li>Light weight plugin</li>
<li>Customize skin color,buttons, link, box, form background etc.</li>
<li>login,register , forgot password form using shortcode, widget, popup</li>
<li><strong>Responsive</strong></li>
<li>MultiSite</li>
<li>Multi language</li>
<li><strong>REST API</strong>- Login, Register, Forgot, Update, view User, List users, Generate Token</li>
</ul>

= Integration plugin =
* woocommerce
* SupportCandy
* YITH WooCommerce Points and Rewards
* YITH WooCommerce Wishlist
* WP Twilio Core

= Demo =
<a target="_blank" href="http://wpuserplus.com/demo/">http://wpuserplus.com/demo/</a>

= Screenshots =
<a target="_blank" href="https://wpuserplus.com/blog/doc/screenshots/">https://wpuserplus.com/blog/doc/screenshots/</a>

= Documentation =
<a target="_blank" href="https://wpuserplus.com/documentation/">https://wpuserplus.com/documentation/</a>

= Get Pro Feature =
<a target="_blank" href="https://wpuserplus.com/#optimizer_front_blocks-3">https://wpuserplus.com</a>
<a target="_blank" href="https://www.wpseeds.com/product/wp-user-custom-registration-forms-login-and-user-profile/">www.wpseeds.com</a>

= Pro Feature =
* Ultimate Registration form
* Custom form fields
* Create required fields
* Social Login/Register i.e Facebook,Google,Twitter
* Add / Edit / Delete / Duplicate Multiple Address
* Get user current location(address) using Geolocation
* Set defualt WooCommerce billing/shipping address from address list
* Select WooCommerce billing/shipping address from address book on checkout page
* Subscription newslatter on new user Registration with MailChimp, Aweber and Campaign Monitor
* Show the percentage of user profile completion
* On click Improve button it will show highlighted fields for improve profile strength.
* Set custom weight for field
* Profile progress on member profile
* WoCommerce integration
* Support Multiple address and set billing and shipping address
* Badges and Achievements - Automatically or manually assign badges to users based on different criteria’s like
* Specific user roles
* Based on activity score i.e Number of posts, comments, followers etc.
* Admin can manually assign badge
* Follow / Unfollow Feature lets users follow other users.
* User Like, Bookmark (With Social Network addon )
* User View Count
* Whenever a user posts, all the followers will receive a notification regarding the update.
* Keeps your user community more interactive and engaging.
* Premium Support
* New features added regularly!


== Installation ==
* Download the plugin file, unzip and place it in your wp-content/plugins/ folder. You can alternatively upload it via the WordPress plugin backend.
* Activate the plugin through the 'Plugins' menu in WordPress.
* WP User menu will appear in Dashboard->WP User.
* <b>shortcode</b><br>
<b> [wp_user] </b> shortcode for display login, registration, forgot password form.<br>
You Can use following attribute for custom form<br>
<b>[wp_user id='1234' width='360px' popup='1' active='register' role='subscriber' login_redirect='".get_site_url()."']</b><br>
<b> id </b> : If Multiple Form Add-on activated then create form and set id='form_id'.
You can use diffrent registration form for diffrent page.<br>
Ex. [wp_user id='1234']<br>

<b> width </b> : set custom width to login, registration, forgot password form.<br>
[wp_user width='360px']<br>

<b> popup </b>:  set  popup='1' shortcode for popuup model login, registration, forgot password form.<br>
Ex. [wp_user popup='1']<br>

<b> active </b>: For activate default form. By Defualt login.<br>
[wp_user active='register' popup='1'] shortcode for popuup model login, registration, forgot password form. default active registration form<br>
[wp_user active='register'] for display default active registration form.(sign up page)<br>
[wp_user active='forgot'] shortcode for display login, registration, forgot password form. default active forgot form<br>

<b> role </b>: Set role for new register user via register form. You can set diffrent role for diffrent form. By Defualt subscriber role<br>
Ex. [wp_user role='subscriber']<br>

<b> login_redirect </b>: Custom login redirection url for each login form.<br>
Ex. [wp_user login_redirect='www.yoursite.com/redirectPageUlr'] for redirect user after login to custom link. Replace 'www.yoursite.com/redirectPageUlr' Url with redirect page Url.

<br>
<b> [wp_user_member] </b> shortcode for display member list/directory<br>
You can use following attributes for filter/show member list <br>
<b>[wp_user_member role_in='subscriber' role_not_in='author' include='1,2,5,7' exclude='55,44,78,87' approve='1' size='small']</b><br>
<b>role_in </b> : If you want to show only selected member role in list then set this attribute by comma seprated<br>
Ex. [wp_user_member role_in='subscriber,author']<br>

<b>role_not_in </b> : If you want exclude to show some user roles in member list then set this attribute by comma seprated<br>
Ex. [wp_user_member role_not_in='subscriber,author']<br>

<b>include </b> : If you want only show selected user ids then set this attribute by comma seprated<br>
Ex. [wp_user_member include='1,2,5,7' ]<br>

<b>exclude </b> : If you don't want show selected user ids then set this attribute by comma seprated<br>
Ex. [wp_user_member exclude='55,44,78,87' ]<br>

<b>approve </b> : If you want show only approve user then set approve='1'<br>
Ex. [wp_user_member approve='1' ]<br>

<b>size </b> : If you want change default display member list template to small one then set size='small'<br>
Ex. [wp_user_member size='small' ]<br>
<br>[wp_user_list] shortcode for display member list/directory
<br>You can use following attributes for filter/show member list
<br>[wp_user_list role_in=’subscriber’ role_not_in=’author’ include=’1,2,5,7′ exclude=’55,44,78,87′ approve=’1′ size=’small’]
<br>role_in : If you want to show only selected member role in list then set this attribute by comma separated
<br>Ex. [wp_user_list role_in=’subscriber,author’]
<br>role_not_in : If you want exclude to show some user roles in member list then set this attribute by comma separated
<br>Ex. [wp_user_list role_not_in=’subscriber,author’]
<br>include : If you want only show selected user ids then set this attribute by comma separated
<br>Ex. [wp_user_list include=’1,2,5,7′ ]
<br>exclude : If you don’t want show selected user ids then set this attribute by comma separated
<br>Ex. [wp_user_list exclude=’55,44,78,87′ ]
<br>approve : If you want show only approve user then set approve=’1′
<br>Ex. [wp_user_list approve=’1′ ]
<br>template : If you want change default display member list with different layout then set template=<template name>
<br>Template : Currently we 2 template available for show user list
<br>Rounded (template=rounded ): set template parameter as rounded.
<br>Ex: [wp_user_list template=’rounded’]
<br>Default :
<br>Ex: [wp_user_list]

<br>Key,Value : if you want user list display user list on particular page based on user_meta key and value then set Key and Value attribute.
<br>If you have pages like Bride, Groom so based on user meta_key you can display or category user using this attribute
<br>Ex:
<br>Bride : [wp_user_list key='gender' value='Female']
<br>Groom : [wp_user_list key='gender' value='Male']

<br>Use above shortcode respective pages and set metaket as gender and it value male or female.
<br>This meta key add using form builder addon and add filed for gender in register/profile form.

<br>You can add multiple key and values in this shortcode.
<br>Ex: If you want to add Bride page on site and only for Unmarried' then add multiple keys and values using comma separated

<br>[wp_user_list key='gender,maritial_status' value='Female,Unmarried']

<br>This meta key add using form builder addon and add filed for gender and maritial_status  in register/profile form.

<br>id : If Form builder addon is Activated then filter result based on custom fields.
<br>Create new form using form builder
<br>Add field into form which you want to add in custom filter
<br>Get form id from list
<br>Set id attribute in form and set value as form id
<br>Ex:
<br>[wp_user_list id='474']
<br>[wp_user_list id='474' key='gender,maritial_status' value='Female,Unmarried']

<br>So based on this filter it will filter the result.

<br><br><b> [wp_user_restrict] your restricted content goes here [/wp_user_restrict]</b>
shortcode for Restrict Content to registered users only. logged in users only access content<br>
To restrict just a section of content within a post or page, you may use above shortcodes<br>
You can also set user role for access content.<br>
You can use role attribute for only access content to selected user role:<br>
Ex. [wp_user_restrict role='author,editor'] your restricted content goes here [/wp_user_restrict]<br>
Ex. [wp_user_restrict role='author'] your restricted content goes here [/wp_user_restrict]<br>
Ex. [wp_user_restrict role='logged_in'] your restricted content goes here [/wp_user_restrict] : logged in users only access content<br>
To restrict an entire post or page, simply select the user role you’d like to restrict the post or page to from the drop down menu added just below the post/page editor.

* Refer to the for More Information.
* https://wpuserplus.com/documentation

== Screenshots ==


1. screenshot-1.png
2. screenshot-2.png
3. screenshot-3.jpeg
4. screenshot-4.jpeg
5. screenshot-5.jpeg
6. screenshot-6.jpeg
7. screenshot-7.png
8. screenshot-8.png
9. screenshot-9.jpg


== Changelog ==

Latest Change log : https://wpuserplus.com/blog/release-new-version-6-0-wp-user/

= 6.4.2 =
* 24-09-2020
* Added Pro link and changed plugin logo

= 6.4.1 =
* 17-09-2020
* Fixed warning

= 6.4 =
* 27-08-2020
* Fixed issue - Resolved Tab conflicts with some themes
* Added Feature - Login with OTP Link

= 6.3 =
* 05-12-2019
* Fixed issue - Javascript Uncaught SyntaxError: Unexpected token

= 6.2 =
* 28-09-2019
* Resolved issue - Delete blank tabs
* Resolved issue - Login reload

= 6.1 =
* 06-06-2019
* Added Setting - Hide Blogs link on My Profile

= 6.0 =
* 21-03-2019
* REST API : Extends the WP REST API using JSON Web Tokens (JWT) Authentication as an authentication method.
* https://wpuserplus.com/blog/doc/wordpress-rest-api/
* Resolved issue : JQuery UI CSS is enqueued over HTTP, instead of HTTPS
* Resolved issue : Getting logged out over HTTPS when uploading profile images

= 5.9.3 =
* 23-02-2019
* Added Feature - Admin can approve/deny user profile from member list

= 5.9.2 =
* 01-02-2019
* Added orderby, order attributes in wp_user_list shortcode
* wp_user_list, Added attribute view in wp_user_list shortcode defualt list view
* disable view list / count in n the user profile page
* Resolved bug Call to undefined function is_plugin_active()

= 5.9.1 =
* 28-01-2019
* Blacklisting / Whitelisting IP addresses

= 5.9 =
* 27-01-2019
* UI Changes - list, login form.
* Integration with WP Twilio Core.
* Login with Password or OTP - let your user signup on your wordpress website simply with their mobile number.
* Add feature 2-Step Verification -  add an extra layer of security to user account.

= 5.8 =
* 26-01-2019
* User can login using mobile number
* Added Search form filter
* Scroll top to Error/Success message when you click on sign up
* Filter and add setting enable/disable Bookmark, Like, Follow, Send mail features
* Changes In login popup
* UI Changes in login, view profile, List member, Profile.
* Bug Fixes


= 5.7 =
* 06-01-2019
- Profile UI changes
- Better Member listing UI
- Profile photo and file upload non login user - (With Form Builder addon)
- User Like, Bookmark, View (With Social Network addon )
- Filter for user name prefix, permission etc.

= 5.6 =
* 07-12-2018
- Added wp_user_counter Shortcode show user counter based on key

= 5.5 =
* 01-12-2018
- Integration with SupportCandy plugin
- Integration with YITH WooCommerce Points and Rewards plugin
- Integration YITH WooCommerce Wishlist plugin

= 5.4 =
* 29-11-2018
- Fixed Multistep registration issue
- Fixed Search issue


= 5.3 =
* 24-11-2018
- Added wp_user_search shortcode for search user
- Search in Header, Poupup, page, Widget section
- Support for Form-Builder add-on fields - Date,Time,Range,Tel, Image, Mobile Number, Section
- UI Changes
- Resolved bugs


= 5.2 =
* 12-11-2018
- Added register_redirect attribute for wp_user shortcode
- Support Multi step form and validation - Form builder Addon

= 5.1 =
* 23-10-2018
- Improve [wp_user_list] shortcode user listing UI
- Added icon for tabs
- Resolved error and warnings
- Image popup on user profile
- Added setting for Enable /Disable post tab
- Added parameter in in about_ids like [wp_user about_ids='433,471'] lo show multiple view form on About section

= 5.0 =
* 16-10-2018
- Added [wp_user_list] shortcode for display member list/directory
- Changes in Edit Profile
- Create and join groups
- Changes in UI

= 4.2.3 =
* 10-02-2018
- Added auto login after register

= 4.2.2 =
* 05-02-2018
* Added Layout - customize skin color,buttons, link, box, form background etc
* http://wpuserplus.com/blog/release-new-version-4-2-2-wp-user/

= 4.2.1 =
* 06-01-2018
* Load minified css/js
* Imporve performance


= 4.2 =
* 28-12-2017
* Added language support
* Added support for addon backend
* Updated submenu hook position
* Chages wpseed name and icons

= 4.1 =
* 28-11-2017
* Added feature : Get Notification new comment on user post, woocommerce order status changed to refund/complete, new follow

= 4.0 =
* 22-11-2017
* Changes Complete UI
* AJAX implementation
* Added feature : Admin Approve
* Added feature : Email Approve
* Deny / Allow user lo login
* Set Default New User Approval status
* Restrict content
* Member Directory Changes
* Set Diffrent role for diffrent form (shortcode)
* Changes in widget
* Code Optimization
* Integration with WooCommerce
* More Secure and fast

= 2.9.3 =
* Change the Default Gravatar

= 2.9.2 =
* User Avatar : for users to upload images or enter url to their profile

= 2.9.1 =
* Subscription feature in edit profile - WP Subscription
* http://www.wpseeds.com/documentation/docs/wp-subscription/integration/wp-user-wordpress-plugin/

= 2.9 =
* View/Edit user information user front end dashboard
* View/Edit billing, shipping Address on user dashboard (WooCommerce)
* Support System in user profile
* Send mail to admin via Contact Us form
* http://www.wpseeds.com/blog/release-new-version-2-9-wp-user/

= 2.8 =
* login,register, forgot password form using widget
* [wp_user active='register'] for display default active registration form.(sign up page)
* [wp_user active='forgot'] default active forgot form.
* added hook for login,register, forgot password

= 2.7.2 =
* Added : Language Translation for Hungarian

= 2.7.1 =
* Added : Language Translation for Detch, German

= 2.7 =
* Added google reCAPTCHA to registration form
* http://www.wpseeds.com/blog/release-new-version-2-7-wp-user/

= 2.6 =
* Added : Login Redirect - redirect user after login to custom link.
* Added : Language Translation for English, French
* Changed : Make Terms and Conditions readonly on front end
* Added : Forgot password email template (admin setting).

= 2.5.1 =
* Make Terms and Conditions readonly on front end

= 2.5 =
* Resolved WP User css(bootstrap) conflict with wordpress plugin css and theme css.
* Sign up form should reset after submitting sign form
* change in register template mail.

= 2.4 =
* User getting confused that what is user name becuase in the sign form there is no username field - so name is changed as username
* Login with Username or Email Id
* fixed form icon issue

= 2.3 =
* Change user register email template

= 2.2 =
* Fixed css load issue

= 2.1 =
* Fixed missing file issue

= 2.0 =
* Rebuilt Plugin in AngularJS
* security
* Limit Login Attempts
* Mechanism for slow down brute force attack
* Notify on lockout (Email to admin after cross limit the number of login attempts)
* Password Regular Expression (Form Validation & Security )
* Email Notification
* New Registration
* Custom email subject,content
* Auto Generate page for Login,Register
* Popup model for login,register, forgot password

= 1.1 =
* Added google reCAPTCHA to registration form

= 1.0.0 =
* Plugin Created

== Frequently Asked Questions ==

* Q-How to disable WordPress admin bar for all users except admin?
  <br>Go to Dashboard->WP User
  <br>1) Click on Disable Admin Bar check box
  <br>2) Save setting

* Q-How to disable signup form?
  <br>Go to Dashboard->WP User
  <br>1) Click on uncheck Enable Signup Form check box.
  <br>2) Save setting

* do you offer custom pricing?
<br> Yes, we do. We can offer you a custom plan to meet your requirements. Please let us know all your requirements and we will contact you asap.

* Q.want more feature?
 <br>If you want more feature then
 <br>Drop Mail :walke.prashant28@gmail.com

== Upgrade Notice ==
* Added Feature - Login with OTP Link

== Official Site ==
* For More Information
* https://wpuserplus.com
* https://www.wpseeds.com
* Or Advanced feature drop mail:walke.prashant28@gmail.com
