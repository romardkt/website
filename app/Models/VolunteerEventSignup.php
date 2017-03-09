<?php

namespace Cupa\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class VolunteerEventSignup extends Model
{
    protected $table = 'volunteer_event_signups';
    protected $fillable = [
        'volunteer_event_id',
        'volunteer_id',
        'answers',
        'notes',
    ];

    public function event()
    {
        return $this->belongsTo(VolunteerEvent::class, 'volunteer_event_id', 'id');
    }

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }

    public static function generateVolunteersByYearForChart()
    {
        $years = [];
        $counts = [];
        foreach(VolunteerEventSignup::with('event')->get() as $signup) {
            $year = (new Carbon($signup->event->start))->year;

            // set the unique years
            if (!isset($years[$year])) {
                $years[$year] = true;
            }

            if (!isset($counts[$year])) {
                $counts[$year] = 0;
            }

            $counts[$year] += 1;
        }

        ksort($years);

        $data = [
            'labels' => array_keys($years),
            'datasets' => [
                [
                    'label' => 'Volunteers',
                    'data' => array_values($counts),
                    'backgroundColor' => array_fill(0, count($counts), 'rgba(54, 162, 235, 0.4)'),
                ],
            ],
        ];

        return [
            'type' => 'bar',
            'data' => $data,
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => '# of Volunteers per year',
                ],
                'scales' => [
                    'yAxes' => [
                        [
                            'ticks' => [
                                'beginAtZero' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
