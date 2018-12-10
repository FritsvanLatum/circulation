# Tickets and ID cards printing Peace Palace Library


## Description

Tools for printing tickets from pulllists and ID Cards. Uses OCLC API's.

This applications needs two files with OCLC's wskeys, secrets and ppid's:

*pulllist/key.php*

```
$config = [];
$config['wskey'] = {your key};
$config['secret'] = {your secret};
$config['ppid'] = {ppid of a patron with adequate roles from your institution};
```

*patron/key_idm.php*

```
$config_idm = [];
$config_idm['wskey'] = {your key};
$config_idm['secret'] = {your secret};
$config_idm['ppid'] = {ppid of a patron with adequate roles from your institution};
```

## ID cards


## Tickets (loopbonnen)

Use:
```
php pulllist2tickets.php
```
on the commandline in order to generate tickets from the pulllist in WMS.

Use:
```
php html2pdf.php
```
on the commandline in order to send tickets to a printer.

Schedule *pulllist2tickets.php* and (after some minutes) *html2pdf.php* in order to get tickets from pullists. E.g. each half hour.

For inspecting what has to be printed and what already is printed use the webpage *tickets.php*

For testing purposes open *test_pulllist2tickets.php* and *test_html2pdf.php* in a browser.

## Uses

* OCLC's authorization
* Twig
* mPDF

