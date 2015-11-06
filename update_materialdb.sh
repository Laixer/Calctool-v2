#!/bin/bash
wget http://www.bouwmaat.nl/is-bin/INTERSHOP.enfinity/WFS/org-webshop-Site/nl_NL/-/EUR/ViewSoftware-DownloadXMLFile -O material.xml
php ./artisan material:import material.xml
rm material.xml
