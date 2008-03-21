<?php /* Smarty version 2.5.0, created on 2003-07-26 23:45:06
         compiled from left_frame.tpl */ ?>
<?php $this->_load_plugins(array(
array('modifier', 'strip_tags', 'left_frame.tpl', 122, false),)); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("header.tpl", array('top2' => true));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<h3 class="package-title"><?php echo $this->_tpl_vars['info']['0']['package']; ?>
</h3>
<div class="tree">
<script language="Javascript">
if (document.getElementById) {
<?php if (isset($this->_sections['p'])) unset($this->_sections['p']);
$this->_sections['p']['name'] = 'p';
$this->_sections['p']['loop'] = is_array($this->_tpl_vars['info']) ? count($this->_tpl_vars['info']) : max(0, (int)$this->_tpl_vars['info']);
$this->_sections['p']['show'] = true;
$this->_sections['p']['max'] = $this->_sections['p']['loop'];
$this->_sections['p']['step'] = 1;
$this->_sections['p']['start'] = $this->_sections['p']['step'] > 0 ? 0 : $this->_sections['p']['loop']-1;
if ($this->_sections['p']['show']) {
    $this->_sections['p']['total'] = $this->_sections['p']['loop'];
    if ($this->_sections['p']['total'] == 0)
        $this->_sections['p']['show'] = false;
} else
    $this->_sections['p']['total'] = 0;
if ($this->_sections['p']['show']):

            for ($this->_sections['p']['index'] = $this->_sections['p']['start'], $this->_sections['p']['iteration'] = 1;
                 $this->_sections['p']['iteration'] <= $this->_sections['p']['total'];
                 $this->_sections['p']['index'] += $this->_sections['p']['step'], $this->_sections['p']['iteration']++):
$this->_sections['p']['rownum'] = $this->_sections['p']['iteration'];
$this->_sections['p']['index_prev'] = $this->_sections['p']['index'] - $this->_sections['p']['step'];
$this->_sections['p']['index_next'] = $this->_sections['p']['index'] + $this->_sections['p']['step'];
$this->_sections['p']['first']      = ($this->_sections['p']['iteration'] == 1);
$this->_sections['p']['last']       = ($this->_sections['p']['iteration'] == $this->_sections['p']['total']);
?>
	<?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['subpackage'] == ""): ?>
		var tree = new WebFXTree('<span class="package"><?php echo $this->_tpl_vars['info']['0']['package']; ?>
</span>');
		tree.setBehavior('classic');
		tree.openIcon = 'media/images/package.png';
		tree.icon = 'media/images/package.png';
	
		<?php if ($this->_tpl_vars['hastodos']): ?>
			var todos = new WebFXTreeItem('To-do List', '<?php echo $this->_tpl_vars['todolink']; ?>
');
			todos.openIcon = 'media/images/Index.png';
			todos.icon = 'media/images/Index.png';
			tree.add(todos);
		<?php endif; ?>

		var class_trees = new WebFXTreeItem('Class trees', '<?php echo $this->_tpl_vars['classtreepage']; ?>
.html');
		class_trees.openIcon = 'media/images/Index.png';
		class_trees.icon = 'media/images/Index.png';
		tree.add(class_trees);

		var elements = new WebFXTreeItem('Index of elements', '<?php echo $this->_tpl_vars['elementindex']; ?>
.html');
		elements.openIcon = 'media/images/Index.png';
		elements.icon = 'media/images/Index.png';
		tree.add(elements);

		var parent_node;

		<?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']): ?>
			var tree_tutorial = new WebFXTreeItem('Tutorial(s)/Manual(s)', '');
			tree_tutorial.openIcon = 'media/images/tutorial_folder.png';
			tree_tutorial.icon = 'media/images/tutorial_folder.png';
			tree.add(tree_tutorial);
			
			<?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['pkg']): ?>
				var tree_inner_tutorial = new WebFXTreeItem('Package-level', '');
				tree_inner_tutorial.openIcon = 'media/images/package_folder.png';
				tree_inner_tutorial.icon = 'media/images/package_folder.png';
				tree_tutorial.add(tree_inner_tutorial);
				
				parent_node = tree_inner_tutorial;
				<?php if (isset($this->_sections['ext'])) unset($this->_sections['ext']);
$this->_sections['ext']['name'] = 'ext';
$this->_sections['ext']['loop'] = is_array($this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['pkg']) ? count($this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['pkg']) : max(0, (int)$this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['pkg']);
$this->_sections['ext']['show'] = true;
$this->_sections['ext']['max'] = $this->_sections['ext']['loop'];
$this->_sections['ext']['step'] = 1;
$this->_sections['ext']['start'] = $this->_sections['ext']['step'] > 0 ? 0 : $this->_sections['ext']['loop']-1;
if ($this->_sections['ext']['show']) {
    $this->_sections['ext']['total'] = $this->_sections['ext']['loop'];
    if ($this->_sections['ext']['total'] == 0)
        $this->_sections['ext']['show'] = false;
} else
    $this->_sections['ext']['total'] = 0;
if ($this->_sections['ext']['show']):

            for ($this->_sections['ext']['index'] = $this->_sections['ext']['start'], $this->_sections['ext']['iteration'] = 1;
                 $this->_sections['ext']['iteration'] <= $this->_sections['ext']['total'];
                 $this->_sections['ext']['index'] += $this->_sections['ext']['step'], $this->_sections['ext']['iteration']++):
$this->_sections['ext']['rownum'] = $this->_sections['ext']['iteration'];
$this->_sections['ext']['index_prev'] = $this->_sections['ext']['index'] - $this->_sections['ext']['step'];
$this->_sections['ext']['index_next'] = $this->_sections['ext']['index'] + $this->_sections['ext']['step'];
$this->_sections['ext']['first']      = ($this->_sections['ext']['iteration'] == 1);
$this->_sections['ext']['last']       = ($this->_sections['ext']['iteration'] == $this->_sections['ext']['total']);
?>
					<?php echo $this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['pkg'][$this->_sections['ext']['index']]; ?>

				<?php endfor; endif; ?>
			<?php endif; ?>
			
			<?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['cls']): ?>
				var tree_inner_tutorial = new WebFXTreeItem('Class-level', '');
				tree_inner_tutorial.openIcon = 'media/images/class_folder.png';
				tree_inner_tutorial.icon = 'media/images/class_folder.png';
				tree_tutorial.add(tree_inner_tutorial);
				
				parent_node = tree_inner_tutorial;
				<?php if (isset($this->_sections['ext'])) unset($this->_sections['ext']);
$this->_sections['ext']['name'] = 'ext';
$this->_sections['ext']['loop'] = is_array($this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['cls']) ? count($this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['cls']) : max(0, (int)$this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['cls']);
$this->_sections['ext']['show'] = true;
$this->_sections['ext']['max'] = $this->_sections['ext']['loop'];
$this->_sections['ext']['step'] = 1;
$this->_sections['ext']['start'] = $this->_sections['ext']['step'] > 0 ? 0 : $this->_sections['ext']['loop']-1;
if ($this->_sections['ext']['show']) {
    $this->_sections['ext']['total'] = $this->_sections['ext']['loop'];
    if ($this->_sections['ext']['total'] == 0)
        $this->_sections['ext']['show'] = false;
} else
    $this->_sections['ext']['total'] = 0;
if ($this->_sections['ext']['show']):

            for ($this->_sections['ext']['index'] = $this->_sections['ext']['start'], $this->_sections['ext']['iteration'] = 1;
                 $this->_sections['ext']['iteration'] <= $this->_sections['ext']['total'];
                 $this->_sections['ext']['index'] += $this->_sections['ext']['step'], $this->_sections['ext']['iteration']++):
$this->_sections['ext']['rownum'] = $this->_sections['ext']['iteration'];
$this->_sections['ext']['index_prev'] = $this->_sections['ext']['index'] - $this->_sections['ext']['step'];
$this->_sections['ext']['index_next'] = $this->_sections['ext']['index'] + $this->_sections['ext']['step'];
$this->_sections['ext']['first']      = ($this->_sections['ext']['iteration'] == 1);
$this->_sections['ext']['last']       = ($this->_sections['ext']['iteration'] == $this->_sections['ext']['total']);
?>
					<?php echo $this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['cls'][$this->_sections['ext']['index']]; ?>

				<?php endfor; endif; ?>
			<?php endif; ?>
			
			<?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['proc']): ?>
				var tree_inner_tutorial = new WebFXTreeItem('Function-level', '');
				tree_inner_tutorial.openIcon = 'media/images/function_folder.png';
				tree_inner_tutorial.icon = 'media/images/function_folder.png';
				tree_tutorial.add(tree_inner_tutorial);
				
				parent_node = tree_inner_tutorial;
				<?php if (isset($this->_sections['ext'])) unset($this->_sections['ext']);
$this->_sections['ext']['name'] = 'ext';
$this->_sections['ext']['loop'] = is_array($this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['proc']) ? count($this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['proc']) : max(0, (int)$this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['proc']);
$this->_sections['ext']['show'] = true;
$this->_sections['ext']['max'] = $this->_sections['ext']['loop'];
$this->_sections['ext']['step'] = 1;
$this->_sections['ext']['start'] = $this->_sections['ext']['step'] > 0 ? 0 : $this->_sections['ext']['loop']-1;
if ($this->_sections['ext']['show']) {
    $this->_sections['ext']['total'] = $this->_sections['ext']['loop'];
    if ($this->_sections['ext']['total'] == 0)
        $this->_sections['ext']['show'] = false;
} else
    $this->_sections['ext']['total'] = 0;
if ($this->_sections['ext']['show']):

            for ($this->_sections['ext']['index'] = $this->_sections['ext']['start'], $this->_sections['ext']['iteration'] = 1;
                 $this->_sections['ext']['iteration'] <= $this->_sections['ext']['total'];
                 $this->_sections['ext']['index'] += $this->_sections['ext']['step'], $this->_sections['ext']['iteration']++):
$this->_sections['ext']['rownum'] = $this->_sections['ext']['iteration'];
$this->_sections['ext']['index_prev'] = $this->_sections['ext']['index'] - $this->_sections['ext']['step'];
$this->_sections['ext']['index_next'] = $this->_sections['ext']['index'] + $this->_sections['ext']['step'];
$this->_sections['ext']['first']      = ($this->_sections['ext']['iteration'] == 1);
$this->_sections['ext']['last']       = ($this->_sections['ext']['iteration'] == $this->_sections['ext']['total']);
?>
					<?php echo $this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['proc'][$this->_sections['ext']['index']]; ?>

				<?php endfor; endif; ?>
			<?php endif; ?>		
		<?php endif; ?>
	
		<?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['classes']): ?>
			var tree_classe = new WebFXTreeItem('Class(es)', '<?php echo $this->_tpl_vars['packagedoc']; ?>
');
			tree_classe.openIcon = 'media/images/class_folder.png';
			tree_classe.icon = 'media/images/class_folder.png';
			
			<?php if (isset($this->_sections['class'])) unset($this->_sections['class']);
$this->_sections['class']['name'] = 'class';
$this->_sections['class']['loop'] = is_array($this->_tpl_vars['info'][$this->_sections['p']['index']]['classes']) ? count($this->_tpl_vars['info'][$this->_sections['p']['index']]['classes']) : max(0, (int)$this->_tpl_vars['info'][$this->_sections['p']['index']]['classes']);
$this->_sections['class']['show'] = true;
$this->_sections['class']['max'] = $this->_sections['class']['loop'];
$this->_sections['class']['step'] = 1;
$this->_sections['class']['start'] = $this->_sections['class']['step'] > 0 ? 0 : $this->_sections['class']['loop']-1;
if ($this->_sections['class']['show']) {
    $this->_sections['class']['total'] = $this->_sections['class']['loop'];
    if ($this->_sections['class']['total'] == 0)
        $this->_sections['class']['show'] = false;
} else
    $this->_sections['class']['total'] = 0;
if ($this->_sections['class']['show']):

            for ($this->_sections['class']['index'] = $this->_sections['class']['start'], $this->_sections['class']['iteration'] = 1;
                 $this->_sections['class']['iteration'] <= $this->_sections['class']['total'];
                 $this->_sections['class']['index'] += $this->_sections['class']['step'], $this->_sections['class']['iteration']++):
$this->_sections['class']['rownum'] = $this->_sections['class']['iteration'];
$this->_sections['class']['index_prev'] = $this->_sections['class']['index'] - $this->_sections['class']['step'];
$this->_sections['class']['index_next'] = $this->_sections['class']['index'] + $this->_sections['class']['step'];
$this->_sections['class']['first']      = ($this->_sections['class']['iteration'] == 1);
$this->_sections['class']['last']       = ($this->_sections['class']['iteration'] == $this->_sections['class']['total']);
?>
				var classe = new WebFXTreeItem('<?php echo $this->_tpl_vars['info'][$this->_sections['p']['index']]['classes'][$this->_sections['class']['index']]['title']; ?>
', '<?php echo $this->_tpl_vars['info'][$this->_sections['p']['index']]['classes'][$this->_sections['class']['index']]['link']; ?>
');
				classe.openIcon = 'media/images/<?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['classes'][$this->_sections['class']['index']]['abstract']): ?>Abstract<?php endif; ?><?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['classes'][$this->_sections['class']['index']]['access'] == 'private'): ?>Private<?php endif; ?>Class.png';
				classe.icon = 'media/images/<?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['classes'][$this->_sections['class']['index']]['abstract']): ?>Abstract<?php endif; ?><?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['classes'][$this->_sections['class']['index']]['access'] == 'private'): ?>Private<?php endif; ?>Class.png';
				tree_classe.add(classe);
			<?php endfor; endif; ?>
	
			tree.add(tree_classe);
		<?php endif; ?>

		<?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['functions']): ?>
			var tree_function = new WebFXTreeItem('Function(s)', '<?php echo $this->_tpl_vars['packagedoc']; ?>
');
			tree_function.openIcon = 'media/images/function_folder.png';
			tree_function.icon = 'media/images/function_folder.png';
			
			<?php if (isset($this->_sections['nonclass'])) unset($this->_sections['nonclass']);
$this->_sections['nonclass']['name'] = 'nonclass';
$this->_sections['nonclass']['loop'] = is_array($this->_tpl_vars['info'][$this->_sections['p']['index']]['functions']) ? count($this->_tpl_vars['info'][$this->_sections['p']['index']]['functions']) : max(0, (int)$this->_tpl_vars['info'][$this->_sections['p']['index']]['functions']);
$this->_sections['nonclass']['show'] = true;
$this->_sections['nonclass']['max'] = $this->_sections['nonclass']['loop'];
$this->_sections['nonclass']['step'] = 1;
$this->_sections['nonclass']['start'] = $this->_sections['nonclass']['step'] > 0 ? 0 : $this->_sections['nonclass']['loop']-1;
if ($this->_sections['nonclass']['show']) {
    $this->_sections['nonclass']['total'] = $this->_sections['nonclass']['loop'];
    if ($this->_sections['nonclass']['total'] == 0)
        $this->_sections['nonclass']['show'] = false;
} else
    $this->_sections['nonclass']['total'] = 0;
if ($this->_sections['nonclass']['show']):

            for ($this->_sections['nonclass']['index'] = $this->_sections['nonclass']['start'], $this->_sections['nonclass']['iteration'] = 1;
                 $this->_sections['nonclass']['iteration'] <= $this->_sections['nonclass']['total'];
                 $this->_sections['nonclass']['index'] += $this->_sections['nonclass']['step'], $this->_sections['nonclass']['iteration']++):
$this->_sections['nonclass']['rownum'] = $this->_sections['nonclass']['iteration'];
$this->_sections['nonclass']['index_prev'] = $this->_sections['nonclass']['index'] - $this->_sections['nonclass']['step'];
$this->_sections['nonclass']['index_next'] = $this->_sections['nonclass']['index'] + $this->_sections['nonclass']['step'];
$this->_sections['nonclass']['first']      = ($this->_sections['nonclass']['iteration'] == 1);
$this->_sections['nonclass']['last']       = ($this->_sections['nonclass']['iteration'] == $this->_sections['nonclass']['total']);
?>
				var fic = new WebFXTreeItem('<?php echo $this->_tpl_vars['info'][$this->_sections['p']['index']]['functions'][$this->_sections['nonclass']['index']]['title']; ?>
', '<?php echo $this->_tpl_vars['info'][$this->_sections['p']['index']]['functions'][$this->_sections['nonclass']['index']]['link']; ?>
');
				fic.openIcon = 'media/images/Function.png';
				fic.icon = 'media/images/Function.png';
				tree_function.add(fic);
			<?php endfor; endif; ?>
		
			tree.add(tree_function);
		<?php endif; ?>
	
		<?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['files']): ?>
			var tree_file = new WebFXTreeItem('File(s)', '<?php echo $this->_tpl_vars['packagedoc']; ?>
');
			tree_file.openIcon = 'media/images/folder.png';
			tree_file.icon = 'media/images/folder.png';
	
			<?php if (isset($this->_sections['nonclass'])) unset($this->_sections['nonclass']);
$this->_sections['nonclass']['name'] = 'nonclass';
$this->_sections['nonclass']['loop'] = is_array($this->_tpl_vars['info'][$this->_sections['p']['index']]['files']) ? count($this->_tpl_vars['info'][$this->_sections['p']['index']]['files']) : max(0, (int)$this->_tpl_vars['info'][$this->_sections['p']['index']]['files']);
$this->_sections['nonclass']['show'] = true;
$this->_sections['nonclass']['max'] = $this->_sections['nonclass']['loop'];
$this->_sections['nonclass']['step'] = 1;
$this->_sections['nonclass']['start'] = $this->_sections['nonclass']['step'] > 0 ? 0 : $this->_sections['nonclass']['loop']-1;
if ($this->_sections['nonclass']['show']) {
    $this->_sections['nonclass']['total'] = $this->_sections['nonclass']['loop'];
    if ($this->_sections['nonclass']['total'] == 0)
        $this->_sections['nonclass']['show'] = false;
} else
    $this->_sections['nonclass']['total'] = 0;
if ($this->_sections['nonclass']['show']):

            for ($this->_sections['nonclass']['index'] = $this->_sections['nonclass']['start'], $this->_sections['nonclass']['iteration'] = 1;
                 $this->_sections['nonclass']['iteration'] <= $this->_sections['nonclass']['total'];
                 $this->_sections['nonclass']['index'] += $this->_sections['nonclass']['step'], $this->_sections['nonclass']['iteration']++):
$this->_sections['nonclass']['rownum'] = $this->_sections['nonclass']['iteration'];
$this->_sections['nonclass']['index_prev'] = $this->_sections['nonclass']['index'] - $this->_sections['nonclass']['step'];
$this->_sections['nonclass']['index_next'] = $this->_sections['nonclass']['index'] + $this->_sections['nonclass']['step'];
$this->_sections['nonclass']['first']      = ($this->_sections['nonclass']['iteration'] == 1);
$this->_sections['nonclass']['last']       = ($this->_sections['nonclass']['iteration'] == $this->_sections['nonclass']['total']);
?>
				var file = new WebFXTreeItem('<?php echo $this->_tpl_vars['info'][$this->_sections['p']['index']]['files'][$this->_sections['nonclass']['index']]['title']; ?>
', '<?php echo $this->_tpl_vars['info'][$this->_sections['p']['index']]['files'][$this->_sections['nonclass']['index']]['link']; ?>
');
				file.openIcon = 'media/images/Page.png';
				file.icon = 'media/images/Page.png';
				tree_file.add(file);
			<?php endfor; endif; ?>
	
			tree.add(tree_file);
		<?php endif; ?>

	<?php else: ?>
		<?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['subpackagetutorial']): ?>
			var subpackagetree = new WebFXTreeItem('<span class="sub-package"><?php echo $this->_run_mod_handler('strip_tags', true, $this->_tpl_vars['info'][$this->_sections['p']['index']]['subpackagetutorialtitle']); ?>
</span>', '<?php echo $this->_tpl_vars['info'][$this->_sections['p']['index']]['subpackagetutorialnoa']; ?>
');
		<?php else: ?>
			var subpackagetree = new WebFXTreeItem('<span class="sub-package"><?php echo $this->_tpl_vars['info'][$this->_sections['p']['index']]['subpackage']; ?>
</span>', '<?php echo $this->_tpl_vars['packagedoc']; ?>
');
		<?php endif; ?>

		subpackagetree.openIcon = 'media/images/package.png';
		subpackagetree.icon = 'media/images/package.png';

		<?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']): ?>
			var tree_tutorial = new WebFXTreeItem('Tutorial(s)/Manual(s)', '');
			tree_tutorial.openIcon = 'media/images/tutorial_folder.png';
			tree_tutorial.icon = 'media/images/tutorial_folder.png';
			tree.add(tree_tutorial);
			
			<?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['pkg']): ?>
				var tree_inner_tutorial = new WebFXTreeItem('Package-level', '');
				tree_inner_tutorial.openIcon = 'media/images/package_folder.png';
				tree_inner_tutorial.icon = 'media/images/package_folder.png';
				tree_tutorial.add(tree_inner_tutorial);
				
				parent_node = tree_inner_tutorial;
				<?php if (isset($this->_sections['ext'])) unset($this->_sections['ext']);
$this->_sections['ext']['name'] = 'ext';
$this->_sections['ext']['loop'] = is_array($this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['pkg']) ? count($this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['pkg']) : max(0, (int)$this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['pkg']);
$this->_sections['ext']['show'] = true;
$this->_sections['ext']['max'] = $this->_sections['ext']['loop'];
$this->_sections['ext']['step'] = 1;
$this->_sections['ext']['start'] = $this->_sections['ext']['step'] > 0 ? 0 : $this->_sections['ext']['loop']-1;
if ($this->_sections['ext']['show']) {
    $this->_sections['ext']['total'] = $this->_sections['ext']['loop'];
    if ($this->_sections['ext']['total'] == 0)
        $this->_sections['ext']['show'] = false;
} else
    $this->_sections['ext']['total'] = 0;
if ($this->_sections['ext']['show']):

            for ($this->_sections['ext']['index'] = $this->_sections['ext']['start'], $this->_sections['ext']['iteration'] = 1;
                 $this->_sections['ext']['iteration'] <= $this->_sections['ext']['total'];
                 $this->_sections['ext']['index'] += $this->_sections['ext']['step'], $this->_sections['ext']['iteration']++):
$this->_sections['ext']['rownum'] = $this->_sections['ext']['iteration'];
$this->_sections['ext']['index_prev'] = $this->_sections['ext']['index'] - $this->_sections['ext']['step'];
$this->_sections['ext']['index_next'] = $this->_sections['ext']['index'] + $this->_sections['ext']['step'];
$this->_sections['ext']['first']      = ($this->_sections['ext']['iteration'] == 1);
$this->_sections['ext']['last']       = ($this->_sections['ext']['iteration'] == $this->_sections['ext']['total']);
?>
					<?php echo $this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['pkg'][$this->_sections['ext']['index']]; ?>

				<?php endfor; endif; ?>
			<?php endif; ?>
			
			<?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['cls']): ?>
				var tree_inner_tutorial = new WebFXTreeItem('Class-level', '');
				tree_inner_tutorial.openIcon = 'media/images/class_folder.png';
				tree_inner_tutorial.icon = 'media/images/class_folder.png';
				tree_tutorial.add(tree_inner_tutorial);
				
				parent_node = tree_inner_tutorial;
				<?php if (isset($this->_sections['ext'])) unset($this->_sections['ext']);
$this->_sections['ext']['name'] = 'ext';
$this->_sections['ext']['loop'] = is_array($this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['cls']) ? count($this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['cls']) : max(0, (int)$this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['cls']);
$this->_sections['ext']['show'] = true;
$this->_sections['ext']['max'] = $this->_sections['ext']['loop'];
$this->_sections['ext']['step'] = 1;
$this->_sections['ext']['start'] = $this->_sections['ext']['step'] > 0 ? 0 : $this->_sections['ext']['loop']-1;
if ($this->_sections['ext']['show']) {
    $this->_sections['ext']['total'] = $this->_sections['ext']['loop'];
    if ($this->_sections['ext']['total'] == 0)
        $this->_sections['ext']['show'] = false;
} else
    $this->_sections['ext']['total'] = 0;
if ($this->_sections['ext']['show']):

            for ($this->_sections['ext']['index'] = $this->_sections['ext']['start'], $this->_sections['ext']['iteration'] = 1;
                 $this->_sections['ext']['iteration'] <= $this->_sections['ext']['total'];
                 $this->_sections['ext']['index'] += $this->_sections['ext']['step'], $this->_sections['ext']['iteration']++):
$this->_sections['ext']['rownum'] = $this->_sections['ext']['iteration'];
$this->_sections['ext']['index_prev'] = $this->_sections['ext']['index'] - $this->_sections['ext']['step'];
$this->_sections['ext']['index_next'] = $this->_sections['ext']['index'] + $this->_sections['ext']['step'];
$this->_sections['ext']['first']      = ($this->_sections['ext']['iteration'] == 1);
$this->_sections['ext']['last']       = ($this->_sections['ext']['iteration'] == $this->_sections['ext']['total']);
?>
					<?php echo $this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['cls'][$this->_sections['ext']['index']]; ?>

				<?php endfor; endif; ?>
			<?php endif; ?>
			
			<?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['proc']): ?>
				var tree_inner_tutorial = new WebFXTreeItem('Function-level', '');
				tree_inner_tutorial.openIcon = 'media/images/function_folder.png';
				tree_inner_tutorial.icon = 'media/images/function_folder.png';
				tree_tutorial.add(tree_inner_tutorial);
				
				parent_node = tree_inner_tutorial;
				<?php if (isset($this->_sections['ext'])) unset($this->_sections['ext']);
$this->_sections['ext']['name'] = 'ext';
$this->_sections['ext']['loop'] = is_array($this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['proc']) ? count($this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['proc']) : max(0, (int)$this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['proc']);
$this->_sections['ext']['show'] = true;
$this->_sections['ext']['max'] = $this->_sections['ext']['loop'];
$this->_sections['ext']['step'] = 1;
$this->_sections['ext']['start'] = $this->_sections['ext']['step'] > 0 ? 0 : $this->_sections['ext']['loop']-1;
if ($this->_sections['ext']['show']) {
    $this->_sections['ext']['total'] = $this->_sections['ext']['loop'];
    if ($this->_sections['ext']['total'] == 0)
        $this->_sections['ext']['show'] = false;
} else
    $this->_sections['ext']['total'] = 0;
if ($this->_sections['ext']['show']):

            for ($this->_sections['ext']['index'] = $this->_sections['ext']['start'], $this->_sections['ext']['iteration'] = 1;
                 $this->_sections['ext']['iteration'] <= $this->_sections['ext']['total'];
                 $this->_sections['ext']['index'] += $this->_sections['ext']['step'], $this->_sections['ext']['iteration']++):
$this->_sections['ext']['rownum'] = $this->_sections['ext']['iteration'];
$this->_sections['ext']['index_prev'] = $this->_sections['ext']['index'] - $this->_sections['ext']['step'];
$this->_sections['ext']['index_next'] = $this->_sections['ext']['index'] + $this->_sections['ext']['step'];
$this->_sections['ext']['first']      = ($this->_sections['ext']['iteration'] == 1);
$this->_sections['ext']['last']       = ($this->_sections['ext']['iteration'] == $this->_sections['ext']['total']);
?>
					<?php echo $this->_tpl_vars['info'][$this->_sections['p']['index']]['tutorials']['proc'][$this->_sections['ext']['index']]; ?>

				<?php endfor; endif; ?>
			<?php endif; ?>		
		<?php endif; ?>
	
		<?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['classes']): ?>
			var subpackagetree_classe = new WebFXTreeItem('Class(es)', '<?php echo $this->_tpl_vars['packagedoc']; ?>
');
			subpackagetree_classe.openIcon = 'media/images/class_folder.png';
			subpackagetree_classe.icon = 'media/images/class_folder.png';
			
			<?php if (isset($this->_sections['class'])) unset($this->_sections['class']);
$this->_sections['class']['name'] = 'class';
$this->_sections['class']['loop'] = is_array($this->_tpl_vars['info'][$this->_sections['p']['index']]['classes']) ? count($this->_tpl_vars['info'][$this->_sections['p']['index']]['classes']) : max(0, (int)$this->_tpl_vars['info'][$this->_sections['p']['index']]['classes']);
$this->_sections['class']['show'] = true;
$this->_sections['class']['max'] = $this->_sections['class']['loop'];
$this->_sections['class']['step'] = 1;
$this->_sections['class']['start'] = $this->_sections['class']['step'] > 0 ? 0 : $this->_sections['class']['loop']-1;
if ($this->_sections['class']['show']) {
    $this->_sections['class']['total'] = $this->_sections['class']['loop'];
    if ($this->_sections['class']['total'] == 0)
        $this->_sections['class']['show'] = false;
} else
    $this->_sections['class']['total'] = 0;
if ($this->_sections['class']['show']):

            for ($this->_sections['class']['index'] = $this->_sections['class']['start'], $this->_sections['class']['iteration'] = 1;
                 $this->_sections['class']['iteration'] <= $this->_sections['class']['total'];
                 $this->_sections['class']['index'] += $this->_sections['class']['step'], $this->_sections['class']['iteration']++):
$this->_sections['class']['rownum'] = $this->_sections['class']['iteration'];
$this->_sections['class']['index_prev'] = $this->_sections['class']['index'] - $this->_sections['class']['step'];
$this->_sections['class']['index_next'] = $this->_sections['class']['index'] + $this->_sections['class']['step'];
$this->_sections['class']['first']      = ($this->_sections['class']['iteration'] == 1);
$this->_sections['class']['last']       = ($this->_sections['class']['iteration'] == $this->_sections['class']['total']);
?>
				var classe = new WebFXTreeItem('<?php echo $this->_tpl_vars['info'][$this->_sections['p']['index']]['classes'][$this->_sections['class']['index']]['title']; ?>
', '<?php echo $this->_tpl_vars['info'][$this->_sections['p']['index']]['classes'][$this->_sections['class']['index']]['link']; ?>
');
				classe.openIcon = 'media/images/<?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['classes'][$this->_sections['class']['index']]['abstract']): ?>Abstract<?php endif; ?><?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['classes'][$this->_sections['class']['index']]['access'] == 'private'): ?>Private<?php endif; ?>Class.png';
				classe.icon = 'media/images/<?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['classes'][$this->_sections['class']['index']]['abstract']): ?>Abstract<?php endif; ?><?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['classes'][$this->_sections['class']['index']]['access'] == 'private'): ?>Private<?php endif; ?>Class.png';
				subpackagetree_classe.add(classe);
			<?php endfor; endif; ?>
			
			subpackagetree.add(subpackagetree_classe);
		<?php endif; ?>

		<?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['functions']): ?>
			var subpackagetree_function = new WebFXTreeItem('Function(s)', '<?php echo $this->_tpl_vars['packagedoc']; ?>
');
			subpackagetree_function.openIcon = 'media/images/function_folder.png';
			subpackagetree_function.icon = 'media/images/function_folder.png';
			
			<?php if (isset($this->_sections['nonclass'])) unset($this->_sections['nonclass']);
$this->_sections['nonclass']['name'] = 'nonclass';
$this->_sections['nonclass']['loop'] = is_array($this->_tpl_vars['info'][$this->_sections['p']['index']]['functions']) ? count($this->_tpl_vars['info'][$this->_sections['p']['index']]['functions']) : max(0, (int)$this->_tpl_vars['info'][$this->_sections['p']['index']]['functions']);
$this->_sections['nonclass']['show'] = true;
$this->_sections['nonclass']['max'] = $this->_sections['nonclass']['loop'];
$this->_sections['nonclass']['step'] = 1;
$this->_sections['nonclass']['start'] = $this->_sections['nonclass']['step'] > 0 ? 0 : $this->_sections['nonclass']['loop']-1;
if ($this->_sections['nonclass']['show']) {
    $this->_sections['nonclass']['total'] = $this->_sections['nonclass']['loop'];
    if ($this->_sections['nonclass']['total'] == 0)
        $this->_sections['nonclass']['show'] = false;
} else
    $this->_sections['nonclass']['total'] = 0;
if ($this->_sections['nonclass']['show']):

            for ($this->_sections['nonclass']['index'] = $this->_sections['nonclass']['start'], $this->_sections['nonclass']['iteration'] = 1;
                 $this->_sections['nonclass']['iteration'] <= $this->_sections['nonclass']['total'];
                 $this->_sections['nonclass']['index'] += $this->_sections['nonclass']['step'], $this->_sections['nonclass']['iteration']++):
$this->_sections['nonclass']['rownum'] = $this->_sections['nonclass']['iteration'];
$this->_sections['nonclass']['index_prev'] = $this->_sections['nonclass']['index'] - $this->_sections['nonclass']['step'];
$this->_sections['nonclass']['index_next'] = $this->_sections['nonclass']['index'] + $this->_sections['nonclass']['step'];
$this->_sections['nonclass']['first']      = ($this->_sections['nonclass']['iteration'] == 1);
$this->_sections['nonclass']['last']       = ($this->_sections['nonclass']['iteration'] == $this->_sections['nonclass']['total']);
?>
				var fic = new WebFXTreeItem('<?php echo $this->_tpl_vars['info'][$this->_sections['p']['index']]['functions'][$this->_sections['nonclass']['index']]['title']; ?>
', '<?php echo $this->_tpl_vars['info'][$this->_sections['p']['index']]['functions'][$this->_sections['nonclass']['index']]['link']; ?>
');
				fic.openIcon = 'media/images/Function.png';
				fic.icon = 'media/images/Function.png';
				subpackagetree_function.add(fic);
			<?php endfor; endif; ?>
			
			subpackagetree.add(subpackagetree_function);
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['info'][$this->_sections['p']['index']]['files']): ?>
			var subpackagetree_file = new WebFXTreeItem('File(s)', '<?php echo $this->_tpl_vars['packagedoc']; ?>
');
			subpackagetree_file.openIcon = 'media/images/folder.png';
			subpackagetree_file.icon = 'media/images/folder.png';
			
			<?php if (isset($this->_sections['nonclass'])) unset($this->_sections['nonclass']);
$this->_sections['nonclass']['name'] = 'nonclass';
$this->_sections['nonclass']['loop'] = is_array($this->_tpl_vars['info'][$this->_sections['p']['index']]['files']) ? count($this->_tpl_vars['info'][$this->_sections['p']['index']]['files']) : max(0, (int)$this->_tpl_vars['info'][$this->_sections['p']['index']]['files']);
$this->_sections['nonclass']['show'] = true;
$this->_sections['nonclass']['max'] = $this->_sections['nonclass']['loop'];
$this->_sections['nonclass']['step'] = 1;
$this->_sections['nonclass']['start'] = $this->_sections['nonclass']['step'] > 0 ? 0 : $this->_sections['nonclass']['loop']-1;
if ($this->_sections['nonclass']['show']) {
    $this->_sections['nonclass']['total'] = $this->_sections['nonclass']['loop'];
    if ($this->_sections['nonclass']['total'] == 0)
        $this->_sections['nonclass']['show'] = false;
} else
    $this->_sections['nonclass']['total'] = 0;
if ($this->_sections['nonclass']['show']):

            for ($this->_sections['nonclass']['index'] = $this->_sections['nonclass']['start'], $this->_sections['nonclass']['iteration'] = 1;
                 $this->_sections['nonclass']['iteration'] <= $this->_sections['nonclass']['total'];
                 $this->_sections['nonclass']['index'] += $this->_sections['nonclass']['step'], $this->_sections['nonclass']['iteration']++):
$this->_sections['nonclass']['rownum'] = $this->_sections['nonclass']['iteration'];
$this->_sections['nonclass']['index_prev'] = $this->_sections['nonclass']['index'] - $this->_sections['nonclass']['step'];
$this->_sections['nonclass']['index_next'] = $this->_sections['nonclass']['index'] + $this->_sections['nonclass']['step'];
$this->_sections['nonclass']['first']      = ($this->_sections['nonclass']['iteration'] == 1);
$this->_sections['nonclass']['last']       = ($this->_sections['nonclass']['iteration'] == $this->_sections['nonclass']['total']);
?>
				var file = new WebFXTreeItem('<?php echo $this->_tpl_vars['info'][$this->_sections['p']['index']]['files'][$this->_sections['nonclass']['index']]['title']; ?>
', '<?php echo $this->_tpl_vars['info'][$this->_sections['p']['index']]['files'][$this->_sections['nonclass']['index']]['link']; ?>
');
				file.openIcon = 'media/images/Page.png';
				file.icon = 'media/images/Page.png';
				subpackagetree_file.add(file);
			<?php endfor; endif; ?>
		
			subpackagetree.add(subpackagetree_file);
		<?php endif; ?>
	
	  tree.add(subpackagetree);
	<?php endif; ?>
<?php endfor; endif; ?>

document.write(tree);
}
</script>
</div>
<p class="notes">
	Generated by 
	<a href="<?php echo $this->_tpl_vars['phpdocwebsite']; ?>
" target="_blank">phpDocumentor <span class="field"><?php echo $this->_tpl_vars['phpdocversion']; ?>
</span></a>
</p>
</body>
</html>
