# hyblog

### The hyblog blogging system

The name is an amalgam of hybrid and blog. It is a different approach to database driven systems partly inspired by Static Site Generators but with a more dynamic approach. Rather than having to build the site after each change, hyblog uses dynamic files which write/pull posts and comments to/from .md files on the fly.

See [changelog](https://github.com/colin-walker/hyblog/blob/main/changelog.md) for updates.

Check out the demo site [here](https://colinwalker.me.uk).

### Example sites:

- [Dispatches](https://log.kvl.me) – a good example of what can be done when you tweak hyblog and change the styles
- [Musings](https://log.amitg.net/) - another example of a custoised installation

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

When logged in, if there are no posts on that day you will be presented with a simple form:

![hyblog post form](https://colinwalker.me.uk/uploads/2023/01/postform.png)

Just start typing in markdown and hit post when done. It's as easy as that to create your first post! If you want to edit it just double-click/double-tap anywhere in the content and you will be taken back to the post form.

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

To make subsequent posts just click the '+' n the top right and you're off. The post content, separator and time will be appended to the day's file automatically when saving.

So, what else is going on here? Having a hash followed by a space on the first line of a post indicates that it has a title - titles are not mandatory. Also, Adding an exclamation point followed by a space at the start of a post will make it a draft which won't show on the page or in the RSS feed. When you're ready to make it live just delete the exclamation point.

Each day is saved to it's own .md file in a year/month folder structure - the filename will be in the format yyyy-mm-dd.md. These folders are automatically created as needed. For now the year folders sit in the root of your chosen path but this will be amended in a future version to be in a sub folder.

**Media uploads**

Above the post form is a "Choose file" button which allows you to select a file to upload. After selecting a file the button will change to "Selected" - you can click this to choose a different file or click "Upload". Once the file has been uploaded the original button will change to "Copy path", click this to copy the full external path to the file to your clipboard.

Uploads are place in a year/month folder structure under http(s)://{your chosen url}/uploads/

Images of type jpg, jpeg & png will have a .webp version created and placed in the same location.

**Comments**

Clicking the comment icon before each post shows a simple comment form. When a comment is submitted a new .md file is created in the same location as the post files. The filename will be comments**X**-yyyy-mm-dd.md where 'X' is the number of the post for that day.

After comments have been submitted, hyblog automatically pulls them out of the .md file and displays them under the relevant post. When logged in each comment will have a delete icon next to it allowing you to easily remove them. You can, of course, edit the .md file if you want.

**The About page**

The about page is empty by default and will initially display "Nothing here yet". Double-clicking that text will show a form where you can enter your required about information in markdown. You can edit it at any time by double-clicking the text when logged in. You've guessed it, this is saved back to a file called about.md under http(s)://{your chosen url}/about/

**Custom Pages**

A UI for creating custom pages in located in /admin - see the changelog for details

**RSS Feeds**

When posts are added or updated the RSS feed is rebuilt automatically and saved as hyblog.xml in your path root. There is an option in 'Admin' to use the daily feed digest. When this is set you 'yes' the link for daily.xml is included on the /feeds page. You will need to create cron job or equivalent to run dailyfeed.php each night (just after midnight is best) to rebuild the daily feed. If you don't want to use the daily digest just leave the option as 'no' in Admin.

Oh, the RSS feeds support rssCloud using Andy Sylvester's server at rpc.rsscloud.io.

**Admin**

While we're on the subject, the admin page also allows you to change your username, avatar image, site name, decsription, root URL, and email address. Leave the username blank when saving any changes to retain the one you chose at setup.

You can also change your password here.

The username and password are securely hashed and saved to config.php along with the other constants created by setup or reset on the admin page.

**Extras**

Some additional formatting and content options are included in content_filters.php to make things a bit quicker when posting:

- `[hr]` - will be converted to a centered, horizontal rule with 33% width
- `~~text here~~` - for strikethrough
- `~text here~` - for underline
- `^text here^` - for superscript
- `::text here::` - to highlight
- `==text here==` - for HTML mark tags
- `[a[link to audio file]a]` - to include audio tags for the given link
- `[v[link to video file]v]` - to include a video
- `[y[YouTube embed ID]y]` - to embed a YouTube video, NOTE: you only need to include the video ID
- `%%text here%%` - to include 'badges' or alerts such as 'Update' or 'Breaking' - these will be highlighted in a coloured badge or bubble
- `!!details text>!summary text!<` - to use HTML details/summary tags
- `![path_to_image,classes,alt_text]]` - this lets you put in a jpg, jpeg or png image and have it automatically replaced by a .webp image if the browser supports them. Classes are non-mandatory but see below.

**Classes and Markdown extra**

hyblog uses Emanuil Rusev's [Parsedown](https://github.com/erusev/parsedown) & [ParsedownExtra](https://github.com/erusev/parsedown-extra) libraries. You can add classes to items using the markdown extra syntax `![alt_text](path_to_image){.CSS_class_name}`

A number of classes are included in the CSS:

- .aligncenter
- .left (align left, width 48%)
- .right (align right, width 48%)
- .i50 (align center, width 50%)
- .i60 (align center, width 60%)
- .i75 (align center, width 75%)
- .i80 (align center, width 80%)
- .i90 (align center, width 90%)
- .i100 (align center, width 100%)

These classes can also be used in the webp markup above.

If you want to make changes to the CSS edit style.css then run minify.php to update style_min.css

**And that's it...**

I think that's it.

This is version 1.0 of hyblog, more a proof of concept than anything but updates and improvements will happen over time.

**Roadmap**

- posts and comments have been moved to a /posts/ sub folder

There is no specific roadmap for development but things I want to do are:

- rework posting/editing to hide post separators/date from the end user
- make hyblog extensible by creating an easy way to make new pages just by typing the name and markdown contents into a form
