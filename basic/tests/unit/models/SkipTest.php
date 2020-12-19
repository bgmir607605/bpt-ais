<?php

namespace tests\unit\models;

use app\models\Skip;

class SkipTest extends \Codeception\Test\Unit
{

    public function testFindOne()
    {
        // Поиск существующего. deleted = 0
        $skip = Skip::findOne(13);
        expect_that($skip);
        expect($skip->studentId)->equals('229');

        // Поиск существующего. deleted = 1
        expect_not(Skip::findOne(424));
        // Поиск не существующего.
        expect_not(Skip::findOne(5));
    }
    
    public function testFindOneDeleted()
    {
        expect_that(Skip::findOneDeleted(424));
        expect_not(Skip::findOneDeleted(13));
        expect_not(Skip::findOneDeleted(5));
    }
    public function testFindAll()
    {
        $this->assertCount(14747, Skip::findAll());
    }
    
    public function testFindAllDeleted()
    {
        $this->assertCount(88, Skip::findAllDeleted());
    }
    
    public function testFindForSchedule() {
        $skips = Skip::findForSchedule(2802);
        $this->assertCount(4, $skips);
        $this->assertEquals('508', $skips[0]->studentId);
    }
    
    
    
    public function testDelete()
    {
        $this->assertCount(14747, Skip::findAll());
        $this->assertCount(88, Skip::findAllDeleted());
        $skip = Skip::findOne(480);
        $skip->delete();
        $this->assertCount(14746, Skip::findAll());
        $this->assertCount(89, Skip::findAllDeleted());
        
        
    }

}
