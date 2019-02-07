<?php

use Illuminate\Database\Seeder;

class RanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 6; $i++) { 
            $rec = new App\Rank;
            if($i==0){
                $rec->name = 'Initial category';
            }else{
                $rec->name = 'Category '.($i);                
            }
            $rec->save();
        }
    }
}
