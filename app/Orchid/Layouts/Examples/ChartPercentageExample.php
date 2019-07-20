<?php

namespace App\Orchid\Layouts\Examples;

use Orchid\Screen\Layouts\Chart;

class ChartPercentageExample extends Chart
{
    /**
     * @var string
     */
    public $title = 'Percentage Chart';

    /**
     * @var int
     */
    public $height = 250;

    /**
     * Available options:
     * 'bar', 'line',
     * 'pie', 'percentage'.
     *
     * @var string
     */
    public $type = 'percentage';

    /**
     * @var array
     */
    public $labels = [
        '12am-3am',
        '3am-6am',
        '6am-9am',
        '9am-12pm',
        '12pm-3pm',
        '3pm-6pm',
        '6pm-9pm',
    ];

    /**
     * @var string
     */
    public $data = 'charts';
}
