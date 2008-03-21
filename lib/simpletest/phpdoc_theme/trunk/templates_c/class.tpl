<?php /* Smarty version 2.5.0, created on 2003-07-27 00:26:21
         compiled from class.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("header.tpl", array('top3' => true));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<h2 class="class-name"><img src="<?php echo $this->_tpl_vars['subdir']; ?>
media/images/<?php if ($this->_tpl_vars['abstract']): ?><?php if ($this->_tpl_vars['access'] == 'private'): ?>AbstractPrivate<?php else: ?>Abstract<?php endif; ?><?php else: ?><?php if ($this->_tpl_vars['access'] == 'private'): ?>Private<?php endif; ?><?php endif; ?>Class_logo.png"
														alt="<?php if ($this->_tpl_vars['abstract']): ?><?php if ($this->_tpl_vars['access'] == 'private'): ?>AbstractPrivate<?php else: ?>Abstract<?php endif; ?><?php else: ?><?php if ($this->_tpl_vars['access'] == 'private'): ?>Private<?php endif; ?><?php endif; ?> Class"
														title="<?php if ($this->_tpl_vars['abstract']): ?><?php if ($this->_tpl_vars['access'] == 'private'): ?>AbstractPrivate<?php else: ?>Abstract<?php endif; ?><?php else: ?><?php if ($this->_tpl_vars['access'] == 'private'): ?>Private<?php endif; ?><?php endif; ?> Class"
														style="vertical-align: middle"> <?php echo $this->_tpl_vars['class_name']; ?>
</h2>

<a name="sec-description"></a>
<div class="info-box">
	<div class="info-box-title">Description</div>
	<div class="nav-bar">
		<?php if ($this->_tpl_vars['children'] || $this->_tpl_vars['vars'] || $this->_tpl_vars['ivars'] || $this->_tpl_vars['methods'] || $this->_tpl_vars['imethods']): ?>
			<span class="disabled">Description</span> |
		<?php endif; ?>
		<?php if ($this->_tpl_vars['children']): ?>
			<a href="#sec-descendents">Descendents</a>
			<?php if ($this->_tpl_vars['vars'] || $this->_tpl_vars['ivars'] || $this->_tpl_vars['methods'] || $this->_tpl_vars['imethods']): ?>|<?php endif; ?>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['vars'] || $this->_tpl_vars['ivars']): ?>
			<?php if ($this->_tpl_vars['vars']): ?>
				<a href="#sec-var-summary">Vars</a> (<a href="#sec-vars">details</a>)
			<?php else: ?>
				<a href="#sec-vars">Vars</a>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['methods'] || $this->_tpl_vars['imethods']): ?>|<?php endif; ?>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['methods'] || $this->_tpl_vars['imethods']): ?>
			<?php if ($this->_tpl_vars['methods']): ?>
				<a href="#sec-method-summary">Methods</a> (<a href="#sec-methods">details</a>)
			<?php else: ?>
				<a href="#sec-methods">Methods</a>
			<?php endif; ?>			
		<?php endif; ?>
	</div>
	<div class="info-box-body">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("docblock.tpl", array('type' => 'class','sdesc' => $this->_tpl_vars['sdesc'],'desc' => $this->_tpl_vars['desc']));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<p class="notes">
			Located in <a class="field" href="<?php echo $this->_tpl_vars['page_link']; ?>
"><?php echo $this->_tpl_vars['source_location']; ?>
</a> (line <span class="field"><?php if ($this->_tpl_vars['class_slink']): ?><?php echo $this->_tpl_vars['class_slink']; ?>
<?php else: ?><?php echo $this->_tpl_vars['line_number']; ?>
<?php endif; ?></span>)
		</p>
		
		<?php if ($this->_tpl_vars['tutorial']): ?>
			<hr class="separator" />
			<div class="notes">Tutorial: <span class="tutorial"><?php echo $this->_tpl_vars['tutorial']; ?>
</div>
		<?php endif; ?>
		
		<pre><?php if (isset($this->_sections['tree'])) unset($this->_sections['tree']);
$this->_sections['tree']['name'] = 'tree';
$this->_sections['tree']['loop'] = is_array($this->_tpl_vars['class_tree']['classes']) ? count($this->_tpl_vars['class_tree']['classes']) : max(0, (int)$this->_tpl_vars['class_tree']['classes']);
$this->_sections['tree']['show'] = true;
$this->_sections['tree']['max'] = $this->_sections['tree']['loop'];
$this->_sections['tree']['step'] = 1;
$this->_sections['tree']['start'] = $this->_sections['tree']['step'] > 0 ? 0 : $this->_sections['tree']['loop']-1;
if ($this->_sections['tree']['show']) {
    $this->_sections['tree']['total'] = $this->_sections['tree']['loop'];
    if ($this->_sections['tree']['total'] == 0)
        $this->_sections['tree']['show'] = false;
} else
    $this->_sections['tree']['total'] = 0;
if ($this->_sections['tree']['show']):

            for ($this->_sections['tree']['index'] = $this->_sections['tree']['start'], $this->_sections['tree']['iteration'] = 1;
                 $this->_sections['tree']['iteration'] <= $this->_sections['tree']['total'];
                 $this->_sections['tree']['index'] += $this->_sections['tree']['step'], $this->_sections['tree']['iteration']++):
$this->_sections['tree']['rownum'] = $this->_sections['tree']['iteration'];
$this->_sections['tree']['index_prev'] = $this->_sections['tree']['index'] - $this->_sections['tree']['step'];
$this->_sections['tree']['index_next'] = $this->_sections['tree']['index'] + $this->_sections['tree']['step'];
$this->_sections['tree']['first']      = ($this->_sections['tree']['iteration'] == 1);
$this->_sections['tree']['last']       = ($this->_sections['tree']['iteration'] == $this->_sections['tree']['total']);
?><?php echo $this->_tpl_vars['class_tree']['classes'][$this->_sections['tree']['index']]; ?>
<?php echo $this->_tpl_vars['class_tree']['distance'][$this->_sections['tree']['index']]; ?>
<?php endfor; endif; ?></pre>
	
		<?php if ($this->_tpl_vars['conflicts']['conflict_type']): ?>
			<hr class="separator" />
			<div><span class="warning">Conflicts with classes:</span><br /> 
			<?php if (isset($this->_sections['me'])) unset($this->_sections['me']);
$this->_sections['me']['name'] = 'me';
$this->_sections['me']['loop'] = is_array($this->_tpl_vars['conflicts']['conflicts']) ? count($this->_tpl_vars['conflicts']['conflicts']) : max(0, (int)$this->_tpl_vars['conflicts']['conflicts']);
$this->_sections['me']['show'] = true;
$this->_sections['me']['max'] = $this->_sections['me']['loop'];
$this->_sections['me']['step'] = 1;
$this->_sections['me']['start'] = $this->_sections['me']['step'] > 0 ? 0 : $this->_sections['me']['loop']-1;
if ($this->_sections['me']['show']) {
    $this->_sections['me']['total'] = $this->_sections['me']['loop'];
    if ($this->_sections['me']['total'] == 0)
        $this->_sections['me']['show'] = false;
} else
    $this->_sections['me']['total'] = 0;
if ($this->_sections['me']['show']):

            for ($this->_sections['me']['index'] = $this->_sections['me']['start'], $this->_sections['me']['iteration'] = 1;
                 $this->_sections['me']['iteration'] <= $this->_sections['me']['total'];
                 $this->_sections['me']['index'] += $this->_sections['me']['step'], $this->_sections['me']['iteration']++):
$this->_sections['me']['rownum'] = $this->_sections['me']['iteration'];
$this->_sections['me']['index_prev'] = $this->_sections['me']['index'] - $this->_sections['me']['step'];
$this->_sections['me']['index_next'] = $this->_sections['me']['index'] + $this->_sections['me']['step'];
$this->_sections['me']['first']      = ($this->_sections['me']['iteration'] == 1);
$this->_sections['me']['last']       = ($this->_sections['me']['iteration'] == $this->_sections['me']['total']);
?>
				<?php echo $this->_tpl_vars['conflicts']['conflicts'][$this->_sections['me']['index']]; ?>
<br />
			<?php endfor; endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php if ($this->_tpl_vars['children']): ?>
	<a name="sec-descendents"></a>
	<div class="info-box">
		<div class="info-box-title">Direct descendents</div>
		<div class="nav-bar">
			<a href="#sec-description">Description</a> |
			<span class="disabled">Descendents</span>
			<?php if ($this->_tpl_vars['vars'] || $this->_tpl_vars['ivars'] || $this->_tpl_vars['methods'] || $this->_tpl_vars['imethods']): ?>|<?php endif; ?>
			<?php if ($this->_tpl_vars['vars'] || $this->_tpl_vars['ivars']): ?>
				<?php if ($this->_tpl_vars['vars']): ?>
					<a href="#sec-var-summary">Vars</a> (<a href="#sec-vars">details</a>)
				<?php else: ?>
					<a href="#sec-vars">Vars</a>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['methods'] || $this->_tpl_vars['imethods']): ?>|<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['methods'] || $this->_tpl_vars['imethods']): ?>
				<?php if ($this->_tpl_vars['methods']): ?>
					<a href="#sec-method-summary">Methods</a> (<a href="#sec-methods">details</a>)
				<?php else: ?>
					<a href="#sec-methods">Methods</a>
				<?php endif; ?>			
			<?php endif; ?>
		</div>
		<div class="info-box-body">
			<table cellpadding="2" cellspacing="0" class="class-table">
				<tr>
					<th class="class-table-header">Class</th>
					<th class="class-table-header">Description</th>
				</tr>
				<?php if (isset($this->_sections['kids'])) unset($this->_sections['kids']);
$this->_sections['kids']['name'] = 'kids';
$this->_sections['kids']['loop'] = is_array($this->_tpl_vars['children']) ? count($this->_tpl_vars['children']) : max(0, (int)$this->_tpl_vars['children']);
$this->_sections['kids']['show'] = true;
$this->_sections['kids']['max'] = $this->_sections['kids']['loop'];
$this->_sections['kids']['step'] = 1;
$this->_sections['kids']['start'] = $this->_sections['kids']['step'] > 0 ? 0 : $this->_sections['kids']['loop']-1;
if ($this->_sections['kids']['show']) {
    $this->_sections['kids']['total'] = $this->_sections['kids']['loop'];
    if ($this->_sections['kids']['total'] == 0)
        $this->_sections['kids']['show'] = false;
} else
    $this->_sections['kids']['total'] = 0;
if ($this->_sections['kids']['show']):

            for ($this->_sections['kids']['index'] = $this->_sections['kids']['start'], $this->_sections['kids']['iteration'] = 1;
                 $this->_sections['kids']['iteration'] <= $this->_sections['kids']['total'];
                 $this->_sections['kids']['index'] += $this->_sections['kids']['step'], $this->_sections['kids']['iteration']++):
$this->_sections['kids']['rownum'] = $this->_sections['kids']['iteration'];
$this->_sections['kids']['index_prev'] = $this->_sections['kids']['index'] - $this->_sections['kids']['step'];
$this->_sections['kids']['index_next'] = $this->_sections['kids']['index'] + $this->_sections['kids']['step'];
$this->_sections['kids']['first']      = ($this->_sections['kids']['iteration'] == 1);
$this->_sections['kids']['last']       = ($this->_sections['kids']['iteration'] == $this->_sections['kids']['total']);
?>
				<tr>
					<td style="padding-right: 2em; white-space: nowrap">
						<img src="<?php echo $this->_tpl_vars['subdir']; ?>
media/images/<?php if ($this->_tpl_vars['children'][$this->_sections['kids']['index']]['abstract']): ?>Abstract<?php endif; ?><?php if ($this->_tpl_vars['children'][$this->_sections['kids']['index']]['access'] == 'private'): ?>Private<?php endif; ?>Class.png"
								 alt="<?php if ($this->_tpl_vars['children'][$this->_sections['kids']['index']]['abstract']): ?>Abstract<?php endif; ?><?php if ($this->_tpl_vars['children'][$this->_sections['kids']['index']]['access'] == 'private'): ?>Private<?php endif; ?> class"
								 title="<?php if ($this->_tpl_vars['children'][$this->_sections['kids']['index']]['abstract']): ?>Abstract<?php endif; ?><?php if ($this->_tpl_vars['children'][$this->_sections['kids']['index']]['access'] == 'private'): ?>Private<?php endif; ?> class"
								 style="vertical-align: center"/>
						<?php echo $this->_tpl_vars['children'][$this->_sections['kids']['index']]['link']; ?>

					</td>
					<td>
					<?php if ($this->_tpl_vars['children'][$this->_sections['kids']['index']]['sdesc']): ?>
						<?php echo $this->_tpl_vars['children'][$this->_sections['kids']['index']]['sdesc']; ?>

					<?php else: ?>
						<?php echo $this->_tpl_vars['children'][$this->_sections['kids']['index']]['desc']; ?>

					<?php endif; ?>
					</td>
				</tr>
				<?php endfor; endif; ?>
			</table>
		</div>
	</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['vars']): ?>
	<a name="sec-var-summary"></a>
	<div class="info-box">
		<div class="info-box-title">Variable Summary</span></div>
		<div class="nav-bar">
			<a href="#sec-description">Description</a> |
			<?php if ($this->_tpl_vars['children']): ?>
				<a href="#sec-descendents">Descendents</a> |
			<?php endif; ?>
			<span class="disabled">Vars</span> (<a href="#sec-vars">details</a>)
			<?php if ($this->_tpl_vars['methods'] || $this->_tpl_vars['imethods']): ?>
				| 
				<?php if ($this->_tpl_vars['methods']): ?>
					<a href="#sec-method-summary">Methods</a> (<a href="#sec-methods">details</a>)
				<?php else: ?>
					<a href="#sec-methods">Methods</a>
				<?php endif; ?>			
			<?php endif; ?>
		</div>
		<div class="info-box-body">
			<div class="var-summary">
				<?php if (isset($this->_sections['vars'])) unset($this->_sections['vars']);
$this->_sections['vars']['name'] = 'vars';
$this->_sections['vars']['loop'] = is_array($this->_tpl_vars['vars']) ? count($this->_tpl_vars['vars']) : max(0, (int)$this->_tpl_vars['vars']);
$this->_sections['vars']['show'] = true;
$this->_sections['vars']['max'] = $this->_sections['vars']['loop'];
$this->_sections['vars']['step'] = 1;
$this->_sections['vars']['start'] = $this->_sections['vars']['step'] > 0 ? 0 : $this->_sections['vars']['loop']-1;
if ($this->_sections['vars']['show']) {
    $this->_sections['vars']['total'] = $this->_sections['vars']['loop'];
    if ($this->_sections['vars']['total'] == 0)
        $this->_sections['vars']['show'] = false;
} else
    $this->_sections['vars']['total'] = 0;
if ($this->_sections['vars']['show']):

            for ($this->_sections['vars']['index'] = $this->_sections['vars']['start'], $this->_sections['vars']['iteration'] = 1;
                 $this->_sections['vars']['iteration'] <= $this->_sections['vars']['total'];
                 $this->_sections['vars']['index'] += $this->_sections['vars']['step'], $this->_sections['vars']['iteration']++):
$this->_sections['vars']['rownum'] = $this->_sections['vars']['iteration'];
$this->_sections['vars']['index_prev'] = $this->_sections['vars']['index'] - $this->_sections['vars']['step'];
$this->_sections['vars']['index_next'] = $this->_sections['vars']['index'] + $this->_sections['vars']['step'];
$this->_sections['vars']['first']      = ($this->_sections['vars']['iteration'] == 1);
$this->_sections['vars']['last']       = ($this->_sections['vars']['iteration'] == $this->_sections['vars']['total']);
?>
				<div class="var-title">
					<img src="<?php echo $this->_tpl_vars['subdir']; ?>
media/images/<?php if ($this->_tpl_vars['vars'][$this->_sections['vars']['index']]['access'] == 'private'): ?>PrivateVariable<?php else: ?>Variable<?php endif; ?>.png" alt=" " />
					<span class="var-type"><?php echo $this->_tpl_vars['vars'][$this->_sections['vars']['index']]['var_type']; ?>
</span>
					<a href="#<?php echo $this->_tpl_vars['vars'][$this->_sections['vars']['index']]['var_name']; ?>
" title="details" class="var-name"><?php echo $this->_tpl_vars['vars'][$this->_sections['vars']['index']]['var_name']; ?>
</a>
				</div>
				<?php endfor; endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['methods']): ?>
	<a name="sec-method-summary"></a>
	<div class="info-box">
		<div class="info-box-title">Method Summary</span></div>
		<div class="nav-bar">
			<a href="#sec-description">Description</a> |
			<?php if ($this->_tpl_vars['children']): ?>
				<a href="#sec-descendents">Descendents</a> |
			<?php endif; ?>
			<?php if ($this->_tpl_vars['vars'] || $this->_tpl_vars['ivars']): ?>
				<?php if ($this->_tpl_vars['vars']): ?>
					<a href="#sec-var-summary">Vars</a> (<a href="#sec-vars">details</a>)
				<?php else: ?>
					<a href="#sec-vars">Vars</a>
				<?php endif; ?> 
				|
			<?php endif; ?>
			<span class="disabled">Methods</span> (<a href="#sec-methods">details</a>)
		</div>
		<div class="info-box-body">			
			<div class="method-summary">
				<?php if (isset($this->_sections['methods'])) unset($this->_sections['methods']);
$this->_sections['methods']['name'] = 'methods';
$this->_sections['methods']['loop'] = is_array($this->_tpl_vars['methods']) ? count($this->_tpl_vars['methods']) : max(0, (int)$this->_tpl_vars['methods']);
$this->_sections['methods']['show'] = true;
$this->_sections['methods']['max'] = $this->_sections['methods']['loop'];
$this->_sections['methods']['step'] = 1;
$this->_sections['methods']['start'] = $this->_sections['methods']['step'] > 0 ? 0 : $this->_sections['methods']['loop']-1;
if ($this->_sections['methods']['show']) {
    $this->_sections['methods']['total'] = $this->_sections['methods']['loop'];
    if ($this->_sections['methods']['total'] == 0)
        $this->_sections['methods']['show'] = false;
} else
    $this->_sections['methods']['total'] = 0;
if ($this->_sections['methods']['show']):

            for ($this->_sections['methods']['index'] = $this->_sections['methods']['start'], $this->_sections['methods']['iteration'] = 1;
                 $this->_sections['methods']['iteration'] <= $this->_sections['methods']['total'];
                 $this->_sections['methods']['index'] += $this->_sections['methods']['step'], $this->_sections['methods']['iteration']++):
$this->_sections['methods']['rownum'] = $this->_sections['methods']['iteration'];
$this->_sections['methods']['index_prev'] = $this->_sections['methods']['index'] - $this->_sections['methods']['step'];
$this->_sections['methods']['index_next'] = $this->_sections['methods']['index'] + $this->_sections['methods']['step'];
$this->_sections['methods']['first']      = ($this->_sections['methods']['iteration'] == 1);
$this->_sections['methods']['last']       = ($this->_sections['methods']['iteration'] == $this->_sections['methods']['total']);
?>				
				<div class="method-definition">
					<img src="<?php echo $this->_tpl_vars['subdir']; ?>
media/images/<?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['constructor']): ?>Constructor<?php elseif ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['destructor']): ?>Destructor<?php elseif ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['access'] == 'private'): ?><?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['abstract']): ?>Abstract<?php endif; ?>PrivateMethod<?php else: ?><?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['abstract']): ?>Abstract<?php endif; ?>Method<?php endif; ?>.png" alt=" "/>
					<?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['function_return']): ?>
						<span class="method-result"><?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['function_return']; ?>
</span>
					<?php endif; ?>
					<a href="#<?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['function_name']; ?>
" title="details" class="method-name"><?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['returnsref']): ?>&amp;<?php endif; ?><?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['function_name']; ?>
</a>
					<?php if (count ( $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params'] )): ?>
						(<?php if (isset($this->_sections['params'])) unset($this->_sections['params']);
$this->_sections['params']['name'] = 'params';
$this->_sections['params']['loop'] = is_array($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params']) ? count($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params']) : max(0, (int)$this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params']);
$this->_sections['params']['show'] = true;
$this->_sections['params']['max'] = $this->_sections['params']['loop'];
$this->_sections['params']['step'] = 1;
$this->_sections['params']['start'] = $this->_sections['params']['step'] > 0 ? 0 : $this->_sections['params']['loop']-1;
if ($this->_sections['params']['show']) {
    $this->_sections['params']['total'] = $this->_sections['params']['loop'];
    if ($this->_sections['params']['total'] == 0)
        $this->_sections['params']['show'] = false;
} else
    $this->_sections['params']['total'] = 0;
if ($this->_sections['params']['show']):

            for ($this->_sections['params']['index'] = $this->_sections['params']['start'], $this->_sections['params']['iteration'] = 1;
                 $this->_sections['params']['iteration'] <= $this->_sections['params']['total'];
                 $this->_sections['params']['index'] += $this->_sections['params']['step'], $this->_sections['params']['iteration']++):
$this->_sections['params']['rownum'] = $this->_sections['params']['iteration'];
$this->_sections['params']['index_prev'] = $this->_sections['params']['index'] - $this->_sections['params']['step'];
$this->_sections['params']['index_next'] = $this->_sections['params']['index'] + $this->_sections['params']['step'];
$this->_sections['params']['first']      = ($this->_sections['params']['iteration'] == 1);
$this->_sections['params']['last']       = ($this->_sections['params']['iteration'] == $this->_sections['params']['total']);
?><?php if ($this->_sections['params']['iteration'] != 1): ?>, <?php endif; ?><?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params'][$this->_sections['params']['index']]['default']): ?>[<?php endif; ?><span class="var-type"><?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params'][$this->_sections['params']['index']]['type']; ?>
</span>&nbsp;<span class="var-name"><?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params'][$this->_sections['params']['index']]['name']; ?>
</span><?php if ($this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params'][$this->_sections['params']['index']]['default']): ?> = <span class="var-default"><?php echo $this->_tpl_vars['methods'][$this->_sections['methods']['index']]['ifunction_call']['params'][$this->_sections['params']['index']]['default']; ?>
</span>]<?php endif; ?><?php endfor; endif; ?>)
					<?php else: ?>
					()
					<?php endif; ?>
				</div>
				<?php endfor; endif; ?>
			</div>
		</div>
	</div>		
<?php endif; ?>

<?php if ($this->_tpl_vars['vars'] || $this->_tpl_vars['ivars']): ?>
	<a name="sec-vars"></a>
	<div class="info-box">
		<div class="info-box-title">Variables</div>
		<div class="nav-bar">
			<a href="#sec-description">Description</a> |
			<?php if ($this->_tpl_vars['children']): ?>
				<a href="#sec-descendents">Descendents</a> |
			<?php endif; ?>
			<?php if ($this->_tpl_vars['methods']): ?>
				<a href="#sec-var-summary">Vars</a> (<span class="disabled">details</span>)
			<?php else: ?>
				<span class="disabled">Vars</span>
			<?php endif; ?>			
			
			<?php if ($this->_tpl_vars['methods'] || $this->_tpl_vars['imethods']): ?>
				| 
				<?php if ($this->_tpl_vars['methods']): ?>
					<a href="#sec-method-summary">Methods</a> (<a href="#sec-methods">details</a>)
				<?php else: ?>
					<a href="#sec-methods">Methods</a>
				<?php endif; ?>			
			<?php endif; ?>
		</div>
		<div class="info-box-body">
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("var.tpl", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<?php if ($this->_tpl_vars['ivars']): ?>
				<h4>Inherited Variables</h4>
				<A NAME='inherited_vars'><!-- --></A>
				<?php if (isset($this->_sections['ivars'])) unset($this->_sections['ivars']);
$this->_sections['ivars']['name'] = 'ivars';
$this->_sections['ivars']['loop'] = is_array($this->_tpl_vars['ivars']) ? count($this->_tpl_vars['ivars']) : max(0, (int)$this->_tpl_vars['ivars']);
$this->_sections['ivars']['show'] = true;
$this->_sections['ivars']['max'] = $this->_sections['ivars']['loop'];
$this->_sections['ivars']['step'] = 1;
$this->_sections['ivars']['start'] = $this->_sections['ivars']['step'] > 0 ? 0 : $this->_sections['ivars']['loop']-1;
if ($this->_sections['ivars']['show']) {
    $this->_sections['ivars']['total'] = $this->_sections['ivars']['loop'];
    if ($this->_sections['ivars']['total'] == 0)
        $this->_sections['ivars']['show'] = false;
} else
    $this->_sections['ivars']['total'] = 0;
if ($this->_sections['ivars']['show']):

            for ($this->_sections['ivars']['index'] = $this->_sections['ivars']['start'], $this->_sections['ivars']['iteration'] = 1;
                 $this->_sections['ivars']['iteration'] <= $this->_sections['ivars']['total'];
                 $this->_sections['ivars']['index'] += $this->_sections['ivars']['step'], $this->_sections['ivars']['iteration']++):
$this->_sections['ivars']['rownum'] = $this->_sections['ivars']['iteration'];
$this->_sections['ivars']['index_prev'] = $this->_sections['ivars']['index'] - $this->_sections['ivars']['step'];
$this->_sections['ivars']['index_next'] = $this->_sections['ivars']['index'] + $this->_sections['ivars']['step'];
$this->_sections['ivars']['first']      = ($this->_sections['ivars']['iteration'] == 1);
$this->_sections['ivars']['last']       = ($this->_sections['ivars']['iteration'] == $this->_sections['ivars']['total']);
?>
					<p>Inherited from <span class="classname"><?php echo $this->_tpl_vars['ivars'][$this->_sections['ivars']['index']]['parent_class']; ?>
</span></p>
					<blockquote>
						<?php if (isset($this->_sections['ivars2'])) unset($this->_sections['ivars2']);
$this->_sections['ivars2']['name'] = 'ivars2';
$this->_sections['ivars2']['loop'] = is_array($this->_tpl_vars['ivars'][$this->_sections['ivars']['index']]['ivars']) ? count($this->_tpl_vars['ivars'][$this->_sections['ivars']['index']]['ivars']) : max(0, (int)$this->_tpl_vars['ivars'][$this->_sections['ivars']['index']]['ivars']);
$this->_sections['ivars2']['show'] = true;
$this->_sections['ivars2']['max'] = $this->_sections['ivars2']['loop'];
$this->_sections['ivars2']['step'] = 1;
$this->_sections['ivars2']['start'] = $this->_sections['ivars2']['step'] > 0 ? 0 : $this->_sections['ivars2']['loop']-1;
if ($this->_sections['ivars2']['show']) {
    $this->_sections['ivars2']['total'] = $this->_sections['ivars2']['loop'];
    if ($this->_sections['ivars2']['total'] == 0)
        $this->_sections['ivars2']['show'] = false;
} else
    $this->_sections['ivars2']['total'] = 0;
if ($this->_sections['ivars2']['show']):

            for ($this->_sections['ivars2']['index'] = $this->_sections['ivars2']['start'], $this->_sections['ivars2']['iteration'] = 1;
                 $this->_sections['ivars2']['iteration'] <= $this->_sections['ivars2']['total'];
                 $this->_sections['ivars2']['index'] += $this->_sections['ivars2']['step'], $this->_sections['ivars2']['iteration']++):
$this->_sections['ivars2']['rownum'] = $this->_sections['ivars2']['iteration'];
$this->_sections['ivars2']['index_prev'] = $this->_sections['ivars2']['index'] - $this->_sections['ivars2']['step'];
$this->_sections['ivars2']['index_next'] = $this->_sections['ivars2']['index'] + $this->_sections['ivars2']['step'];
$this->_sections['ivars2']['first']      = ($this->_sections['ivars2']['iteration'] == 1);
$this->_sections['ivars2']['last']       = ($this->_sections['ivars2']['iteration'] == $this->_sections['ivars2']['total']);
?>
							<img src="<?php echo $this->_tpl_vars['subdir']; ?>
media/images/<?php if ($this->_tpl_vars['ivars'][$this->_sections['ivars']['index']]['ivars'][$this->_sections['ivars2']['index']]['access'] == 'private'): ?>PrivateVariable<?php else: ?>Variable<?php endif; ?>.png" />
							<span class="var-title">
								<span class="var-name"><?php echo $this->_tpl_vars['ivars'][$this->_sections['ivars']['index']]['ivars'][$this->_sections['ivars2']['index']]['link']; ?>
</span><?php if ($this->_tpl_vars['ivars'][$this->_sections['ivars']['index']]['ivars'][$this->_sections['ivars2']['index']]['ivar_sdesc']): ?>: <?php echo $this->_tpl_vars['ivars'][$this->_sections['ivars']['index']]['ivars'][$this->_sections['ivars2']['index']]['ivar_sdesc']; ?>
<?php endif; ?><br>
							</span>
						<?php endfor; endif; ?>
					</blockquote> 
				<?php endfor; endif; ?>
			<?php endif; ?>			
		</div>
	</div>
<?php endif; ?>
	
<?php if ($this->_tpl_vars['methods'] || $this->_tpl_vars['imethods']): ?>
	<a name="sec-methods"></a>
	<div class="info-box">
		<div class="info-box-title">Methods</div>
		<div class="nav-bar">
			<a href="#sec-description">Description</a> |
			<?php if ($this->_tpl_vars['children']): ?>
				<a href="#sec-descendents">Descendents</a> |
			<?php endif; ?>
			<?php if ($this->_tpl_vars['vars'] || $this->_tpl_vars['ivars']): ?>
				<?php if ($this->_tpl_vars['vars']): ?>
					<a href="#sec-var-summary">Vars</a> (<a href="#sec-vars">details</a>)
				<?php else: ?>
					<a href="#sec-vars">Vars</a>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['methods']): ?>
				<a href="#sec-method-summary">Methods</a> (<span class="disabled">details</span>)
			<?php else: ?>
				<span class="disabled">Methods</span>
			<?php endif; ?>			
		</div>
		<div class="info-box-body">
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("method.tpl", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<?php if ($this->_tpl_vars['imethods']): ?>
				<h4>Inherited Methods</h4>
				<a name='inherited_methods'><!-- --></a>	
				<?php if (isset($this->_sections['imethods'])) unset($this->_sections['imethods']);
$this->_sections['imethods']['name'] = 'imethods';
$this->_sections['imethods']['loop'] = is_array($this->_tpl_vars['imethods']) ? count($this->_tpl_vars['imethods']) : max(0, (int)$this->_tpl_vars['imethods']);
$this->_sections['imethods']['show'] = true;
$this->_sections['imethods']['max'] = $this->_sections['imethods']['loop'];
$this->_sections['imethods']['step'] = 1;
$this->_sections['imethods']['start'] = $this->_sections['imethods']['step'] > 0 ? 0 : $this->_sections['imethods']['loop']-1;
if ($this->_sections['imethods']['show']) {
    $this->_sections['imethods']['total'] = $this->_sections['imethods']['loop'];
    if ($this->_sections['imethods']['total'] == 0)
        $this->_sections['imethods']['show'] = false;
} else
    $this->_sections['imethods']['total'] = 0;
if ($this->_sections['imethods']['show']):

            for ($this->_sections['imethods']['index'] = $this->_sections['imethods']['start'], $this->_sections['imethods']['iteration'] = 1;
                 $this->_sections['imethods']['iteration'] <= $this->_sections['imethods']['total'];
                 $this->_sections['imethods']['index'] += $this->_sections['imethods']['step'], $this->_sections['imethods']['iteration']++):
$this->_sections['imethods']['rownum'] = $this->_sections['imethods']['iteration'];
$this->_sections['imethods']['index_prev'] = $this->_sections['imethods']['index'] - $this->_sections['imethods']['step'];
$this->_sections['imethods']['index_next'] = $this->_sections['imethods']['index'] + $this->_sections['imethods']['step'];
$this->_sections['imethods']['first']      = ($this->_sections['imethods']['iteration'] == 1);
$this->_sections['imethods']['last']       = ($this->_sections['imethods']['iteration'] == $this->_sections['imethods']['total']);
?>
					<!-- =========== Summary =========== -->
					<p>Inherited From <span class="classname"><?php echo $this->_tpl_vars['imethods'][$this->_sections['imethods']['index']]['parent_class']; ?>
</span></p>
					<blockquote>
						<?php if (isset($this->_sections['im2'])) unset($this->_sections['im2']);
$this->_sections['im2']['name'] = 'im2';
$this->_sections['im2']['loop'] = is_array($this->_tpl_vars['imethods'][$this->_sections['imethods']['index']]['imethods']) ? count($this->_tpl_vars['imethods'][$this->_sections['imethods']['index']]['imethods']) : max(0, (int)$this->_tpl_vars['imethods'][$this->_sections['imethods']['index']]['imethods']);
$this->_sections['im2']['show'] = true;
$this->_sections['im2']['max'] = $this->_sections['im2']['loop'];
$this->_sections['im2']['step'] = 1;
$this->_sections['im2']['start'] = $this->_sections['im2']['step'] > 0 ? 0 : $this->_sections['im2']['loop']-1;
if ($this->_sections['im2']['show']) {
    $this->_sections['im2']['total'] = $this->_sections['im2']['loop'];
    if ($this->_sections['im2']['total'] == 0)
        $this->_sections['im2']['show'] = false;
} else
    $this->_sections['im2']['total'] = 0;
if ($this->_sections['im2']['show']):

            for ($this->_sections['im2']['index'] = $this->_sections['im2']['start'], $this->_sections['im2']['iteration'] = 1;
                 $this->_sections['im2']['iteration'] <= $this->_sections['im2']['total'];
                 $this->_sections['im2']['index'] += $this->_sections['im2']['step'], $this->_sections['im2']['iteration']++):
$this->_sections['im2']['rownum'] = $this->_sections['im2']['iteration'];
$this->_sections['im2']['index_prev'] = $this->_sections['im2']['index'] - $this->_sections['im2']['step'];
$this->_sections['im2']['index_next'] = $this->_sections['im2']['index'] + $this->_sections['im2']['step'];
$this->_sections['im2']['first']      = ($this->_sections['im2']['iteration'] == 1);
$this->_sections['im2']['last']       = ($this->_sections['im2']['iteration'] == $this->_sections['im2']['total']);
?>
							<img src="<?php echo $this->_tpl_vars['subdir']; ?>
media/images/<?php if ($this->_tpl_vars['imethods'][$this->_sections['imethods']['index']]['imethods'][$this->_sections['im2']['index']]['constructor']): ?>Constructor<?php elseif ($this->_tpl_vars['imethods'][$this->_sections['imethods']['index']]['imethods'][$this->_sections['im2']['index']]['destructor']): ?>Destructor<?php elseif ($this->_tpl_vars['imethods'][$this->_sections['imethods']['index']]['imethods'][$this->_sections['im2']['index']]['access'] == 'private'): ?><?php if ($this->_tpl_vars['imethods'][$this->_sections['imethods']['index']]['imethods'][$this->_sections['im2']['index']]['abstract']): ?>Abstract<?php endif; ?>PrivateMethod<?php else: ?><?php if ($this->_tpl_vars['imethods'][$this->_sections['imethods']['index']]['imethods'][$this->_sections['im2']['index']]['abstract']): ?>Abstract<?php endif; ?>Method<?php endif; ?>.png" alt=" "/>
							<span class="method-name"><?php echo $this->_tpl_vars['imethods'][$this->_sections['imethods']['index']]['imethods'][$this->_sections['im2']['index']]['link']; ?>
</span><?php if ($this->_tpl_vars['imethods'][$this->_sections['imethods']['index']]['imethods'][$this->_sections['im2']['index']]['ifunction_sdesc']): ?>: <?php echo $this->_tpl_vars['imethods'][$this->_sections['imethods']['index']]['imethods'][$this->_sections['im2']['index']]['ifunction_sdesc']; ?>
<?php endif; ?><br>
						<?php endfor; endif; ?>
					</blockquote>
				<?php endfor; endif; ?>
			<?php endif; ?>			
		</div>
	</div>
<?php endif; ?>
	
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("footer.tpl", array('top3' => true));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
