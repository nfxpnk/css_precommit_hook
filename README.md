
# svn-css-pre-commit-hook

>CSS pre-commit hook for TortoiseSVN

# Getting started

## TortoiseSVN -- http://tortoisesvn.net/downloads.html

## PHP5 -- http://windows.php.net/download

Install the latest release for windows **php-5.6.7-Win32-VC11-x86.zip**

Extract it to any writable folder, for an example

```text
c:/apps/php
```

Check if it works as expected with command

```text
c:/apps/php/php.exe -v
```

### node.js -- https://nodejs.org/download/

Download and install. After installation be sure you have **npm** in the windows command path.
To check it please follow these steps:

- From the desktop, right-click My Computer and click Properties.

- In the System Properties window, click on the Advanced tab.

- In the Advanced section, click the Environment Variables button.

- Finally, in the Environment Variables window, highlight the Path variable in the Systems Variable section and click the Edit button. Add or modify the path lines with the paths you wish the computer to access. Each different directory is separated with a semicolon as shown below.

```text
C:\Users\user\AppData\Roaming\npm;C:\Program Files\nodejs
```

Check if it work with this line in cmd

```text
npm -v
```

#### csslint -- https://github.com/CSSLint/csslint

open cmd and type

```text
npm install -g csslint
```

Check if it works with this line in cmd

```text
csslint -v
```

#### csscomb -- `https://github.com/csscomb/csscomb.js`

open cmd and type

```text
npm install csscomb -g
```

Check if it works with this line in cmd

```text
csscomb -v
```

#### git -- `http://git-scm.com/download/win`

## Configuration

### Make SVN Checkout

```text
https://github.com/nfxpnk/css_hooks
```

Now you have the lastest version of the **pre-commit-hook**

#### Copy these files to any folder (e.g. c:/apps/hook/)

```text
config.php.example
pre-commit-hook.php
config-csscomb.json
```

#### Rename config.php.example to config.php and make changes accordingly

```php
<?php
$cssLintCliPath = 'csslint'; #no need to change this
$cssCombCliPath = 'csscomb'; #no need to change this
$cssCombConfigFilePath = 'c:/apps/hook/config-csscomb.json'; #this is config for csscomb
$diffCliPath = 'C:/Users/user/AppData/Local/Programs/Git/bin/diff.exe'; #path to diff.exe
$tempDirectory = 'c:/apps/hook/temp'; #path to *.patch file
$patchFilePath = $tempDirectory . '/' . 'pre-commit.patch'; #name of the generated patch file
```

#### Open TortoiseSVN settings and setup [Client Side Hook Script](http://tortoisesvn.net/docs/release/TortoiseSVN_en/tsvn-dug-settings.html#tsvn-dug-settings-hooks)

Hook type:

```text
pre-commit-hook
```

Working copy path:

```text
c:/svn-repository
```

Command line to execute:

```text
c:/apps/php/php.exe c:/apps/hook/pre-commit-hook.php
```

Mark checkbox

```text
Wait for script to finish
```

Unmark checkbox

```text
Hide the script while running
```

# Usage

This pre-commit hook process css files only.

The message textarea in the commit dialogue window can't be empty also message should began with JIRA ticket name, otherwise the hook will not let you to perform a commit.

Try to commit a css file with obvious syntax errors. 

```css
.test {
    col1or: #c33;
}
```

In that case script will not let you to perform a commit and show a message which line of css file has an error.

```text
The hook script returned an error:
csslint: There are 1 problems in c:/svn-repository/css/main.css.

main.css
1: error at line 1174, col 2
Unknown property 'col1or'.
    col1or: #c33;
```

Try to commit a css file with formatting which doesn't correspond to styleguide (defined within **config-csscomb.json**).

```css
.test {color: #c33;}
```

In that case script will not let you to perform a commit and show a message:

```text
The hook script returned an error:
File c:/svn-repository/css/main.css is not perfect.
See: c:/apps/hook/temp/pre-commit.patch for details.
```

**pre-commit.patch** contents

```diff
-.nfx {color: #c33;}
\ No newline at end of file
+.nfx {
+    color: #c33;
+}
```

You can fix the formatting by yourself or just apply the pre-commit.patch to main.css

## To apply patch to css file follow these steps:

-Copy pre-commit.patch to c:/svn-repository/css/

-Open Git Bash ant type following

```php
cd /c/svn-repository/css/
patch main.css pre-commit.patch
```

-Delete patch file from c:/svn-repository/css/

-Perform a commit.
