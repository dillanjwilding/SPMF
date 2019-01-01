# Simple PHP MVC Framework (SPMF)

This is the foundation of a basic MVC framework written in PHP I created 2013 for educational purposes while learning about MVC frameworks back when I was studying at the University of Vermont. Since then I've updated the code a little bit to improve the repository's quality but it still shouldn't be used in a production environment.

## Using

### Setup / Running

This was built to be a drop in solution where you copy the framework and app structure and start modifying the app and for the most part that hasn't changed but now I've added some conveniences like Docker and plan to do helper scripts.

#### Automatic Via Docker

First, install Docker. Then use the cd command to change directories to where this project is located and run docker-compose up --build from the terminal. This will build an image/container linking to the current directory so you can make modifications, most of which won't require you restart any services or your container.

#### Manual

I'll link to a blog post when I've written one up how to set up a LAMP stack but for now, I'll assume you've installed Apache2, PHP 7.0+, and some features/functionality requires MySQL/MariaDB on Ubuntu 18.04 or some Unix based system.

Download this project to a directory. Move the contents of the web directory to /var/www. We'll be pointing Apache2 to serve these PHP files. Then copy apache.conf from the docker directory to an Apache2 sites-available (i.e. usually /etc/apache2/sites-available -- and you can rename it to 000-default.conf or anything else if you'd like), point it to where you've placed this project's public directory (e.g. /var/www/public), and run a2ensite 000-default.conf, or whatever you decided to name it, in the directory you placed the conf file in. After modifying the Apache2 configuration, you'll need to reload and/or restart Apache2 using the sudo service apache2 reload and sudo service apache2 restart commands respectively.

### Building an App/Site

To build an app/site you'll be adding to the web/app directory which will either be mapped to /var/www/app if you used the docker files or moved there if you used the manual approach. You'll find a basic example of a Model, View, and Controller in that directory. A controller, let's call it TestController, will have public action methods. 

## Fundamentals

### Routing

`domain.com/<controller>/<action>` and both controller and action default to "index".

## Recently Added

Recently I've decided to "dockerize" the project. The container might be a little bloated but I tried to make it reflect the server I host code on so I'd be familiar with the environment to limit the variables while developing. It helps to get up and running quickly and have CI/CD pipelines auto-deploy projects. 

## History / Development

I started the project, with very little MVC framework experience, as an assignment while I was studying Computer Science at the University of Vermont to familiarize myself with MVC principles and implementation decisions. After the assignment was done I mothballed the project until I found people asking fundamental questions on social media sites like Reddit. I thought this project could serve as a good introduction for beginners because unlike other frameworks, it's basic by design and forgoes some more fancy features so it's less like drinking from a firehose so hopefully they'll be less likely to be overwhelmed. 

So I've periodically been updating it minimally to help inquiring individuals and provide them with answers to their questions with an example. Until this past year there was no method to the madness but in an effort to work on open source projects and help the community on a regular basis rather than when it's convenient, I've updated the code, made it easier to use, and hopefully do well to maintain my open source repositories more actively.

## Future Development

I still have some bugs to fix and a bit I want to clean up, reimplement, and some feature I want to add. I have limited time but I'm still trying to keep it relavent as a teaching/learning tool so please feel free to reach out, request help or features through the ticketing system. And if you feel inspired, please don't hesitate to contribute, just submit a pull/merge request. 

### To Do

*  Finish the Active Record Database Models.
*  More configurable options (custom routes, etc.).
*  Remove deprecated code.
*  Document code better.