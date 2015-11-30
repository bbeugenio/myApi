# My first experience using php

Hi everyone, I made a service that shows a JSON with many information about a Location of a photo uploaded to Instagram. e.g. Latitude, Longitude, Address, place's Name, and a static Map of the photo.
Then its shows some nearest places, giving the same information. The maps are a little bit different here. They mark the location of the photo on red and the neareast place with green showing the road between them.
Also we have another map. This is a general map that shows the location on the photo(red point) and all the nearest places with green.

####How do I run it?
After download the project's last version [here](https://github.com/bbeugenio/myApi/archive/master.zip), run the following commands to install the php dependencies, import some data, and run a local php server.

You need at least php **5.4.** and [Composer](https://getcomposer.org)
    
    composer install 
    php -S localhost:9001 -t web/
    
Your api is now available at http://localhost:9001/media/

####Unit tests
This is also my first experience using [phpunit](https://github.com/sebastianbergmann/phpunit) and [mockery](https://github.com/padraic/mockery). I made some tests, not all the classes are tested, only a few and some services like Instagram and Google Services.

From the root folder run the following command to run tests.
    
    vendor/bin/phpunit 


####What you will get
The api will respond to
	
	GET	->	http://localhost:9001/media/{photo_id}?instagram_token=INSTAGRAM_TOKEN
	GET	->	https://instagram.com/oauth/authorize/?client_id=5fa500be04134056ab745cc48cf0382f&redirect_uri=http://localhost:9001/token_info&response_type=token

The second GET is to generate a new access token from instagram. This allow access to your Instagram's photos, in case that they aren't private.


####Contributing

Take a look at the source code and if you have some questions ,comments or any advice let me know and I will answer you.


####Author

+	<mailto:bertonibrunoeugenio@gmail.com>

