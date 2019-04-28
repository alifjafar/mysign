<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use setasign\Fpdi\Tcpdf\Fpdi;

class SignatureController extends Controller
{
    public function setSign(Request $request)
    {
        $pdf = new Fpdi();
        $pageSignature = 1;
        $certificate = File::get(storage_path('app\credentials\tcpdf.crt'));
        $count = $pdf->setSourceFile(storage_path('app\pdf\secret.pdf'));

        $info = [
            'Name' => 'Alif Jafar',
            'Reason' => 'Saya telah menyutujui document ini',
            'Location' => 'Bandung - Jawa Barat, Indonesia',
            'Date' => now()->format('l jS F Y h:i:s A'),
        ];


        $pdf->setSignature($certificate, $certificate, 'simplesystem', '', 2, $info);

        for ($i = 1; $i <= $count; $i++) {
            $pageId = $pdf->importPage($i);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->addPage();
            $pdf->useTemplate($pageId);
//            if ($i == $pageSignature) {
//                $pdf->Image(storage_path('app/public/signature.png'), 170, 220, 15, 15, 'PNG');
//            }
        }
//        $pdf->setSignatureAppearance(170, 220, 15, 15, $pageSignature);


        $pdf->Output(storage_path('app/pdf/') . 'sign_secret2.pdf', 'F');

        return "Berhasil";
    }
}
