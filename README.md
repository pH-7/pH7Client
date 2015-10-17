# pH7Client - Save tasks by bot your tasks!

**pH7Client** is a wonderful tool if you need to automate task (like posting articles every day to your blog, add message in a member area, etc). Yes! The pH7Client bot can do almost everything (you just need to create some rules and need to know the input name of the website's forms). Finally, you need to setup a cron for executing the script every X time on a server (Type ```crontab -e``` on your server Linux terminal, then add the cron jobs wanted and save it). Once finished, go to the beach, order a mojito and enjoy your REAL free time that you really deserved!


## Examples

### .1 - The Basic one

```PHP
// Include the library
require 'PH7Client.php';

use PH7\External\Http\Client\PH7Client;

$sUrl = 'http://ph2date-soft-example.com';
$sReauest1 = 'user/login/';
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
$oPH7CMSApi->post($sReauest1, $aLogin);
// Submit the form
$oPH7CMSApi->send();


/***** Send a message *****/
$aMsg = ['message' => $sBody];

// Send the message
$oPH7CMSApi->post($sRequest2, ['message' => $sBody])->setHeader(false)->send();

echo $oPH7CMSApi->getResponse(); // Will show the sucessful message telling you that your msg has been send
```

## Author

I'm **Pierre-Henry Soria**, young **PHP software developer** living currently in the Wonderful Manchester city, in the UK.

## Contact

You can send an email at **pierrehenry [AT] gmail {D0T} COM** or at **phy {AT} hizup [D0T] UK**


## Need a Social/Dating Networking Builder?

Have a look to my **[social dating Web Builder](http://ph7cms.com)**

I'm building this software in order to allow people to build Amazing Social/Dating Businesses really easily with almost no money!


## Licence

This project is under MIT license! Enjoy!!

Psst!! By the way, Feel free to contribute to it! If you can, That would be really amazing!

