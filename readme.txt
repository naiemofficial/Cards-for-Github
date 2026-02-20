=== Github Cards ===
Contributors: naiemofficial
Tags: github, github card, repository card, developer tools, api, profile card
Requires at least: 5.0
Tested up to: 6.7
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A plugin to showcase GitHub repositories in beautiful, dynamic card layouts similar to social media cards. Displays stars, forks, issues, contributors, description, and more using the GitHub API.

== Description ==
The plugin displays GitHub repository information in a stylish card format similar to social media preview cards.  
It fetches data from the GitHub API and presents details such as stars, forks, issues, contributors, language colors, description, and more.

Cards can load using PHP or JavaScript, support shortcode integration, customizable appearance, preloader animations, responsive auto-scaling, and color customization options.

== Features ==
- Display GitHub repository information in a card layout
- Fetch data from GitHub API
- Load via PHP or JavaScript
- Customizable card style & layout
- Shortcode support for easy embedding
- Option to show/hide specific repo details
- Responsive design & auto-scaling
- Skeleton or spinner preloaders
- Color customization options
- Language bar showing language percentage colors
- Caching for improved performance

== Settings Page ==
- Choose PHP or JavaScript loading method  
- Enable/disable preloader animation (spinner or skeleton)  
- Change spinner style  
- Auto-scaling support based on container width  
- Enable/disable language bar  
- Customize card colors, spinner color, skeleton background, wrapper preloader color, and language bar colors  
- Cache Enable/disable and set cache duration

== Shortcode Usage ==

### Standard Card
[github_card repo="naiemofficial/Github-Cards"]
Displays a full GitHub card with avatar, description, contributors, issues, stars, forks.

### Mini Card
[github_card mini="true" repo="naiemofficial/Github-Cards"]
Shows a minimal GitHub card similar to:  
https://gh-card.dev/repos/naiemofficial/Github-Cards.svg


### Customization Options
- class="custom-css-class" - Add a custom CSS class to the card for additional styling.
- heading="Custom Heading Text" (default: repository name) - Set a custom heading text instead of the default repository name.
- description-text="Custom description text" (default: repository description) - Set a custom description text instead of the default repository description.


### Hide Elements
Each component can be shown/hidden:

- username="true|false" default: true
- slash="true|false" default: true
- dash="true|false" default: true
- avatar="true|false" default: true
- description="true|false" default: true
- contributors="true|false" default: true
- issues="true|false" default: true
- stars="true|false" default: true
- forks="true|false" default: true


Example:
[github_card
repo="naiemofficial/Github-Cards"
avatar="false"
description="false"
contributors="false"
issues="false"
stars="false"
forks="false"
]


### Custom Avatar/Logo for repo card
[github_card repo="naiemofficial/Github-Cards" avatar="https://example.com/custom-avatar.png"]
Sets a custom avatar/logo image URL instead of the default GitHub owner avatar.
- false - Hides the avatar.
- url - Uses the provided URL as the avatar image.


### Limit Description Words
[github_card repo="naiemofficial/Github-Cards" description-words="10"]
Limits the description to 10 words.

== Installation ==
1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin through the **Plugins** menu
3. Go to **GitHub Cards** settings page under WordPress admin
4. Add the shortcode anywhere to display a card

== Contribute ==
This plugin is fully open source, and contributions are welcome!  
Report issues, request features, or contribute improvements on GitHub.

GitHub Repository:  
https://github.com/naiemofficial/Github-Cards

== Frequently Asked Questions ==
= Does this plugin require a GitHub API token? =  
No. Public repo data works without authentication. For high-traffic sites, using an API token is recommended.

= Will it slow down my website? =  
No. The plugin uses caching to reduce repeated API calls.

= Does it support private repositories? =  
Not at the moment.

== Changelog ==
= 1.0.0 =
Initial release.




