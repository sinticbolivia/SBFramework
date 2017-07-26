<?php
?>
<div class="wrap">
	<h2><?php print $title; ?></h2>
	<form action="" method="post">
		<input type="hidden" name="mod" value="content" />
		<input type="hidden" name="task" value="categories.save" />
		<?php if( isset($category) ): ?>
		<input type="hidden" name="category_id" value="<?php print $category->category_id; ?>" />
		<?php endif; ?>
		<div class="form-group">
			<label class="has-popover" data-content="<?php print SBText::_('SECTION_TITLE'); ?>">
				<?php print SB_Text::_('Name', 'content'); ?></label>
			<input type="text" name="category_name" value="<?php print SB_Request::getString('category_name', isset($category) ? $category->name : ''); ?>" 
						class="form-control" maxlength="40" />
		</div>
		<div class="form-group">
				<label class="has-popover" data-content="<?php print SBText::_('SECTION_DESCRIPTION'); ?>">
					<?php _e('Description', 'content'); ?>
				</label>
				<textarea id="description" name="description" class="form-control"><?php print SB_Request::getString('description', isset($category) ? $category->description : ''); ?></textarea>
		</div>
		<div class="form-group">
			<label class="has-popover" data-content="<?php print SBText::_('CATEGORY_PARENT'); ?>"><?php _e('Category Parent:', 'content'); ?></label>
			<?php print sb_categories_dropdown(array('id' => 'parent_id', 'selected' => isset($category) ? $category->parent_id : -1)); ?>
		</div>
		<div class="form-group">
			<label><?php _e('Language', 'content'); ?></label>
			<select name="lang" class="form-control">
  				<?php foreach(SB_Factory::getApplication()->GetLanguages() as $code => $lang):?>
  				<option value="<?php print $code; ?>" <?php print @LANGUAGE == $code ? 'selected' : ''; ?>>
  					<?php print $lang; ?>
  				</option>
  				<?php endforeach; ?>
  			</select>
		</div>
		<?php SB_Module::do_action('categories_fields', isset($category) ? $category : null); ?>
		<p>
			<a class="btn btn-secondary has-popover" href="<?php print SB_Route::_('index.php?mod=content&view=categories.default'); ?>"
				data-content="<?php print SBText::_('SECTION_BUTTON_CANCEL'); ?>">
				<?php print SB_Text::_('Cancel', 'content'); ?></a>
			<button type="submit" class="btn btn-secondary has-popover" data-content="<?php print SBText::_('SECTION_BUTTON_SAVE'); ?>">
				<?php print SB_Text::_('Save', 'content'); ?></button>
		</p>
	</form>
</div>