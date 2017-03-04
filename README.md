PHP Facebook Gallery
====================

Simple PHP program to display public Facebook galleries. **[Live Demo](http://castletwo.com/facebook-gallery/)**

**Features:**

- Gallery caching to static HTML files
- Uses [prettyPhoto](http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/) for large images
- Clean UI based on [Twitter Bootstrap v3](http://twitter.github.com/bootstrap/)


Audience / Usage
----------------

The intended audience/usage is for businesses who have both a website and Facebook page. 
This gallery will allow you update your photos in one place and have them display on both your Facebook page and website.

Setup
-----

- Requires PHP 5.6+
- You will first need to create a Facebook app to obtain an App ID and App Secret. [Facebook Developers](https://developers.facebook.com/apps/)
- Next update the `index.php` config values to fit your needs:
```php
$config = array(
    'page_name' => '{FACEBOOK PAGE NAME}',  // https://www.facebook.com/{PAGE NAME}/
    'app_id' => '{YOUR APP ID}',            // Facebook assigns this during 
    'app_secret' => '{YOUR APP SECRET}',    // Same as app_id
    'breadcrumbs' => true,                  // Displays bread crumbs at top
    'cache' => array(
        'location' => 'cache',              // Directory to save cache files - PHP must have read/write access. (755 or 775)
        'time' => 7200                      // Number of seconds to keep cache
    )
);
```

Using with Existing Website
---------------------------

If you want to use this with an existing website, you'll need to import the CSS, Javascript, and two JS functions your self. Take a look at the `index.php` file for a the files and functions. Feel free to use the [issues](https://github.com/jveldboom/facebook-gallery/issues) page for any help with this.


Bugs / Issues / Suggestions
---------------------------

Please let us know if you see any bugs or issues. Would love help improving this, so pull requests are always welcome!
