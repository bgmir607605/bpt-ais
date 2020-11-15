<?php

namespace tests\unit\models;

use app\models\Schedule;

class ScheduleTest extends \Codeception\Test\Unit
{
    
    public function testFindOne()
    {
        // Поиск существующего. deleted = 0
        $schedule = Schedule::findOne(2802);
        expect_that($schedule);
        expect($schedule->teacherLoadId)->equals('147');

        // Поиск существующего. deleted = 1
        expect_not(Schedule::findOne(2174));
        // Поиск не существующего.
        expect_not(Schedule::findOne(5560));
    }
    
    public function testFindOneDeleted()
    {
        expect_that(Schedule::findOneDeleted(2174));
        expect_not(Schedule::findOneDeleted(2802));
        expect_not(Schedule::findOneDeleted(5560));
    }
    public function testFindAll()
    {
        $this->assertCount(5219, Schedule::findAll());
    }
    
    public function testFindAllDeleted()
    {
        $this->assertCount(336, Schedule::findAllDeleted());
    }
    
    public function testFindForTeacherload() {
        $schedule = Schedule::findForTeacherload(147);
        $this->assertCount(12, $schedule);
        $this->assertEquals('2020-09-28', $schedule[0]->date);
    }
    
    
    
    public function testDelete()
    {
        $schedule = Schedule::findOne(2802);
        $this->assertCount(5219, Schedule::findAll());
        $this->assertCount(336, Schedule::findAllDeleted());
        $this->assertCount(4, $schedule->skips);
        $this->assertCount(15, $schedule->marks);
        
        $schedule->delete();
        
        $this->assertCount(5218, Schedule::findAll());
        $this->assertCount(337, Schedule::findAllDeleted());
        $this->assertCount(0, $schedule->skips);
        $this->assertCount(0, $schedule->marks);
        
        
    }

}
