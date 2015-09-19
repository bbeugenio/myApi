# my first experience using php

Hi everyone, I made a service that shows a JSON with many information about a Location of a photo uploaded to Instagram. e.g. Latitude, Longitude, Address, place's Name, and a static Map of the photo.
Then its shows some nearest places, giving the same information. The maps are a little bit differnt here. They mark the location of the photo on red and the neareast place with green showing the road between them.
Also we have another map. This is a general map that shows the location on the photo(red point) and all the nearest places with green.

####How do I run it?
After download the project's last version[release](https://github.com/bbeugenio/myApi/archive/master.zip), run the following commands to install the php dependencies, import some data, and run a local php server.

You need at least php **5.4.** and **Composer**
    
    composer install 
    php -S localhost:9001 -t web/
    
Your api is now available at http://localhost:9001/myApi/

####Unit tests
This is also my first experience using phpunit. I made some tests and all the Services and Controllers classes are fully tested.

From the root folder run the following command to run tests.
    
    vendor/bin/phpunit 


####What you will get
The api will respond to
	
	GET	->	http://localhost:9001/myApi/photo/{id}


####What's under the hood

Take a look at the source code and if you have some questions ,comments or any advice let me know and I will answer you.


####Author

+	<mailto:bertonibrunoeugenio@gmail.com>

