<?php
/*
 * This file is part of the BeanLocationBundle package.
 *
 * (c) Bean Project <https://bean-project.github.io/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bean\Bundle\LocationBundle\Model;

use Bean\Bundle\LocationBundle\Validator\Constraints as BeanAssert;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Storage agnostic user object
 *
 * @author Binh Le <binh@bean-project.org>
 */
class Geolocation implements GeolocationInterface, \ArrayAccess
{
    /**
     * @param string $placeId
     */
    public function setPlaceId($placeId)
    {
        if ($placeId === $this->placeId) {
            return $this;
        }
        $this->id = null;
        $this->placeId = $placeId;
        return $this;
    }

    private $locationData = array();

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->locationData[] = $value;
        } else {
            $this->locationData[$offset] = $value;
            if (isset($this->$offset)) {
                $setter = 'set' . ucfirst($offset);
                $getter = 'get' . ucfirst($offset);
                $this->{$setter}($value);
                $this->locationData[$offset] = $this->{$getter}();
            }
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->locationData[$offset]) || isset($this->$offset);
    }

    public function offsetUnset($offset)
    {
        unset($this->locationData[$offset]);
        $this->$offset = null;
    }

    public function offsetGet($offset)
    {
        $property = isset($this->$offset) ? $this->$offset : null;
        return isset($this->locationData[$offset]) ? $this->locationData[$offset] : $property;
    }

    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $address;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var string
     */
    protected $firstDivision;

    /**
     * @var string
     */
    protected $secondDivision;

    /**
     * @var string
     */
    protected $thirdDivision;

    /**
     * @var string
     */
    protected $fourthDivision;

    /**
     * @var string
     */
    protected $fifthDivision;

    /**
     * @var string
     */
    protected $locality;

    /**
     * @var string
     */
    protected $street;

    /**
     * @var string
     */
    protected $placeId;

    /**
     * @var string
     */
    protected $number;

    /**
     * @var float
     */
    protected $latitude;

    /**
     * @var float
     */
    protected $longitude;


    public function setGeolocation($location = array())
    {
        $this->locationData = $location;
        $this->setLatitude($location['latitude']); // in accordance with $options['lat_name'] (Form Field Option): default to lat
        $this->setLongitude($location['longitude']); // in accordance with $options['long_name'] (Form Field Option): default to long
        $this->setFirstDivision($location['firstDivision']);  // in accordance with $options['first_division_name'] (Form Field Option): default to first_division
        // the same to below
        $this->setSecondDivision($location['secondDivision']);
        $this->setThirdDivision($location['thirdDivision']);
        $this->setFourthDivision($location['fourthDivision']);
        $this->setFifthDivision($location['fifthDivision']);
        $this->setCountry($location['country']);
        $this->setStreet($location['street']);
        $this->setNumber($location['number']);
        $this->setAddress($location['address']);
        $this->setLocality($location['locality']);
        $this->setPlaceId($location['placeId']);
        return $this;
    }

    /**
     * @Assert\NotBlank()
     * @BeanAssert\LatLong()
     */
    public function getGeolocation()
    {
        return array(
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude(),
            'country' => $this->getCountry(),
            'firstDivision' => $this->getFirstDivision(),
            'secondDivision' => $this->getSecondDivision(),
            'thirdDivision' => $this->getThirdDivision(),
            'fourthDivision' => $this->getFourthDivision(),
            'fifthDivision' => $this->getFifthDivision(),
            'street' => $this->getStreet(),
            'number' => $this->getNumber(),
            'address' => $this->getAddress(),
            'placeId' => $this->getPlaceId(),
            'locality' => $this->getLocality()
        );
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getFirstDivision()
    {
        return $this->firstDivision;
    }

    /**
     * @param string $firstDivision
     */
    public function setFirstDivision($firstDivision)
    {
        $this->firstDivision = $firstDivision;
    }


    /**
     * @return string
     */
    public function getSecondDivision()
    {
        return $this->secondDivision;
    }

    /**
     * @param string $secondDivision
     */
    public function setSecondDivision($secondDivision)
    {
        $this->secondDivision = $secondDivision;
    }

    /**
     * @return string
     */
    public function getThirdDivision()
    {
        return $this->thirdDivision;
    }

    /**
     * @param string $thirdDivision
     */
    public function setThirdDivision($thirdDivision)
    {
        $this->thirdDivision = $thirdDivision;
    }

    /**
     * @return string
     */
    public function getFourthDivision()
    {
        return $this->fourthDivision;
    }

    /**
     * @param string $fourthDivision
     */
    public function setFourthDivision($fourthDivision)
    {
        $this->fourthDivision = $fourthDivision;
    }

    /**
     * @return string
     */
    public function getFifthDivision()
    {
        return $this->fifthDivision;
    }

    /**
     * @param string $fifthDivision
     */
    public function setFifthDivision($fifthDivision)
    {
        $this->fifthDivision = $fifthDivision;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }


    /**
     * @return string
     */
    public function getPlaceId()
    {
        return $this->placeId;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $lat
     */
    public function setLatitude($lat)
    {
        $this->latitude = $lat;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude($long)
    {
        $this->longitude = $long;
    }

    /**
     * @return string
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * @param string $locality
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;
    }


}