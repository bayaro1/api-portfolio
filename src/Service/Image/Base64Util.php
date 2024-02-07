<?php
namespace App\Service\Image;

use App\Config\SiteConfig;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Base64Util
{
    /**
     * @param string $fullBase64String
     * @return string|null (null si format incorrect)
     */
    public function extractPureBase64String(string $fullBase64String): ?string
    {
        //base64Content example :  data:img/jpeg;base64,/9jxmdMN...
        $parts = explode(';base64,', $fullBase64String);
        return $parts[1] ?? null;
    }

    public function createPrefixedBase64String(string $pureBase64, string $format)
    {
        return 'data:img/' . $format . ';base64,' . $pureBase64;
    }

    /**
     * @param string $base64String
     * @param string $originalName
     * @return UploadedFile|null (null si la string ne peut pas être convertie)
     */
    public function convertBase64ToUploadedFile(string $base64String, string $originalName): ?UploadedFile
    {
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

    /**
     * @param string $path (ex: '/img/pictures/picture-123456.jpg')
     * @return string
     */
    public function convertPathToBase64(string $path): string
    {
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);
        $content = file_get_contents(SiteConfig::SITE_URL . $path, false, $context);
        $base64 = base64_encode($content);

        return $base64;
    }
}