<?php
namespace App\Service\Image;

use App\Config\SiteConfig;
use App\Entity\Picture;
use App\Entity\TranslatableString;
use App\Service\PicturePathResolver;
use Exception;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PictureUploader
{
    public function __construct(
        private Base64Util $base64Util,
        private ValidatorInterface $validator,
        private PicturePathResolver $picturePathResolver
    )
    {

    }

    public function uploadFile(File $file, TranslatableString $alt): Picture
    {
        $picture = new Picture;
        $picture->setFile($file)
                ->setFileSize($file->getSize())
                ->setAlt($alt)
                ->setCreatedAt($this->dateTimeGenerator->generateImmutable())
                ;

        return $picture;
    }

    /**
     * Undocumented function
     *
     * @param string $base64
     * @param string $originalName
     * @return Picture|null (null si base64 est invalide)
     */
    public function uploadBase64(string $base64, string $originalName): ?Picture
    {
        $base64String = $this->base64Util->extractPureBase64String($base64);
        if(!$base64String)
        {
            return null;
        }
        $imageFile = $this->base64Util->convertBase64ToUploadedFile($base64String, $originalName);
        if(!$imageFile)
        {
            return null;
        }

        $this->validateMaxSize($imageFile);

        $picture = new Picture;
        $picture->setFile($imageFile)
                ->setFileSize($imageFile->getSize())
                ;

        return $picture;
    }

    public function getPictureBase64(?Picture $picture): ?string 
    {
        if(!$picture)
        {
            return null;
        }
        $path = $this->picturePathResolver->getPath($picture);
        $pureBase64 = $this->base64Util->convertPathToBase64($path);
        
        return $this->base64Util->createPrefixedBase64String($pureBase64, 'jpeg');
    }


    private function validateMaxSize(UploadedFile $imageFile)
    {
        if($imageFile->getSize() > SiteConfig::UPLOAD_MAX_SIZE)
        {
            throw new Exception(SiteConfig::UPLOAD_MAX_SIZE_MESSAGE);
        }
    }
}