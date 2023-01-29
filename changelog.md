# Changelog

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
