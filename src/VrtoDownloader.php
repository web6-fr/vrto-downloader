<?php
/**
 * VRto.me Downloader
 * 
 * This class downloads images used to generate panoramas hosted on vrto.me to use the offilne
 *
 * @package   W6\VrtoDownloader
 * @author    WEB6 <contact@web6.fr>
 * @copyright 2019 WEB6
 * @license   https://www.gnu.org/licenses/gpl-3.0.txt  GNU GPLv3
 * @link      https://github.com/web6-fr/vrto-downloader/
 */

namespace W6\VrtoDownloader;

class VrtoDownloader {

    /**
     * URL of the folder containing the tiles (without trailing slash)
     * 
     * This URL is easy to find in the pages ressources. It's a folder ending in ".tiles"
     * For example, the tiles folder URL for the panorama actualy hosted on vrto.me's home page is :
     * https://vrto.me/_office/files/2/projects/blackhaus_-_panama_house/panos/panama_gear_high_res_gear01jpg.tiles
     */
    public $tilesFolderUrl;

    /**
     * Path to the folder where the images will be stored
     */
    public $targetDirectory;

    /**
     * Current instance options
     * @see $defaults
     */
    public $options;

    /**
     * Default options
     * - keepFolderStructure : If this option is true the folder structure will be the same as on vrto.me otherwose all the images will be downloaded in the target folder
     */
    protected $defaults = array(
        'keepFolderStructure' => true
    );

    /**
     * Create a downloader instance
     * 
     * @param string $tilesFolderUrl The URL of the tiles folder on vrto.me (see corresponding property)
     * @param string $targetDirectory Local directory where the images will be stored (see corresponding property)
     * @param string $options Options of the downloader instance (see $defaults property)
     */
    public function __construct( string $tilesFolderUrl, string $targetDirectory, array $options = array() ) {
        $this->tilesFolderUrl = rtrim($tilesFolderUrl, '/');
        $targetDirectory = rtrim($targetDirectory, '/\\');
        if( !file_exists( $targetDirectory ) ){
            throw new VrtoDownloaderException( "Target folder `$targetDirectory` does not exist." );
        }
        if( !is_dir( $targetDirectory ) ){
            throw new VrtoDownloaderException( "Target folder `$targetDirectory` is not a folder." );
        }
        if( !is_writable( $targetDirectory ) ){
            throw new VrtoDownloaderException( "Target folder `$targetDirectory` is not writable." );
        }
        $this->targetDirectory = $targetDirectory;
        $this->options = array_merge($this->defaults, $options);
    }

    /**
     * Download the images
     */
    public function execute(){

        $levels = [];
        $levels[] = ['b', 'd', 'f', 'l', 'r', 'u'];
        $levels[] = ['l1', 'l2'];

        $deepMode = $this->options['keepFolderStructure'];

        $ds = \DIRECTORY_SEPARATOR;
        
        foreach($levels[0] as $l0){
            foreach($levels[1] as $k => $l1){
                $range = range(1, $k ? 4 : 2);
                foreach($range as $l2){
                    foreach($range as $l3){
                        $folder = join($ds, array($l0, $l1, $l2));
                        // Create deep folder
                        if($deepMode){
                            $folderPath = $this->targetDirectory.$ds.$folder;
                            if(!is_dir($folderPath)){
                                if(!mkdir($folderPath, 0777, true)){
                                    throw new VrtoDownloaderException( "Unable to create folder `$folderPath`." );
                                }
                            }
                        }
                        // Download file
                        $filename = join('_', array($l1, $l0, $l2, $l3)).'.jpg';
                        $url = "{$this->tilesFolderUrl}/$folder/$filename?t=2019-05-28_072&s=generated";
                        try {
                            $content = file_get_contents($url);
                            if ($content === false) {
                                throw new VrtoDownloaderException( "Unable to download file `$url`." );
                            }
                        } catch (Exception $e) {
                            throw new VrtoDownloaderException( "Unable to download file `$url`." );
                        }
                        // Create file
                        $filePath = $deepMode ? $folderPath.$ds.$filename : $this->targetDirectory.$ds.$filename;
                        if(!file_put_contents($filePath, $content)){
                            throw new VrtoDownloaderException( "Unable to create file `$filePath`." );
                        }
                    }
                }
            }
        }
    }

 }
