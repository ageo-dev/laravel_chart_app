<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 4; $i++) { 
            $rec = new App\Role;
            if($i==0){
                $rec->name = 'The highest authority';
            }
            if($i==1){
                $rec->name = 'Can register / edit / delete';
            }
            if($i==2){
                $rec->name = 'Only registered';
            }
            if($i==3){
                $rec->name = 'Only browsable';
            }
            $rec->save();
        }
    }
}
