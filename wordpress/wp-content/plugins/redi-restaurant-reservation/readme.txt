=== ReDi Restaurant Reservation – Instant Restaurant Booking & Table Reservation System ===
Contributors: thecatkin
Tags: restaurant reservation, restaurant booking, table reservation, table booking, reservation system
Requires at least: 5.0
Tested up to: 7.1.4
Requires PHP: 7.4
Stable tag: 26.4.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Restaurant reservation & table booking with instant availability and confirmation. Used by ~400 restaurants.

== Description ==

ReDi is a restaurant reservation and table booking plugin for WordPress. Accept online table bookings with an instant availability check and instant confirmation. Your guests book a table in seconds, and you get a fully automated restaurant booking system — no manual email confirmations, with personalized notifications about the reservation status. In short, convert website visitors into restaurant guests.

[youtube https://www.youtube.com/watch?v=DmU4K8jbjBU]

= Why restaurants choose ReDi =

- **Proven in real restaurants** – about 400 restaurants in 300+ cities, nearly 5 million reservations processed.
- **Maintained, not abandoned** – tested with every new WordPress release; see the Changelog for regular updates.
- **Real support** – every day 07:00–23:00 CET via WhatsApp, email and Facebook, usually within 10 minutes. Multilingual support: English natively, other languages via translation. Plugin and booking form available in 9+ languages.
- **Your bookings, always accessible** – manage reservations from the ReDi dashboard on any device, even when your WordPress site is offline
- **Done for You** – prefer not to set it up yourself? We configure ReDi for you.

= How does ReDi compare? =

We publish a weekly health check of popular WordPress restaurant booking plugins: WordPress compatibility, last update, known vulnerabilities and announced shutdowns, scored with the same public rules for every plugin.
[See the comparison](https://reservationdiary.eu/wordpress-restaurant-reservation-plugins/)

= Switching from GloriaFood? =

GloriaFood table reservations end on 30 April 2027. ReDi imports your opening hours and reservation settings so you can switch without losing bookings.
[How to migrate from GloriaFood](https://reservationdiary.eu/migrate-from-gloriafood/)

= Get started in 3 steps =

1. Install and activate the plugin (Plugins > Add New > search "ReDi Restaurant Reservation").
2. Register and generate your API key — a "Reservation" page with the booking form is created automatically.
3. Set your opening hours, capacity and restaurant details. Done.

[Plugin overview video](https://youtu.be/ms6VO9-4RFA)

= Key features =

- **Instant availability check and confirmation** – before confirming, ReDi checks that the restaurant is open, that capacity is enough and that seats are free at that time. No manual approval needed (manual confirmation is optional).
- **Mobile-friendly booking form** – works on phones, tablets and desktops; guests see only the times that are really available.
- **Capacity control** – maximum seats per weekday, party size limits, multiple opening times per day.
- **Blocked times** – stop online bookings for a period and show guests the reason.
- **Waiting list** – guests can join the waitlist when you are fully booked.
- **Guest self-service** – guests cancel or modify bookings online by reservation number, email, phone or name.
- **Custom fields** – ask for anything you need: occasion, allergies note, high chair, GDPR consent.
- **Multilingual emails** – customizable confirmation emails in 20+ languages.
- **Integrations for developers** – webhook on new reservation, Google Tag Manager event, public API.

= Plans =

All plans use the same booking form and instant confirmation. Prices and billing options: [reservationdiary.eu/pricing](https://reservationdiary.eu/pricing/)

**Free** – no time limit

* Up to 100 guests per month
* Instant availability check and instant confirmation (manual confirmation optional)
* Custom fields (simple)
* Waiting list
* Daily reservations email

**Basic** – one restaurant, 30-day free trial

* Everything in Free, with unlimited reservations
* Dashboard for managing reservations, including a tablet view for the host
* Multiple working hours per day
* Statistics and CSV export
* Email template customization
* Daily notes and internal comments
* After-visit feedback request
* Custom fields (dropdown, options)

**Full** – all add-ons

* Everything in Basic
* Table management and automatic table assignment
* Pre-payment during reservation to reduce no-shows
* Discounts and daily offers
* Email marketing: Mailchimp, Newsletter plugin, Brevo (Sendinblue)
* SMS notifications: Twilio, BulkSMS, PushBullet
* WhatsApp notifications: Twilio, Ultramsg

**Done for You**

* Everything in Full
* We set up and configure ReDi for you
* Priority help to get live quickly

**Agency / multi-location**

* Manage multiple venues in one dashboard
* White-label options, custom API access, volume discounts
* [Book a call](https://calendly.com/reservationdiary/demo)

= AI Concierge (premium service) =

Never miss a call — and never miss the revenue behind it. The ReDi AI Concierge answers your restaurant's phone and WhatsApp, speaks with guests naturally in multiple languages, and books reservations directly into ReDi. It also answers guest questions about opening hours, location, menu — anything you train it on.

Use it as a full-time receptionist or as a backup outside working hours and during peak times. Availability is currently limited — contact us at info@reservationdiary.eu to learn more.

= Custom services (on request) =

* Additional languages for the reservation form
* Reservation module for restaurant catalogues and directories
* Your own integration via our [public API](https://redi.atlassian.net/wiki/spaces/REDIPLUGINDOCS/pages/1665138689/Reservation+Diary+API)

Contact us: info@reservationdiary.eu or WhatsApp — all contact details are on [reservationdiary.eu](https://reservationdiary.eu/).

= Supported languages =

Catalan, Czech, Danish, Dutch, English, Estonian, Finnish, French, German, Hebrew, Hungarian, Italian, Japanese, Latvian, Lithuanian, Norwegian, Polish, Portuguese, Russian, Slovak, Spanish, Swedish, Turkish. Need another language? Contact us.

= Resources =

* [Documentation](https://redi.atlassian.net/wiki/spaces/REDIPLUGINDOCS/overview)
* [Video tutorials](https://www.youtube.com/channel/UCvMNupiAUT7enwnDhgrbKOg)
* [Live demo of the host dashboard](https://demo.reservationdiary.eu/)
* [Facebook page](https://www.facebook.com/ReDiReservation)

== External services ==

ReDi is a hosted reservation service. The plugin connects to the following external services:

- **ReDi Reservation API (api.reservationdiary.eu)** – required for the plugin to work. Used to register your restaurant and API key, load availability, and create, modify and cancel reservations. Sent: restaurant settings; for each booking the date, time, party size and the guest details entered in the form (name, email, phone, comments, custom fields). Data is sent when you save settings and when a guest checks availability or books. Provided by CATZ SOFT OÜ – [Terms](https://reservationdiary.eu/terms/), [Privacy policy](https://reservationdiary.eu/privacy/).
- **Google reCAPTCHA v3** – only if you enable captcha in settings. Protects the booking form from spam; Google receives the visitor's IP address and browser data when the form is shown. [Google Terms](https://policies.google.com/terms), [Google Privacy Policy](https://policies.google.com/privacy).
- **Google Tag Manager** – only if you add your GTM container ID. Sends a reservation event to your own GTM container. [Google Privacy Policy](https://policies.google.com/privacy).
- **YouTube** – tutorial videos embedded in the plugin settings pages load from YouTube when the settings page is opened. [Google Privacy Policy](https://policies.google.com/privacy).
- **"Report a problem" form** – when you submit it, the restaurant name, API key, contact email, description and attachments are sent to ReDi support (info@reservationdiary.eu).

== Frequently Asked Questions ==

= Is ReDi compatible with the latest WordPress? =

Yes. We test ReDi with every new WordPress release – check "Compatible up to" on this page. You can also see how it compares with other booking plugins in our weekly health check: https://reservationdiary.eu/wordpress-restaurant-reservation-plugins/

= How is ReDi different from free booking plugins? =

Free plugins give you the code, and keeping it working is up to you. ReDi is a service: we maintain the plugin and the hosted booking engine, fix issues and give you live support every day. The free version covers up to 100 guests per month so you can try it on your site first.

= I use GloriaFood for table bookings. Can I switch? =

Yes. GloriaFood table reservations end on 30 April 2027. We import your opening hours and reservation settings – see https://reservationdiary.eu/migrate-from-gloriafood/

= The reservation form / shortcode is not displaying — what should I check? =
First, make sure the page contains the [redirestaurant] shortcode (the plugin creates a "Reservation" page with it automatically on activation). If the form still does not appear: 1) check that the API key was generated during registration (Settings > ReDi Restaurant Reservation), 2) temporarily switch to a default theme to rule out a theme conflict, and 3) disable caching/optimization plugins for the reservation page. If it still does not show, contact us at info@reservationdiary.eu — we respond quickly and will help you get it running.

= How do guests book a table online? =
Guests open your reservation page, pick a date, party size and an available time slot, and the table booking is confirmed instantly. The restaurant reservation form works on mobile, tablet and desktop.

= Is this restaurant booking plugin free? =
Yes. The free version includes the instant availability check, instant and manual confirmation, custom fields, a waitlist and confirmation emails for up to 100 guests per month. The Basic version removes the limits and adds a reservation management dashboard.

= Does the table reservation system send instant confirmations? =
Yes. ReDi checks availability in real time and sends a fully customizable, multilingual confirmation email the moment a reservation is made — no manual approval needed.

= Can I limit table bookings per time slot or weekday? =
Yes. Set the maximum number of seats per weekday, define multiple open/close times per day, and block times when online reservations should not be accepted.

= Does the restaurant table booking form work for cafes and bars? =
Yes. ReDi works for any business that takes table reservations: restaurants, cafes, bistros, bars and pubs of any size.

= Can guests cancel or modify a reservation? =
Yes. Guests can cancel or modify their booking online using their reservation number, email, phone or name.

= Is the reservation form available in my language? =
The table booking form is available in more than 20 languages, and email templates are customizable per language. New languages can be added on request.

= Can I collect pre-payments for reservations? =
Yes. The Full version lets you charge guests when they book a table, reducing no-shows.

= Can ReDi take reservations by phone? =
Yes. With the AI Concierge premium service, an AI assistant answers your phone and WhatsApp, takes reservations directly into your ReDi system, and answers guest questions about opening hours, location and more. It can run 24/7 or as a backup for out-of-hours and peak-hour calls. Contact us at info@reservationdiary.eu for details.


== Installation ==

1. Click on "Install now" to install plugin
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings/Redi Restaurant Reservation page
4. Follow instructions on the page to setup plugin


When the plugin is activated, a "Reservation" page is created automatically and you can make your first reservation straight away. You can also create a page manually and add the [redirestaurant] shortcode.

Need help or customization? Contact us at info@reservationdiary.eu — we usually reply within 10 minutes during support hours (07:00–23:00 CET).

== Screenshots ==
1.	Example of the plugin's first page installed on a default theme. When the plugin is activated, a new "Reservation" page is automatically created. Step 1: The user selects a restaurant, specifies the number of adults and children, and clicks on the "Next Step" button to proceed.
2.	Example of the calendar control used for selecting available dates.
3.	On this step, the user is requested to select an available time slot if any are available.
4.	Once the user selects a reservation time, they will be asked to fill out a reservation form with contact details and any custom fields required by the restaurant.
5.	Reservation confirmation screen. At this stage, the user receives a confirmation email, while the restaurant owner gets a list of all upcoming reservations.
6.	Cancel reservation page for clients, allowing them to manage and cancel their bookings.
7.	Step 1: Modify reservation. The user can find an existing reservation by entering any available details such as reservation number, phone number, email, or name.
8.	Step 2: Modify reservation. The user can update reservation details as needed.
9.	Modify a reservation from the Tablet PC version, allowing users to adjust bookings conveniently on a tablet.
10.	Setup screen for configuring the number of available seats, the maximum number of persons per reservation, and the restaurant’s opening times and days of the week.
11.	Setup screen for entering restaurant details, including name, address, country, phone number, email, and website URL.
12.	List of upcoming reservations on Mobile/Tablet PC, available only for Basic package users. Demo version can be accessed by this link: <a href="https://upcoming.reservationdiary.eu/en/Entry2/89a08039-2c68-47c4-978c-3acb3e9989ea" target="_blank">Open demo version</a>.
13.	Detailed reservation information on Mobile/Tablet PC, available only for Basic package users. The profile photo is retrieved from the user's Facebook public profile. Additional details such as photo, visit history, and rating are available upon request.
14.	Cancel reservation from the Tablet PC version, providing users with an easy way to manage cancellations on a tablet.
15.	Configuration for setting the maximum number of available seats for online reservations by weekday, available only for Basic package users.
16.	Setting for defining custom fields that users must fill in during the reservation process.
17.	List of time shifts available for reservations, accessible only to Basic package users.
18.	Open and close time configuration for different time shifts, available only for Basic package users.
19.	Time restrictions before reservation for each time shift by weekday, available only for Basic package users.
20.	Blocked time list management, available only for Basic package users.
21.	Edit blocked time settings, allowing changes to restricted time slots, available only for Basic package users.
22.	List of all email templates available for customization to fit specific business needs.
23.	Example of the email customization page, showing how email notifications can be tailored.
24. New booking form (React theme): Select the restaurant location for your reservation.
25. New booking form (React theme): Choose the number of adults attending.
26. New booking form (React theme): Choose the number of children attending.
27. New booking form (React theme): Select your preferred reservation date from the calendar.
28. New booking form (React theme): Pick a time slot for lunch or dinner.
29. New booking form (React theme): Enter your contact details and finalize the reservation.
30. New booking form (React theme): View the reservation confirmation with all booking details.

== Changelog ==
= 26.4.1 =
* Fixed issue with support form
* Added version number for script file for resetting cache
* 11/8
* Collect structured reasons on plugin deactivation
* 11/13
* Send plugin version number to api
* 11/15
* Fixed reservation ownership check so empty or sanitized-away personal info cannot match empty email, phone, or name
* 11/16
* React theme: show hints on disabled booking steps until previous steps are completed
* Fixed theme CSS leaking into the React reservation form styles
* Working time settings: accept am/pm and other common time inputs, save as 24h, show using WordPress time format, and mark invalid fields with a clear error

= 26.4.0 =
* Replaced UserVoice with a local "Report a problem" form that emails info@reservationdiary.eu
* Settings now displays available options from Free, Basic and Full packages for visibility
* Added React theme support to pre select date
* Added link to email troubleshooting guide in settings

= 26.3.0 =
* Fixed double account registration
* added support for google captcha v3
* added saving changes popup in config
* Default theme: Enter in Name/Email/Phone/Comments no longer silently changes the selected time to the day's first slot
* Default theme: Changing date or guest count clears the selected time and disables submit until a new time is picked
* Reject reservation-create requests whose start time was not offered for the selected date, party size and place

= 26.2.0 =
* Added server-side Google Tag Manager support
* Disabled plugin cache when admin is logged in
* Clear plugin cache when settings are saved
* Improved API timeout to scope only to reservation API
* Fixed reservation canceling for React theme

= 26.1.0 =
* Fixed Update reservation page issue
* Fixed cancel reservation redirect to thank you page
* Added Edit reservation functionality to React Theme


= 26.0.1 =
* Updated translations: Finnish, German, French

= 26.0.0 =
* Added event to google tag manager when reservation is created
* Added protection to json containing elements that is added by the server
* Added webhook when reservation is created for integration possibility
* Fixed function to support older PHP version
* Fixed missing translations for React theme, updated Est/Rus translations, fixed closed restaurant style for React theme, Added Estonian language for emails
* Fixed currency symbols in menu, added select button in menu for React theme
* Default theme: Fixed issue with custom fields when dropdown or options does not have any elements
* Default theme: Add option how adults and guests are selected. Currently it is asking Adults and then Kids, but some customers wants Total Guests and Kids. So depends on option settings, plugin changes behavior
* Zeplin: if there is only one restaurant, do not show drop down and do not show it on reservation details
* Zeplin: Added loading icon when calendar, time is loaded and when reservation created
* Zeplin: Auto scroll to first available date in calendar
* Zeplin: If there is only 1 time shift returned show only one tab where time options are shown
* Zeplin: Phone number pre selects proper country based on user locations
* Zeplin: Show full address in dropdown and in information box
* Admin: Fixed escaping text with ' and \ 
* Removed custom language folder support
* Fixed GDPR custom field saving with links
* Switched to Semantic Versioning per client request
* Removed red styles from calendar for weekend days in React theme
* Removed scrolling to reservation form on first load

= 25.0918 =
* Removed nonce check for api calls due to cache issues. Set default Theme R for new installations, updated alternative time stamps, fixed custom fields values passed
* Fixed passing reservation duration for Theme react 25

= 25.0828 =
* Added new theme: Theme React 25 (BETA)
* Added Save buttons after every section for less scrolling down
* Improved calendar, so it navigates to first month according to late booking, and max date is set in calendar according to early booking
* Added cancel reservation form to Theme react 25
* Added menu-preselection addon support to There react 25

= 25.0513 =
* Changed language options from generic to locale-specific versions
* Added dropdown for selecting number of children in reservation
* Adults and children values are now sent independently to the reservation API, allowing separate handling
* Added ability to sign up for the waitlist at a specific time
* Added new theme: Theme 25 (BETA)
* Added option to enable/disable the Comments field
* Fixed XSS vulnerability in Admin settings

= 24.1209 =
* Added filter by open time name when provided as a parameter in the shortcode
* Added the ability to place custom fields in any order on the reservation form, including before the first field
* Fixed issue with the waitlist

= 24.1015 =
* Changed tooltip styles for disabled times, so that information can be shown on mobiles as well
* Fixed Cross-Site Scripting vulnerability in setup wizard pages
* Added query parameter support select-date to pre select reservation date in calendar

= 24.0902 =
* Added a parameter for place ID to the discount filter, allowing discounts to be defined for specific places.

= 24.0730 =
* Bugfix in plugin activation

= 24.0726 =
* Scroll calendar to first available month
* Fixed loading of alternative time settings

= 24.0712 =
* Fixed Broken Access Control vulnerability
* Fixed issues when admin page brakes for French translation
* Updated according to Plugin Check recommendations

= 24.0422 =
* Support for multilingual text in message for large groups and fully booked message
* Updated catalan translation
* Fixed issue with changing and modifying places

= 24.0303 =
* Fixed XSS vulnerability injection in admin panel

= 24.0128 =
* Added option to control if reason is required when guest is canceling reservation

= 23.1219 =
* Added more options to alternative time step, 10 min and 20 min

= 23.1124 =
* Added open time validation settings
* Hide unnecessary form elements for cancel reservation, when one required fields is filled in

= 23.1106 =
* Removed nonce check for reservation form 
* Improved admin setting form validation
* Added subscribe to newsletter box

= 23.0926 =
* Email Sending Update: Now, when an email is sent, the plugin will use the email address configured in the plugin settings instead of the WordPress admin email.
* Enhanced Welcome Page: We have enhanced the welcome page to include convenient links for obtaining personalized assistance and connecting with us through our social channels.
* Admin Settings Page Makeover: The admin settings page has been given a fresh, redesigned look for improved user experience.
* Translation Support: We've introduced support for a translation folder located outside of the plugin. This means that with each update, your translations will remain intact, ensuring a seamless multilingual experience.

= 23.0907 =
* Added Lithuanian and Latvian languages. Updated French translation
* Added Select place option when there more than one restaurant

= 23.0629 =
* Fine tuned registration flow
* Added collecting feedback about reservation experience and plugin usage for future plugin improvements
* Fixed security finding 
* Added nonce check for CSRF
* Added permission check for changes in admin settings
* Added page to reconfirm reservation by guest before on a day of visiting restaurant

= 23.0211 =
* Added support for reservation duration when there are multiple restaurants on one page

= 23.0123 =
* Fixed bug: After first plugin installation generate API Key by email gives an error.
* Fixed bug: When calendar is in popup mode, and dates are loaded, then the user has to open it again.
* Fixed bug: After installation, by default reservation page does not show success page.
* After installation by default calendar is click to show, changed to show always.
* Timeshift normal mode is hidden for new installations
* TimePicker type setting is hidden for new installations
* Fixed bug: Cancel reservation error in wordpress.
* Pre-selection of the country in the settings after the first installation.
* Fixed bug: When saving settings after installing the plugin, an error occurred.

= 22.1130 =
* Reverted settings block with time shift

= 22.1117 =
* Fixed calendar to disable closed days

= 22.1018 =
* Fixed custom fields appearance
* Fixed hide steps setting default value
* Updated Spanish translation

= 22.1017 =
* Added support for discount addon
* Changed date parsing from gmdate to date
* Changed the way local time is obtained

= 22.1016 =
* fixed date format when submitting waitlist form 
* split into multiple files so that loco translate picks files that is less than 100kb
* added separated setting for email via SMTP
* removed session init
* updated calendar to show fully booked days
* check box custom fields extended to have default values
* changed order between date and guests
* fixed option to collect phone number with country code
* added possibility to define later reservation time in minutes, hours and days 

= 22.0322 =
* fixed reservation form for iPad and Safari browser

= 22.0303 =
* fixed date bug in wait list
* updated local time preview in admin settings
* added video for Reservation Form settings and Reservation settings
* added more videos for settings
* added support for flexible reservation duration time per time block
* updated time format on modify reservation page


= 21.0819 =
* Added First/Last name enable option
* Added Phone number country flag disable options
* Updated/Shown 'End reservation time' front end during reservation
* Added video instructions
* Added link to documentation
* Reorganized place reservation settings


= 21.0601 =
* Renamed Upcoming to Waiter Dashboard
* Added return back to WordPress admin dashboard from Basic package settings and Waiter Dashboard


= 21.0524 =
* Updated custom fields
* Added new custom field Birthday for using in promotions
* Fixed new key warning

= 21.0503 =
* Removed unneeded folders and files
* Removed settings iframes
* Removed diagnostics data collection
* Added data sanitization
* Updated calling of files locations
* Renamed constants to plugin specific

= 21.0426 =
* Fixed XSS vulnerability

= 21.0307 = 
* Added Català language
* Added link to videos
* Updated German translations
* Sorted admin settings
* Added information where reservation was created
* Fixed session creation

= 20.0810 =
* Select number of guests instead of pre-selected value

= 20.0727 =
* Settings to set max time before reservation in hours and days
* Custom fields as dropdown and options

= 20.0608 =
* Fixed date format when changing places
* Added option to show seats left when time is selected
* Extended size in database for custom fields

= 20.0516 =
* Added edit reservation form
* Fixed entry to wait list
* Save custom field to local database

= 20.0402 =
* Save reservations to local database
* International phone number selection
* Added captcha
* Added children drop down
* Allow to customize fully booked message
* Improved first available date selection
* Allow to enter into waitlist when part of the day is closed

= 20.0315 = 
* Added login via social network accounts on reservation form

= 20.0312 = 
* Fixed cancel reservation form

= 20.0229 = 
* When plugin is not working user can send direct email to restaurant
* Fixed custom fields issue when there are multiple restaurants
* Added option to enable/disable cancel reservation form
* Improved reservation form auto-complete
* Moved logic of showing available times to script

= 19.1220 = 
* Added support to override default reservation confirmation page

= 19.1126 = 
* Fixed blocked dates with new line in reason

= 19.1030 = 
* Added field for time to wait list form

= 19.1003 = 
* Fixed issue with waitlist and gdpr checkbox
* Fixed blocked dates when ' is included in reason

= 19.0914 = 
* Removed extra slash from path
* Fixed wait list to not pickup custom fields validations
* Blocked wait list for current day
* Added support for overriding frontend file and styles file from current theme directory
* Added phone to wait list
* Added GDPR checkbox to wait list

= 19.0513 = 
* Added Wait List feature, that allows guests to enter their information when fully busy

= 19.0302 = 
* Excluded custom fields from printing when specified

= 19.0224 = 
* Added tool tip to calendar for disabled dates
* Replaced Email field to Email, Phone or Name on cancel reservation form
* Allow to pre-populate fields in reservation form from the link
* Added new parameter to custom fields to exclude parameter from printing
* Added possibility to change API key 

= 18.1003 = 
* Improved reducing number of seats without losing reservations

= 18.0610 = 
* Fixed updating disabled dates in calendar when place is changed in dropdown
* Changed "allow to contact by SMS", "Send newsletter and promotion", "Reminder" checkbox default values to unchecked to comply with GDPR 

= 18.0322 = 
* Fixed issue with missing custom fields

= 18.0225 =
* Added specific field types for SMS/Email reminders
* Added more strict validation for Phone number

= 17.0114 =
* Fixed check of available time when minimum number of guests is more than one
* Added reservation number to thank you page
* Disabled in calendar non-working days. Limited calendar max time to 1 year.
* Added support for manual reservations
* Added Norwegian translation
* Scroll to top of the form instead of page to support single page sites
* Added no translate directive to fix Google translate issues

= 15.0422 =
* Fixed cancel reservation with space in number
* Changed country from free form field to dropdown
* New variation is added to plugin, that displays all possible times for selected date
* Updated list of languages and translation
* Added diagnostics for installation troubleshooting
* Added support for translation to Upcoming Reservations TabletPC version
* Added description to each setting
* Added widget to collect new feature requests
* Disable blocked dates in calendar
* Added list of reservations

= 14.0904 =
* Extended view for basic package settings
* Removed warnings
* Improved error handling during first registration
* Added support for Email CC for reservation notifications

= 14.0630 =
* Limit maximum time before reservation
* Fixed warnings
* Fixed upcoming reservation list
* New style for upcoming reservation email
* Increased time out for API call
* Added check for comments size

= 14.0511 =
* Added settings to select Min and Max party size
* Added message to client when he select Large group
* Added setting to specify alternative time step
* Added Portuguese (Brazil) translation
* Added Swedish translation
* Fixed multi language support
* Fixed time displayed in drop down
* Removed line brakes from javascript to prevent WordPress inserting p elements there when content formatting is turned on
* Removed direct dependency from curl library
* Added Google Analytic Tracking Code to thank you page
* Add extra attribute to br to prevent controls displacement
* Added ability to cancel reservation by client
* Added possibility to override errors returned by API
* New client reservation confirmation email template

= 14.0221 =
* Added alternative time picker for better reservation time selection on mobile devices
* Added limits to text areas
* Moved all texts to language files

= 14.0114 =
* Added support for multiple places (for basic package users)
* Added better error handling to Admin console
* Changed style of confirmation message
* Scroll up page to confirmation message
* Set calendar style to display on top of all elements
* Moved cancel functionality to separated Tab
* Fixed double click on reservation
* Fixed manual date input

= 13.1022 =
* Added setting for maximum persons per reservation
* Added support for custom fields that user should fill during reservation
* Added Polish language

= 13.0919 =
* Added Portuguese language
* Added German language
* Refactored calendar localization support to work on PHP 5.2 version
* Improved error handling

= 13.0817 =
* Added calendar localization support

= 13.0815 =
* Added cancel reservation from admin panel

= 13.0716 =
* Added possibility to specify date format in emails
* Added to configuration reservation duration time

= 13.0625 =
* Added timezone support

= 13.0618 =
* Fixed problem with double escaping of ' or \ symbols

= 13.0530 =
* Added Dutch translation
* Added Danish translation
* Added Spanish translation

= 13.0505 =
* Added support for different time formats

= 13.0406 =
* Added French translation
* Added possibility to provide Short and Full description about restaurant
* Added language selection for emails that are sent to administrator
* Added configuration of minimum time in hours before reservation

= 13.0316 =
* Fixed incompatibility with plugins that resides on same page
* Added multi language support to reservation form
* Added translations on Russian and Estonian

= 13.0302 =
* Fixed incompatibility with some themes

= 13.0203 =
* Changed persons from free form text to drop down
* When user changes date, time or number of persons, form is closed and user has to check reservation availability with new form data
* Added required field validation on a client side
* When fully booked added information message

= 13.0128 =
* Added settings saved message box to admin page
* Added check box that asks for permission to publish restaurant details into reservationdiary.eu catalogue
* Fixed pre populating of URL field in admin page

= 13.0119 =
* Updated reservation confirmation text
* Selected reservation time highlighted with bold style
* Added loading icon
* Applied time zone of the general WordPress settings
* Removed links from confirmation email

= 13.0114 =
* Initial version