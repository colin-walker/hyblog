# Changelog

## 10th Feb 2023

Should have fixed the Daily Feed 'titles' issue.

Files updated:

- dailyfeed.php

## 6th Feb 2023

New feature: choose date format in setup (choices are dd/mm/yyyy & mm/dd/yyyy)

Files updated:

- setup.php
- hyblog.php

## 6th Feb 2023

Fixed bug with comments not deleting. It _used_ to work so I obviously made a change but didn't do proper regression testing.

Files updated:

- delcomment.php

## 31st Jan 2023

Added ['now' namespace](https://github.com/colin-walker/Now-Namespace) support.

After one or more custom pages have been created a new option will appear in admin: "Now namespace page". This will let you select from the available custom pages.

The content of the chosen page will then be added to the RSS feed as the `<now:content>` update.

- `<now:title>` will be the page title
- `<now:link>` will be the link to the update page
- `<now:timestamp>` will be the last modified date of the page's .md file

A supporting reader can then get the update information alongside posts.

Files added:

- updatepage.php
- /admin/reset.html

Files Updated:

- managepages.php
- page.php
- rss.php
- setup.php
- /admin/admin.php

## 30th Jan 2023

Added option to set the site sub title to /admin. It will set to '(hybrid blog)' by default.

## 29th Jan 2023

Slight update to page template for formatting purposes.

Files updated:

- page.php 

## 29th Jan 2023

Security updates added to the new page management pages and for comment deletion.

Files Updates:

- addpage.php
- delcomment.php
- delpage.php
- managepages.php

## 29th Jan 2023

Another small change to more reliably insert new lines between posts when added to the day's .md file.

Files Updated:

- addpost.php
- newpost.php

## 29th Jan 2023

Big update!

Everything updated with better references to file locations (using `dirname(__FILE__)` & `dirname(__DIR__)` instead of `$_SERVER['DOCUMENT_ROOT']`) so it works when installed outside of the root folder.

**Page management**

Custom pages can be added, updated, deleted! In /admin there is now a link to 'Manage pages' at the top.

- click 'Add a page' to go to a simple form with just 'Title' and 'Content' fields. Pages are saved as .md files in /pages/ – add your content in markdown and HTML
- any existing pages will be listed as links to take you to an 'update' page
- each existing page will have delete icon to the right

A test page - test.md - is included. (You can delete that.)

New file page.php accepts a URL parameter ?p={page_name}, gets the file contents from the relevant file, and displays the page. The address in the browser history is then changed to a cleaner version using javascript - a page called 'test' will have the link http(s)://{root_url}/test/ rather than http(s)://{root_url}/page.php?p=test.

As the page addresses don't actually exist the new 404 page checks if the URI matches one of the pages and redirects to /page.php?p=test

footer.php has been updated to check /pages/ for files and display links for each in the menu tray.

The example .htaccess has been updated to include `ErrorDocument 404 /404.php` to ensure it is used if an address doesn't exist. If you are not using .htaccess you should ensure that 404.php is called.

Files added:

- .htaccess
- /pages/test.md
- 404.php
- addpage.php
- delpage.php
- managepages.php
- page.php

Files Updated:

- just about everything else! 😆

## 28th Jan 2023

Improvements to posting:

- the first post of the day is added as normal
- after this, a new '+' icon will show top right to show a new post form
- the post content will be auto-appended to the day's file

Files added:

- addpost.php
- script.js

Files updated:

- hyblog.php
- style.css
- style_min.css
