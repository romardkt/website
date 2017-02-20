<?php
namespace CupaTest\Models;

use App;
use Config;
use Cupa\Models\UserRole;
use Cupa\Models\Role;
use CupaTest\TestCase as BaseTest;
use \Illuminate\Foundation\Testing\WithoutMiddleware;
// use \Illuminate\Foundation\Testing\DatabaseMigrations;
use \Illuminate\Foundation\Testing\DatabaseTransactions;

class UserRoleModelTest extends BaseTest
{
    use DatabaseTransactions, WithoutMiddleware;

    public function testRole()
    {
      $role = factory(Role::class)->create();
      $userRole = factory(UserRole::class)->create(['role_id' => $role->id]);

      $this->assertEquals($role->id, $userRole->role->id, 'it has a role relation');
    }

}
