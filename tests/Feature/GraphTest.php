<?php

use App\Models\County;
use App\Models\Population;
use App\Models\Town;

// TASK 8 - Graph page tests (Pest)

beforeEach(function () {
    County::create(['id' => 1, 'cname' => 'Csongrád']);
    Town::create(['id' => 1, 'tname' => 'Szeged', 'countyid' => 1, 'countyseat' => true, 'countylevel' => true]);
    Town::create(['id' => 2, 'tname' => 'Makó', 'countyid' => 1, 'countyseat' => false, 'countylevel' => false]);
    Population::create(['townid' => 1, 'ryear' => 2018, 'women' => 87000, 'total' => 163000]);
    Population::create(['townid' => 1, 'ryear' => 2019, 'women' => 86000, 'total' => 162000]);
    Population::create(['townid' => 2, 'ryear' => 2019, 'women' => 12000, 'total' => 23000]);
});

test('the graph page loads chart.js and the four charts', function () {
    $this->get('/graph')
        ->assertOk()
        ->assertSee('chart.umd.min.js', false)
        ->assertSee('id="trendChart"', false)
        ->assertSee('id="topChart"', false)
        ->assertSee('id="countyChart"', false)
        ->assertSee('id="genderChart"', false);
});

test('the chart data comes from the database', function () {
    $response = $this->get('/graph?year=2019&towns[]=1');

    $response->assertOk();
    expect($response->viewData('topTowns')['labels']->all())->toBe(['Szeged', 'Makó']);
    expect($response->viewData('gender')['men']->all())->toBe([76000, 87000]);
    expect($response->viewData('trend')['datasets'][0]['data']->all())->toBe([163000, 162000]);
});
