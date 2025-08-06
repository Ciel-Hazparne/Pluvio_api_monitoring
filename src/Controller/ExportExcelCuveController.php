<?php

namespace App\Controller;

use App\Repository\CuveRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class ExportExcelCuveController extends AbstractController
{
    #[Route('/cuves/export/excel', name: 'cuve_export_excel')]
    public function export(CuveRepository $cuveRepository): Response
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Niveaux des Cuves');

        // Titre
        $sheet->setCellValue('B2', 'Historique des niveaux de cuve');
        $sheet->mergeCells('B2:D2');
        $sheet->getStyle('B2')->getFont()->setSize(16)->setBold(true);
        $sheet->getStyle('B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // En-têtes
        $sheet->setCellValue('B4', 'ID');
        $sheet->setCellValue('C4', 'Niveau (cm)');
        $sheet->setCellValue('D4', 'Horodatage');
        $sheet->getStyle('B4:D4')->getFont()->setBold(true);
        $sheet->getStyle('B4:D4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B4:D4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE699');

        // Largeur colonnes
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(30);

        // Données
        $cuves = $cuveRepository->findAll();
        $row = 5;
        foreach ($cuves as $cuve) {
            $sheet->setCellValue('B' . $row, $cuve->getId());
            $sheet->setCellValue('C' . $row, $cuve->getNiveauCm());
            $sheet->setCellValue('D' . $row, $cuve->getHorodatage()->format('Y-m-d H:i:s'));
            $row++;
        }

        // Style tableau
        $sheet->getStyle('B4:D' . ($row - 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Génération fichier Excel
        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'cuve_') . '.xlsx';
        $writer->save($tempFile);

        return $this->file($tempFile, 'export_cuves.xlsx', ResponseHeaderBag::DISPOSITION_ATTACHMENT);
    }
}
