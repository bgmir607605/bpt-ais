<?php

namespace tests\unit\models;

use app\models\Mark;

class MarkTest extends \Codeception\Test\Unit
{

    public function testFindOne()
    {
        // Поиск существующего. deleted = 0
        $mark = Mark::findOne(3);
        expect_that($mark);
        expect($mark->value)->equals('3');

        // Поиск существующего. deleted = 1
        expect_not(Mark::findOne(2));
        // Поиск не существующего.
        expect_not(Mark::findOne(5));
    }
    
    public function testFindOneDeleted()
    {
        expect_that(Mark::findOneDeleted(2));
        expect_not(Mark::findOneDeleted(3));
        expect_not(Mark::findOneDeleted(15));
    }
    public function testFindAll()
    {
        $this->assertCount(16212, Mark::findAll());
    }
    
    public function testFindAllDeleted()
    {
        $this->assertCount(3, Mark::findAllDeleted());
    }
    
    public function testFindForSchedule() {
        $marks = Mark::findForSchedule(2174);
        $this->assertCount(17, $marks);
        $this->assertEquals('3', $marks[0]->value);
    }
    
    
    
    public function testDelete()
    {
        $this->assertCount(16212, Mark::findAll());
        $this->assertCount(3, Mark::findAllDeleted());
        $mark = Mark::findOne(3);
        $mark->delete();
        $this->assertCount(16211, Mark::findAll());
        $this->assertCount(4, Mark::findAllDeleted());
        
        
    }

}
