<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Faker\Factory;
use DateTime;

class myController extends Controller
{
    //creo les dades fictícies
    public function inici()
    {
        //1000 registres 
        for ($i = 0; $i < 1000; $i++) {
            $faker = Factory::create();
            $client = new Order();
            $client->company = $faker->freeEmail();
            //creo el format d'acord amb el que es demana
            $client->date = date_format($faker->dateTimeBetween(), 'Y-m-d');
            //creo numeros del o al 10000
            $client->qty =  $faker->numberBetween(0, 10000);
            //es guarda el client
            $client->save();
        }
    }

    public function api()
    {
        $api = Order::all();
        return json_encode($api, JSON_FORCE_OBJECT);
    }
}
