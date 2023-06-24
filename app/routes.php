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
        "message" => "Welcome to  iysabox"
    ]);
});

route("/api/{:num}", function($id){
    json([
        "id" => $id
    ]);
});