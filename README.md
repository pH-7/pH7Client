# pH7Client - Make your Tasks on Autopilot by Automating them!

**pH7Client** is a wonderful tool if you need to automate tasks (like posting articles every day to your blog, add message in a member area, etc) and save lot of times everyday! :smiley: Yes! The pH7Client bot can do almost everything (you just need to create some rules and need to know the input name of the website's forms).
In fact, it's a PHP Web client class that simulates a Web browser for retrieving webpage content, login to websites and posting forms, ...

Finally, you have created rules that you want to automate by pH7Client, you need to setup a cron for executing the script every X time on a server (Type ```crontab -e``` on your server Linux terminal, then add the desired cron jobs and save it). Once finished, go to the beach, order a mojito and enjoy your REAL free time that you really deserved!


## Examples

### .1 - The Basic one

```PHP
// Include the library
require 'PH7Client.php';

use PH7\External\Http\Client\PH7Client;

$sUrl = 'http://ph2date-soft-example.com';
$sRequest1 = 'user/login/';
$sRequest2 = 'user/message_box/myuser920';
$sUser = 'test@ph2date.com';
$sPass = 'testpass123';
$sBody = "Hey! What's up today? Psst! I'm a bot but I may understand you...";

$oPH7CMSApi = new PH7Client($sUrl);

/***** Log a user *****/
$aLogin = [
    'identity' => $sUser,
    'password' => $sPass,
    'remember' => 'on',
    'submit' => 'Login'
];

// Login the user
$oPH7CMSApi->post($sRequest1, $aLogin);
// Submit the form
$oPH7CMSApi->send();


/***** Send a message *****/
$aMsg = ['message' => $sBody];

// Send the message
$oPH7CMSApi->post($sRequest2, ['message' => $sBody])->setHeader(false)->send();

echo $oPH7CMSApi->getResponse(); // Will show the sucessful message telling you that your msg has been send
```


## Requirements

* PHP 5.4.0 or higher
* cURL PHP library


## Author

I'm **Pierre-Henry Soria**, young **PHP software developer** living currently in the Wonderful Manchester city, in the UK.


## Contact

You can send an email at **pierrehenrysoria [AT] gmail {D0T} COM** or at **phy {AT} hizup [D0T] UK**


## Need a Social/Dating Networking Builder?

Have a look to my **[Social Dating Web Builder](http://ph7cms.com)**

I'm building this software in order to allow people to build Amazing Social/Dating Businesses really easily with almost no money!


## Licence

This project is under MIT license! Enjoy!!

Psst!! By the way, Feel free to contribute to it! If you can, That would be really amazing!
