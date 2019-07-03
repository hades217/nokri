<?php 
global $nokri;
if ( is_active_sidebar( 'search_sidebar' ) ){ ?>
<aside class="new-sidebar side-filters">
<div class="heading">
<h4> <?php  echo esc_html__("Search Filters", "nokri"); ?></h4>
<a href="<?php  echo get_the_permalink($nokri['sb_search_page']); ?>"> Clear All</a>
</div>
<?php dynamic_sidebar('search_sidebar'); ?>                   
</aside>
<?php }  ?>