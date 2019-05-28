# VRto.me panorama images downloader

This tools downloads panorama images hosted on VRto.me.
We use it to create offline panoramas using [krpano](https://krpano.com/).

## Install

There are three ways to install this tool.

### Composer

You can add this library as a depedency to a project already using composer.

```bash
$ composer require web6/vrto-downloader
```

### GIT

You can download this library as a standalone tool and clone it anywhere. This way you will be alble to have all the project history and will be able to make future updates easily.

```bash
$ git clone https://github.com/web6-fr/vrto-downloader.git
$ cd vrto-downloader
$ composer install
```

### ZIP ARCHICE

You can [download the zip archive](https://github.com/web6-fr/php-singleton-trait/archive/master.zip) of this library and extract it anywhere on your computer. than run :

```bash
$ cd /path/to/vrto-downloader
$ composer install
```

## Usage

### Find the VRto.me panoeama tiles URL

To do this I usualy go to the sources panel in the development tools inspector and find an image used by the panorama.

![How to find the tiles URL](https://octodex.github.com/images/yaktocat.png)

Than I copy the part of the URL ending with `.tiles`. For example in [this screenshot](https://raw.githubusercontent.com/web6-fr/vrto-downloader/master/demo/screenshot.png) the URL is :

```
https://vrto.me/_office/files/2/projects/blackhaus_-_panama_house/panos/panama_gear_high_res_gear01jpg.tiles/d/l1/1/l1_d_1_1.jpg?t=2016-12-12_2241&s=generated
```

And the retained tiles URL is:

```
https://vrto.me/_office/files/2/projects/blackhaus_-_panama_house/panos/panama_gear_high_res_gear01jpg.tiles
```

### Create a php script

Create a PHP script with your won configuration :

```php
include_once('path/to/vrto-downloader/vendor/autoload.php');

$panoramaTilesUrl = 'https://vrto.me/_office/files/2/projects/blackhaus_-_panama_house/panos/panama_gear_high_res_gear01jpg.tiles';

$targetFolder = '/path/to/target/folder';

$options = array(
    'keepFolderStructure' => true
);

try {
    $downloader = new \W6\VrtoDownloader\VrtoDownloader( $panoramaTilesUrl, $targetFolder );
    $downloader->execute();
} catch ( \W6\VrtoDownloader\VrtoDownloaderException $e ) {
    die( 'Error : ' . $e->getMessage() );
}
```

