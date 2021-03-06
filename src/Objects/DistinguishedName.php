<?php

namespace Adldap\Objects;

use Adldap\Classes\Utilities;
use Adldap\Schemas\ActiveDirectory;

class DistinguishedName
{
    /**
     * The optional base dn string.
     *
     * @var string
     */
    protected $base = '';

    /**
     * Stores the domain components in the DN.
     *
     * @var array
     */
    protected $domainComponents = [];

    /**
     * Stores the common names in the DN.
     *
     * @var array
     */
    protected $commonNames = [];

    /**
     * Stores the organizational units in the DN.
     *
     * @var array
     */
    protected $organizationUnits = [];

    /**
     * Constructor.
     *
     * @param null $baseDn
     */
    public function __construct($baseDn = null)
    {
        $this->setBase($baseDn);
    }

    /**
     * Returns the complete distinguished name.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->get();
    }

    /**
     * Returns the complete distinguished name.
     *
     * @return string
     */
    public function get()
    {
        return $this->assemble();
    }

    /**
     * Adds a DC to the DC array.
     *
     * @param string $dc
     *
     * @return $this
     */
    public function addDc($dc)
    {
        $this->domainComponents[] = $dc;

        return $this;
    }

    /**
     * Removes a DC from the DC array.
     *
     * @param string $dc
     *
     * @return $this
     */
    public function removeDc($dc)
    {
        $this->domainComponents = array_diff($this->domainComponents, [$dc]);

        return $this;
    }

    /**
     * Adds a CN to the CN array.
     *
     * @param string $cn
     *
     * @return $this
     */
    public function addCn($cn)
    {
        $this->commonNames[] = $cn;

        return $this;
    }

    /**
     * Removes a CN from the CN array.
     *
     * @param string $cn
     *
     * @return $this
     */
    public function removeCn($cn)
    {
        $this->commonNames = array_diff($this->commonNames, [$cn]);

        return $this;
    }

    /**
     * Adds an OU to the OU array.
     *
     * @param string $ou
     *
     * @return $this
     */
    public function addOu($ou)
    {
        $this->organizationUnits[] = $ou;

        return $this;
    }

    /**
     * Removes an OU from the OU array.
     *
     * @param string $ou
     *
     * @return $this
     */
    public function removeOu($ou)
    {
        $this->organizationUnits = array_diff($this->organizationUnits, [$ou]);

        return $this;
    }

    /**
     * Sets the base DB string.
     *
     * @param string $base
     *
     * @return $this
     */
    public function setBase($base)
    {
        $this->base = $base;

        return $this;
    }

    /**
     * Returns the base DN string.
     *
     * @return string
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * Assembles all of the RDNs and returns the result.
     *
     * @return string
     */
    private function assemble()
    {
        $cns = $this->assembleRdns(ActiveDirectory::COMMON_NAME, $this->commonNames);

        $ous = $this->assembleRdns(ActiveDirectory::ORGANIZATIONAL_UNIT_SHORT, $this->organizationUnits);

        $dcs = $this->assembleRdns(ActiveDirectory::DOMAIN_COMPONENT, $this->domainComponents);

        return implode(',', array_filter([$cns, $ous, $dcs, $this->getBase()]));
    }

    /**
     * Assembles an RDN with the specified attribute and value.
     *
     * @param string $attribute
     * @param array  $values
     *
     * @return null|string
     */
    private function assembleRdns($attribute, array $values = [])
    {
        if(count($values) > 0) {
            $values = array_reverse($values);

            $values = array_map(function($value) use ($attribute) {
                return $attribute.'='.Utilities::escape($value, '', 2);
            }, $values);

            return implode(',', $values);
        }

        return null;
    }
}
