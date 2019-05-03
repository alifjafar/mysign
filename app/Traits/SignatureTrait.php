<?php
/**
 * Created by PhpStorm.
 * User: Alif Jafar
 * Date: 4/28/2019
 * Time: 2:02 PM
 */

namespace App\Traits;

use GuzzleHttp\Client;
use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Support\Facades\File;
use Smalot\PdfParser\Parser;

trait SignatureTrait
{
    public function digitalSignatureUpload($file, $user)
    {
        $client = new Client();
        $client->request('POST', 'http://localhost:8080/signed', [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen(storage_path('app') . '/' . $file['path'] . $file['filename'], 'r'),
                ],
                [
                    'name' => 'path',
                    'contents' => $file['path']
                ],
                [
                    'name' => 'cert',
                    'contents' => fopen(storage_path('app\credentials\tcpdf.p12')
                        , 'r')
                ],
                [
                    'name' => 'key',
                    'contents' => 'simplesystem'
                ],
                [
                    'name' => 'name',
                    'contents' => $user['name']
                ],
                [
                    'name' => 'reason',
                    'contents' => 'Dokumen ini di upload oleh ' . $user['name'] . ' pada '
                        . now()
                ]
            ]
        ]);
    }


    public function getPDFDetails($filename, $filepath)
    {
        $client = new Client();
        $response = $client->request('POST', 'http://localhost:8080/validate', [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen(storage_path('app') . '/' . $filepath . $filename
                        , 'r'),
                ],
            ]
        ]);

        $data = $response->getBody()->getContents();
        return json_decode($data, true);
    }

    public function digitalSignatureApprove($file, $user)
    {
        $client = new Client();
        $client->request('POST', 'http://localhost:8080/signed', [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen(storage_path('app') . '/' . $file['path'] . $file['filename'], 'r'),
                ],
                [
                    'name' => 'path',
                    'contents' => $file['path']
                ],
                [
                    'name' => 'cert',
                    'contents' => fopen(storage_path('app\credentials\tcpdf.p12')
                        , 'r')
                ],
                [
                    'name' => 'key',
                    'contents' => 'simplesystem'
                ],
                [
                    'name' => 'name',
                    'contents' => $user['name']
                ],
                [
                    'name' => 'reason',
                    'contents' => 'Saya menyetujui document ini  (' . $user['name'] . ') pada '
                        . now()
                ]
            ]
        ]);
    }

    public function digitalSignature($file, $user)
    {
        $pdf = new Fpdi();
        $certificate = File::get(storage_path('app\credentials\tcpdf.crt'));
        $count = $pdf->setSourceFile(storage_path('app') . '/' . $file['path'] . $file['filename']);

        $info = [
            'Name' => $user['name'],
            'Reason' => 'Dokumen di approve oleh ' . $user['name'],
            'Location' => 'Bandung',
            'Date' => now()->format('l jS F Y h:i:s A'),
        ];

        $pdf->setSignature($certificate, $certificate, 'simplesystem', '', 2, $info);

        for ($i = 1; $i <= $count; $i++) {
            $pageId = $pdf->importPage($i);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->addPage();
            $pdf->useTemplate($pageId);
        }


        $pdf->Output(storage_path('app') . '/' . $file['path'] . $file['filename'], 'F');
    }
    public function olddigitalSignatureUpload($file, $user)
    {
        $pdf = new Fpdi();
        $certificate = File::get(storage_path('app\credentials\tcpdf.crt'));
        $count = $pdf->setSourceFile(storage_path('app') . '/' . $file['path'] . $file['filename']);

        $info = [
            'Name' => $user['name'],
            'Reason' => 'Dokumen ini di upload oleh ' . $user['name'],
            'Location' => 'Bandung',
            'Date' => now()->format('l jS F Y h:i:s A'),
        ];

        $pdf->setSignature($certificate, $certificate, 'simplesystem', '',
            3, $info, 'A');

        for ($i = 1; $i <= $count; $i++) {
            $pageId = $pdf->importPage($i);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->addPage();
            $pdf->useTemplate($pageId);
        }


        $pdf->Output(storage_path('app') . '/' . $file['path'] . $file['filename'], 'F');
    }
}
