<?php

/*
 * This file is Inventory Doctrine Class.
 *
 * (c) Test Te <test@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CRUD\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Inventory
 *
 * @ORM\Table(name="inventory")
 * @ORM\Entity(repositoryClass="CRUD\InventoryBundle\Repository\InventoryRepository")
 */
class Inventory
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="newUsed", type="string", length=45)
     */
    private $newUsed;

      /**
     * @var string
     *
     * @ORM\Column(name="stock", type="string", length=4)
     *
     * @Assert\Regex(
     *     pattern="/^[a-z0-9]+$/i",
     *     htmlPattern = "^[a-zA-Z0-9]+$",
     *     message="Error: You can use only text & numbers (no whitespaces)"
     * )
     */
    private $stock;

    /**
     * @var string
     *
     * @ORM\Column(name="make", type="string", length=45)
     */
    private $make;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=45)
     */
    private $model;

    /**
     * @var string
     *
     * @ORM\Column(name="trim", type="string", length=45)
     */
    private $trim;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

   /**
     * @Assert\Image(
     * maxSize="2000000"
     * )
     */
    private $mainImage;

    /**
    * @var string
    * image path
    */
    private $path;

    /**
     * Sets mainImage.
     *
     * @param UploadedFile $mainImage
     */
    public function setMainImage(UploadedFile $mainImage = null)
    {
        $this->mainImage = $mainImage;
    }

    /**
     * Get mainImage.
     *
     * @return UploadedFile
     */
    public function getMainImage()
    {
        return $this->mainImage;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set newUsed
     *
     * @param string $newUsed
     *
     * @return Inventory
     */
    public function setNewUsed($newUsed)
    {
        $this->newUsed = $newUsed;

        return $this;
    }

    /**
     * Get newUsed
     *
     * @return string
     */
    public function getNewUsed()
    {
        return $this->newUsed;
    }

    /**
     * Set stock
     *
     * @param string $stock
     *
     * @return Inventory
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return string
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set make
     *
     * @param string $make
     *
     * @return Inventory
     */
    public function setMake($make)
    {
        $this->make = $make;

        return $this;
    }

    /**
     * Get make
     *
     * @return string
     */
    public function getMake()
    {
        return $this->make;
    }

    /**
     * Set model
     *
     * @param string $model
     *
     * @return Inventory
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set trim
     *
     * @param string $trim
     *
     * @return Inventory
     */
    public function setTrim($trim)
    {
        $this->trim = $trim;

        return $this;
    }

    /**
     * Get trim
     *
     * @return string
     */
    public function getTrim()
    {
        return $this->trim;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return float
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Show Absolute Path.
     *
     * @return string
     */
    public function getAbsolutePath()
    {
        return null === $this->path
            ? $this->getUploadRootDir()
            : $this->getUploadRootDir() . '/' . $this->path;
    }

    /**
     * Show Upload root dir.
     *
     * @return string
     */ 
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return $_SERVER['DOCUMENT_ROOT'] . $this->getUploadDir();
    }

    /**
     * Show Upload Dir.
     *
     * @return string
     */
    public function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return '/uploads/images';
    }

    /**
     * Show Upload Dir.
     *
     * @param object $image
     */
    public function uploadImage($image = '')
    {
        // the file property can be empty if the field is not required
        if (!empty($image)) {
            $this->setMainImage($image);
        }

        if (null === $this->getMainImage()) {
            return 'Error Empty file' ;
        }
      
        $error = '';
        $imageFileName = $this->getMainImage();
        if (is_object($imageFileName) && $imageFileName->getError() == '0') {
           
            if ($imageFileName->getClientSize() < 20000000) { // 2mb
                $originalName = $imageFileName->getClientOriginalName();
                $nameArray = explode('.' , $originalName);
                $fileType = $nameArray[1];
                $validFiletypes = array('jpg', 'jpeg', 'bmp', 'png');
                
                if (in_array(strtolower($fileType), $validFiletypes)) {
                    $this->setMainImage($imageFileName); 
                    $fileName = date('U') . $this->getMainImage()->getClientOriginalName();
                    
                    // move takes the target directory and then the
                    // target filename to move to
                    $this->getMainImage()->move(
                        $this->getUploadRootDir(),
                        $fileName
                    );

                    // set the path property to the filename where you've saved the file
                    $this->path = $fileName;
                    $this->mainImage = $fileName;
                    
                } else {
                    $error = "Erorr Incorrect file type. You can upload only jpg jpeg bmp png";
                }
            } else {
                $error = "Error Max file size 2mb";
            }
        } else {
            $error = "Error. I can't upload file";
        }
        return $error;
    }

    /**
     * Remove Upload Image.
     *
     * @param string $file
     */
    public function removeUploadImage($file)
    {
        
        $filePath = $this->getAbsolutePath() . '/' . $file;
        if (file_exists($filePath) && !empty($file)) {
            unlink($filePath);
        }
    }
}
