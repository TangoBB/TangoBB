<?php
 global $TANGO;
 global $MYSQL;
 


              $return = $TANGO->tpl->entity(
                  'forum_listings_category',
                  array(
                      'category_name',
                      'category_desc',
                      'category_forums'
                  ),
                  array(
                      'Blah',
                      'Bleh',
                      'Bluh'
                  )
              );
 


$TANGO->tpl->addParam('shoutbox', $return);

 ?>