Email notifications when new ads are published
----------------------------------------------

This code used to live at http://lebonmail.com which has now been blocked by http://leboncoin.fr

It enabled users to receive email notifications as soon as a new ad corresponding to their search was published.

Install this code on any php compatible web host to use it for yourself

Set a cron job to run every ten minutes (or whichever update inerval you choose) to call the `/all_searches` URL with `wget` or `curl`
For example : 

    */10 * * * * wget http://lebonmail.local/all_searches
