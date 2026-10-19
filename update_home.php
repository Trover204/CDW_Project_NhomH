<?php
$content = file_get_contents('/app/cdw_stadium_project/app/Http/Controllers/HomeController.php');
if (strpos($content, '$sportTypes') === false) {
    $content = str_replace(
        "return view('web.home', compact('courts'));",
        "\$sportTypes = \\App\\Models\\SportType::where('status', 1)->take(3)->get();\n        \$locations = \\App\\Models\\Facility::where('status', 1)->select('address')->distinct()->take(3)->get();\n        return view('web.home', compact('courts', 'sportTypes', 'locations'));",
        $content
    );
    file_put_contents('/app/cdw_stadium_project/app/Http/Controllers/HomeController.php', $content);
    echo "HomeController updated\n";
} else {
    echo "Already updated\n";
}
