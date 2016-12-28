<?php
namespace Bean\Bundle\LocationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LatLong extends Constraint
{
    public $message = 'The values for latitude and longitude ("%lat%" and "%lng%") are not valid.';
}