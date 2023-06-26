<?php

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

route("/id/{:num}/user/{:alpha}/page/{/[a-z]/}", function($id, $user, $page){
    json([
        "id" => $id,
        "user" => $user,
        'page' => $page
    ]);
});