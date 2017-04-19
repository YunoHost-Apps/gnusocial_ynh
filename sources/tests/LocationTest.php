<?php

if (isset($_SERVER) && array_key_exists('REQUEST_METHOD', $_SERVER)) {
    print "This script must be run from the command line\n";
    exit();
}

define('INSTALLDIR', realpath(dirname(__FILE__) . '/..'));
define('GNUSOCIAL', true);
define('STATUSNET', true);  // compatibility

require_once INSTALLDIR . '/lib/common.php';

// Make sure this is loaded
// XXX: how to test other plugins...?

addPlugin('Geonames');

class LocationTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider locationNames
     */

    public function testLocationFromName($name, $language, $location)
    {
        $result = Location::fromName($name, $language);
        $this->assertEquals($result, $location);
    }

    static public function locationNames()
    {
        return array(array('Montreal', 'en', null),
                     array('San Francisco, CA', 'en', null),
                     array('Paris, France', 'en', null),
                     array('Paris, Texas', 'en', null));
    }

    /**
     * @dataProvider locationIds
     */

    public function testLocationFromId($id, $ns, $language, $location)
    {
        $result = Location::fromId($id, $ns, $language);
        $this->assertEquals($result, $location);
    }

    static public function locationIds()
    {
        return array(array(6077243, GeonamesPlugin::LOCATION_NS, 'en', null),
                     array(5391959, GeonamesPlugin::LOCATION_NS, 'en', null));
    }

    /**
     * @dataProvider locationLatLons
     */

    public function testLocationFromLatLon($lat, $lon, $language, $location)
    {
        $result = Location::fromLatLon($lat, $lon, $language);
        $this->assertEquals($location, $result->location_id);
    }

    static public function locationLatLons()
    {
        return array(array(37.77493, -122.41942, 'en', null),
                     array(45.509, -73.588, 'en', null));
    }

    /**
     * @dataProvider nameOfLocation
     */

    public function testLocationGetName($location, $language, $name)
    {
        $result = empty($location)?null:$location->getName($language);
        $this->assertEquals($name, $result);
    }

    static public function nameOfLocation()
    {
        $loc = Location::fromName('Montreal', 'en');
        return array(array($loc, 'en', null), //'Montreal'),
                     array($loc, 'fr', null));//'Montréal'));
    }
}

