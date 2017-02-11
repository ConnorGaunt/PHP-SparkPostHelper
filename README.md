
# SparkPostHelper  

###v1.0.0 - PHP  

## Description
SparkPostHelper is a __PHP based__ helper class to send emails __via the [SparkPost](https://www.sparkpost.com "SparkPost's Website ") API__. The reason for creating this class is that the [libary](https://github.com/SparkPost/php-sparkpost) linked in the SparkPost API docs required other packages and a PHP version above 5.6, which wasn't possible on the project I was working on. This class uses cURL so it will work in pretty much every version of PHP and not require any other packages to work.    


## Contents
* [Installation](#installation)
* [Basic Usage](#basic-usage)
* [Let's Explain Those Methods!](#lets-explain-those-methods)
* [Plans For The Future](#plans-for-the-future)
* [Contact](#contact)  




## Installation  
Currently the only way to install this helper class is to do it manually. (Plans to eventually submit to [Packagist](https://packagist.org/ 'Packageist Home Page') for installation via [Composer](https://getcomposer.org/ 'Composer Home Page'))   

1. Click the button at the top of this repo, then download zip.  
![Download Package](http://connorgaunt.com/opensource/clonedownload.png)

2. Unzip the folder
3. Copy the "Lib" folder and paste it somewhere in your project.  
![Copy Lib](http://connorgaunt.com/opensource/copylib.png "Copy lib")


4. Go to the php file you wish to use SparkPostHelper

5. At the top of the page, require/include the _autoload.php from inside lib/SparkPostHelper/

6. Now you've included the helper class, we need to use the namespace  
See example below
```PHP
<?php
include ('PATH_TO_COPIED_LIB/lib/SparkPostHelper/_autoload.php');
use SparkPostHelper\SparkPostHelper;
```
  
## Basic Usage  
To use the SparkPostHelper class, you need to call it with two parameters:
```PHP
$sparkPostHelper = new SparkPostHelper("<API_KEY>","<SENDING_DOMAIN>");
```
1. __API KEY__  
  * Pass in the API Key you generated using the SparkPost dashboard.  
  * eg: 38y32874329743289043b4324jhgjhg32483974 

2. __Sending Domain__  
  * The sending domain is the second parameter, you set this up in the SparkPost dashboard.  
  * eg: email.example.com  

Before we can send any emails, we need to define a few things:
* Recipients - _Required_
* Subject Line - _Required_
* HTML (Email Content) - _Required_
* Plain Text (Fall-back Email Content) - _Required_

``` PHP 
$sparkPostHelper = new SparkPostHelper(SPARK_POST_API_KEY, SPARK_SENDING_DOMAIN);
$sparkPostHelper->recipients(array(
    "example@example.co.uk",
    "example2@gmail.com",
    "exampleexample@live.co.uk",
));
$sparkPostHelper->subject("First SparkPost Email Via Helper");
$sparkPostHelper->html("<h1>This is awesome!</h1><p>This is my first email with SparkPostHelper</p>");
$sparkPostHelper->text("This is awesome!!! This is my first email sent with SparkPostHelper.");
$response = $sparkPostHelper->send();
```

That's it. That's the bare minimum to send emails via SparkPost using this helper class.


## Let's Explain Those Methods!

#### ->fromName(STRING)  
* __Required__: NO
* __Description__: This method is used to set the email sender's name, usually appears above subject line of the emails.
* __Parameters__: 1, STRING
```PHP
$sparkPostHelper->fromName("PHP-SparkPostHelper");
```
![From Name Example](http://connorgaunt.com/opensource/emailsuccessfrom.png 'From Name Example')  



#### ->recipients(ARRAY)  
* __Required__: YES
* __Description__: This method is used to set the recipients of the emails. It takes an array of strings (Email Addresses).  If you make the array associative you can attach a name to the recipient email, this is optional. See example.
* __Parameters__: 1, Array
```PHP
$sparkPostHelper->recipients(array(
    "Person Name" => "example@example.com",
    "example@example.com", 
    "example@example.com",
    "Person Name" => "example@example.com",
    "example@example.com"
));
```  
![Recipient Example](http://connorgaunt.com/opensource/recip1.png 'Recipient Example')  

#### ->subject(STRING)  
* __Required__: YES
* __Description__: This method is used to set the subject line of the emails.
* __Parameters__: 1, STRING
```PHP
$sparkPostHelper->subject("This is an email subject line.");
```
![Subject Line Example](http://connorgaunt.com/opensource/emailsuccesssub1.png 'Subject Line Example')  



#### ->replyTo(STRING)  
* __Required__: NO
* __Description__: If you want a user to hit reply, this will be the reply email address.
* __Parameters__: 1, STRING
```PHP
$sparkPostHelper->subject("contact@connorgaunt.com");
```
![Reply To Example](http://connorgaunt.com/opensource/emailreplyto1.png 'Reply To Example')  



#### ->html(STRING)  
* __Required__: YES
* __Description__: This is the default main body content for most email clients, this will be your emails.
* __Parameters__: 1, STRING
```PHP
$sparkPostHelper->subject("This is an email subject line.");
```
![HTML Body Content](http://connorgaunt.com/opensource/emailhtmlbody1.png 'Html Body')  



#### ->text(STRING)  
* __Required__: YES
* __Description__: This is the default fall-back content for all email clients, this will be your emails if html is not supported.
* __Parameters__: 1, STRING
```PHP
$sparkPostHelper->subject("This is an email subject line.");
```
![HTML Text Content](http://connorgaunt.com/opensource/emailtextbody1.png 'Text Body')



#### ->send()  
* __Required__: YES
* __Description__: This method is where all the magic happens, calling this method will send the email will all the information you set beforehand. Calling this method will return an array, so you can save that to a variable to check for errors and such.
* __Parameters__: 0
```PHP
$response = $sparkPostHelper->send();
// Response will be an array containing the object sent back from SparkPost
// such as error messages or success'
```  


## Plans For The Future
This is my first ever open source project so I started of very small. I would love to implement more of the [API's features](https://developers.sparkpost.com/api/transmissions.html) into this class but I believe this is everything you need for the very very basic to start sending emails right away. But in case you were wondering what I want to implement in the future, here's a list of a few of them.  
* CC 
* BCC
* Use Stored Email Templates
* Set Email Options
* Email Description
* Attachments
* Use Stored Recipient List  

  
  
## Contact
Please if you find an issue with the class please don't hesitate to post an issue. If you're wanting to see more of me, here are a few of my active social media links.  
[Website](http://connorgaunt.com)  
[Twitter](http://twitter.com/Connor_Gaunt)  
[Instagram](http://instagram.com/ConnorGaunt)  
[CodePen](http://codepen.io/ConnorGaunt)  
[Twitch](http://twitch.tv/ConnorGaunt)  





