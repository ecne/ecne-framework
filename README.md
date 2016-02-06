# ecne-framework
MVC &amp; ORM PHP Framework with a Template Engine

## Ecne - Documentation

#### Ecne Framework

*   [Installation](#installation)
    *   [Install Framework](#install-framework)
    *   [Install Dependencies](#install-dependencies)
*   [Controllers](#controllers)
    *   [Default Controllers](#default-controllers)
    *   [Creating a Controller](#create-controller)
    *   [Defining Actions](#define-actions)

* * *

#### Installation

Ecne framework is avaiable on [GitHub](https://www.github.com/natedrake/ecne_framework) and [Packagist](#).It is best to download the framework using the git command in your projects root folder

1\. Go to your webservers root folder, or the root folder of your project.  
2\. Open command prompt and run the following command:  
`git clone https://www.github.com/natedrake/ecne_framework`

#### Install Framework

Once you have downloaded Ecne to your project folder you should have a file structure like:  

*   Ecne Framework
    *   app
        *   controllers
        *   core
        *   libraries
        *   models
        *   views
    *   public
        *   res
    *	assets

Inside app is all the core functionality for this _Model View Controller_ framework. We store the controllers inside the controllers folder. The controllers will be responsible for loading the correct view depending on where in your website a user goes. It will also be responsible for loading in models which store the dynamic data for the webpage, and serve it to the view to be rendered in webpage.

#### Install Dependencies

With the command prompt still open, change the directory to the root directory of the ecne_framework folder. You will see a file name _composer.json_, this contains all the dependencies that Ecne is dependent upon and must be installed in order for Ecne to function. With the command prompt open and in the root folder of the ecne_framework folder, run the following command:  
`composer install`

This command will install all dependecies in a vendor folder and create an autload file to be use in your project. This has already been defined in the _index.php_ file in the root of ecne_framework directory. `include_once BASE_PATH . '/vendor/autoload.php';`

#### Controllers

Controllers are responsible for the transfer of model data to the view.  Controllers contain actions, which are basically routes that controller can load.  For example, if you had a Controller User, you could have multiple actions associated with that control that relate to a user account.  Actions may include, login, logout, register, settings.  Here is an example Controller class:


```<?php
namespace Ecne\Controller;

class NewController extends Controller
{
   public function action()
   {
         ... perform action ...
   }
}
```
You can now type `www.yourwebsite.com/newcontroller/action` into your browser and the code above will run.

#### Default Controllers

#### Creating a Controller

#### Defining Actions

