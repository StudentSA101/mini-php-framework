# I created this markdown file in order to describe how the application works and my thoughts in developing it

I created this structure of the application myself, no framework used, and based some of the files on previous projects that I worked on. I adhered to SOLID as much as the time justified.  I used a lot of the packages helper static calls instead of injecting dependecies simply for speed. I fortunately have previously created a rudimentary router and incorporated it in this project. 

My main concern was the scaling and to find a package that would work independetly of a framework. The difficult part was reverse engineering a lot of the packages as there is not a lot of documentation on this topic. For instance, the standalone queue package from laravel.  I attempted to use gearman but figured that it is a too old and the test requests that the best tools for the job is used. 

I wanted to use a package called resque but the latest package does not conform to ps4 standards and did not work as documented.  I then looked at the symfony package called enqueue.  Although there is no tutorials on this package it is relatively well documented.  I created a rudimentary queue.  The package has the potential to connect with a host of different queue drivers and seems to be the best maintained package available apart from laravel Queues.  

I chose all the packages that I used based on whether they are well maintained and documented to a usable degree. 

These are some of the nice features of enqueue:

receive and delete delivery: the queue deletes the message when it’s fetched by consumer. If processing fails, then the message is lost and won’t be processed again. This is called at most once processing.
peek and lock delivery: the queue locks for a short amount of time a message when it’s fetched by consumer, making it invisible to other consumers, in order to prevent duplicate processing and message lost. If there is no acknowledgment before the lock times out, failure is assumed and then the message is made visible again in the queue for another try. This is called at least once processing.
an explicit acknowledgement: the queue locks a message when it’s fetched by consumer, making it invisible to other consumers, in order to prevent duplicate processing and message lost. If there is no explicit acknowledgment received before the connection is closed, failure is assumed and then the message is made visible again in the queue for another try. This is called at least once processing.
message delivery delay: messages are sent to the queue but won’t be visible right away to consumers to fetch them. You may need it to plan an action at a specific time.
message expiration: messages could be dropped of a queue within some period of time without processing. You may need it to not process stale messages. Some transports do not support the feature.
message priority: message could be sent with higher priority, therefor being consumed faster. It violates first in first out concept and should be used with precautions. Some transports do not support the feature.
first in first out: messages are processed in the same order than they have entered the queue.

The idea around the queues is to load up the queue in redis with all the relevant data and then to spin up instances of the application that can process the load.  

The application works as follows: 

```
Composer install (a few extensions might be required in php.ini)
```

1.  Serve application from public file to bootstrap application.  Route files contain description of routes and those requested in test file. 
2.  To lanch the shell type php shell. This will display options.  The one needed to import data into the database is listed.

Things I considered: 

1.  I imported the data into a mysql database by use of a terminal command.
2.  I saved the date and time in a single time zone using the time column for this.
3.  I validated the email domain using a simple method, recorded whether it is a valid domain and saved the IP address of the queried domain in the database.
4.  I created a contact card jpeg image using unsplash.com and inserted the name, surname and email address as requested. 
5.  I made available the data to the relevant endpoints as json and the images are served from the root domain. 
6.  I created two endpoints known as contacts and time-zones. (check the routes for there location)
7.  I paginated the contacts endpoint. I allowed the user to query timezone using the query name timezone={timezone}. The data is structured as requested
8.  The script is duplicate safe and only runs once.