<?php

use Illuminate\Database\Seeder;

//注册数据填充类
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //注意顺序
        $this->call(UsersTableSeeder::class);
        $this->call(TopicsTableSeeder::class);
        $this->call(ReplysTableSeeder::class);
        $this->call(LinksTableSeeder::class);
    }
}