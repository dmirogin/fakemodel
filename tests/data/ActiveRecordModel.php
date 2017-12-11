<?php

namespace tests\data;

use yii\db\ActiveRecord;

class ActiveRecordModel extends ActiveRecord
{
    public $id;
    public $title;
    public $text;

    public static function tableName(): string
    {
        return 'posts';
    }
}
