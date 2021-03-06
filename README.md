# Dashboard Changelog

**Contributors:** [Chris Reynolds](https://chrisreynolds.io)  
**Donate link:** https://paypal.me/jazzsequence  
**License:** GPLv3  
**License URI:** https://www.gnu.org/licenses/gpl-3.0.html  

Adds a GitHub release widget to your WordPress dashboard for a public GitHub repository.

## Installation ##

1. Download the zip file, unzip, and upload to your `/wp-content/plugins/` directory.
2. Activate Dashboard Changelog through the 'Plugins' menu in WordPress.
3. Go to your General Settings page, and add the repository you'd like to display updates from in <owner>/<repository-name> format (e.g. `jazzsequence/dashboard-changelog`).

## How to use ##
By default, Dashboard Changelog will pull updates from GitHub _releases_, with the body content of each release acting as content for each update. In order to use, your repository will need to use releases. (The GitHub API endpoint can be modified to use any endpoint that is available, however additional customization would likely need to be done to format the data that gets pulled into the update.) The plugin will pull the 3 most recent releases and cache the API data for 24 hours.

## Screenshots ##

![Dashboard changelog appearance](https://i.imgur.com/HxQ52rS.png)
[Dashboard Changelog appearance.]

![Dashboard changelog setting](https://i.imgur.com/3Nyhg5m.png)
[Dashboard Changelog setting in General Settings.]

## Developer Reference ##

There are a number of filter hooks that can be used to modify the plugin to suit your needs. By default, this plugin was built to pull GitHub releases, specifically. but this can be changed to any API endpoint the GitHub API provides, or, potentially, pull updates from any public API.

### `dc.api_expiration`
Filters the cache expiration time.

API requests are filtered using `wp_cache_set` and `wp_cache_get` so we are making as few hits to the API as possible. By default, cached data from the API is stored for 1 day. You can filter this to whatever length you would prefer.

#### Parameters

**`$expire`** _(int)_ The length to retain cached response codes.

### `dc.api.url`
The API URL

By default, this is `https://api.github.com/repos`, the GitHub API endpoint for repositories, specifically. If you want to use a completely different API, you can filter this variable.

#### Parameters
**`$base_url`** _(string)_ The API URL to filter.

### `dc.widget.max_display`
The number of updates to display.

By default, we display 3 updates (releases) from the API, but this can be updates here.

#### Parameters
**`$max_display`** _(int)_ The number of release updates to display.

## `JSDC_REPOSITORY`
Global constant that can be used to hard-code the repository. If defined, the setting does not display in general settings. This constant can be defined in the `wp-config.php` file or elsewhere.

**Note:** No validation is done at all on this string, so make sure it's saved in the proper format.
