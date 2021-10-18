<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class FlyersControllerTest extends TestCase {
    use IntegrationTestTrait;

    public function testGetApiSuccess() {
        $this->get('/flyers.json');
        $this->assertResponseOk();
        $this->get('/flyers/1.json');
        $this->assertResponseOk();
    }

    public function testGetListApiWrongFields() {
        $this->get('/flyers.json?fields=city');
        $this->assertResponseCode(400);
        $this->assertResponseContains("error");
    }

    public function testGetListApiWrongFilters() {
        $this->get('/flyers.json?filter[is_not_published]=1');
        $this->assertResponseCode(400);
        $this->assertResponseContains("error");
    }

    public function testGetListApiEmpty() {
        $this->get('/flyers.json?filter[category]=wrong');
        $this->assertResponseCode(404);
        $this->assertResponseContains("error");
    }

    public function testGetListCompleteQuery() {
        $this->get('/flyers.json?page=1&limit=50&fields=title,category&filter[category]=Discount&filter[is_published]=1');
        $this->assertResponseCode(200);
        $this->assertResponseContains("results");
    }

    public function testGetFlyer() {
        $this->get('/flyers/2.json');
        $this->assertResponseCode(200);
        $this->assertResponseContains("results");
    }

    public function testGetFlyerCompleteQuery() {
        $this->get('/flyers/2.json?fields=title,category');
        $this->assertResponseCode(200);
        $this->assertResponseContains("results");
    }
}
