# hyblog

### The hyblog blogging system

The name is an amalgam of hybrid and blog. It is a different approach to database driven systems partly inspired by Static Site Generators but with a more dynamic approach. Rather than having to build the site after each change, hyblog uses dynamic files which write/pull posts and comments to/from .md files on the fly.

### Installation

- copy all files and folders to your desired path - the repository includes example .htaccess files to get you going
- navigate to http(s)://{your chosen url}/setup.php and fill out the required fields
- Avatar will be the used as the favicon for the site, you can leave this blank and a default one will be used
- the file config.php will be created in the root of your chosen path
- submitting the setup options will take you to the admin page where you can make any required changes

**Note:** setup.php will be deleted once you submit the initial options

### Usage

Once you have completed setup you can get started quickly and easily.

**Logging in**

Tap/click the burger menu button at the bottom and click the 'ⓗ' symbol to go to the login page. Once logged in the symbol will change to the admin 'cog' icon where you can change the options set above. More on that later.

**Let's get blogging!**

Hyblog operates on a daily basis. Each day is a separate entity and the post for that day will be listed as they are typed.

When logged in, if there are no posts you will be presented with a simple form:

![hyblog post form](https://colinwalker.me.uk/uploads/2023/01/post_form.png)

Just start typing in markdown and hit post when done. It's as easy as that to create your first post! If you want to edit it or post something else just double-click/double-tap anywhere in the content and you will be taken back to the post form.

This is where you'll notice something a little different. The text you type will have had @@ added before it and !! with a time appended after it. These tell hyblog when to split the day's text between posts and when they were published. Here's an example:

```
@@ This is a first post.

!! 11:40:54

@@ This is a second post.

!! 12:40:23

@@ # Title

This is a third post with a **title**.

!! 14:20:51

@@ ! This should be a draft and not show.

!! 22:12:36
```

To start a new post just type @@ and you're off. The time will be added automatically when saving.

So, what else is going on here? Having a hash on the first line of a post indicates that it has a title - titles are not mandatory. Also, Adding an exclamation point followed by a space at the start of a post will make it a draft which won't show on the page or in the RSS feed. When you're ready to make it live just delete the exclamation point.

Each day is saved to it's own .md file in a year/month folder structure - the filename will be in the format yyyy-mm-dd.md. These folders are automatically created as needed. For now the year folders sit in the root of your chosen path but this will be amended in a future version to be in a sub folder.

**Comments**

Clicking the comment icon before each post shows a simple comment form. When a comment is submitted a new .md file is created in the same location as the post files. The filename will be comments**X**-yyyy-mm-dd.md where 'X' is the number of the post for that day.

After comments have been submitted, hyblog automatically pulls them out of the .md file and displays them under the relevant post. When logged in each comment will have a delete icon next to it allowing you to easily remove them. You can, of course, edit the .md file if you want.

**The About page**

The about page is empty by default and will initially display "Nothing here yet". Double-clicking that text will show a form where you can enter your required about information in markdown. You can edit it at any time by double-clicking the text when logged in. You've guessed it, this is saved back to a file called about.md under http(s)://{your chosen url}/about/

**RSS Feeds**

When posts are added or updated the RSS feed is rebuilt automatically and saved as hyblog.xml in your path root. There is an option in 'Admin' to use the daily feed digest. When this is set you 'yes' the link for daily.xml is included on the /feeds page. You will need to create cron job or equivalent to run dailyfeed.php each night (just after midnight is best) to rebuild the daily feed. If you don't want to use the daily digest just leave the option as 'no' in Admin.

**Admin**

While we're on the subject, the admin page also allows you to change your username, avatar image, site name, decsription, root URL, and email address. Leave the username blank when saving any changes to retain the one you chose at setup.

You can also change your password here.

The username and password are securely hashed and saved to config.php along with the other constants created by setup or reset on the admin page.

