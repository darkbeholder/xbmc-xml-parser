XBMC XML Library Parser Readme


1. Context and Purpose of Code

I wrote this code as I was looking for a simple way to generate an east to read list of all the movies and TV shows listed in my XMBC library to view online and include the path information for sharing. After sampling many different versions I decided to take a look at the library xml and see if I could just make something quick and easy myself. 

Once I had the initial code I expanded it to add some error checking at the start and a copyright line to the bottom of the generated html so it could be given to other people to use but it still hasn't ever been released.

2. Programming style 

This code reflects general standards that I always use when coding in php such as indenting control structures using 4 spaces and braces on their own lines.

3. What this code demonstrates

This code shows simple xml parsing and creating formatted output to files. This code also demonstrates code that can be run either from a browser or from the command line to achieve the same result. This code also demonstrates an anonymous callback function and use of built in constants for error checking and cross-platform compatibility.

4. How is the code organised and structured

This code is basically structured to do preliminary checks before loading and parsing an xml file. The code then loops through the parsed xml to generate the list of movies before looping through the parsed xml for tvshows. The code is entirely procedural and runs from start to finish.

5. Differences if the code was rewritten

If the code was going to be rewritten I would choose to go with an object oriented approach to have the movie and tvshow parsing moved to functions and also move the static html code to template files  to allow easy changing. 
