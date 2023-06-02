# 5andbox

A bare bones Wordpress starter kit. Includes useful plugins, functions and an esbuild workflow to compile Sass and JavaScript.

## Installation

- Create a new directory for your website:

`cd ~/projects/my-new-website`

- Download a fresh Wordpress install:

With WP-CLI http://wp-cli.org/:

`wp core download`

Or with a terminal:

```bash
curl https://wordpress.org/latest.tar.gz --output latest.tar.gz
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

- Edit the `style.css` file and configure the Theme Name, URI, author and description (this is a duplicated plain CSS file, in addition to `style.scss` set up in SCSS).

- This theme includes a version of [Forest Assets](https://github.com/dylanfisher/forest-assets) adjusted to work with Wordpress.

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

## Recommended Plugins

> This theme ships with Advanced Custom Fields and Classic Editor. You may also find the following plugins helpful.

- [Better Images](https://wordpress.org/plugins/better-images/)
- [SVG Support](https://wordpress.org/plugins/svg-support/)
- [WP Migrate DB Pro](https://deliciousbrains.com/wp-migrate-db-pro/)

## Developing

- Install Node, npm (Node Package Manager) and nvm (Node Version Manager)

- Install npm development dependencies

`cd ~/projects/my-new-website/wp-content/themes/my-new-website`

`npm`

- Run esbuild

`npm run build`

esbuild automatically watches files and rebuilds assets on change.

### Excluding build files from your text editor

Add the following to your text editor's project configuration to exclude build files from being indexed.

Sublime Text:

```json
{
  "folders":
  [
    {
      "path": "/Users/dylanfisher/projects/my-new-website/wp-content/themes/my-new-website",
      "folder_exclude_patterns": ["dist", "build", "/Users/dylanfisher/projects/my-new-website/wp-content/themes/my-new-website/vendor"],
      "file_exclude_patterns": ["style.css"]
    }
  ]
}
```

## Deploying

- Update the htaccess file on your server with content from this theme's htaccess file.

- Confirm that your web host has backups enabled

## Have fun!
