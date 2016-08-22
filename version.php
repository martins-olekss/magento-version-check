<?php
/**
 * Checks Magento edition and version
 * Check based on content of /js/varien/product.js file
 *
 * @param $url
 * @return mixed
 */
function getMagentoVerstion($url) {
    $site_url = 'http://'.$url.'/js/varien/product.js';

    //TODO: Case when file does not exist, is not handled
    $homepage = file_get_contents($site_url);

    $version['url'] = $url;
    $version['complete_path'] = $site_url;

    //Get edition
    preg_match('/@license.*/', $homepage, $match);
    if (isset($match[0])) {
        if (strpos($match[0], 'enterprise') !== false) {
            $version['edition'] = "EDITION_ENTERPRISE";
        } elseif (strpos($match[0], 'commercial') !== false) {
            $version['edition'] = "EDITION_PROFESSIONAL";
        }
        $version['edition'] = "EDITION_COMMUNITY";
    }

    preg_match('/@copyright.*/', $homepage, $match);
    if (isset($match[0])
        && preg_match('/[0-9-]{4,}/', $match[0], $match)
        && isset($match[0])
    )
    {
        switch ($match[0]) {
            case '2006-2015':
            case '2006-2014':
            case '2014':
                $version['version'] = '1.14 / 1.9';
                break;
            case '2013':
                $version['version'] = '1.13 / 1.8';
                break;
            case '2012':
                $version['version'] = '1.12 / 1.7';
                break;
            case '2011':
                $version['version'] = '1.11 / 1.6';
                break;
            case '2010':
                $version['version'] = '1.9 - 1.10 / 1.4 - 1.5';
                break;
            default:
                $version['version'] = "unknown";
                break;
        }
    }

    return $version;
}

/**
 * Outputs content of $version array
 *
 * @param $version
 */
function showResult($version) {
    echo "\nResult: \n";
    foreach($version as $k => $i) {
        echo $k . ": " . $i . PHP_EOL;
    }
}

/**
 * If parameter not present when launching script prompt will ask for url
 */
if (isset($argv[1]) && $argv[1] != "" ) {
    $version = getMagentoVerstion($argv[1]);
    showResult($version);
} else {
    $url = strtolower(readline("Enter URL of the site to check: \n"));
    $version = getMagentoVerstion($url);
    showResult($version);
}
