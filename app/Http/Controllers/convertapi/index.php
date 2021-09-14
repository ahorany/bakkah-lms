<?php
require __DIR__ . '/vendor/autoload.php';

use \ConvertApi\ConvertApi;

ConvertApi::setApiSecret('KmKCe223BpWtWQFC');

//$secret = 'KmKCe223BpWtWQFC';

// $fromFormat = 'web';
// $conversionTimeout = 180;
// $dir = sys_get_temp_dir();

// $result = ConvertApi::convert(
//     'pdf',
//     [
//         'Url' => 'https://en.wikipedia.org/wiki/Data_conversion',
//         'FileName' => 'web-example'
//     ],
//     $fromFormat,
//     $conversionTimeout
// );

// $savedFiles = $result->saveFiles($dir);

// echo "The web page PDF saved to\n";

// print_r($savedFiles);

$result = ConvertApi::convert('pdf', [
        'File' => 'html_certificate.html',
        'FileName' => 'html_certificate',
        'PageOrientation' => 'landscape',
        'PageSize' => 'a4',
        'MarginTop' => '0',
        'MarginRight' => '0',
        'MarginBottom' => '0',
        'MarginLeft' => '0',
    ], 'html'
);
$result->saveFiles(__DIR__ . '\pdf');

// $result = ConvertApi::convert('pdf', [
//     'File' => 'html_certificate_axelos.html',
//     'FileName' => 'html_certificate_axelos',
//     'PageSize' => 'a4',
//     'MarginTop' => '0',
//     'MarginRight' => '0',
//     'MarginBottom' => '0',
//     'MarginLeft' => '0',
// ], 'html'
// );
// $result->saveFiles(__DIR__ . '\pdf');