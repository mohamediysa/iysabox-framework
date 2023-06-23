# iysabox-framework
a simple and lightweight php router/framework for building apis and small webpages<br>
<pre>1. clone the files into your dir
    git clone https://github.com/mohamediysa/iysabox-framework.git</pre>
<br>
<pre>2. localhost/your-dir-name</pre>
<br>
<pre>
3. open routes.php and add your routes <br>
route("path", callback)
</pre>
<br>
examples :
<pre>
route("page", function () {
    $data = [
        "email" => "example@gmail.com",
        "age" => 25
    ];
    view("page", $data); // html/page.php | pass data to a view file
});
</pre>


<pre>
route("page/user", function () {
    view("user"); // html/user.php
});
</pre>

<pre>
route("page/{id}", function ($id) {
    $data = [
        "parametre" => $id,
        "all_requests" => all_request(false),
        "get" => get("q")
    ];
    json($data, 200); //print data as a json with a status code
});

<a>localhost/iysabox/page/1?q=query&name=iysa</a>
<b>output:</b> 
{
    "parametre" : "1",
    "all" : [
        q : "query"
         name : "iysa"
     ],
      "get" : {
          "q":"query"
       }
}
</pre>
<pre>
<b>only nums</b> 
route("users/{:num}", function ($id) {
    $data = [
        "parametre 1" => $id
    ];
    json($data);
});
</pre>
<pre>
<b>only alpha</b>
route("users/{:alpha}", function ($id) {
    $data = [
        "parametre 1" => $id
    ];
    json($data);
});
</pre><pre>
<b>alpha and nums</b>
route("users/{:alpha_num}", function ($id) {
    $data = [
        "parametre 1" => $id
    ];
    json($data);
});
</pre>

<pre>
<b>using regex, one param</b>
route("page/{/^[0-9]+$/}", function ($id) {
    $data = [
        "id" => $id,
    ];
    json($data);
});
</pre>

<pre>
<b>using regex, multi params</b>
route("page/{/^[a-zA-Z]+$/}/user/{/^[0-9]+$/}", function ($p1, $p2) {
    $data = [
        "parametre 1" => $p1,
        "parametre 2" => $p2
    ];
    json($data);
});
</pre>
<pre>
route("page/{/^[a-zA-Z]+$/}/user/{:alpha}", function ($id, $id2) {
    $data = [
        "parametre 1" => $id,
        "parametre 2" => $id2
    ];
    json($data);
});
</pre>

<h3>Helper functions : </h3>
<pre>
view(string $page_path, array $data = [])
</pre>
the view function use to view an html page
note : all html should go in html directory
you can pass an array of data as a second param
ex:
$data = [
    "name" => "iysa"
];
view("page",$data)
in the html/page.php you can use the $name variable to get its value
<br>
<pre>
json(array $data, int $status_code = 200) // echo array as json
</pre>
<pre>
error404() // echo a json 404 error with 404 header
</pre>
<pre>
br() // return < br > tag 
pre() // return < pre > tag 
</pre>
<b>Handle requests</b>
<pre>
<i>get(string $param, bool $escape = true)</i>
</pre>
return a get request ex:
get("id", false)
it will return the value of id
set it to true for esaping html tags
<br>
<pre>
<i>post(string $param, bool $escape = true)</i>
</pre>
return a post request ex:
post("id", false)
it will return the value of id
set it to true for esaping html tags
<br>
<pre>
<i>all_request(bool $escape = true)</i>
</pre>
return an array of all requests ex:
all_request(false)
set it to true for esaping html tags