<?php /* Smarty version 2.5.0, created on 2003-04-23 15:36:57
         compiled from tutorial_tree.tpl */ ?>
<?php $this->_load_plugins(array(
array('modifier', 'strip_tags', 'tutorial_tree.tpl', 1, false),)); ?>	var <?php echo $this->_tpl_vars['name']; ?>
node = new WebFXTreeItem('<?php echo $this->_run_mod_handler('strip_tags', true, $this->_tpl_vars['main']['title']); ?>
','<?php echo $this->_tpl_vars['main']['link']; ?>
', parent_node, 
																			'media/images/tutorial.png', 'media/images/tutorial.png');

<?php if ($this->_tpl_vars['haskids']): ?>
  var <?php echo $this->_tpl_vars['name']; ?>
_old_parent_node = parent_node;
	parent_node = <?php echo $this->_tpl_vars['name']; ?>
node;
	<?php echo $this->_tpl_vars['kids']; ?>

	parent_node = <?php echo $this->_tpl_vars['name']; ?>
_old_parent_node;
<?php endif; ?>