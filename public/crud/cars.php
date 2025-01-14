<?php

$cars = new Cars();

if($method=='GET'){
    if(!isset($url_array[1])){ // if parameter id not exist
        // METHOD : GET api/cars
        $data = $cars->getAll();
        $response['status'] = 200;
        $response['data'] = $data;
    }else{ // if parameter id exist
        // METHOD : GET api/cars/status
        if ($url_array[1] == 'status') {
            // METHOD : GET api/cars/status
            $data = $cars->getCarsStatus();
            if (empty($data)) {
                $response['status'] = 404;
                $response['data'] = array('error' => 'Objeto no encontrado');
            } else {
                $response['status'] = 200;
                $response['data'] = $data;
            }
        } else {
            // METHOD : GET api/cars/:id
            $id=$url_array[1];
            $data=$cars->getCars($id);
            if(empty($data)) {
                $response['status'] = 404;
                $response['data'] = array('error' => 'Objeto no encontrado');
            } else {
                $response['status'] = 200;
                $response['data'] = $data;	
            }
        }
    }
}
elseif($method=='POST'){
    // METHOD : POST api/cars
    // get post from client
    $json = file_get_contents('php://input');
    $post = json_decode($json); // decode to object

    // check input
    if($post->name=="" || $post->costformula=="" || $post->km==""){
        $response['status'] = 400;
        $response['data'] = array('error' => 'Datos incompletos');
    }else{
        $status = $cars->insertCars($post->name, $post->costformula, $post->km);
        if($status==1){
            $response['status'] = 201;
            $response['data'] = array('success' => 'Datos guardados exitosamente');
        }else{
            $response['status'] = 400;
            $response['data'] = array('error' => 'Hay un error');
        }
    }
}
elseif($method=='PUT'){
    // METHOD : PUT api/cars/:id
    if(isset($url_array[1])){
        $id = $url_array[1];
        // check if id exist in database
        $data=$cars->getCars($id);
        if(empty($data)) { 
            $response['status'] = 404;
            $response['data'] = array('error' => 'Datos no encontrados');	
        }else{
            // get post from client
            $json = file_get_contents('php://input');
            $post = json_decode($json); // decode to object

            // check input completeness
            if($post->name=="" || $post->costformula=="" || $post->km==""){
                $response['status'] = 400;
                $response['data'] = array('error' => 'Datos incompletos');
            }else{
                $status = $cars->updatecars($id, $post->name, $post->costformula, $post->km);
                if($status==1){
                    $response['status'] = 200;
                    $response['data'] = array('success' => 'Los datos se editaron correctamente');
                }else{
                    $response['status'] = 400;
                    $response['data'] = array('error' => 'Hay un error');
                }
            }
        }
    }
}
elseif($method=='DELETE'){
    // METHOD : DELETE api/cars/:id
    if(isset($url_array[1])){
        $id = $url_array[1];
        // check if id exist in database
        $data=$cars->getCars($id);
        if(empty($data)) {
            $response['status'] = 404;
            $response['data'] = array('error' => 'Datos no encontrados');	
        }else{
            $status = $cars->deleteCars($id);
            if($status==1){
                $response['status'] = 200;
                $response['data'] = array('success' => 'Datos eliminados con éxito');
            }else{
                $response['status'] = 400;
                $response['data'] = array('error' => 'Hay un error');
            }
        }
    }
}