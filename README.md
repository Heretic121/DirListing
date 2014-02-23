DirListing
==========

A PHP class for listing directories, with different output options.


==USAGE==

Initiating the class:

$DR = new dirlisting();
By default this will do a scan on the current directory, assigned by "getcwd()".
You can stop it doing so by doing this;
$DR = new dirlisting(false);

Scanning a directory:

$DR->scanfld();
This will scan the current directory, assigned by "getcwd()".

$DR->scanfld('/random/directory');
This will scan the given directory.

$DR->scanfld('/random/directory', true);
This will do a recursive scan on the given directory.

$DR->scanfld('/random/directory', true, array('.exe', '.bin'));
This will do a recursive scan, ignoring files with the extenstions '.exe' and '.bin', on the given directory.

Outputting a scan:

You can buffer outputs from the CLI and HTML functions by first doing;
$DR->chgsetting('return',true);
This will "return" the output, for either CLI or HTML, to the function that called it. 

$DR->clioutput();
This will output the result of the last scan in an easy to read manner for command lines, text documents, etc.

$DR->clioutput('/random/directory/within');
Same as above, but will start the output from the folder given.

$DR->htmloutput();
This will output the result of the last scan in a pre-formatted block of HTML.

$DR->htmloutput('/random/directory/within');
Same as above, but will start the output from the folder given.

$DR->arrayoutput();
This will return the result of the scan in an array.

$DR->arrayoutput('/random/directory/within');
Same as above, but will start the output from the folder given.
