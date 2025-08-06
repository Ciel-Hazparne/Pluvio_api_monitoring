<?php
namespace App\Controller;

use App\Repository\PluviometrieRepository;
use App\Repository\CuveRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartController extends AbstractController
{
    #[Route('/mesureschart', name: 'mesures_chart')]
    public function index(
        ChartBuilderInterface $chartBuilder,
        PluviometrieRepository $pluviometrieRepo,
        CuveRepository $cuveRepo,
        Request $request,
        SessionInterface $session
    ): Response {
        $dataType = $request->query->get('type', 'pluviometrie');
        $selectedDay = trim((string) $request->query->get('day', '')) ?: null;
        $selectedHour = trim((string) $request->query->get('hour', '')) ?: null;

        if ($dataType === 'pluviometrie') {
            $title = "Évolution de la Pluviométrie";
            $label = "Pluviométrie (mm)";
            $color = "rgb(54, 162, 235)";
            $entries = $pluviometrieRepo->findBy([], ['horodatage' => 'ASC']);
        } else {
            $title = "Niveau des Cuves";
            $label = "Niveau de Cuve (cm)";
            $color = "rgb(255, 99, 132)";
            $entries = $cuveRepo->findBy([], ['horodatage' => 'ASC']);
        }

        $labels = [];
        $data = [];

        foreach ($entries as $entry) {
            $date = $entry->getHorodatage();
            $day = $date->format('Y-m-d');
            $hour = $date->format('H');

            if (($selectedHour && $selectedHour !== $hour) || ($selectedDay && $selectedDay !== $day)) {
                continue;
            }

            $labels[] = $date->format('Y-m-d H:i');
            $data[] = $dataType === 'pluviometrie'
                ? $entry->getPluvioHeure()
                : $entry->getNiveauCm();
        }

        $chartType = $selectedHour ? Chart::TYPE_BAR : Chart::TYPE_LINE;

        $chart = $chartBuilder->createChart($chartType);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [[
                'label' => $label,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'data' => $data,
            ]],
        ]);

        return $this->render('chart/index.html.twig', [
            'chart' => $chart,
            'dataType' => $dataType,
            'selectedDay' => $selectedDay,
            'selectedHour' => $selectedHour,
            'title' => $title,
    'current_menu' => 'mesureschart'    ]);
    }
}

