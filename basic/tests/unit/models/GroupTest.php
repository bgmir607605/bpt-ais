<?php

namespace tests\unit\models;

use app\models\Group;

class GroupTest extends \Codeception\Test\Unit
{

    public function testFindOne()
    {
        // Поиск существующего. deleted = 0
        $group = Group::findOne(14);
        expect_that($group);
        expect($group->directId)->equals('2');

//        // Поиск существующего. deleted = 1
//        expect_not(Group::findOne(2));
        // Поиск не существующего.
        expect_not(Group::findOne(24));
    }
    
    public function testFindOneDeleted()
    {
//        expect_that(Group::findOneDeleted(2));
        expect_not(Group::findOneDeleted(14));
        expect_not(Group::findOneDeleted(24));
    }
    public function testFindAll()
    {
        $this->assertCount(23, Group::findAll());
    }
    
    public function testFindAllDeleted()
    {
        $this->assertCount(0, Group::findAllDeleted());
    }
    
    public function testFindForDirect() {
        $groups = Group::findForDirect(2);
        $this->assertCount(5, $groups);
        $this->assertEquals('1', $groups[0]->course);
    }
    
    
    
    public function testDelete()
    {
        $this->assertCount(23, Group::findAll());
        $this->assertCount(0, Group::findAllDeleted());
        $group = Group::findOne(14);
        $this->assertCount(29, $group->teacherloads);
        
        $group->delete();
        
        $this->assertCount(0, $group->teacherloads);
        $this->assertCount(22, Group::findAll());
        $this->assertCount(1, Group::findAllDeleted());
        
        
    }

}
