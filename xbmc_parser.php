<?php
/**
 * A simple xml parser to convert xbmc library xml file into a page of movies and a page of tv shows listed 
 * alphabetically by title. The titles also link to either imdb (movies) or thetvdb (tv shows)
 *  
 * Copyright (C) 2012  Nick Mather
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */  
  
const LIBRARY = 'videodb.xml';
 
//Check that the required library file exists
if (!file_exists(LIBRARY))
{
    die('Unable to locate ' . LIBRARY . '. Please ensure that the file is in the same directory as the php script for processing and is readable' . PHP_EOL);
}
//Script requires php 5.0 or greater
if (strnatcmp(phpversion(),'5.0.0') < 0)
{
    die('PHP Version 5.0.0 or greater is required to run this script. You are running version ' . phpversion() . PHP_EOL);
}
//Check for SimpleXML library is enabled
if (!function_exists('simplexml_load_file'))
{
    die('The SimpleXML library is required to read the XBMC library file' . PHP_EOL);
}
//Check if we support multibyte strings
if (function_exists('mb_internal_encoding'))
{
    //We support multibyte strings so set encoding to UTF-8
    mb_internal_encoding("UTF-8"); 
}

//Determine if running from command line 
$cli = false;
if (PHP_SAPI == 'cli')
{
    $cli = true;
}

$xml = simplexml_load_file(LIBRARY);

$movies = $shows = array();

//Loop through the movie list and build an array
foreach ($xml->movie as $movie)
{
    $key = "{$movie->id}";
    $movies[$key] = array(
        'title' => $movie->title,
        'id'    => $movie->id,
	'path'  => $movie->filenameandpath,
    );
}
//Sort the movies by Title
uasort($movies, function($a, $b){ return strcasecmp($a['title'], $b['title']); });

//Loop through TV shows
foreach ($xml->tvshow as $show)
{
    if ($show->episode < 1)
    {
        //TV Show has no episodes so skip it
        continue;
    }
    $key = "{$show->showtitle}";
    if (array_key_exists($key, $shows))
    {
        //Sometimes show series are stored in sepearate locations so we just need to increment the episode count and list the extra path
        $shows[$key]['episodes'] = (int)$shows[$key]['episodes'] + $show->episode;
        $shows[$key]['path']	 = $shows[$key]['path'] . '<br />' . $show->path;
    }
    else
    {
        $shows[$key] = array(
            'title'     => $show->showtitle,
            'id'        => $show->id,
            'episodes'  => $show->episode,
            'path'      => $show->path
        );
    }
}
//Sort the shows by Title
ksort($shows);

$movies_html = <<<EOF
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Movies</title>
</head>
<body>
<table border="1" cellpadding="1" cellspacing="0">
    <tr>
        <th>Index</th>
        <th>Title</th>
        <th>Path</th>
        <th>IMDB Link</th>
    </tr>
EOF;
$count = 0;
foreach ($movies as $movie)
{
    $count++;
    $movies_html .= <<<EOF

<tr>
    <td>{$count}</td>
    <td>{$movie['title']}</td>
    <td>{$movie['path']}</td>
    <td><a href="http://www.imdb.com/title/{$movie['id']}">{$movie['id']}</a></td>
</tr>
EOF;
}

$movies_html .= <<<EOF

</table>
<div style="text-align: center;">Created by Nick's XMBC Library Parser &copy;2012</div>
</body>
</html>
EOF;
file_put_contents('movies.html', $movies_html);
if ($cli)
{
    echo 'created movies.html' . PHP_EOL;
}
else
{
    echo 'created <a href="movies.html">movies.html</a><br />';
}
$tv_html = <<<EOF
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>TV Shows</title>
</head>
<body>
<table border="1" cellpadding="1" cellspacing="0">
    <tr>
        <th>Index</th>
        <th>Title</th>
        <th>Episodes</th>
        <th>Path</th>
        <th>TheTVDB Link</th>
    </tr>
EOF;
$count = 0;
foreach ($shows as $show)
{
    $count++;
    $tv_html .= <<<EOF
	
    <tr>
        <td>{$count}</td>
        <td>{$show['title']}</td>
        <td>{$show['episodes']}</td>
        <td>{$show['path']}</td>
        <td><a href="http://www.thetvdb.com/?tab=series&id={$show['id']}">{$show['id']}</a></td>
    </tr>
EOF;
}
$tv_html .= <<<EOF

</table>
<div style="text-align: center;">Created by Nick's XMBC Library Parser &copy;2012</div>
</body>
</html>
EOF;

file_put_contents('tvshows.html', $tv_html);
if ($cli)
{
    echo 'created tvshows.html' . PHP_EOL;
}
else
{
    echo 'created <a href="tvshows.html">tvshows.html</a>';
}
