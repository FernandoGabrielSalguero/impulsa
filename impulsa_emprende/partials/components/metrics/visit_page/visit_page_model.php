<?php

declare(strict_types=1);

class VisitPageMetricsModel
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * @param array<int, int> $integrationIds
     * @return array<int, array{label:string,total:int,year:int,month:int}>
     */
    public function obtenerResumenMensual(array $integrationIds): array
    {
        $meses = [];
        $base = new DateTimeImmutable('first day of this month');

        for ($offset = 0; $offset < 3; $offset++) {
            $fecha = $base->modify('-' . $offset . ' month');
            $meses[] = [
                'label' => $this->formatearMes($fecha),
                'year' => (int) $fecha->format('Y'),
                'month' => (int) $fecha->format('n'),
                'total' => 0,
            ];
        }

        if ($integrationIds === []) {
            return $meses;
        }

        $placeholders = implode(',', array_fill(0, count($integrationIds), '?'));
        $sql = "SELECT YEAR(visited_at) AS year_number,
                       MONTH(visited_at) AS month_number,
                       COUNT(*) AS total
                FROM visit_user_page
                WHERE api_integration_id IN ($placeholders)
                  AND visited_at >= DATE_SUB(DATE_FORMAT(CURDATE(), '%Y-%m-01'), INTERVAL 2 MONTH)
                  AND visited_at < DATE_ADD(DATE_FORMAT(CURDATE(), '%Y-%m-01'), INTERVAL 1 MONTH)
                GROUP BY YEAR(visited_at), MONTH(visited_at)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($integrationIds));
        $totales = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($meses as &$mes) {
            foreach ($totales as $total) {
                if ((int) ($total['year_number'] ?? 0) === $mes['year'] && (int) ($total['month_number'] ?? 0) === $mes['month']) {
                    $mes['total'] = (int) ($total['total'] ?? 0);
                    break;
                }
            }
        }
        unset($mes);

        return $meses;
    }

    private function formatearMes(DateTimeImmutable $fecha): string
    {
        $mapa = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];

        return ($mapa[(int) $fecha->format('n')] ?? $fecha->format('F')) . ' ' . $fecha->format('Y');
    }
}
