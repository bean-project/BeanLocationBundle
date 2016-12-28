<?php

namespace Bean\Bundle\LocationBundle\Model;


interface GeolocationInterface
{

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getAddress();

    /**
     * @param string $address
     */
    public function setAddress($address);

    /**
     * @return string
     */
    public function getCountry();

    /**
     * @param string $country
     */
    public function setCountry($country);

    /**
     * @return string
     */
    public function getFirstDivision();

    /**
     * @param string $firstDivision
     */
    public function setFirstDivision($firstDivision);

    /**
     * @return string
     */
    public function getSecondDivision();

    /**
     * @param string $secondDivision
     */
    public function setSecondDivision($secondDivision);

    /**
     * @return string
     */
    public function getThirdDivision();

    /**
     * @param string $thirdDivision
     */
    public function setThirdDivision($thirdDivision);

    /**
     * @return string
     */
    public function getFourthDivision();

    /**
     * @param string $fourthDivision
     */
    public function setFourthDivision($fourthDivision);

    /**
     * @return string
     */
    public function getFifthDivision();

    /**
     * @param string $fifthDivision
     */
    public function setFifthDivision($fifthDivision);

    /**
     * @return string
     */
    public function getNumber();

    /**
     * @param string $number
     */
    public function setNumber($number);

    /**
     * @return string
     */
    public function getStreet();

    /**
     * @param string $street
     */
    public function setStreet($street);
	
    /**
     * @return string
     */
    public function getPlaceid();

    /**
     * @param string $street
     */
    public function setPlaceid($placeid);
	

	
    /**
     * @return float
     */
    public function getLatitude();

    /**
     * @param float $lat
     */
    public function setLatitude($lat);

    /**
     * @return float
     */
    public function getLongitude();

    /**
     * @param float $geoLong
     */
    public function setLongitude($geoLong);
	
}