<?php
namespace Bean\Bundle\LocationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class LatLongValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        return true;

//        if (!preg_match('/^[0-9\-\.]+$/', $value['lat'], $matches) || !preg_match('/^[0-9\-\.]+$/', $value['long'], $matches)) {
//            $this->context->addViolation($constraint->message, array('%lat%' => (float)$value['lat'], '%long%' => (float)$value['long']));
//            return false;
//        }
//        if ($value['lat'] > 90 || $value['lat'] < -90 || $value['long'] > 180 || $value['long'] < -180) {
//            $this->context->addViolation($constraint->message, array('%lat%' => (float)$value['lat'], '%long%' => (float)$value['long']));
//            return false;
//        }
//        return true;

    }
}