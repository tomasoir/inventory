<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Test Te <test@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CRUD\InventoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Inventory;
use Symfony\Component\HttpFoundation\Request;

/**
 * Show all vehicles.
 *
 * @package Add
 * @author  Test Te <test@symfony.com>
 * @license Corporation 
 * @link    Here/fff
 */
class DefaultController extends Controller
{
    /**
    * Show all vehicles 
    *
    * @return array
    */
    public function indexAction()
    {
        $del_vehicle=Request::createFromGlobals()->query->get('del');
        $deleted_vehicle="";
        
        if (!empty($del_vehicle)) {
            $deleted_vehicle=$this->delete($del_vehicle);
        }

        $inventory = $this->getDoctrine()
            ->getRepository('CRUDInventoryBundle:Inventory')
            ->findall();
                       
        return $this->render(
            'CRUDInventoryBundle:Default:index.html.twig',
            array(
                'inventory'=>$inventory,
                'deleted_vehicle'=>$deleted_vehicle)
        );
    }

    /**
    * Delete vehicle
    * 
    * @param integer $id vehicle
    *
    * @return array
    */
    public function delete($id) 
    {
        $em = $this->getDoctrine()->getManager();
        $vehicle = $em->getRepository('CRUDInventoryBundle:Inventory')->find($id);
               
        if ($vehicle) {
            $em->remove($vehicle);
            $em->flush();
            $vehicle->removeUploadImage($vehicle->getMainimage());
            return "vehicle deleted";
        } else {
            return "";
        }
    }
}
