<?php
namespace App\Service\Image;

use App\Config\SiteConfig;
use App\Entity\Picture;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureUploader
{
    /**
     * Undocumented function
     *
     * @param string $base64
     * @param string $originalName
     * @return Picture|null (null si base64 est invalide)
     */
    public function uploadBase64(string $fullBase64String, string $originalName): ?UploadedFile
    {
        //base64Content example :  data:img/jpeg;base64,/9jxmdMN...
        $parts = explode(';base64,', $fullBase64String);
        $base64String = $parts[1] ?? null;
        
        $decodedString = base64_decode($base64String);
        if(!$decodedString)
        {
            return null;
        }
        $tempFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php' . substr(str_shuffle(str_repeat('AZERTYUIOPQSDFGHJKLMWXCVBN0123456789', 2)), 0, 5) . '.tmp';
        
        file_put_contents($tempFilePath, $decodedString);
        $mimeType = null;
        $error = null;
        $test = true;

        return new UploadedFile($tempFilePath, $originalName, $mimeType, $error, $test);
    }

    public function convertPathToBase64(string $path): ?string 
    {
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);
        $content = file_get_contents(SiteConfig::SITE_URL . $path, false, $context);
        $pureBase64 = base64_encode($content);
        
        return 'data:img/jpeg;base64,' . $pureBase64;
    }
}