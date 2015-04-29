
<div class="wrap">
	<h2><?php echo $pageData[3];?> <a href="<?php echo admin_url('admin.php?page=justrows-add'); ?>" class="add-new-h2"><?php _e('Add new'); ?></a></h2>

	<form id="posts-filter" action="<?php echo admin_url('admin.php'); ?>" method="get">
    
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <label for="bulk-action-selector-top" class="screen-reader-text"><?php _e('Choose bulk action'); ?></label>
                <select name="action" id="bulk-action-selector-top">
                    <option value="-1" selected="selected"><?php _e('Bulk actions'); ?></option>
                    <option value="trash"><?php _e('Delete'); ?></option>
                </select>
                <input type="hidden" name="page" value="justrows-index">
                <input type="submit" id="doaction" class="button action" value="<?php _e('Apply'); ?>">
            </div>
            <br class="clear" />
        </div>
    
        <table class="wp-list-table widefat fixed posts" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col" id="cb" class="manage-column column-cb check-column" style="">
                        <label class="screen-reader-text" for="cb-select-all"><?php _e('Select all'); ?></label>
                        <input id="cb-select-all" type="checkbox">
                    </th>
                    <th scope="col" id="title" class="manage-column column-title sortable desc">
                        <span><?php _e('Title'); ?></span>
                    </th>
                    <th scope="col" id="slug" class="manage-column column-title sortable desc">
                        <span><?php _e('Slug'); ?></span>
                    </th>
                    <th scope="col" id="shortcode" class="manage-column column-title sortable desc">
                        <span><?php _e('Shortcode'); ?></span>
                    </th>
                    <th scope="col" id="postType" class="manage-column column-title sortable desc">
                        <span><?php _e('Post type', 'justrows'); ?></span>
                    </th>
                    <th scope="col" id="order" class="manage-column column-title sortable desc">
                        <span><?php _e('Order', 'justrows'); ?></span>
                    </th>
                    <th scope="col" id="limit" class="manage-column column-title sortable desc">
                        <span><?php _e('Limit', 'justrows'); ?></span>
                    </th>
                    <th scope="col" id="appendMethod" class="manage-column column-title sortable desc">
                        <span><?php _e('Append method', 'justrows'); ?></span>
                    </th>
                    <th scope="col" id="layout" class="manage-column column-title sortable desc">
                        <span><?php _e('Layout', 'justrows'); ?></span>
                    </th>		
                </tr>
            </thead>
            <tbody id="the-list">
                <?php foreach ($configs as $config) : ?>
                    <tr>
                        <th scope="row" class="check-column">
                            <label class="screen-reader-text" for="cb-select-<?php echo $config['slug']; ?>"><?php echo __('Select') . ' ' . $config['name']; ?></label>
                            <input id="cb-select-<?php echo $config['slug']; ?>" type="checkbox" name="configs[]" value="<?php echo $config['slug']; ?>">
                            <div class="locked-indicator"></div>
                        </th>
                        <td>
                            <strong><a class="row-title" href="<?php echo admin_url('admin.php?page=justrows-edit&slug='.$config['slug']); ?>" title=""><?php echo $config['name']; ?></a></strong>
                        </td>
                        <td>
                            <?php echo $config['slug']; ?>
                        </td>
                        <td>
                            <pre><?php echo '[justrows slug="', $config['slug'] ,'"]'; ?></pre>
                        </td>
                        <td>
                            <?php echo $config['postType']; ?>
                        </td>
                        <td>
                            <?php
                            	echo $config['orderBy'];
								if ( !empty($config['orderByFieldName']) )
									echo ' (', $config['orderBy'], ')';
								echo ' ', $config['order']; ?>
                        </td>
                        <td>
                            <?php echo $config['postsLimit']; ?>
                        </td>
                        <td>
                            <?php 
                                switch ($config['appendMethod']) {
                                    case 'button':
                                        _e('Add button', 'justrows'); 
                                        break;
                                    case 'infinite-scrolling':
                                        _e('Infinite scrolling', 'justrows');
                                        break;
                                }
                                
                            ?>
                        </td>
                        <td>
                            <?php echo $config['layout']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

	</form>

	<?php if( current_user_can('install_plugins') )	{ ?>
    	<iframe class="justrows-pro-banner" src="http://canaveralstudio.com/justrows-plugin.html"></iframe>
	<?php } ?>

</div>
