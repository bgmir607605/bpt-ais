<?php

namespace tests\unit\models;

use app\models\Teacherload;

class TeacherloadTest extends \Codeception\Test\Unit
{
    
    public function testFindOne()
    {
        // Поиск существующего. deleted = 0
        $teacherload = Teacherload::findOne(9);
        expect_that($teacherload);
        expect($teacherload->userId)->equals('4');

        // Поиск существующего. deleted = 1
        expect_not(Teacherload::findOne(22));
        // Поиск не существующего.
        expect_not(Teacherload::findOne(640));
    }
    
    public function testFindOneDeleted()
    {
        expect_that(Teacherload::findOneDeleted(22));
        expect_not(Teacherload::findOneDeleted(9));
        expect_not(Teacherload::findOneDeleted(640));
    }
    public function testFindAll()
    {
        $this->assertCount(622, Teacherload::findAll());
    }
    
    public function testFindAllDeleted()
    {
        $this->assertCount(10, Teacherload::findAllDeleted());
    }
    
    public function testFindForGroup() {
        $teacherload = Teacherload::findForGroup(14);
        $this->assertCount(29, $teacherload);
        $this->assertEquals('24', $teacherload[0]->userId);
    }
    public function testFindForUser() {
        $teacherload = Teacherload::findForUser(27);
        $this->assertCount(13, $teacherload);
        $this->assertEquals('20', $teacherload[0]->groupId);
    }
    
    
    
    public function testDelete()
    {
        // TODO monitoring delete too
        $teacherload = Teacherload::findOne(509);
        $this->assertCount(622, Teacherload::findAll());
        $this->assertCount(10, Teacherload::findAllDeleted());
        $this->assertCount(37, $teacherload->schedules);
        
        $teacherload->delete();
        
        $this->assertCount(621, Teacherload::findAll());
        $this->assertCount(11, Teacherload::findAllDeleted());
        $this->assertCount(0, $teacherload->schedules);
        
        
    }

}
