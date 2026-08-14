<?php
    use App\Models\BusStop;
    use App\Models\BusRouteStop;

    if(!function_exists('getBusStop')){
        function getBusStop($bus_stop_id){
            $data = BusStop::find($bus_stop_id);

           return "{$data->name} ({$data->bus_stop_number})";
        }
    }