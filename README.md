### 5andbox

A bare bones Wordpress starter kit. Includes useful plugins, functions and a Gulp workflow to compile Sass and JavaScript.

### Installation

1. Create a new directory for your website:

`cd ~/projects/my-new-website`

1. Download a fresh Wordpress install:

With WP-CLI http://wp-cli.org/:

`wp core download`

Or with a terminal:

`wget http://wordpress.org/latest.tar.gz`

`tar xfz latest.tar.gz`

`mv wordpress/* ./`

`rmdir ./wordpress/`

`rm -f latest.tar.gz`

1. Rename wp-config-sample.php to wp-config.php and configure it with your database information.

1. Remove the wp-content directory (this repository will replace it):

`rm -rf wp-content/`

1. Clone this repository:

`git clone git@github.com:dylanfisher/5andbox.git`

1. Rename the cloned repo to wp-content:

`mv 5andbox/ wp-content/`

1. Rename the 5andbox theme to your new website's name:

`cd wp-content/themes/`

`mv 5andbox/ my-new-website/`

1. Edit the `scss/style.scss` file and configure the Theme Name, URI, author and description.

1. Delete the .git directory and initialize a new git repo in the theme directory (unless you want to track plugins):

`cd ~/projects/my-new-website/wp-content/`

`rm -rf .git/`

`mv .gitignore themes/my-new-website/`

`cd themes/my-new-website`

`git init`

`git add -A`

`git commit -m "first commit"`

### Developing

1. Install Node, npm (Node Package Manager) and nvm (Node Version Manager)

1. Install npm development dependencies

`cd ~/projects/my-new-website/wp-content/themes/my-new-website`

`npm install`

1. Run gulp

`gulp`

Gulp automatically watches files and will livereload connected browsers.

### Have fun!

```
                          . .7?..
                            :Z$.I
                          .,:O$.I....       .,.   ... .
                          .~=N+D I?...  . . ,+.~ . ..  .
           . . . . .  . ...+7$.?D8~,. . ~~+.,7=~= := .+   .
            .  .I~ .    .+.~+I~:IDOZ   .,=?+II7=7+..N :. ,
              ,+~+?+, :.,,,Z7NO$8N7O: ,$ :?8?I$?Z?Z7,I? ~?.
            .  .I7I+++:?.~Z+~MO78$$+?..O=??8ZZZZ,M+.:$??I~.
              .~?Z=MI++,+$+ZD.I?N.D?8~.87$I$DD+OI~+I77?7O:~
            .   ,:,+II$+7I+$~=D8.Z,88O.D$Z~I8$M=O8M$D?,N,,. ..    .  ..
            .. .~?~=778?ZI?+7N.I, MDMM:M$7=N:M,I$+.8D7M7     ...   ..
           .  ..,.+,Z+$7O?I7+8:+I8OM?M?MZ?I~M=ZDMMMI7$,. ... .,~=+++=~,
..  . . ..   ..  .?7+$:8$MO+ZI.DN$MMZ?$==,:OI7DO+.I=? 7NM8=:?OZ?+, .. ... .
 .   ,==?Z+  .. ,,.:II7INI???ND+NZNON8MII=D~$.7M?+MMDZOZI7?==?...+?. . ...
    ..:=?ZDOIOI..~+: ZIZ8Z?8$DN$O$DM87?7,8+Z$DM8MMMM7I8O7~,,,~~ ~.. .
     .  .. ,+Z7DNMI .:I=$ZDO?ON:8$MMO?? M=$OMMMMNNDNO,:,Z:~:,, ........
      .  ?:.$?77ZN$$D7.Z$7$NZOM7.M8M77ON78MMNMMMMD7=. ?I .7.~..
     ... :..~$$N?MM$N88D.ZZ$?OI:?OM~MOM?DMNMMODM=8Z..Z.. 7 .,..
      .. .,I7=M7MON=OMMOMMM$N7O~7D=DD$MNDDN88MDZ+~7I.= .......
        . .?.I$$N$??,INM8NO8DN8Z8D7OMM87:?+$NDMDNNOO+OO$$NMMN+...
        . ..,,~=:7:~:O$.8N8NOND$OZZMM,$NONNNNO$7DDZ8OOZ$7I$?DD$O8 .
        ...  ..=?+OMMMDND$DMMMMMMMM7NZZMI$$DO+7Z+7~$$O.8+7?$:+~7O77. .
          ..:DM$O77Z:7.,DO?I?$=ZDMMMMMON?NINO.7$N$$=?,IO..~:+~.=:=I?
        .+?O:I7ID~??.+.Z8Z++I=N=.MMO.NM$NMM8MI+.$$I7?.+O O.8  , :. 7
.   . II.7.?+~?7Z.Z.$ $O?,,7N8Z$ZNM8$$?7~$M88NONZO$+.+.= ~Z ~.  ...=,.
  ..?+ .=.+=.~=,.= : 77==7ZNO8O$7NMM=$$7==:.D??~,=$I7,.= I 8 . .   ~..
. ,. ..+ :,  .~= ~.~ ~ .DMNI?7NODDMM7$Z$7OI$?+7OZZ8$OI?+~:?..
  . ...    . ...:    .7DI8DID8.:OMMM~~.ZO7?7O+:7~I,IZ7~. ,.    .
               .    .7N8M+M=78~??MMM?+.7I~?~Z$7M+:O~$8OI~,,
               .    $MZZMNDM+~Z77NMM.I:MZ$OZ$,=DZ+~,~??$$~~.
               .. ,ONO7?++DM7~Z=.NMM~. M.:.O787N$$8Z~M$:?~ .
               . .D$Z+$:=ZZ7$Z++:8MM:  ~:77: ~=ODZDZZZZ77::..  .
                 NM7?O?O+=:77I,. MMM. , : +.=.?.M7Z87=$OZ.+.
           .  ..D878=~++7:==+,. .MMM.     . :.+.~,,NO$I+ZI.,.  .
           . ..OZ$I8:+N~=~..  .  MMM.    . ....~~=.O$=8~?Z... .
             .+$D8+I?OM,~:    .  MMM     .   . ..+.8Z$ZD:?=.   .
           ..I$+=?:~+==.,.    .  DMM=            ..Z.O?+$,
           .~?:. ,..:,.         .ZMM,              :++~~,7
           .=:,, .. ..          .$MM               .=.:...
           ...   .    .         .ZMM.            . .,., .
                                .OMM.                 ..
                                .OMD                .
                               . 8M8                 .
                              . .8M8.
                               ..8M8.
                                 DMO.
                                 NMO.
                              .. MMO.
                                 NM8.
                              .. MMD
                               ..MMD.
                               . MMN
                               . MMN
                                 MMM.
                                 MMM.
                              .. MMM
                                 MMM
                               ..MMM
                               ..MMM.
                                .MMM.
                                .MMM
                               ..MMM.
                              ...MMM
                               .,MMM.
                               .:MMM
                               .:MMM.
                              ..~MMM.
                               .=MMM.
                               .+MMM.
                               .?MMM,
                               .IMMM,
                               .7MMM:
                               .$MMM~
                               .ZMMM~
                               .ZMMM=
                               .8MMM+
                              . 8MMM?
                              . DMMMI
                                NMMM7
                               .NMMM7
                              ..MMMM$
                              . NMMMZ
                                NMMMO
                                NMMM8
                                DMMM8
                               .8MMMD
                               .8MMMN.
                               .OMMMN.
                               .ZMMMM.
                               .ZMMMM.
```
