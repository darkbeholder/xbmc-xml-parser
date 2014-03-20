xbmc-xml-parser
===============

__Basic Description__

Generates HTML pages with clean listings of each movie and tv series that exist in your library

__Context and Purpose of Code__

I wrote this code as I was looking for a simple way to generate an easy to read list of all the movies and TV shows listed in my XMBC library to view online and include the path information for sharing. After sampling many different versions I decided to take a look at the library xml and see if I could just make something quick and easy myself. 

Once I had the initial code I expanded it to add some error checking at the start and a copyright line to the bottom of the generated html so it could be given to other people to use

__Basic Use__

1. Export your XBMC library to a single file (videodb.xml)
2. Place the library file in the same directory as the parser
3. Run xbmc_parser.php either from the commandline or through a web browser
4. Check the 2 new files movies.html and tvshows.html
