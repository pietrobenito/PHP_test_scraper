<?php
header('Content-Type: application/json');
// This is a template for a PHP scraper on morph.io (https://morph.io)
// including some code snippets below that you should find helpful

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/openaustralia/scraperwiki/scraperwiki.php';
require __DIR__ . '/vendor/openaustralia/scraperwiki/simple_html_dom.php';

$url = 'https://scholar.google.co.uk/scholar?hl=en&as_sdt=1,5&as_vis=1&q=%22Copyright+Legislation%22&scisbd=1';
// // Read in a page
$html = scraperwiki::scrape($url);
//
// // Find something on the page using css selectors
$dom = new simple_html_dom();
$dom->load($html);
// print_r($dom->find('div.gs_rs', 0));
$posts = [];
$i = "0";
foreach ($dom->find("h3.gs_rt a") as $ln) {
    // print $ln . "<hr>\n";
    $posts[$i]['title'] = utf8_encode($ln->innertext);
    $i++;
}
$i="0";
foreach ($dom->find("h3.gs_rt a") as $ln) {
    // print $ln . "<hr>\n";
    $posts[$i]['link'] = utf8_encode($ln->href);
    $i++;
}
$i="0";
foreach ($dom->find("div.gs_a") as $ln) {
    // print $ln . "<hr>\n";
    $posts[$i]['author'] = utf8_encode($ln->innertext);
    $i++;
}
$i="0";
foreach ($dom->find("div.gs_rs") as $ln) {
    // print $ln . "<hr>\n";
    // $escapedText = htmlspecialchars($ln->innertext);
    $posts[$i]['details'] = utf8_encode($ln->innertext);
    $i++;
}

$json = json_encode($posts, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK, 2);

echo $json;

//
// // Write out to the sqlite database using scraperwiki library
// scraperwiki::save_sqlite(array('name'), array('name' => 'susan', 'occupation' => 'software developer'));
//
// // An arbitrary query against the database
// scraperwiki::select("* from data where 'name'='peter'")

// You don't have to do things with the ScraperWiki library.
// You can use whatever libraries you want: https://morph.io/documentation/php
// All that matters is that your final data is written to an SQLite database
// called "data.sqlite" in the current working directory which has at least a table
// called "data".
?>
