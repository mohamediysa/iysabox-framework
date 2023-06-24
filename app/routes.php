<?php
errors(); //set to false to disable errors

######################################################
//                                                  //
//     Thanks for giving the framework a try.       //
//           Give us a start on github:             //
// https://github.com/mohamediysa/iysabox-framework //
//                                                  //
######################################################


route("/", function () {
    json([
        "message" => "Welcome"
    ]);
});

route("id1/{/^[a-zA-Z0-9-_.]+$/}/id22/{:num}", function($id1, $id2){
    json([
        "id1" => $id1,
        "id2" => $id2
    ]);
});

route("user_name/{:alpha}/user_id/{:num}", function($name, $id){
    json([
        "user name" => $name,
        "user id" => $id
    ]);
});

route("page", function (){
    $data = [
        "email" => "example@gmail.com",
        "age" => 23
    ];
    view("page", $data);
});