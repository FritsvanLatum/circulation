# Ticket printing from pulllists


## Description

This applications needs a file *key.php* with the following lines:
```
$config = [];

//wskey en secret van PPL-APIkey
$config['wskey'] = {your key};
$config['secret'] = {your secret};

//user loopbonnenprinter in WMS
$config['ppid'] = {ppid of your institution};
```

## Usage

Schedule pulllist2tickets.php in order to get tickets from pullists. E.g. each half hour.

## Uses

* OCLC's authorization
* Twig
* mPDF

