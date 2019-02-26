# Tickets and ID cards printing Peace Palace Library


## Description

Tools for printing tickets from pulllists and ID Cards. Uses OCLC API's.

This repository consists of website files and scripts that need to be scheduled, e.g. in cron of with nohup.

Website:

| file |  description |
|---|---|
| index.php | Simple startpage, choice between tickets view and printing of patron library cards. |
| tickets.php | List of tickets yet to be printed and list of tickets that are already printed. |
| idcard.php | Webpage with form for getting patron data using a barcode and button for printin a library card. |

Scripts for scheduling:

| file |  description | cron |
|---|---|---|
| archive_tickets.php | Scripts for archiving | in cron: every ...|
| eternal_tickets.php | Script for checking the pulllist, generating tickets in HTML and PDF filesv| in nohup, prints every 5 minutes |
| nohup_eternal_tickets_command.txt | example of nohup command | |

The script *test_pulllist2tickets.php* can be opened with a browser and should be used for testing. It generates tickets, but doe not print the tickets.

## Dependencies

* OCLC's authorization: see subdirectory OCLC
* Twig, is installed using composer 
* mPDF, is installed using composer


## OCLC keys

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

## ID cards, class Patron
The webpage *idcard.php* uses *patron/patron.php* in which a class `Patron` is defined. This class takes care of the communication 
with the IDM API of WMS and the generation of tickets. 

The webpage also takes care of printing patron cards, using a cards printer via the network. In *patron* two
Twig temlate files are stored:

| files | description |
|---|---| 
| id_template.html | used for dispaying user data in the webpage|
| idcard_template.html | used for generating the id card itself, after which it is converted to PDF|

The cards are stored in *patron/idcards*.

## Tickets (loopbonnen)

The script:
```
eternal_tickets.php
```
generates tickets and prints them in an "eternal loop". The file:

```
nohup_eternal_tickets_command.txt
```
consists of an example command to start *eternal_tickets.php* in nohup.

For inspecting what has to be printed and what already is printed use the webpage *tickets.php*

For testing purposes open *test_pulllist2tickets.php* in a browser.

## Class pulllist.php

In *pulllist/pulllist.php* the class `Pulllist` is defined. This class takes care of the communication 
with the circulation API of WMS and the generation of tickets. It also uses tha class `Patron` in order to
get the barcode of the patron.

In *pulllist* three Twig temlate files are stored:

| files | description |
|---|---|
| ticket_template.html | is used for the generation of HTML tickets |
| tablerow_template.html | is used in the webpage *tickets.php* for generating rows in the first table (tickets yet to be printed |
| tablerow_print_template.html | idem for the second table (tickets that are already printed), these rows have a print button |

The print button in the last template uses *pulllist/prFile.php* via a jQuery's AJAX call in order to print
an individual ticket. 

## Directory *pulllist/tickets*

The directory *pulllist/tickets* is used to store pulllists and tickets.

| files | description |
|---|---|
| archive/ | archives of tickets|
| printed/ | tickets are moved here when they are printed|
| temp_printer/ | generated tickets in PDF that must be printed|
| tobeprinted/ | generated tickets in HTML, used in the webpage *tickets.php* |
| actual_pulllist.json  | last pulllist from WMS in JSON format|
| previous_pulllist.json  | previous pulllist from WMS in JSON format|

Tickets are moved from *tobeprinted* to *printed* and deleted from *temp_printer* when they are printed.

Tickets are moved from *printed* and archived in *archive* after a few days.




