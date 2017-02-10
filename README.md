# SparkPostHelper v1.0.0 - PHP
### Description
SparkPostHelper is a __PHP based__ helper class to send emails __via the [SparkPost](https://www.sparkpost.com "SparkPost's Website ") API__. The reason for creating this class was the [libary](https://github.com/SparkPost/php-sparkpost) linked in the SparkPost API docs required other packages and a PHP version above 5.6, which wasn't possible on the project I was working on. This class uses cURL so it will work in pretty much every version of PHP and not require any other packages to work.    


### Installation  
Currently the only way to install this helper class is to do it manually. (Plans to eventually submit to [Packagist](https://packagist.org/ 'Packageist Home Page') for installtion via [Composer](https://getcomposer.org/ 'Composer Home Page'))   

1. Click the button at the top of this repo, then download zip.  
![Download Package](http://connorgaunt.com/opensource/clonedownload.png)

2. Unzip the folder
3. Copy the "Lib" folder and paste it somewhere in your project.  
![Copy Lib](http://connorgaunt.com/opensource/copylib.png "Copy lib")


4. Go to the php file you wish to use SparkPostHelper

5. At the top of the page, require/inlcude the _autoload.php from inside lib/SparkPostHelper/

6. Now you've included the helper class, we need to use the namespace  
See example below
```PHP
<?php
include ('PATH_TO_COPIED_LIB/lib/SparkPostHelper/_autoload.php');
use SparkPostHelper\SparkPostHelper;
```
  
### Basic Usage  
To use the SparkPostHelper class, you need to call it with two paramters:
```PHP
$sparkPostHelper = new SparkPostHelper("<API_KEY>","<SENDING_DOMAIN>");
```
1. __API KEY__  
  * Pass in the API Key you generated using the SparkPost dashbaord.  
  * eg: 38y32874329743289043b4324jhgjhg32483974 

2. __Sending Domain__  
  * The sending domain is the second parameter, you set this up in the SparkPost dashbaord.  
  * eg: email.example.com  

Before we can send any emails, we need to define a few things:
* Recipients
* Subject Line
* HTML (Email Content) 
* Plain Text (Fallback Email Content)

``` PHP 
$sparkPostHelper = new SparkPostHelper(SPARK_POST_API_KEY, SPARK_SENDING_DOMAIN);
$sparkPostHelper->recipients(array(
    "example@example.co.uk",
    "example2@gmail.com",
    "exampleexample@live.co.uk",
));
$sparkPostHelper->subject("First SparkPost Email Via Helper");
$sparkPostHelper->html("<h1>This is asweome!</h1><p>This is my first email sent with SparkPostHelper</p>");
$sparkPostHelper->text("This is asweome!!! This is my first email sent with SparkPostHelper.");
```

