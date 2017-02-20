<?php
namespace CupaTest\Models;

use App;
use Cupa\Models\VolunteerEventCategory;
use CupaTest\TestCase as BaseTest;
use \Illuminate\Foundation\Testing\WithoutMiddleware;
// use \Illuminate\Foundation\Testing\DatabaseMigrations;
use \Illuminate\Foundation\Testing\DatabaseTransactions;

class VolunteerEventCategoryModelTest extends BaseTest
{
    use DatabaseTransactions, WithoutMiddleware;


    public function testFetchForSelect()
    {
        // create some categories
        $categories = factory(VolunteerEventCategory::class, 4)->create();

        $result = VolunteerEventCategory::fetchForSelect();
        $this->assertTrue(is_array($result));
        $this->assertEquals(4, count($result), 'it has the correct number of entries');
    }
}