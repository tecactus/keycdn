<?php

// global CDN link helper function
if (! function_exists('cdn')) {
    function cdn($asset) {
        
        if (app()->isLocal()) {
            return asset($asset);
        }

        // Verify if KeyCDN URLs are present in the config file
        if( !Config::get('keycdn.cdn') )
            return asset( $asset );

        // Get file name incl extension and CDN URLs
        $cdns = Config::get('keycdn.cdn');
        $assetName = basename( $asset );

        // Remove query string
        $assetName = explode("?", $assetName);
        $assetName = $assetName[0];

        // Select the CDN URL based on the extension
        foreach( $cdns as $cdn => $types ) {
            if( preg_match('/^.*\.(' . $types . ')$/i', $assetName) )
                return cdnPath($cdn, $asset);
        }

        // In case of no match use the last in the array
        end($cdns);
        return "//" . rtrim(key( $cdns ), "/") . "/" . ltrim( $asset, "/");

    }
}