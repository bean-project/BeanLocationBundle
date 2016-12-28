<?php
namespace Bean\Bundle\LocationBundle\Model;

use Sonata\CoreBundle\Model\ManagerInterface;
use Sonata\CoreBundle\Model\PageableManagerInterface;

/**
/**
 * Interface to be implemented by Location managers. This adds an additional level
 * of abstraction between your application, and the actual repository.
 *
 * All changes to locations should happen through this interface.
 *
 *
 * @author Binh Le <binh@bean-project.org>
 */
interface LocationManagerInterface extends ManagerInterface, PageableManagerInterface
{

}