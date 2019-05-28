<?php

// Error management
ini_set('display_errors', true);
error_reporting(E_ALL);

// Autoload
require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';

// This is the tiles URL of the panorama found on vrto.me's home page
$panoramaTilesUrl = 'https://vrto.me/_office/files/2/projects/blackhaus_-_panama_house/panos/panama_gear_high_res_gear01jpg.tiles';

/*========    EXAMPLE 1 : keep folder structure    ========*/
$targetFolder = dirname(__FILE__).DIRECTORY_SEPARATOR.'deep';
@mkdir($targetFolder);
try {
    $downloader = new \W6\VrtoDownloader\VrtoDownloader( $panoramaTilesUrl, $targetFolder );
    $downloader->execute();
} catch ( \W6\VrtoDownloader\VrtoDownloaderException $e ) {
    echo 'Error : ' . $e->getMessage();
    exit(1);
}
echo 'Example 1 downloaded.'.PHP_EOL;

/*========    EXAMPLE 2 : All images in the same folder    ========*/
$targetFolder = dirname(__FILE__).DIRECTORY_SEPARATOR.'flat';
@mkdir($targetFolder);
try {
    $options = array(
        'keepFolderStructure' => false
    );
    $downloader = new \W6\VrtoDownloader\VrtoDownloader( $panoramaTilesUrl, $targetFolder, $options );
    $downloader->execute();
} catch ( \W6\VrtoDownloader\VrtoDownloaderException $e ) {
    echo 'Error : ' . $e->getMessage();
    exit(1);
}
echo 'Example 2 downloaded.'.PHP_EOL;

exit(0);