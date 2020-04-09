<?php 
global $nokri;
if ( is_active_sidebar( 'search_sidebar' ) ){ ?>
<aside class="new-sidebar side-filters">
<div class="heading">
<h4> <?php  echo esc_html__("Search Filters", "nokri"); ?></h4>
<a href="<?php  echo get_the_permalink($nokri['sb_search_page']); ?>"> Clear All</a>
<?php if(wp_is_mobile()){ ?>
<a role="button" class="" data-toggle="collapse" href="#accordion" aria-expanded="true" id="panel_acordian"></a>
<?php }?>
</div>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<?php dynamic_sidebar('search_sidebar'); ?>   
</div>  
</aside>
<?php }  ?>