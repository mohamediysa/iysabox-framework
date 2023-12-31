# iysabox-framework
a simple and lightweight php framework for building apis<br>
<pre>1. clone the files into your dir
    git clone https://github.com/mohamediysa/iysabox-framework.git</pre>
<br>
<pre>2. localhost/your-dir-name</pre>
<br>
<pre>
3. open app/routes.php and start adding your routes <br>
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
<b>more</b>
{:num} ONLY NUMS
{:alpha} ONLY ALPHABETS
{:alpha_num} NUMS AND ALPHABETS
{:num_hyphen} NUM WITH -
{:alpha_hyphen} ALPHA WITH -
{:alpha_num_hyphen} ALPHA & NUM WITH -
{:num_dash} NUM WITH _
{:alpha_dash} ALPHA WITH _
{:alpha_num_dash} ALPHA NUM WITH _
{:num_hyphen_dash} NUM WITH - AND _
{:alpha_hyphen_dash} ALPHA WITH - AND _
{:alpha_num_hyphen_dash} ALPHA NUM WITH - AND _

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
the view function use to view an html page<br>
note : all html should go in html directory<br>
you can pass an array of data as a second param<br>
ex:<br>
$data = [
    "name" => "iysa"
];<br>
view("page",$data)<br>
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
get(string $param, bool $escape = true)
</pre>
return a get request ex:<br>
get("id", false)<br>
it will return the value of id<br>
set it to true for esaping html tags<br>
<br>
<br>
<pre>
post(string $param, bool $escape = true)
</pre>
return a post request ex:<br>
post("id", false)<br>
it will return the value of id<br>
set it to true for esaping html tags<br>
<br>
<br>
<pre>
all_request(bool $escape = true)
</pre>
return an array of all requests ex:<br>
all_request(false)<br>
set it to true for esaping html tags<br>
