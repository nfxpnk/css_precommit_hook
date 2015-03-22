
# css_hooks
>CSS pre-commit hook for TortoiseSVN

#Getting started

###TortoiseSVN -- http://tortoisesvn.net/downloads.html</li>
###PHP5 -- http://windows.php.net/download

Instal the latest release for windows **php-5.6.7-Win32-VC11-x86.zip**

Extract it to any writable folder, for an example
``` 
c:/apps/php
```

Check if it works as expected with command
``` 
c:/apps/php/php.exe -v
```

###node.js -- https://nodejs.org/download/
Download and install. After installation be sure you have **npm** in the windows command path.
To check it please follow these steps:
1. From the desktop, right-click My Computer and click Properties.
2. In the System Properties window, click on the Advanced tab.
3. In the Advanced section, click the Environment Variables button.
4. Finally, in the Environment Variables window, highlight the Path variable in the Systems Variable section and click the Edit button. Add or modify the path lines with the paths you wish the computer to access. Each different directory is separated with a semicolon as shown below.
``` 
C:\Users\user\AppData\Roaming\npm;C:\Program Files\nodejs
```
Check if it work with this line in cmd
``` 
npm -v
```

####csslint -- https://github.com/CSSLint/csslint
open cmd and type 
``` 
npm install -g csslint
```
Check if it works with this line in cmd
``` 
csslint -v
```

####csscomb -- https://github.com/csscomb/csscomb.js
open cmd and type 
``` 
npm install csscomb -g
```
Check if it works with this line in cmd
``` 
csscomb -v
```

####diffutils -- http://gnuwin32.sourceforge.net/packages/diffutils.htm
If you have **Git** istalled on your computer you can skip this step

#Configuration
####Make SVN Checkout
``` 
https://github.com/nfxpnk/css_hooks
```
Now you have the lastest version of the **pre-commit-hook**

####Copy these files to any folder (e.g. c:/apps/hook/)
``` 
config.php.example
pre-commit-hook.php
config-csscomb.json
```
####Rename config.php.example to config.php and make changes accordingly
``` php
<?php
$cssLintCliPath = 'csslint'; #no need to change this
$cssCombCliPath = 'csscomb'; #no need to change this
$cssCombConfigFilePath = 'c:/apps/hook/config-csscomb.json'; #this is config for csscomb
$diffCliPath = 'C:/Users/user/AppData/Local/Programs/Git/bin/diff.exe'; #path to diff.exe
$tempDirectory = 'c:/apps/hook/temp'; #path to *.patch file
$patchFilePath = $tempDirectory . '/' . 'pre-commit.patch'; #name of the generated patch file
```

####Open TortoiseSVN settings and setup [Client Side Hook Script](http://tortoisesvn.net/docs/release/TortoiseSVN_en/tsvn-dug-settings.html#tsvn-dug-settings-hooks)
**Hook type:** pre-commit-hook

Working copy path:
```
c:/svn-repository
```

Command line to execute:
```
c:/apps/php/php.exe c:/apps/hook/pre-commit-hook.php
```

Mark checkbox
```
Wait for script to finish
```

Unmark checkbox
```
Hide the script while running
```





