# laravel-tourify
A Laravel Package for adding Tour/Help to your Laravel Projects.

This is a simple package which helps the developers to add Tours for pages in your Products. 

For example: To define or explain the use of any component in your UI like a Button/Tab. 

The package gives the developers a rich UI to create/edit/remove/update Tour for a Route defined in your application.

### This package includes:
 - Migration - To keep track of which view uses which Tour file
 - Assets File - Used for UI for the Tours
 - views/Controller/Model - For Tour CRUD Operations

 The package makes use of https://github.com/LinkedInAttic/hopscotch to display the tour.

 ## Installation

 ```shell
composer require zusamarehan/tourify
```

Then you will need to publish few assets using the below command

 ```shell
 php artisan vendor:publish
```

Once the public is successful you need to import a stylesheet and js file in your master/layout file like so,

        <link rel="stylesheet" href="{{ asset('/vendor/zusamarehan/tourify/css/hopscotch.css') }}">
        <script rel="stylesheet" src="{{ asset('/vendor/zusamarehan/tourify/js/hopscotch.js') }}"></script>

Also, run migrations as well.

## Usage

Once installation is successfull  head to `/productTour/create` it will bring up the available routes names specified in your `web.php` file.


You can select the name and click on add Tour. Here you need to specify which div ID you need to target or show help/tour. Having ID is mandatory in your component.

Once you are satisfied with your tour data you can save it. It will create a file in your storage. You shouldn't be editing it or deleting it manually.

The last step is to add a custom blade directive in your view at the end of the file just add 
```php
@tour
``` 
to be able to recogize by the package to run tour on page load.


### NOTE
- For adding tours to your pages, names routes are required.
- To be able to target UI Components, an ID is required to be able to recogize by the package.
- Table data and files created by the package shouldn't be deleted by developers manually.
