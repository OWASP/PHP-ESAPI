<?php /* Smarty version 2.5.0, created on 2003-07-27 00:08:15
         compiled from page.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("header.tpl", array('top3' => true));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<h2 class="file-name"><img src="<?php echo $this->_tpl_vars['subdir']; ?>
media/images/Page_logo.png" alt="File" style="vertical-align: middle"><?php echo $this->_tpl_vars['source_location']; ?>
</h2>

<a name="sec-description"></a>
<div class="info-box">
	<div class="info-box-title">Description</div>
	<div class="nav-bar">
		<?php if ($this->_tpl_vars['classes'] || $this->_tpl_vars['includes'] || $this->_tpl_vars['defines'] || $this->_tpl_vars['globals'] || $this->_tpl_vars['functions']): ?>
			<span class="disabled">Description</span> |
		<?php endif; ?>
		<?php if ($this->_tpl_vars['classes']): ?>
			<a href="#sec-classes">Classes</a>
			<?php if ($this->_tpl_vars['includes'] || $this->_tpl_vars['defines'] || $this->_tpl_vars['globals'] || $this->_tpl_vars['functions']): ?>|<?php endif; ?>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['includes']): ?>
			<a href="#sec-includes">Includes</a>
			<?php if ($this->_tpl_vars['defines'] || $this->_tpl_vars['globals'] || $this->_tpl_vars['functions']): ?>|<?php endif; ?>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['defines']): ?>
			<a href="#sec-constants">Constants</a>
			<?php if ($this->_tpl_vars['globals'] || $this->_tpl_vars['functions']): ?>|<?php endif; ?>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['globals']): ?>
			<a href="#sec-variables">Variables</a>
			<?php if ($this->_tpl_vars['functions']): ?>|<?php endif; ?>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['functions']): ?>
			<a href="#sec-functions">Functions</a>
		<?php endif; ?>
	</div>
	<div class="info-box-body">	
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("docblock.tpl", array('desc' => $this->_tpl_vars['desc'],'sdesc' => $this->_tpl_vars['sdesc'],'tags' => $this->_tpl_vars['tags']));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		
		<?php if ($this->_tpl_vars['tutorial']): ?>
			<hr class="separator" />
			<div class="notes">Tutorial: <span class="tutorial"><?php echo $this->_tpl_vars['tutorial']; ?>
</div>
		<?php endif; ?>
	</div>
</div>
		
<?php if ($this->_tpl_vars['classes']): ?>
	<a name="sec-classes"></a>	
	<div class="info-box">
		<div class="info-box-title">Classes</div>
		<div class="nav-bar">
			<a href="#sec-description">Description</a> |
			<span class="disabled">Classes</span>
			<?php if ($this->_tpl_vars['includes'] || $this->_tpl_vars['defines'] || $this->_tpl_vars['globals'] || $this->_tpl_vars['functions']): ?>|<?php endif; ?>
			<?php if ($this->_tpl_vars['includes']): ?>
				<a href="#sec-includes">Includes</a>
				<?php if ($this->_tpl_vars['defines'] || $this->_tpl_vars['globals'] || $this->_tpl_vars['functions']): ?>|<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['defines']): ?>
				<a href="#sec-constants">Constants</a>
				<?php if ($this->_tpl_vars['globals'] || $this->_tpl_vars['functions']): ?>|<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['globals']): ?>
				<a href="#sec-variables">Variables</a>
				<?php if ($this->_tpl_vars['functions']): ?>|<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['functions']): ?>
				<a href="#sec-functions">Functions</a>
			<?php endif; ?>
		</div>
		<div class="info-box-body">	
			<table cellpadding="2" cellspacing="0" class="class-table">
				<tr>
					<th class="class-table-header">Class</th>
					<th class="class-table-header">Description</th>
				</tr>
				<?php if (isset($this->_sections['classes'])) unset($this->_sections['classes']);
$this->_sections['classes']['name'] = 'classes';
$this->_sections['classes']['loop'] = is_array($this->_tpl_vars['classes']) ? count($this->_tpl_vars['classes']) : max(0, (int)$this->_tpl_vars['classes']);
$this->_sections['classes']['show'] = true;
$this->_sections['classes']['max'] = $this->_sections['classes']['loop'];
$this->_sections['classes']['step'] = 1;
$this->_sections['classes']['start'] = $this->_sections['classes']['step'] > 0 ? 0 : $this->_sections['classes']['loop']-1;
if ($this->_sections['classes']['show']) {
    $this->_sections['classes']['total'] = $this->_sections['classes']['loop'];
    if ($this->_sections['classes']['total'] == 0)
        $this->_sections['classes']['show'] = false;
} else
    $this->_sections['classes']['total'] = 0;
if ($this->_sections['classes']['show']):

            for ($this->_sections['classes']['index'] = $this->_sections['classes']['start'], $this->_sections['classes']['iteration'] = 1;
                 $this->_sections['classes']['iteration'] <= $this->_sections['classes']['total'];
                 $this->_sections['classes']['index'] += $this->_sections['classes']['step'], $this->_sections['classes']['iteration']++):
$this->_sections['classes']['rownum'] = $this->_sections['classes']['iteration'];
$this->_sections['classes']['index_prev'] = $this->_sections['classes']['index'] - $this->_sections['classes']['step'];
$this->_sections['classes']['index_next'] = $this->_sections['classes']['index'] + $this->_sections['classes']['step'];
$this->_sections['classes']['first']      = ($this->_sections['classes']['iteration'] == 1);
$this->_sections['classes']['last']       = ($this->_sections['classes']['iteration'] == $this->_sections['classes']['total']);
?>
				<tr>
					<td style="padding-right: 2em; vertical-align: top; white-space: nowrap">
						<img src="<?php echo $this->_tpl_vars['subdir']; ?>
media/images/<?php if ($this->_tpl_vars['classes'][$this->_sections['classes']['index']]['abstract']): ?>Abstract<?php endif; ?><?php if ($this->_tpl_vars['classes'][$this->_sections['classes']['index']]['access'] == 'private'): ?>Private<?php endif; ?>Class.png"
								 alt="<?php if ($this->_tpl_vars['classes'][$this->_sections['classes']['index']]['abstract']): ?>Abstract<?php endif; ?><?php if ($this->_tpl_vars['classes'][$this->_sections['classes']['index']]['access'] == 'private'): ?>Private<?php endif; ?> class"
								 title="<?php if ($this->_tpl_vars['classes'][$this->_sections['classes']['index']]['abstract']): ?>Abstract<?php endif; ?><?php if ($this->_tpl_vars['classes'][$this->_sections['classes']['index']]['access'] == 'private'): ?>Private<?php endif; ?> class"/>
						<?php echo $this->_tpl_vars['classes'][$this->_sections['classes']['index']]['link']; ?>

					</td>
					<td>
					<?php if ($this->_tpl_vars['classes'][$this->_sections['classes']['index']]['sdesc']): ?>
						<?php echo $this->_tpl_vars['classes'][$this->_sections['classes']['index']]['sdesc']; ?>

					<?php else: ?>
						<?php echo $this->_tpl_vars['classes'][$this->_sections['classes']['index']]['desc']; ?>

					<?php endif; ?>
					</td>
				</tr>
				<?php endfor; endif; ?>
			</table>
		</div>
	</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['includes']): ?>
	<a name="sec-includes"></a>	
	<div class="info-box">
		<div class="info-box-title">Includes</div>
		<div class="nav-bar">
			<a href="#sec-description">Description</a> |
			<?php if ($this->_tpl_vars['classes']): ?>
				<a href="#sec-classes">Classes</a>
				<?php if ($this->_tpl_vars['includes'] || $this->_tpl_vars['defines'] || $this->_tpl_vars['globals'] || $this->_tpl_vars['functions']): ?>|<?php endif; ?>
			<?php endif; ?>
			<span class="disabled">Includes</span>
			<?php if ($this->_tpl_vars['defines'] || $this->_tpl_vars['globals'] || $this->_tpl_vars['functions']): ?>|<?php endif; ?>
			<?php if ($this->_tpl_vars['defines']): ?>
				<a href="#sec-constants">Constants</a>
				<?php if ($this->_tpl_vars['globals'] || $this->_tpl_vars['functions']): ?>|<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['globals']): ?>
				<a href="#sec-variables">Variables</a>
				<?php if ($this->_tpl_vars['functions']): ?>|<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['functions']): ?>
				<a href="#sec-functions">Functions</a>
			<?php endif; ?>
		</div>
		<div class="info-box-body">	
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("include.tpl", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		</div>
	</div>
<?php endif; ?>
	
<?php if ($this->_tpl_vars['defines']): ?>
	<a name="sec-constants"></a>	
	<div class="info-box">
		<div class="info-box-title">Constants</div>
		<div class="nav-bar">
			<a href="#sec-description">Description</a> |
			<?php if ($this->_tpl_vars['classes']): ?>
				<a href="#sec-classes">Classes</a>
				<?php if ($this->_tpl_vars['includes'] || $this->_tpl_vars['defines'] || $this->_tpl_vars['globals'] || $this->_tpl_vars['functions']): ?>|<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['includes']): ?>
				<a href="#sec-includes">Includes</a>
				<?php if ($this->_tpl_vars['defines'] || $this->_tpl_vars['globals'] || $this->_tpl_vars['functions']): ?>|<?php endif; ?>
			<?php endif; ?>
			<span class="disabled">Constants</span>
			<?php if ($this->_tpl_vars['globals'] || $this->_tpl_vars['functions']): ?>|<?php endif; ?>
			<?php if ($this->_tpl_vars['globals']): ?>
				<a href="#sec-variables">Variables</a>
				<?php if ($this->_tpl_vars['functions']): ?>|<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['functions']): ?>
				<a href="#sec-functions">Functions</a>
			<?php endif; ?>
		</div>
		<div class="info-box-body">	
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("define.tpl", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		</div>
	</div>
<?php endif; ?>
	
<?php if ($this->_tpl_vars['globals']): ?>
	<a name="sec-variables"></a>	
	<div class="info-box">
		<div class="info-box-title">Variables</div>
		<div class="nav-bar">
			<a href="#sec-description">Description</a> |
			<?php if ($this->_tpl_vars['classes']): ?>
				<a href="#sec-classes">Classes</a>
				<?php if ($this->_tpl_vars['includes'] || $this->_tpl_vars['defines'] || $this->_tpl_vars['globals'] || $this->_tpl_vars['functions']): ?>|<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['includes']): ?>
				<a href="#sec-includes">Includes</a>
				<?php if ($this->_tpl_vars['defines'] || $this->_tpl_vars['globals'] || $this->_tpl_vars['functions']): ?>|<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['defines']): ?>
				<a href="#sec-constants">Constants</a>
				<?php if ($this->_tpl_vars['globals'] || $this->_tpl_vars['functions']): ?>|<?php endif; ?>
			<?php endif; ?>
			<span class="disabled">Variables</span>
			<?php if ($this->_tpl_vars['functions']): ?>|<?php endif; ?>
			<?php if ($this->_tpl_vars['globals']): ?>
				<a href="#sec-functions">Functions</a>
			<?php endif; ?>
		</div>
		<div class="info-box-body">	
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("global.tpl", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		</div>
	</div>
<?php endif; ?>
	
<?php if ($this->_tpl_vars['functions']): ?>
	<a name="sec-functions"></a>	
	<div class="info-box">
		<div class="info-box-title">Functions</div>
		<div class="nav-bar">
			<a href="#sec-description">Description</a> |
			<?php if ($this->_tpl_vars['classes']): ?>
				<a href="#sec-classes">Classes</a>
				<?php if ($this->_tpl_vars['includes'] || $this->_tpl_vars['defines'] || $this->_tpl_vars['globals'] || $this->_tpl_vars['functions']): ?>|<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['includes']): ?>
				<a href="#sec-includes">Includes</a>
				<?php if ($this->_tpl_vars['defines'] || $this->_tpl_vars['globals'] || $this->_tpl_vars['functions']): ?>|<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['defines']): ?>
				<a href="#sec-constants">Constants</a>
				<?php if ($this->_tpl_vars['globals'] || $this->_tpl_vars['functions']): ?>|<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['globals']): ?>
				<a href="#sec-variables">Variables</a>
				<?php if ($this->_tpl_vars['functions']): ?>|<?php endif; ?>
			<?php endif; ?>
			<span class="disabled">Functions</span>
		</div>
		<div class="info-box-body">	
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("function.tpl", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		</div>
	</div>
<?php endif; ?>
	
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("footer.tpl", array('top3' => true));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
