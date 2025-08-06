<?php

namespace App\Controller;

use App\Repository\PluviometrieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


class ExportExcelPluvioController extends AbstractController
{
    #[Route('/pluvio/export/excel', name: 'pluvio_export_excel')]
    public function export(PluviometrieRepository $pluviometrieRepository): Response
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Pluviométrie');

        // Titre principal
        $sheet->setCellValue('B2', 'Historique de Pluviométrie');
        $sheet->mergeCells('B2:D2');
        $sheet->getStyle('B2')->getFont()->setSize(16)->setBold(true);
        $sheet->getStyle('B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // En-têtes
        $sheet->setCellValue('B4', 'Date & Heure');
        $sheet->setCellValue('C4', 'Valeur (mm)');
        $sheet->getStyle('B4:C4')->getFont()->setBold(true);
        $sheet->getStyle('B4:C4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B4:C4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D9E1F2');

        // Largeur des colonnes
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(20);

        // Récupération des données
        $pluvios = $pluviometrieRepository->findAll();
        $row = 5;
        foreach ($pluvios as $pluvio) {
            $sheet->setCellValue('B' . $row, $pluvio->getHorodatage()->format('Y-m-d H:i:s'));
            $sheet->setCellValue('C' . $row, $pluvio->getPluvioHeure());
            $row++;
        }

        // Style des cellules
        $sheet->getStyle('B4:C' . ($row - 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Création du fichier Excel
        $writer = new Xlsx($spreadsheet);

        $tempFile = tempnam(sys_get_temp_dir(), 'pluvio_') . '.xlsx';
        $writer->save($tempFile);

        return $this->file($tempFile, 'pluviometrie_export.xlsx', ResponseHeaderBag::DISPOSITION_ATTACHMENT);
    }
}
