# 5andbox

A bare bones Wordpress starter kit. Includes useful plugins, functions and a Gulp workflow to compile Sass and JavaScript.

## Installation

- Create a new directory for your website:

`cd ~/projects/my-new-website`

- Download a fresh Wordpress install:

With WP-CLI http://wp-cli.org/:

`wp core download`

Or with a terminal:

```bash
wget http://wordpress.org/latest.tar.gz
tar xfz latest.tar.gz
mv wordpress/* ./
rmdir ./wordpress/
rm -f latest.tar.gz
```

- Rename wp-config-sample.php to wp-config.php and configure it with your database information.

- Remove the wp-content directory (this repository will replace it):

`rm -rf wp-content/`

- Clone this repository:

`git clone git@github.com:dylanfisher/5andbox.git wp-content`

- Rename the 5andbox theme to your new website's name:

`cd wp-content/themes/`

`mv 5andbox/ my-new-website/`

- Edit the `css/sass/style.scss` file and configure the Theme Name, URI, author and description.

- Delete the .git directory and initialize a new git repo in the theme directory (unless you want to track plugins):

```bash
cd ~/projects/my-new-website/wp-content/
rm -rf .git
mv .gitignore themes/my-new-website/
cd themes/my-new-website
git init
git add -A
git commit -m "first commit"
```

## Developing

- Install Node, npm (Node Package Manager) and nvm (Node Version Manager)

- Install npm development dependencies

`cd ~/projects/my-new-website/wp-content/themes/my-new-website`

`npm install`

- Run gulp

`gulp`

Gulp automatically watches files and will livereload connected browsers.

## Deploying

- Update the htaccess file on your server with content from this theme's htaccess file.

- Confirm that your web host has backups enabled

## Have fun!
