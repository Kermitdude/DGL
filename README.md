# DGL
DGL website - Firefly version

The Firefly version employs my own take on the MVC structure. I opted not to use any pre-existing frameworks, as they only hindered me.
Therefore, there is no available API to explain the inner workings of the website. I will take a moment to do so.

Each page follows a very strange flow pattern; I say strange, because it's a hybrid of the conventional Front Controller motif so pervasive in PHP scripting,
with the addition of Single Page Application (SPA) moiety. It uses AJAX to effect the changes in each page, so the end-user does not experience page
changes in the conventional fashion. A page load follows this pipeline:

- Index.php launches
-- Bootloader.php is loaded, which primes the site
-- Install.php is loaded, if necessary
-- Theme is loaded
-- AppController.php is loaded
- Theme loads dgl.js
- dgl.js sends request to AppController.php
- AppController.php sends request to the specified Controller
- The Controller gets information and includes the intended View
- AppController sends response back to dgl.js, which loads into the #content div on the main page

Yikes! When I type it out like that, it seems very cumbersome indeed. I could have easily bypassed half of that by simply sending an AJAX request to specific pages,
but there is added security in having a front controller reroute to pages not accessible by the client.

#Technologies

Modules/scripts/packages used:

Pace.js (shows a loading bar on AJAX requests)
Gridster.js (shows a drag-drop grid)
