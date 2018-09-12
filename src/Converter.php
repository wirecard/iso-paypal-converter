<?php

namespace Wirecard\IsoToPayPal;
use Wirecard\IsoToPayPal\Exception\CountryNotFoundException;
use Wirecard\IsoToPayPal\Exception\StateNotFoundException;

/**
 * Class Converter
 *
 * This class converts ISO-3166 alpha 2 country/state codes to their respective PayPal API counterparts.
 * This is necessary, because PayPal switches between following the standard and using its own identifiers.
 *
 * @package Wirecard\IsoToPayPal
 */
class Converter
{
    const ISO3166 = "/[a-zA-Z0-9]{2}\-[a-zA-Z0-9]{1,3}/";

    private $mapping;

    /**
     * Converter constructor.
     *
     * Loads in the mappings file from the filesystem.
     */
    public function __construct()
    {
        $this->mapping = file_get_contents(__DIR__ . "/../assets/mapping.json");
        $this->mapping = json_decode($this->mapping, true);
    }

    /**
     * @param string $country An ISO-3166 country identifier. Can contain a state identifier as well.
     * @param string $state (optional) An ISO 3166-2 state identifier.
     * @return string The PayPal identifier for the given state.
     * @throws CountryNotFoundException
     * @throws StateNotFoundException
     */
    public function convert($country, $state = null)
    {
        if (!$state && !preg_match(self::ISO3166, $country)) {
            throw new \InvalidArgumentException("Country must be a fully formed ISO 3166-2 code if state parameter is omitted.");
        }

        if (!$state) {
            $isoParts = explode('-', $country);

            $country = $isoParts[0];
            $state = $isoParts[1];
        }

        if (!array_key_exists($country, $this->mapping)) {
            throw new CountryNotFoundException("Country was not found in the conversion table.");
        }

        if (!array_key_exists($state, $this->mapping[$country])) {
            throw new StateNotFoundException("State was not found in the conversion table for country '$country'");
        }

        return $this->mapping[$country][$state];
    }
}