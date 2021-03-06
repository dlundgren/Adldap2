<?php

namespace Adldap\Objects\Traits;

use Adldap\Schemas\ActiveDirectory;

trait HasDescriptionTrait
{
    /**
     * Returns the entry's description.
     *
     * https://msdn.microsoft.com/en-us/library/ms675492(v=vs.85).aspx
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getAttribute(ActiveDirectory::DESCRIPTION, 0);
    }

    /**
     * Sets the entry's description.
     *
     * @param string $description
     *
     * @return $this
     */
    public function setEmails($description)
    {
        return $this->setAttribute(ActiveDirectory::DESCRIPTION, $description);
    }
}
